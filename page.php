<?php
/**
 * Default page template.
 *
 * @package JASE_Peer_Review
 */

get_header();
?>
<main id="jase-main" class="jase-main jase-main--page">
	<div class="jase-page-hero">
		<div class="jase-container">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<header class="jase-page-hero__header jase-reveal">
					<h1 class="jase-page-hero__title"><?php the_title(); ?></h1>
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
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
