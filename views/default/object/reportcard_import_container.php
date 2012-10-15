<?php
/**
 * Report Cards Import Object View
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$full = elgg_extract('full_view', $vars, FALSE);

$import = (isset($vars['entity'])) ? $vars['entity'] : FALSE;

if (!$import) {
	return '';
}

$linked_title = "<h3 style='padding-top: 14px;'><a href=\"{$import->getURL()}\" title=\"" . htmlentities($import->title) . "\">{$import->title}</a></h3>";

$metadata = elgg_view_menu('entity', array(
	'entity' => $import,
	'handler' => 'reportcards/reportimport',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if ($full) {
	if (!elgg_in_context('admin')) {
		forward();
	}
	$params = array(
		'entity' => $import,
		'metadata' => $metadata,
	);
	
	$reports_title = elgg_echo('reportcards:label:reportcards');
	
	// Get reportcards belonging to this import
	$reportcards = elgg_list_entities_from_relationship(array(
		'type' => 'object',
		'subtype' => 'reportcardfile',
		'pagination' => TRUE,
		'full_view' => FALSE,
		'relationship' => REPORTCARD_IMPORT_RELATIONSHIP,
		'relationship_guid' => $import->guid,
		'inverse_relationship' => true,
	));
	
	if (!$reportcards) {
		$reportcards = "<label>" . elgg_echo('reportcards:label:noresults') . "</label>";
	}
	
	$reports_module = elgg_view_module('inline', $reports_title, $reportcards);
	
	$list_body = elgg_view('object/elements/summary', $params);

	$import_info = elgg_view_image_block('', $list_body);
	
	echo <<<HTML
		$import_info
		$reports_module
HTML;
} else {
	// brief view
	$params = array(
		'title' => $linked_title,
		'entity' => $import,
		'metadata' => $metadata,
	);
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block('', $list_body);
}
?>