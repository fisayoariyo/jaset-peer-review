<?php
/**
 * Shared branding column for auth split layouts.
 *
 * @package JASE_Peer_Review
 *
 * @var array<string,mixed> $args Arguments from get_template_part (WP 5.5+).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$jase_has_logo  = isset( $args['jase_has_logo'] ) ? (bool) $args['jase_has_logo'] : false;
$jase_logo_uri  = isset( $args['jase_logo_uri'] ) ? $args['jase_logo_uri'] : '';
?>
<aside class="jase-reg-split__brand" aria-label="<?php esc_attr_e( 'Journal branding', 'jase-peer-review' ); ?>">
	<?php if ( $jase_has_logo && $jase_logo_uri ) : ?>
		<div
			class="jase-reg-split__watermark"
			aria-hidden="true"
			style="<?php echo esc_attr( 'background-image: url(' . $jase_logo_uri . ');' ); ?>"
		></div>
	<?php endif; ?>
	<a class="jase-reg-split__back" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<span class="jase-reg-split__back-arrow" aria-hidden="true">←</span>
		<?php esc_html_e( 'Back to site', 'jase-peer-review' ); ?>
	</a>
	<div class="jase-reg-split__brand-inner">
		<?php if ( $jase_has_logo && $jase_logo_uri ) : ?>
			<div class="jase-reg-split__logo-wrap">
				<img
					class="jase-reg-split__logo"
					src="<?php echo esc_url( $jase_logo_uri ); ?>"
					width="220"
					height="220"
					alt="<?php esc_attr_e( 'University of Ibadan — Automotive Engineering', 'jase-peer-review' ); ?>"
					loading="eager"
					decoding="async"
				/>
			</div>
		<?php endif; ?>
		<h1 class="jase-reg-split__title"><?php esc_html_e( 'JASET', 'jase-peer-review' ); ?></h1>
		<p class="jase-reg-split__dept"><?php esc_html_e( 'University of Ibadan', 'jase-peer-review' ); ?></p>
	</div>
</aside>
