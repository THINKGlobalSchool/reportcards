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

$import_directory = get_input('import_directory', FALSE);

// Check if directory exists, create it otherwise
if (!file_exists($import_directory)) {
	if (mkdir($import_directory)) {
		system_message(elgg_echo('reportcards:success:createimportdirectory'));
	} else {
		register_error(elgg_echo('reportcards:error:createimportdirectory'));
	}
}

if (is_dir($import_directory)) {
	system_message(elgg_echo('reportcards:success:importdirectory'));
	elgg_set_plugin_setting('import_directory', $import_directory, 'reportcards');
} else {
	register_error(elgg_echo('reportcards:error:importdirectory'));
}

forward(REFERER);