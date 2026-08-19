<?php
/**
 * Migrate legacy flexible-content fields into the new universal
 * `content_sections` field (group_universal_content_sections).
 *
 * This is a non-destructive, idempotent, additive migration:
 *   - It never deletes or modifies the legacy fields (page_sections,
 *     info_sections, instructions_sections, or the fixed fields on
 *     template-landing.php pages). Those remain in place as a safety net
 *     and as the automatic fallback rendered by the templates whenever
 *     content_sections has no rows yet.
 *   - It skips any page that already has content_sections rows, so running
 *     the migration multiple times (or against a partially-migrated site)
 *     is safe and will not create duplicate sections.
 *
 * The core migration logic below (`doubletap_run_sections_migration()`) is
 * shared by two entry points:
 *
 *   1. WP-CLI (this file, when WP_CLI is active):
 *        wp doubletap migrate-sections            # migrate every eligible page
 *        wp doubletap migrate-sections --dry-run   # preview without writing
 *        wp doubletap migrate-sections --post_id=123  # migrate a single page
 *
 *   2. A REST endpoint for environments without CLI/SSH access, registered
 *      in inc/rest-migrate-sections.php:
 *        POST /wp-json/doubletap/v1/migrate-sections
 *      Requires an authenticated administrator (WordPress core Application
 *      Passwords over HTTPS). See that file and the README for details.
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

/**
 * Layout-name correspondence between legacy fields and the new universal
 * `content_sections` field. Keys are the legacy ACF flexible-content
 * layout `name`, values are the matching new universal layout `name`.
 */
function doubletap_migrate_layout_map(): array {
	return [
		// group_homepage_sections.json -> page_sections
		'section_hero'              => 'hero',
		'section_category_tiles'    => 'category_tiles',
		'section_featured_products' => 'featured_products',
		'section_brand_story'       => 'text_image',
		'section_features'          => 'features_grid',
		'section_testimonials'      => 'testimonials',
		'section_cta_banner'        => 'cta_banner',

		// group_information_page_2.json -> info_sections
		// NOTE: the actual layout names on the live site have NO `info_`
		// prefix at all -- verified against a WXR export of the live site
		// (August 2026). The previously assumed `info_*` names did not
		// exist, so the whole Information-page migration silently skipped
		// every row.
		'page_header'      => 'page_header',
		'brand_story'      => 'text_image',
		'methods'          => 'application_methods',
		'product_line'     => 'product_line',
		'what_it_protects' => 'features_grid',
		'safety'           => 'safety_information',

		// group_instructions_page.json -> instructions_sections
		'section_video_grid' => 'video_grid',
	];
}

/**
 * Field-name correspondence per legacy layout -> new universal layout.
 * Each entry maps old sub-field name => new sub-field name (a plain
 * string), or => ['to' => new_key, 'repeater' => [old_sub => new_sub, ...]]
 * for repeater fields whose sub-field names also need renaming. Fields not
 * listed are dropped (no destination) or handled by custom logic below.
 *
 * NOTE: corrected against a live-site WXR export (August 2026) -- the
 * originally assumed bare field names (`heading`, `body`, `items`, etc.)
 * did not match the live ACF field names, which are prefixed per-layout
 * (e.g. `hero_heading`, `brand_story_text`, `features_section_heading`).
 */
