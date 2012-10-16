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
	// Relationship for reportcard files to import container
	define(REPORTCARD_IMPORT_RELATIONSHIP, 'belongs_to_reportcard_import');
	
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
	
	// Extend student homepage module
	elgg_extend_view('tgstheme/modules/profile', 'reportcards/student');
	
	// Extend parent child profile module
	elgg_extend_view('parentportal/module/profile', 'reportcards/parent');
	
	// Register 'reportcards' page handler
	elgg_register_page_handler('reportcards', 'reportcards_page_handler');
	
	// Register URL handlers
	elgg_register_entity_url_handler('object', 'reportcard_import_container', 'reportcards_import_url');
	elgg_register_entity_url_handler('object', 'reportcardfile', 'reportcards_file_url');
	
	// Reportcards entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'reportcards_setup_entity_menu', 999);
	
	// Reportcard file icon override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'reportcards_file_icon_url_override');

	// Register run once for report cards for initial setup
	run_function_once("reportcards_run_once");
	
	// Register actions
	$action_base = elgg_get_plugins_path() . 'reportcards/actions/reportcards';
	elgg_register_action('reportcards/import_settings', "$action_base/import_settings.php", 'admin');
	elgg_register_action('reportcards/import', "$action_base/import.php", 'admin');
	elgg_register_action('reportcards/reset', "$action_base/reset.php", 'admin');
	elgg_register_action('reportcards/reportimport/delete', "$action_base/reportimport/delete.php", 'admin');
	elgg_register_action('reportcards/reportimport/edit', "$action_base/reportimport/edit.php", 'admin');

	elgg_load_css('elgg.reportcards');
	elgg_load_js('elgg.reportcards');

	// Ajax whitelist
	elgg_register_ajax_view('reportcards/modules/reportcards');
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
 * Populates the ->getUrl() method for reportcard import entities
 *
 * @param ElggObject entity
 * @return string request url
 */
function reportcards_import_url($entity) {
	return elgg_get_site_url() . 'admin/reportcards/viewimport?guid=' . $entity->guid;
}

/**
 * Populates the ->getUrl() method for reportcard file entities
 *
 * @param ElggObject entity
 * @return string request url
 */
function reportcards_file_url($entity) {
 	return elgg_get_site_url() . 'reportcards/download/' . $entity->guid;
}

/**
 * Override the default entity icon for reportcard files
 *
 * @return string Relative URL
 */
function reportcards_file_icon_url_override($hook, $type, $returnvalue, $params) {
	$file = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($file, 'object', 'reportcardfile')) {
		if ($size == 'large') {
			$ext = '_lrg';
		} else {
			$ext = '';
		}
		
		$url = "mod/reportcards/graphics/pdf{$ext}.gif";
		return $url;
	}
}

/**
 * Reportcards entity plugin hook
 */
function reportcards_setup_entity_menu($hook, $type, $return, $params) {

	$entity = $params['entity'];
	
	if (elgg_instanceof($entity, 'object', 'reportcardfile')) {
		return $return;
	}
	
	if (!elgg_instanceof($entity, 'object', 'reportcard_import_container')) {
		return $return;
	}

	$return = array();

	$options = array(
		'name' => 'edit',
		'text' => elgg_echo('edit'),
		'href' => elgg_get_site_url() . 'admin/reportcards/editimport?guid=' . $entity->guid,
		'priority' => 2,
	);
	$return[] = ElggMenuItem::factory($options);
	
	$options = array(
		'name' => 'delete',
		'text' => elgg_view_icon('delete'),
		'title' => elgg_echo('delete:this'),
		'href' => "action/{$params['handler']}/delete?guid={$entity->getGUID()}",
		'confirm' => elgg_echo('deleteconfirm'),
		'priority' => 3,
	);

	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Run once for report cards
 *
 * @return void
 */
function reportcards_run_once() {
	// Register reportcardfile submission file class
	add_subtype("object", "reportcardfile", "ElggFile");
}