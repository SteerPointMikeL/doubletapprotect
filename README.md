# Double Tap Protect — WordPress Theme

**Version:** 1.0.0  
**Author:** SteerPoint  
**Target Site:** doubletapprotector.com  

---

## Requirements

| Dependency | Version |
|---|---|
| WordPress | 6.3+ |
| PHP | 8.1+ |
| Advanced Custom Fields Pro | 6.x |
| WooCommerce | 8.0+ |
| Gravity Forms | 2.7+ |

---

## Installation

1. Upload the `doubletap-theme/` folder to `/wp-content/themes/`
2. Activate the theme in **Appearance → Themes**
3. ACF field groups will auto-register on activation — check **Custom Fields → Field Groups** to verify
4. If field groups show "Sync Available" in ACF, click **Sync** to pull from `/acf-json/`

---

## ACF Flexible Content — Homepage

The homepage is driven by a **Flexible Content** field called `page_sections`. Each section can be added, removed, and reordered from the WordPress admin.

**Available section layouts:**

| Layout Name | Description |
|---|---|
| `section_hero` | Full-viewport hero with background image, heading, and CTAs |
| `section_category_tiles` | 4-column product category tiles |
| `section_brand_story` | Two-column copy + image section |
| `section_featured_products` | Product grid pulling selected WooCommerce products |
| `section_features` | 3-column benefits/features grid with icons |
| `section_testimonials` | 3-column testimonials grid |
| `section_cta_banner` | Full-width olive green CTA banner |

To manage the homepage sections:
1. Edit the front page (Pages → Front Page or the page set as "Homepage" in Settings → Reading)
2. Scroll down to the **Homepage Sections** meta box
3. Click **Add Section**, choose a layout, and fill in the fields
4. Sections can be dragged to reorder

> **Note:** `page_sections` is kept for backward compatibility. New work
> should use the universal `content_sections` field described below —
> see [ACF Flexible Content — Universal Sections](#acf-flexible-content--universal-sections-recommended).

---

## ACF Flexible Content — Universal Sections (recommended)

Every page (`post_type == page`, any template) now has access to a single
**Flexible Content** field called `content_sections`
(`acf-json/group_universal_content_sections.json`, field group key
`group_universal_content_sections`). Unlike the older per-template fields
below, this field is not locked to a specific page or template — any of its
17 layouts can be added, removed, or reordered on **any** page from the
normal WordPress editor.

This replaces the need for hardcoded, page-specific markup (like the old
fixed sections on the Firearms/Knives/Pistols/Rifles/Scopes/Tactical
landing pages) or content locked into a single template's ACF group (like
the old Home-only `page_sections` or Information-only `info_sections`
fields). A shared section — for example the "Protection That Performs
Under Pressure" text + image block on the Firearms page — is now just one
`text_image` row that can be reused verbatim on any other page.

**Available layouts:**

| Layout Name | Description |
|---|---|
| `hero` | Full-viewport hero: eyebrow, heading (supports `{word}` accent syntax), subheading, background image, optional logo, up to 2 CTAs |
| `page_header` | Simple title + subtitle band |
| `warning_strip` | Compact safety/warning banner |
| `text_image` | **Canonical** two-column text + image block (label, heading, rich text body, image, left/right position) — e.g. "Protection That Performs Under Pressure" |
| `category_tiles` | Grid of linked image tiles |
| `featured_products` | WooCommerce product grid (manually selected or auto-filled) |
| `features_grid` | 3 or 4-column icon + text benefits grid |
| `pairing_cards` | Card grid with badge, tag, title, body, and bullet list |
| `application_steps` | Numbered step-by-step grid |
| `application_methods` | Icon + title + text method cards |
| `product_line` | Repeating product name/description/instructions blocks |
| `video_showcase` | Single click-to-play video with poster frame |
| `video_grid` | Multi-column grid of embedded or HTML5 videos with captions |
| `compatibility_grid` | Icon + label compatibility grid |
| `testimonials` | Testimonial grid with optional star rating |
| `safety_information` | Safety copy + downloadable SDS file list |
| `cta_banner` | Full-width CTA banner with up to 2 buttons |

Field/sub-field names for each layout are documented as PHP docblocks at
the top of their corresponding template part in
`template-parts/sections/{layout_name}.php`, and are generated from
`dev/build_acf_group.py` (kept in the repo as the maintained source of
truth for the field group — edit the layout definitions there and re-run
`python3 dev/build_acf_group.py` from the theme root if you need to add a
new layout or field; ACF Pro will offer to sync the regenerated JSON on the
next admin page load).

**Non-destructive rollout:** legacy fields (`page_sections`,
`info_sections`, `instructions_sections`, and the fixed fields on
`template-landing.php`) are untouched and still work. Every template
checks `content_sections` first; if it has no rows, the page automatically
falls back to whatever legacy content already exists, so no existing page
breaks during rollout.

**Migrating legacy content into `content_sections`:** the same
non-destructive, idempotent migration logic
(`doubletap_run_sections_migration()` in `inc/cli-migrate-sections.php`) is
reachable two ways — pick whichever fits your hosting access:

1. **WP-CLI** (needs SSH/terminal access to the server):

   ```bash
   wp doubletap migrate-sections            # migrate every eligible page
   wp doubletap migrate-sections --dry-run  # preview without writing
   wp doubletap migrate-sections --post_id=42  # migrate a single page
   ```

