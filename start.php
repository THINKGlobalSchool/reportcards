<?php
/**
 * Report Cards Start
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

elgg_register_event_handler('init', 'system', 'reportcards_init');

// Report Cards Init
function reportcards_init() {
	// Register and load library
	elgg_register_library('reportcards', elgg_get_plugins_path() . 'reportcards/lib/reportcards.php');
	elgg_load_library('reportcards');
	
	// Register CSS
	$rc_css = elgg_get_simplecache_url('css', 'reportcards/css');
	elgg_register_simplecache_view('css/reportcards/css');	
	elgg_register_css('elgg.reportcards', $rc_css);
	
	// Register tag dashboards JS library
	$nm_js = elgg_get_simplecache_url('js', 'reportcards/reportcards');
	elgg_register_simplecache_view('js/reportcards/reportcards');	
	elgg_register_js('elgg.reportcards', $nm_js);
	
	// Pagesetup event handler
	elgg_register_event_handler('pagesetup','system','reportcards_pagesetup');
	
	elgg_extend_view('tgstheme/modules/profile', 'reportcards/modules/student');
	
	// Register 'reportcards' page handler
	elgg_register_page_handler('reportcards', 'reportcards_page_handler');
	
	// Register run once for report cards for initial setup
	run_function_once("reportcards_run_once");
	
	// Register actions
	$action_base = elgg_get_plugins_path() . 'reportcards/actions/reportcards';
	elgg_register_action('reportcards/import_settings', "$action_base/import_settings.php", 'admin');
	elgg_register_action('reportcards/import', "$action_base/import.php", 'admin');
	elgg_register_action('reportcards/reset', "$action_base/reset.php", 'admin');

	//elgg_load_css('elgg.reportcards');
	//elgg_load_js('elgg.reportcards');
}

/* Report Cards Page Handler */
function reportcards_page_handler($page) {
	switch($page[0]) {
		case 'debug':
			if (!elgg_is_admin_logged_in()) {
				forward();
			}
			$title = "Report Cards Debug";
			$content = elgg_view_title($title);
			$test_directory = elgg_get_plugins_path() . 'reportcards/test_data/';
			$test_file = $test_directory . "test_import.xml";
			//$content .= reportcards_import_from_file($test_file, $test_directory, TRUE);
			//reportcards_reset();
			break;
		case 'download':
			set_input('guid', $page[1]);
			$pages_path = elgg_get_plugins_path() . 'reportcards/pages/reportcards';
			include "$pages_path/download.php";
			break;
		default:
			forward();
			break;
	}

	$params['content'] = $content;

	$body = elgg_view_layout('one_column', $params);
	echo elgg_view_page($title, $body);
}

/**
* Pagesetup event handler
* 
* @return NULL
 */
function reportcards_pagesetup() {
	// Admin menu item(s)
	if (elgg_in_context('admin')) {
		elgg_register_admin_menu_item('administer', 'import', 'reportcards');
		elgg_register_admin_menu_item('administer', 'manage', 'reportcards');
	}
}

/**
 * Run once for report cards
 *
 * @return void
 */
function reportcards_run_once() {
	// Register todo submission file class
	add_subtype("object", "reportcardfile", "ElggFile");
}