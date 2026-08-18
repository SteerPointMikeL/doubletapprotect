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
│       ├── section_hero.php
│       ├── section_category_tiles.php
│       ├── section_brand_story.php
│       ├── section_featured_products.php
│       ├── section_features.php
│       ├── section_testimonials.php
│       └── section_cta_banner.php
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
│   └── gravity-forms.php           Gravity Forms styling hooks
│
└── acf-json/                         ACF local JSON sync (auto-generated)
```

---

## Contact

**Developer:** Mike Lundy — SteerPoint  
**Project Lead:** Jayson — jayson@steerpoint.com  
**Client:** Ian — ian@aglaze-us.com  
