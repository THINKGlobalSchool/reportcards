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

// @TODO Check if user has a reports available
// ie: reportcards_user_has_reports()
$report_cards = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'reportcardfile',
	'owner_guid' => elgg_get_logged_in_user_guid(),
));

elgg_dump($report_cards);

//$body = <<<HTML
//HTML;
$report_body = '';
foreach ($report_cards as $report) {
	$report_body .= elgg_view('output/url', array(
		'text' => $report->title,
		'href' => elgg_get_site_url() . 'reportcards/download/' . $report->guid,
	)) . "<br />";
}

$options = array(
	'class' => 'tgstheme-module',
);

echo elgg_view_module('featured', elgg_echo('reportcards:label:reportcards', array($user->name)), $report_body, $options);