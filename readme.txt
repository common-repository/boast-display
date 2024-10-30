=== Boast Display ===
Contributors: joshuamileswa
Donate link: https://webascender.com
Tags: boast, reviews
Requires at least: 4.7
Tested up to: 4.8
Stable tag: 1.2.2
License: GPLv2 or later
License URI: https://gnu.org/licenses/gpl-2.0.html

Display Boasts on your Wordpress site to help customer confidence and SEO.

== Description ==

The Boast Display Wordpress Plugin allows Boast subscribers to display Boasts they have collected on their Wordpress site with ease.

== Installation ==

To upload the Boast Display Plugin .zip file:

1. Upload the Boast Display Plugin to the /wp-contents/plugins/ folder.
2. Activate the plugin from the "Plugins" menu.
3. Create a Boast account at <a href="https://secure.boast.io/users/sign_up" target="_blank">https://secure.boast.io/users/sign_up</a>
4. Navigate to "Settings" -> "Boast Display" and enter your JSON feed URL.

To auto install the Boast Display Plugin from the Wordpress admin:

1. Navigate to "Plugins" -> "Add New"
2. Search for "Boast Display" and click "Install Now" for the "Boast Display" listing.
3. Activate the plugin from the "Plugins" menu.
4. Create a Boast account at <a href="https://secure.boast.io/users/sign_up" target="_blank">https://secure.boast.io/users/sign_up</a>
5. Navigate to "Settings" -> "Boast Display" and enter your JSON feed URL.

= Example Shortcodes =
``
[boast_display]
``

### Options
#### show_count
Type: `Integer`
Default: `3`

Specify how many boasts to show

Example:
```
[boast_display show_count=4]
```

#### slide_timeout
Type: `Integer`
Default: `10`

Specify how long (in seconds) the display should stay on one boast\
If the value is <= 0, the slide will never switch automatically

Example:
```
[boast_display slide_timeout=5]
```

#### photos_only
Type: `Boolean`
Default: `false`

Show only Boasts that have photos

Example:
```
[boast_display photos_only=true]
```

#### videos_only
Type: `Boolean`
Default: `false`

Show only Boasts that have videos

Example:
```
[boast_display videos_only=true]
```

#### photos_and_videos_only
Type: `Boolean`
Default: `false`

Show only Boasts that have videos or photos

Example:
```
[boast_display photos_and_videos_only=true]
```

#### campaign_key
Type: `String`
Default: `""`

Show only Boasts that match the specified campaign key.
Multiple campaign keys can be entered by separating them with commas.

Example:
```
[boast_display campaign_key="1A2B3C,Another Key"]
```

#### tags
Type: `String`
Default: `""`

Show only Boasts that have at least one of the specified tags.
Multiple tags can be entered by separating them with commas.

Example:
```
[boast_display tags="tag1,tag-2"]
```

#### minimum_star_rating
Type: `Integer`
Default: `0`

Show only Boasts that have a rating greater than or equal to the specified amount

Example:
```
[boast_display minimum_star_rating=3]
```

#### custom_styles
Type: `String`
Default: `""`

Add inline styles to the wrapper div. Works well for background color and border styles.
Example:
```
[boast_display custom_styles="background-color:blue;border-color:white;"]
```

#### auto_height
Type: `Boolean`
Default: `false`

Automatically adjust height of the container to the height of the current card.

Example:
```
[boast_display auto_height=true]
```

== Frequently asked questions ==

= Where can I find my JSON Feed URL? =

You can find your Boast JSON Feed Url [here](https://secure.boast.io/embed/show_kudos "Display Boasts") by scrolling to the embed code section and copying the link to the JSON feed.

= Where can I use this? =

You can use the shortcode to display the plugin anywhere you can normally use shortcodes on your site.

= Are there additional settings? =

No. All settings are listed above.

== Changelog ==

Version 1.2.2

Fix closing div on show count == 1

Version 1.2.1

Fix auto_height not working on click

Version 1.2

Add auto_height feature

Version 1.1.1

Fix errors for reviews without ratings and other small bugs

Version 1.1

Add review star metadata

Version 1.0

== Upgrade Notice ==

Version 1.1
