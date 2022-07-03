<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Steam_Scraper
 * @subpackage Steam_Scraper/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Steam_Scraper
 * @subpackage Steam_Scraper/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Steam_Scraper_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Steam_Scraper_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Steam_Scraper_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/steam-scraper-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Steam_Scraper_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Steam_Scraper_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/steam-scraper-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, "steamscraper", array(
			'adminajax' => admin_url("admin-ajax.php"),
			'nonce' => wp_create_nonce( "steamnonce" )
		) );

	}

	function post_meta_boxes(){
		add_meta_box( "steam_scraper", "Steam scraper", [$this, "steam_scraper_callback"], "post", "advanced", "high" );
	}

	function steam_scraper_callback($post){
		$value = get_post_meta($post->ID, 'steam_scraper_url', true);
		echo '<input type="url" name="steam_scraper_url" placeholder="Steam URL" class="widefat" value="'.$value.'">';
		echo '<button style="margin-top: 10px;" class="button-secondary" id="scrap_steam">Scrape</button>';
	}

	function save_post_meta($post_id){
		if(isset($_POST['steam_scraper_url'])){
			update_post_meta($post_id, 'steam_scraper_url', $_POST['steam_scraper_url']);
		}
	}

	function get_steam_source(){
		if(!wp_verify_nonce( $_GET['nonce'], "steamnonce" )){
			die("Invalid Request!");
		}

		if(isset($_GET['url'])){

			$url = esc_url_raw( $_GET['url'] );

			$response = wp_remote_get( esc_url_raw( $url ) );
			if( is_array( $response ) && ! is_wp_error( $response ) ) {
				$response = wp_remote_retrieve_body( $response );

				echo json_encode(array("success" => $response));
			}else{
				echo json_encode(array("error" => $response));
			}
			
			die;
		}
	}

	function frontend_styles(){
		?>
		<style>
			div.bb_table {
				display: table;
				font-size: 12px;
				border-collapse: collapse;
			}
			div.bb_table div.bb_table_tr {
				display: table-row;
			}
			div.bb_table div.bb_table_th {
				display: table-cell;
				font-weight: bold;
				border: 1px solid #4d4d4d;
				padding: 4px;
			}
		</style>
		<?php
	}
}
