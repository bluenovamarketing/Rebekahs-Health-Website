<?php
/** Complete, bounded production reconciliation and server-side schedule. */
defined( 'ABSPATH' ) || exit;

function rhn_live_catalog_seed_skus() {
	return array(
		'733739401854','733739423900','733739430700','733739433268','788332037313',
		'788332048517','788332051418','788332054013','788332083815','788332083921',
		'788332128806','788332128905','788332129209','788332155000','788332155703',
		'788332166921','788332167812','788332173806','788332174506','788332174704',
		'788332198601','788332209703','788332215704','788332217302','788332226502',
		'788332227509','788332227806','788332230110','788332230226','788332231308',
	);
}

function rhn_live_catalog_product_is_managed( $product ) {
	return $product instanceof WC_Product && (
		'yes' === (string) $product->get_meta( '_rhn_live_catalog_managed', true, 'edit' )
		|| in_array( (string) $product->get_sku( 'edit' ), rhn_live_catalog_seed_skus(), true )
	);
}

function rhn_live_catalog_find_product( $sku ) {
	$ids = get_posts( array(
		'post_type' => array( 'product', 'product_variation' ), 'post_status' => 'any',
		'fields' => 'ids', 'posts_per_page' => 3, 'meta_key' => '_sku', 'meta_value' => (string) $sku,
		'orderby' => 'ID', 'order' => 'ASC',
	) );
	if ( ! $ids ) {
		return null;
	}
	if ( 1 !== count( $ids ) ) {
		return new WP_Error( 'rhn_live_catalog_duplicate', 'Expected exactly one WooCommerce match for SKU ' . $sku . '; found ' . count( $ids ) . '.' );
	}
	$product = wc_get_product( (int) $ids[0] );
	if ( ! $product || (string) $sku !== (string) $product->get_sku( 'edit' ) ) {
		return new WP_Error( 'rhn_live_catalog_identity', 'Exact WooCommerce identity verification failed for SKU ' . $sku . '.' );
	}
	return $product;
}

function rhn_live_catalog_protected_snapshot( $product ) {
	$out = array();
	foreach ( array( 'sku','regular_price','sale_price','price','stock_quantity','stock_status','manage_stock','backorders' ) as $field ) {
		$getter = 'get_' . $field;
		$out[ $field ] = $product->$getter( 'edit' );
	}
	return $out;
}

function rhn_live_catalog_content_snapshot( $product ) {
	$fields = array();
	foreach ( array( 'sku','regular_price','sale_price','price','stock_quantity','stock_status','manage_stock','backorders','name','description','short_description','weight','category_ids','image_id','gallery_image_ids','status','catalog_visibility' ) as $field ) {
		$getter = 'get_' . $field;
		$fields[ $field ] = $product->$getter( 'edit' );
	}
	$meta_keys = array(
		'_rhn_package_size','_rhn_ingredients','_rhn_allergens','_rhn_supplement_facts',
		'_rhn_directions','_rhn_warnings','_rhn_directions_warnings','_rhn_seo_title',
		'_rhn_seo_description','_seopress_titles_title','_seopress_titles_desc',
		'_rhn_featured_image_source_url','_rhn_gallery_image_source_urls',
		'_rhn_catalog_source_weight','_rhn_source_presentation','_rhn_catalog_approved_source',
		'_rhn_catalog_sync_errors','_rhn_catalog_sheet_receipt','_rhn_catalog_withdrawn_by_sheet',
		'_rhn_catalog_pending_sheet_review','_rhn_live_catalog_managed','_rhn_catalog_quarantine_created_gmt',
		'_rhn_live_catalog_inventory_receipt','_rhn_live_catalog_price_receipt',
	);
	$meta = array();
	foreach ( $meta_keys as $key ) {
		$meta[ $key ] = array( 'exists' => $product->meta_exists( $key ), 'value' => $product->get_meta( $key, true, 'edit' ) );
	}
	$brands = taxonomy_exists( 'product_brand' ) ? wp_get_object_terms( $product->get_id(), 'product_brand', array( 'fields' => 'ids' ) ) : array();
	return array(
		'id' => $product->get_id(), 'sku' => (string) $product->get_sku( 'edit' ),
		'fields' => $fields, 'meta' => $meta,
		'brand_ids' => is_wp_error( $brands ) ? array() : array_map( 'intval', $brands ),
	);
}

function rhn_live_catalog_restore_snapshot( $snapshot ) {
	$product = wc_get_product( (int) ( $snapshot['id'] ?? 0 ) );
	if ( ! $product || (string) ( $snapshot['sku'] ?? '' ) !== (string) $product->get_sku( 'edit' ) ) {
		return false;
	}
	foreach ( (array) ( $snapshot['fields'] ?? array() ) as $field => $value ) {
		$setter = 'set_' . $field;
		if ( is_callable( array( $product, $setter ) ) ) {
			$product->$setter( $value );
		}
	}
	foreach ( (array) ( $snapshot['meta'] ?? array() ) as $key => $record ) {
		if ( ! empty( $record['exists'] ) ) {
			$product->update_meta_data( $key, $record['value'] );
		} else {
			$product->delete_meta_data( $key );
		}
	}
	$product->save();
	if ( taxonomy_exists( 'product_brand' ) ) {
		wp_set_object_terms( $product->get_id(), array_map( 'intval', (array) ( $snapshot['brand_ids'] ?? array() ) ), 'product_brand', false );
	}
	clean_post_cache( $product->get_id() );
	wc_delete_product_transients( $product->get_id() );
	return true;
}

