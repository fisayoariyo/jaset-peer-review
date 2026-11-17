<?php
/**
 * Minimal header for JASE auth templates (register / login).
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
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
