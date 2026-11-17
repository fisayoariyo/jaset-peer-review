<?php
/**
 * Template Name: JASET — Create account (split)
 * Description: Full-width split layout; uses the peer-review plugin registration AJAX handler.
 *
 * @package JASE_Peer_Review
 */

global $prs_settings;
if ( empty( $prs_settings ) || ! is_array( $prs_settings ) ) {
	$prs_settings = get_option( apply_filters( 'prs_settings_option_name', 'prs_settings' ), array() );
}

$terms_page = '';
if ( ! empty( $prs_settings['tpl_terms_conditions'] ) ) {
	$terms_page = get_permalink( (int) $prs_settings['tpl_terms_conditions'] );
}
if ( ! $terms_page ) {
	$terms_page = jase_peer_review_page_url( 'terms-and-conditions' );
}

$dashboard_redirect = jase_peer_review_dashboard_redirect_url();
if ( is_user_logged_in() ) {
	wp_safe_redirect( $dashboard_redirect );
	exit;
}

$login_url = ! empty( $prs_settings['tpl_login'] ) ? get_permalink( (int) $prs_settings['tpl_login'] ) : jase_peer_review_page_url( 'login' );
$tpl_reset = ! empty( $prs_settings['tpl_reset'] ) ? get_permalink( (int) $prs_settings['tpl_reset'] ) : jase_peer_review_page_url( 'forgot-password' );

$registration_roles = ! empty( $prs_settings['allow_registration_roles'] ) ? $prs_settings['allow_registration_roles'] : array( 'article_author', 'article_reviewer' );
$default_type       = ! empty( $prs_settings['defult_register_type'] ) ? $prs_settings['defult_register_type'] : 'article_author';
$list_roles         = apply_filters( 'peer_review_system_roles_list', array() );

get_header( 'auth' );

