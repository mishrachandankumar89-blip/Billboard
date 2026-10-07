<?php
/**
 * Appearance → Billboard Pro Setup: batched one-click content import / removal.
 *
 * Creates: core pages, menus, LED billboard knowledge-hub guides, 50 market
 * (location) pages, billboard types, sample case studies & testimonials, and
 * sample billboard inventory (configurable, default 20 per market = 1,000).
 *
 * Everything imported is tagged with `_bbp_demo` so it can be removed in one click.
 *
 * @package Billboard_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load a demo data file.
 *
 * @param string $name File name without extension.
 * @return array
 */
function bbp_demo_data( $name ) {
	static $cache = array();
	if ( ! isset( $cache[ $name ] ) ) {
		$cache[ $name ] = require BBP_DIR . '/inc/demo/' . $name . '.php';
	}
	return $cache[ $name ];
}

/**
 * All guides from the three guide files.
 *
 * @return array
 */
function bbp_demo_guides() {
	return array_merge( bbp_demo_data( 'guides-technology' ), bbp_demo_data( 'guides-buying' ), bbp_demo_data( 'guides-planning' ) );
}

/**
 * Admin menu.
 */
function bbp_setup_menu() {
	add_theme_page( __( 'Billboard Pro Setup', 'billboard-pro' ), __( 'Billboard Pro Setup', 'billboard-pro' ), 'manage_options', 'bbp-setup', 'bbp_setup_page' );
}
add_action( 'admin_menu', 'bbp_setup_menu' );

/**
 * Nudge after activation.
 */
function bbp_setup_notice() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'bbp_demo_done' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'appearance_page_bbp-setup' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info is-dismissible"><p><strong>%1$s</strong> %2$s <a class="button button-primary" href="%3$s">%4$s</a></p></div>',
		esc_html__( 'Billboard Pro is active.', 'billboard-pro' ),
		esc_html__( 'Import the pages, guides, locations and sample inventory in one click.', 'billboard-pro' ),
		esc_url( admin_url( 'themes.php?page=bbp-setup' ) ),
		esc_html__( 'Open setup', 'billboard-pro' )
	);
}
add_action( 'admin_notices', 'bbp_setup_notice' );

/**
 * Reminder that sample inventory / testimonials must be replaced.
 */
function bbp_demo_reminder() {
	if ( ! current_user_can( 'manage_options' ) || ! get_option( 'bbp_demo_done' ) || get_option( 'bbp_demo_reminder_off' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'billboard', 'bb_testimonial', 'bb_case_study' ), true ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'Sample content: imported billboards, prices, impressions, case studies and testimonials are placeholders. Replace them with your real inventory and genuine client quotes (or remove them under Appearance → Billboard Pro Setup) before launch.', 'billboard-pro' ) . '</p></div>';
}
add_action( 'admin_notices', 'bbp_demo_reminder' );

/**
 * Setup page.
 */
function bbp_setup_page() {
	$cities = bbp_demo_data( 'cities' );
	$demo   = (int) get_option( 'bbp_demo_done' );
	?>
	<div class="wrap" id="bbp-setup">
		<h1><?php esc_html_e( 'Billboard Pro Setup', 'billboard-pro' ); ?></h1>
		<p style="max-width:760px;font-size:14px"><?php esc_html_e( 'One click builds a complete, internally-linked website: core pages, an LED billboard knowledge hub, market pages for 50 US cities, billboard types, sample case studies and testimonials, menus and a large sample inventory. Images are original artwork bundled with the theme — free to use, no attribution needed.', 'billboard-pro' ); ?></p>

		<div class="card" style="max-width:760px;padding:20px 24px">
			<h2 style="margin-top:0"><?php esc_html_e( 'Import website content', 'billboard-pro' ); ?></h2>
			<p>
				<label for="bbp-per-city"><strong><?php esc_html_e( 'Sample billboards per market', 'billboard-pro' ); ?></strong></label><br>
				<input type="number" id="bbp-per-city" min="0" max="30" value="20" style="width:90px">
				<span class="description">
					<?php
					/* translators: %d: number of cities */
					printf( esc_html__( '× %d markets. 20 creates 1,000 billboard pages (about 1,100+ URLs in total). Use 0–3 if you will add your real inventory yourself.', 'billboard-pro' ), count( $cities ) );
					?>
				</span>
			</p>
			<p><label><input type="checkbox" id="bbp-set-front" checked> <?php esc_html_e( 'Set the homepage, blog page, menus and pretty permalinks', 'billboard-pro' ); ?></label></p>
			<p class="description"><?php esc_html_e( 'Billboards, prices, impressions, case studies and testimonials are sample data for layout and SEO structure. Replace them with your real inventory and genuine client quotes before launch.', 'billboard-pro' ); ?></p>
			<p>
				<button class="button button-primary button-hero" id="bbp-import" <?php disabled( $demo ); ?>><?php esc_html_e( 'Import content', 'billboard-pro' ); ?></button>
				<?php if ( $demo ) : ?>
					<span style="margin-left:10px"><?php esc_html_e( 'Content already imported.', 'billboard-pro' ); ?></span>
				<?php endif; ?>
			</p>
			<div id="bbp-progress" style="display:none;margin-top:16px">
				<div style="height:10px;background:#e5e7eb;border-radius:5px;overflow:hidden"><div id="bbp-bar" style="height:100%;width:0;background:#ff5a1f;transition:width .3s"></div></div>
				<p id="bbp-status" style="margin:8px 0 0"></p>
			</div>
		</div>

		<div class="card" style="max-width:760px;padding:20px 24px">
			<h2 style="margin-top:0"><?php esc_html_e( 'Remove imported content', 'billboard-pro' ); ?></h2>
			<p><?php esc_html_e( 'Deletes everything the importer created (posts, pages, terms, menus and bundled images). Your own content is not touched.', 'billboard-pro' ); ?></p>
			<button class="button" id="bbp-remove"><?php esc_html_e( 'Remove imported content', 'billboard-pro' ); ?></button>
		</div>

		<div class="card" style="max-width:760px;padding:20px 24px">
			<h2 style="margin-top:0"><?php esc_html_e( 'Next steps', 'billboard-pro' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Appearance → Customize → Billboard Pro: logo, brand colors, hero, stats, phone, email, address.', 'billboard-pro' ); ?></li>
				<li><?php esc_html_e( 'Billboards → edit or add your real screens (photos, specs, prices, availability).', 'billboard-pro' ); ?></li>
				<li><?php esc_html_e( 'Testimonials / Case Studies → replace samples with real clients.', 'billboard-pro' ); ?></li>
				<li><?php esc_html_e( 'Settings → General: site title. Inquiries arrive under the Inquiries menu and by email.', 'billboard-pro' ); ?></li>
				<li><?php esc_html_e( 'Install an SMTP plugin so form emails are delivered reliably.', 'billboard-pro' ); ?></li>
			</ol>
		</div>
	</div>
	<script>
	(function(){
		var nonce = <?php echo wp_json_encode( wp_create_nonce( 'bbp_demo' ) ); ?>;
		var ajax = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
		function post(data){
			data.nonce = nonce;
			return fetch(ajax,{method:'POST',credentials:'same-origin',body:new URLSearchParams(data)}).then(function(r){return r.json();});
		}
		function run(action, extra){
			var bar=document.getElementById('bbp-bar'), st=document.getElementById('bbp-status');
			document.getElementById('bbp-progress').style.display='block';
			function step(i){
				var d = Object.assign({action:action, step:i}, extra||{});
				post(d).then(function(res){
					if(!res || !res.success){ st.textContent = 'Error: ' + ((res && res.data) || 'request failed') + ' — click again to resume.'; return; }
					bar.style.width = Math.round(res.data.progress*100)+'%';
					st.textContent = res.data.message;
					if(res.data.done){ st.innerHTML = res.data.message + ' <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">View site →</a>'; return; }
					step(res.data.next);
				}).catch(function(){ st.textContent='Network error — retrying…'; setTimeout(function(){step(i);},3000); });
			}
			step(0);
		}
		var b=document.getElementById('bbp-import');
		b && b.addEventListener('click',function(){
			b.disabled=true;
			run('bbp_demo_import',{per_city:document.getElementById('bbp-per-city').value,set_front:document.getElementById('bbp-set-front').checked?1:0});
		});
		document.getElementById('bbp-remove').addEventListener('click',function(){
			if(confirm('Remove all imported content? This cannot be undone.')){ this.disabled=true; run('bbp_demo_remove',{}); }
		});
	})();
	</script>
	<?php
}

/**
 * Guard for AJAX handlers.
 */
function bbp_demo_guard() {
	check_ajax_referer( 'bbp_demo', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'forbidden', 403 );
	}
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}
	wp_defer_term_counting( true );
	wp_suspend_cache_invalidation( true );
}

