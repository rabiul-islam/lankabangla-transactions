<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://selise.ch/
 * @since      1.0.0
 *
 * @package    Lankabangla_Transactions
 * @subpackage Lankabangla_Transactions/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Lankabangla_Transactions
 * @subpackage Lankabangla_Transactions/includes
 * @author     Selise Team (ITSM) <rabiul.islam@selise.ch>
 */
class Lankabangla_Transactions_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'lankabangla-transactions',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
