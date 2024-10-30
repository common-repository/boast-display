<?php

class BoastDisplay {
  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   */
  protected $loader;
  
  /**
   * The unique identifier of this plugin
   */
  protected $plugin_name;

  /**
   * The current version of the plugin
   */
  protected $version;

  public function __construct() {
    $this->plugin_name = 'Boast Display';
    $this->version = '1.2.2';

    $this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
  }

  /**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - BoastDisplayLoader. Orchestrates the hooks of the plugin.
	 * - BoastDisplayAdmin. Defines all hooks for the admin area.
	 * - BoastDisplayPublic. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   */
  private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/boast-display-loader.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/boast-display-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/boast-display-public.php';
		$this->loader = new BoastDisplayLoader();
	}

  /**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 */
	private function define_admin_hooks() {
		$boast_display_admin = new BoastDisplayAdmin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $boast_display_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $boast_display_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_menu', $boast_display_admin, 'add_menu' );
    $this->loader->add_action( 'admin_menu', $boast_display_admin, 'init_settings' );
	}

  /**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 */
	private function define_public_hooks() {
		$boast_display_public = new BoastDisplayPublic( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $boast_display_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $boast_display_public, 'enqueue_scripts' );
    $this->loader->add_shortcode( 'boast_display', $boast_display_public, 'display_boasts' );
	}

  /**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 */
	public function get_version() {
		return $this->version;
	}
}