/**
 * Import step router.
 */
function bbp_ajax_demo_import() {
	bbp_demo_guard();
	$step     = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 0;
	$per_city = isset( $_POST['per_city'] ) ? min( 30, absint( $_POST['per_city'] ) ) : 20;
	$front    = ! empty( $_POST['set_front'] );
	$cities   = bbp_demo_data( 'cities' );
	$n_cities = count( $cities );
	// Steps: 0 setup, 1 guides, 2 pages, 3 locations, 4 cases/testimonials, 5..(5+n-1) billboards per city, last = finalize.
	$total = 5 + $n_cities + 1;

	if ( 0 === $step ) {
		update_option( 'bbp_demo_per_city', $per_city, false );
		bbp_demo_step_setup();
		$msg = __( 'Images and taxonomies ready…', 'billboard-pro' );
	} elseif ( 1 === $step ) {
		bbp_demo_step_guides();
		$msg = __( 'Knowledge hub guides created…', 'billboard-pro' );
	} elseif ( 2 === $step ) {
		bbp_demo_step_pages();
		$msg = __( 'Core pages created…', 'billboard-pro' );
	} elseif ( 3 === $step ) {
		bbp_demo_step_locations();
		$msg = __( 'Market pages created…', 'billboard-pro' );
	} elseif ( 4 === $step ) {
		bbp_demo_step_cases();
		$msg = __( 'Case studies and testimonials created…', 'billboard-pro' );
	} elseif ( $step < 5 + $n_cities ) {
		$idx  = $step - 5;
		$made = bbp_demo_step_billboards( $idx, (int) get_option( 'bbp_demo_per_city', 20 ) );
		/* translators: 1: count, 2: city */
		$msg = sprintf( __( 'Created %1$d billboards in %2$s…', 'billboard-pro' ), $made, $cities[ $idx ]['name'] );
	} else {
		bbp_demo_step_finalize( $front );
		update_option( 'bbp_demo_done', time() );
		wp_send_json_success(
			array(
				'done'     => true,
				'progress' => 1,
				'message'  => __( 'Done! Your website is ready.', 'billboard-pro' ),
			)
		);
	}
	wp_send_json_success(
		array(
			'done'     => false,
			'next'     => $step + 1,
			'progress' => ( $step + 1 ) / $total,
			'message'  => $msg,
		)
	);
}
add_action( 'wp_ajax_bbp_demo_import', 'bbp_ajax_demo_import' );

/**
 * Find a demo post by key (idempotent re-runs).
 *
 * @param string $key       Unique demo key.
 * @param string $post_type Post type.
 * @return int
 */
function bbp_demo_find( $key, $post_type ) {
	$ids = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_bbp_demo', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => $key, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	return $ids ? (int) $ids[0] : 0;
}

/**
 * Insert (or update) a demo post.
 *
 * @param string $key  Unique key.
 * @param array  $post Post array.
 * @param array  $meta Meta.
 * @return int
 */
function bbp_demo_insert( $key, $post, $meta = array() ) {
	$existing = bbp_demo_find( $key, $post['post_type'] );
	$post     = array_merge( array( 'post_status' => 'publish', 'post_author' => get_current_user_id() ), $post );
	if ( $existing ) {
		$post['ID'] = $existing;
		$id         = wp_update_post( wp_slash( $post ) );
	} else {
		$id = wp_insert_post( wp_slash( $post ) );
	}
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	update_post_meta( $id, '_bbp_demo', $key );
	foreach ( $meta as $k => $v ) {
		if ( '' === $v || null === $v ) {
			continue;
		}
		update_post_meta( $id, $k, $v );
	}
	return (int) $id;
}

/**
 * Step 0: images, taxonomies.
 */
function bbp_demo_step_setup() {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$images = (array) get_option( 'bbp_demo_images', array() );
	$alts   = array(
		'hero.jpg'              => __( 'LED billboard glowing above a city highway at dusk', 'billboard-pro' ),
		'hero-inner.jpg'        => __( 'Building-mounted LED advertising screen at night', 'billboard-pro' ),
		'hero-inventory.jpg'    => __( 'Roadside digital billboard above evening traffic', 'billboard-pro' ),
		'hero-city.jpg'         => __( 'City-center LED screen on a downtown building', 'billboard-pro' ),
		'hero-campaign.jpg'     => __( 'Transit station LED advertising screens', 'billboard-pro' ),
		'cta-bg.jpg'            => __( 'Large LED billboard facing highway traffic', 'billboard-pro' ),
		'led-closeup.jpg'       => __( 'Low-angle view of a large outdoor LED screen', 'billboard-pro' ),
		'billboard-1.jpg'       => __( 'Highway LED billboard at sunset', 'billboard-pro' ),
		'billboard-2.jpg'       => __( 'Building LED screen above a busy street', 'billboard-pro' ),
		'billboard-3.jpg'       => __( 'Portrait digital screen inside a shopping mall', 'billboard-pro' ),
		'billboard-4.jpg'       => __( 'Digital advertising screens in a transit station', 'billboard-pro' ),
		'billboard-5.jpg'       => __( 'Roadside billboard clearly visible in daylight', 'billboard-pro' ),
		'billboard-6.jpg'       => __( 'Portrait LED tower on a city building', 'billboard-pro' ),
		'billboard-7.jpg'       => __( 'Night-time digital billboard beside an urban freeway', 'billboard-pro' ),
		'billboard-8.jpg'       => __( 'Bright billboard beside a sunny highway', 'billboard-pro' ),
		'guide-pixel-pitch.jpg' => __( 'Diagram comparing LED pixel pitches P4, P6, P10 and P16', 'billboard-pro' ),
	);
	foreach ( $alts as $file => $alt ) {
		if ( ! empty( $images[ $file ] ) && get_post( $images[ $file ] ) ) {
			continue;
		}
		$src = BBP_DIR . '/assets/images/' . $file;
		if ( ! file_exists( $src ) ) {
			continue;
		}
		$tmp = wp_tempnam( $file );
		copy( $src, $tmp );
		$id = media_handle_sideload(
			array(
				'name'     => 'bbp-' . $file,
				'tmp_name' => $tmp,
			),
			0,
			$alt
		);
		if ( is_wp_error( $id ) ) {
			@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors, WordPress.WP.AlternativeFunctions
			continue;
		}
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		update_post_meta( $id, '_bbp_demo', 'img-' . $file );
		$images[ $file ] = $id;
	}
	update_option( 'bbp_demo_images', $images, false );

	// Billboard types with intro copy (shown on each type archive).
	foreach ( bbp_demo_data( 'types' ) as $slug => $t ) {
		$term = term_exists( $slug, 'billboard_type' );
		if ( ! $term ) {
			$term = wp_insert_term( $t['name'], 'billboard_type', array( 'slug' => $slug, 'description' => $t['description'] ) );
		} else {
			wp_update_term( (int) $term['term_id'], 'billboard_type', array( 'description' => $t['description'] ) );
		}
		if ( ! is_wp_error( $term ) ) {
			update_term_meta( (int) $term['term_id'], '_bbp_demo', 1 );
		}
	}

	// Cities.
	foreach ( bbp_demo_data( 'cities' ) as $c ) {
		$slug = sanitize_title( $c['name'] );
		$term = term_exists( $slug, 'billboard_city' );
		$desc = sprintf(
			/* translators: 1: city, 2: state, 3: corridors */
			__( '<p>Compare LED billboard screens and outdoor advertising sites in %1$s, %2$s — on corridors such as %3$s. Every listing shows format, size, estimated impressions, starting price and live availability.</p>', 'billboard-pro' ),
			$c['name'],
			$c['state_name'],
			implode( ', ', array_slice( $c['corridors'], 0, 3 ) )
		);
		if ( ! $term ) {
			$term = wp_insert_term( $c['name'], 'billboard_city', array( 'slug' => $slug, 'description' => $desc ) );
		}
		if ( ! is_wp_error( $term ) ) {
			update_term_meta( (int) $term['term_id'], '_bbp_demo', 1 );
		}
	}
}