$jase_logo_file = get_template_directory() . '/assets/images/jase-dept-logo.png';
$jase_logo_uri  = get_template_directory_uri() . '/assets/images/jase-dept-logo.png';
$jase_has_logo  = is_readable( $jase_logo_file );
?>
<main id="jase-main" class="jase-reg jase-auth-shell">
	<div class="jase-reg-split">
		<?php
		get_template_part(
			'template-parts/auth',
			'brand-aside',
			array(
				'jase_has_logo'  => $jase_has_logo,
				'jase_logo_uri'  => $jase_logo_uri,
				'jase_auth_mode' => 'register',
			)
		);
		?>

		<div class="jase-reg-split__main">
			<div class="jase-reg-panel">
				<h2 class="jase-reg-panel__title"><?php esc_html_e( 'Get started', 'jase-peer-review' ); ?></h2>
				<p class="jase-reg-panel__lead"><?php esc_html_e( 'Choose how you will use this journal, then complete your details.', 'jase-peer-review' ); ?></p>

				<form class="sj-themeform sj-login-form sj-signup-form jase-reg-form" method="post" autocomplete="on">
					<fieldset>
						<legend class="screen-reader-text"><?php esc_html_e( 'Registration', 'jase-peer-review' ); ?></legend>
						<div class="sj-themeform__wrap">
							<div class="form-group-wrap">
								<div class="form-group jase-reg-rolefield">
									<span class="jase-reg-rolefield__label"><?php esc_html_e( 'I am joining as', 'jase-peer-review' ); ?></span>
									<div class="jase-reg-rolegrid" role="radiogroup" aria-label="<?php esc_attr_e( 'Account type', 'jase-peer-review' ); ?>">
										<?php
										foreach ( $registration_roles as $role ) {
											if ( ! is_string( $role ) ) {
												continue;
											}
											$rid    = 'jase_ut_' . sanitize_html_class( $role );
											$label  = isset( $list_roles[ $role ] ) ? $list_roles[ $role ] : $role;
											$checked = checked( $default_type, $role, false );
											?>
											<label class="jase-reg-role" for="<?php echo esc_attr( $rid ); ?>">
												<input
													class="jase-reg-role__input"
													id="<?php echo esc_attr( $rid ); ?>"
													type="radio"
													name="registration[user_type]"
													value="<?php echo esc_attr( $role ); ?>"
													<?php echo $checked; ?>
												/>
												<span class="jase-reg-role__card">
													<?php if ( 'article_author' === $role ) : ?>
														<span class="jase-reg-role__icon" aria-hidden="true">
															<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="1.5"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="1.5"/></svg>
														</span>
													<?php elseif ( 'article_reviewer' === $role ) : ?>
														<span class="jase-reg-role__icon" aria-hidden="true">
															<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20h9" stroke="currentColor" stroke-width="1.5"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.5"/></svg>
														</span>
													<?php endif; ?>
													<strong class="jase-reg-role__name"><?php echo esc_html( $label ); ?></strong>
													<span class="jase-reg-role__hint">
														<?php
														if ( 'article_author' === $role ) {
															esc_html_e( 'Submit and track your manuscripts.', 'jase-peer-review' );
														} elseif ( 'article_reviewer' === $role ) {
															esc_html_e( 'Review assigned papers and share recommendations.', 'jase-peer-review' );
														}
														?>
													</span>
												</span>
											</label>
											<?php
										}
										?>
									</div>
								</div>

								<div class="jase-reg-namegrid">
								<div class="form-group">
									<div class="sj-placeholderholder">
										<input type="text" name="registration[fname]" class="form-control" required="required" placeholder=" " autocomplete="given-name" />
										<div class="sj-placeholder">
											<span><?php esc_html_e( 'First name', 'jase-peer-review' ); ?></span>
											<em><?php esc_html_e( '*', 'jase-peer-review' ); ?></em>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="sj-placeholderholder">
										<input type="text" name="registration[lname]" class="form-control" required="required" placeholder=" " autocomplete="family-name" />
										<div class="sj-placeholder">
											<span><?php esc_html_e( 'Last name', 'jase-peer-review' ); ?></span>
											<em><?php esc_html_e( '*', 'jase-peer-review' ); ?></em>
										</div>
									</div>
								</div>
								</div>
								<div class="form-group">
									<div class="sj-placeholderholder">
										<input type="email" name="registration[email]" class="form-control" required="required" placeholder=" " autocomplete="email" />
										<div class="sj-placeholder">
											<span><?php esc_html_e( 'Email address', 'jase-peer-review' ); ?></span>
											<em><?php esc_html_e( '*', 'jase-peer-review' ); ?></em>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="sj-placeholderholder">
										<input type="password" name="registration[password]" class="form-control" required="required" placeholder=" " autocomplete="new-password" />
										<div class="sj-placeholder">
											<span><?php esc_html_e( 'Password', 'jase-peer-review' ); ?></span>
											<em><?php esc_html_e( '*', 'jase-peer-review' ); ?></em>
										</div>
									</div>
								</div>

								<div class="form-group">
									<button type="button" class="sj-secondery-btn sj-submit-registration jase-reg-submit">
										<span><?php esc_html_e( 'Create account', 'jase-peer-review' ); ?></span>
										<i class="icon icon-arrow-right" aria-hidden="true"></i>
									</button>
								</div>

								<div class="form-group">
									<div class="sj-check sj-signup-check">
										<input type="hidden" name="registration[terms]" value="" />
										<input type="checkbox" name="registration[terms]" id="sj-terms" value="on" />
										<label for="sj-terms">
											<span><?php esc_html_e( 'I have read and agree to the', 'jase-peer-review' ); ?></span>
											<a href="<?php echo esc_url( $terms_page ); ?>"><?php esc_html_e( 'Terms & conditions', 'jase-peer-review' ); ?></a>
										</label>
									</div>
								</div>

								<div class="sj-lost-password form-group jase-reg-altlinks">
									<a href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Already registered? Log in', 'jase-peer-review' ); ?></a>
									<a href="<?php echo esc_url( $tpl_reset ); ?>" class="sj-password-clr_light"><?php esc_html_e( 'Forgot password?', 'jase-peer-review' ); ?></a>
								</div>
							</div>
						</div>
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
