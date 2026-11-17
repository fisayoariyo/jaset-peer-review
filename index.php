<?php
/**
 * Main fallback template.
 *
 * @package JASE_Peer_Review
 */

get_header();
?>
<main id="jase-main" class="jase-main jase-main--archive">
	<header class="jase-archive-hero">
		<div class="jase-container jase-reveal">
			<h1 class="jase-archive-hero__title"><?php esc_html_e( 'News & updates', 'jase-peer-review' ); ?></h1>
			<p class="jase-archive-hero__lead"><?php esc_html_e( 'Announcements and posts from the editorial team.', 'jase-peer-review' ); ?></p>
		</div>
	</header>
	<div class="jase-container jase-archive-list">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'jase-loop-card jase-reveal' ); ?>>
					<h2 class="jase-loop-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="jase-loop-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
					<div class="jase-loop-card__excerpt"><?php the_excerpt(); ?></div>
					<a class="jase-loop-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continue reading', 'jase-peer-review' ); ?> →</a>
				</article>
				<?php
			endwhile;
			?>
			<div class="jase-posts-nav-wrap">
				<?php
				the_posts_navigation(
					array(
						'prev_text' => '← ' . __( 'Newer', 'jase-peer-review' ),
						'next_text' => __( 'Older', 'jase-peer-review' ) . ' →',
					)
				);
				?>
			</div>
		<?php else : ?>
			<p class="jase-empty jase-reveal"><?php esc_html_e( 'No posts found.', 'jase-peer-review' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