function doubletap_migrate_field_map(): array {
	return [
		'section_hero' => [
			'hero_heading'            => 'heading',
			'hero_heading_accent'     => 'heading_accent',
			'hero_subheading'         => 'subheading',
			'hero_background_image'  => 'background_image',
			'hero_cta_primary_text'   => 'cta_primary_text',
			'hero_cta_primary_url'    => 'cta_primary_url',
			'hero_cta_secondary_text' => 'cta_secondary_text',
			'hero_cta_secondary_url'  => 'cta_secondary_url',
		],
		'section_category_tiles' => [
			'category_tiles_heading'    => 'heading',
			'category_tiles_subheading' => 'subheading',
			'category_tiles'            => 'tiles', // repeater sub-fields already match: tile_image/tile_title/tile_description/tile_link
		],
		'section_featured_products' => [
			'featured_products_label'       => 'label',
			'featured_products_heading'     => 'heading',
			'featured_products_description' => 'description',
			'featured_product_ids'          => 'product_ids',
		],
		'section_brand_story' => [
			'brand_story_label'    => 'label',
			'brand_story_heading'  => 'heading',
			'brand_story_text'     => 'body',
			'brand_story_image'    => 'image',
			'brand_story_cta_text' => 'cta_text',
			'brand_story_cta_url'  => 'cta_url',
		],
		'section_features' => [
			'features_section_label'   => 'label',
			'features_section_heading' => 'heading',
			'features' => [
				'to'       => 'items',
				'repeater' => [
					'feature_icon'        => 'icon',
					'feature_title'       => 'title',
					'feature_description' => 'description',
				],
			],
		],
		'section_testimonials' => [
			'testimonials_label'   => 'label',
			'testimonials_heading' => 'heading',
			'testimonials' => [
				'to'       => 'testimonials',
				'repeater' => [
					'testimonial_rating' => 'rating',
					'testimonial_text'   => 'text',
					'testimonial_author' => 'author',
					'testimonial_role'   => 'role',
				],
			],
		],
		'section_cta_banner' => [
			'cta_heading'      => 'heading',
			'cta_text'         => 'text',
			'cta_button_text'  => 'button_text',
			'cta_button_url'   => 'button_url',
			'cta_button2_text' => 'button2_text',
			'cta_button2_url'  => 'button2_url',
		],
		'page_header' => [
			'page_title'    => 'title',
			'page_subtitle' => 'subtitle',
		],
		'brand_story' => [
			'heading'            => 'heading',
			'body'               => 'body',
			'image'              => 'image',
			'section_background' => 'section_background',
		],
		'methods' => [
			'section_label'      => 'label',
			'section_heading'    => 'heading',
			'section_background' => 'section_background',
			'methods' => [
				'to'       => 'methods',
				'repeater' => [
					'method_icon_svg' => 'icon',
					'method_title'    => 'title',
					'method_text'     => 'text',
				],
			],
		],
		'product_line' => [
			'section_heading'    => 'heading',
			'section_background' => 'section_background',
			'products' => [
				'to'       => 'products',
				'repeater' => [
					'product_name'         => 'name',
					'product_description'  => 'description',
					'product_instructions' => 'instructions',
				],
			],
		],
		'what_it_protects' => [
			'section_label'      => 'label',
			'section_heading'    => 'heading',
			'section_desc'       => 'description',
			'section_background' => 'section_background',
			'items' => [
				'to'       => 'items',
				'repeater' => [
					'item_icon_svg'    => 'icon',
					'item_title'       => 'title',
					'item_description' => 'description',
				],
			],
		],
		'safety' => [
			'section_label'      => 'label',
			'section_heading'    => 'heading',
			'section_background' => 'section_background',
			'body'      => 'body',
			'cta_label' => 'cta_label',
			'cta_url'   => 'cta_url',
			// `sds_files` intentionally not mapped: its legacy ACF field type
			// could not be confirmed (empty on every live page checked), and
			// the new `sds_files` repeater on `safety_information` has a
			// different shape -- revisit once real data exists.
		],
		'section_video_grid' => [
			'section_background' => 'section_background',
			'section_label'      => 'label',
			'section_heading'    => 'heading',
			'section_note'       => 'note',
			'columns'            => 'columns',
			'videos'             => 'videos', // repeater sub-fields already match: video_type/video_embed/video_file/video_poster/video_caption
		],
	];
}

/**
 * Detect a row's true legacy layout from the sub-fields it actually
 * contains, rather than trusting its `acf_fc_layout` value.
 *
 * This is necessary because a live-site WXR export (verified August 2026)
 * showed `page_sections` rows 2 and 3 on the Home page with their
 * `acf_fc_layout` value swapped relative to their actual field data (row 2
 * labelled `section_brand_story` but containing `featured_products_*`
 * fields, and vice versa), and `instructions_sections` row 1 missing its
 * `acf_fc_layout` meta entirely. Detecting by field signature is robust to
 * both problems.
 *
 * @return string|null Legacy layout name, or null if no signature matched.
 */
