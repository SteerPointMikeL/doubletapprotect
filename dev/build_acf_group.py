#!/usr/bin/env python3
"""Generates acf-json/group_universal_content_sections.json for the Double Tap Protect theme.

This is a maintained build-time dev tool (not loaded by the theme at
runtime — ACF only reads the generated JSON in ../acf-json/). It is the
source of truth for the `content_sections` universal field group: to add,
remove, or change a layout/field on `content_sections`, edit the layout
definitions below and re-run this script from the theme root:

    python3 dev/build_acf_group.py

This regenerates ../acf-json/group_universal_content_sections.json, which
ACF Pro will then offer to "Sync" on the next admin page load.
"""
import json
import time

WRAPPER = {"width": "", "class": "", "id": ""}


def _base(key, label, name, ftype, **extra):
    f = {
        "key": key,
        "label": label,
        "name": name,
        "aria-label": "",
        "type": ftype,
        "instructions": extra.pop("instructions", ""),
        "required": extra.pop("required", 0),
        "conditional_logic": 0,
        "wrapper": dict(WRAPPER, **extra.pop("wrapper", {})),
        "allow_in_bindings": 0,
    }
    f.update(extra)
    return f


def text(key, label, name, default="", **kw):
    return _base(key, label, name, "text", default_value=default, maxlength="", placeholder=kw.pop("placeholder", ""), prepend="", append="", **kw)


def textarea(key, label, name, default="", rows=3, **kw):
    return _base(key, label, name, "textarea", default_value=default, maxlength="", placeholder=kw.pop("placeholder", ""), rows=rows, new_lines="", **kw)


def wysiwyg(key, label, name, **kw):
    return _base(key, label, name, "wysiwyg", default_value="", tabs="all", toolbar=kw.pop("toolbar", "basic"), media_upload=0, delay=0, **kw)


def url(key, label, name, default="", **kw):
    return _base(key, label, name, "url", default_value=default, placeholder=kw.pop("placeholder", ""), **kw)


def email_field(key, label, name, default="", **kw):
    return _base(key, label, name, "email", default_value=default, placeholder=kw.pop("placeholder", ""), prepend="", append="", **kw)


def image(key, label, name, **kw):
    return _base(
        key, label, name, "image",
        return_format="array", library="all", min_width="", min_height="", min_size="",
        max_width="", max_height="", max_size="", mime_types="", preview_size="medium", **kw,
    )


def file_field(key, label, name, **kw):
    return _base(
        key, label, name, "file",
        return_format="array", library="all", min_size="", max_size="", mime_types="", **kw,
    )


def select(key, label, name, choices, default="", multiple=0, **kw):
    return _base(
        key, label, name, "select",
        choices=choices, default_value=default, allow_null=0, multiple=multiple,
        ui=1, ajax=0, placeholder="", return_format="value", **kw,
    )


def true_false(key, label, name, default=0, **kw):
    return _base(key, label, name, "true_false", default_value=default, ui=1, ui_on_text="", ui_off_text="", message="", **kw)


def number(key, label, name, default=0, minimum=None, maximum=None, step=1, **kw):
    return _base(
        key, label, name, "number", default_value=default, placeholder="", prepend="", append="",
        min=minimum if minimum is not None else "", max=maximum if maximum is not None else "", step=step, **kw,
    )


def relationship(key, label, name, post_types, maximum=0, **kw):
    return _base(
        key, label, name, "relationship",
        post_type=post_types, taxonomy=[], filters=["search", "post_type"], elements=[],
        min=0, max=maximum, return_format="id", bidirectional_target=[], **kw,
    )


def repeater(key, label, name, sub_fields, button_label="Add Row", minimum=0, maximum="", **kw):
    for sf in sub_fields:
        sf["parent_repeater"] = key
    return _base(
        key, label, name, "repeater",
        collapsed="", min=minimum, max=maximum, layout="block", button_label=button_label,
        rows_per_page=20, sub_fields=sub_fields, **kw,
    )


def layout(key, name, label, sub_fields):
    return {
        "key": key,
        "name": name,
        "label": label,
        "display": "block",
        "sub_fields": sub_fields,
        "min": "",
        "max": "",
    }


BG_CHOICES = {"bg": "Dark (--color-bg)", "surface": "Surface (--color-surface)"}

layouts = {}


def add(name, label, sub_fields):
    key = f"layout_cs_{name}"
    layouts[key] = layout(key, name, label, sub_fields)


