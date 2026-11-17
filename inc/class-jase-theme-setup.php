<?php
/**
 * Idempotent theme activation: pages, menus, Peer Review plugin option merge.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class JASE_Theme_Setup
 */
class JASE_Theme_Setup {

	const OPTION_KEY    = 'jase_peer_review_setup';
	const SETUP_VERSION = 8;

	/**
	 * Run setup when version increases or first install.
	 */
	public static function maybe_run() {
		$stored = get_option( self::OPTION_KEY, array() );
		$ver    = isset( $stored['version'] ) ? (int) $stored['version'] : 0;
		if ( $ver >= self::SETUP_VERSION ) {
			self::maybe_repair_missing_pages( $stored );
			return;
		}

		$page_map = self::create_or_get_pages();
		self::create_menus( $page_map );
		self::configure_reading( $page_map );
		self::merge_prs_settings( $page_map );

		update_option(
			self::OPTION_KEY,
			array(
				'version'  => self::SETUP_VERSION,
				'page_ids' => $page_map,
			)
		);
	}

	/**
	 * Recreate missing auto-pages even when setup version is current.
	 *
	 * @param array<string,mixed> $stored Stored setup option.
	 */
	private static function maybe_repair_missing_pages( $stored ) {
		$map        = isset( $stored['page_ids'] ) && is_array( $stored['page_ids'] ) ? $stored['page_ids'] : array();
		$has_missing = empty( $map );
		if ( ! $has_missing ) {
			foreach ( array_keys( self::page_definitions() ) as $slug ) {
				$page_id = ! empty( $map[ $slug ] ) ? (int) $map[ $slug ] : 0;
				if ( ! $page_id || ! get_post( $page_id ) || 'trash' === get_post_status( $page_id ) ) {
					$has_missing = true;
					break;
				}
			}
		}

		if ( ! $has_missing ) {
			return;
		}

		$page_map = self::create_or_get_pages();
		self::create_menus( $page_map );
		self::configure_reading( $page_map );
		self::merge_prs_settings( $page_map );

		update_option(
			self::OPTION_KEY,
			array(
				'version'  => self::SETUP_VERSION,
				'page_ids' => $page_map,
			)
		);
	}

	/**
	 * Create pages or return existing IDs keyed by slug.
	 *
	 * @return array<string, int>
	 */
	private static function create_or_get_pages() {
		$definitions = self::page_definitions();
		$map         = array();

		foreach ( $definitions as $slug => $def ) {
			$existing = get_page_by_path( $slug, OBJECT, 'page' );
			if ( $existing ) {
				$map[ $slug ] = (int) $existing->ID;
				self::ensure_page_template( $existing->ID, $def['template'] );
				if ( ! empty( $def['content'] ) && empty( $existing->post_content ) ) {
					wp_update_post(
						array(
							'ID'           => $existing->ID,
							'post_content' => $def['content'],
						)
					);
				}
				continue;
			}

			$post_id = wp_insert_post(
				array(
					'post_title'   => $def['title'],
					'post_name'    => $slug,
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => $def['content'],
					'post_author'  => self::default_author_id(),
				),
				true
			);

			if ( is_wp_error( $post_id ) ) {
				continue;
			}

			if ( ! empty( $def['template'] ) ) {
				update_post_meta( $post_id, '_wp_page_template', $def['template'] );
			}

			$map[ $slug ] = (int) $post_id;
		}

		return $map;
	}

	/**
	 * @param int         $post_id Post ID.
	 * @param string|null $template Template path or empty.
	 */
	private static function ensure_page_template( $post_id, $template ) {
		if ( empty( $template ) ) {
			return;
		}
		$current = get_post_meta( $post_id, '_wp_page_template', true );
		if ( $current !== $template ) {
			update_post_meta( $post_id, '_wp_page_template', $template );
		}
	}

	/**
	 * @return int
	 */
	private static function default_author_id() {
		$user = get_user_by( 'login', 'admin' );
		if ( $user ) {
			return (int) $user->ID;
		}
		$users = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
		if ( ! empty( $users[0] ) ) {
			return (int) $users[0]->ID;
		}
		return 1;
	}