function rhn_live_catalog_content_changes( $product, $entry ) {
	$changes = array();
	foreach ( array( 'name','description','short_description' ) as $field ) {
		if ( array_key_exists( $field, $entry ) ) {
			$getter = 'get_' . $field;
			$target = 'name' === $field ? sanitize_text_field( $entry[ $field ] ) : wp_kses_post( $entry[ $field ] );
			if ( rhn_live_catalog_semantic_text( $target ) !== rhn_live_catalog_semantic_text( $product->$getter( 'edit' ) ) ) {
				$changes[] = $field;
			}
		}
	}
	if ( isset( $entry['weight'], $entry['weight_unit'] ) ) {
		$target = rhn_catalog_convert_weight( $entry['weight'], $entry['weight_unit'] );
		if ( is_wp_error( $target ) ) {
			throw new RuntimeException( $target->get_error_message() );
		}
		if ( abs( (float) $target - (float) $product->get_weight( 'edit' ) ) > 0.000001 ) {
			$changes[] = 'weight';
		}
	}
	if ( isset( $entry['categories'] ) ) {
		$target = rhn_catalog_resolve_term_ids( $entry['categories'], 'product_cat' );
		if ( is_wp_error( $target ) ) {
			throw new RuntimeException( $target->get_error_message() );
		}
		$current = array_map( 'intval', $product->get_category_ids( 'edit' ) ); sort( $current ); sort( $target );
		if ( $current !== $target ) { $changes[] = 'categories'; }
	}
	if ( isset( $entry['brand'] ) ) {
		$target = rhn_catalog_resolve_term_ids( array( $entry['brand'] ), 'product_brand' );
		if ( is_wp_error( $target ) ) { throw new RuntimeException( $target->get_error_message() ); }
		$current = wp_get_object_terms( $product->get_id(), 'product_brand', array( 'fields' => 'ids' ) );
		if ( is_wp_error( $current ) ) { throw new RuntimeException( $current->get_error_message() ); }
		$current = array_map( 'intval', $current ); sort( $current ); sort( $target );
		if ( $current !== $target ) { $changes[] = 'brand'; }
	}
	$meta_map = array(
		'package_size'=>'_rhn_package_size','ingredients'=>'_rhn_ingredients','allergens'=>'_rhn_allergens',
		'supplement_facts'=>'_rhn_supplement_facts','directions'=>'_rhn_directions','warnings'=>'_rhn_warnings',
		'seo_title'=>'_seopress_titles_title','seo_description'=>'_seopress_titles_desc',
	);
	foreach ( $meta_map as $field => $key ) {
		if ( array_key_exists( $field, $entry ) ) {
			$target = in_array( $field, array( 'seo_title','seo_description' ), true ) ? sanitize_text_field( $entry[ $field ] ) : wp_kses_post( $entry[ $field ] );
			if ( $target !== (string) $product->get_meta( $key, true, 'edit' ) ) { $changes[] = $field; }
		}
	}
	if ( isset( $entry['featured_image_url'] ) ) {
		if ( rhn_live_catalog_image_identity( $entry['featured_image_url'] ) !== rhn_live_catalog_image_identity( $product->get_meta( '_rhn_featured_image_source_url', true, 'edit' ) ) || ! $product->get_image_id( 'edit' ) ) { $changes[] = 'featured_image'; }
	}
	if ( isset( $entry['gallery_image_urls'] ) ) {
		$current = (array) $product->get_meta( '_rhn_gallery_image_source_urls', true, 'edit' );
		if ( rhn_live_catalog_image_identities( $current ) !== rhn_live_catalog_image_identities( $entry['gallery_image_urls'] ) || count( $product->get_gallery_image_ids( 'edit' ) ) !== count( $entry['gallery_image_urls'] ) ) { $changes[] = 'gallery_images'; }
	}
	return array_values( array_unique( $changes ) );
}

function rhn_live_catalog_actions_from_sheet( $sheet ) {
	$actions = array();
	foreach ( (array) ( $sheet['registry'] ?? array() ) as $sku => $entry ) {
		$actions[ $sku ] = array(
			'sku' => (string) $sku, 'action' => 'Publish',
			'row' => (int) ( $entry['sheet_row'] ?? 0 ), 'source' => (string) ( $entry['source'] ?? '' ),
			'entry' => $entry,
		);
	}
	foreach ( (array) ( $sheet['withdrawals'] ?? array() ) as $sku => $entry ) {
		if ( isset( $actions[ $sku ] ) ) {
			throw new RuntimeException( 'SKU ' . $sku . ' appears in both Publish and Remove actions.' );
		}
		$actions[ $sku ] = array(
			'sku' => (string) $sku, 'action' => 'Remove from Website',
			'row' => (int) ( $entry['row'] ?? 0 ), 'source' => (string) ( $entry['source'] ?? '' ),
		);
	}
	return $actions;
}

