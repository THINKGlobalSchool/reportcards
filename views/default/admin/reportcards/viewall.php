<?php
/**
 * Report Cards Admin View All
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

// Get reportcards belonging to this import
$reportcards = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'reportcardfile',
	'pagination' => TRUE,
	'full_view' => FALSE,
));

if (!$reportcards) {
	$reportcards = "<label>" . elgg_echo('reportcards:label:noresults') . "</label>";
}

echo $reportcards;