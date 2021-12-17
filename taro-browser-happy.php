<?php
/**
Plugin Name: Taro Browser Happy
Plugin URI: https://wordpress.org/plugins/taro-programmable-search/
Description: Navigate the user or display message for old browser.
Author: Tarosky INC.
Version: nightly
Author URI: https://tarosky.co.jp/
License: GPL3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: tbh
Domain Path: /languages
 */

defined( 'ABSPATH' ) or die();

/**
 * Init plugins.
 */
function tbh_init() {
	// Register translations.
	load_plugin_textdomain( 'tbh', false, basename( __DIR__ ) . '/languages' );
	// Composer.
	$composer = __DIR__ . '/vendor/autoload.php';
	if ( file_exists( $composer ) ) {
		// Boostrap.
		require $composer;
	}
	// Register Hooks.
	add_action( 'admin_init', 'tbh_admin_setting' );
	add_action( 'wp_enqueue_scripts', 'tbh_enqueue_script' );
}

/**
 * Get navigation type.
 *
 * @return array
 */
function tbh_navigation_types() {
	return [
		''         => __( 'Display Message', 'tbh' ),
		'navigate' => __( 'Navigation Forcibly', 'tbh' ),
	];
}

/**
 * Register setting
 */
function tbh_admin_setting() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}
	// Register setting section.
	add_settings_section( 'tbh_browser_setting', __( 'Browser Setting', 'tbh' ), function() {
		printf(
			'<p class="description">%s</p>',
			esc_html__( 'Display message for old browser(<= IE11).', 'tbh' )
		);
	}, 'reading' );
	// Register settings field.
	add_settings_field( 'tbh-type', __( 'Navigation Type', 'tbh' ), function() {
		?>
		<select name="tbh-type">
			<?php
			foreach ( tbh_navigation_types() as $value => $label ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $value ),
					selected( $value, get_option( 'tbh-type', '' ), false ),
					esc_html( $label )
				);
			}
			?>
		</select>
		<?php
	}, 'reading', 'tbh_browser_setting' );
	add_settings_field( 'tbh-message', __( 'Message', 'tbh' ), function() {
		?>
		<textarea rows="5" name="tbh-message" class="widefat" style="box-sizing: border-box"><?php echo esc_textarea( get_option( 'tbh-message' ) ); ?></textarea>
		<p class="description">
			<?php esc_html_e( 'Displayed for old browser.', 'tbh' ); ?>
		</p>
		<?php
	}, 'reading', 'tbh_browser_setting' );
	add_settings_field( 'tbh-header', __( 'Header', 'tbh' ), function() {
		?>
		<input name="tbh-header" type="text" class="regular-text" value="<?php echo esc_attr( get_option( 'tbh-header' ) ); ?>" />
		<p class="description">
			<?php esc_html_e( 'Optional. If set, header text will be shown.', 'tbh' ); ?>
		</p>
		<?php
	}, 'reading', 'tbh_browser_setting' );
	add_settings_field( 'tbh-url', __( 'URL to Redirect', 'tbh' ), function() {
		?>
		<input class="regular-text" type="url" name="tbh-url" value="<?php echo esc_url( get_option( 'tbh-url' ) ); ?>" />
		<?php
	}, 'reading', 'tbh_browser_setting' );
	add_settings_field( 'tbh-label', __( 'Link Label', 'tbh' ), function() {
		?>
		<input class="regular-text" type="text" name="tbh-label" value="<?php echo esc_attr( get_option( 'tbh-label' ) ); ?>" placeholder="<?php esc_attr_e( 'See Detail', 'thb' ); ?>" />
		<?php
	}, 'reading', 'tbh_browser_setting' );
	// Register Fields.
	register_setting( 'reading', 'tbh-type' );
	register_setting( 'reading', 'tbh-message' );
	register_setting( 'reading', 'tbh-header' );
	register_setting( 'reading', 'tbh-url' );
	register_setting( 'reading', 'tbh-label' );
}

/**
 * Wait time.
 *
 * @return int
 */
function tbh_wait_time() {
	return max( 1, (int) apply_filters( 'tbh_wait_time', 10 ) );
}

/**
 * Enqueue Old
 */
function tbh_enqueue_script() {
	$info = get_file_data( __FILE__, [
		'version' => 'Version',
	] );
	$base = plugin_dir_url( __FILE__ );
	wp_enqueue_script( 'tbh-happy', $base . 'dist/js/happy.js', [], $info['version'], true );
	wp_localize_script( 'tbh-happy', 'TbhHappy', [
		'css'     => apply_filters( 'tbh_css_url', $base . 'dist/css/happy.css?ver=' . $info['version'] ),
		'message' => wp_kses_post( wpautop( get_option( 'tbh-message' ) ) ),
		'url'     => get_option( 'tbh-url' ),
		'type'    => get_option( 'tbh-type' ),
		'header'  => get_option( 'tbh-header' ),
		'label'   => apply_filters( 'tbh_link_label', get_option( 'tbh-label', esc_html__( 'See Detail', 'thb' ) ) ),
		'wait'    => tbh_wait_time(),
		'close'   => __( 'Close', 'tbh' ),
	] );
}

// Register hooks.
add_action( 'plugins_loaded', 'tbh_init' );
