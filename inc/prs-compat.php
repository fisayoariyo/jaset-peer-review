<?php
/**
 * Plugin compatibility: dashboard cache + “article” → “manuscript” labels (gettext). Does not patch plugin files.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Avoid full-page/CDN caches on the Peer Review dashboard for logged-in users so AJAX nonces stay valid
 * and sidebar counts refresh on reload.
 */
function jase_peer_review_no_cache_peer_dashboard() {
	if ( ! is_user_logged_in() || ! is_page() ) {
		return;
	}
	if ( ! is_page_template( 'templates/dashboard.php' ) ) {
		return;
	}
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	nocache_headers();
	header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
}
add_action( 'template_redirect', 'jase_peer_review_no_cache_peer_dashboard', 0 );

/**
 * Auth pages use theme auth.js — drop plugin public script so select2 init does not break login.
 */
function jase_peer_review_auth_dequeue_plugin_public_script() {
	if ( ! function_exists( 'jase_peer_review_is_auth_page' ) || ! jase_peer_review_is_auth_page() ) {
		return;
	}
	wp_dequeue_script( 'peer-review-system-public' );
}
add_action( 'wp_enqueue_scripts', 'jase_peer_review_auth_dequeue_plugin_public_script', 100 );

/**
 * @param string $translated Unused (gettext passes current translation).
 * @param string $text       Original English string from the plugin.
 * @param string $domain     Text domain.
 */
function jase_peer_review_prs_gettext_article_to_manuscript( $translated, $text, $domain ) {
	if ( 'peer-review-system' !== $domain ) {
		return $translated;
	}

	static $map = null;

	if ( null === $map ) {
		$rquo = "\xE2\x80\x99";
		$map  = array(
			'Add article'                       => __( 'Add manuscript', 'jase-peer-review' ),
			'Under review articles'             => __( 'Under review manuscripts', 'jase-peer-review' ),
			'Approved articles'                 => __( 'Approved manuscripts', 'jase-peer-review' ),
			'Rejected articles'                 => __( 'Rejected manuscripts', 'jase-peer-review' ),
			'Add a new article'                => __( 'Add a new manuscript', 'jase-peer-review' ),
			'Edit article'                      => __( 'Edit manuscript', 'jase-peer-review' ),
			'Sample article file'               => __( 'Sample manuscript file', 'jase-peer-review' ),
			'Download article'                  => __( 'Download manuscript', 'jase-peer-review' ),
			'Article title'                     => __( 'Manuscript title', 'jase-peer-review' ),
			'Enter articles title'              => __( 'Enter manuscript title', 'jase-peer-review' ),
			'Select article category'           => __( 'Select manuscript category', 'jase-peer-review' ),
			'Add article author'                => __( 'Add manuscript author', 'jase-peer-review' ),
			'Article excerpts'                   => __( 'Manuscript excerpts', 'jase-peer-review' ),
			'Enter article excerpts'            => __( 'Enter manuscript excerpts', 'jase-peer-review' ),
			'Article details'                   => __( 'Manuscript details', 'jase-peer-review' ),
			'Re-submit article'                 => __( 'Re-submit manuscript', 'jase-peer-review' ),
			'Articles under review'             => __( 'Manuscripts under review', 'jase-peer-review' ),
			'Submit article now'                => __( 'Submit manuscript now', 'jase-peer-review' ),
			'Minor revision articles'            => __( 'Minor revision manuscripts', 'jase-peer-review' ),
			'Articles major revisions'         => __( 'Major revision manuscripts', 'jase-peer-review' ),
			'Assigned articles'                 => __( 'Assigned manuscripts', 'jase-peer-review' ),
			'Assign articles'                   => __( 'Assign manuscripts', 'jase-peer-review' ),
			'Select articles from list'         => __( 'Select manuscripts from list', 'jase-peer-review' ),
			'Select articles from list below'  => __( 'Select manuscripts from list below', 'jase-peer-review' ),
		);

		$map[ 'I accept all the submiting article' . $rquo . 's' ] = __( 'I accept all the submitting manuscript' . $rquo . 's', 'jase-peer-review' );
	}

	return isset( $map[ $text ] ) ? $map[ $text ] : $translated;
}
add_filter( 'gettext', 'jase_peer_review_prs_gettext_article_to_manuscript', 20, 3 );