2. **REST endpoint** (no SSH needed — for shared hosting or when only an
   admin login is available), registered in `inc/rest-migrate-sections.php`:

   ```
   POST /wp-json/doubletap/v1/migrate-sections
   Body (JSON, all optional): { "post_id": 42, "dry_run": true }
   ```

   Auth is a WordPress administrator account authenticated via core
   **Application Passwords** (Users → Profile → Application Passwords —
   built into WordPress since 5.6, no plugin needed), sent as HTTP Basic
   auth. HTTPS is strongly recommended in production; this endpoint doesn't
   hard-require it since dev/staging hosts often lack TLS (see
   `inc/rest-migrate-sections.php` if you want to re-add that check on a
   production HTTPS site). Example:

   ```bash
   curl -X POST http://your-site.example/wp-json/doubletap/v1/migrate-sections \
     -u "admin_username:xxxx xxxx xxxx xxxx xxxx xxxx" \
     -H "Content-Type: application/json" \
     -d '{"dry_run": true}'
   ```

   The response is JSON: `{ ok, total_pages, total_sections, migrated: [...], skipped: [...] }`.

Either way, the migration is idempotent (it skips any page that already
has `content_sections` rows) and additive (it never deletes the legacy
field data), so it can be re-run safely at any time. See
`inc/cli-migrate-sections.php` for the full layout/field mapping.

---

## Page Templates

| Template File | WordPress Usage |
|---|---|
| `front-page.php` | Auto-used when a page is set as the static front page |
| `page-information.php` | Assign via **Page Attributes → Template: Information** |
| `page-contact.php` | Assign via **Page Attributes → Template: Contact** |

---

## WooCommerce

- Default WooCommerce styles are disabled — all styles come from `style.css`
- Product badge: add `_product_badge` custom text in the product editor (General tab → "Product Badge")
- Merchant Kit (SKU: `DT-MK-001`) is marked non-purchasable and redirects to `/contact/`
- Override templates are in `woocommerce/` at the theme root

### Required Pages

Create WordPress pages with these exact slugs:

| Page | Slug | Template |
|---|---|---|
| Home | `/` (set as front page) | Default |
| Information | `information` | Information |
| Shop | `shop` | WooCommerce auto-creates |
| Contact Us | `contact` | Contact |

---

## Gravity Forms

1. Create the Contact Us form with these fields: First Name, Last Name, Email, Subject (Select), Message
2. Note the form ID
3. Edit the Contact page → set the **Gravity Form ID** ACF field to that ID

Subject dropdown options:
- Product Question
- Order Status
- Wholesale/Dealer Inquiry
- Safety Data Sheet Request
- Other

---

## Logo Files

| File | Use |
|---|---|
| `assets/images/logo.svg` | Dark backgrounds (white text, white outline, green shield) — used in nav + footer |
| `assets/images/logo-full-color.svg` | Light background use if needed |

---

## ACF JSON Sync

The `acf-json/` directory stores field group schemas for version control. Always commit this directory. When deploying to a new environment, ACF Pro will detect changes and prompt you to sync.

---

## Theme File Map

```
doubletapprotect/
├── style.css                         Theme header + ALL design tokens + component CSS
├── functions.php                     Theme setup, enqueues, ACF JSON, includes
├── header.php                        Top bar + sticky nav
├── footer.php                        4-column footer
├── front-page.php                    Homepage (ACF flexible content loop)
├── page-information.php              /information/ page template
├── page-contact.php                  /contact/ page template
├── index.php                         Blog fallback
├── 404.php                           404 page
│
├── template-parts/
│   └── sections/
│       ├── hero.php                  Universal content_sections layouts (17 total,
│       ├── page_header.php           see "ACF Flexible Content — Universal Sections"
│       ├── warning_strip.php         above for the full list and field names)
│       ├── text_image.php
│       ├── category_tiles.php
│       ├── featured_products.php
│       ├── features_grid.php
│       ├── pairing_cards.php
│       ├── application_steps.php
│       ├── application_methods.php
│       ├── product_line.php
│       ├── video_showcase.php
│       ├── video_grid.php
│       ├── compatibility_grid.php
│       ├── testimonials.php
│       ├── safety_information.php
│       ├── cta_banner.php
│       │
│       ├── section_hero.php          Legacy (page_sections), kept for fallback
│       ├── section_category_tiles.php
│       ├── section_brand_story.php
│       ├── section_featured_products.php
│       ├── section_features.php
│       ├── section_testimonials.php
│       ├── section_cta_banner.php
│       ├── info_page_header.php      Legacy (info_sections), kept for fallback
│       ├── info_brand_story.php
│       ├── info_methods.php
│       ├── info_product_line.php
│       ├── info_what_it_protects.php
│       ├── info_safety.php
│       └── section_video_grid.php    Legacy (instructions_sections), kept for fallback
│
├── woocommerce/
│   ├── archive-product.php           Shop listing page
│   ├── single-product.php            Single product page
│   └── content-product.php          Product card (loop item)
│
├── assets/
│   ├── images/
│   │   ├── logo.svg                  Nav/footer logo (dark bg)
│   │   └── logo-full-color.svg       Full-color version
│   ├── js/
│   │   ├── navigation.js             Mobile toggle + scroll header
│   │   └── animations.js            Fade-in IntersectionObserver
│   └── (css — no separate file; all CSS is in style.css)
│
├── inc/
│   ├── acf-fields.php               ACF field groups (programmatic)
│   ├── woocommerce.php              WooCommerce hooks + overrides
│   ├── gravity-forms.php           Gravity Forms styling hooks
│   ├── cli-migrate-sections.php    Shared migration logic + WP-CLI command
│   └── rest-migrate-sections.php   REST endpoint for the same migration (no SSH needed)
│
├── dev/
│   └── build_acf_group.py          Source of truth generator for content_sections' ACF JSON
│
└── acf-json/                         ACF local JSON sync (auto-generated)
```

---

## Contact

**Developer:** Mike Lundy — SteerPoint  
**Project Lead:** Jayson — jayson@steerpoint.com  
**Client:** Ian — ian@aglaze-us.com  
