# Rebekah's Instagram Feed - Elementor Setup

## Recommended approach

Use **Smash Balloon Instagram Feed Pro**. Harvest Health Foods is currently using the same plugin (`instagram-feed-pro`). It provides a live, automatically updating feed rather than manually embedded posts whose image URLs can expire.

Instagram account:

`https://www.instagram.com/rebekahs_health_and_nutrition/`

## WordPress setup

1. Install and activate **Smash Balloon Instagram Feed Pro**.
2. Go to **Instagram Feed > Settings > Sources**.
3. Choose **Add Source** and sign in to the Instagram/Facebook account authorized to manage `@rebekahs_health_and_nutrition`.
4. Approve the requested Meta permissions and select the correct Instagram account.
5. Go to **Instagram Feed > All Feeds > Add New**.
6. Choose **User Timeline**, select Rebekah's connected account, and name the feed `Homepage Instagram`.

## Feed settings

- Layout: Grid
- Initial posts: 8
- Desktop columns: 4
- Tablet columns: 3
- Mobile columns: 2
- Image crop: Square or portrait
- Include: Photos, videos, and Reels
- Header: On
- Bio: On
- Follow button: On
- Load More button: Optional
- Captions: Off in the grid; show in the lightbox
- Likes/comments: Optional
- Lightbox/video playback: On
- Feed cache: On

## Elementor placement

1. Edit the homepage with Elementor.
2. Add a full-width container after **Wellness Source Blog** and before **Locations**.
3. Give the container the CSS ID `rebekahs-instagram`.
4. Suggested container settings:
   - Content width: Full width
   - Inner content width: 1180px
   - Desktop padding: 100px top/bottom and 7vw left/right
   - Tablet padding: 80px and 40px
   - Mobile padding: 70px and 20px
   - Background: `#FFF9EC`
5. Add the heading and introduction in a nested container.
6. Search Elementor widgets for **Instagram Feed**, drag in the Smash Balloon widget, and choose `Homepage Instagram`.
7. If the dedicated widget is unavailable, use Elementor's **Shortcode** widget and paste the shortcode supplied by Smash Balloon, such as:

   `[instagram-feed feed=1]`

## Optional custom CSS

Add this to the container's Elementor Custom CSS or the site's Additional CSS. Confirm selectors against the installed plugin version after the feed is live.

```css
#rebekahs-instagram #sb_instagram {
  max-width: 1180px;
  margin: 0 auto;
}

#rebekahs-instagram #sbi_images {
  gap: 14px;
}

#rebekahs-instagram .sbi_item {
  overflow: hidden;
  border-radius: 32px 12px 12px 12px;
  box-shadow: 0 18px 38px rgba(23, 76, 60, 0.14);
  transition: transform .35s ease, box-shadow .35s ease;
}

#rebekahs-instagram .sbi_item:nth-child(even) {
  border-radius: 12px 12px 32px 12px;
}

#rebekahs-instagram .sbi_item:hover {
  transform: translateY(-7px);
  box-shadow: 0 26px 48px rgba(23, 76, 60, 0.2);
}

#rebekahs-instagram .sbi_follow_btn a,
#rebekahs-instagram .sbi_load_btn {
  border-radius: 999px !important;
  background: #174C3C !important;
}
```

## Maintenance

- Do not build the production feed from copied Instagram CDN image URLs; those URLs expire.
- Keep the plugin updated and caching enabled.
- If the feed stops updating, reconnect the Instagram source under **Instagram Feed > Settings > Sources**.
- Recheck the feed on desktop, tablet, and mobile after Instagram or plugin updates.
