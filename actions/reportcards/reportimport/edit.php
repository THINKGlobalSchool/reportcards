<?php
/**
 * Report Cards Import Edit Action
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$guid = get_input('import_guid');

$import = get_entity($guid);

$title = get_input('title');

// Check inputs
if (!$title) {
	register_error(elgg_echo('reportcards:error:requiredfields'));
	forward(REFERER);
}

if (elgg_instanceof($import, 'object', 'reportcard_import_container')) {
	$import->title = $title;

	if ($import->save()) {
		// Clear Sticky form
		elgg_clear_sticky_form('reportcards-import-edit-form');
		system_message(elgg_echo('reportcards:success:editreportimport'));
		forward('admin/reportcards/manage');
	} else {
		register_error(elgg_echo('reportcards:error:editreportimport'));
		forward(REFERER);
	}
} else {
	register_error('reportcards:error:invalidreportimport');
	forward('admin/reportcards/manage');
}