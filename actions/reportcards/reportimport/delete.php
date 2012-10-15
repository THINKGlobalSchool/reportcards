<?php
/**
 * Report Cards Import Delete Action
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$guid = get_input('guid');

$import = get_entity($guid);

if (elgg_instanceof($import, 'object', 'reportcard_import_container')) {
	// Get reportcards belonging to this import
	$options = array(
		'type' => 'object',
		'subtype' => 'reportcardfile',
		'relationship' => REPORTCARD_IMPORT_RELATIONSHIP,
		'relationship_guid' => $import->guid,
		'inverse_relationship' => true,
	);
	
	$reportcards = new ElggBatch('elgg_get_entities_from_relationship', $options);
	
	$success = TRUE;
	// Delete each report associate with this import
	foreach ($reportcards as $report) {
		$success &= $report->delete();
	}

	// No prob deleting reports, nuke the import
	if ($success && $import->delete()) {
		system_message(elgg_echo('reportcards:success:deleteimport'));
	} else {
		register_error(elgg_echo('reportcards:error:deleteimport'));
	}
	
} else {
	register_error(elgg_echo('reportcards:error:invalidreportimport'));
}

forward('admin/reportcards/manage');