/**
 * Attachment ID for a bundled image.
 *
 * @param string $file File name.
 * @return int
 */
function bbp_demo_img( $file ) {
	$images = (array) get_option( 'bbp_demo_images', array() );
	return isset( $images[ $file ] ) ? (int) $images[ $file ] : 0;
}

/**
 * Step 1: guides (posts) + categories.
 */
function bbp_demo_step_guides() {
	$order = 0;
	foreach ( bbp_demo_guides() as $g ) {
		$cat = term_exists( $g['category'], 'category' );
		if ( ! $cat ) {
			$cat = wp_insert_term( $g['category'], 'category' );
		}
		$cat_id = is_wp_error( $cat ) ? 0 : (int) $cat['term_id'];
		if ( $cat_id ) {
			update_term_meta( $cat_id, '_bbp_demo', 1 );
		}
		$id = bbp_demo_insert(
			'guide-' . $g['slug'],
			array(
				'post_type'     => 'post',
				'post_title'    => $g['title'],
				'post_name'     => $g['slug'],
				'post_excerpt'  => $g['excerpt'],
				'post_content'  => $g['content'],
				'post_category' => $cat_id ? array( $cat_id ) : array(),
				'post_date'     => gmdate( 'Y-m-d H:i:s', time() - ( $order++ * DAY_IN_SECONDS * 3 ) ),
			)
		);
		if ( $id ) {
			$img = bbp_demo_img( isset( $g['image'] ) ? $g['image'] : 'billboard-' . ( ( $order % 8 ) + 1 ) . '.jpg' );
			if ( $img ) {
				set_post_thumbnail( $id, $img );
			}
			if ( ! empty( $g['tags'] ) ) {
				wp_set_post_tags( $id, $g['tags'] );
			}
		}
	}
	// Remove the default "Uncategorized" from imported posts list display by giving it a nicer name if empty.
}

/**
 * Step 2: pages.
 */
function bbp_demo_step_pages() {
	foreach ( bbp_demo_data( 'pages' ) as $key => $p ) {
		$id = bbp_demo_insert(
			'page-' . $key,
			array(
				'post_type'    => 'page',
				'post_title'   => $p['title'],
				'post_name'    => $p['slug'],
				'post_excerpt' => isset( $p['excerpt'] ) ? $p['excerpt'] : '',
				'post_content' => isset( $p['content'] ) ? $p['content'] : '',
				'menu_order'   => isset( $p['order'] ) ? $p['order'] : 0,
			)
		);
		if ( $id && ! empty( $p['template'] ) ) {
			update_post_meta( $id, '_wp_page_template', $p['template'] );
		}
		if ( $id && ! empty( $p['image'] ) && bbp_demo_img( $p['image'] ) ) {
			set_post_thumbnail( $id, bbp_demo_img( $p['image'] ) );
		}
		if ( 'quote' === $key ) {
			set_theme_mod( 'bbp_page_quote', $id );
		}
		if ( 'contact' === $key ) {
			set_theme_mod( 'bbp_page_contact', $id );
		}
	}
}

/**
 * Step 3: locations (market pages).
 */
function bbp_demo_step_locations() {
	$cities = bbp_demo_data( 'cities' );
	$imgs   = array( 'hero-city.jpg', 'hero-inner.jpg', 'billboard-2.jpg', 'billboard-6.jpg', 'hero.jpg', 'billboard-7.jpg', 'billboard-1.jpg', 'cta-bg.jpg' );
	foreach ( $cities as $i => $c ) {
		$id = bbp_demo_insert(
			'loc-' . sanitize_title( $c['name'] ),
			array(
				'post_type'    => 'bb_location',
				'post_title'   => $c['name'],
				'post_name'    => sanitize_title( $c['name'] ),
				'post_excerpt' => sprintf(
					/* translators: 1: city, 2: state */
					__( 'LED billboard screens and outdoor advertising locations in %1$s, %2$s — highway, city-center, mall and transit formats.', 'billboard-pro' ),
					$c['name'],
					$c['state_name']
				),
				'post_content' => bbp_demo_location_content( $c, $i ),
				'menu_order'   => $i,
			),
			array(
				'loc_city'      => $c['name'],
				'loc_state'     => $c['state_name'],
				'loc_address'   => implode( ' · ', array_slice( $c['landmarks'], 0, 2 ) ),
				'loc_lat'       => (string) $c['lat'],
				'loc_lng'       => (string) $c['lng'],
				'loc_traffic'   => sprintf(
					/* translators: %s: corridors */
					__( 'Key advertising corridors: %s. Commuter peaks typically run on weekday mornings and late afternoons, with heavier leisure traffic on weekend evenings near entertainment districts.', 'billboard-pro' ),
					implode( ', ', $c['corridors'] )
				),
				'loc_audience'  => __( 'Daily commuters, business travellers, shoppers and visitors. Highway sites deliver broad reach; city-center, mall and transit screens reach dense pedestrian audiences with longer dwell time.', 'billboard-pro' ),
				'loc_landmarks' => implode( "\n", $c['landmarks'] ),
			)
		);
		if ( $id && bbp_demo_img( $imgs[ $i % count( $imgs ) ] ) ) {
			set_post_thumbnail( $id, bbp_demo_img( $imgs[ $i % count( $imgs ) ] ) );
		}
	}
}

/**
 * Pick from a list deterministically.
 *
 * @param array $list List.
 * @param int   $n    Seed.
 * @return mixed
 */
