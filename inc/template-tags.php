<?php
/**
 * Template helpers.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Permalink for a page slug, with plugin settings fallback.
 *
 * @param string $slug Page slug.
 * @return string
 */
function jase_peer_review_page_url( $slug ) {
	static $cache = array();
	if ( array_key_exists( $slug, $cache ) ) {
		return $cache[ $slug ];
	}

	$prs_key_map = array(
		'dashboard'       => 'tpl_dashboard',
		'search-articles' => 'tpl_search_articles',
		'search-editions' => 'tpl_search_editions',
		'login'           => 'tpl_login',
		'register'        => 'tpl_registration',
		'forgot-password' => 'tpl_reset',
	);

	if ( isset( $prs_key_map[ $slug ] ) ) {
		$opt_name = apply_filters( 'prs_settings_option_name', 'prs_settings' );
		$prs      = get_option( $opt_name, array() );
		$key      = $prs_key_map[ $slug ];
		$page_id  = ! empty( $prs[ $key ] ) ? absint( $prs[ $key ] ) : 0;
		if ( $page_id ) {
			$permalink = get_permalink( $page_id );
			if ( $permalink ) {
				$cache[ $slug ] = $permalink;
				return $permalink;
			}
		}
	}

	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $page ) {
		$cache[ $slug ] = get_permalink( $page );
		return $cache[ $slug ];
	}

	$cache[ $slug ] = home_url( '/' );
	return $cache[ $slug ];
}

/**
 * Dashboard URL after login or registration (plugin helper with theme fallback).
 *
 * @return string
 */
function jase_peer_review_dashboard_redirect_url() {
	if ( function_exists( 'peer_review_system_dashboard_page_uri' ) ) {
		$url = peer_review_system_dashboard_page_uri();
		if ( $url && trailingslashit( $url ) !== trailingslashit( home_url( '/' ) ) ) {
			return $url;
		}
	}

	$url = jase_peer_review_page_url( 'dashboard' );
	if ( ! $url || trailingslashit( $url ) === trailingslashit( home_url( '/' ) ) ) {
		return home_url( '/' );
	}

	if ( is_user_logged_in() ) {
		$url = add_query_arg( 'useridentity', get_current_user_id(), $url );
	}

	return $url;
}

/**
 * Login or registration screen (template slug, page slug, or plugin page ID).
 *
 * @return bool
 */
function jase_peer_review_is_auth_page() {
	static $cached = null;
	if ( null !== $cached ) {
		return $cached;
	}

	if ( ! is_page() ) {
		$cached = false;
		return $cached;
	}

	$template = get_page_template_slug();
	if ( in_array( $template, array( 'template-jase-login.php', 'template-jase-register.php' ), true ) ) {
		$cached = true;
		return $cached;
	}

	$post = get_queried_object();
	if ( $post instanceof WP_Post && in_array( $post->post_name, array( 'login', 'register' ), true ) ) {
		$cached = true;
		return $cached;
	}

	$page_id  = get_queried_object_id();
	$opt_name = apply_filters( 'prs_settings_option_name', 'prs_settings' );
	$prs      = get_option( $opt_name, array() );
	if ( is_array( $prs ) ) {
		if ( ! empty( $prs['tpl_login'] ) && (int) $prs['tpl_login'] === $page_id ) {
			$cached = true;
			return $cached;
		}
		if ( ! empty( $prs['tpl_registration'] ) && (int) $prs['tpl_registration'] === $page_id ) {
			$cached = true;
			return $cached;
		}
	}

	$cached = false;
	return $cached;
}

/**
 * Nav menu fallback.
 */
function jase_peer_review_fallback_menu() {
	echo '<ul class="jase-nav__list"><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'jase-peer-review' ) . '</a></li></ul>';
}

/**
 * Peer Review editions post type slug (respects plugin filter when active).
 *
 * @return string
 */
function jase_peer_review_editions_post_type_slug() {
	return apply_filters( 'peer_review_system_edition_post_type_name', 'editions' );
}

/**
 * Peer Review manuscripts post type slug (respects plugin filter when active).
 *
 * @return string
 */
function jase_peer_review_articles_post_type_slug() {
	return apply_filters( 'peer_review_system_articles_post_type_name', 'articles' );
}

/**
 * Published editions for Latest releases on the home page.
 *
 * @param int $limit Max posts.
 * @return WP_Query
 */