function rhn_live_catalog_build_plan( $sheet, $max_changes = 1, $excluded_skus = array() ) {
	$actions = rhn_live_catalog_actions_from_sheet( $sheet );
	$states = array();
	foreach ( $actions as $sku => $action ) {
		$product = rhn_live_catalog_find_product( $sku );
		if ( null === $product ) { continue; }
		if ( is_wp_error( $product ) ) { $states[ $sku ] = array( 'error' => $product->get_error_message() ); continue; }
		$changes = array();
		try {
			if ( 'Publish' === $action['action'] && ! $product->is_type( 'variation' ) ) {
				$changes = rhn_live_catalog_content_changes( $product, $action['entry'] );
			}
		} catch ( Throwable $error ) {
			$states[ $sku ] = array( 'error' => $error->getMessage() );
			continue;
		}
		$states[ $sku ] = array(
			'id' => $product->get_id(), 'type' => $product->get_type(),
			'status' => $product->get_status( 'edit' ), 'visibility' => $product->get_catalog_visibility( 'edit' ),
			'managed' => rhn_live_catalog_product_is_managed( $product ), 'content_changes' => $changes,
		);
	}
	$plan = rhn_live_catalog_plan( $actions, $states, PHP_INT_MAX, 3 );
	if ( 'yes' !== (string) get_option( 'rhn_live_catalog_creation_enabled', 'no' ) && $plan['create_quarantine'] ) {
		foreach ( $plan['create_quarantine'] as $sku => $item ) {
			$plan['unmanaged'][ $sku ] = array_merge( $item, array( 'reason' => 'Draft/hidden creation is disabled pending explicit activation.' ) );
		}
		$plan['create_quarantine'] = array();
	}
	foreach ( array_values( array_unique( array_map( 'strval', (array) $excluded_skus ) ) ) as $sku ) {
		foreach ( array( 'update', 'create_quarantine' ) as $bucket ) {
			if ( isset( $plan[ $bucket ][ $sku ] ) ) {
				$plan['errors'][ $sku ] = 'Skipped for the remainder of this drain after the per-SKU retry limit.';
				unset( $plan[ $bucket ][ $sku ] );
			}
		}
	}
	// A photo-backed product can require several network downloads and image
	// derivatives. Keep each cron/HTTP transaction below common gateway limits
	// so infrastructure retries cannot overlap a partially completed batch.
	return rhn_live_catalog_bound_plan( $plan, max( 1, (int) $max_changes ) );
}

function rhn_live_catalog_require_publish_inventory( $product, $sku ) {
	if ( rhn_live_catalog_is_already_public( $product->get_status( 'edit' ), $product->get_catalog_visibility( 'edit' ) ) ) { return; }
	$raw_quantity = $product->get_stock_quantity( 'edit' );
	$gate_errors = rhn_live_catalog_first_publication_errors(
		$product->get_regular_price( 'edit' ),
		$raw_quantity,
		$product->get_manage_stock( 'edit' ),
		$product->get_backorders( 'edit' ),
		$product->get_stock_status( 'edit' )
	);
	if ( $gate_errors ) {
		throw new RuntimeException( 'Publish held for SKU ' . $sku . ': ' . implode( ' ', $gate_errors ) );
	}
	$quantity = (float) $raw_quantity;
	if ( 'yes' === (string) $product->get_meta( '_rhn_catalog_pending_sheet_review', true, 'edit' ) && $quantity > 0 ) {
		$receipt = $product->get_meta( '_rhn_live_catalog_inventory_receipt', true, 'edit' );
		$receipt_time = is_array( $receipt ) ? strtotime( (string) ( $receipt['observed_at_gmt'] ?? '' ) ) : false;
		$quarantine_time = strtotime( (string) $product->get_meta( '_rhn_catalog_quarantine_created_gmt', true, 'edit' ) );
		if ( ! $receipt_time || ! $quarantine_time || $receipt_time < $quarantine_time || (string) ( $receipt['sku'] ?? '' ) !== (string) $sku || (float) ( $receipt['quantity'] ?? -1 ) !== $quantity ) {
			throw new RuntimeException( 'Publish held for SKU ' . $sku . ': positive stock requires a verified inventory update after quarantine creation.' );
		}
	}
}

function rhn_live_catalog_create_quarantine( $sku, $item, $run_id ) {
	$entry = (array) $item['entry'];
	$product = new WC_Product_Simple();
	$product->set_sku( (string) $sku );
	$product->set_name( sanitize_text_field( (string) $entry['name'] ) );
	$product->set_status( 'draft' ); $product->set_catalog_visibility( 'hidden' );
	$product->set_manage_stock( true ); $product->set_stock_quantity( 0 ); $product->set_stock_status( 'outofstock' ); $product->set_backorders( 'no' );
	$product->update_meta_data( '_rhn_live_catalog_managed', 'yes' );
	$product->update_meta_data( '_rhn_catalog_pending_sheet_review', 'yes' );
	$product->update_meta_data( '_rhn_catalog_created_from_sheet_quarantine', gmdate( 'c' ) );
	$product->update_meta_data( '_rhn_catalog_quarantine_created_gmt', gmdate( 'c' ) );
	$product = rhn_catalog_apply_entry_to_product( $product, null, $entry );
	$product->update_meta_data( '_rhn_catalog_sheet_receipt', array( 'run_id'=>$run_id,'action'=>'Create Draft/hidden quarantine','sheet_row'=>$item['row'],'processed_at_utc'=>gmdate( 'c' ),'processor'=>'2.1.1' ) );
	$id = $product->save();
	if ( ! $id ) { throw new RuntimeException( 'Draft/hidden creation failed for SKU ' . $sku . '.' ); }
	rhn_catalog_after_save( $product, null, true, $entry );
	$fresh = wc_get_product( $id );
	if ( ! $fresh || (string) $sku !== (string) $fresh->get_sku( 'edit' ) || 'draft' !== $fresh->get_status( 'edit' ) || 'hidden' !== $fresh->get_catalog_visibility( 'edit' ) ) {
		throw new RuntimeException( 'Draft/hidden creation read-back failed for SKU ' . $sku . '.' );
	}
	$errors = array_values( array_filter( (array) $fresh->get_meta( '_rhn_catalog_sync_errors', true, 'edit' ) ) );
	if ( $errors ) { throw new RuntimeException( 'Quarantine content error for SKU ' . $sku . ': ' . implode( '; ', $errors ) ); }
	return array( 'sku'=>$sku,'id'=>$id,'action'=>'Create Draft/hidden quarantine','status'=>'draft','visibility'=>'hidden','row'=>$item['row'] );
}