function bbp_pick( $list, $n ) {
	return $list[ abs( $n ) % count( $list ) ];
}

/**
 * Market page body copy. Built from real corridor and landmark names, varied per city,
 * and cross-linked to guides, the city inventory, neighbouring markets and the quote form.
 *
 * @param array $c City.
 * @param int   $i Index.
 * @return string
 */
function bbp_demo_location_content( $c, $i ) {
	$name   = $c['name'];
	$state  = $c['state_name'];
	$cor    = $c['corridors'];
	$lm     = $c['landmarks'];
	$slug   = sanitize_title( $name );
	$intros = array(
		"<p class=\"answer-box\"><strong>Advertising on billboards in {$name}</strong> means choosing between high-reach highway LED screens on corridors like {$cor[0]} and {$cor[1]}, and city-center placements around {$lm[0]} and {$lm[1]}. Below you will find every format we offer in {$name}, how buyers typically combine them, and how to get availability and pricing.</p>",
		"<p class=\"answer-box\"><strong>{$name} billboard advertising</strong> works best when you match the format to the journey: LED billboards on {$cor[0]} for commuter reach, screens near {$lm[0]} for dense, high-dwell audiences, and static bulletins for long-term presence. This guide explains your options in {$name}, {$state}.</p>",
		"<p class=\"answer-box\">Looking for <strong>LED billboards in {$name}</strong>? Our {$name} network covers major routes such as {$cor[0]}, {$cor[1]} and {$cor[2]}, plus high-visibility placements near {$lm[0]}. Every site listed here shows size, format, estimated impressions and a starting price.</p>",
	);
	$html  = bbp_pick( $intros, $i );
	$html .= "<h2>Why advertise on billboards in {$name}?</h2>";
	$html .= bbp_pick(
		array(
			"<p>{$name} audiences spend real time on the road and on foot. Commuters on {$cor[0]} and {$cor[1]} pass the same screens day after day, which is exactly the repetition that builds recall. Around {$lm[0]} and {$lm[1]}, foot traffic and slower vehicle speeds give creative more time to land. Outdoor is also one of the few channels people cannot skip, block or scroll past.</p>",
			"<p>Outdoor advertising in {$name} reaches people in the moments between home, work and leisure. Highway placements on {$cor[0]} deliver scale, while screens near {$lm[0]} put your brand in front of shoppers, visitors and event crowds. Because a billboard is seen repeatedly by the same commuters, frequency builds naturally without additional cost per view.</p>",
			"<p>Brands choose {$name} billboards for three reasons: reach on arterial routes like {$cor[0]}, relevance near destinations such as {$lm[0]}, and flexibility on LED screens that can change creative in minutes. Whether you are launching a product, opening a location or building awareness across {$state}, outdoor gives you a physical, unmissable presence.</p>",
		),
		$i + 1
	);
	$html .= "<h2>Billboard formats available in {$name}</h2>";
	$html .= "<ul>
<li><strong>[[type:led-highway-billboard|LED highway billboards]]</strong> — large digital faces on routes such as {$cor[0]} and {$cor[1]}, typically sold as a share of a rotating loop.</li>
<li><strong>[[type:led-spectacular|City-center LED spectaculars]]</strong> — landmark screens near {$lm[0]} for launches and high-impact moments.</li>
<li><strong>[[type:building-led-screen|Building-mounted LED screens]]</strong> — wall and façade screens that dominate a street or intersection.</li>
<li><strong>[[type:mall-screen|Shopping mall screens]]</strong> and <strong>[[type:transit-screen|transit screens]]</strong> — close-range digital formats that reach shoppers and commuters with longer dwell time.</li>
<li><strong>[[type:static-bulletin|Static bulletins]]</strong> and <strong>[[type:static-poster|posters]]</strong> — exclusive 24/7 presence for longer campaigns.</li>
</ul>";
	$html .= "<p>Not sure which format fits? Our guide to [[g:digital-vs-static-billboards|digital vs static billboards]] walks through the trade-offs, and [[g:how-to-choose-billboard-location|how to choose a billboard location]] explains how planners shortlist sites.</p>";
	$html .= "<h2>Key advertising corridors in {$name}</h2><p>These are the routes and districts where outdoor inventory in {$name} is most in demand:</p><ul>";
	foreach ( $cor as $k => $r ) {
		$html .= '<li><strong>' . esc_html( $r ) . '</strong> — ' . esc_html(
			bbp_pick(
				array(
					'heavy weekday commuter volumes and repeated exposure for the same drivers.',
					'a major arterial route with strong reach across the metro area.',
					'consistent all-day traffic, ideal for LED rotation and dayparted creative.',
					'a high-visibility approach used by both commuters and visitors.',
				),
				$i + $k
			)
		) . '</li>';
	}
	$html .= '</ul>';
	$html .= "<h2>How much does billboard advertising cost in {$name}?</h2>";
	$html .= "<p>Pricing in {$name} depends on format, audience size, campaign length and season. LED screens are usually sold as a share of a loop (for example one 8-second spot in a rotation of six to eight advertisers), while static faces are sold exclusively for four-week periods. Each listing on this site shows a starting price; for a full breakdown read [[g:how-much-does-billboard-advertising-cost|how much billboard advertising costs]], or [[quote|request a {$name} proposal]] with your dates and budget.</p>";
	$html .= "<h2>Planning tips for {$name} campaigns</h2><ol>
<li><strong>Start with the audience, not the board.</strong> Decide who you need to reach — commuters, shoppers, visitors — then pick corridors and districts that match.</li>
<li><strong>Combine reach and proximity.</strong> Pair a highway LED billboard on {$cor[0]} with a screen near {$lm[0]} or your store to connect awareness with action.</li>
<li><strong>Use dayparting on LED.</strong> Run morning and evening creatives on the same screen — see our guide to [[g:dooh-scheduling-dayparting|dayparting and scheduling]].</li>
<li><strong>Design for a glance.</strong> Keep copy short and contrast high; our [[g:billboard-design-best-practices|billboard design guide]] explains why.</li>
</ol>";
	$html .= "<h2>Frequently asked questions</h2>";
	$html .= "<h3>How do I book a billboard in {$name}?</h3><p>Browse the {$name} listings below, open any site for specifications and pricing, then use the quote form. A campaign planner confirms availability and sends a proposal within one business day.</p>";
	$html .= "<h3>What is the minimum booking for a {$name} LED billboard?</h3><p>Most LED screens in {$name} can be booked from one or two weeks; static faces are usually sold in four-week periods. Exact minimums are shown on each listing.</p>";
	$html .= "<h3>Can I change my creative during the campaign?</h3><p>Yes on LED screens — creative can be updated remotely, often the same day, after approval. Static faces require a new print and installation.</p>";
	$html .= "<p>Explore all [[city:{$slug}|billboards in {$name}]], browse [[locs|other markets]], or [[contact|talk to our team]].</p>";
	return $html;
}

/**
 * Step 4: case studies + testimonials.
 */