# 1. Hero -----------------------------------------------------------------
add("hero", "Hero", [
    select("field_cs_hero_style", "Style", "hero_style",
           {"full": "Full-Bleed (Home)", "compact": "Compact (Inside Pages)"}, default="full",
           instructions="Full-Bleed fills most of the viewport with a darker overlay (used on Home). Compact is shorter with a lighter overlay so the photo reads more clearly (used on inside/landing pages)."),
    text("field_cs_hero_eyebrow", "Eyebrow (optional)", "eyebrow"),
    text("field_cs_hero_heading", "Heading", "heading",
         instructions="Wrap a word in curly braces, e.g. {Firearm}, to render it in the accent color."),
    text("field_cs_hero_heading_accent", "Heading Accent Line (optional)", "heading_accent",
         instructions="If set, renders as a second accent-colored line below the heading."),
    textarea("field_cs_hero_subheading", "Subheading", "subheading", rows=2),
    image("field_cs_hero_bg_image", "Background Image", "background_image",
          instructions="Recommended: 1920\u00d71080 JPG."),
    true_false("field_cs_hero_show_logo", "Show Site Logo", "show_logo", default=0),
    text("field_cs_hero_cta1_text", "Primary CTA Text", "cta_primary_text"),
    text("field_cs_hero_cta1_url", "Primary CTA URL", "cta_primary_url"),
    text("field_cs_hero_cta2_text", "Secondary CTA Text", "cta_secondary_text"),
    text("field_cs_hero_cta2_url", "Secondary CTA URL", "cta_secondary_url"),
])

# 2. Page Header ------------------------------------------------------------
add("page_header", "Page Header", [
    text("field_cs_ph_title", "Title", "title"),
    text("field_cs_ph_subtitle", "Subtitle", "subtitle"),
])

# 3. Warning Strip -----------------------------------------------------------
add("warning_strip", "Warning Strip", [
    text("field_cs_warn_title", "Title", "title", default="Safety First"),
    text("field_cs_warn_text", "Text", "text"),
])

# 4. Text + Image (canonical "Protection That Performs Under Pressure") ----
add("text_image", "Text + Image", [
    text("field_cs_ti_label", "Label (optional eyebrow)", "label"),
    text("field_cs_ti_heading", "Heading", "heading"),
    wysiwyg("field_cs_ti_body", "Body Copy", "body"),
    image("field_cs_ti_image", "Image", "image"),
    file_field("field_cs_ti_video", "Background Video (optional)", "video",
               instructions="Self-hosted video file (mp4). If set, this plays muted/looped in place of the Image above; Image is used as a fallback/poster when no video is set."),
    select("field_cs_ti_image_pos", "Image Position", "image_position",
           {"right": "Right", "left": "Left"}, default="right"),
    text("field_cs_ti_cta_text", "CTA Button Text (optional)", "cta_text"),
    text("field_cs_ti_cta_url", "CTA Button URL (optional)", "cta_url"),
    select("field_cs_ti_bg", "Background", "section_background", BG_CHOICES, default="bg"),
])

# 5. Category Tiles ---------------------------------------------------------
add("category_tiles", "Category Tiles", [
    text("field_cs_cat_heading", "Section Heading (optional)", "heading"),
    text("field_cs_cat_sub", "Section Subheading (optional)", "subheading"),
    repeater("field_cs_cat_tiles", "Tiles", "tiles", [
        image("field_cs_cat_tile_image", "Image", "tile_image"),
        text("field_cs_cat_tile_title", "Title", "tile_title", required=1),
        textarea("field_cs_cat_tile_desc", "Description", "tile_description", rows=2),
        text("field_cs_cat_tile_link", "Link", "tile_link"),
        text("field_cs_cat_tile_cta", "CTA Button Text (optional)", "tile_cta_text",
             instructions="If set, renders a visible button with this label linking to Link above. If blank, the whole card stays clickable with no visible button label (previous behavior)."),
    ], button_label="Add Tile", minimum=1, maximum=8),
])

# 6. Featured Products -------------------------------------------------------
add("featured_products", "Featured Products", [
    text("field_cs_fp_label", "Section Label", "label", default="Best Sellers"),
    text("field_cs_fp_heading", "Section Heading", "heading", default="Featured Products"),
    text("field_cs_fp_desc", "Section Description (optional)", "description"),
    relationship("field_cs_fp_ids", "Products", "product_ids", ["product"], maximum=6,
                 instructions="Select up to 6 products. Leave empty to show 3 most recent published products."),
])

