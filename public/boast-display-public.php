<?php


/**
 * The public-facing functionality of the plugin.
 */
class BoastDisplayPublic {
  private $plugin_name; ///< The ID of this plugin
  private $version; ///< The version of this plugin

  public function __construct($plugin_name, $version) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
	 * Register the stylesheets for the public-facing side of the site.
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/boast-display-public.css', array(), $this->version, 'all' );
  }

  /**
	 * Register the JavaScript for the public-facing side of the site.
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/build/boast-display-public.min.js', array(), $this->version, false );
  }

  public function display_boasts( $atts ) {
    $a = shortcode_atts( array(
      'show_count' => 3,
      'slide_timeout' => 10,
      'photos_only' => false,
      'videos_only' => false,
      'photos_and_videos_only' => false,
      'campaign_key' => "",
      'tags' => "",
      'minimum_star_rating' => 0,
      'custom_styles' => "",
      'auto_height' => false,
    ), $atts );

    $a['campaign_key'] = explode(',', $a['campaign_key']);
    $a['tags'] = explode(',', $a['tags']);

    $url = esc_attr(get_option('json-feed-url'));
    $data = json_decode($this->get_data($url), true);

    foreach ($data as $boast_key => $boast) {
      if ($a['photos_and_videos_only'] && !$boast["has_photo"] && !$boast["has_video"]) {
        unset($data[$boast_key]);
      }

      if ($a['photos_only'] && !$boast["has_photo"]) {
        unset($data[$boast_key]);
      }

      if ($a['videos_only'] && !$boast["has_video"]) {
        unset($data[$boast_key]);
      }

      if ($a['campaign_key'] !== [""] && !in_array($boast["campaign_key"], $a["campaign_key"])) {
        unset($data[$boast_key]);
      }

      if ($a['tags'] !== [""] && array_intersect($boast["tags"], $a["tags"]) === []) {
        unset($data[$boast_key]);
      }

      if ($a['minimum_star_rating'] > $boast['rating']) {
        unset($data[$boast_key]);
      }

      if ($boast['rating'] === null) {
        $boast['rating'] = 0;
      }
    }

    usort($data, function($a, $b) {
      if (isset($b['position_weight']) && isset($a['position_weight'])) {
        return $b['position_weight'] - $a['position_weight'];
      } else {
        return 0;
      }
    });

    $html = $this->present_data($data, $a);
    return $html;
  }

  private function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }

  private function present_data($data, $attr) {
    $show_count = sizeof($data) < $attr['show_count'] ? sizeof($data) : $attr['show_count'];
    $slide_timeout = $attr['slide_timeout'];
    $custom_styles = $attr['custom_styles'];
    $aggregate_rating = 0;
    $total_reviews = 0;

    if ($show_count === 0) {
      return "";
    }

    $html = "<div class='boast-display' data-timeout='$slide_timeout' style='$custom_styles'><div class='cards";
    if ($attr['auto_height']) {
      $html .= " auto_height";
    }
    $html .= "'>";
    $i = 0;
    foreach ($data as $boast_key => $boast) {
      if ($i >= $show_count) {
        break;
      } else {
        $i++;
      }

      $title = $boast["title"];
      $description = $boast["description"];
      $name = $boast["name"];
      $img = $boast["photo_url"];
      $vid = $boast["source_video_url"];
      $thumb = $boast["video_thumb_url"];
      $rating = $boast["rating"];

      if ($rating > 0) {
        $aggregate_rating += $rating;
        $total_reviews++;
      }

      $html .= <<<HTML
<div class="boast-card">
HTML;
      if ($boast["has_video"]) {
        $html .= <<<HTML
<video controls controlsList="nodownload" poster="$thumb">
  <source src="$vid" type="video/mp4">
Your browser does not support video playback
</video>
HTML;
      } elseif ($boast["has_photo"]) {
        $html .= "<img src='$img' alt='boast image' />";
      }
    $html .= <<<HTML
  <div>
    <h3>$title</h3>
    <p>$description</p>
    <p class="name">$name</p>
  </div>
</div>
HTML;
    }

    if ($show_count > 1) {
      $html .= <<<HTML
  </div>
  <div class="selectors">
HTML;
      for ($i = 0; $i < $show_count; $i++) {
        if ($i === 0) {
          $html .= "<span class='selector active' data-index='$i'></span>";
        } else {
          $html .= "<span class='selector' data-index='$i'></span>";
        }
      }
      $html .= <<<HTML
  </div>
</div>
HTML;
    } else {
      $html .= "</div></div>";
    }

    if ($total_reviews > 0) {
      $aggregate_rating = $aggregate_rating / $total_reviews;
      $business_name = esc_attr(get_option('business-name'));

      $html .= <<<HTML
<script type="application/ld+json">
{ "@context": "http://schema.org",
  "@type": "Product",
  "name": "$business_name",
  "aggregateRating":
  {
    "@type": "AggregateRating",
    "ratingValue": "$aggregate_rating",
    "reviewCount": "$total_reviews"
  }
}
</script>
HTML;
    }

    return $html;
  }
}
