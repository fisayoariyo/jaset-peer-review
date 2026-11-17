<?php
/**
 * Latest approved manuscripts strip (homepage).
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$limit = isset( $args['limit'] ) ? (int) $args['limit'] : max( 1, min( 8, (int) get_theme_mod( 'jase_latest_releases_count', 4 ) ) );
$q     = jase_peer_review_approved_manuscripts_query(
	array(
		'posts_per_page' => $limit,
		'no_found_rows'  => true,
	)
);
$url   = jase_peer_review_manuscripts_archive_url();
?>
<section class="jase-releases" aria-labelledby="jase-releases-title">
	<div class="jase-container">
		<div class="jase-releases__head">
			<div class="jase-releases__intro">
				<h2 id="jase-releases-title" class="jase-releases__title"><?php esc_html_e( 'Latest releases', 'jase-peer-review' ); ?></h2>
				<p class="jase-releases__note"><?php esc_html_e( 'Approved manuscripts appear here automatically as they are published by the journal.', 'jase-peer-review' ); ?></p>
			</div>
			<?php if ( $url ) : ?>
				<a class="jase-releases__all jase-link-arrow" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'See all manuscripts', 'jase-peer-review' ); ?></a>
			<?php endif; ?>
		</div>
		<?php if ( ! $q->have_posts() ) : ?>
			<p class="jase-releases__empty"><?php esc_html_e( 'Approved manuscripts will appear here once they are published.', 'jase-peer-review' ); ?></p>
			<?php
		else :
			echo '<div class="jase-releases__rail jase-stagger" role="list">';
			while ( $q->have_posts() ) {
				$q->the_post();
				$id           = get_the_ID();
				$thumb        = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'medium_large' ) : '';
				$author_name  = jase_peer_review_get_article_author_name( $id );
				$download_url = jase_peer_review_get_article_download_url( $id );
				$file_count   = jase_peer_review_get_article_download_count( $id );
				?>
				<article class="jase-release-card jase-reveal" role="listitem">
					<a class="jase-release-card__link" href="<?php the_permalink(); ?>">
						<?php if ( $thumb ) : ?>
							<span class="jase-release-card__thumb" aria-hidden="true"><img src="<?php echo esc_url( $thumb ); ?>" alt="" width="440" height="280" decoding="async" loading="lazy" /></span>
						<?php else : ?>
							<span class="jase-release-card__thumb jase-release-card__thumb--placeholder" aria-hidden="true"></span>
						<?php endif; ?>
						<span class="jase-release-card__body">
							<span class="jase-release-card__label"><?php esc_html_e( 'Approved manuscript', 'jase-peer-review' ); ?></span>
							<span class="jase-release-card__name"><?php the_title(); ?></span>
							<span class="jase-release-card__meta">
								<?php
								echo esc_html( get_the_date() );
								if ( $author_name ) {
									echo ' - ' . esc_html( $author_name );
								}
								?>
							</span>
							<?php if ( $file_count ) : ?>
								<span class="jase-release-card__downloads"><?php echo esc_html( sprintf( _n( '%d download', '%d downloads', $file_count, 'jase-peer-review' ), $file_count ) ); ?></span>
							<?php endif; ?>
						</span>
					</a>
					<div class="jase-release-card__actions">
						<a class="jase-btn jase-btn--ghost" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View', 'jase-peer-review' ); ?></a>
						<?php if ( $download_url ) : ?>
							<a class="jase-btn jase-btn--primary" href="<?php echo esc_url( $download_url ); ?>" download><?php esc_html_e( 'Download', 'jase-peer-review' ); ?></a>
						<?php endif; ?>
					</div>
				</article>
				<?php
			}
			echo '</div>';
			wp_reset_postdata();
		endif;
		?>
	</div>
</section>
