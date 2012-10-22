<?php
/**
 * Report Cards Parent Module
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$child = $vars['entity'];

// Create group module				
$module = elgg_view('modules/genericmodule', array(
	'view' => 'reportcards/modules/reportcards',
	'module_id' => 'reportcards-module',
	'view_vars' => array(
		'owner_guid' => $child->guid,
		'display' => 'latest',
		'period' => 'all',
		'year' => 'all',
		'parent_mode' => TRUE, // Set parent mode
	), 
));

$options = array(
	'class' => 'reportcard-pp-module',
);

echo elgg_view_module('featured', elgg_echo('reportcards:label:reportcards'), $module, $options);