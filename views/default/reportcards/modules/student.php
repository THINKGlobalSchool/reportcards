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

$report_cards = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'reportcardfile',
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'full_view' => FALSE,
));

$options = array(
	'class' => 'tgstheme-module',
);

if (!$report_cards) {
	$report_cards = "<label>" . elgg_echo('reportcards:label:noresults') . "</label>";
}

echo elgg_view_module('featured', elgg_echo('reportcards:label:reportcards', array($user->name)), $report_cards, $options);