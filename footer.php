<?php
/**
 * Theme footer.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$issn_p = get_theme_mod( 'jase_issn_print', '' );
$issn_o = get_theme_mod( 'jase_issn_online', '' );
$pub    = get_theme_mod( 'jase_publisher_line', '' );
$copy   = get_theme_mod( 'jase_copyright_line', '' );
?>
<footer class="jase-footer" role="contentinfo">
	<div class="jase-footer__glow" aria-hidden="true"></div>
	<div class="jase-container jase-footer__grid">
		<div class="jase-footer__brand">
			<a class="jase-footer__sitename" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			<?php
			$desc = get_bloginfo( 'description', 'display' );
			if ( $desc ) {
				echo '<p class="jase-footer__tagline">' . esc_html( $desc ) . '</p>';
			}
			?>
		</div>
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="jase-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'jase-peer-review' ); ?>">
				<span class="jase-footer__nav-label"><?php esc_html_e( 'Legal & contact', 'jase-peer-review' ); ?></span>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'jase-footer__list',
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>
		<div class="jase-footer__meta">
			<?php
			if ( $issn_p ) {
				echo '<p class="jase-footer__line">' . esc_html( $issn_p ) . '</p>';
			}
			if ( $issn_o ) {
				echo '<p class="jase-footer__line">' . esc_html( $issn_o ) . '</p>';
			}
			if ( $pub ) {
				echo '<p class="jase-footer__line">' . esc_html( $pub ) . '</p>';
			}
			if ( $copy ) {
				echo '<p class="jase-footer__line">' . esc_html( $copy ) . '</p>';
			}
			?>
			<p class="jase-footer__credit">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: current year */
						__( '© %s', 'jase-peer-review' ),
						gmdate( 'Y' )
					)
				);
				?>
				<span class="jase-footer__sep"> · </span>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			</p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
