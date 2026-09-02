# Clear the Website Cache Safely

**Version:** v1.1  
**Required access:** Editor for an available record/page cache action; Administrator or Blue Nova for approved sitewide/server cache controls

## When cache clearing applies

- WordPress confirmed that the correct content was saved.
- The exact public page still shows older information.
- You know the exact public URL visitors use.

Cache clearing does not save work and does not correct wrong content.

## Use the smallest safe sequence

1. Review the approved change and select **Save**, **Update**, or **Publish** once.
2. Wait for WordPress to confirm the save.
3. Keep the exact public URL. Do not add a question mark or test text.
4. If available, use the changed row's **Clear Cache** action.
5. Open the exact public URL and hard refresh once:
   - Windows: **Ctrl + F5**
   - Mac: **Command + Shift + R**
6. If the regular browser remains stale, check the same URL in a private or Incognito window.
7. Use an approved **Breeze Purge All Cache** only when the change affects shared pages and the smaller clear was insufficient.
8. Use a Varnish purge only when specifically authorized. Otherwise contact Blue Nova.

## Never do these without Blue Nova direction

- Repeatedly click Update, Publish, or cache purge controls.
- Change Breeze, Cloudways, Varnish, Redis, object-cache, CDN, database, image, plugin, or optimization settings.
- Restart services, disable Varnish, install plugins, or purge unrelated content.

## Verify publicly

1. Open the exact canonical public URL.
2. Confirm the visible change and test affected links at desktop size.
3. Repeat at phone size and check for unreadable text, broken controls, or horizontal overflow.

**Always finish with: Save > clear the applicable cache > open the exact public URL > verify the visible result on desktop and phone. If it is still wrong or missing, stop and contact Blue Nova.**

