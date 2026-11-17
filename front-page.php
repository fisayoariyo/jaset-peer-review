<?php
/**
 * Front page landing experience.
 *
 * @package JASE_Peer_Review
 */

get_header();

$reg_base   = jase_peer_review_page_url( 'register' );
$reg_author = ( '#' !== $reg_base ) ? add_query_arg( 'register_as', 'article_author', $reg_base ) : '#';
$reg_rev    = ( '#' !== $reg_base ) ? add_query_arg( 'register_as', 'article_reviewer', $reg_base ) : '#';

$url_articles  = jase_peer_review_page_url( 'search-articles' );
$url_editions  = jase_peer_review_page_url( 'search-editions' );
$url_guide     = jase_peer_review_page_url( 'instructions-authors' );
$url_dashboard = jase_peer_review_page_url( 'dashboard' );
$url_login     = jase_peer_review_page_url( 'login' );

$jase_cover_id  = absint( get_theme_mod( 'jase_hero_journal_cover_id', 0 ) );
$jase_cover_uri = '';
$jase_cover_alt = '';
if ( $jase_cover_id ) {
	$jase_cover_uri = wp_get_attachment_image_url( $jase_cover_id, 'large' );
	$jase_cover_alt = get_post_meta( $jase_cover_id, '_wp_attachment_image_alt', true );
	$jase_cover_alt = is_string( $jase_cover_alt ) ? $jase_cover_alt : '';
}
if ( ! $jase_cover_uri ) {
	$jase_fallback_path = get_template_directory() . '/assets/coverjj.jpeg';
	if ( is_readable( $jase_fallback_path ) ) {
		$jase_cover_uri = get_template_directory_uri() . '/assets/coverjj.jpeg';
	}
}

