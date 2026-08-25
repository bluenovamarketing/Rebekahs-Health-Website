<?php
/**
 * Plugin Name: Rebekah's iContact Connector
 * Description: Adds successful submissions from Rebekah's canonical Forminator signup form to a selected iContact list.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined( 'ABSPATH' ) || exit;

final class Rebekahs_IContact_Connector {
	const VERSION      = '1.0.0';
	const OPTION_NAME  = 'rebekahs_icontact_connector';
	const FORM_ID      = 313;
	const API_BASE     = 'https://app.icontact.com/icp/a/';
	const API_VERSION  = '2.2';
	const SETTINGS_SLUG = 'rebekahs-icontact';

	/**
	 * Bootstrap the connector.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_settings_page' ) );
		add_action( 'admin_post_rebekahs_icontact_save', array( __CLASS__, 'save_settings' ) );
		add_action( 'forminator_after_handle_form', array( __CLASS__, 'handle_form_submission' ), 20, 1 );
	}

	/**
	 * Add the settings screen beneath WordPress Settings.
	 */
	public static function register_settings_page() {
		add_options_page(
			__( "Rebekah's iContact", 'rebekahs-icontact' ),
			__( "Rebekah's iContact", 'rebekahs-icontact' ),
			'manage_options',
			self::SETTINGS_SLUG,
			array( __CLASS__, 'render_settings_page' )
		);
	}

	/**
	 * Render the connector settings.
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$options       = self::get_options();
		$has_password  = ! empty( $options['api_password'] );
		$lists         = isset( $options['lists'] ) && is_array( $options['lists'] ) ? $options['lists'] : array();
		$custom_fields = isset( $options['custom_fields'] ) && is_array( $options['custom_fields'] ) ? $options['custom_fields'] : array();
		$notice        = get_transient( 'rebekahs_icontact_admin_notice' );
		delete_transient( 'rebekahs_icontact_admin_notice' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( "Rebekah's iContact Connector", 'rebekahs-icontact' ); ?></h1>
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %d is the Forminator form ID. */
						__( 'This connector sends successful submissions from Forminator form <strong>%d</strong> to the selected iContact list. It does not send SMS messages.', 'rebekahs-icontact' ),
						self::FORM_ID
					)
				);
				?>
			</p>

			<?php if ( is_array( $notice ) && ! empty( $notice['message'] ) ) : ?>
				<div class="notice notice-<?php echo esc_attr( ! empty( $notice['success'] ) ? 'success' : 'error' ); ?> is-dismissible">
					<p><?php echo esc_html( $notice['message'] ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="rebekahs_icontact_save">
				<?php wp_nonce_field( 'rebekahs_icontact_save', 'rebekahs_icontact_nonce' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable syncing', 'rebekahs-icontact' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="enabled" value="1" <?php checked( ! empty( $options['enabled'] ) ); ?>>
								<?php esc_html_e( 'Send new signup submissions to iContact', 'rebekahs-icontact' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="rebekahs-icontact-app-id"><?php esc_html_e( 'Application ID', 'rebekahs-icontact' ); ?></label></th>
						<td><input class="regular-text" id="rebekahs-icontact-app-id" name="app_id" type="text" value="<?php echo esc_attr( $options['app_id'] ); ?>" autocomplete="off"></td>
					</tr>
					<tr>
						<th scope="row"><label for="rebekahs-icontact-username"><?php esc_html_e( 'API username', 'rebekahs-icontact' ); ?></label></th>
						<td><input class="regular-text" id="rebekahs-icontact-username" name="api_username" type="text" value="<?php echo esc_attr( $options['api_username'] ); ?>" autocomplete="off"></td>
					</tr>
					<tr>
						<th scope="row"><label for="rebekahs-icontact-password"><?php esc_html_e( 'API password', 'rebekahs-icontact' ); ?></label></th>
						<td>
							<input class="regular-text" id="rebekahs-icontact-password" name="api_password" type="password" value="" autocomplete="new-password" placeholder="<?php echo esc_attr( $has_password ? __( 'Saved — leave blank to keep it', 'rebekahs-icontact' ) : '' ); ?>">
							<p class="description"><?php esc_html_e( 'Use the password created for the iContact API integration, not the normal account password.', 'rebekahs-icontact' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Discovered account', 'rebekahs-icontact' ); ?></th>
						<td>
							<code><?php echo esc_html( $options['account_id'] ? $options['account_id'] : __( 'Not connected', 'rebekahs-icontact' ) ); ?></code>
							<?php if ( ! empty( $options['folder_id'] ) ) : ?>
								<span> / <?php echo esc_html( $options['folder_id'] ); ?></span>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="rebekahs-icontact-list"><?php esc_html_e( 'iContact list', 'rebekahs-icontact' ); ?></label></th>
						<td>
							<select id="rebekahs-icontact-list" name="list_id">
								<option value=""><?php esc_html_e( 'Select a list after testing the connection', 'rebekahs-icontact' ); ?></option>
								<?php foreach ( $lists as $list ) : ?>
									<?php
									$list_id = isset( $list['listId'] ) && is_scalar( $list['listId'] ) ? (string) $list['listId'] : '';
									$name    = isset( $list['name'] ) && is_scalar( $list['name'] ) ? (string) $list['name'] : $list_id;
									if ( '' === $list_id ) {
										continue;
									}
									?>
									<option value="<?php echo esc_attr( $list_id ); ?>" <?php selected( $options['list_id'], $list_id ); ?>><?php echo esc_html( $name ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="rebekahs-icontact-store-field"><?php esc_html_e( 'Preferred store field', 'rebekahs-icontact' ); ?></label></th>
						<td>
							<select id="rebekahs-icontact-store-field" name="store_field">
								<option value=""><?php esc_html_e( 'Do not send preferred store', 'rebekahs-icontact' ); ?></option>
								<?php foreach ( $custom_fields as $field ) : ?>
									<?php
									$private_name = isset( $field['privateName'] ) && is_scalar( $field['privateName'] ) ? (string) $field['privateName'] : '';
									$public_name  = isset( $field['publicName'] ) && is_scalar( $field['publicName'] ) ? (string) $field['publicName'] : $private_name;
									if ( '' === $private_name ) {
										continue;
									}
									?>
									<option value="<?php echo esc_attr( $private_name ); ?>" <?php selected( $options['store_field'], $private_name ); ?>><?php echo esc_html( $public_name ); ?></option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php esc_html_e( 'Maps Forminator select-1 to an existing iContact custom field.', 'rebekahs-icontact' ); ?></p>
						</td>
					</tr>
				</table>

				<p class="submit">
					<button class="button button-primary" type="submit" name="operation" value="save"><?php esc_html_e( 'Save settings', 'rebekahs-icontact' ); ?></button>
					<button class="button" type="submit" name="operation" value="test"><?php esc_html_e( 'Save and test connection', 'rebekahs-icontact' ); ?></button>
				</p>
			</form>

			<?php if ( ! empty( $options['last_sync'] ) ) : ?>
				<h2><?php esc_html_e( 'Last sync attempt', 'rebekahs-icontact' ); ?></h2>
				<p>
					<strong><?php echo esc_html( $options['last_sync']['status'] ); ?></strong>
					— <?php echo esc_html( $options['last_sync']['time'] ); ?>
					<?php if ( ! empty( $options['last_sync']['message'] ) ) : ?>
						<br><?php echo esc_html( $options['last_sync']['message'] ); ?>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Persist settings and optionally test the API connection.
	 */
	public static function save_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to manage these settings.', 'rebekahs-icontact' ) );
		}

		check_admin_referer( 'rebekahs_icontact_save', 'rebekahs_icontact_nonce' );

		$options              = self::get_options();
		$old_credential_stamp = $options['app_id'] . '|' . $options['api_username'];
		$options['enabled']    = ! empty( $_POST['enabled'] ) ? 1 : 0;
		$options['app_id']     = isset( $_POST['app_id'] ) ? sanitize_text_field( wp_unslash( $_POST['app_id'] ) ) : '';
		$options['api_username'] = isset( $_POST['api_username'] ) ? sanitize_text_field( wp_unslash( $_POST['api_username'] ) ) : '';

		$password = isset( $_POST['api_password'] ) ? (string) wp_unslash( $_POST['api_password'] ) : '';
		if ( '' !== $password ) {
			$encrypted = self::encrypt_secret( $password );
			if ( is_wp_error( $encrypted ) ) {
				self::set_notice( false, $encrypted->get_error_message() );
				self::redirect_to_settings();
			}
			$options['api_password'] = $encrypted;
		}

		$new_credential_stamp = $options['app_id'] . '|' . $options['api_username'];
		if ( $old_credential_stamp !== $new_credential_stamp || '' !== $password ) {
			$options['account_id']    = '';
			$options['folder_id']     = '';
			$options['lists']         = array();
			$options['custom_fields'] = array();
		}

		$options['list_id']    = isset( $_POST['list_id'] ) ? sanitize_text_field( wp_unslash( $_POST['list_id'] ) ) : '';
		$posted_store_field = isset( $_POST['store_field'] ) ? sanitize_text_field( wp_unslash( $_POST['store_field'] ) ) : '';
		$options['store_field'] = self::is_known_custom_field( $posted_store_field, $options['custom_fields'] ) ? $posted_store_field : '';

		$operation = isset( $_POST['operation'] ) ? sanitize_key( wp_unslash( $_POST['operation'] ) ) : 'save';
		if ( 'test' === $operation ) {
			$test = self::discover_account( $options );
			if ( is_wp_error( $test ) ) {
				update_option( self::OPTION_NAME, $options, false );
				self::set_notice( false, $test->get_error_message() );
				self::redirect_to_settings();
			}

			$options['account_id'] = $test['account_id'];
			$options['folder_id']  = $test['folder_id'];
			$options['lists']      = $test['lists'];
			$options['custom_fields'] = $test['custom_fields'];
			self::set_notice( true, __( 'Connection successful. Choose the correct iContact list, save the settings, and then enable syncing.', 'rebekahs-icontact' ) );
		} else {
			self::set_notice( true, __( 'iContact connector settings saved.', 'rebekahs-icontact' ) );
		}

		update_option( self::OPTION_NAME, $options, false );
		self::redirect_to_settings();
	}

	/**
	 * Sync a successful Forminator submission.
	 *
	 * @param Forminator_Form_Entry_Model|mixed $entry Forminator entry model.
	 */
	public static function handle_form_submission( $entry ) {
		if ( ! is_object( $entry ) || ! isset( $entry->form_id ) || self::FORM_ID !== (int) $entry->form_id ) {
			return;
		}

		if ( isset( $entry->status ) && ! in_array( $entry->status, array( 'active', '' ), true ) ) {
			return;
		}

		$options = self::get_options();
		if ( empty( $options['enabled'] ) || empty( $options['account_id'] ) || empty( $options['folder_id'] ) || empty( $options['list_id'] ) ) {
			return;
		}

		$email = self::entry_value( $entry, 'email-1' );
		if ( ! is_email( $email ) ) {
			self::record_sync( 'failed', __( 'Submission did not contain a valid email address.', 'rebekahs-icontact' ) );
			return;
		}

		$contact = array( 'email' => sanitize_email( $email ) );
		$phone   = self::entry_value( $entry, 'phone-1' );
		$store   = self::entry_value( $entry, 'select-1' );

		if ( '' !== $phone ) {
			$contact['phone'] = sanitize_text_field( $phone );
		}
		if ( '' !== $store && ! empty( $options['store_field'] ) ) {
			$contact[ $options['store_field'] ] = sanitize_text_field( $store );
		}

		$result = self::api_request( $options, 'contacts', 'POST', array( $contact ) );
		if ( is_wp_error( $result ) ) {
			self::record_sync( 'failed', $result->get_error_message() );
			return;
		}

		$contact_id = '';
		if ( ! empty( $result['contacts'][0]['contactId'] ) && is_scalar( $result['contacts'][0]['contactId'] ) ) {
			$contact_id = (string) $result['contacts'][0]['contactId'];
		}
		if ( '' === $contact_id ) {
			self::record_sync( 'failed', __( 'iContact did not return a contact ID.', 'rebekahs-icontact' ) );
			return;
		}

		$subscription = self::api_request(
			$options,
			'subscriptions',
			'POST',
			array(
				array(
					'contactId' => $contact_id,
					'listId'    => (string) $options['list_id'],
					'status'    => 'normal',
				),
			)
		);

		if ( is_wp_error( $subscription ) ) {
			self::record_sync( 'failed', $subscription->get_error_message() );
			return;
		}

		self::record_sync( 'success', __( 'The latest signup was added to the configured iContact list.', 'rebekahs-icontact' ) );
	}

	/**
	 * Test credentials, discover the first accessible account/folder, and load lists and custom fields.
	 *
	 * @param array $options Connector options.
	 * @return array|WP_Error
	 */
	private static function discover_account( $options ) {
		$password = self::decrypt_secret( $options['api_password'] );
		if ( is_wp_error( $password ) || '' === $options['app_id'] || '' === $options['api_username'] || '' === $password ) {
			return new WP_Error( 'missing_credentials', __( 'Enter the iContact Application ID, API username, and API password first.', 'rebekahs-icontact' ) );
		}

		$headers = self::api_headers( $options, $password );
		$account_response = self::remote_request( self::API_BASE, 'GET', $headers );
		if ( is_wp_error( $account_response ) ) {
			return $account_response;
		}

		$account_id = isset( $account_response['accounts'][0]['accountId'] ) && is_scalar( $account_response['accounts'][0]['accountId'] ) ? (string) $account_response['accounts'][0]['accountId'] : '';
		if ( '' === $account_id ) {
			return new WP_Error( 'no_account', __( 'The credentials worked, but iContact did not return an account.', 'rebekahs-icontact' ) );
		}

		$folder_response = self::remote_request( self::API_BASE . rawurlencode( $account_id ) . '/c/', 'GET', $headers );
		if ( is_wp_error( $folder_response ) ) {
			return $folder_response;
		}

		$folder_id = isset( $folder_response['clientfolders'][0]['clientFolderId'] ) && is_scalar( $folder_response['clientfolders'][0]['clientFolderId'] ) ? (string) $folder_response['clientfolders'][0]['clientFolderId'] : '';
		if ( '' === $folder_id ) {
			return new WP_Error( 'no_folder', __( 'The account does not contain an accessible iContact client folder.', 'rebekahs-icontact' ) );
		}

		$base = self::API_BASE . rawurlencode( $account_id ) . '/c/' . rawurlencode( $folder_id ) . '/';
		$lists_response = self::remote_request( $base . 'lists', 'GET', $headers );
		if ( is_wp_error( $lists_response ) ) {
			return $lists_response;
		}

		$fields_response = self::remote_request( $base . 'customfields', 'GET', $headers );
		if ( is_wp_error( $fields_response ) ) {
			$fields_response = array( 'customfields' => array() );
		}

		return array(
			'account_id'    => $account_id,
			'folder_id'     => $folder_id,
			'lists'         => isset( $lists_response['lists'] ) && is_array( $lists_response['lists'] ) ? $lists_response['lists'] : array(),
			'custom_fields' => isset( $fields_response['customfields'] ) && is_array( $fields_response['customfields'] ) ? $fields_response['customfields'] : array(),
		);
	}

	/**
	 * Make an authenticated request relative to the selected iContact account/folder.
	 *
	 * @param array  $options Connector options.
	 * @param string $endpoint Relative API endpoint.
	 * @param string $method HTTP method.
	 * @param array  $body Request body.
	 * @return array|WP_Error
	 */
	private static function api_request( $options, $endpoint, $method = 'GET', $body = array() ) {
		$password = self::decrypt_secret( $options['api_password'] );
		if ( is_wp_error( $password ) ) {
			return $password;
		}

		$url = self::API_BASE . rawurlencode( (string) $options['account_id'] ) . '/c/' . rawurlencode( (string) $options['folder_id'] ) . '/' . ltrim( $endpoint, '/' );
		return self::remote_request( $url, $method, self::api_headers( $options, $password ), $body );
	}

	/**
	 * Send a request and return decoded JSON without logging subscriber data or credentials.
	 *
	 * @param string $url API URL.
	 * @param string $method HTTP method.
	 * @param array  $headers Request headers.
	 * @param array  $body Request body.
	 * @return array|WP_Error
	 */
	private static function remote_request( $url, $method, $headers, $body = array() ) {
		$args = array(
			'method'  => $method,
			'headers' => $headers,
			'timeout' => 30,
		);

		if ( ! empty( $body ) ) {
			$args['body'] = wp_json_encode( $body );
		}

		$response = wp_remote_request( $url, $args );
		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'icontact_transport', sanitize_text_field( $response->get_error_message() ) );
		}

		$status = (int) wp_remote_retrieve_response_code( $response );
		$data   = json_decode( wp_remote_retrieve_body( $response ), true );
		$data   = is_array( $data ) ? $data : array();

		if ( $status < 200 || $status >= 300 ) {
			$message = sprintf( __( 'iContact returned HTTP %d.', 'rebekahs-icontact' ), $status );
			foreach ( array( 'errors', 'warnings' ) as $key ) {
				if ( ! empty( $data[ $key ][0] ) ) {
					$item = $data[ $key ][0];
					if ( is_array( $item ) ) {
						$item = isset( $item['message'] ) ? $item['message'] : reset( $item );
					}
					if ( is_scalar( $item ) ) {
						$message = sanitize_text_field( (string) $item );
					}
					break;
				}
			}
			return new WP_Error( 'icontact_api', $message, array( 'status' => $status ) );
		}

		return $data;
	}

	/**
	 * Build iContact API headers.
	 *
	 * @param array  $options Connector options.
	 * @param string $password Decrypted API password.
	 * @return array
	 */
	private static function api_headers( $options, $password ) {
		return array(
			'Accept'       => 'application/json',
			'Content-Type' => 'application/json',
			'Api-Version'  => self::API_VERSION,
			'Api-AppId'    => (string) $options['app_id'],
			'Api-Username' => (string) $options['api_username'],
			'Api-Password' => $password,
		);
	}

	/**
	 * Read and normalize one Forminator entry value.
	 *
	 * @param object $entry Forminator entry model.
	 * @param string $key Field key.
	 * @return string
	 */
	private static function entry_value( $entry, $key ) {
		$value = method_exists( $entry, 'get_meta' ) ? $entry->get_meta( $key, '' ) : '';
		if ( is_array( $value ) ) {
			$value = implode( ', ', array_filter( array_map( 'strval', $value ) ) );
		}
		return is_scalar( $value ) ? trim( (string) $value ) : '';
	}

	/**
	 * Confirm that a submitted field key came from the current iContact field list.
	 *
	 * @param string $candidate Candidate private field name.
	 * @param array  $fields Cached iContact custom fields.
	 * @return bool
	 */
	private static function is_known_custom_field( $candidate, $fields ) {
		if ( '' === $candidate || ! is_array( $fields ) ) {
			return false;
		}

		foreach ( $fields as $field ) {
			if ( is_array( $field ) && isset( $field['privateName'] ) && is_scalar( $field['privateName'] ) && hash_equals( (string) $field['privateName'], $candidate ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Encrypt the API password with the site's WordPress salts.
	 *
	 * @param string $secret Plaintext secret.
	 * @return string|WP_Error
	 */
	private static function encrypt_secret( $secret ) {
		if ( ! function_exists( 'openssl_encrypt' ) || ! function_exists( 'openssl_random_pseudo_bytes' ) ) {
			return new WP_Error( 'encryption_unavailable', __( 'OpenSSL is required to store the iContact API password securely.', 'rebekahs-icontact' ) );
		}

		$cipher = 'aes-256-cbc';
		$iv_len = openssl_cipher_iv_length( $cipher );
		$iv     = openssl_random_pseudo_bytes( $iv_len );
		if ( ! is_string( $iv ) || strlen( $iv ) !== $iv_len ) {
			return new WP_Error( 'encryption_failed', __( 'The iContact API password could not be encrypted.', 'rebekahs-icontact' ) );
		}
		$key    = hash( 'sha256', wp_salt( 'auth' ), true );
		$value  = openssl_encrypt( $secret, $cipher, $key, OPENSSL_RAW_DATA, $iv );
		if ( false === $value ) {
			return new WP_Error( 'encryption_failed', __( 'The iContact API password could not be encrypted.', 'rebekahs-icontact' ) );
		}

		return 'enc:v1:' . base64_encode( $iv . $value );
	}

	/**
	 * Decrypt the stored API password.
	 *
	 * @param string $stored Encrypted secret.
	 * @return string|WP_Error
	 */
	private static function decrypt_secret( $stored ) {
		if ( '' === (string) $stored ) {
			return '';
		}
		if ( 0 !== strpos( $stored, 'enc:v1:' ) || ! function_exists( 'openssl_decrypt' ) ) {
			return new WP_Error( 'decryption_failed', __( 'The stored iContact API password cannot be read. Enter it again.', 'rebekahs-icontact' ) );
		}

		$decoded = base64_decode( substr( $stored, 7 ), true );
		$cipher  = 'aes-256-cbc';
		$iv_len  = openssl_cipher_iv_length( $cipher );
		if ( false === $decoded || strlen( $decoded ) <= $iv_len ) {
			return new WP_Error( 'decryption_failed', __( 'The stored iContact API password cannot be read. Enter it again.', 'rebekahs-icontact' ) );
		}

		$iv     = substr( $decoded, 0, $iv_len );
		$value  = substr( $decoded, $iv_len );
		$key    = hash( 'sha256', wp_salt( 'auth' ), true );
		$secret = openssl_decrypt( $value, $cipher, $key, OPENSSL_RAW_DATA, $iv );
		return false === $secret ? new WP_Error( 'decryption_failed', __( 'The stored iContact API password cannot be read. Enter it again.', 'rebekahs-icontact' ) ) : $secret;
	}

	/**
	 * Store a non-sensitive sync result for administrators.
	 *
	 * @param string $status Result status.
	 * @param string $message Safe status message.
	 */
	private static function record_sync( $status, $message ) {
		$options = self::get_options();
		$options['last_sync'] = array(
			'status'  => sanitize_key( $status ),
			'message' => sanitize_text_field( $message ),
			'time'    => current_time( 'mysql' ),
		);
		update_option( self::OPTION_NAME, $options, false );
	}

	/**
	 * Retrieve options with defaults.
	 *
	 * @return array
	 */
	private static function get_options() {
		$defaults = array(
			'enabled'       => 0,
			'app_id'        => '',
			'api_username'  => '',
			'api_password'  => '',
			'account_id'    => '',
			'folder_id'     => '',
			'list_id'       => '',
			'store_field'   => '',
			'lists'         => array(),
			'custom_fields' => array(),
			'last_sync'     => array(),
		);

		$options = get_option( self::OPTION_NAME, array() );
		return wp_parse_args( is_array( $options ) ? $options : array(), $defaults );
	}

	/**
	 * Queue a one-time admin notice.
	 *
	 * @param bool   $success Whether the action succeeded.
	 * @param string $message Notice text.
	 */
	private static function set_notice( $success, $message ) {
		set_transient(
			'rebekahs_icontact_admin_notice',
			array(
				'success' => (bool) $success,
				'message' => sanitize_text_field( $message ),
			),
			MINUTE_IN_SECONDS
		);
	}

	/**
	 * Return to the settings page.
	 */
	private static function redirect_to_settings() {
		wp_safe_redirect( admin_url( 'options-general.php?page=' . self::SETTINGS_SLUG ) );
		exit;
	}
}

Rebekahs_IContact_Connector::init();