function rhn_live_catalog_apply_update( $sku, $item, $run_id ) {
	$product = wc_get_product( (int) $item['id'] );
	if ( ! $product || (string) $sku !== (string) $product->get_sku( 'edit' ) ) { throw new RuntimeException( 'Product identity changed before apply for SKU ' . $sku . '.' ); }
	$protected = rhn_live_catalog_protected_snapshot( $product );
	$rollback = rhn_live_catalog_content_snapshot( $product );
	$baselines = (array) get_option( 'rhn_live_catalog_product_baselines', array() );
	if ( ! isset( $baselines[ $sku ] ) ) { $baselines[ $sku ] = $rollback; update_option( 'rhn_live_catalog_product_baselines', $baselines, false ); }
	try {
		if ( 'Publish' === $item['action'] ) {
			$entry = (array) $item['entry'];
			$product = rhn_catalog_apply_entry_to_product( $product, null, $entry );
			rhn_live_catalog_require_publish_inventory( $product, $sku );
			$product->set_status( 'publish' ); $product->set_catalog_visibility( 'visible' );
			$product->delete_meta_data( '_rhn_catalog_pending_sheet_review' );
			$product->delete_meta_data( '_rhn_catalog_withdrawn_by_sheet' );
		} else {
			$product->set_status( 'draft' ); $product->set_catalog_visibility( 'hidden' );
			$product->update_meta_data( '_rhn_catalog_withdrawn_by_sheet', current_time( 'mysql' ) );
		}
		$product->update_meta_data( '_rhn_live_catalog_managed', 'yes' );
		$product->update_meta_data( '_rhn_catalog_sheet_receipt', array( 'run_id'=>$run_id,'action'=>$item['action'],'sheet_row'=>$item['row'],'source'=>$item['source'],'processed_at_utc'=>gmdate( 'c' ),'processor'=>'2.1.1','content_changes'=>$item['content_changes'] ) );
		$product->save();
		if ( 'Publish' === $item['action'] ) { rhn_catalog_after_save( $product, null, false, $entry ); }
		clean_post_cache( $product->get_id() ); wc_delete_product_transients( $product->get_id() );
		$fresh = wc_get_product( $product->get_id() );
		if ( $protected !== rhn_live_catalog_protected_snapshot( $fresh ) ) { throw new RuntimeException( 'Protected POS-owned field changed for SKU ' . $sku . '.' ); }
		$errors = array_values( array_filter( (array) $fresh->get_meta( '_rhn_catalog_sync_errors', true, 'edit' ) ) );
		if ( $errors ) { throw new RuntimeException( implode( '; ', $errors ) ); }
		if ( $item['to_status'] !== $fresh->get_status( 'edit' ) || $item['to_visibility'] !== $fresh->get_catalog_visibility( 'edit' ) ) { throw new RuntimeException( 'Status/visibility read-back failed for SKU ' . $sku . '.' ); }
		return array( 'sku'=>$sku,'id'=>$fresh->get_id(),'action'=>$item['action'],'status'=>$fresh->get_status( 'edit' ),'visibility'=>$fresh->get_catalog_visibility( 'edit' ),'row'=>$item['row'],'content_changes'=>$item['content_changes'] );
	} catch ( Throwable $error ) {
		rhn_live_catalog_restore_snapshot( $rollback );
		throw $error;
	}
}