function jase_peer_review_latest_editions_query( $limit = 4 ) {
	$pt = jase_peer_review_editions_post_type_slug();
	if ( ! post_type_exists( $pt ) ) {
		return new WP_Query( array( 'post__in' => array( 0 ) ) ); // empty.
	}

	return new WP_Query(
		array(
			'post_type'           => $pt,
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, min( (int) $limit, 12 ) ),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

/**
 * Approved public manuscripts for homepage and archive use.
 *
 * @param array<string,mixed> $args Query overrides.
 * @return WP_Query
 */
function jase_peer_review_approved_manuscripts_query( $args = array() ) {
	$pt = jase_peer_review_articles_post_type_slug();
	if ( ! post_type_exists( $pt ) ) {
		return new WP_Query( array( 'post__in' => array( 0 ) ) ); // empty.
	}

	$defaults = array(
		'posts_per_page'      => 4,
		'paged'               => 1,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	$args     = wp_parse_args( $args, $defaults );

	return new WP_Query(
		array(
			'post_type'           => $pt,
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, min( (int) $args['posts_per_page'], 24 ) ),
			'paged'               => max( 1, (int) $args['paged'] ),
			'orderby'             => sanitize_key( (string) $args['orderby'] ),
			'order'               => ( 'ASC' === strtoupper( (string) $args['order'] ) ) ? 'ASC' : 'DESC',
			'ignore_sticky_posts' => (bool) $args['ignore_sticky_posts'],
			'no_found_rows'       => (bool) $args['no_found_rows'],
			'meta_query'          => array(
				array(
					'key'     => 'prs_article_editor_status',
					'value'   => 'approved',
					'compare' => '=',
				),
				array(
					'key'     => 'prs_publishing_status',
					'value'   => 'published',
					'compare' => '=',
				),
			),
		)
	);
}

/**
 * Public page for approved manuscripts, with search-articles fallback.
 *
 * @return string
 */
function jase_peer_review_manuscripts_archive_url() {
	$url = jase_peer_review_page_url( 'approved-manuscripts' );
	if ( trailingslashit( $url ) !== trailingslashit( home_url( '/' ) ) ) {
		return $url;
	}

	$fallback = jase_peer_review_page_url( 'search-articles' );
	if ( $fallback ) {
		return $fallback;
	}

	return home_url( '/' );
}

/**
 * Article attachments saved by the peer review plugin.
 *
 * @param int $post_id Article ID.
 * @return array<int,array<string,string>>
 */
function jase_peer_review_get_article_attachments( $post_id ) {
	$attachments = get_post_meta( $post_id, 'attachments', true );
	if ( ! is_array( $attachments ) ) {
		return array();
	}

	$clean = array();
	foreach ( $attachments as $attachment ) {
		if ( empty( $attachment['attachment_url'] ) ) {
			continue;
		}

		$clean[] = array(
			'attachment_url'  => esc_url_raw( (string) $attachment['attachment_url'] ),
			'attachment_name' => isset( $attachment['attachment_name'] ) ? sanitize_text_field( (string) $attachment['attachment_name'] ) : '',
			'attachment_size' => isset( $attachment['attachment_size'] ) ? sanitize_text_field( (string) $attachment['attachment_size'] ) : '',
		);
	}

	return $clean;
}

/**
 * Best public download URL for an approved manuscript.
 *
 * @param int $post_id Article ID.
 * @return string
 */
function jase_peer_review_get_article_download_url( $post_id ) {
	$attachments = jase_peer_review_get_article_attachments( $post_id );
	if ( ! empty( $attachments[0]['attachment_url'] ) ) {
		return $attachments[0]['attachment_url'];
	}

	$paid_mode = get_post_meta( $post_id, 'sj_paid_article', true );
	if ( 'download' === $paid_mode ) {
		$external_url = get_post_meta( $post_id, 'article_download_url', true );
		if ( is_string( $external_url ) && $external_url ) {
			return esc_url_raw( $external_url );
		}
	}

	return '';
}

/**
 * Number of downloadable files a manuscript exposes publicly.
 *
 * @param int $post_id Article ID.
 * @return int
 */
function jase_peer_review_get_article_download_count( $post_id ) {
	$attachments = jase_peer_review_get_article_attachments( $post_id );
	if ( ! empty( $attachments ) ) {
		return count( $attachments );
	}

	return jase_peer_review_get_article_download_url( $post_id ) ? 1 : 0;
}

/**
 * Article author display name.
 *
 * @param int $post_id Article ID.
 * @return string
 */
function jase_peer_review_get_article_author_name( $post_id ) {
	$author_id = (int) get_post_field( 'post_author', $post_id );
	if ( ! $author_id ) {
		return '';
	}

	$name = get_the_author_meta( 'display_name', $author_id );
	return is_string( $name ) ? $name : '';
}
