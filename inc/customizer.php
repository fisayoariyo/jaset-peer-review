<?php
/**
 * Customizer: homepage and footer settings.
 *
 * @package JASE_Peer_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function jase_peer_review_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'jase_journal_hero',
		array(
			'title'       => __( 'Journal homepage', 'jase-peer-review' ),
			'description' => __( 'Hero cover image and latest manuscript defaults.', 'jase-peer-review' ),
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting(
		'jase_hero_journal_cover_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'jase_hero_journal_cover_id',
			array(
				'label'     => __( 'Journal cover image (hero)', 'jase-peer-review' ),
				'section'   => 'jase_journal_hero',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'jase_latest_releases_count',
		array(
			'default'           => 4,
			'sanitize_callback' => static function ( $v ) {
				$n = absint( $v );
				return max( 1, min( 8, $n ? $n : 4 ) );
			},
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'jase_latest_releases_count',
		array(
			'label'       => __( 'Latest releases - number of manuscripts to show', 'jase-peer-review' ),
			'section'     => 'jase_journal_hero',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 8,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_section(
		'jase_peer_review_footer',
		array(
			'title'       => __( 'Journal footer & legal', 'jase-peer-review' ),
			'description' => __( 'Optional lines shown in the site footer. Replace placeholders with your official data.', 'jase-peer-review' ),
			'priority'    => 160,
		)
	);

	$fields = array(
		'jase_issn_print'      => __( 'ISSN (print)', 'jase-peer-review' ),
		'jase_issn_online'     => __( 'ISSN (online)', 'jase-peer-review' ),
		'jase_publisher_line'  => __( 'Publisher line', 'jase-peer-review' ),
		'jase_copyright_line'  => __( 'Copyright / rights line', 'jase-peer-review' ),
	);

	foreach ( $fields as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'jase_peer_review_footer',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'jase_peer_review_customize_register' );