function doubletap_migrate_detect_layout( array $row ): ?string {
	if ( array_key_exists( 'hero_heading', $row ) || array_key_exists( 'hero_background_image', $row ) ) {
		return 'section_hero';
	}
	if ( array_key_exists( 'category_tiles_heading', $row ) ) {
		return 'section_category_tiles';
	}
	if ( array_key_exists( 'featured_products_label', $row ) || array_key_exists( 'featured_product_ids', $row ) ) {
		return 'section_featured_products';
	}
	if ( array_key_exists( 'brand_story_label', $row ) || array_key_exists( 'brand_story_heading', $row ) ) {
		return 'section_brand_story';
	}
	if ( array_key_exists( 'features_section_heading', $row ) || array_key_exists( 'features_section_label', $row ) ) {
		return 'section_features';
	}
	if ( array_key_exists( 'testimonials_heading', $row ) && array_key_exists( 'testimonials_label', $row ) ) {
		return 'section_testimonials';
	}
	if ( array_key_exists( 'cta_heading', $row ) || array_key_exists( 'cta_text', $row ) ) {
		return 'section_cta_banner';
	}
	if ( array_key_exists( 'page_title', $row ) ) {
		return 'page_header';
	}
	if ( array_key_exists( 'products', $row ) ) {
		return 'product_line';
	}
	if ( array_key_exists( 'methods', $row ) ) {
		return 'methods';
	}
	if ( array_key_exists( 'items', $row ) && array_key_exists( 'section_desc', $row ) ) {
		return 'what_it_protects';
	}
	if ( array_key_exists( 'cta_url', $row ) || array_key_exists( 'sds_files', $row ) ) {
		return 'safety';
	}
	if ( array_key_exists( 'heading', $row ) && array_key_exists( 'image', $row ) ) {
		return 'brand_story';
	}
	if ( array_key_exists( 'videos', $row ) || array_key_exists( 'section_heading', $row ) ) {
		return 'section_video_grid';
	}
	return null;
}

/**
 * Copy one legacy row's sub-fields into a new content_sections row array,
 * ready to be appended via update_field()/add_row(), based on the map.
 */
function doubletap_migrate_map_row( string $legacy_layout, string $new_layout, array $row ): array {
	$field_map = doubletap_migrate_field_map();
	$mapped    = [ 'acf_fc_layout' => $new_layout ];

	if ( empty( $field_map[ $legacy_layout ] ) ) {
		return $mapped;
	}

	foreach ( $field_map[ $legacy_layout ] as $old_key => $spec ) {
		if ( ! array_key_exists( $old_key, $row ) ) {
			continue;
		}

		if ( is_array( $spec ) ) {
			// Repeater field: rename each sub-field within every row too.
			$new_key       = $spec['to'];
			$sub_field_map = $spec['repeater'];
			$old_rows      = is_array( $row[ $old_key ] ) ? $row[ $old_key ] : [];
			$new_sub_rows  = [];
			foreach ( $old_rows as $sub_row ) {
				$mapped_sub = [];
				foreach ( $sub_field_map as $old_sub => $new_sub ) {
					if ( is_array( $sub_row ) && array_key_exists( $old_sub, $sub_row ) ) {
						$mapped_sub[ $new_sub ] = $sub_row[ $old_sub ];
					}
				}
				$new_sub_rows[] = $mapped_sub;
			}
			$mapped[ $new_key ] = $new_sub_rows;
		} else {
			$mapped[ $spec ] = $row[ $old_key ];
		}
	}

	return $mapped;
}

/**
 * Migrate a legacy flexible-content field's rows into content_sections.
 *
 * @param int    $post_id
 * @param string $legacy_field
 * @param bool   $dry_run
 * @return int Number of rows migrated.
 */
function doubletap_migrate_flexible_field( int $post_id, string $legacy_field, bool $dry_run ): int {
	$raw_rows = get_field( $legacy_field, $post_id );
	if ( empty( $raw_rows ) || ! is_array( $raw_rows ) ) {
		return 0;
	}

	$layout_map = doubletap_migrate_layout_map();
	$new_rows   = get_field( 'content_sections', $post_id );
	$new_rows   = is_array( $new_rows ) ? $new_rows : [];
	$migrated   = 0;

	foreach ( $raw_rows as $row ) {
		// Detect the row's true layout from its actual field data first
		// (robust to mislabeled/missing acf_fc_layout -- see
		// doubletap_migrate_detect_layout() docblock), falling back to the
		// row's own acf_fc_layout value only if no signature matched.
		$legacy_layout = doubletap_migrate_detect_layout( $row );
		if ( empty( $legacy_layout ) ) {
			$legacy_layout = $row['acf_fc_layout'] ?? '';
		}
		if ( empty( $legacy_layout ) || empty( $layout_map[ $legacy_layout ] ) ) {
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::warning( "  Skipping unmapped layout '{$legacy_layout}' on post {$post_id}." );
			}
			continue;
		}
		$new_layout = $layout_map[ $legacy_layout ];
		$new_rows[] = doubletap_migrate_map_row( $legacy_layout, $new_layout, $row );
		$migrated++;
	}

	if ( $migrated > 0 && ! $dry_run ) {
		update_field( 'content_sections', $new_rows, $post_id );
	}

	return $migrated;
}

