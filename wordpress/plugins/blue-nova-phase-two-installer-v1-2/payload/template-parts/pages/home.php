<?php
/** Generated from approved mockup: third-mockup-v3.1.23.html. */
$rhn_uploads         = wp_upload_dir();
$rhn_upload_base_url = trailingslashit( $rhn_uploads['baseurl'] );
$rhn_hero_video_url  = $rhn_upload_base_url . '2026/08/rebekahs-homepage-hero-loop-full-v3-hd.mp4';
$rhn_hero_poster_url = $rhn_upload_base_url . '2026/08/rebekahs-homepage-hero-poster-v1.jpg';
$rhn_home_events     = function_exists( 'tribe_get_events' ) ? tribe_get_events(
	array(
		'posts_per_page' => 3,
		'order'          => 'ASC',
		'orderby'        => 'event_date',
		'post_status'    => 'publish',
		'start_date'     => current_time( 'Y-m-d H:i:s' ),
	)
) : array();
$rhn_home_posts      = get_posts(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
	)
);
$rhn_home_featured_products = array();
if ( function_exists( 'wc_get_products' ) ) {
	$rhn_home_featured_products = wc_get_products(
		array(
			'limit'    => 4,
			'status'   => 'publish',
			'featured' => true,
		)
	);

	if ( count( $rhn_home_featured_products ) < 4 ) {
		$rhn_home_featured_products = wc_get_products(
			array(
				'limit'   => 4,
				'status'  => 'publish',
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
	}
}
?>
<main id="main">
    <section class="hero" data-mock-section="2">
      <div class="hero-video" aria-hidden="true"><video autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url( $rhn_hero_poster_url ); ?>" src="<?php echo esc_url( $rhn_hero_video_url ); ?>"></video></div>
      <div class="hero-shade"></div>
      <div class="hero-copy"><span class="kicker light">Your neighborhood wellness source</span><h1>Wellness begins with knowing <em>where to start.</em></h1><p>For nearly 20 years, Rebekah's has helped Michigan families find trusted supplements, natural products, practical education and personalized guidance—all under one roof.</p><div class="buttons"><a class="pill honey" href="#locations">Find Your Store</a><a class="pill glass" href="#events">Explore Classes & Events</a><a class="pill glass" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop Online</a></div></div>
      <div class="hero-proof"><b>4</b><span>Michigan<br>locations</span><b>20</b><span>Years of<br>local care</span></div>
    </section>

    <div class="original-section-mount mount-pathway" id="pathway" data-source-section="pathway" data-mock-section="3" aria-label="Expert guidance pathways"></div>

    <section class="phase-two-shop rhn-home-commerce section" id="online-store" data-mock-section="3b" aria-labelledby="online-store-title">
      <div class="phase-shop-wrap">
        <div class="phase-shop-heading">
          <div><span class="kicker">Rebekah's Online Store</span><h2 id="online-store-title">Shop wellness essentials with the guidance you already trust.</h2></div>
          <div class="phase-shop-cta"><div class="phase-shop-actions"><a class="pill phase-shop-primary" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop All Products</a><a class="text-link" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Browse by category →</a></div></div>
        </div>
        <div class="phase-shop-paths" aria-label="Online shopping paths">
          <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><span class="phase-path-image" aria-hidden="true"></span><span class="phase-path-copy"><strong>Shop All Products</strong><small>Explore supplements, natural products and everyday wellness essentials.</small></span></a>
          <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><span class="phase-path-image" aria-hidden="true"></span><span class="phase-path-copy"><strong>Shop by Category</strong><small>Browse vitamins, herbs, healthy foods, body care and more.</small></span></a>
          <a href="tel:12488432011"><span class="phase-path-image" aria-hidden="true"></span><span class="phase-path-copy"><strong>Ask Rebekah's Team</strong><small>Call (248) 843-2011 for friendly product guidance.</small></span></a>
        </div>
        <div class="phase-featured-head"><div><span class="kicker">Featured Products</span><h3>Wellness favorites thoughtfully selected.</h3></div></div>
        <?php if ( $rhn_home_featured_products ) : ?>
          <div class="phase-product-grid">
            <?php foreach ( $rhn_home_featured_products as $rhn_home_product ) : ?>
              <?php
				$rhn_product_categories = wc_get_product_category_list( $rhn_home_product->get_id(), ', ' );
				$rhn_product_action_url = $rhn_home_product->is_type( 'simple' ) && $rhn_home_product->is_purchasable() ? $rhn_home_product->add_to_cart_url() : $rhn_home_product->get_permalink();
				$rhn_product_action     = $rhn_home_product->is_type( 'simple' ) && $rhn_home_product->is_purchasable() ? $rhn_home_product->add_to_cart_text() : __( 'View product', 'rebekahs-2026' );
				?>
              <article class="phase-product">
                <a class="phase-product-image" href="<?php echo esc_url( $rhn_home_product->get_permalink() ); ?>"><?php echo wp_kses_post( $rhn_home_product->get_image( 'woocommerce_thumbnail' ) ); ?></a>
                <?php if ( $rhn_product_categories ) : ?><p><?php echo wp_kses_post( $rhn_product_categories ); ?></p><?php endif; ?>
                <h4><a href="<?php echo esc_url( $rhn_home_product->get_permalink() ); ?>"><?php echo esc_html( $rhn_home_product->get_name() ); ?></a></h4>
                <b><?php echo wp_kses_post( $rhn_home_product->get_price_html() ); ?></b>
                <a class="phase-product-action" href="<?php echo esc_url( $rhn_product_action_url ); ?>"><?php echo esc_html( $rhn_product_action ); ?></a>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <div class="phase-product-pending" role="status"><strong>Featured products are being prepared.</strong><span>The approved layout is ready, and products will populate here after the catalog connection is completed.</span></div>
        <?php endif; ?>
      </div>
    </section>

    <section class="intro section" data-mock-section="4">
      <div class="intro-media"><div class="intro-photo-frame"><img src="<?php echo esc_url( rhn_theme_asset( 'output/homepage/private-label-bottles-leveled-v1.png' ) ); ?>" alt="Level rows of Rebekah's private-label supplements in amber glass bottles"></div><span>Purity · Potency · Price</span></div>
      <div class="intro-copy"><span class="kicker">A better place to begin</span><h2>Real guidance for your everyday wellness.</h2><p class="lead">Wellness shelves can feel overwhelming. Our caring, knowledgeable team helps you compare options, ask better questions and choose products with confidence.</p><a class="text-link" href="#story">Discover the Rebekah's difference →</a></div>
    </section>

    <div class="original-section-mount" id="shipping" data-source-section="shipping" data-mock-section="5" aria-label="Nationwide shipping"></div>

    <section class="signature" data-mock-section="6">
      <div class="signature-numbers" aria-hidden="true"><i class="signature-orbit-dot orbit-honey"></i><i class="signature-orbit-dot orbit-sage"></i><span>Rebekah's</span><strong>200+</strong><p>private-label<br>wellness formulas<em>PURITY · POTENCY · PRICE</em></p></div>
      <div class="signature-copy"><span class="kicker light">Rebekah's Private Label Signature Line</span><h2>Premium formulas. Personal standards.</h2><p>Rebekah developed a collection of more than 200 herbal, vitamin and nutraceutical formulas made with whole-food ingredients and bottled in protective amber glass.</p><div class="chips"><span>200+ formulas</span><span>Whole-food ingredients</span><span>Amber glass bottles</span></div><a class="pill cream" href="<?php echo esc_url( home_url( '/rebekahs-signature-line/' ) ); ?>">Explore the Signature Line</a></div>
    </section>

    <section class="section story" id="story" data-mock-section="7">
      <div class="collage"><img class="big" src="<?php echo esc_url( rhn_theme_asset( 'output/homepage/rebekah-story-ai-enhanced-v1.png' ) ); ?>" alt="Rebekah Spencer, founder of Rebekah's Health & Nutrition"><span class="badge">Rooted in<br><b>care</b></span></div>
      <div class="story-copy"><span class="kicker">Meet Rebekah Spencer</span><h2>It has always been personal.</h2><p class="lead">After navigating her own health challenges, Rebekah built the kind of wellness store she wished every family had: trusted products, useful education and people who genuinely listen.</p><p>Since opening the first location in 2010, she has grown Rebekah's into four Michigan stores while remaining a hands-on Holistic Health Practitioner, Wellness Educator and Business Owner.</p><blockquote>“Take control of your health, and you take control of your life.”</blockquote><a class="text-link" href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>">Read Rebekah's story →</a></div>
    </section>

    <div class="source-b" id="events" data-mock-section="8">
      <section class="section events" aria-label="Classes and events">
        <div class="container">
          <div class="events-head reveal visible"><div><span class="eyebrow">Community &amp; education</span><h2>Learn, connect and support your wellness locally.</h2></div><div><p>Rebekah’s hosts free educational classes, health screenings, brand representatives, wellness fairs and local pop-ups throughout the year. Browse upcoming events and find something happening at the store nearest you.</p><a class="btn btn-secondary btn-arrow" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">View All Events</a></div></div>
          <div class="event-grid">
            <?php if ( $rhn_home_events ) : ?>
              <?php foreach ( $rhn_home_events as $rhn_event ) : ?>
                <?php
				$rhn_event_id      = $rhn_event->ID;
				$rhn_event_date    = function_exists( 'tribe_get_start_date' ) ? tribe_get_start_date( $rhn_event_id, false, 'F j' ) : get_the_date( 'F j', $rhn_event_id );
				$rhn_event_venue   = function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $rhn_event_id ) : '';
				$rhn_event_meta    = trim( $rhn_event_date . ( $rhn_event_venue ? ' · ' . $rhn_event_venue : '' ) );
				$rhn_event_excerpt = get_the_excerpt( $rhn_event_id );
				if ( ! $rhn_event_excerpt ) {
					$rhn_event_excerpt = wp_strip_all_tags( get_post_field( 'post_content', $rhn_event_id ) );
				}
				?>
                <a class="event-card reveal visible" href="<?php echo esc_url( get_permalink( $rhn_event_id ) ); ?>">
                  <?php if ( has_post_thumbnail( $rhn_event_id ) ) : ?><?php echo get_the_post_thumbnail( $rhn_event_id, 'medium_large', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php else : ?><span class="rhn-home-card-placeholder" aria-hidden="true">Rebekah's</span><?php endif; ?>
                  <div class="event-body"><span class="date"><?php echo esc_html( $rhn_event_meta ); ?></span><h3><?php echo esc_html( get_the_title( $rhn_event_id ) ); ?></h3><p><?php echo esc_html( wp_trim_words( $rhn_event_excerpt, 24 ) ); ?></p></div>
                </a>
              <?php endforeach; ?>
            <?php else : ?>
              <div class="event-card rhn-home-empty"><div class="event-body"><span class="date">More dates coming soon</span><h3>Upcoming classes &amp; events</h3><p>Visit the full calendar for the latest community events at Rebekah's locations.</p><a class="btn btn-secondary btn-arrow" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Open the event calendar</a></div></div>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </div>

    <div class="source-b" id="journal" data-mock-section="9">
      <section class="section journal" aria-label="Wellness education">
        <div class="container journal-grid">
          <div class="journal-intro reveal visible"><span class="eyebrow">Wellness, explained</span><h2>Natural wellness education you can use.</h2><p>Read practical guidance from Rebekah Spencer and the Rebekah’s team on supplements, immune support, digestion, stress, sleep, detoxification and healthy daily routines.</p><a class="btn btn-primary btn-arrow" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Visit the Wellness Blog</a></div>
          <div class="posts">
            <?php foreach ( $rhn_home_posts as $rhn_home_post ) : ?>
              <a class="post reveal visible" href="<?php echo esc_url( get_permalink( $rhn_home_post ) ); ?>"><?php echo wp_kses_post( rhn_post_image( $rhn_home_post->ID, 'medium' ) ); ?><div><time datetime="<?php echo esc_attr( get_the_date( 'c', $rhn_home_post ) ); ?>"><?php echo esc_html( get_the_date( 'F j, Y', $rhn_home_post ) ); ?></time><h3><?php echo esc_html( get_the_title( $rhn_home_post ) ); ?></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt( $rhn_home_post ), 18 ) ); ?></p></div></a>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </div>

    <?php get_template_part( 'template-parts/components/home-social-feeds' ); ?>

    <section class="section locations" id="locations" data-mock-section="11">
      <div class="section-title"><div><span class="kicker">Four stores, one caring community</span><h2>Find your Rebekah's.</h2></div><p>Choose a location for directions, local hours, events and store-specific updates.</p></div>
      <div class="location-grid"><article><b>01</b><h3>Lapeer</h3><p>588 S. Main Street<br>Lapeer, MI 48446</p><a href="tel:18106608585">(810) 660-8585</a><a class="location-link" href="<?php echo esc_url( home_url( '/locations/lapeer/' ) ); ?>">View store →</a></article><article><b>02</b><h3>Grand Blanc</h3><p>252 Perry Road<br>Grand Blanc, MI 48439</p><a href="tel:18108664642">(810) 866-4642</a><a class="location-link" href="<?php echo esc_url( home_url( '/locations/grand-blanc/' ) ); ?>">View store →</a></article><article><b>03</b><h3>Clarkston</h3><p>7093 Dixie Hwy<br>Clarkston, MI 48346</p><a href="tel:12488432011">(248) 843-2011</a><a class="location-link" href="<?php echo esc_url( home_url( '/locations/clarkston/' ) ); ?>">View store →</a></article><article><b>04</b><h3>Lake Orion</h3><p>1095 S. Lapeer Road<br>Lake Orion, MI 48360</p><a href="tel:12489298990">(248) 929-8990</a><a class="location-link" href="<?php echo esc_url( home_url( '/locations/lake-orion/' ) ); ?>">View store →</a></article></div>
    </section>

    <div class="original-section-mount" id="connect" data-source-section="newsletter" data-mock-section="12" aria-label="Stay connected signup"></div>
    <div id="rhn-newsletter-form-source" hidden><?php echo do_shortcode( '[forminator_form id="313"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><p class="rhn-recaptcha-note">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">Terms of Service</a> apply.</p></div>

    <div class="original-section-mount" id="practitioners" data-source-section="practitioner" data-mock-section="13" aria-label="Practitioner brands"></div>
  </main>
