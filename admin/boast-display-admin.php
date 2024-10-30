<?php

class BoastDisplayAdmin {

  private $plugin_name; ///< The ID of this plugin
  private $version; ///< The version of this plugin

  public function __construct($plugin_name, $version) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
	 * Register the stylesheets for the admin area.
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/boast-display-admin.css', array(), $this->version, 'all' );
  }

  /**
	 * Register the JavaScript for the admin area.
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/boast-display-admin.js', array( 'jquery' ), $this->version, false );
  }

  /**
   * Add top level menu
   */
  public function add_menu() {
    add_options_page( $this->plugin_name, $this->plugin_name, 'manage_options', "boast-display-settings", array($this, "display_content"));
  }

  /**
   * Displays the page content
   */
  public function display_content() {
    echo <<<HTML
<div class="wrap">
  <h2>Boast Display Settings</h2>
  <form action="options.php" method="POST">
HTML;

    settings_fields('boast-settings-group');
    do_settings_sections('boast-display-settings');
    submit_button();

    echo <<<HTML
  </form>
  <h2>Example shortcode</h2>
  <p><code>[boast_display]</code></p>

  <h2>Options</h2>
  <h4>show_count</h4>
  <p>
    Type: <code>Integer</code><br>
    Default: 3
  </p>
  <p>Specify how many boasts to show.</p>
  <p>Example:</p>
  <p><code>[boast_display show_count=4]</code></p>

  <h4>slide_timeout</h4>
  <p>
    Type: <code>Integer</code><br>
    Default: 10
  </p>
  <p>
    Specify how long (in seconds) the display should stay on one boast.<br>
    If the value is &#8804; 0, the slide will never switch automatically.
  </p>
  <p>Example:</p>
  <p><code>[boast_display slide_timeout=5]</code></p>

  <h4>photos_only</h4>
  <p>
    Type: <code>Boolean</code><br>
    Default: false
  </p>
  <p>Show only Boasts that have photos.</p>
  <p>Example:</p>
  <p><code>[boast_display photos_only=true]</code></p>

  <h4>videos_only</h4>
  <p>
    Type: <code>Boolean</code><br>
    Default: false
  </p>
  <p>Show only Boasts that have videos.</p>
  <p>Example:</p>
  <p><code>[boast_display videos_only=true]</code></p>

  <h4>photos_and_videos_only</h4>
  <p>
    Type: <code>Boolean</code><br>
    Default: false
  </p>
  <p>Show only Boasts that have photos or videos.</p>
  <p>Example:</p>
  <p><code>[boast_display photos_and_videos_only=true]</code></p>

  <h4>campaign_key</h4>
  <p>
    Type: <code>String</code><br>
    Default: ""
  </p>
  <p>
    Show only Boasts that match the specified campaign key.<br>
    Multiple campaign keys can be entered by separating them with commas.<br>
  </p>
  <p>Example:</p>
  <p><code>[boast_display campaign_key="1A2B3C,Another Key"]</code></p>

  <h4>tags</h4>
  <p>
    Type: <code>String</code><br>
    Default: ""
  </p>
  <p>
    Show only Boasts that have at least one of the specified tags.<br>
    Multiple tags can be entered by separating them with commas.<br>
  </p>
  <p>Example:</p>
  <p><code>[boast_display tags="tag1,tag-2"]</code></p>

  <h4>minimum_star_rating</h4>
  <p>
    Type: <code>Integer</code><br>
    Default: 0
  </p>
  <p>
    Show only Boasts that have a rating greater than or equal to the specified amount
  </p>
  <p>Example:</p>
  <p><code>[boast_display minimum_star_rating=3]</code></p>

  <h4>custom_styles</h4>
  <p>
    Type: <code>String</code><br>
    Default: ""
  </p>
  <p>
    Add inline styles to the wrapper div. Works well for background color and border styles.
  </p>
  <p>Example:</p>
  <p><code>[boast_display custom_styles="background-color:blue;border-color:white;"]</code></p>

  <h4>auto_height</h4>
  <p>
    Type: <code>Boolean</code><br>
    Default: false
  </p>
  <p>
    Automatically adjust height of the container to the height of the current card.
  </p>
  <p>Example:</p>
  <p><code>[boast_display auto_height=true]</code></p>
</div>
HTML;
  }

  public function init_settings() {
    register_setting( 'boast-settings-group', 'json-feed-url');
    register_setting( 'boast-settings-group', 'business-name');
    add_settings_section('general', 'General', array($this, 'general_section_callback'), 'boast-display-settings');
    add_settings_field('url', 'JSON Feed Url', array($this, 'json_field_callback'), 'boast-display-settings', 'general');
    add_settings_field('text', 'Business Name', array($this, 'name_field_callback'), 'boast-display-settings', 'general');
  }

  public function general_section_callback() {
  }

  public function json_field_callback() {
    $value = esc_attr(get_option('json-feed-url'));
    echo "<input type='text' name='json-feed-url' value='$value' class='large-text' />";
    echo "<br><p>You can find your Boast JSON Feed Url <a href='https://secure.boast.io/embed/show_kudos' target='_blank'>here</a> by scrolling to the embed code section and copying the link to the JSON feed.</p>";
  }

  public function name_field_callback() {
    $value = esc_attr(get_option('business-name'));
    echo "<input type='text' name='business-name' value='$value' class='large-text' />";
    echo "<br><p>Add your business name for displaying Boast reviews in search results</p>";
    echo "<p>Note: Reviews will not appear in search results without a business name</p>";
  }
}
