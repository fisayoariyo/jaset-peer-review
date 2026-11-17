<?php
/**
 * Template Name: JASET — Log in (split)
 * Description: Desktop split layout; mobile single-column. Uses peer_review_system_user_signin AJAX.
 *
 * @package JASE_Peer_Review
 */

global $prs_settings;
if ( empty( $prs_settings ) || ! is_array( $prs_settings ) ) {
	$prs_settings = get_option( apply_filters( 'prs_settings_option_name', 'prs_settings' ), array() );
}

$dashboard_redirect = jase_peer_review_dashboard_redirect_url();
if ( is_user_logged_in() ) {
	wp_safe_redirect( $dashboard_redirect );
	exit;
}

$tpl_registration = ! empty( $prs_settings['tpl_registration'] ) ? get_permalink( (int) $prs_settings['tpl_registration'] ) : jase_peer_review_page_url( 'register' );
$tpl_reset        = ! empty( $prs_settings['tpl_reset'] ) ? get_permalink( (int) $prs_settings['tpl_reset'] ) : jase_peer_review_page_url( 'forgot-password' );

get_header( 'auth' );

$jase_logo_file = get_template_directory() . '/assets/images/jase-dept-logo.png';
$jase_logo_uri  = get_template_directory_uri() . '/assets/images/jase-dept-logo.png';
$jase_has_logo  = is_readable( $jase_logo_file );
?>
<main id="jase-main" class="jase-login jase-auth-shell">
	<div class="jase-reg-split">
		<?php
		get_template_part(
			'template-parts/auth',
			'brand-aside',
			array(
				'jase_has_logo'  => $jase_has_logo,
				'jase_logo_uri'  => $jase_logo_uri,
				'jase_auth_mode' => 'login',
			)
		);
		?>

		<div class="jase-reg-split__main">
			<div class="jase-reg-panel jase-auth-login-panel">
				<h2 class="jase-reg-panel__title"><?php esc_html_e( 'Log in to your account', 'jase-peer-review' ); ?></h2>
				<p class="jase-reg-panel__lead jase-auth-login-lead"><?php esc_html_e( 'Use the email and password for your journal account.', 'jase-peer-review' ); ?></p>

				<form class="sj-themeform sj-login-form jase-auth-login-form" method="post" autocomplete="on">
					<fieldset>
						<legend class="screen-reader-text"><?php esc_html_e( 'Sign in', 'jase-peer-review' ); ?></legend>

						<div class="jase-auth-field">
							<label class="jase-auth-label" for="jase-login-email"><?php esc_html_e( 'Email', 'jase-peer-review' ); ?></label>
							<div class="jase-auth-input-wrap">
								<span class="jase-auth-input-icon" aria-hidden="true">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6h16v12H4V6z" stroke="currentColor" stroke-width="1.5"/><path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
								</span>
								<input
									id="jase-login-email"
									class="form-control jase-auth-input"
									type="email"
									name="login[email]"
									required="required"
									autocomplete="username"
									inputmode="email"
								/>
							</div>
						</div>

						<div class="jase-auth-field">
							<div class="jase-auth-label-row">
								<label class="jase-auth-label" for="jase-login-password"><?php esc_html_e( 'Password', 'jase-peer-review' ); ?></label>
								<a class="jase-auth-link-forgot" href="<?php echo esc_url( $tpl_reset ); ?>"><?php esc_html_e( 'Forgot password?', 'jase-peer-review' ); ?></a>
							</div>
							<div class="jase-auth-input-wrap">
								<span class="jase-auth-input-icon" aria-hidden="true">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
								</span>
								<input
									id="jase-login-password"
									class="form-control jase-auth-input"
									type="password"
									name="login[password]"
									required="required"
									autocomplete="current-password"
								/>
								<button type="button" class="jase-auth-pass-toggle" data-jase-target="#jase-login-password" aria-pressed="false" aria-label="<?php esc_attr_e( 'Show password', 'jase-peer-review' ); ?>">
									<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6-10-6-10-6z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
								</button>
							</div>
						</div>

						<div class="jase-auth-field jase-auth-field--submit">
							<input type="hidden" name="login[redirect_to]" value="<?php echo esc_url( $dashboard_redirect ); ?>" />
							<button type="button" class="sj-user-login jase-auth-btn-continue">
								<span><?php esc_html_e( 'Continue', 'jase-peer-review' ); ?></span>
							</button>
						</div>

						<p class="jase-auth-register-prompt">
							<a href="<?php echo esc_url( $tpl_registration ); ?>"><?php esc_html_e( "I don't have an account", 'jase-peer-review' ); ?></a>
						</p>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</main>
<?php
if ( function_exists( 'peer_review_system_get_template_part' ) ) {
	peer_review_system_get_template_part( 'user-type', 'js-templates' );
}
get_footer( 'auth' );
