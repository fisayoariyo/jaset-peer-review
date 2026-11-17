<?php
/**
 * WooCommerce integration (optional). Journal starts free; enable plugins + products when needed.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce support when the plugin is active (may load after the theme).
 */
function jase_peer_review_woocommerce_support() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'jase_peer_review_woocommerce_support', 20 );

/**
 * Minimal layout wrapper compatibility (runs after WooCommerce registers its hooks).
 */
function jase_peer_review_woocommerce_wrappers() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	add_action( 'woocommerce_before_main_content', 'jase_peer_review_woocommerce_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'jase_peer_review_woocommerce_wrapper_end', 10 );
}
add_action( 'wp', 'jase_peer_review_woocommerce_wrappers' );

/**
 * @return void
 */
function jase_peer_review_woocommerce_wrapper_start() {
	echo '<div class="jase-wc-wrap">';
}

/**
 * @return void
 */
function jase_peer_review_woocommerce_wrapper_end() {
	echo '</div>';
}