# 7. Features / Benefits Grid -----------------------------------------------
add("features_grid", "Features / Benefits Grid", [
    text("field_cs_feat_label", "Section Label", "label"),
    text("field_cs_feat_heading", "Section Heading", "heading"),
    text("field_cs_feat_desc", "Section Description (optional)", "description"),
    select("field_cs_feat_cols", "Columns", "columns", {"3": "3", "4": "4"}, default="3"),
    select("field_cs_feat_bg", "Background", "section_background", BG_CHOICES, default="bg"),
    repeater("field_cs_feat_items", "Items", "items", [
        textarea("field_cs_feat_item_icon", "Icon (SVG code)", "icon", rows=3,
                  instructions="Paste raw SVG markup."),
        text("field_cs_feat_item_title", "Title", "title", required=1),
        textarea("field_cs_feat_item_desc", "Description", "description", rows=3),
    ], button_label="Add Item", minimum=1, maximum=8),
])

# 8. Pairing Cards -----------------------------------------------------------
add("pairing_cards", "Pairing Cards", [
    text("field_cs_pair_heading", "Section Heading (optional)", "heading"),
    repeater("field_cs_pair_cards", "Cards", "cards", [
        text("field_cs_pair_badge", "Badge (optional)", "badge"),
        text("field_cs_pair_tag", "Tag (optional)", "tag"),
        text("field_cs_pair_title", "Title", "title", required=1),
        textarea("field_cs_pair_body", "Body", "body", rows=3),
        repeater("field_cs_pair_bullets", "Bullets (optional)", "bullets", [
            text("field_cs_pair_bullet_text", "Text", "text"),
        ], button_label="Add Bullet"),
    ], button_label="Add Card", minimum=1, maximum=6),
])

# 9. Application Steps --------------------------------------------------------
add("application_steps", "Application Steps", [
    text("field_cs_steps_heading", "Section Heading (optional)", "heading"),
    repeater("field_cs_steps_rows", "Steps", "steps", [
        text("field_cs_steps_number", "Number", "number", default="01"),
        text("field_cs_steps_title", "Title", "title", required=1),
        textarea("field_cs_steps_body", "Body", "body", rows=3),
    ], button_label="Add Step", minimum=1, maximum=8),
])

# 10. Application Methods -----------------------------------------------------
add("application_methods", "Application Methods", [
    text("field_cs_meth_label", "Section Label", "label"),
    text("field_cs_meth_heading", "Section Heading", "heading"),
    select("field_cs_meth_bg", "Background", "section_background", BG_CHOICES, default="surface"),
    repeater("field_cs_meth_rows", "Methods", "methods", [
        textarea("field_cs_meth_icon", "Icon (SVG code)", "icon", rows=3),
        text("field_cs_meth_title", "Title", "title", required=1),
        textarea("field_cs_meth_text", "Text", "text", rows=3),
    ], button_label="Add Method", minimum=1, maximum=6),
])

# 11. Product Line ------------------------------------------------------------
add("product_line", "Product Line", [
    text("field_cs_pl_heading", "Section Heading (optional)", "heading"),
    select("field_cs_pl_bg", "Background", "section_background", BG_CHOICES, default="bg"),
    repeater("field_cs_pl_products", "Products", "products", [
        text("field_cs_pl_name", "Product Name", "name", required=1),
        wysiwyg("field_cs_pl_desc", "Description", "description"),
        wysiwyg("field_cs_pl_instructions", "Application Instructions (optional)", "instructions"),
    ], button_label="Add Product", minimum=1),
])

# 12. Video Showcase ----------------------------------------------------------
add("video_showcase", "Video Showcase", [
    text("field_cs_vs_heading", "Section Heading (optional)", "heading"),
    select("field_cs_vs_provider", "Provider", "provider",
           {"youtube": "YouTube", "vimeo": "Vimeo"}, default="youtube"),
    text("field_cs_vs_video_id", "Video ID", "video_id",
         instructions="The YouTube/Vimeo video ID (not the full URL)."),
    image("field_cs_vs_poster", "Poster Image (optional)", "poster"),
])