function rhn_live_catalog_receipt( $run_id, $sheet, $plan, $applied, $source, $write, $runtime_errors = array() ) {
	$coverage = rhn_live_catalog_coverage( $plan );
	if ( $runtime_errors ) {
		$coverage['fully_synchronized'] = false;
		$coverage['result_status'] = 'partially_applied_with_errors';
	}
	$counts = array(
		'actionable'=>count( rhn_live_catalog_actions_from_sheet( $sheet ) ), 'sheet_skipped'=>count( (array) ( $sheet['skipped'] ?? array() ) ),
		'planned_updates'=>count( $plan['update'] ), 'planned_creations'=>count( $plan['create_quarantine'] ),
		'deferred'=>count( $plan['deferred'] ),
		'unchanged'=>count( $plan['unchanged'] ), 'missing_remove_satisfied'=>count( $plan['missing_remove_satisfied'] ),
		'unmanaged'=>count( $plan['unmanaged'] ), 'unsupported_variations'=>count( $plan['unsupported_variations'] ),
		'errors'=>count( $plan['errors'] ), 'runtime_errors'=>count( $runtime_errors ), 'applied'=>count( $applied ),
	);
	$outcomes = array();
	foreach ( rhn_live_catalog_actions_from_sheet( $sheet ) as $sku => $action ) {
		$outcome = 'not_planned'; $detail = '';
		if ( isset( $runtime_errors[ $sku ] ) ) { $outcome = 'failed'; $detail = (string) $runtime_errors[ $sku ]; }
		elseif ( isset( $applied[ $sku ] ) ) { $outcome = 'applied_and_verified'; }
		elseif ( isset( $plan['unchanged'][ $sku ] ) ) { $outcome = 'already_correct'; }
		elseif ( isset( $plan['missing_remove_satisfied'][ $sku ] ) ) { $outcome = 'missing_remove_already_satisfied'; }
		elseif ( isset( $plan['unmanaged'][ $sku ] ) ) { $outcome = 'review_unmanaged'; $detail = (string) ( $plan['unmanaged'][ $sku ]['reason'] ?? '' ); }
		elseif ( isset( $plan['unsupported_variations'][ $sku ] ) ) { $outcome = 'review_unsupported_variation'; $detail = (string) ( $plan['unsupported_variations'][ $sku ]['reason'] ?? '' ); }
		elseif ( isset( $plan['errors'][ $sku ] ) ) { $outcome = 'failed_validation'; $detail = (string) $plan['errors'][ $sku ]; }
		elseif ( isset( $plan['deferred'][ $sku ] ) ) { $outcome = 'deferred_to_next_short_job'; $detail = (string)($plan['deferred'][$sku]['reason']??''); }
		elseif ( isset( $plan['create_quarantine'][ $sku ] ) ) { $outcome = $write ? 'not_applied' : 'planned_create_quarantine'; }
		elseif ( isset( $plan['update'][ $sku ] ) ) { $outcome = $write ? 'not_applied' : 'planned_update'; }
		$outcomes[ $sku ] = array( 'sku'=>$sku,'row'=>(int)($action['row']??0),'action'=>(string)($action['action']??''),'outcome'=>$outcome,'detail'=>$detail );
	}
	return array(
		'run_id'=>$run_id,'processed_at_utc'=>gmdate( 'c' ),'source'=>sanitize_key( $source ),'write'=>(bool)$write,
		'processor'=>'2.1.1','sheet_id_sha256'=>hash( 'sha256', (string) rhn_catalog_google_sheet_id() ),
		'range'=>(string) get_option( 'rhn_catalog_google_sheet_range', 'Catalog!A:W' ),
		'counts'=>$counts,'plan_fingerprint'=>rhn_live_catalog_plan_fingerprint( $plan ),'coverage'=>$coverage,
		'applied'=>array_values( $applied ), 'outcomes'=>$outcomes, 'photo_intake'=>(array)($sheet['photo_intake']??array()),
		'deferred'=>array_values( $plan['deferred'] ),
		'unmanaged'=>array_values( $plan['unmanaged'] ),'unsupported_variations'=>array_values( $plan['unsupported_variations'] ),
		'missing_remove_sha256'=>hash( 'sha256', implode( '|', array_keys( $plan['missing_remove_satisfied'] ) ) ),
		'missing_remove_sample'=>array_slice( array_keys( $plan['missing_remove_satisfied'] ), 0, 50 ),'errors'=>$plan['errors'],'runtime_errors'=>$runtime_errors,
	);
}

function rhn_live_catalog_reconcile( $sheet, $write = true, $source = 'manual', $options = array() ) {
	$plan = rhn_live_catalog_build_plan( $sheet, (int) ( $options['max_changes'] ?? 1 ), (array) ( $options['excluded_skus'] ?? array() ) );
	$run_id = wp_generate_uuid4(); $applied = array(); $runtime_errors = array();
	$blocking_errors = $plan['errors'];
	if ( ! empty( $options['allow_keyed_errors'] ) ) {
		$blocking_errors = array_filter( $blocking_errors, static fn( $value, $key ) => str_starts_with( (string) $key, '_' ), ARRAY_FILTER_USE_BOTH );
	}
	if ( $blocking_errors ) {
		$receipt = rhn_live_catalog_receipt( $run_id, $sheet, $plan, $applied, $source, false, $runtime_errors );
		if ( $write ) { update_option( 'rhn_live_catalog_last_receipt', $receipt, false ); }
		return new WP_Error( 'rhn_live_catalog_plan', 'Catalog plan failed closed; no product changes were made.', array( 'receipt'=>$receipt,'plan'=>$plan ) );
	}
	if ( $write ) {
		foreach ( $plan['create_quarantine'] as $sku => $item ) {
			try { $applied[ $sku ] = rhn_live_catalog_create_quarantine( $sku, $item, $run_id ); } catch ( Throwable $error ) { $runtime_errors[ $sku ] = $error->getMessage(); }
		}
		foreach ( $plan['update'] as $sku => $item ) {
			try { $applied[ $sku ] = rhn_live_catalog_apply_update( $sku, $item, $run_id ); } catch ( Throwable $error ) { $runtime_errors[ $sku ] = $error->getMessage(); }
		}
		$existing = rhn_catalog_registry();
		$verified_registry = array();
		foreach ( (array) ( $sheet['registry'] ?? array() ) as $sku => $entry ) {
			if ( isset( $plan['unchanged'][ $sku ] ) || isset( $applied[ $sku ] ) ) {
				$verified_registry[ $sku ] = $entry;
			}
		}
		if ( $verified_registry && true !== ( $options['persist_registry'] ?? true ) ) {
			$verified_registry = array();
		}
		if ( $verified_registry ) {
			update_option( 'rhn_catalog_presentation_previous', $existing, false );
			$merged = array_replace( $existing, $verified_registry );
			update_option( 'rhn_catalog_presentation_overrides', $merged, false );
			if ( get_option( 'rhn_catalog_presentation_overrides' ) !== $merged ) {
				$runtime_errors['_registry'] = 'Verified product actions completed, but their website-field registry could not be persisted.';
			}
		}
		$receipt = rhn_live_catalog_receipt( $run_id, $sheet, $plan, $applied, $source, $write, $runtime_errors );
		update_option( 'rhn_live_catalog_last_receipt', $receipt, false );
		if ( $runtime_errors ) { return new WP_Error( 'rhn_live_catalog_partial', 'One or more exact-SKU catalog actions failed; verified successful actions were retained and failures are in the durable receipt.', array( 'receipt'=>$receipt,'plan'=>$plan ) ); }
	}
	$receipt = rhn_live_catalog_receipt( $run_id, $sheet, $plan, $applied, $source, $write, $runtime_errors );
	if ( $write ) { update_option( 'rhn_live_catalog_last_receipt', $receipt, false ); }
	return array( 'plan'=>$plan,'receipt'=>$receipt );
}

