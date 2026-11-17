<?php
/**
 * Template Name: Approved Manuscripts
 * Template Post Type: page
 *
 * @package JASE_Peer_Review
 */

get_header();

$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$q     = jase_peer_review_approved_manuscripts_query(
	array(
		'posts_per_page' => 12,
		'paged'          => $paged,
		'no_found_rows'  => false,
	)
);
?>
<main id="jase-main" class="jase-main jase-main--archive jase-main--manuscripts">
	<header class="jase-archive-hero">
		<div class="jase-container jase-reveal">
			<p class="jase-archive-hero__kicker"><?php esc_html_e( 'Journal archive', 'jase-peer-review' ); ?></p>
			<h1 class="jase-archive-hero__title"><?php esc_html_e( 'Approved manuscripts', 'jase-peer-review' ); ?></h1>
			<p class="jase-archive-hero__lead"><?php esc_html_e( 'Browse every approved manuscript published by the journal and download available files directly from each record.', 'jase-peer-review' ); ?></p>
		</div>
	</header>

	<div class="jase-container jase-manuscripts">
		<?php if ( $q->have_posts() ) : ?>
			<div class="jase-manuscript-grid">
				<?php
				while ( $q->have_posts() ) :
					$q->the_post();
					$id           = get_the_ID();
					$author_name  = jase_peer_review_get_article_author_name( $id );
					$attachments  = jase_peer_review_get_article_attachments( $id );
					$download_url = jase_peer_review_get_article_download_url( $id );
					$thumb        = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'large' ) : '';
					?>
					<article class="jase-manuscript-card jase-reveal">
						<a class="jase-manuscript-card__cover" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
							<?php if ( $thumb ) : ?>
								<img src="<?php echo esc_url( $thumb ); ?>" alt="" width="560" height="360" decoding="async" loading="lazy" />
							<?php else : ?>
								<span class="jase-manuscript-card__cover-placeholder"></span>
							<?php endif; ?>
						</a>
						<div class="jase-manuscript-card__body">
							<p class="jase-manuscript-card__eyebrow"><?php esc_html_e( 'Approved manuscript', 'jase-peer-review' ); ?></p>
							<h2 class="jase-manuscript-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="jase-manuscript-card__meta">
								<?php
								echo esc_html( get_the_date() );
								if ( $author_name ) {
									echo ' - ' . esc_html( $author_name );
								}
								?>
							</p>
							<div class="jase-manuscript-card__excerpt">
								<?php echo wp_kses_post( wpautop( wp_trim_words( get_the_excerpt(), 28 ) ) ); ?>
							</div>

							<?php if ( ! empty( $attachments ) ) : ?>
								<ul class="jase-manuscript-card__downloads" role="list">
									<?php foreach ( $attachments as $attachment ) : ?>
										<li class="jase-manuscript-card__download-item">
											<div>
												<span class="jase-manuscript-card__download-name"><?php echo esc_html( $attachment['attachment_name'] ? $attachment['attachment_name'] : __( 'Manuscript file', 'jase-peer-review' ) ); ?></span>
												<?php if ( $attachment['attachment_size'] ) : ?>
													<span class="jase-manuscript-card__download-size"><?php echo esc_html( $attachment['attachment_size'] ); ?></span>
												<?php endif; ?>
											</div>
											<a class="jase-btn jase-btn--ghost" href="<?php echo esc_url( $attachment['attachment_url'] ); ?>" download><?php esc_html_e( 'Download', 'jase-peer-review' ); ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<div class="jase-manuscript-card__actions">
								<a class="jase-btn jase-btn--primary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View manuscript', 'jase-peer-review' ); ?></a>
								<?php if ( $download_url ) : ?>
									<a class="jase-btn jase-btn--primary" href="<?php echo esc_url( $download_url ); ?>" download><?php esc_html_e( 'Quick download', 'jase-peer-review' ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php if ( $q->max_num_pages > 1 ) : ?>
				<nav class="jase-pagination" aria-label="<?php esc_attr_e( 'Approved manuscripts pagination', 'jase-peer-review' ); ?>">
					<?php
					echo wp_kses_post(
						paginate_links(
							array(
								'total'     => (int) $q->max_num_pages,
								'current'   => $paged,
								'type'      => 'list',
								'prev_text' => __( 'Previous', 'jase-peer-review' ),
								'next_text' => __( 'Next', 'jase-peer-review' ),
							)
						)
					);
					?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<p class="jase-empty jase-reveal"><?php esc_html_e( 'No approved manuscripts are published yet.', 'jase-peer-review' ); ?></p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</main>
<?php
get_footer();