# 13. Video Grid ---------------------------------------------------------------
add("video_grid", "Video Grid", [
    select("field_cs_vg_bg", "Background", "section_background",
           {"bg": "Dark (--color-bg)", "surface": "Surface (--color-surface)", "surface_2": "Surface 2 (--color-surface-2)"},
           default="bg"),
    text("field_cs_vg_label", "Section Label (optional)", "label"),
    text("field_cs_vg_heading", "Section Heading (optional)", "heading"),
    text("field_cs_vg_note", "Note (optional)", "note"),
    select("field_cs_vg_cols", "Columns", "columns",
           {"2": "2", "3": "3", "4": "4", "5": "5"}, default="3"),
    repeater("field_cs_vg_videos", "Videos", "videos", [
        select("field_cs_vg_video_type", "Type", "video_type",
               {"iframe": "Embed (iframe code)", "html5": "HTML5 File"}, default="iframe"),
        textarea("field_cs_vg_video_embed", "Embed Code", "video_embed", rows=3,
                  instructions="Paste the full <iframe> embed code from Vimeo/YouTube."),
        file_field("field_cs_vg_video_file", "Video File", "video_file"),
        image("field_cs_vg_video_poster", "Poster Image", "video_poster"),
        text("field_cs_vg_video_caption", "Caption (optional)", "video_caption"),
    ], button_label="Add Video", minimum=1),
])

# 14. Compatibility Grid --------------------------------------------------------
add("compatibility_grid", "Compatibility Grid", [
    text("field_cs_compat_heading", "Section Heading (optional)", "heading"),
    repeater("field_cs_compat_items", "Items", "items", [
        text("field_cs_compat_label", "Label", "label", required=1),
        image("field_cs_compat_icon", "Icon", "icon"),
    ], button_label="Add Item", minimum=1),
])

# 15. Testimonials ----------------------------------------------------------------
add("testimonials", "Testimonials", [
    text("field_cs_testi_label", "Section Label (optional)", "label"),
    text("field_cs_testi_heading", "Section Heading (optional)", "heading"),
    repeater("field_cs_testi_rows", "Testimonials", "testimonials", [
        number("field_cs_testi_rating", "Rating (optional)", "rating", default=5, minimum=1, maximum=5, step=1),
        textarea("field_cs_testi_text", "Quote", "text", rows=3, required=1),
        text("field_cs_testi_author", "Author Name", "author", required=1),
        text("field_cs_testi_role", "Role / Location (optional)", "role"),
    ], button_label="Add Testimonial", minimum=1, maximum=12),
])

# 16. Safety Information ------------------------------------------------------------
add("safety_information", "Safety Information", [
    text("field_cs_safety_label", "Section Label (optional)", "label"),
    text("field_cs_safety_heading", "Section Heading (optional)", "heading"),
    select("field_cs_safety_bg", "Background", "section_background", BG_CHOICES, default="bg"),
    wysiwyg("field_cs_safety_body", "Body Copy", "body"),
    repeater("field_cs_safety_files", "SDS Files (optional)", "sds_files", [
        text("field_cs_safety_file_name", "Product Name", "product_name", required=1),
        file_field("field_cs_safety_file", "File", "file"),
    ], button_label="Add File"),
    text("field_cs_safety_cta_label", "Fallback CTA Label (optional)", "cta_label",
         instructions="Shown only if no SDS files are added."),
    url("field_cs_safety_cta_url", "Fallback CTA URL (optional)", "cta_url"),
])

# 17. CTA Banner ------------------------------------------------------------------------
add("cta_banner", "CTA Banner", [
    text("field_cs_cta_heading", "Heading", "heading", default="Ready to Protect Your Investment?"),
    textarea("field_cs_cta_text", "Supporting Text (optional)", "text", rows=2),
    text("field_cs_cta_btn1_text", "Primary Button Text (optional)", "button_text", default="Shop Now"),
    text("field_cs_cta_btn1_url", "Primary Button URL (optional)", "button_url", default="/shop/"),
    text("field_cs_cta_btn2_text", "Secondary Button Text (optional)", "button2_text"),
    text("field_cs_cta_btn2_url", "Secondary Button URL (optional)", "button2_url"),
])

# 18. Contact Info & Form ------------------------------------------------------------
# Migrated from the fixed-field "Contact Page Settings" group
# (acf-json/group_contact_page.json), the only page-specific section that
# had not yet been rolled into the universal content_sections builder.
add("contact_info", "Contact Info & Form", [
    text("field_cs_contact_phone", "Phone Number", "phone", default="317-236-7701"),
    email_field("field_cs_contact_email", "Email Address", "email", default="info@doubletapprotect.com"),
    text("field_cs_contact_response_time", "Response Time", "response_time", default="Within 1 business day"),
    text("field_cs_contact_ig_handle", "Instagram Handle", "instagram_handle", placeholder="@handle"),
    url("field_cs_contact_ig_url", "Instagram URL", "instagram_url", placeholder="https://www.instagram.com/..."),
    text("field_cs_contact_dealer_heading", "Dealer Inquiry Heading", "dealer_heading", default="Retail & Dealer Inquiries"),
    textarea("field_cs_contact_dealer_text", "Dealer Inquiry Text", "dealer_text", rows=3),
    text("field_cs_contact_form_heading", "Form Heading", "form_heading", default="Send A Message"),
    number("field_cs_contact_gf_id", "Gravity Form ID", "gravity_form_id",
           instructions="Enter the numeric ID of the Gravity Form to display on this page.", minimum=1),
])

