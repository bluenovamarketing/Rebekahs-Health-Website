<?php
/** Generated from approved mockup: 404-page-mockup-v1.0.0.html. */
?>
<main id="main">
    <section class="error" aria-labelledby="error-title">
      <div class="wrap error-grid">
        <div class="mark" aria-hidden="true"><span class="number">404</span><span class="mark-note">Let’s get you<br>back on a<br>helpful path.</span></div>
        <div>
          <span class="eyebrow">Page not found</span>
          <h1 id="error-title">This path doesn’t lead <em>anywhere yet.</em></h1>
          <p class="lead">The page may have moved, the address may be incomplete, or the link may be out of date. Try a search or choose one of the helpful places below.</p>
          <div class="actions"><a class="button primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Return home</a><a class="button secondary" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">Find a store</a></div>
          <form class="search" role="search" action="https://rebekahspureliving.com/" method="get">
            <label for="site-search">Search Rebekah's website</label>
            <input id="site-search" name="s" type="search" placeholder="What are you looking for?" autocomplete="off">
            <button type="submit">Search site</button>
          </form>
        </div>
      </div>
    </section>
    <section class="routes" aria-label="Helpful destinations">
      <div class="wrap route-grid">
        <a class="route" href="<?php echo esc_url( home_url( '/in-store-products/' ) ); ?>"><small>Explore in store</small><h2>Products & categories</h2><p>See the kinds of wellness products available across our four stores.</p><span>Browse in-store products →</span></a>
        <a class="route" href="<?php echo esc_url( home_url( '/events/' ) ); ?>"><small>Learn together</small><h2>Classes & events</h2><p>Find upcoming opportunities to learn, ask questions and connect locally.</p><span>See what’s coming up →</span></a>
        <a class="route" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><small>Still need help?</small><h2>Contact our team</h2><p>Tell us what you were trying to find and we’ll point you in the right direction.</p><span>Get in touch →</span></a>
      </div>
    </section>
  </main>