$jase_logo_path = get_template_directory() . '/assets/images/jase-dept-logo.png';
$jase_logo_uri  = is_readable( $jase_logo_path ) ? get_template_directory_uri() . '/assets/images/jase-dept-logo.png' : '';
?>
<main id="jase-main" class="jase-main jase-main--front">
	<section class="jase-hero" aria-labelledby="jase-hero-title">
		<div class="jase-hero__bg" aria-hidden="true"></div>
		<div class="jase-container">
			<div class="jase-hero__inner">
				<div class="jase-hero__intro jase-hero-sequence">
					<p class="jase-hero__subheading jase-reveal"><?php esc_html_e( 'University of Ibadan', 'jase-peer-review' ); ?></p>
					<h1 id="jase-hero-title" class="jase-hero__title jase-reveal">
						<span class="jase-hero__title-line"><?php esc_html_e( 'Journal of Automotive Science', 'jase-peer-review' ); ?></span>
						<span class="jase-hero__title-line"><?php esc_html_e( '& Engineering Technology', 'jase-peer-review' ); ?></span>
					</h1>
					<p class="jase-hero__tagline jase-reveal"><?php esc_html_e( 'Manuscript submission, peer review, and access to approved research in automotive science and engineering.', 'jase-peer-review' ); ?></p>
					<div class="jase-hero__actions jase-reveal">
						<a class="jase-btn jase-btn--primary jase-btn--lg" href="<?php echo esc_url( $reg_author ); ?>"><?php esc_html_e( 'Submit manuscript', 'jase-peer-review' ); ?></a>
					</div>
					<ul class="jase-hero__trust jase-reveal" role="list">
						<li><?php esc_html_e( 'Structured peer review', 'jase-peer-review' ); ?></li>
						<li><?php esc_html_e( 'Author and reviewer workspaces', 'jase-peer-review' ); ?></li>
						<li><?php esc_html_e( 'Approved manuscript archive', 'jase-peer-review' ); ?></li>
					</ul>
				</div>
				<div class="jase-hero__book-slot jase-reveal"<?php echo $jase_cover_uri ? '' : ' aria-hidden="true"'; ?>>
					<div class="jase-hero__book-scene">
						<div class="jase-hero__paper-stack" aria-hidden="true">
							<span class="jase-hero__paper jase-hero__paper--3"></span>
							<span class="jase-hero__paper jase-hero__paper--2"></span>
							<span class="jase-hero__paper jase-hero__paper--1"></span>
						</div>
						<div class="jase-hero__book<?php echo $jase_cover_uri ? '' : ' jase-hero__book--minimal'; ?>">
							<?php if ( $jase_cover_uri ) : ?>
								<div class="jase-hero__book-face">
									<img class="jase-hero__book-cover-img" src="<?php echo esc_url( $jase_cover_uri ); ?>" alt="<?php echo esc_attr( $jase_cover_alt ? $jase_cover_alt : get_bloginfo( 'name', 'display' ) ); ?>" decoding="async" loading="lazy" />
								</div>
								<span class="jase-hero__book-spine" aria-hidden="true"></span>
							<?php else : ?>
								<span class="jase-hero__book-title"><?php bloginfo( 'name' ); ?></span>
								<span class="jase-hero__book-sub"><?php esc_html_e( 'Journal workspace', 'jase-peer-review' ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/jase', 'latest-editions' ); ?>

	<section class="jase-section jase-section--features" aria-labelledby="jase-features-title">
		<div class="jase-container">
			<div class="jase-section__head jase-reveal">
				<h2 id="jase-features-title" class="jase-section__title"><?php esc_html_e( 'Everything in one place', 'jase-peer-review' ); ?></h2>
				<p class="jase-section__lead"><?php esc_html_e( 'Search the catalog, follow your manuscript, and collaborate without switching tools.', 'jase-peer-review' ); ?></p>
			</div>
			<div class="jase-bento jase-stagger">
				<article class="jase-bento__card jase-bento__card--wide jase-reveal">
					<div class="jase-bento__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
					</div>
					<h3 class="jase-bento__title"><?php esc_html_e( 'Manuscript pipeline', 'jase-peer-review' ); ?></h3>
					<p class="jase-bento__text"><?php esc_html_e( 'Upload files, track status, and respond to revisions from a single dashboard.', 'jase-peer-review' ); ?></p>
				</article>
				<article class="jase-bento__card jase-reveal">
					<div class="jase-bento__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.5"/><path d="M20 20l-3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
					</div>
					<h3 class="jase-bento__title"><?php esc_html_e( 'Discoverable content', 'jase-peer-review' ); ?></h3>
					<p class="jase-bento__text"><?php esc_html_e( 'Search manuscripts and editions so readers find the right issue quickly.', 'jase-peer-review' ); ?></p>
				</article>
				<article class="jase-bento__card jase-reveal">
					<div class="jase-bento__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
					</div>
					<h3 class="jase-bento__title"><?php esc_html_e( 'Roles that fit', 'jase-peer-review' ); ?></h3>
					<p class="jase-bento__text"><?php esc_html_e( 'Authors submit manuscripts; reviewers provide structured evaluations for each assignment.', 'jase-peer-review' ); ?></p>
				</article>
				<article class="jase-bento__card jase-bento__card--accent jase-reveal">
					<div class="jase-bento__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M12 12l8-4.5M12 12v9M12 12L4 7.5" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
					</div>
					<h3 class="jase-bento__title"><?php esc_html_e( 'Editions and issues', 'jase-peer-review' ); ?></h3>
					<p class="jase-bento__text"><?php esc_html_e( 'Curate volumes and bind accepted work into published editions.', 'jase-peer-review' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="jase-section jase-section--steps" aria-labelledby="jase-steps-title">
		<div class="jase-container">
			<div class="jase-section__head jase-reveal">
				<h2 id="jase-steps-title" class="jase-section__title"><?php esc_html_e( 'How it works', 'jase-peer-review' ); ?></h2>
				<p class="jase-section__lead"><?php esc_html_e( 'A straightforward path from submission to publication.', 'jase-peer-review' ); ?></p>
			</div>
			<ol class="jase-steps jase-stagger">
				<li class="jase-steps__item">
					<span class="jase-steps__num" aria-hidden="true">01</span>
					<div class="jase-steps__body">
						<h3 class="jase-steps__title"><?php esc_html_e( 'Register and verify', 'jase-peer-review' ); ?></h3>
						<p class="jase-steps__text"><?php esc_html_e( 'Create an author or reviewer account using your institutional email.', 'jase-peer-review' ); ?></p>
					</div>
				</li>
				<li class="jase-steps__item">
					<span class="jase-steps__num" aria-hidden="true">02</span>
					<div class="jase-steps__body">
						<h3 class="jase-steps__title"><?php esc_html_e( 'Submit and review', 'jase-peer-review' ); ?></h3>
						<p class="jase-steps__text"><?php esc_html_e( 'Authors upload manuscripts; reviewers provide structured feedback in the dashboard.', 'jase-peer-review' ); ?></p>
					</div>
				</li>
				<li class="jase-steps__item">
					<span class="jase-steps__num" aria-hidden="true">03</span>
					<div class="jase-steps__body">
						<h3 class="jase-steps__title"><?php esc_html_e( 'Decide and publish', 'jase-peer-review' ); ?></h3>
						<p class="jase-steps__text"><?php esc_html_e( 'Editors manage decisions, revisions, and edition assembly before readers explore the published archive.', 'jase-peer-review' ); ?></p>
					</div>
				</li>
			</ol>
		</div>
	</section>

	<section class="jase-section jase-section--roles" aria-labelledby="jase-role-heading">
		<div class="jase-container">
			<div class="jase-section__head jase-reveal">
				<h2 id="jase-role-heading" class="jase-section__title"><?php esc_html_e( 'Choose your path', 'jase-peer-review' ); ?></h2>
				<p class="jase-section__lead"><?php esc_html_e( 'Self-service registration is available for authors and reviewers.', 'jase-peer-review' ); ?></p>
			</div>
			<div class="jase-role-grid jase-stagger">
				<article class="jase-role-card jase-reveal">
					<div class="jase-role-card__icon" aria-hidden="true">
						<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 7h8M8 11h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
					</div>
					<h3 class="jase-role-card__title"><?php esc_html_e( 'Author', 'jase-peer-review' ); ?></h3>
					<p class="jase-role-card__text"><?php esc_html_e( 'Submit manuscripts, respond to revisions, and track each stage in your workspace.', 'jase-peer-review' ); ?></p>
					<a class="jase-btn jase-btn--purple jase-btn--block" href="<?php echo esc_url( $reg_author ); ?>"><?php esc_html_e( 'Register as author', 'jase-peer-review' ); ?></a>
				</article>
				<article class="jase-role-card jase-reveal">
					<div class="jase-role-card__icon" aria-hidden="true">
						<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 20h9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
					</div>
					<h3 class="jase-role-card__title"><?php esc_html_e( 'Reviewer', 'jase-peer-review' ); ?></h3>
					<p class="jase-role-card__text"><?php esc_html_e( 'Access assigned papers, share recommendations, and collaborate with the editorial team.', 'jase-peer-review' ); ?></p>
					<a class="jase-btn jase-btn--purple jase-btn--block" href="<?php echo esc_url( $reg_rev ); ?>"><?php esc_html_e( 'Register as reviewer', 'jase-peer-review' ); ?></a>
				</article>
			</div>
			<p class="jase-section__note jase-reveal">
				<a class="jase-link-arrow" href="<?php echo esc_url( $url_login ); ?>"><?php esc_html_e( 'Already have an account? Log in', 'jase-peer-review' ); ?></a>
			</p>
		</div>
	</section>

	<section class="jase-section jase-section--explore" aria-labelledby="jase-explore-title">
		<?php if ( $jase_logo_uri ) : ?>
			<div
				class="jase-explore__watermark"
				id="jase-explore-watermark"
				aria-hidden="true"
				style="<?php echo esc_attr( 'background-image: url(' . $jase_logo_uri . ');' ); ?>"
			></div>
		<?php endif; ?>
		<div class="jase-container">
			<div class="jase-section__head jase-reveal">
				<h2 id="jase-explore-title" class="jase-section__title"><?php esc_html_e( 'Explore the journal', 'jase-peer-review' ); ?></h2>
				<p class="jase-section__lead"><?php esc_html_e( 'Browse the corpus or read submission requirements before you register.', 'jase-peer-review' ); ?></p>
			</div>
			<div class="jase-explore-grid jase-stagger">
				<a class="jase-explore-card jase-reveal" style="--jase-slide:-1" href="<?php echo esc_url( $url_articles ); ?>">
					<span class="jase-explore-card__label"><?php esc_html_e( 'Manuscripts', 'jase-peer-review' ); ?></span>
					<span class="jase-explore-card__action jase-link-arrow"><?php esc_html_e( 'Search', 'jase-peer-review' ); ?></span>
				</a>
				<a class="jase-explore-card jase-explore-card--alt jase-reveal" style="--jase-slide:1" href="<?php echo esc_url( $url_editions ); ?>">
					<span class="jase-explore-card__label"><?php esc_html_e( 'Editions', 'jase-peer-review' ); ?></span>
					<span class="jase-explore-card__action jase-link-arrow"><?php esc_html_e( 'Browse', 'jase-peer-review' ); ?></span>
				</a>
				<a class="jase-explore-card jase-reveal" style="--jase-slide:-1" href="<?php echo esc_url( $url_guide ); ?>">
					<span class="jase-explore-card__label"><?php esc_html_e( 'Author guidelines', 'jase-peer-review' ); ?></span>
					<span class="jase-explore-card__action jase-link-arrow"><?php esc_html_e( 'Read', 'jase-peer-review' ); ?></span>
				</a>
			</div>
		</div>
	</section>

	<section class="jase-cta" aria-labelledby="jase-cta-title">
		<div class="jase-container jase-cta__inner jase-reveal jase-reveal--cta">
			<div class="jase-cta__copy">
				<h2 id="jase-cta-title" class="jase-cta__title"><?php esc_html_e( 'Ready when you are', 'jase-peer-review' ); ?></h2>
				<p class="jase-cta__text"><?php esc_html_e( 'Open your dashboard to continue a submission or start a new one.', 'jase-peer-review' ); ?></p>
			</div>
			<div class="jase-cta__actions">
				<a class="jase-btn jase-btn--inverse jase-btn--lg" href="<?php echo esc_url( $url_dashboard ); ?>"><?php esc_html_e( 'Go to dashboard', 'jase-peer-review' ); ?></a>
				<a class="jase-btn jase-btn--outline-purple jase-btn--lg" href="<?php echo esc_url( $reg_author ); ?>"><?php esc_html_e( 'Create an account', 'jase-peer-review' ); ?></a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
