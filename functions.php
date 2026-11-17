<?php
/**
 * JASET Peer Review theme bootstrap.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'JASE_PEER_REVIEW_VERSION', '1.8.4' );
define( 'JASE_PEER_REVIEW_DIR', get_template_directory() );
define( 'JASE_PEER_REVIEW_URI', get_template_directory_uri() );

require_once JASE_PEER_REVIEW_DIR . '/inc/prs-compat.php';
require_once JASE_PEER_REVIEW_DIR . '/inc/template-tags.php';
require_once JASE_PEER_REVIEW_DIR . '/inc/class-jase-theme-setup.php';
require_once JASE_PEER_REVIEW_DIR . '/inc/customizer.php';
require_once JASE_PEER_REVIEW_DIR . '/inc/woocommerce.php';

/**
 * Theme defaults.
 */
function jase_peer_review_setup() {
	load_theme_textdomain( 'jase-peer-review', JASE_PEER_REVIEW_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 360,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'jase-peer-review' ),
			'footer'  => __( 'Footer menu', 'jase-peer-review' ),
		)
	);

	add_theme_support( 'editor-styles' );
	add_editor_style(
		array(
			'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Literata:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap',
			'assets/css/editor-style.css',
		)
	);
}
add_action( 'after_setup_theme', 'jase_peer_review_setup' );

/**
 * Body class for global layout hooks.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function jase_peer_review_body_class( $classes ) {
	$classes[] = 'jase-site';
	if ( is_front_page() ) {
		$classes[] = 'jase-front-tier3';
	}
	if ( function_exists( 'jase_peer_review_is_auth_page' ) && jase_peer_review_is_auth_page() ) {
		$classes[] = 'jase-auth-page';
		if ( is_page( 'register' ) || is_page_template( 'template-jase-register.php' ) ) {
			$classes[] = 'jase-auth-page--register';
			$classes[] = 'jase-register-split';
		} else {
			$classes[] = 'jase-auth-page--login';
			$classes[] = 'jase-login-split';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'jase_peer_review_body_class' );

/**
 * Preconnect to font CDN.
 */
function jase_peer_review_font_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
}
add_action( 'wp_head', 'jase_peer_review_font_preconnect', 1 );

/**
 * Content width for embeds and blocks.
 */
