<?php
/**
 * Report Cards Student Report Cards Widget
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 */

// Count user reports first
$user_report_count = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'reportcardfile',
	'count' => TRUE,
	'owner_guid' => elgg_get_logged_in_user_guid(),
));

if ($user_report_count >= 1) {
	$banner_enable = elgg_get_plugin_setting('banner_enable', 'reportcards') == 'yes';
	
	if ($banner_enable) {
		echo elgg_view('reportcards/banner');
	}

	echo elgg_view('modules/genericmodule', array(
		'view' => 'reportcards/modules/reportcards',
		'module_id' => 'reportcards-module',
		'view_vars' => array(
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'display' => 'latest',
			'period' => 'all',
			'year' => 'all',
		), 
	));
}