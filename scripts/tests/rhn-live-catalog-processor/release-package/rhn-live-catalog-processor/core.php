<?php
/** Pure planning helpers for the production catalog processor. */
defined( 'ABSPATH' ) || exit;

function rhn_live_catalog_required_field_errors( $entry ) {
	$errors = array();
	if ( '' === trim( (string) ( $entry['brand'] ?? '' ) ) ) {
		$errors[] = 'Brand is required.';
	}
	$categories = array_values( array_filter( array_map( 'trim', (array) ( $entry['categories'] ?? array() ) ) ) );
	if ( ! $categories || in_array( 'Unknown / Needs Review', $categories, true ) ) {
		$errors[] = 'A resolved primary website category is required.';
	}
	if ( '' === trim( (string) ( $entry['package_size'] ?? '' ) ) ) {
		$errors[] = 'Package size / net contents is required.';
	}
	if ( ! isset( $entry['weight'] ) || ! is_numeric( $entry['weight'] ) || (float) $entry['weight'] <= 0 ) {
		$errors[] = 'A positive packaged shipping weight is required.';
	}
	if ( ! in_array( (string) ( $entry['weight_unit'] ?? '' ), array( 'oz', 'lb', 'g', 'kg' ), true ) ) {
		$errors[] = 'An approved packaged shipping-weight unit is required.';
	}
	if ( '' === trim( wp_strip_all_tags( (string) ( $entry['short_description'] ?? '' ) ) ) ) {
		$errors[] = 'Short description is required.';
	}
	return $errors;
}

