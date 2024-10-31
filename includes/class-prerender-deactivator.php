<?php

/**
 * Fired during plugin deactivation
 *
 * @link        https://vorster.cloud/
 * @since      1.0.0
 *
 * @package    Prerender
 * @subpackage Prerender/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Prerender
 * @subpackage Prerender/includes
 * @author     Michael Vorster <michael@vorster.cloud>
 */
class Prerender_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function deactivate() {
		$prerender_options = get_option('prerender');
		$options = array(
			'prerender-enable' => 0,
			'prerender-token' => $prerender_options['prerender-token']
		);
		update_option('prerender', $options);

		$htaccess_file = get_home_path() . '.htaccess';

		if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
			$htaccess = file_get_contents($htaccess_file);

			// remove everything
			$pattern = "/#\s?BEGIN\s?Prerender.*?#\s?END\s?Prerender/s";
			//only remove if the pattern is there at all
			if (preg_match($pattern, $htaccess)) $htaccess = preg_replace($pattern, "", $htaccess);

			$htaccess = preg_replace("/\n+/", "\n", $htaccess);
      file_put_contents($htaccess_file, $htaccess);
		}
	}

}