/**
 * Migrate the fixed (non-flexible-content) fields on a template-landing.php
 * page into content_sections rows, following the same ordering the template
 * currently hardcodes: hero, warning_strip, text_image (intro), pairing_cards,
 * application_steps, video_showcase, compatibility_grid, testimonials, cta_banner.
 *
 * @param int  $post_id
 * @param bool $dry_run
 * @return int Number of rows migrated.
 */
function doubletap_migrate_landing_page( int $post_id, bool $dry_run ): int {
	$rows = [];

	// Hero
	$hero_title = get_field( 'hero_title', $post_id );
	if ( $hero_title || get_field( 'hero_image', $post_id ) ) {
		$rows[] = [
			'acf_fc_layout'      => 'hero',
			'eyebrow'            => get_field( 'hero_eyebrow', $post_id ),
			'heading'            => $hero_title,
			'subheading'         => get_field( 'hero_subtitle', $post_id ),
			'background_image'  => get_field( 'hero_image', $post_id ),
			'cta_primary_text'   => get_field( 'hero_cta_primary_label', $post_id ),
			'cta_primary_url'    => get_field( 'hero_cta_primary_url', $post_id ),
			'cta_secondary_text' => get_field( 'hero_cta_secondary_label', $post_id ),
			'cta_secondary_url'  => get_field( 'hero_cta_secondary_url', $post_id ),
		];
	}

	// Warning strip
	if ( get_field( 'warning_show', $post_id ) ) {
		$rows[] = [
			'acf_fc_layout' => 'warning_strip',
			'title'         => get_field( 'warning_title', $post_id ),
			'text'          => get_field( 'warning_text', $post_id ),
		];
	}

	// Intro -> text_image (the "Protection That Performs Under Pressure" section)
	$intro_heading = get_field( 'intro_heading', $post_id );
	if ( $intro_heading || get_field( 'intro_image', $post_id ) ) {
		$rows[] = [
			'acf_fc_layout' => 'text_image',
			'heading'       => $intro_heading,
			'body'          => get_field( 'intro_body', $post_id ),
			'image'         => get_field( 'intro_image', $post_id ),
			'image_position' => 'right',
			'section_background' => 'bg',
		];
	}

	// Pairing cards
	$pairing_cards = get_field( 'pairing_cards', $post_id );
	if ( ! empty( $pairing_cards ) ) {
		$rows[] = [
			'acf_fc_layout' => 'pairing_cards',
			'heading'       => get_field( 'pairing_heading', $post_id ),
			'cards'         => $pairing_cards,
		];
	}

	// Application steps
	$steps = get_field( 'steps', $post_id );
	if ( ! empty( $steps ) ) {
		$rows[] = [
			'acf_fc_layout' => 'application_steps',
			'heading'       => get_field( 'steps_heading', $post_id ),
			'steps'         => $steps,
		];
	}

	// Video showcase
	if ( get_field( 'video_show', $post_id ) && get_field( 'video_id', $post_id ) ) {
		$rows[] = [
			'acf_fc_layout' => 'video_showcase',
			'heading'       => get_field( 'video_heading', $post_id ),
			'provider'      => get_field( 'video_provider', $post_id ),
			'video_id'      => get_field( 'video_id', $post_id ),
			'poster'        => get_field( 'video_poster', $post_id ),
		];
	}

	// Compatibility grid
	$compat_items = get_field( 'compat_items', $post_id );
	if ( ! empty( $compat_items ) ) {
		$rows[] = [
			'acf_fc_layout' => 'compatibility_grid',
			'heading'       => get_field( 'compat_heading', $post_id ),
			'items'         => $compat_items,
		];
	}

	// Testimonials — legacy field uses "quote"/"name"/"role"; new layout uses "text"/"author"/"role".
	$raw_testimonials = get_field( 'testimonials', $post_id );
	if ( ! empty( $raw_testimonials ) ) {
		$mapped_testimonials = array_map( function ( $t ) {
			return [
				'text'   => $t['quote'] ?? '',
				'author' => $t['name'] ?? '',
				'role'   => $t['role'] ?? '',
			];
		}, $raw_testimonials );

		$rows[] = [
			'acf_fc_layout' => 'testimonials',
			'heading'       => get_field( 'testimonials_heading', $post_id ),
			'testimonials'  => $mapped_testimonials,
		];
	}

	// CTA banner
	if ( get_field( 'cta_show', $post_id ) ) {
		$rows[] = [
			'acf_fc_layout' => 'cta_banner',
			'heading'       => get_field( 'cta_title', $post_id ),
			'text'          => get_field( 'cta_subtitle', $post_id ),
			'button_text'   => get_field( 'cta_button_label', $post_id ),
			'button_url'    => get_field( 'cta_button_url', $post_id ),
		];
	}

	if ( ! empty( $rows ) && ! $dry_run ) {
		update_field( 'content_sections', $rows, $post_id );
	}

	return count( $rows );
}

