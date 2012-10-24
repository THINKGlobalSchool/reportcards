<?php
/**
 * Report Cards Admin Import
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
elgg_load_js('elgg.reportcards');

// Display import settings form
$import_directory_title = elgg_echo('reportcards:label:directory');
$import_directory_form = elgg_view_form('reportcards/import_settings');

echo elgg_view_module('inline', $import_directory_title, $import_directory_form);

// Display import dirctory info and remote copy form
if ($import_directory = elgg_get_plugin_setting('import_directory', 'reportcards')) {
	$remote_copy_title = elgg_echo('reportcards:label:remotecopy');
	$remote_copy_form = elgg_view_form('reportcards/remote_copy');

	echo elgg_view_module('inline', $remote_copy_title, $remote_copy_form);
	echo "<div id='reportcards-remote-output'></div>";
	
	$import_form_title = elgg_echo('reportcards:label:directoryinfo');
	$import_form = elgg_view_form('reportcards/import', array(), array('import_directory' => $import_directory));
	
	echo elgg_view_module('inline', $import_form_title, $import_form);
	echo "<div id='reportcards-import-output'></div>";
}