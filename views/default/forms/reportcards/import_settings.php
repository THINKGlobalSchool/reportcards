<?php
/**
 * Report Cards Admin Import Settings Form
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
// Get elgg's dataroot
$dataroot = elgg_get_config('dataroot');

$import_directory = elgg_get_plugin_setting('import_directory', 'reportcards');

if (!$import_directory) {
	elgg_add_admin_notice('import_directory_unset', elgg_echo('reportcards:import_directory_unset'));
	$import_directory = $dataroot;
} else if (!is_dir($import_directory)) {
	elgg_add_admin_notice('import_directory_invalid', elgg_echo('reportcards:import_directory_invalid'));
} else {
	elgg_delete_admin_notice('import_directory_invalid');
	elgg_delete_admin_notice('import_directory_unset');
}

$directory_input = elgg_view('input/text', array(
	'name' => 'import_directory',
	'value' => $import_directory,
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit',
	'value' => elgg_echo('save')
));

$content = <<<HTML
	<div>
		$directory_input
	</div>
	<div>
		$submit_input
	</div>
HTML;

echo $content;