	/**
	 * Page definitions: slug => data.
	 *
	 * @return array<string, array{title: string, content: string, template: string}>
	 */
	private static function page_definitions() {
		$terms_short = __( 'Replace this page with your official terms. This placeholder is provided for registration checkboxes and footer links.', 'jase-peer-review' );
		$privacy     = __( 'Replace this page with your privacy policy.', 'jase-peer-review' );

		return array(
			'home'                 => array(
				'title'    => __( 'Home', 'jase-peer-review' ),
				'content'  => '',
				'template' => '',
			),
			'about'                => array(
				'title'    => __( 'About & Aims and Scope', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Edit this page to describe the journal’s mission, scope, and audience.', 'jase-peer-review' ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'instructions-authors' => array(
				'title'    => __( 'Instructions for Authors', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Add submission guidelines, formatting rules, and review timelines here.', 'jase-peer-review' ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'editorial-board'      => array(
				'title'    => __( 'Editorial Board', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'List editors, associate editors, and board members here.', 'jase-peer-review' ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'current-issue'        => array(
				'title'    => __( 'Current Issue', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Highlight the current edition or link to the editions search page.', 'jase-peer-review' ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'archives'             => array(
				'title'    => __( 'Archives', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Use this page for archive navigation or link visitors to article and edition search.', 'jase-peer-review' ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'contact'              => array(
				'title'    => __( 'Contact', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Add the editorial office email, postal address, and phone details here.', 'jase-peer-review' ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'search-articles'        => array(
				'title'    => __( 'Search Articles', 'jase-peer-review' ),
				'content'  => '',
				'template' => 'templates/search-articles.php',
			),
			'approved-manuscripts'   => array(
				'title'    => __( 'Approved Manuscripts', 'jase-peer-review' ),
				'content'  => '',
				'template' => 'template-approved-manuscripts.php',
			),
			'search-editions'        => array(
				'title'    => __( 'Search Editions', 'jase-peer-review' ),
				'content'  => '',
				'template' => 'templates/search-editions.php',
			),
			'dashboard'            => array(
				'title'    => __( 'Dashboard', 'jase-peer-review' ),
				'content'  => '',
				'template' => 'templates/dashboard.php',
			),
			'register'             => array(
				'title'    => __( 'Register', 'jase-peer-review' ),
				'content'  => '',
				'template' => 'template-jase-register.php',
			),
			'login'                => array(
				'title'    => __( 'Login', 'jase-peer-review' ),
				'content'  => '',
				'template' => 'template-jase-login.php',
			),
			'forgot-password'      => array(
				'title'    => __( 'Forgot Password', 'jase-peer-review' ),
				'content'  => '[peer_review_system_forgot_password]',
				'template' => '',
			),
			'terms-and-conditions' => array(
				'title'    => __( 'Terms and Conditions', 'jase-peer-review' ),
				'content'  => '<!-- wp:heading --><h2>' . esc_html__( 'Terms and Conditions', 'jase-peer-review' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html( $terms_short ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'privacy-policy'       => array(
				'title'    => __( 'Privacy Policy', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html( $privacy ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
			'submit-manuscript'    => array(
				'title'    => __( 'Submit Manuscript', 'jase-peer-review' ),
				'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'Authors can submit manuscripts from the peer review dashboard after signing in.', 'jase-peer-review' ) . '</p><!-- /wp:paragraph -->',
				'template' => '',
			),
		);
	}

	/**
	 * Register page uses theme template `template-jase-register.php` (split UI); clear legacy shortcode content.
	 *
	 * @param array<string, int> $map Page map.
	 */
	private static function sync_register_page_template( $map ) {
		if ( empty( $map['register'] ) ) {
			return;
		}
		wp_update_post(
			array(
				'ID'           => $map['register'],
				'post_content' => '',
			)
		);
		update_post_meta( $map['register'], '_wp_page_template', 'template-jase-register.php' );
	}

	/**
	 * Login page: split template, no shortcode.
	 *
	 * @param array<string, int> $map Page map.
	 */
	private static function sync_login_page_template( $map ) {
		if ( empty( $map['login'] ) ) {
			return;
		}
		wp_update_post(
			array(
				'ID'           => $map['login'],
				'post_content' => '',
			)
		);
		update_post_meta( $map['login'], '_wp_page_template', 'template-jase-login.php' );
	}

	/**
	 * Assign primary / footer menus.
	 *
	 * @param array<string, int> $map Page map.
	 */
	private static function create_menus( $map ) {
		$primary_title = 'JASE Primary';
		$footer_title  = 'JASE Footer';

		$primary    = wp_get_nav_menu_object( $primary_title );
		$primary_id = $primary ? (int) $primary->term_id : 0;
		if ( ! $primary_id ) {
			$created = wp_create_nav_menu( $primary_title );
			$primary_id = is_wp_error( $created ) ? 0 : (int) $created;
		}

		$footer    = wp_get_nav_menu_object( $footer_title );
		$footer_id = $footer ? (int) $footer->term_id : 0;
		if ( ! $footer_id ) {
			$created_f = wp_create_nav_menu( $footer_title );
			$footer_id = is_wp_error( $created_f ) ? 0 : (int) $created_f;
		}

		if ( ! $primary_id || ! $footer_id ) {
			return;
		}

		$order = 0;
		foreach ( self::primary_menu_slugs() as $slug ) {
			if ( empty( $map[ $slug ] ) ) {
				continue;
			}
			self::add_page_to_menu_once( $primary_id, $map[ $slug ], ++$order );
		}

		$order_f = 0;
		foreach ( self::footer_menu_slugs() as $slug ) {
			if ( empty( $map[ $slug ] ) ) {
				continue;
			}
			self::add_page_to_menu_once( $footer_id, $map[ $slug ], ++$order_f );
		}

		$locations = get_theme_mod( 'nav_menu_locations', array() );
		if ( ! is_array( $locations ) ) {
			$locations = array();
		}
		$locations['primary'] = $primary_id;
		$locations['footer']  = $footer_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * @return string[]
	 */
	private static function primary_menu_slugs() {
		return array(
			'home',
			'about',
			'instructions-authors',
			'editorial-board',
			'search-articles',
			'search-editions',
			'submit-manuscript',
			'dashboard',
			'login',
			'register',
			'contact',
		);
	}

	/**
	 * Drop extra pages from primary nav; keep Home, About, Editorial Board, Search Editions, Submit Manuscript only.
	 *
	 * @param array<string, int> $map Page map.
	 */
	private static function normalize_primary_menu( $map ) {
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		if ( ! is_array( $locations ) || empty( $locations['primary'] ) ) {
			return;
		}
		$menu_id       = (int) $locations['primary'];
		$allowed_order = array( 'home', 'about', 'editorial-board', 'search-editions', 'submit-manuscript' );

		$items = wp_get_nav_menu_items( $menu_id );
		if ( $items ) {
			foreach ( $items as $item ) {
				if ( isset( $item->type ) && 'post_type' === $item->type && isset( $item->object ) && 'page' === $item->object ) {
					wp_delete_post( (int) $item->ID, true );
				}
			}
		}

		$order = 0;
		foreach ( $allowed_order as $slug ) {
			if ( ! empty( $map[ $slug ] ) ) {
				self::add_page_to_menu_once( $menu_id, (int) $map[ $slug ], ++$order );
			}
		}
	}

	/**
	 * @return string[]
	 */
	private static function footer_menu_slugs() {
		return array(
			'terms-and-conditions',
			'privacy-policy',
			'contact',
		);
	}

	/**
	 * @param int $menu_id Menu term ID.
	 * @param int $page_id Page ID.
	 * @param int $order   Position.
	 */
	private static function add_page_to_menu_once( $menu_id, $page_id, $order ) {
		$items = wp_get_nav_menu_items( $menu_id );
		if ( $items ) {
			foreach ( $items as $item ) {
				if ( (int) $item->object_id === (int) $page_id && 'post_type' === $item->type && 'page' === $item->object ) {
					return;
				}
			}
		}
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => get_the_title( $page_id ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $order,
			)
		);
	}

	/**
	 * Static front page.
	 *
	 * @param array<string, int> $map Page map.
	 */
	private static function configure_reading( $map ) {
		if ( empty( $map['home'] ) ) {
			return;
		}
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $map['home'] );
	}

	/**
	 * Merge Peer Review System (Redux) options — safe if plugin inactive.
	 *
	 * @param array<string, int> $map Page map.
	 */
	private static function merge_prs_settings( $map ) {
		self::sync_register_page_template( $map );
		self::sync_login_page_template( $map );

		$opt_name = apply_filters( 'prs_settings_option_name', 'prs_settings' );
		$prs      = get_option( $opt_name, array() );
		if ( ! is_array( $prs ) ) {
			$prs = array();
		}

		$prs['allow_registration_roles'] = array( 'article_author', 'article_reviewer' );
		$prs['defult_register_type']     = 'article_author';

		$keys = array(
			'tpl_dashboard'         => 'dashboard',
			'tpl_search_articles'   => 'search-articles',
			'tpl_search_editions'   => 'search-editions',
			'tpl_login'             => 'login',
			'tpl_registration'      => 'register',
			'tpl_reset'             => 'forgot-password',
			'tpl_terms_conditions'  => 'terms-and-conditions',
		);

		foreach ( $keys as $prs_key => $slug ) {
			if ( ! empty( $map[ $slug ] ) ) {
				$prs[ $prs_key ] = (string) $map[ $slug ];
			}
		}

		// Free-first: do not force WooCommerce product settings here; see inc/woocommerce.php.
		$prs['user_account_approve'] = true;

		update_option( $opt_name, $prs );
	}
}