# 19. Feature Columns ----------------------------------------------------------
# Plain (non-card) column layout: image on top, then title, then body text,
# then an optional CTA button -- stacked vertically, no card background or
# absolute-positioned overlay text (unlike category_tiles, which remains
# unchanged/untouched above for existing usages such as the Home page).
add("feature_columns", "Feature Columns", [
    text("field_cs_fcol_heading", "Section Heading (optional)", "heading"),
    select("field_cs_fcol_bg", "Background", "section_background", BG_CHOICES, default="bg"),
    select("field_cs_fcol_grid_cols", "Columns Per Row", "grid_columns", {"2": "2", "3": "3", "4": "4"}, default="3",
           instructions="How many columns to display per row on desktop (always stacks to a single column on mobile)."),
    repeater("field_cs_fcol_columns", "Columns", "columns", [
        image("field_cs_fcol_image", "Image", "image"),
        text("field_cs_fcol_title", "Title", "title", required=1),
        textarea("field_cs_fcol_body", "Body Text", "body", rows=3),
        text("field_cs_fcol_cta_text", "CTA Button Text (optional)", "cta_text"),
        text("field_cs_fcol_cta_url", "CTA Button URL (optional)", "cta_url"),
        true_false("field_cs_fcol_full_link", "Make entire column clickable (optional)", "full_link",
                    instructions="If enabled, the whole column links to the CTA URL above (in addition to the visible button). If disabled, only the button itself is clickable."),
    ], button_label="Add Column", minimum=1, maximum=6),
])

# 20. Bullet List ---------------------------------------------------------------
# Lightweight, icon-free replacement for compatibility_grid when a page has
# too many items to reasonably source/maintain icons for. Renders as a
# plain multi-column bulleted list (no icons), matching a classic simple
# list layout. compatibility_grid remains unchanged/untouched above for
# existing usages (Firearms, Rifles, Scopes and Optics, Tactical Gear).
add("bullet_list", "Bullet List", [
    text("field_cs_blist_heading", "Section Heading (optional)", "heading"),
    select("field_cs_blist_cols", "Columns", "columns", {"2": "2", "3": "3", "4": "4"}, default="4"),
    select("field_cs_blist_bg", "Background", "section_background", BG_CHOICES, default="bg"),
    repeater("field_cs_blist_items", "Items", "items", [
        text("field_cs_blist_item_text", "Text", "text", required=1),
    ], button_label="Add Item", minimum=1, maximum=40),
])


group = {
    "key": "group_universal_content_sections",
    "title": "Page Content Sections",
    "fields": [
        {
            "key": "field_content_sections",
            "label": "Content Sections",
            "name": "content_sections",
            "aria-label": "",
            "type": "flexible_content",
            "instructions": "Add, remove, and reorder content sections. Every layout here is reusable on any page — this replaces page-specific/locked section fields and hardcoded page templates.",
            "required": 0,
            "conditional_logic": 0,
            "wrapper": WRAPPER,
            "layouts": layouts,
            "min": "",
            "max": "",
            "button_label": "Add Section",
        }
    ],
    "location": [
        [
            {"param": "post_type", "operator": "==", "value": "page"}
        ]
    ],
    "menu_order": 0,
    "position": "normal",
    "style": "default",
    "label_placement": "top",
    "instruction_placement": "label",
    "hide_on_screen": "",
    "active": True,
    "description": "Universal, reusable content-section builder available on every Page. Add any layout, in any order, on any page instead of being locked to a specific template.",
    "show_in_rest": 0,
    "display_title": "",
    "allow_ai_access": False,
    "ai_description": "",
    "modified": int(time.time()),
}

with open("acf-json/group_universal_content_sections.json", "w") as fh:
    json.dump(group, fh, indent=4)
    fh.write("\n")

print("Wrote acf-json/group_universal_content_sections.json with", len(layouts), "layouts")