function rhn_live_catalog_semantic_text( $value ) {
	$value = html_entity_decode( (string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	return str_replace( array( "\r\n", "\r" ), "\n", $value );
}

function rhn_live_catalog_image_identity( $url ) {
	$url = (string) $url;
	if ( function_exists( 'rhn_catalog_google_drive_file_id' ) ) {
		$id = rhn_catalog_google_drive_file_id( $url );
		if ( '' !== $id ) { return 'drive:' . $id; }
	}
	return '' === $url ? '' : 'url:' . $url;
}

function rhn_live_catalog_image_identities( $urls ) {
	return array_values( array_map( 'rhn_live_catalog_image_identity', (array) $urls ) );
}

/**
 * Plan a complete, bounded Sheet reconciliation from abstract product states.
 *
 * Each action has action, row, source and (for Publish) entry. Each state has
 * id, type, status, visibility, managed and content_changes.
 */
function rhn_live_catalog_plan( $actions, $states, $max_changes = 10, $max_creations = 3 ) {
	$plan = array(
		'update'                    => array(),
		'unchanged'                 => array(),
		'create_quarantine'         => array(),
		'deferred'                  => array(),
		'missing_remove_satisfied'  => array(),
		'unmanaged'                 => array(),
		'unsupported_variations'    => array(),
		'errors'                    => array(),
	);
	foreach ( (array) $actions as $sku => $action ) {
		$is_publish = 'Publish' === (string) ( $action['action'] ?? '' );
		$is_remove  = 'Remove from Website' === (string) ( $action['action'] ?? '' );
		if ( ! $is_publish && ! $is_remove ) {
			$plan['errors'][ $sku ] = 'Unsupported Sheet action.';
			continue;
		}
		if ( $is_publish ) {
			$required_errors = rhn_live_catalog_required_field_errors( (array) ( $action['entry'] ?? array() ) );
			if ( $required_errors ) {
				$plan['errors'][ $sku ] = implode( ' ', $required_errors );
				continue;
			}
		}
		if ( ! isset( $states[ $sku ] ) ) {
			if ( $is_remove ) {
				$plan['missing_remove_satisfied'][ $sku ] = $action;
			} elseif ( '' === trim( (string) ( $action['entry']['name'] ?? '' ) ) ) {
				$plan['errors'][ $sku ] = 'A resolved product name is required before creating a Draft/hidden shell.';
			} else {
				$plan['create_quarantine'][ $sku ] = $action;
			}
			continue;
		}
		$state = (array) $states[ $sku ];
		if ( ! empty( $state['error'] ) ) {
			$plan['errors'][ $sku ] = (string) $state['error'];
			continue;
		}
		if ( 'variation' === (string) ( $state['type'] ?? '' ) ) {
			$plan['unsupported_variations'][ $sku ] = array_merge( $action, array(
				'id' => (int) ( $state['id'] ?? 0 ),
				'reason' => 'Variation rows require an explicit parent/attribute model; no variation write is inferred.',
			) );
			continue;
		}
		if ( empty( $state['managed'] ) ) {
			$plan['unmanaged'][ $sku ] = array_merge( $action, array(
				'id' => (int) ( $state['id'] ?? 0 ),
				'reason' => 'Exact SKU exists but is outside the enrolled website catalog scope.',
			) );
			continue;
		}
		$target_status     = $is_publish ? 'publish' : 'draft';
		$target_visibility = $is_publish ? 'visible' : 'hidden';
		$item = array_merge( $action, array(
			'id'                 => (int) ( $state['id'] ?? 0 ),
			'from_status'        => (string) ( $state['status'] ?? '' ),
			'from_visibility'    => (string) ( $state['visibility'] ?? '' ),
			'to_status'          => $target_status,
			'to_visibility'      => $target_visibility,
			'content_changes'    => array_values( (array) ( $state['content_changes'] ?? array() ) ),
		) );
		if (
			$item['from_status'] === $target_status
			&& $item['from_visibility'] === $target_visibility
			&& ! $item['content_changes']
		) {
			$plan['unchanged'][ $sku ] = $item;
		} else {
			$plan['update'][ $sku ] = $item;
		}
	}
	// Keep a conservative creation tranche without letting the fourth valid
	// product block the first three. Overflow remains explicit and is drained by
	// the server-side continuation queue, never silently discarded.
	$max_creations = max( 1, (int) $max_creations );
	$creation_candidates = array();
	foreach ( $plan['create_quarantine'] as $sku => $item ) {
		$creation_candidates[] = array( 'sku'=>(string)$sku, 'row'=>(int)($item['row']??0), 'item'=>$item );
	}
	usort( $creation_candidates, static function( $left, $right ) {
		return ( $left['row'] <=> $right['row'] ) ?: strcmp( $left['sku'], $right['sku'] );
	} );
	foreach ( array_slice( $creation_candidates, $max_creations ) as $candidate ) {
		unset( $plan['create_quarantine'][ $candidate['sku'] ] );
		$plan['deferred'][ $candidate['sku'] ] = array_merge( $candidate['item'], array(
			'deferred_from'=>'create_quarantine',
			'reason'=>'Deferred to the next short server-side creation tranche; the per-tranche creation limit is ' . $max_creations . '.',
		) );
	}
	return rhn_live_catalog_bound_plan( $plan, $max_changes );
}

/** Keep each write run bounded while explicitly carrying the remainder forward. */
function rhn_live_catalog_bound_plan( $plan, $max_changes = 10 ) {
	$max_changes = (int) $max_changes;
	if ( $max_changes < 1 ) {
		$plan['errors']['_change_scope'] = 'Fail-closed change limit is invalid.';
		return $plan;
	}
	$plan['deferred'] = (array) ( $plan['deferred'] ?? array() );
	$candidates = array();
	foreach ( array( 'create_quarantine', 'update' ) as $bucket ) {
		foreach ( (array) ( $plan[ $bucket ] ?? array() ) as $sku => $item ) {
			$candidates[] = array( 'bucket'=>$bucket, 'sku'=>(string)$sku, 'row'=>(int)($item['row']??0), 'item'=>$item );
		}
	}
	usort( $candidates, static function( $left, $right ) {
		return ( $left['row'] <=> $right['row'] ) ?: strcmp( $left['sku'], $right['sku'] );
	} );
	foreach ( array_slice( $candidates, $max_changes ) as $candidate ) {
		$sku = $candidate['sku'];
		unset( $plan[ $candidate['bucket'] ][ $sku ] );
		$plan['deferred'][ $sku ] = array_merge( $candidate['item'], array(
			'deferred_from'=>$candidate['bucket'],
			'reason'=>'Deferred to the next short server-side transaction in this drain; no more than ' . $max_changes . ' product may change per job.',
		) );
	}
	return $plan;
}

function rhn_live_catalog_plan_fingerprint( $plan ) {
	$parts = array();
	foreach ( array( 'update', 'unchanged', 'create_quarantine', 'deferred', 'missing_remove_satisfied', 'unmanaged', 'unsupported_variations', 'errors' ) as $bucket ) {
		foreach ( (array) ( $plan[ $bucket ] ?? array() ) as $sku => $item ) {
			$parts[] = $bucket . ':' . $sku . ':' . wp_json_encode( $item );
		}
	}
	sort( $parts, SORT_STRING );
	return hash( 'sha256', implode( '|', $parts ) );
}

function rhn_live_catalog_coverage( $plan ) {
	$errors      = count( (array) ( $plan['errors'] ?? array() ) );
	$unmanaged   = count( (array) ( $plan['unmanaged'] ?? array() ) );
	$variations  = count( (array) ( $plan['unsupported_variations'] ?? array() ) );
	$deferred    = count( (array) ( $plan['deferred'] ?? array() ) );
	$complete    = 0 === $errors && 0 === $unmanaged && 0 === $variations && 0 === $deferred;
	return array(
		'scope' => 'Existing enrolled exact-SKU simple products plus bounded Draft/hidden creation for complete Publish rows.',
		'fully_synchronized' => $complete,
		'result_status' => $errors ? 'failed_closed' : ( $deferred ? 'serial_drain_pending' : ( $complete ? 'fully_reconciled' : 'completed_with_review_exceptions' ) ),
		'limitations' => array(
			'Price and inventory remain owned by Revel/Kosmos and are never sourced from the Sheet.',
			'A newly created product requires a POS price and internally consistent stock controls; positive stock also requires a verified post-quarantine inventory REST update.',
			'Variation rows are reported without mutation until a parent/attribute mapping is explicitly approved.',
		),
	);
}

/**
 * Validate POS-owned values before a Draft/hidden product first becomes public.
 *
 * This is intentionally a pure check. It never normalizes or rewrites the
 * Revel/Kosmos-owned price or inventory values it inspects.
 */
function rhn_live_catalog_first_publication_errors( $regular_price, $stock_quantity, $manage_stock, $backorders, $stock_status ) {
	$errors = array();
	if ( '' === trim( (string) $regular_price ) || ! is_numeric( $regular_price ) ) {
		$errors[] = 'Revel/Kosmos regular price is missing or non-numeric.';
	} elseif ( (float) $regular_price <= 0 ) {
		$errors[] = 'Revel/Kosmos regular price must be greater than zero.';
	}
	if ( null === $stock_quantity || '' === trim( (string) $stock_quantity ) || ! is_numeric( $stock_quantity ) ) {
		$errors[] = 'WooCommerce stock quantity is missing or non-numeric.';
	} elseif ( (float) $stock_quantity < 0 ) {
		$errors[] = 'WooCommerce stock quantity cannot be negative.';
	} else {
		$quantity = (float) $stock_quantity;
		$expected_status = $quantity > 0 ? 'instock' : 'outofstock';
		if ( ! $manage_stock || 'no' !== (string) $backorders || $expected_status !== (string) $stock_status ) {
			$errors[] = 'WooCommerce stock controls are not internally consistent.';
		}
	}
	return $errors;
}

function rhn_live_catalog_is_already_public( $status, $visibility ) {
	return 'publish' === (string) $status && 'visible' === (string) $visibility;
}

/** Next server-side four-hour anchor at 02:30/06:30/10:30 UTC. */
function rhn_live_catalog_next_anchor_at( $now ) {
	$now = (int) $now;
	$candidate = strtotime( gmdate( 'Y-m-d', $now ) . ' 02:30:00 UTC' );
	while ( $candidate <= $now ) {
		$candidate += 4 * HOUR_IN_SECONDS;
	}
	return $candidate;
}
