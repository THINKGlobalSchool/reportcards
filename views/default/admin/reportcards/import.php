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
echo elgg_view_form('reportcards/import_settings');

// Display import dirctory info
if ($import_directory = elgg_get_plugin_setting('import_directory', 'reportcards')) {
	$header = elgg_view_title(elgg_echo('reportcards:label:directoryinfo'));
	$import_form = elgg_view_form('reportcards/import', array(), array('import_directory' => $import_directory));
	
	$content = <<<HTML
		<br />
		$header
		$import_form
		<div id='reportcards-import-output'>
		</div>
HTML;

	echo $content;
}