/**
 * Migrates the Contact page's fixed "Contact Page Settings" fields
 * (contact_phone, contact_email, contact_response_time,
 * contact_instagram_handle, contact_instagram_url, dealer_inquiry_heading,
 * dealer_inquiry_text, gravity_form_id) into a single universal
 * `contact_info` content_sections row. Mirrors migrate_contact_page() in
 * build_migrated_wxr.py. Non-destructive: the original fixed fields are
 * left untouched.
 *
 * @param int  $post_id
 * @param bool $dry_run
 * @return int Number of rows migrated (0 or 1).
 */
function doubletap_migrate_contact_page( int $post_id, bool $dry_run ): int {
	$rows = [];

	$phone = get_field( 'contact_phone', $post_id );
	if ( $phone || get_field( 'contact_email', $post_id ) ) {
		$rows[] = [
			'acf_fc_layout'    => 'contact_info',
			'phone'            => $phone,
			'email'            => get_field( 'contact_email', $post_id ),
			'response_time'    => get_field( 'contact_response_time', $post_id ),
			'instagram_handle' => get_field( 'contact_instagram_handle', $post_id ),
			'instagram_url'    => get_field( 'contact_instagram_url', $post_id ),
			'dealer_heading'   => get_field( 'dealer_inquiry_heading', $post_id ),
			'dealer_text'      => get_field( 'dealer_inquiry_text', $post_id ),
			'form_heading'     => 'Send A Message',
			'gravity_form_id'  => get_field( 'gravity_form_id', $post_id ),
		];
	}

	if ( ! empty( $rows ) && ! $dry_run ) {
		update_field( 'content_sections', $rows, $post_id );
	}

	return count( $rows );
}

/**
 * Shared migration core, used by both the WP-CLI command and the REST
 * endpoint in inc/rest-migrate-sections.php. Migrates legacy
 * flexible-content fields (page_sections, info_sections,
 * instructions_sections), template-landing.php's fixed fields, and
 * page-contact.php's fixed fields into the new universal `content_sections`
 * field. Non-destructive: legacy data is left untouched. Idempotent: pages
 * that already have content_sections rows are skipped.
 *
 * @param int  $single_post_id Only migrate this page ID. 0 = every eligible page.
 * @param bool $dry_run        Preview without writing any changes.
 * @return array{
 *   ok: bool,
 *   error: string,
 *   dry_run: bool,
 *   total_pages: int,
 *   total_sections: int,
 *   migrated: array<int, array{post_id:int, title:string, sections:int}>,
 *   skipped: array<int, array{post_id:int, title:string, reason:string}>,
 * }
 */