function rhn_catalog_apply_sheet_result( $sheet, $write_products = true ) {
	return rhn_live_catalog_reconcile( $sheet, (bool) $write_products, 'admin' );
}

function rhn_catalog_sync_health() { $h=get_option( 'rhn_live_catalog_health', array() ); return is_array($h)?$h:array(); }
function rhn_catalog_run_sheet_sync( $simulated_failure = false, $source = 'manual', $excluded_skus = array() ) {
	if ( ! rhn_catalog_batch_guarded() ) { return new WP_Error( 'rhn_live_catalog_disabled', 'Production catalog processor is not enabled.' ); }
	if ( $simulated_failure ) { return new WP_Error( 'rhn_live_catalog_simulated', 'Simulated failure; no changes made.' ); }
	if ( ! add_option( 'rhn_live_catalog_lock', time(), '', false ) ) {
		$started=(int)get_option('rhn_live_catalog_lock',0); if($started && time()-$started<600){return new WP_Error('rhn_live_catalog_locked','Another catalog run is active.');}
		delete_option('rhn_live_catalog_lock'); if(!add_option('rhn_live_catalog_lock',time(),'',false)){return new WP_Error('rhn_live_catalog_locked','Could not acquire catalog lock.');}
	}
	$attempt=gmdate('c');
	$GLOBALS['rhn_live_catalog_job_deadline']=microtime(true)+90;
	try {
		$sheet=rhn_catalog_fetch_google_sheet(); if(is_wp_error($sheet)){throw new RuntimeException($sheet->get_error_message());}
		$sheet=rhn_catalog_photo_intake($sheet,true);
		if('error'===(string)($sheet['photo_intake']['status']??'')){throw new RuntimeException((string)($sheet['photo_intake']['message']??'Photo intake failed.'));}
		$result=rhn_live_catalog_reconcile($sheet,true,$source,array('max_changes'=>1,'excluded_skus'=>$excluded_skus,'allow_keyed_errors'=>true));
		if(is_wp_error($result)){
			$data=(array)$result->get_error_data();
			if(!empty($data['receipt'])){return $result;}
			throw new RuntimeException($result->get_error_message());
		}
		update_option('rhn_live_catalog_health',array('last_attempt_utc'=>$attempt,'last_success_utc'=>gmdate('c'),'last_error'=>'','consecutive_failures'=>0,'counts'=>$result['receipt']['counts'],'run_id'=>$result['receipt']['run_id']),false);
		return $result;
	} catch(Throwable $error) {
		$h=rhn_catalog_sync_health(); update_option('rhn_live_catalog_health',array('last_attempt_utc'=>$attempt,'last_success_utc'=>(string)($h['last_success_utc']??''),'last_error'=>sanitize_text_field($error->getMessage()),'consecutive_failures'=>(int)($h['consecutive_failures']??0)+1),false);
		return new WP_Error('rhn_live_catalog_failure',$error->getMessage());
	} finally { unset($GLOBALS['rhn_live_catalog_job_deadline']);delete_option('rhn_live_catalog_lock'); }
}

function rhn_live_catalog_new_cycle_state( $cycle_id, $max_jobs = 10, $max_retries = 2 ) {
	return array(
		'cycle_id'=>(string)$cycle_id,'status'=>'queued','started_at_utc'=>gmdate('c'),'updated_at_utc'=>gmdate('c'),
		'max_jobs'=>max(1,(int)$max_jobs),'max_retries'=>max(1,(int)$max_retries),'processed_jobs'=>0,'total_processed_jobs'=>0,'tranches'=>1,'attempts'=>0,
		'applied'=>array(),'failures'=>array(),'excluded_skus'=>array(),'retry_counts'=>array(),'handled_receipts'=>array(),
		'pending_token'=>'','pending_scheduled_at'=>0,'job_started_at'=>0,'cycle_error_retries'=>0,
		'last_receipt'=>array(),'queue_backend'=>'','new_rows_policy'=>'refetched before every short job; overflow continues automatically without waiting four hours',
	);
}

