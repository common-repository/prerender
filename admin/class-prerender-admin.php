<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link        https://vorster.cloud/
 * @since      1.0.0
 *
 * @package    Prerender
 * @subpackage Prerender/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Prerender
 * @subpackage Prerender/admin
 * @author     Michael Vorster <michael@vorster.cloud>
 */
class Prerender_Admin {

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
		$this->prerender_options = get_option($this->plugin_name);

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
		 * defined in Prerender_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Prerender_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), '4.3.1', 'all' );
		wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/prerender-admin.css', array(), $this->version, 'all' );

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
		 * defined in Prerender_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Prerender_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/prerender-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	* Register the administration menu for this plugin into the WordPress Dashboard menu.
	*
	* @since 1.0.0
	*/

	public function add_plugin_admin_menu() {
		add_options_page( 'Prerender.io Settings', 'Prerender', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}

	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}

	public function display_plugin_setup_page() {
		$prerenderEnable = $this->prerender_options['prerender-enable'];
		$prerenderToken = $this->prerender_options['prerender-token'];

		include_once( 'partials/prerender-admin-display.php' );
	}

	/**
	 * Validate token provided against Prerender.io Recache API
	 * 
	 */

	public function validateToken($token) {
		$body = array(
			"prerenderToken" => $token,
			"url" => home_url()
		);

		$args = array(
			'body' => $body
		);

		$response = wp_remote_post('https://api.prerender.io/recache', $args);

		if(empty(wp_remote_retrieve_body($response))) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Compile .htaccess rules with valid Prerender Token
	 * 
	 */

	public function get_prerender_rules() {
		$site_url = get_site_url();
		
		$rules = "" . "# BEGIN Prerender Service" . "\n";
		$rules .= "<IfModule mod_headers.c>" . "\n";
		$rules .= "\t" . "RequestHeader set X-Prerender-Token \"" . $this->prerender_options['prerender-token'] ."\"" . "\n";
		$rules .= "\t" . "RequestHeader set X-Prerender-Version \"prerender-apache@2.0.0\"" . "\n";
		$rules .= "</IfModule>" . "\n";
		$rules .= "\n";
		$rules .= "<IfModule mod_rewrite.c>" . "\n";
		$rules .= "\t" . "RewriteEngine On" . "\n";
		$rules .= "\t" . "<IfModule mod_proxy_http.c>" . "\n";
		$rules .= "\t" . "\t" . "RewriteCond %{HTTP_USER_AGENT} googlebot|bingbot|yandex|baiduspider|facebookexternalhit|twitterbot|rogerbot|linkedinbot|embedly|quora\ link\ preview|showyoubot|outbrain|pinterest\/0\.|pinterestbot|slackbot|vkShare|W3C_Validator|whatsapp [NC,OR]" . "\n";
		$rules .= "\t" . "\t" . "RewriteCond %{QUERY_STRING} _escaped_fragment_" . "\n";
		$rules .= "\t" . "\t" . "RewriteCond %{HTTP:X-Prerender} !1" . "\n";
		$rules .= "\t" . "\t" . "RewriteCond %{REQUEST_URI} ^(?!.*?(\.js|\.css|\.xml|\.less|\.png|\.jpg|\.jpeg|\.gif|\.pdf|\.doc|\.txt|\.ico|\.rss|\.zip|\.mp3|\.rar|\.exe|\.wmv|\.doc|\.avi|\.ppt|\.mpg|\.mpeg|\.tif|\.wav|\.mov|\.psd|\.ai|\.xls|\.mp4|\.m4a|\.swf|\.dat|\.dmg|\.iso|\.flv|\.m4v|\.torrent|\.ttf|\.woff|\.svg))" . "\n";
		$rules .= "\t" . "\t" . "RewriteRule ^(index\.html|index\.php)?(.*) http://service.prerender.io/" . $site_url . "/$2 [P,END]" . "\n";
		$rules .= "\t" . "</IfModule>" . "\n";
		$rules .= "</IfModule>" . "\n";
		$rules .= "# END Prerender" . "\n\n";

		return $rules;
	}

	/**
	 * Checks if the .htaccess contains the Prerender Service comment
	 * 
	 */

	public function contains_prerender_rules() {
		if (!file_exists($this->get_htaccess_file())) {
			return false;
		}

		$htaccess = file_get_contents($this->get_htaccess_file());

		$check = null;
		preg_match("/BEGIN Prerender Service/", $htaccess, $check);
		if (count($check) === 0) {
				return false;
		} else {
				return true;
		}
	}

	/**
	 * Adds Prerender Service rules to .htaccess file
	 * 
	 */

	public function edit_htaccess() {
		$rules = $this->get_prerender_rules();
		$htaccess = file_get_contents($this->get_htaccess_file());

		if(!$this->contains_prerender_rules()) {
			//insert rules before wordpress part.
			if (strlen($rules) > 0) {
				$wptag = "# BEGIN WordPress";
				if (strpos($htaccess, $wptag) !== false) {
						$htaccess = str_replace($wptag, $rules . $wptag, $htaccess);
				} else {
						$htaccess = $htaccess . $rules;
				}
				file_put_contents($this->get_htaccess_file(), $htaccess);
			}
		}
	}

	/**
	 * Remove all rules added to the .htacces file
	 */

	public function remove_htaccess_edit() {
		if (file_exists($this->get_htaccess_file()) && is_writable($this->get_htaccess_file())) {
			$htaccess = file_get_contents($this->get_htaccess_file());

			// remove everything
			$pattern = "/#\s?BEGIN\s?Prerender.*?#\s?END\s?Prerender/s";
			//only remove if the pattern is there at all
			if (preg_match($pattern, $htaccess)) $htaccess = preg_replace($pattern, "", $htaccess);

			$htaccess = preg_replace("/\n+/", "\n", $htaccess);
      file_put_contents($this->get_htaccess_file(), $htaccess);
		}
	}

	/**
	 * Fetch and return .htaccess file path
	 * 
	 */

	public function get_htaccess_file() {
		return get_home_path() . '.htaccess';
	}

	/**
	 * Fetch and return contents of .htaccess file
	 * 
	 */

	public function get_htaccess_content() {
    $content = file_get_contents($this->get_htaccess_file());

    return $content;
	}
	
	/**
	 * Validate form input data
	 * 
	 */

	public function validate($input) {
		$valid = array();

		//Cleanup
		$valid['prerender-enable'] = (isset($input['prerender-enable']) && !empty($input['prerender-enable'])) ? 1 : 0;
		$valid['prerender-token'] = (isset($input['prerender-token']) && !empty($input['prerender-token'])) ? sanitize_text_field($input['prerender-token']) : '';

		if(!$this->validateToken($valid['prerender-token'])) {
			add_settings_error( 'prerenderToken', 'prerenderTokenTextError', 'Please enter a valid Prerender.io Token.', 'error');
			// Prevent settings being enabled
			$valid['prerender-enable'] = 0;
			if($this->contains_prerender_rules()) {
				$this->remove_htaccess_edit();
			}
		} else {
			if($valid['prerender-enable'] == 1) {
				$this->edit_htaccess();
			} else {
				if($this->contains_prerender_rules()) {
					$this->remove_htaccess_edit();
				}
			}			
		}

		return $valid;
	}

	/**
	 * Update settings on form update
	 * 
	 */

	public function options_update() {
    register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

}