function bbp_demo_step_cases() {
	$imgs = array( 'billboard-1.jpg', 'billboard-2.jpg', 'billboard-3.jpg', 'billboard-4.jpg', 'billboard-6.jpg', 'billboard-7.jpg', 'hero-campaign.jpg', 'billboard-5.jpg' );
	foreach ( bbp_demo_data( 'case-studies' ) as $i => $cs ) {
		$id = bbp_demo_insert(
			'cs-' . $i,
			array(
				'post_type'    => 'bb_case_study',
				'post_title'   => $cs['title'],
				'post_excerpt' => $cs['excerpt'],
				'post_content' => $cs['content'],
				'menu_order'   => $i,
			),
			$cs['meta']
		);
		if ( $id && bbp_demo_img( $imgs[ $i % count( $imgs ) ] ) ) {
			set_post_thumbnail( $id, bbp_demo_img( $imgs[ $i % count( $imgs ) ] ) );
			update_post_meta( $id, 'cs_gallery', implode( ',', array_filter( array( bbp_demo_img( $imgs[ ( $i + 1 ) % 8 ] ), bbp_demo_img( $imgs[ ( $i + 2 ) % 8 ] ) ) ) ) );
		}
	}
	foreach ( bbp_demo_data( 'testimonials' ) as $i => $t ) {
		bbp_demo_insert(
			't-' . $i,
			array(
				'post_type'    => 'bb_testimonial',
				'post_title'   => $t['name'],
				'post_content' => $t['quote'],
				'menu_order'   => $i,
			),
			array(
				't_company'  => $t['company'],
				't_position' => $t['position'],
			)
		);
	}
}

/**
 * Billboard archetypes by type.
 *
 * @return array
 */
function bbp_demo_archetypes() {
	return array(
		'led-highway-billboard' => array( 'format' => 'digital', 'w' => 48, 'h' => 14, 'unit' => 'ft', 'res' => '1400 × 400 px', 'slot' => '8-second spot, loop of 8', 'illum' => 'High-brightness LED, auto-dimming', 'inst' => 'Monopole', 'daily' => array( 45000, 160000 ), 'price' => array( 2400, 9500 ), 'punit' => '4weeks', 'min' => '1 week', 'dur' => '4–12 weeks', 'traffic' => array( 'high', 'very-high' ), 'vis' => array( '300 m', '400 m', '500 m' ), 'vt' => array( '6 seconds', '8 seconds', '10 seconds' ), 'imgs' => array( 'billboard-1.jpg', 'billboard-7.jpg', 'hero.jpg', 'cta-bg.jpg' ), 'label' => 'LED Billboard' ),
		'led-spectacular'       => array( 'format' => 'digital', 'w' => 60, 'h' => 30, 'unit' => 'ft', 'res' => '1920 × 960 px', 'slot' => '10-second spot, loop of 6', 'illum' => 'High-brightness LED, auto-dimming', 'inst' => 'Building façade', 'daily' => array( 150000, 420000 ), 'price' => array( 9000, 32000 ), 'punit' => 'week', 'min' => '1 week', 'dur' => '1–4 weeks', 'traffic' => array( 'very-high' ), 'vis' => array( '250 m', '350 m' ), 'vt' => array( '12 seconds', '15 seconds' ), 'imgs' => array( 'hero-city.jpg', 'billboard-2.jpg', 'hero-inner.jpg' ), 'label' => 'LED Spectacular' ),
		'building-led-screen'   => array( 'format' => 'digital', 'w' => 32, 'h' => 18, 'unit' => 'ft', 'res' => '1280 × 720 px', 'slot' => '10-second spot, loop of 6', 'illum' => 'High-brightness LED, auto-dimming', 'inst' => 'Wall-mounted', 'daily' => array( 60000, 180000 ), 'price' => array( 3500, 12000 ), 'punit' => '4weeks', 'min' => '2 weeks', 'dur' => '4–8 weeks', 'traffic' => array( 'high', 'very-high' ), 'vis' => array( '150 m', '200 m', '250 m' ), 'vt' => array( '10 seconds', '12 seconds' ), 'imgs' => array( 'billboard-2.jpg', 'billboard-6.jpg', 'hero-inner.jpg' ), 'label' => 'Building LED Screen' ),
		'mall-screen'           => array( 'format' => 'digital', 'w' => 3.6, 'h' => 6.4, 'unit' => 'ft', 'res' => '1080 × 1920 px', 'slot' => '10-second spot, loop of 8', 'illum' => 'Indoor LED / LCD', 'inst' => 'Freestanding', 'daily' => array( 8000, 35000 ), 'price' => array( 450, 1800 ), 'punit' => 'week', 'min' => '1 week', 'dur' => '2–8 weeks', 'traffic' => array( 'medium', 'high' ), 'vis' => array( '20 m', '30 m' ), 'vt' => array( '15 seconds', '20 seconds' ), 'imgs' => array( 'billboard-3.jpg' ), 'label' => 'Mall Screen', 'orient' => 'portrait' ),
		'transit-screen'        => array( 'format' => 'digital', 'w' => 10, 'h' => 5.6, 'unit' => 'ft', 'res' => '1920 × 1080 px', 'slot' => '10-second spot, loop of 6', 'illum' => 'LED', 'inst' => 'Wall-mounted', 'daily' => array( 20000, 90000 ), 'price' => array( 900, 4200 ), 'punit' => '4weeks', 'min' => '2 weeks', 'dur' => '4–12 weeks', 'traffic' => array( 'high', 'very-high' ), 'vis' => array( '30 m', '50 m' ), 'vt' => array( '20 seconds', '30 seconds' ), 'imgs' => array( 'billboard-4.jpg', 'hero-campaign.jpg' ), 'label' => 'Transit Screen' ),
		'static-bulletin'       => array( 'format' => 'static', 'w' => 48, 'h' => 14, 'unit' => 'ft', 'res' => '', 'slot' => '', 'illum' => 'LED floodlights', 'inst' => 'Monopole', 'daily' => array( 30000, 120000 ), 'price' => array( 1800, 7000 ), 'punit' => '4weeks', 'min' => '4 weeks', 'dur' => '12–52 weeks', 'traffic' => array( 'medium', 'high', 'very-high' ), 'vis' => array( '300 m', '400 m' ), 'vt' => array( '6 seconds', '8 seconds' ), 'imgs' => array( 'billboard-5.jpg', 'billboard-8.jpg' ), 'label' => 'Bulletin' ),
		'static-poster'         => array( 'format' => 'static', 'w' => 22.8, 'h' => 10.5, 'unit' => 'ft', 'res' => '', 'slot' => '', 'illum' => 'Front-lit', 'inst' => 'Single pole', 'daily' => array( 12000, 45000 ), 'price' => array( 600, 2200 ), 'punit' => '4weeks', 'min' => '4 weeks', 'dur' => '8–24 weeks', 'traffic' => array( 'medium', 'high' ), 'vis' => array( '150 m', '200 m' ), 'vt' => array( '5 seconds', '6 seconds' ), 'imgs' => array( 'billboard-8.jpg', 'billboard-5.jpg' ), 'label' => 'Poster' ),
		'rooftop-billboard'     => array( 'format' => 'static', 'w' => 60, 'h' => 20, 'unit' => 'ft', 'res' => '', 'slot' => '', 'illum' => 'LED floodlights', 'inst' => 'Rooftop', 'daily' => array( 70000, 220000 ), 'price' => array( 5000, 16000 ), 'punit' => '4weeks', 'min' => '4 weeks', 'dur' => '12–52 weeks', 'traffic' => array( 'high', 'very-high' ), 'vis' => array( '400 m', '600 m' ), 'vt' => array( '8 seconds', '10 seconds' ), 'imgs' => array( 'billboard-7.jpg', 'cta-bg.jpg' ), 'label' => 'Rooftop Billboard' ),
	);
}