function rhn_live_catalog_cycle_transition( $state, $receipt ) {
	$receipt=(array)$receipt;$run_id=(string)($receipt['run_id']??'');
	if(''!==$run_id && in_array($run_id,(array)($state['handled_receipts']??array()),true)){return $state;}
	if(''!==$run_id){$state['handled_receipts'][]=$run_id;}
	$state['last_receipt']=$receipt;$state['updated_at_utc']=gmdate('c');
	foreach((array)($receipt['errors']??array()) as $sku=>$message){if(!str_starts_with((string)$sku,'_')){$state['failures'][(string)$sku]=(string)$message;}}
	$runtime=(array)($receipt['runtime_errors']??array());
	if($runtime){
		$sku=(string)array_key_first($runtime);$state['retry_counts'][$sku]=(int)($state['retry_counts'][$sku]??0)+1;
		if($state['retry_counts'][$sku]>=(int)$state['max_retries']){$state['excluded_skus'][]=$sku;$state['excluded_skus']=array_values(array_unique($state['excluded_skus']));$state['failures'][$sku]=(string)$runtime[$sku];$state['processed_jobs']++;$state['total_processed_jobs']++;}
		$state['status']=((int)$state['processed_jobs']>=(int)$state['max_jobs'] && (int)($receipt['counts']['deferred']??0)>0)?'capped_with_continuation':'queued';return $state;
	}
	$applied=(array)($receipt['applied']??array());
	foreach($applied as $item){$sku=(string)($item['sku']??'');if(''!==$sku){$state['applied'][$sku]=$item;$state['processed_jobs']++;$state['total_processed_jobs']++;}}
	$deferred=(int)($receipt['counts']['deferred']??0);
	if((int)$state['processed_jobs']>=(int)$state['max_jobs']){$state['status']=$deferred>0?'capped_with_continuation':'completed';}
	elseif($applied && $deferred>0){$state['status']='queued';}
	elseif($applied){$state['status']=$state['failures']?'completed_with_failures':'completed';}
	else{$state['status']=$state['failures']?'completed_with_failures':'completed';}
	return $state;
}

function rhn_live_catalog_enqueue_cycle_job( $cycle_id, $delay = 0 ) {
	$state=(array)get_option('rhn_live_catalog_active_cycle',array());
	if((string)($state['cycle_id']??'')!==(string)$cycle_id){return false;}
	if(''!==(string)($state['pending_token']??'')){return true;}
	$token=wp_generate_uuid4();$state['pending_token']=$token;$state['pending_scheduled_at']=time();$state['updated_at_utc']=gmdate('c');
	if(function_exists('as_schedule_single_action')){$id=as_schedule_single_action(time()+max(1,(int)$delay),'rhn_live_catalog_cycle_job',array($cycle_id,$token),'rhn-live-catalog',true);$state['queue_backend']='action_scheduler';}
	else{$id=wp_schedule_single_event(time()+max(1,(int)$delay),'rhn_live_catalog_cycle_job',array($cycle_id,$token));$state['queue_backend']='wp_cron_continuation';}
	if(!$id){$state['pending_token']='';$state['pending_scheduled_at']=0;$state['status']='queue_failed';update_option('rhn_live_catalog_active_cycle',$state,false);return false;}
	update_option('rhn_live_catalog_active_cycle',$state,false);return true;
}

function rhn_live_catalog_schedule_follow_on_cycle( $delay = 30 ) {
	$timestamp=time()+max(5,(int)$delay);
	if(function_exists('as_schedule_single_action')){return as_schedule_single_action($timestamp,'rhn_live_catalog_drain_continue_event',array(),'rhn-live-catalog',true);}
	if(wp_next_scheduled('rhn_live_catalog_drain_continue_event')){return true;}
	return wp_schedule_single_event($timestamp,'rhn_live_catalog_drain_continue_event');
}

function rhn_live_catalog_continue_drain() {
	$state=function_exists('rhn_full_range_legacy_dispatch_state')?rhn_full_range_legacy_dispatch_state((array)get_option('rhn_live_catalog_active_cycle',array())):(array)get_option('rhn_live_catalog_active_cycle',array());
	if('full_range'===(string)($state['mode']??'')){return function_exists('rhn_full_range_feature_enabled')&&rhn_full_range_feature_enabled()&&function_exists('rhn_full_range_continue_drain')?rhn_full_range_continue_drain():rhn_full_range_pause_disabled_cycle_callback();}
	if('capped_with_continuation'!==(string)($state['status']??'')){return $state;}
	$state['status']='queued';$state['processed_jobs']=0;$state['tranches']=(int)($state['tranches']??1)+1;
	$state['updated_at_utc']=gmdate('c');$state['continuation_reason']='Ten short jobs completed; continuing the same scheduled drain.';
	update_option('rhn_live_catalog_active_cycle',$state,false);
	rhn_live_catalog_enqueue_cycle_job((string)$state['cycle_id'],5);
	return (array)get_option('rhn_live_catalog_active_cycle',array());
}

