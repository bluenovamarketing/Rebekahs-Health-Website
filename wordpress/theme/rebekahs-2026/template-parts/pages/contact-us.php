<?php
/** Generated from approved mockup: contact-us-mockup-v1.0.0.html. */
?>
<main id="main">
    <section class="hero">
      <div class="wrap hero-grid">
        <div>
          <span class="eyebrow">We’re here to help</span>
          <h1>Let’s find the <em>right person.</em></h1>
          <p class="lead hero-copy">Have a question about a product, an upcoming class or your neighborhood store? Choose the quickest route below or send our team a message.</p>
          <div class="hero-actions"><a class="button primary" href="#stores">Call your store</a><a class="button secondary" href="#message">Send a message</a></div>
        </div>
        <aside class="route-note" aria-label="Product availability note"><small>Need a product today?</small><strong>Call the store you plan to visit.</strong><p>Selection can vary by location. A quick call is the best way to confirm current availability before making the trip.</p></aside>
      </div>
    </section>

    <section class="routes" aria-labelledby="route-title">
      <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">Start in the right place</span><h2 id="route-title">How can we help?</h2></div><p>Direct questions reach the right team faster. For personal medical advice, please contact a qualified healthcare professional.</p></div>
        <div class="route-grid">
          <article class="route featured"><span class="route-icon" aria-hidden="true">☎</span><h3>Store & product questions</h3><p>Ask about store hours, products, availability or help finding an item.</p><a href="#stores">Choose your store →</a></article>
          <article class="route"><span class="route-icon" aria-hidden="true">◷</span><h3>Classes & events</h3><p>Check event details, participating locations and current attendance information.</p><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Browse classes & events →</a></article>
          <article class="route"><span class="route-icon" aria-hidden="true">✉</span><h3>General inquiries</h3><p>Send a non-urgent question to the team through the contact form below.</p><a href="#message">Send a message →</a></article>
        </div>
      </div>
    </section>

    <section class="stores" id="stores" aria-labelledby="store-title">
      <div class="wrap">
        <div class="stores-head"><span class="eyebrow">Four Michigan locations</span><h2 id="store-title">Call the team nearest you.</h2><p class="lead">For product availability and local questions, contacting your preferred store is usually the fastest option.</p></div>
        <div class="store-grid">
          <article class="store"><span class="store-number">01</span><div><h3>Lapeer</h3><address>588 S. Main Street<br>Lapeer, MI 48446</address><p class="hours">Mon–Sat 9am–7pm · Sun 10am–5pm</p></div><div class="store-actions"><a href="tel:+18106608585">(810) 660-8585</a><a class="directions" href="https://www.google.com/maps/search/?api=1&query=588+S+Main+St+Lapeer+MI+48446">Get directions ↗</a></div></article>
          <article class="store"><span class="store-number">02</span><div><h3>Grand Blanc</h3><address>252 Perry Road, Suite 4<br>Grand Blanc, MI 48439</address><p class="hours">Mon–Sat 9am–7pm · Sun 10am–5pm</p></div><div class="store-actions"><a href="tel:+18108664642">(810) 866-4642</a><a class="directions" href="https://www.google.com/maps/search/?api=1&query=252+Perry+Road+Grand+Blanc+MI+48439">Get directions ↗</a></div></article>
          <article class="store"><span class="store-number">03</span><div><h3>Clarkston</h3><address>7093 Dixie Highway, Suite B<br>Clarkston, MI 48346</address><p class="hours">Mon–Sat 9am–7pm · Sun 10am–5pm</p></div><div class="store-actions"><a href="tel:+12488432011">(248) 843-2011</a><a class="directions" href="https://www.google.com/maps/search/?api=1&query=7093+Dixie+Highway+Clarkston+MI+48346">Get directions ↗</a></div></article>
          <article class="store"><span class="store-number">04</span><div><h3>Lake Orion</h3><address>1095 S. Lapeer Road<br>Lake Orion, MI 48360</address><p class="hours">Mon–Fri 9am–7pm · Sat–Sun 10am–5pm</p></div><div class="store-actions"><a href="tel:+12489298990">(248) 929-8990</a><a class="directions" href="https://www.google.com/maps/search/?api=1&query=1095+S+Lapeer+Road+Lake+Orion+MI+48360">Get directions ↗</a></div></article>
        </div>
      </div>
    </section>

    <section class="contact" id="message" aria-labelledby="message-title">
      <div class="wrap contact-grid">
        <div class="contact-copy"><span class="eyebrow">General inquiries</span><h2 id="message-title">Send us a message.</h2><p class="lead">Tell us what you need and which location you prefer. We’ll route your message to the appropriate team.</p><div class="expect"><strong>Please don’t include private health information.</strong><p>This form is for general, non-urgent questions and cannot provide medical advice or emergency support.</p></div><p class="privacy-note">We use the information you provide only to respond to your inquiry. See our <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a> for details.</p></div>
        <div class="form-card rhn-forminator-wrap">
          <h3>How can we help?</h3><p class="form-intro">Use the secure form below for general, non-urgent questions.</p>
          <?php echo do_shortcode( '[forminator_form id="1064"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          <p class="rhn-recaptcha-note">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">Terms of Service</a> apply.</p>
        </div>
      </div>
    </section>

    <section class="close"><div class="wrap close-grid"><div><h2>Prefer to stop in?</h2><p>Compare all four locations and choose the store that works best for you.</p></div><a class="button" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">Explore our locations →</a></div></section>
  </main>
