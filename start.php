<?php
/**
 * Report Cards Start
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
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
	elgg_register_css('elgg.reportcards', $rc_css);
	
	// Register tag dashboards JS library
	$nm_js = elgg_get_simplecache_url('js', 'reportcards/reportcards');
	elgg_register_js('elgg.reportcards', $nm_js);
	
	// Pagesetup event handler
	elgg_register_event_handler('pagesetup','system','reportcards_pagesetup');
	
	// Register 'reportcards' page handler
	elgg_register_page_handler('reportcards', 'reportcards_page_handler');
	
	// Register URL handler
	elgg_register_plugin_hook_handler('entity:url', 'object', 'reportcards_url_handler');
	
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
	elgg_register_action('reportcards/remote_copy', "$action_base/remote_copy.php", 'admin');
	elgg_register_action('reportcards/reset', "$action_base/reset.php", 'admin');
	elgg_register_action('reportcards/banner', "$action_base/banner.php", 'admin');
	elgg_register_action('reportcards/delete', "$action_base/delete.php", 'admin');
	elgg_register_action('reportcards/reportimport/delete', "$action_base/reportimport/delete.php", 'admin');
	elgg_register_action('reportcards/reportimport/edit', "$action_base/reportimport/edit.php", 'admin');

	// Register roles widgets 
	elgg_register_widget_type('reportcards_student', elgg_echo('reportcards:widget:student_title'), elgg_echo('reportcards:widget:student_desc'), 'rolewidget');

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

	// We're only going to extend views if we're in a context where report cards should appear
	if (elgg_in_context('home') || elgg_in_context('parentportal')) {
		// Count reportcard imports
		$imports_count = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'reportcard_import_container',
			'count' => TRUE,
		));
		
		// Only display modules if we have reportcard imports
		if ($imports_count) {
			// Load CSS/JS
			elgg_load_css('elgg.reportcards');
			elgg_load_js('elgg.reportcards');
			elgg_load_js('jquery.easing');

			$parent_role_guid = elgg_get_plugin_setting('parents_role','parentportal');
			$parent_role = get_entity($parent_role_guid);

			$banner_enable = elgg_get_plugin_setting('banner_enable', 'reportcards') == 'yes';
			$show_for_report = elgg_get_plugin_setting('banner_current_report', 'reportcards');

			if ($show_for_report) {
				$children = parentportal_get_parents_children(elgg_get_logged_in_user_guid());

				$child_guids = array();

				foreach ($children as $child) {
					$child_guids[] = $child->guid;
				}

				$ia = elgg_get_ignore_access();
				elgg_set_ignore_access(TRUE);
				$current_report_count = elgg_get_entities_from_relationship(array(
					'type' => 'object',
					'subtype' => 'reportcardfile',
					'count' => TRUE,
					'owner_guids' => $child_guids,
					'relationship' => REPORTCARD_IMPORT_RELATIONSHIP,
					'relationship_guid' => $show_for_report,
					'inverse_relationship' => true
				));
				elgg_set_ignore_access($ia);

				if (!$current_report_count) {
					$banner_enable &= FALSE;
				}
			}

			// Extend home page content for parents
			if (elgg_instanceof($parent_role, 'object', 'role') && $parent_role->isMember() && $banner_enable) {
				set_input('show_parent_link', true);
				elgg_extend_view('roles/dashboard/content', 'reportcards/banner');
			}
		}
	}
}

/**
 * Returns the URL from report cards entities
 *
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    The current URL
 * @param array  $params Hook parameters
 * @return string
 */
function reportcards_url_handler($hook, $type, $url, $params) {
	$entity = $params['entity'];

	// Check that the entity is a report card related object
	if (!elgg_instanceof($entity, 'object', 'reportcardfile')) {
		return "reportcards/download/{$entity->guid}";
	} else if (elgg_instanceof($entity, 'object', 'reportcard_import_container')) {
		return "admin/reportcards/viewimport?guid={$entity->guid}";
	} else {
		return;
	}
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
		$return = array();

		// Only add stats items in admin context
		if (elgg_in_context('admin')) {
			// Annotation options
			$options = array(
				'guid' => $entity->guid,
				'type' => 'object',
				'subtype' => 'reportcardfile',
				'annotation_name' => 'reportcard_view',
				'limit' => 0,
				'count' => TRUE,
			);

			// Total view count
			$total_view_count = elgg_get_annotations($options);

			// Group by owner_guid foir unique viewers
			$options['group_by'] = 'n_table.owner_guid';
			$options['count'] = FALSE;

			// Unique view count
			$views = elgg_get_annotations($options);

			$unique_view_count = count($views);
			
			// Add total views
			$options = array(
				'name' => 'total_views',
				'text' => elgg_echo('reportcards:label:totalviews') . ": $total_view_count",
				'href' => FALSE,
				'priority' => 1,
				'section' => 'info',
			);
			$return[] = ElggMenuItem::factory($options);

			// Add unique views
			$options = array(
				'name' => 'unique_view',
				'text' => elgg_echo('reportcards:label:uniqueviews') . ": $unique_view_count",
				'href' => FALSE,
				'priority' => 2,
				'section' => 'info',
			);
			$return[] = ElggMenuItem::factory($options);

			// Add view statistics
			$options = array(
				'name' => 'statistics',
				'text' => elgg_echo('reportcards:label:viewstatistics'),
				'href' => elgg_get_site_url() . 'admin/reportcards/statistics?guid=' . $entity->guid,
				'priority' => 3,
				'section' => 'info',
			);
			$return[] = ElggMenuItem::factory($options);

			// delete link
			$options = array(
				'name' => 'delete',
				'text' => elgg_view_icon('delete'),
				'title' => elgg_echo('delete:this'),
				'href' => "action/reportcards/delete?guid={$entity->getGUID()}",
				'confirm' => elgg_echo('deleteconfirm'),
				'priority' => 300,
				'section' => 'info',
			);
			$return[] = ElggMenuItem::factory($options);
		}

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