=== Billboard Pro ===
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

Premium WordPress theme for LED billboard screen and outdoor advertising companies.

== Installation ==

1. WordPress Admin → Appearance → Themes → Add New → Upload Theme → choose billboard-pro.zip → Install → Activate.
2. Appearance → Billboard Pro Setup → Import content (creates pages, menus, 23 guides, 50 market pages,
   billboard types, sample case studies/testimonials and sample inventory — 20 per market = 1,000 by default).
   The import runs in small batches so it will not time out on shared hosting.
3. Appearance → Customize → Billboard Pro: logo, brand colours, hero text/image/video, stats, phone, email,
   address, WhatsApp, social links, currency, GA4 ID.
4. Replace the sample billboards, prices, case studies and testimonials with your real ones
   (or remove them all in one click from Appearance → Billboard Pro Setup).
5. Install an SMTP plugin so form notifications are delivered reliably.

== Managing content (no code needed) ==

* Billboards — every field from the brief: ID, availability, gallery, address, state, country, ZIP,
  latitude/longitude, landmarks, parent location, digital/static, width/height/unit, size class, orientation,
  resolution, illumination, installation, viewing direction, ad slot, daily/monthly impressions, traffic count
  and level, demographics, visibility distance, viewing time, starting price and unit, campaign duration,
  minimum booking, CTA type and label, "feature on homepage". Cities and Billboard Types are taxonomies.
* Locations — city, state, address, coordinates, gallery, traffic, audience, landmarks. Related billboards
  appear automatically (by parent location and by matching city).
* Case Studies — client, industry, campaign, location, duration, format, objective, results
  ("+38% | Store visits" lines become big metrics), impressions, gallery, testimonial.
* Testimonials — name (title), quote (content), photo (featured image), company, position.
* Inquiries — every quote/contact submission is stored here with lead status, internal notes and CSV export,
  and emailed to the address set in the Customizer (falls back to the admin email).

== Filtering ==

The billboard archive filters by keyword, city, state, type, digital/static, size, availability, budget and
traffic level using real WP_Query tax/meta queries — with AJAX when JavaScript is on and normal page loads when
it is off. Filtered URLs are shareable and marked noindex to protect crawl budget.

== SEO ==

Meta descriptions, Open Graph, canonical-friendly breadcrumbs, JSON-LD (Organization, WebSite, LocalBusiness,
Product/Offer for billboards, Place, Article, FAQPage, BreadcrumbList), core XML sitemap, robots.txt rules,
/llms.txt for AI answer engines, one H1 per page, heading anchors and an automatic table of contents.
All of this steps aside automatically if Yoast SEO, Rank Math, SEOPress or AIOSEO is active.

== Developer hooks ==

* bbp_fields (filter) — add/modify custom fields per post type.
* bbp_form_fields (filter) — change form fields.
* bbp_is_spam (filter) — plug in reCAPTCHA / Turnstile / Akismet.
* bbp_inquiry_submitted (action) — send leads to a CRM, Slack or webhook.
* bbp_map_html (filter) — swap the OpenStreetMap embed for Google Maps / Mapbox.
* bbp_currency_code (filter) — schema currency (default USD).

== Credits ==

All bundled images are original artwork generated for this theme and released with it under the GPL —
free to use with no attribution. Fonts: Inter and Manrope (SIL Open Font License) via Google Fonts
(can be disabled in the Customizer to use system fonts).

Note: custom post types are registered by the theme, as requested. If you ever switch themes, move
inc/post-types.php, inc/meta-fields.php and inc/meta-boxes.php into a small plugin to keep the data editable.