/**
 * Step 5+: billboards for one city.
 *
 * @param int $idx      City index.
 * @param int $per_city Count.
 * @return int Created.
 */
function bbp_demo_step_billboards( $idx, $per_city ) {
	$cities = bbp_demo_data( 'cities' );
	if ( ! isset( $cities[ $idx ] ) || $per_city < 1 ) {
		return 0;
	}
	$c      = $cities[ $idx ];
	$cslug  = sanitize_title( $c['name'] );
	$loc_id = bbp_demo_find( 'loc-' . $cslug, 'bb_location' );
	$arch   = bbp_demo_archetypes();
	// Type mix weighted toward LED (the focus of the business).
	$mix  = array( 'led-highway-billboard', 'led-highway-billboard', 'led-highway-billboard', 'building-led-screen', 'led-spectacular', 'transit-screen', 'mall-screen', 'led-highway-billboard', 'static-bulletin', 'building-led-screen', 'static-poster', 'rooftop-billboard', 'transit-screen', 'led-highway-billboard', 'mall-screen', 'static-bulletin', 'led-spectacular', 'building-led-screen', 'led-highway-billboard', 'static-bulletin' );
	$dirs = array( 'Northbound', 'Southbound', 'Eastbound', 'Westbound' );
	$avail = array( 'available', 'available', 'available', 'available', 'available', 'available', 'reserved', 'reserved', 'booked', 'booked', 'available', 'maintenance', 'available', 'unavailable', 'available', 'booked', 'available', 'reserved', 'available', 'available' );
	$made = 0;
	$seed = crc32( $c['name'] );

	for ( $n = 0; $n < $per_city; $n++ ) {
		$type  = $mix[ ( $n + $idx ) % count( $mix ) ];
		$a     = $arch[ $type ];
		$r     = ( $seed >> ( $n % 16 ) ) + $n * 7919;
		$code  = strtoupper( substr( preg_replace( '/[^a-z]/i', '', $c['name'] ), 0, 3 ) ) . '-' . str_pad( (string) ( $n + 1 ), 3, '0', STR_PAD_LEFT );
		$is_road = in_array( $type, array( 'led-highway-billboard', 'static-bulletin', 'static-poster', 'rooftop-billboard' ), true );
		$place = $is_road ? bbp_pick( $c['corridors'], $n ) : bbp_pick( $c['landmarks'], $n );
		$dir   = bbp_pick( $dirs, $r );
		$title = $is_road ? sprintf( '%s %s — %s', $place, $a['label'], $dir ) : sprintf( '%s %s', $place, $a['label'] );
		if ( $n >= count( $c['corridors'] ) * 2 && $is_road ) {
			$title .= ' ' . bbp_pick( array( 'II', 'III', 'IV' ), $n );
		}
		if ( ! $is_road && $n >= count( $c['landmarks'] ) ) {
			$title .= ' ' . bbp_pick( array( 'East', 'West', 'North', 'South', 'Central' ), $n );
		}
		$daily  = (int) ( round( ( $a['daily'][0] + ( $r % 1000 ) / 1000 * ( $a['daily'][1] - $a['daily'][0] ) ) / 500 ) * 500 );
		$price  = (int) ( round( ( $a['price'][0] + ( ( $r >> 3 ) % 1000 ) / 1000 * ( $a['price'][1] - $a['price'][0] ) ) / 50 ) * 50 );
		$status = $avail[ ( $n + $idx * 3 ) % count( $avail ) ];
		$imgs   = $a['imgs'];
		$thumb  = bbp_demo_img( bbp_pick( $imgs, $n + $idx ) );
		$gallery = array();
		foreach ( array_merge( $imgs, array( 'led-closeup.jpg', 'billboard-5.jpg', 'hero.jpg' ) ) as $gi ) {
			$gid = bbp_demo_img( $gi );
			if ( $gid && $gid !== $thumb ) {
				$gallery[] = $gid;
			}
		}
		$gallery = array_slice( array_unique( $gallery ), 0, 4 );

		$meta = array(
			'bb_code'              => $code,
			'bb_availability'      => $status,
			'bb_available_from'    => 'available' === $status ? '' : gmdate( 'Y-m-d', time() + ( ( $r % 9 ) + 2 ) * WEEK_IN_SECONDS ),
			'bb_featured'          => ( $idx < 6 && 0 === $n ) ? '1' : '',
			'bb_gallery'           => implode( ',', $gallery ),
			'bb_address'           => $is_road ? $place . ', ' . strtolower( $dir ) . ' side' : 'Near ' . $place,
			'bb_state'             => $c['state_name'],
			'bb_country'           => 'United States',
			'bb_lat'               => (string) round( $c['lat'] + ( ( $r % 200 ) - 100 ) / 2500, 5 ),
			'bb_lng'               => (string) round( $c['lng'] + ( ( ( $r >> 2 ) % 200 ) - 100 ) / 2500, 5 ),
			'bb_landmark'          => implode( "\n", array_slice( array_merge( array( $place ), $c['landmarks'] ), 0, 3 ) ),
			'bb_location_id'       => $loc_id ? (string) $loc_id : '',
			'bb_format'            => $a['format'],
			'bb_width'             => (string) $a['w'],
			'bb_height'            => (string) $a['h'],
			'bb_unit'              => $a['unit'],
			'bb_orientation'       => isset( $a['orient'] ) ? $a['orient'] : 'landscape',
			'bb_resolution'        => $a['res'],
			'bb_illumination'      => $a['illum'],
			'bb_installation'      => $a['inst'],
			'bb_viewing'           => $is_road ? $dir . ' traffic' : bbp_pick( array( 'Pedestrian concourse', 'Street level, two-way', 'Main entrance approach', 'Intersection, all directions' ), $n ),
			'bb_slot_length'       => $a['slot'],
			'bb_daily_impressions' => (string) $daily,
			'bb_traffic_count'     => (string) (int) ( $daily * 0.62 ),
			'bb_traffic_level'     => bbp_pick( $a['traffic'], $r ),
			'bb_demographics'      => bbp_pick(
				array(
					'Adults 25–54, weekday commuters, high share of professional and managerial workers.',
					'Broad metro audience; strong weekend leisure and shopping traffic.',
					'Visitors, event-goers and downtown workers; strong evening and weekend audience.',
					'Families and shoppers; peak traffic late afternoons and weekends.',
				),
				$r
			),
			'bb_visibility'        => bbp_pick( $a['vis'], $r ),
			'bb_view_time'         => bbp_pick( $a['vt'], $r >> 1 ),
			'bb_price'             => (string) $price,
			'bb_price_unit'        => $a['punit'],
			'bb_campaign_duration' => $a['dur'],
			'bb_min_booking'       => $a['min'],
			'bb_cta'               => 'quote',
		);

		$content = bbp_demo_billboard_content( $c, $type, $a, $place, $dir, $daily, $n );
		$id      = bbp_demo_insert(
			'bb-' . $cslug . '-' . $n,
			array(
				'post_type'    => 'billboard',
				'post_title'   => $title,
				'post_name'    => sanitize_title( $title . '-' . $c['name'] ),
				'post_excerpt' => sprintf(
					/* translators: 1: type label, 2: place, 3: city, 4: dims, 5: impressions */
					__( '%1$s at %2$s, %3$s — %4$s with an estimated %5$s daily impressions. Check availability and request a quote.', 'billboard-pro' ),
					$a['label'],
					$place,
					$c['name'],
					$a['w'] . ' × ' . $a['h'] . ' ' . $a['unit'],
					number_format_i18n( $daily )
				),
				'post_content' => $content,
				'menu_order'   => $n,
			),
			$meta
		);
		if ( ! $id ) {
			continue;
		}
		if ( $thumb ) {
			set_post_thumbnail( $id, $thumb );
		}
		wp_set_object_terms( $id, $cslug, 'billboard_city' );
		wp_set_object_terms( $id, $type, 'billboard_type' );
		bbp_sync_billboard_derived( $id );
		$made++;
	}
	return $made;
}

