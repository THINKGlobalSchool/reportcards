<?php
/**
 * Report Cards Student Module
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

// Create group module				
$module = elgg_view('modules/genericmodule', array(
	'view' => 'reportcards/modules/reportcards',
	'module_id' => 'reportcards-module',
	'view_vars' => array(
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'display' => 'latest',
		'period' => 'all',
		'year' => 'all',
	), 
));

$options = array(
	'class' => 'tgstheme-module',
);

echo "<div class='reportcard-home-module'>" . elgg_view_module('featured', elgg_echo('reportcards:label:reportcards'), $module, $options) . "</div>";