function rhn_live_catalog_start_cycle() {
	if(!rhn_catalog_batch_guarded()){return new WP_Error('rhn_live_catalog_disabled','Production catalog processor is not enabled.');}
	$state=function_exists('rhn_full_range_legacy_dispatch_state')?rhn_full_range_legacy_dispatch_state((array)get_option('rhn_live_catalog_active_cycle',array())):(array)get_option('rhn_live_catalog_active_cycle',array());
	if('full_range'===(string)($state['mode']??'')){return function_exists('rhn_full_range_feature_enabled')&&rhn_full_range_feature_enabled()&&function_exists('rhn_full_range_start_cycle')?rhn_full_range_start_cycle():rhn_full_range_pause_disabled_cycle_callback();}
	if('capped_with_continuation'===(string)($state['status']??'')){rhn_live_catalog_schedule_follow_on_cycle(30);return $state;}
	if(in_array((string)($state['status']??''),array('queued','running'),true)){
		$pending_age=time()-(int)($state['pending_scheduled_at']??0);$running_age=time()-(int)($state['job_started_at']??0);
		if('queued'===(string)$state['status'] && ''!==(string)($state['pending_token']??'') && $pending_age>900){$state['pending_token']='';$state['pending_scheduled_at']=0;update_option('rhn_live_catalog_active_cycle',$state,false);}
		if('running'===(string)$state['status'] && $running_age>900){$state['status']='queued';$state['pending_token']='';$state['pending_scheduled_at']=0;update_option('rhn_live_catalog_active_cycle',$state,false);}
		if(''===(string)($state['pending_token']??'') && 'queued'===(string)($state['status']??'')){rhn_live_catalog_enqueue_cycle_job((string)$state['cycle_id'],5);}
		return $state;
	}
	$state=rhn_live_catalog_new_cycle_state(wp_generate_uuid4(),10,2);update_option('rhn_live_catalog_active_cycle',$state,false);
	rhn_live_catalog_enqueue_cycle_job($state['cycle_id'],1);return (array)get_option('rhn_live_catalog_active_cycle',array());
}

function rhn_live_catalog_run_cycle_job( $cycle_id, $token ) {
	$state=function_exists('rhn_full_range_legacy_dispatch_state')?rhn_full_range_legacy_dispatch_state((array)get_option('rhn_live_catalog_active_cycle',array())):(array)get_option('rhn_live_catalog_active_cycle',array());
	if('full_range'===(string)($state['mode']??'')){return function_exists('rhn_full_range_feature_enabled')&&rhn_full_range_feature_enabled()&&function_exists('rhn_full_range_run_cycle_job')?rhn_full_range_run_cycle_job($cycle_id,$token):rhn_full_range_pause_disabled_cycle_callback($cycle_id,$token);}
	if((string)($state['cycle_id']??'')!==(string)$cycle_id || !hash_equals((string)($state['pending_token']??''),(string)$token)){return;}
	$state['pending_token']='';$state['pending_scheduled_at']=0;$state['job_started_at']=time();$state['status']='running';$state['attempts']++;$state['updated_at_utc']=gmdate('c');update_option('rhn_live_catalog_active_cycle',$state,false);
	$result=rhn_catalog_run_sheet_sync(false,'cycle_job',(array)$state['excluded_skus']);
	$receipt=is_wp_error($result)?(array)(((array)$result->get_error_data())['receipt']??array()):(array)($result['receipt']??array());
	$state['job_started_at']=0;
	if(!$receipt){$state['cycle_error_retries']=(int)($state['cycle_error_retries']??0)+1;$state['failures']['_cycle']=is_wp_error($result)?$result->get_error_message():'Missing cycle receipt.';$state['status']=$state['cycle_error_retries']<=2?'queued':'cycle_failed';}
	else{$state=rhn_live_catalog_cycle_transition($state,$receipt);}
	update_option('rhn_live_catalog_active_cycle',$state,false);update_option('rhn_live_catalog_last_cycle',$state,false);
	if('queued'===(string)$state['status']){rhn_live_catalog_enqueue_cycle_job($cycle_id,5);}
	elseif('capped_with_continuation'===(string)$state['status']){rhn_live_catalog_schedule_follow_on_cycle(30);}
}

function rhn_live_catalog_schedule( $schedules ) { $schedules['rhn_live_catalog_four_hours']=array('interval'=>4*HOUR_IN_SECONDS,'display'=>'Every four hours (RHN live catalog)'); return $schedules; }
function rhn_live_catalog_ensure_schedule() {
	$next=wp_next_scheduled('rhn_catalog_sheet_sync_event');
	if(!rhn_catalog_batch_guarded()){
		if($next){wp_unschedule_event($next,'rhn_catalog_sheet_sync_event');}
		return;
	}
	if(!$next){wp_schedule_event(rhn_live_catalog_next_anchor_at(time()),'rhn_live_catalog_four_hours','rhn_catalog_sheet_sync_event');}
}
add_filter('cron_schedules','rhn_live_catalog_schedule');
add_action('init','rhn_live_catalog_ensure_schedule',30);
add_action('rhn_catalog_sheet_sync_event','rhn_live_catalog_start_cycle');
add_action('rhn_live_catalog_cycle_job','rhn_live_catalog_run_cycle_job',10,2);
add_action('rhn_live_catalog_drain_continue_event','rhn_live_catalog_continue_drain');
