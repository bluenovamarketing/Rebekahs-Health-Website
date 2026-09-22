<?php
/** Dynamic Our Team page. */

defined( 'ABSPATH' ) || exit;

get_header();
$stores  = rhn_team_stores();
$grouped = rhn_team_members_grouped();
$total   = array_sum( array_map( 'count', $grouped ) );
?>
<main id="main">
  <section class="team-hero">
    <div class="team-wrap team-hero-grid">
      <div class="team-hero-copy">
        <span class="team-eyebrow">Our people</span>
        <h1>Meet the team <em>behind the care.</em></h1>
        <p>Across four Michigan stores, our team brings curiosity, continuing education and a genuine desire to help. Meet the people who make every visit feel personal.</p>
        <div class="hero-actions"><a class="pill honey" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">Find your store</a></div>
      </div>
      <div class="hero-collage" aria-label="Rebekah's teams across four Michigan stores">
        <figure class="hero-photo one"><img src="<?php echo esc_url( rhn_theme_asset( 'output/lapeer-location/lapeer-team-1.jpg' ) ); ?>" alt="Team members together inside Rebekah's Lapeer store"><figcaption>Lapeer</figcaption></figure>
        <figure class="hero-photo two"><img src="<?php echo esc_url( rhn_theme_asset( 'output/grand-blanc-location/grand-blanc-team-drive.jpg' ) ); ?>" alt="Team members together inside Rebekah's Grand Blanc store"><figcaption>Grand Blanc</figcaption></figure>
        <figure class="hero-photo three"><img src="<?php echo esc_url( rhn_theme_asset( 'output/clarkston-location/clarkston-team-client.jpg' ) ); ?>" alt="Team members together inside Rebekah's Clarkston store"><figcaption>Clarkston</figcaption></figure>
        <figure class="hero-photo four"><img src="<?php echo esc_url( rhn_theme_asset( 'output/lake-orion-location/lake-orion-team.jpg' ) ); ?>" alt="Team members together inside Rebekah's Lake Orion store"><figcaption>Lake Orion</figcaption></figure>
        <div class="hero-badge"><strong><?php echo esc_html( (string) $total ); ?></strong><span>team stories · one shared purpose</span></div>
      </div>
    </div>
  </section>

  <div class="team-proof"><div class="team-wrap proof-panel"><div class="proof-intro"><strong>Guidance starts with listening.</strong><span>Real people. Thoughtful questions. Practical next steps.</span></div><div><strong>4</strong><span>Michigan stores</span></div><div><strong><?php echo esc_html( (string) $total ); ?></strong><span>team stories</span></div><div><strong>1</strong><span>caring community</span></div></div></div>

  <section class="team-intro" id="meet-the-team">
    <div class="team-wrap intro-grid"><div><span class="team-eyebrow">People before products</span><h2>Knowledgeable by choice. Caring by nature.</h2></div><div class="intro-copy"><p>Some team members came to Rebekah's with years of health-food experience. Others discovered a calling through the customers, classes and daily learning inside our stores. Every story is different, but the purpose is shared.</p><p>We are here to make the wellness aisle feel less overwhelming—by listening first, sharing what we know and helping each visitor make informed choices that fit their own goals and lifestyle.</p></div></div>
  </section>

  <nav class="store-jump team-wrap" aria-label="Jump to a store team"><div class="store-jump-panel">
    <?php $number = 0; foreach ( $stores as $slug => $store ) : $number++; ?>
      <a href="#<?php echo esc_attr( $slug ); ?>-team"><small><?php echo esc_html( str_pad( (string) $number, 2, '0', STR_PAD_LEFT ) . ' · ' . $store['name'] ); ?></small><b>Meet the <?php echo esc_html( $store['name'] ); ?> team</b><span><?php echo esc_html( sprintf( _n( '%s story →', '%s stories →', count( $grouped[ $slug ] ), 'rhn-team' ), number_format_i18n( count( $grouped[ $slug ] ) ) ) ); ?></span></a>
    <?php endforeach; ?>
  </div></nav>

  <?php foreach ( $stores as $slug => $store ) : ?>
    <section class="location-team <?php echo esc_attr( $store['class'] ); ?>" id="<?php echo esc_attr( $slug ); ?>-team">
      <div class="team-wrap"><header class="location-head"><div><span class="team-eyebrow"><?php echo esc_html( $store['eyebrow'] ); ?></span><h2><?php echo esc_html( $store['heading'] ); ?></h2></div><p><?php echo esc_html( $store['description'] ); ?></p></header>
        <div class="team-grid <?php echo esc_attr( rhn_team_grid_class( count( $grouped[ $slug ] ) ) ); ?>">
          <?php foreach ( $grouped[ $slug ] as $member ) :
			$role    = get_post_meta( $member->ID, '_rhn_team_role', true );
			$summary = get_post_meta( $member->ID, '_rhn_team_summary', true );
			$bio     = trim( $member->post_content );
			$media_class = get_post_meta( $member->ID, '_rhn_team_media_class', true );
			?>
            <article class="team-card"><div class="team-card-media <?php echo esc_attr( $media_class ); ?>"><?php echo rhn_team_image_html( $member->ID, $store['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="store-tag"><?php echo esc_html( $store['name'] ); ?></span></div><div class="team-card-content"><h3><?php echo esc_html( get_the_title( $member ) ); ?></h3><span class="role"><?php echo esc_html( $role ); ?></span><p class="card-summary"><?php echo esc_html( $summary ); ?></p><?php if ( $bio ) : ?><details class="bio"><summary>Read <?php echo esc_html( get_the_title( $member ) ); ?>'s full bio</summary><div class="bio-copy"><?php echo wp_kses_post( wpautop( $bio ) ); ?></div></details><?php endif; ?></div></article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endforeach; ?>

  <section class="team-note"><div class="team-wrap team-note-inner"><div class="team-note-mark"><strong><?php echo esc_html( (string) $total ); ?></strong><span>stories worth sharing</span></div><div class="team-note-copy"><span class="team-eyebrow">A team that keeps learning</span><h2>Wellness knowledge is never finished.</h2><p>Our team members study, ask questions, attend product education and learn from the people they serve. We value that curiosity because good guidance begins with humility—and the willingness to keep growing.</p><p>Our role is educational. We help you compare products and understand available options; we do not diagnose, prescribe or replace advice from a qualified healthcare professional.</p></div></div></section>

  <section class="visit-team"><div class="visit-team-copy"><span class="team-eyebrow">Come say hello</span><h2>Meet your team in person.</h2><p>The best way to experience Rebekah's is to walk through the door, ask a question and start a conversation. Find the store nearest you and meet the people ready to help.</p><div class="visit-actions"><a class="pill honey" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">Find your store</a><a class="pill hero-secondary" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Explore classes & events</a></div></div><div class="visit-team-media"><img src="<?php echo esc_url( rhn_theme_asset( 'output/clarkston-location/clarkston-team-client.jpg' ) ); ?>" alt="The Rebekah's team welcoming customers at the Clarkston store"></div></section>
</main>
<?php get_footer(); ?>
