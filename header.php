<?php
/**
 * Theme header.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$jase_dept_logo_abs = get_template_directory() . '/assets/images/jase-dept-logo.png';
$jase_dept_logo_uri = get_template_directory_uri() . '/assets/images/jase-dept-logo.png';
$jase_has_dept_logo = is_readable( $jase_dept_logo_abs );
$jase_account_url   = is_user_logged_in() ? jase_peer_review_page_url( 'dashboard' ) : jase_peer_review_page_url( 'login' );
$jase_account_text  = is_user_logged_in() ? __( 'Dashboard', 'jase-peer-review' ) : __( 'Sign in', 'jase-peer-review' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="jase-skip-link screen-reader-text" href="#jase-main"><?php esc_html_e( 'Skip to content', 'jase-peer-review' ); ?></a>
<div class="jase-scroll-progress" aria-hidden="true"><span class="jase-scroll-progress__bar"></span></div>
<header class="jase-header" role="banner">
	<div class="jase-container jase-header__inner">
		<div class="jase-header__brand">
			<?php
			if ( $jase_has_dept_logo ) {
				?>
				<a class="jase-header__logo jase-header__logo--mark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url( $jase_dept_logo_uri ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" width="300" height="120" decoding="async" fetchpriority="high" />
				</a>
				<?php
			} elseif ( has_custom_logo() ) {
				echo '<span class="jase-header__logo">';
				the_custom_logo();
				echo '</span>';
			}
			?>
			<a class="jase-header__title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		</div>
		<button type="button" class="jase-nav-toggle" aria-expanded="false" aria-controls="jase-primary-menu" aria-label="<?php esc_attr_e( 'Open menu', 'jase-peer-review' ); ?>">
			<span class="jase-nav-toggle__bar" aria-hidden="true"></span>
			<span class="jase-nav-toggle__bar" aria-hidden="true"></span>
			<span class="jase-nav-toggle__bar" aria-hidden="true"></span>
		</button>
		<nav id="jase-primary-menu" class="jase-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary', 'jase-peer-review' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'jase-nav__list',
					'fallback_cb'    => 'jase_peer_review_fallback_menu',
				)
			);
			?>
			<div class="jase-nav__account">
				<a class="jase-btn jase-btn--primary" href="<?php echo esc_url( $jase_account_url ); ?>"><?php echo esc_html( $jase_account_text ); ?></a>
			</div>
		</nav>
	</div>
</header>