function jase_peer_review_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'jase_peer_review_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function jase_peer_review_scripts() {
	wp_enqueue_style(
		'jase-peer-review-fonts',
		'https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'jase-peer-review-base',
		JASE_PEER_REVIEW_URI . '/assets/css/main.css',
		array( 'jase-peer-review-fonts' ),
		JASE_PEER_REVIEW_VERSION
	);

	wp_enqueue_script(
		'jase-peer-review-theme',
		JASE_PEER_REVIEW_URI . '/assets/js/theme.js',
		array(),
		JASE_PEER_REVIEW_VERSION,
		true
	);

	wp_localize_script(
		'jase-peer-review-theme',
		'jasePeerReview',
		array(
			'registerAsParam'   => 'register_as',
			'ariaOpenMenu'      => __( 'Open menu', 'jase-peer-review' ),
			'ariaCloseMenu'     => __( 'Close menu', 'jase-peer-review' ),
			'ariaShowPassword'  => __( 'Show password', 'jase-peer-review' ),
			'ariaHidePassword'  => __( 'Hide password', 'jase-peer-review' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'jase_peer_review_scripts' );

/**
 * Auth (register + login) split / mobile stacked layout styles.
 */
function jase_peer_review_auth_template_styles() {
	if ( ! function_exists( 'jase_peer_review_is_auth_page' ) || ! jase_peer_review_is_auth_page() ) {
		return;
	}
	wp_enqueue_style(
		'jase-auth',
		JASE_PEER_REVIEW_URI . '/assets/css/jase-auth.css',
		array( 'jase-peer-review-base' ),
		JASE_PEER_REVIEW_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'jase_peer_review_auth_template_styles', 30 );

/**
 * Login / register: ensure post-auth redirect targets the dashboard.
 */
function jase_peer_review_auth_scripts() {
	if ( ! function_exists( 'jase_peer_review_is_auth_page' ) || ! jase_peer_review_is_auth_page() ) {
		return;
	}

	wp_enqueue_script(
		'jase-peer-review-auth',
		JASE_PEER_REVIEW_URI . '/assets/js/auth.js',
		array( 'jquery' ),
		JASE_PEER_REVIEW_VERSION,
		true
	);

	wp_localize_script(
		'jase-peer-review-auth',
		'jasePeerReviewAuth',
		array(
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'ajaxNonce'        => wp_create_nonce( 'ajax_nonce' ),
			'dashboardUrl'     => jase_peer_review_dashboard_redirect_url(),
			'loadingText'      => __( 'Please wait…', 'jase-peer-review' ),
			'errorTitle'       => __( 'Something went wrong', 'jase-peer-review' ),
			'errorMessage'     => __( 'Please check your details and try again.', 'jase-peer-review' ),
			'ariaShowPassword' => __( 'Show password', 'jase-peer-review' ),
			'ariaHidePassword' => __( 'Hide password', 'jase-peer-review' ),
			'registerAsParam'  => 'register_as',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'jase_peer_review_auth_scripts', 110 );

/**
 * Inject Customizer logo into the plugin registration shortcode when no logo attr is set.
 *
 * @param bool|string $output Short-circuit return.
 * @param string    $tag     Shortcode name.
 * @param array     $attr    Attributes.
 * @param array     $m       Regex match (unused).
 * @return bool|string
 */
function jase_peer_review_pre_shortcode_registration( $output, $tag, $attr, $m ) {
	if ( 'peer_review_system_registration' !== $tag ) {
		return $output;
	}
	if ( ! function_exists( 'peer_review_system_registration' ) ) {
		return $output;
	}
	if ( ! is_array( $attr ) ) {
		$attr = array();
	}
	if ( empty( $attr['logo'] ) && function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$attr['logo'] = wp_get_attachment_image_url( $logo_id, 'full' );
		}
	}
	ob_start();
	peer_review_system_registration( $attr );
	return ob_get_clean();
}
/**
 * Register shortcode enhancements after WordPress loads.
 */
function jase_peer_review_register_shortcode_filters() {
	if ( version_compare( get_bloginfo( 'version' ), '6.3', '>=' ) ) {
		add_filter( 'pre_do_shortcode_tag', 'jase_peer_review_pre_shortcode_registration', 10, 4 );
	}
}
add_action( 'init', 'jase_peer_review_register_shortcode_filters', 0 );

/**
 * Run one-time / versioned setup (pages, menus, plugin options merge).
 */
function jase_peer_review_run_setup() {
	JASE_Theme_Setup::maybe_run();
}
add_action( 'after_switch_theme', 'jase_peer_review_run_setup' );

/**
 * If the theme was already active, still allow setup when version bumps.
 */
function jase_peer_review_setup_check_version() {
	if ( is_admin() && current_user_can( 'switch_themes' ) ) {
		JASE_Theme_Setup::maybe_run();
	}
}
add_action( 'admin_init', 'jase_peer_review_setup_check_version' );

/**
 * Primary menu labels: match header copy (“About”) without renaming the underlying page title.
 *
 * @param WP_Post[] $items Menu items.
 * @param mixed     $args  Menu arguments.
 * @return WP_Post[]
 */
function jase_peer_review_nav_menu_primary_labels( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location || ! is_array( $items ) ) {
		return $items;
	}

	$allowed_primary = array(
		'about',
		'editorial-board',
		'search-articles',
		'submit-manuscript',
	);
	$register_for_submit = '';
	$rb                  = jase_peer_review_page_url( 'register' );
	if ( '#' !== $rb ) {
		$register_for_submit = add_query_arg( 'register_as', 'article_author', $rb );
	}

	$filtered = array();
	foreach ( $items as $item ) {
		if ( empty( $item->object ) || 'page' !== $item->object || empty( $item->object_id ) ) {
			continue;
		}
		$slug = get_post_field( 'post_name', (int) $item->object_id );
		if ( ! in_array( $slug, $allowed_primary, true ) ) {
			continue;
		}
		if ( 'about' === $slug ) {
			$item->title = __( 'About', 'jase-peer-review' );
		}
		if ( 'search-articles' === $slug ) {
			$item->title = __( 'Manuscripts', 'jase-peer-review' );
		}
		if ( 'submit-manuscript' === $slug && $register_for_submit ) {
			$item->url = esc_url_raw( $register_for_submit );
		}
		$filtered[] = $item;
	}
	return $filtered;
}
add_filter( 'wp_nav_menu_objects', 'jase_peer_review_nav_menu_primary_labels', 10, 2 );

/**
 * Remind administrators to activate the peer review plugin.
 */
function jase_peer_review_plugin_notice() {
	if ( ! current_user_can( 'manage_options' ) || class_exists( 'Peer_Review_System' ) ) {
		return;
	}
	echo '<div class="notice notice-warning is-dismissible"><p>';
	esc_html_e( 'The JASET Peer Review theme expects the “Journal management and Peer review system” plugin to be active.', 'jase-peer-review' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'jase_peer_review_plugin_notice' );
