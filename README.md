# Bric Theme
This is the core theme for Creare Web Solutions websites. Creates a child theme when activated in the Wordpress dashboard.

### Changelog
#### 9/7/2022
- Tweaked the testimonials "plugin" on the develop_hbs branch
#### v2.1.5
- Updates for Bootstrap 5
- Tweaks made during the YB Fire buildout 
- More template parts for various components
#### v2.1.4
- Updates to portfolio plugin. More options to include taxonomies and adjust number of posts per page on archive.
#### v2.0.1
- Try to get my shit together and tag versions; 
- This was the copy of the theme from the Zenas build; it got mashed together with the Bedrock git repo; this is supposed to pull it back out and make it on track again
#### v1.2.1
- Added support for "lazy loading" of Yoast SEO Local Google maps. This little nugget should be published as a gist and written about in a blog.
- Filter autoptimize plugin JS ignore files when doing a map.
- Added a simple way to add Google fonts to the child.

#### v1.2
Broke out template parts a bit more for easier child theme flexibility.


#### v1.1
Made numerous updates to Bric theme to incorporate more accessibility support including:

- aria-label where need be (like in social media icons)
- use clipping technique to hide form labels instead of "display:none"
- use "main" tag to denote the middle of the page
- Add aria-role to header, nav, main, footer for redundancy
- Auto-fill alt tags of images if not present, falling back on the 1) caption, 2) title

Other Bric theme updates:

- Fixed overflowing images when browser scrollbar is present (fixes the horizontal scroll)
- Add controls in customizer for
- Logo max width
- Breakpoint for non-collapse navigation
- Entry content class
- Modify how the customizer renders carousel changes (still not perfect)

=======
>>>>>>> ac7d4a59cf929169f3607ab89919b2e53a545ac8
