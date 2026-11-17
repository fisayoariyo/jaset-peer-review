<?php
/**
 * Minimal footer for JASE auth templates.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="jase-auth-footnote">
	<div class="jase-auth-footnote__inner">
		<p class="jase-auth-footnote__text">
			<?php
			echo esc_html(
				sprintf(
					/* translators: %s: site name */
					__( '© %s', 'jase-peer-review' ),
					get_bloginfo( 'name', 'display' )
				)
			);
			?>
		</p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