/**
 * Billboard description copy.
 *
 * @param array  $c     City.
 * @param string $type  Type slug.
 * @param array  $a     Archetype.
 * @param string $place Place.
 * @param string $dir   Direction.
 * @param int    $daily Daily impressions.
 * @param int    $n     Index.
 * @return string
 */
function bbp_demo_billboard_content( $c, $type, $a, $place, $dir, $daily, $n ) {
	$city    = $c['name'];
	$cslug   = sanitize_title( $city );
	$daily_f = number_format( $daily );
	$lm      = bbp_pick( $c['landmarks'], $n + 1 );
	$digital = 'digital' === $a['format'];
	$open    = array(
		'led-highway-billboard' => array(
			"<p>This LED billboard faces {$dir} traffic on {$place}, one of the busiest routes in {$city}. Drivers get a clear, extended approach to the screen, and high-brightness LED keeps your creative readable from early morning sun to late evening. With an estimated {$daily_f} daily impressions, it is a strong anchor for metro-wide awareness.</p>",
			"<p>Positioned on {$place} for {$dir} drivers, this digital billboard delivers repeated exposure to the same commuters every weekday — the frequency that builds recall. The LED face is engineered for daylight visibility and dims automatically at night, so your message looks right around the clock.</p>",
		),
		'led-spectacular'       => array(
			"<p>A landmark LED spectacular near {$place} in {$city}. Large-format, high-resolution and seen by dense crowds of pedestrians and drivers, it is the screen brands choose for launches, announcements and moments that need to feel big. Estimated daily impressions: {$daily_f}.</p>",
		),
		'building-led-screen'   => array(
			"<p>This building-mounted LED screen near {$place} commands the street below, reaching drivers, pedestrians and office workers throughout the day. Bright, wide-angle LED keeps colours true from every approach — ideal for bold brand creative and timely, dayparted messages.</p>",
		),
		'mall-screen'           => array(
			"<p>A portrait digital screen at {$place}, placed on a main shopper flow where people are already in a buying mindset. Close-range viewing and long dwell time make it ideal for product-led creative, promotions and store-direction messages.</p>",
		),
		'transit-screen'        => array(
			"<p>A digital transit screen at {$place} reaching commuters while they wait and walk — a captive audience with long dwell time. Run multiple creatives in rotation and switch by time of day to match morning and evening journeys.</p>",
		),
		'static-bulletin'       => array(
			"<p>A classic bulletin on {$place} facing {$dir} traffic. You own the face exclusively, 24/7, for the full booking — the most dependable way to build long-term presence on a major {$city} route. Floodlit for night-time visibility.</p>",
		),
		'static-poster'         => array(
			"<p>A poster panel on {$place}, closer to street level and well suited to neighbourhood targeting, store openings and multi-panel showings that build reach across {$city} at an efficient cost.</p>",
		),
		'rooftop-billboard'     => array(
			"<p>A rooftop billboard above {$place}, visible from long distances on the approach to the {$city} core. Exclusive and floodlit, it gives your brand a permanent-feeling skyline presence.</p>",
		),
	);
	$html  = bbp_pick( $open[ $type ], $n );
	$html .= '<h2>Who sees this billboard</h2>';
	$html .= "<p>The audience mixes daily commuters with visitors heading to destinations such as {$lm}. Impression estimates are modelled from traffic volumes, visibility distance and typical viewing time; detailed audience reports are available with your proposal.</p>";
	if ( $digital ) {
		$html .= '<h2>Booking this LED screen</h2>';
		$html .= "<p>Digital campaigns are sold as a share of the screen's rotation ({$a['slot']}). You can upload more than one creative, schedule by daypart and update artwork remotely after approval. Supply files at {$a['res']} — see our [[g:led-billboard-content-specifications|LED creative specifications]] and the guide to [[g:dooh-scheduling-dayparting|dayparting]].</p>";
	} else {
		$html .= '<h2>Booking this billboard</h2>';
		$html .= "<p>Static faces are booked exclusively in four-week periods with printing and installation managed for you. Longer bookings secure the face and usually the best rate — compare formats in [[g:digital-vs-static-billboards|digital vs static billboards]].</p>";
	}
	$html .= "<p>More options: [[city:{$cslug}|all billboards in {$city}]] · [[type:{$type}|more {$a['label']} sites]] · [[loc:{$cslug}|{$city} market guide]] · [[quote|request a quote]].</p>";
	return $html;
}

/**
 * Final step: resolve internal links, menus, reading settings.
 *
 * @param bool $front Set front page/menus/permalinks.
 */
function bbp_demo_step_finalize( $front ) {
	wp_defer_term_counting( false );
	wp_suspend_cache_invalidation( false );

	// Resolve [[token|anchor]] links in every imported post.
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$ids = $wpdb->get_col( "SELECT p.ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_bbp_demo' WHERE p.post_content LIKE '%[[%'" );
	foreach ( $ids as $id ) {
		$content = get_post_field( 'post_content', $id );
		$new     = bbp_demo_resolve_links( $content );
		if ( $new !== $content ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->update( $wpdb->posts, array( 'post_content' => $new ), array( 'ID' => $id ) );
			clean_post_cache( $id );
		}
	}

	if ( $front ) {
		$structure = get_option( 'permalink_structure' );
		if ( ! $structure || '/%year%/%monthnum%/%day%/%postname%/' === $structure ) {
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
		}
		$home   = bbp_demo_find( 'page-home', 'page' );
		$guides = bbp_demo_find( 'page-guides', 'page' );
		if ( $home ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home );
		}
		if ( $guides ) {
			update_option( 'page_for_posts', $guides );
		}
		update_option( 'posts_per_page', 12 );
		bbp_demo_menus();
		// Move WordPress' default placeholder content to the trash (recoverable).
		foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $default ) {
			$p = get_page_by_path( $default[0], OBJECT, $default[1] );
			if ( $p && ! get_post_meta( $p->ID, '_bbp_demo', true ) ) {
				wp_trash_post( $p->ID );
			}
		}
	}

	// Recount terms.
	foreach ( array( 'billboard_city', 'billboard_type', 'category', 'post_tag' ) as $tax ) {
		$terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => false, 'fields' => 'tt_ids' ) );
		if ( ! is_wp_error( $terms ) && $terms ) {
			wp_update_term_count_now( $terms, $tax );
		}
	}
	delete_transient( 'bbp_states' );
	flush_rewrite_rules();
}

/**
 * Replace [[kind:slug|anchor]] tokens with real links.
 *
 * @param string $content Content.
 * @return string
 */
