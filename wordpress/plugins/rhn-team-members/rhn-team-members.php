<?php
/**
 * Plugin Name: Rebekah's Team Members
 * Description: Adds a safe Team Members editor and powers the Our Team page with automatic store grouping and story counts.
 * Version: 1.0.3
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RHN_TEAM_VERSION', '1.0.3' );
define( 'RHN_TEAM_FILE', __FILE__ );

function rhn_team_stores() {
	return array(
		'lapeer'      => array(
			'name'        => 'Lapeer',
			'eyebrow'     => 'Lapeer · Our first location',
			'heading'     => 'Meet your Lapeer team.',
			'description' => 'Passionate educators and familiar faces ready to help you explore natural wellness with confidence.',
			'class'       => 'alt',
		),
		'grand-blanc' => array(
			'name'        => 'Grand Blanc',
			'eyebrow'     => 'Grand Blanc · Experience meets curiosity',
			'heading'     => 'Meet your Grand Blanc team.',
			'description' => 'A thoughtful mix of deep industry experience, holistic health education and a love of helping people learn.',
			'class'       => '',
		),
		'clarkston'   => array(
			'name'        => 'Clarkston',
			'eyebrow'     => 'Clarkston · Growing together',
			'heading'     => 'Meet your Clarkston team.',
			'description' => 'Relationships, continuing education and fresh ideas come together in a team invested in the people it serves.',
			'class'       => 'pine',
		),
		'lake-orion'  => array(
			'name'        => 'Lake Orion',
			'eyebrow'     => 'Lake Orion · Mind and body',
			'heading'     => 'Meet your Lake Orion team.',
			'description' => 'Thoughtful product education and a whole-person view of wellness guide every interaction at Lake Orion.',
			'class'       => 'alt',
		),
	);
}

function rhn_team_register_post_type() {
	register_post_type(
		'rhn_team_member',
		array(
			'labels' => array(
				'name'               => __( 'Team Members', 'rhn-team' ),
				'singular_name'      => __( 'Team Member', 'rhn-team' ),
				'menu_name'          => __( 'Team Members', 'rhn-team' ),
				'add_new'            => __( 'Add Team Member', 'rhn-team' ),
				'add_new_item'       => __( 'Add Team Member', 'rhn-team' ),
				'edit_item'          => __( 'Edit Team Member', 'rhn-team' ),
				'new_item'           => __( 'New Team Member', 'rhn-team' ),
				'view_items'         => __( 'View Team Members', 'rhn-team' ),
				'search_items'       => __( 'Search Team Members', 'rhn-team' ),
				'not_found'          => __( 'No team members found.', 'rhn-team' ),
				'featured_image'     => __( 'Profile photo', 'rhn-team' ),
				'set_featured_image' => __( 'Choose profile photo', 'rhn-team' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-groups',
			'menu_position'       => 21,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);
}
add_action( 'init', 'rhn_team_register_post_type', 5 );

function rhn_team_add_meta_boxes() {
	add_meta_box( 'rhn-team-details', __( 'Team Member Details', 'rhn-team' ), 'rhn_team_details_meta_box', 'rhn_team_member', 'normal', 'high' );
	add_meta_box( 'rhn-team-help', __( 'Publishing checklist', 'rhn-team' ), 'rhn_team_help_meta_box', 'rhn_team_member', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'rhn_team_add_meta_boxes' );

function rhn_team_details_meta_box( $post ) {
	wp_nonce_field( 'rhn_team_save_details', 'rhn_team_nonce' );
	$store    = get_post_meta( $post->ID, '_rhn_team_store', true );
	$role     = get_post_meta( $post->ID, '_rhn_team_role', true );
	$summary  = get_post_meta( $post->ID, '_rhn_team_summary', true );
	$position = get_post_meta( $post->ID, '_rhn_team_photo_position', true );
	$position = $position ? $position : 'center';
	?>
	<p><strong><?php esc_html_e( 'Complete these fields, add the full biography in the main editor, and choose a profile photo.', 'rhn-team' ); ?></strong></p>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="rhn-team-store"><?php esc_html_e( 'Store', 'rhn-team' ); ?></label></th>
			<td><select id="rhn-team-store" name="rhn_team_store" required>
				<option value=""><?php esc_html_e( 'Choose a store', 'rhn-team' ); ?></option>
				<?php foreach ( rhn_team_stores() as $slug => $details ) : ?>
					<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $store, $slug ); ?>><?php echo esc_html( $details['name'] ); ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
		<tr>
			<th><label for="rhn-team-role"><?php esc_html_e( 'Role / title', 'rhn-team' ); ?></label></th>
			<td><input class="regular-text" id="rhn-team-role" name="rhn_team_role" type="text" value="<?php echo esc_attr( $role ); ?>" required></td>
		</tr>
		<tr>
			<th><label for="rhn-team-summary"><?php esc_html_e( 'Short card summary', 'rhn-team' ); ?></label></th>
			<td><textarea class="large-text" id="rhn-team-summary" name="rhn_team_summary" rows="3" maxlength="240" required><?php echo esc_textarea( $summary ); ?></textarea><p class="description"><?php esc_html_e( 'One or two sentences (240 characters maximum). The full biography stays in the main editor.', 'rhn-team' ); ?></p></td>
		</tr>
		<tr>
			<th><label for="rhn-team-order"><?php esc_html_e( 'Display order', 'rhn-team' ); ?></label></th>
			<td><input id="rhn-team-order" name="rhn_team_order" type="number" min="0" step="1" value="<?php echo esc_attr( (string) $post->menu_order ); ?>"><p class="description"><?php esc_html_e( 'Lower numbers appear first within the selected store.', 'rhn-team' ); ?></p></td>
		</tr>
		<tr>
			<th><label for="rhn-team-position"><?php esc_html_e( 'Photo position', 'rhn-team' ); ?></label></th>
			<td><select id="rhn-team-position" name="rhn_team_photo_position">
				<?php foreach ( array( 'top' => 'Top', 'upper' => 'Upper center', 'center' => 'Center', 'lower' => 'Lower center' ) as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $position, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr>
	</table>
	<?php
}

function rhn_team_help_meta_box() {
	echo '<ol style="margin-left:1.2em"><li>Add the person\'s name as the title.</li><li>Choose the store and enter the role.</li><li>Add a concise card summary.</li><li>Paste the approved full biography into the main editor.</li><li>Choose a clear profile photo.</li><li>Preview, then publish or update.</li></ol>';
	echo '<p><strong>The Our Team page updates automatically.</strong> Store grouping and all story counts are calculated for you.</p>';
}

function rhn_team_save_details( $post_id ) {
	if ( ! isset( $_POST['rhn_team_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rhn_team_nonce'] ) ), 'rhn_team_save_details' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$stores   = rhn_team_stores();
	$store    = isset( $_POST['rhn_team_store'] ) ? sanitize_key( wp_unslash( $_POST['rhn_team_store'] ) ) : '';
	$role     = isset( $_POST['rhn_team_role'] ) ? sanitize_text_field( wp_unslash( $_POST['rhn_team_role'] ) ) : '';
	$summary  = isset( $_POST['rhn_team_summary'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rhn_team_summary'] ) ) : '';
	$order    = isset( $_POST['rhn_team_order'] ) ? max( 0, absint( $_POST['rhn_team_order'] ) ) : 0;
	$position = isset( $_POST['rhn_team_photo_position'] ) ? sanitize_key( wp_unslash( $_POST['rhn_team_photo_position'] ) ) : 'center';
	$position = in_array( $position, array( 'top', 'upper', 'center', 'lower' ), true ) ? $position : 'center';

	update_post_meta( $post_id, '_rhn_team_store', isset( $stores[ $store ] ) ? $store : '' );
	update_post_meta( $post_id, '_rhn_team_role', $role );
	update_post_meta( $post_id, '_rhn_team_summary', wp_html_excerpt( $summary, 240, '' ) );
	update_post_meta( $post_id, '_rhn_team_photo_position', $position );

	remove_action( 'save_post_rhn_team_member', 'rhn_team_save_details' );
	wp_update_post( array( 'ID' => $post_id, 'menu_order' => $order ) );
	add_action( 'save_post_rhn_team_member', 'rhn_team_save_details' );
}
add_action( 'save_post_rhn_team_member', 'rhn_team_save_details' );

function rhn_team_admin_columns( $columns ) {
	return array(
		'cb'             => $columns['cb'],
		'rhn_photo'      => __( 'Photo', 'rhn-team' ),
		'title'          => __( 'Name', 'rhn-team' ),
		'rhn_store'      => __( 'Store', 'rhn-team' ),
		'rhn_role'       => __( 'Role / title', 'rhn-team' ),
		'rhn_order'      => __( 'Order', 'rhn-team' ),
		'date'           => $columns['date'],
	);
}
add_filter( 'manage_rhn_team_member_posts_columns', 'rhn_team_admin_columns' );

function rhn_team_admin_column( $column, $post_id ) {
	if ( 'rhn_photo' === $column ) {
		echo get_the_post_thumbnail( $post_id, array( 54, 54 ), array( 'style' => 'width:54px;height:54px;object-fit:cover;border-radius:8px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} elseif ( 'rhn_store' === $column ) {
		$store  = get_post_meta( $post_id, '_rhn_team_store', true );
		$stores = rhn_team_stores();
		echo esc_html( isset( $stores[ $store ] ) ? $stores[ $store ]['name'] : '—' );
	} elseif ( 'rhn_role' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_rhn_team_role', true ) );
	} elseif ( 'rhn_order' === $column ) {
		echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
	}
}
add_action( 'manage_rhn_team_member_posts_custom_column', 'rhn_team_admin_column', 10, 2 );

function rhn_team_admin_order( $query ) {
	if ( is_admin() && $query->is_main_query() && 'rhn_team_member' === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
	}
}
add_action( 'pre_get_posts', 'rhn_team_admin_order' );

function rhn_team_image_source_url( $source ) {
	if ( 0 === strpos( $source, 'theme:' ) ) {
		return trailingslashit( get_template_directory_uri() ) . 'assets/' . ltrim( substr( $source, 6 ), '/' );
	}
	if ( 0 === strpos( $source, 'content:' ) ) {
		return content_url( '/' . ltrim( substr( $source, 8 ), '/' ) );
	}
	if ( 0 === strpos( $source, 'plugin:' ) ) {
		return plugins_url( ltrim( substr( $source, 7 ), '/' ), RHN_TEAM_FILE );
	}
	return '';
}

function rhn_team_photo_position_class( $post_id ) {
	$legacy_class = get_post_meta( $post_id, '_rhn_team_image_class', true );
	if ( $legacy_class ) {
		return sanitize_html_class( $legacy_class );
	}
	$position = get_post_meta( $post_id, '_rhn_team_photo_position', true );
	$map      = array( 'top' => 'portrait-top', 'upper' => 'portrait-high', 'center' => 'portrait-center', 'lower' => 'portrait-low' );
	return isset( $map[ $position ] ) ? $map[ $position ] : 'portrait-center';
}

function rhn_team_image_html( $post_id, $store_name ) {
	$name  = get_the_title( $post_id );
	$role  = get_post_meta( $post_id, '_rhn_team_role', true );
	$alt   = get_post_meta( $post_id, '_rhn_team_image_alt', true );
	$alt   = $alt ? $alt : sprintf( '%1$s, %2$s at Rebekah\'s %3$s', $name, $role, $store_name );
	$class = rhn_team_photo_position_class( $post_id );
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, 'large', array( 'class' => $class, 'alt' => $alt, 'decoding' => 'async' ) );
	}
	$url = rhn_team_image_source_url( get_post_meta( $post_id, '_rhn_team_image_source', true ) );
	return $url ? '<img class="' . esc_attr( $class ) . '" src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy" decoding="async">' : '';
}

function rhn_team_members_grouped() {
	$grouped = array_fill_keys( array_keys( rhn_team_stores() ), array() );
	$members = get_posts(
		array(
			'post_type'      => 'rhn_team_member',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'order'          => 'ASC',
		)
	);
	foreach ( $members as $member ) {
		$store = get_post_meta( $member->ID, '_rhn_team_store', true );
		if ( isset( $grouped[ $store ] ) ) {
			$grouped[ $store ][] = $member;
		}
	}
	return $grouped;
}

function rhn_team_grid_class( $count ) {
	if ( 1 === $count ) {
		return 'solo';
	}
	if ( 2 === $count ) {
		return 'two';
	}
	if ( 4 === $count ) {
		return 'four';
	}
	return '';
}

function rhn_team_template_include( $template ) {
	if ( is_page( 'our-team' ) && post_type_exists( 'rhn_team_member' ) && wp_count_posts( 'rhn_team_member' )->publish > 0 ) {
		return plugin_dir_path( __FILE__ ) . 'templates/our-team.php';
	}
	return $template;
}
add_filter( 'template_include', 'rhn_team_template_include', 99 );

function rhn_team_enqueue_adjustments() {
	if ( ! is_page( 'our-team' ) ) {
		return;
	}
	$css = '@media(min-width:1051px){#lapeer-team .team-grid .team-card{grid-column:span 2}#lapeer-team .team-grid .team-card:last-child:nth-child(3n+1){grid-column:3/span 2}#lapeer-team .team-grid .team-card:nth-last-child(2):nth-child(3n+1){grid-column:2/span 2}}';
	wp_add_inline_style( 'rhn-page-our-team', $css );
}
add_action( 'wp_enqueue_scripts', 'rhn_team_enqueue_adjustments', 40 );

function rhn_team_seed_profiles() {
	rhn_team_register_post_type();
	if ( get_option( 'rhn_team_seed_version' ) === RHN_TEAM_VERSION ) {
		return;
	}

	$profiles = require plugin_dir_path( __FILE__ ) . 'includes/seed-profiles.php';
	foreach ( $profiles as $profile ) {
		$existing = get_posts(
			array(
				'post_type'      => 'rhn_team_member',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_rhn_team_seed_key',
				'meta_value'     => $profile['key'],
			)
		);
		$post_id = $existing ? (int) $existing[0] : wp_insert_post(
			array(
				'post_type'    => 'rhn_team_member',
				'post_status'  => 'publish',
				'post_title'   => $profile['name'],
				'post_content' => $profile['bio'],
				'menu_order'   => $profile['order'],
			)
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}
		update_post_meta( $post_id, '_rhn_team_seed_key', $profile['key'] );
		update_post_meta( $post_id, '_rhn_team_store', $profile['store'] );
		update_post_meta( $post_id, '_rhn_team_role', $profile['role'] );
		update_post_meta( $post_id, '_rhn_team_summary', $profile['summary'] );
		update_post_meta( $post_id, '_rhn_team_photo_position', $profile['position'] );
		update_post_meta( $post_id, '_rhn_team_image_source', $profile['image'] );
		update_post_meta( $post_id, '_rhn_team_image_alt', $profile['alt'] );
		if ( ! empty( $profile['image_class'] ) ) {
			update_post_meta( $post_id, '_rhn_team_image_class', sanitize_html_class( $profile['image_class'] ) );
		} else {
			delete_post_meta( $post_id, '_rhn_team_image_class' );
		}
		if ( ! empty( $profile['media_class'] ) ) {
			update_post_meta( $post_id, '_rhn_team_media_class', sanitize_html_class( $profile['media_class'] ) );
		} else {
			delete_post_meta( $post_id, '_rhn_team_media_class' );
		}
		if ( ! empty( $profile['bundle'] ) && ! has_post_thumbnail( $post_id ) ) {
			rhn_team_import_bundled_image( $post_id, $profile );
		}
	}
	update_option( 'rhn_team_seed_version', RHN_TEAM_VERSION, false );
}

function rhn_team_import_bundled_image( $post_id, $profile ) {
	$source = plugin_dir_path( __FILE__ ) . 'assets/profiles/' . $profile['bundle'];
	if ( ! file_exists( $source ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$temp = wp_tempnam( $profile['bundle'] );
	if ( ! $temp || ! copy( $source, $temp ) ) {
		return;
	}
	$file = array( 'name' => sanitize_file_name( $profile['key'] . '.jpg' ), 'tmp_name' => $temp );
	$attachment_id = media_handle_sideload( $file, $post_id, $profile['name'] . ' profile photo' );
	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $temp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return;
	}
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $profile['alt'] );
	set_post_thumbnail( $post_id, $attachment_id );
}

register_activation_hook( __FILE__, 'rhn_team_seed_profiles' );
add_action( 'init', 'rhn_team_seed_profiles', 30 );

