<?php
/**
 * Single post.
 *
 * @package JASE_Peer_Review
 */

get_header();
?>
<main id="jase-main" class="jase-main jase-main--single">
	<div class="jase-page-hero jase-page-hero--compact">
		<div class="jase-container">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<header class="jase-single-hero jase-reveal">
					<p class="jase-single-hero__meta"><?php echo esc_html( get_the_date() ); ?></p>
					<h1 class="jase-single-hero__title"><?php the_title(); ?></h1>
				</header>
				<?php
			endwhile;
			rewind_posts();
			?>
		</div>
	</div>
	<div class="jase-container jase-page-body">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'jase-page-card jase-reveal' ); ?>>
				<div class="jase-page-card__content jase-content jase-prose">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				echo '<div class="jase-comments-wrap jase-reveal">';
				comments_template();
				echo '</div>';
			}
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