function doubletap_run_sections_migration( int $single_post_id = 0, bool $dry_run = false ): array {
	$result = [
		'ok'             => false,
		'error'          => '',
		'dry_run'        => $dry_run,
		'total_pages'    => 0,
		'total_sections' => 0,
		'migrated'       => [],
		'skipped'        => [],
	];

	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		$result['error'] = 'Advanced Custom Fields (Pro) must be active to run this migration.';
		return $result;
	}

	$query_args = [
		'post_type'      => 'page',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	];
	if ( $single_post_id ) {
		$query_args['p'] = $single_post_id;
	}

	$post_ids = get_posts( $query_args );
	if ( empty( $post_ids ) ) {
		$result['ok']    = true;
		$result['error'] = 'No pages found to migrate.';
		return $result;
	}

	foreach ( $post_ids as $post_id ) {
		$title = get_the_title( $post_id );

		// Idempotency guard: skip pages that already have content_sections rows.
		$existing = get_field( 'content_sections', $post_id );
		if ( ! empty( $existing ) ) {
			$result['skipped'][] = [
				'post_id' => $post_id,
				'title'   => $title,
				'reason'  => 'content_sections already populated',
			];
			continue;
		}

		$template     = get_page_template_slug( $post_id );
		$migrated_now = 0;

		if ( $template === 'template-landing.php' ) {
			$migrated_now = doubletap_migrate_landing_page( $post_id, $dry_run );
		} elseif ( $template === 'page-information.php' ) {
			$migrated_now = doubletap_migrate_flexible_field( $post_id, 'info_sections', $dry_run );
		} elseif ( $template === 'page-instructions.php' ) {
			$migrated_now = doubletap_migrate_flexible_field( $post_id, 'instructions_sections', $dry_run );
		} elseif ( $template === 'page-contact.php' ) {
			$migrated_now = doubletap_migrate_contact_page( $post_id, $dry_run );
		} elseif ( (int) get_option( 'page_on_front' ) === $post_id || $template === 'default' || empty( $template ) ) {
			$migrated_now = doubletap_migrate_flexible_field( $post_id, 'page_sections', $dry_run );
		} else {
			$result['skipped'][] = [
				'post_id' => $post_id,
				'title'   => $title,
				'reason'  => 'no legacy sections field for this template',
			];
			continue;
		}

		if ( $migrated_now > 0 ) {
			$result['total_pages']++;
			$result['total_sections'] += $migrated_now;
			$result['migrated'][] = [
				'post_id'  => $post_id,
				'title'    => $title,
				'sections' => $migrated_now,
			];
		} else {
			$result['skipped'][] = [
				'post_id' => $post_id,
				'title'   => $title,
				'reason'  => 'no legacy content found to migrate',
			];
		}
	}

	$result['ok'] = true;
	return $result;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {

	/**
	 * WP-CLI command class. Thin wrapper around doubletap_run_sections_migration().
	 */
	class Doubletap_Migrate_Sections_Command {

		/**
		 * Migrate legacy flexible-content fields (page_sections, info_sections,
		 * instructions_sections) and template-landing.php's fixed fields into
		 * the new universal `content_sections` field. Non-destructive: legacy
		 * data is left untouched. Idempotent: pages that already have
		 * content_sections rows are skipped.
		 *
		 * ## OPTIONS
		 *
		 * [--post_id=<id>]
		 * : Only migrate a single page by ID.
		 *
		 * [--dry-run]
		 * : Preview what would be migrated without writing any changes.
		 *
		 * ## EXAMPLES
		 *
		 *     wp doubletap migrate-sections
		 *     wp doubletap migrate-sections --dry-run
		 *     wp doubletap migrate-sections --post_id=42
		 *
		 * @when after_wp_load
		 */
		public function migrate_sections( $args, $assoc_args ) {
			$dry_run = isset( $assoc_args['dry-run'] );
			$single  = isset( $assoc_args['post_id'] ) ? (int) $assoc_args['post_id'] : 0;

			$result = doubletap_run_sections_migration( $single, $dry_run );

			if ( ! $result['ok'] ) {
				WP_CLI::error( $result['error'] );
				return;
			}

			if ( $result['error'] ) {
				WP_CLI::warning( $result['error'] );
				return;
			}

			foreach ( $result['skipped'] as $skip ) {
				WP_CLI::log( "Skipping post {$skip['post_id']} (\"{$skip['title']}\") - {$skip['reason']}." );
			}

			$verb = $dry_run ? 'Would migrate' : 'Migrated';
			foreach ( $result['migrated'] as $page ) {
				WP_CLI::success( "{$verb} {$page['sections']} section(s) for post {$page['post_id']} (\"{$page['title']}\")." );
			}

			$summary_verb = $dry_run ? 'Dry run complete.' : 'Migration complete.';
			WP_CLI::log( "{$summary_verb} {$result['total_pages']} page(s), {$result['total_sections']} section(s) total." );
		}
	}

	WP_CLI::add_command( 'doubletap', 'Doubletap_Migrate_Sections_Command' );
}
