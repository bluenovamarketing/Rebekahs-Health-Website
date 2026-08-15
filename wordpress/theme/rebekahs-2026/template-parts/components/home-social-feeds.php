<?php
/**
 * Homepage Instagram and TikTok feeds.
 *
 * The approved static cards remain as a public fallback until each saved
 * Smash Balloon feed contains real posts. The component script swaps each
 * platform independently, so one connected source never hides the other
 * platform's fallback.
 *
 * @package Rebekahs_2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rhn_instagram_feed = do_shortcode( '[instagram-feed feed="1"]' );
$rhn_tiktok_feed    = do_shortcode( '[sbtt-tiktok feed="1"]' );
?>
<section class="social-section" id="instagram" data-mock-section="10">
  <div class="social-heading"><div><span class="kicker">Fresh from Instagram</span><h2>See what's happening at Rebekah's.</h2></div><div class="social-profile"><strong>@rebekahs_health_and_nutrition</strong><span>Store news &middot; wellness education &middot; new products &middot; events</span><a class="pill outline" href="https://www.instagram.com/rebekahs_health_and_nutrition/" target="_blank" rel="noopener noreferrer">Follow on Instagram</a></div></div>
  <div class="rhn-social-feed-switch" data-rhn-social-feed="instagram">
    <div class="rhn-social-feed-live" aria-label="Latest Instagram posts" aria-hidden="true"><?php echo $rhn_instagram_feed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
    <div class="social-feed rhn-social-feed-fallback"><a class="social-post reel" href="https://www.instagram.com/rebekahs_health_and_nutrition/" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/instagram-preview/product-spotlight.jpg' ) ); ?>" alt="Product spotlight"><span class="play">&#9654;</span><small>Product spotlight</small></a><a class="social-post" href="https://www.instagram.com/rebekahs_health_and_nutrition/" target="_blank" rel="noopener noreferrer"><img src="https://rebekahspureliving.com/wp-content/uploads/2026/01/Customer-Appreciation-1024x1024.jpg" alt="Customer appreciation"><small>Community events</small></a><a class="social-post reel tall" href="https://www.instagram.com/rebekahs_health_and_nutrition/" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/in-store-products-v3.1.8/vitamins-private-label.jpg' ) ); ?>" alt="Rebekah's private-label supplements"><span class="play">&#9654;</span><small>Wellness education</small></a><a class="social-post" href="https://www.instagram.com/rebekahs_health_and_nutrition/" target="_blank" rel="noopener noreferrer"><img src="https://rebekahspureliving.com/wp-content/uploads/2024/07/story.jpg" alt="Rebekah Spencer"><small>Meet Rebekah</small></a><a class="social-post reel" href="https://www.instagram.com/rebekahs_health_and_nutrition/" target="_blank" rel="noopener noreferrer"><img src="https://images.unsplash.com/photo-1543362906-acfc16c67564?auto=format&amp;fit=crop&amp;w=800&amp;q=85" alt="Natural foods"><span class="play">&#9654;</span><small>Everyday wellness</small></a><a class="social-post" href="https://www.instagram.com/rebekahs_health_and_nutrition/" target="_blank" rel="noopener noreferrer"><img src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&amp;fit=crop&amp;w=800&amp;q=85" alt="Natural skincare"><small>New in store</small></a></div>
  </div>
</section>

<section class="social-section" id="tiktok" data-mock-section="10b">
  <div class="social-heading tiktok-fixed"><div><span class="kicker">Fresh from TikTok</span><h2>Watch what's happening at Rebekah's.</h2></div><div class="social-profile"><strong>@rebekahspureliving</strong><span>Wellness tips &middot; product education &middot; store moments &middot; community</span><a class="pill outline" href="https://www.tiktok.com/@rebekahspureliving" target="_blank" rel="noopener noreferrer">Follow on TikTok</a></div></div>
  <div class="rhn-social-feed-switch" data-rhn-social-feed="tiktok">
    <div class="rhn-social-feed-live" aria-label="Latest TikTok videos" aria-hidden="true"><?php echo $rhn_tiktok_feed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
    <div class="social-feed tiktok-fixed rhn-social-feed-fallback"><a class="social-post reel" href="https://www.tiktok.com/@rebekahspureliving" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/tiktok-preview/recent-01.jpg' ) ); ?>" alt="Product spotlight"><span class="play">&#9654;</span><small>Product spotlight</small></a><a class="social-post reel" href="https://www.tiktok.com/@rebekahspureliving" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/tiktok-preview/recent-02.jpg' ) ); ?>" alt="Community at Rebekah's"><span class="play">&#9654;</span><small>Community moments</small></a><a class="social-post reel tall" href="https://www.tiktok.com/@rebekahspureliving" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/tiktok-preview/recent-03.jpg' ) ); ?>" alt="Private label supplement"><span class="play">&#9654;</span><small>Behind the label</small></a><a class="social-post reel" href="https://www.tiktok.com/@rebekahspureliving" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/tiktok-preview/recent-04.jpg' ) ); ?>" alt="Rebekah Spencer"><span class="play">&#9654;</span><small>Meet Rebekah</small></a><a class="social-post reel" href="https://www.tiktok.com/@rebekahspureliving" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/tiktok-preview/recent-05.jpg' ) ); ?>" alt="Natural foods"><span class="play">&#9654;</span><small>Everyday wellness</small></a><a class="social-post reel" href="https://www.tiktok.com/@rebekahspureliving" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( rhn_theme_asset( 'output/tiktok-preview/recent-06.jpg' ) ); ?>" alt="Natural skincare"><span class="play">&#9654;</span><small>New in store</small></a></div>
  </div>
</section>