function bbp_demo_resolve_links( $content ) {
	return preg_replace_callback(
		'/\[\[([a-z]+)(?::([a-z0-9\-]+))?\|([^\]]+)\]\]/',
		function ( $m ) {
			static $cache = array();
			$kind   = $m[1];
			$slug   = $m[2];
			$anchor = $m[3];
			$ck     = $kind . ':' . $slug;
			if ( isset( $cache[ $ck ] ) ) {
				return $cache[ $ck ] ? '<a href="' . esc_url( $cache[ $ck ] ) . '">' . esc_html( $anchor ) . '</a>' : esc_html( $anchor );
			}
			$url = '';
			switch ( $kind ) {
				case 'g':
					$p   = get_page_by_path( $slug, OBJECT, 'post' );
					$url = $p ? get_permalink( $p ) : '';
					break;
				case 'p':
					$p   = get_page_by_path( $slug, OBJECT, 'page' );
					$url = $p ? get_permalink( $p ) : '';
					break;
				case 'loc':
					$p   = get_page_by_path( $slug, OBJECT, 'bb_location' );
					$url = $p ? get_permalink( $p ) : '';
					break;
				case 'city':
					$t   = get_term_by( 'slug', $slug, 'billboard_city' );
					$url = $t ? get_term_link( $t ) : '';
					break;
				case 'type':
					$t   = get_term_by( 'slug', $slug, 'billboard_type' );
					$url = $t ? get_term_link( $t ) : '';
					break;
				case 'inv':
					$url = get_post_type_archive_link( 'billboard' );
					if ( $slug ) {
						$url = add_query_arg( 'format', $slug, $url );
					}
					break;
				case 'locs':
					$url = get_post_type_archive_link( 'bb_location' );
					break;
				case 'cs':
					$url = get_post_type_archive_link( 'bb_case_study' );
					break;
				case 'quote':
					$url = bbp_page_url( 'quote' );
					break;
				case 'contact':
					$url = bbp_page_url( 'contact' );
					break;
			}
			if ( is_wp_error( $url ) ) {
				$url = '';
			}
			$cache[ $ck ] = $url;
			if ( ! $url ) {
				return esc_html( $anchor );
			}
			return '<a href="' . esc_url( $url ) . '">' . esc_html( $anchor ) . '</a>';
		},
		$content
	);
}

/**
 * Build menus.
 */
function bbp_demo_menus() {
	$make = function ( $name, $location ) {
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			wp_delete_nav_menu( $menu->term_id );
		}
		$id = wp_create_nav_menu( $name );
		if ( is_wp_error( $id ) ) {
			return 0;
		}
		update_term_meta( $id, '_bbp_demo', 1 );
		$locs              = get_theme_mod( 'nav_menu_locations', array() );
		$locs[ $location ] = $id;
		set_theme_mod( 'nav_menu_locations', $locs );
		return $id;
	};
	$item = function ( $menu, $title, $url, $parent = 0, $obj = array() ) {
		$args = array(
			'menu-item-title'     => $title,
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
		);
		if ( $obj ) {
			$args = array_merge( $args, $obj );
		} else {
			$args['menu-item-url']  = $url;
			$args['menu-item-type'] = 'custom';
		}
		return wp_update_nav_menu_item( $menu, 0, $args );
	};
	$page = function ( $key ) {
		$id = bbp_demo_find( 'page-' . $key, 'page' );
		return $id ? array( 'menu-item-object-id' => $id, 'menu-item-object' => 'page', 'menu-item-type' => 'post_type' ) : array();
	};

	$archive = get_post_type_archive_link( 'billboard' );
	$m       = $make( 'Primary', 'primary' );
	if ( $m ) {
		$b = $item( $m, __( 'Billboards', 'billboard-pro' ), $archive );
		$item( $m, __( 'All billboards', 'billboard-pro' ), $archive, $b );
		$item( $m, __( 'LED digital screens', 'billboard-pro' ), add_query_arg( 'format', 'digital', $archive ), $b );
		$item( $m, __( 'Static billboards', 'billboard-pro' ), add_query_arg( 'format', 'static', $archive ), $b );
		foreach ( array( 'led-highway-billboard', 'led-spectacular', 'building-led-screen', 'mall-screen', 'transit-screen' ) as $ts ) {
			$t = get_term_by( 'slug', $ts, 'billboard_type' );
			if ( $t ) {
				$item( $m, $t->name, get_term_link( $t ), $b );
			}
		}
		$led = $page( 'led' );
		$item( $m, __( 'LED Screens', 'billboard-pro' ), '', 0, $led );
		$item( $m, __( 'Locations', 'billboard-pro' ), get_post_type_archive_link( 'bb_location' ) );
		$item( $m, __( 'Case Studies', 'billboard-pro' ), get_post_type_archive_link( 'bb_case_study' ) );
		$g = $item( $m, __( 'Guides', 'billboard-pro' ), '', 0, $page( 'guides' ) );
		$item( $m, __( 'All guides', 'billboard-pro' ), '', $g, $page( 'guides' ) );
		$item( $m, __( 'FAQ', 'billboard-pro' ), '', $g, $page( 'faq' ) );
		$item( $m, __( 'Glossary', 'billboard-pro' ), '', $g, $page( 'glossary' ) );
		$item( $m, __( 'About', 'billboard-pro' ), '', $g, $page( 'about' ) );
		$item( $m, __( 'Contact', 'billboard-pro' ), '', 0, $page( 'contact' ) );
	}
	$f = $make( 'Footer', 'footer' );
	if ( $f ) {
		foreach ( array( 'about' => 'About us', 'led' => 'LED billboard screens', 'guides' => 'LED billboard guides', 'faq' => 'FAQ', 'glossary' => 'Glossary', 'quote' => 'Request a quote', 'contact' => 'Contact' ) as $k => $l ) {
			$item( $f, $l, '', 0, $page( $k ) );
		}
	}
	$l = $make( 'Legal', 'legal' );
	if ( $l ) {
		$item( $l, __( 'Privacy Policy', 'billboard-pro' ), '', 0, $page( 'privacy' ) );
		$item( $l, __( 'Terms', 'billboard-pro' ), '', 0, $page( 'terms' ) );
	}
}

/**
 * Removal, batched.
 */
function bbp_ajax_demo_remove() {
	bbp_demo_guard();
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 0;
	$ids  = get_posts(
		array(
			'post_type'      => array( 'billboard', 'bb_location', 'bb_case_study', 'bb_testimonial', 'post', 'page', 'attachment' ),
			'post_status'    => 'any',
			'posts_per_page' => 60,
			'fields'         => 'ids',
			'meta_key'       => '_bbp_demo', // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	if ( $ids ) {
		foreach ( $ids as $id ) {
			wp_delete_post( $id, true );
		}
		wp_send_json_success(
			array(
				'done'     => false,
				'next'     => $step + 1,
				'progress' => min( .95, ( $step + 1 ) / 20 ),
				/* translators: %d: count */
				'message'  => sprintf( __( 'Removed a batch of %d items…', 'billboard-pro' ), count( $ids ) ),
			)
		);
	}
	foreach ( array( 'billboard_city', 'billboard_type', 'category', 'nav_menu' ) as $tax ) {
		$terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => false, 'meta_key' => '_bbp_demo' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				wp_delete_term( $t->term_id, $tax );
			}
		}
	}
	delete_option( 'bbp_demo_done' );
	delete_option( 'bbp_demo_images' );
	delete_transient( 'bbp_states' );
	wp_send_json_success(
		array(
			'done'     => true,
			'progress' => 1,
			'message'  => __( 'Imported content removed.', 'billboard-pro' ),
		)
	);
}
add_action( 'wp_ajax_bbp_demo_remove', 'bbp_ajax_demo_remove' );
