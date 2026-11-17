<?php
/**
 * 404 template.
 *
 * @package JASE_Peer_Review
 */

get_header();
?>
<main id="jase-main" class="jase-main jase-main--error">
	<div class="jase-error">
		<div class="jase-container jase-error__inner jase-reveal">
			<p class="jase-error__code">404</p>
			<h1 class="jase-error__title"><?php esc_html_e( 'This page drifted off the manuscript pile', 'jase-peer-review' ); ?></h1>
			<p class="jase-error__text"><?php esc_html_e( 'The link may be outdated or the page may have moved. Try the home page or site search.', 'jase-peer-review' ); ?></p>
			<div class="jase-error__actions">
				<a class="jase-btn jase-btn--primary jase-btn--lg" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'jase-peer-review' ); ?></a>
				<a class="jase-btn jase-btn--ghost jase-btn--lg" href="<?php echo esc_url( jase_peer_review_page_url( 'search-articles' ) ); ?>"><?php esc_html_e( 'Search articles', 'jase-peer-review' ); ?></a>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
