<?php
/**
 * Report Cards File Object View
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
	
$reportcard_file = $vars['entity'];

if (!$reportcard_file) {
	return '';
}


$full = elgg_extract('full_view', $vars, FALSE);

// Forward to download url if viewing full
if ($full) {
	forward($reportcard_file->getURL());
}

$info = elgg_view_menu('simpleicon-entity', array(
	'entity' => $vars['entity'],
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$owner = $reportcard_file->getOwnerEntity();

$info .= "<a class='reportcard-listing-link' target='_blank' href=\"{$reportcard_file->getURL()}\">{$owner->name} - {$reportcard_file->title}</a>";

$icon = "<a href='{$reportcard_file->getURL()}'><img style='margin-left: -3px;' height='22' width='22' src='{$reportcard_file->getIconURL('tiny')}' border='0' /></a>";


if (elgg_in_context('admin')) {
	$metadata = elgg_view_menu('entity', array(
		'entity' => $reportcard_file,
		'handler' => 'reportcards/reportimport',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
	
	$info .= $metadata;
}


//display
echo elgg_view_image_block($icon, $info);
