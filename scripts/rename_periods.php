<?php
/** Enable email notifications for all site users **/
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
admin_gatekeeper();

echo elgg_view_title('Rename Report Periods');

$go = get_input('go');

$report_cards = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'reportcardfile',
	'limit' => 0,
));

foreach ($report_cards as $report) {
	$replace = $replace_echo = $period = $period_echo = NULL;

	if (strstr($report->title, "Term 1 Progress")) {
		$replace = str_replace("Term 1 Progress", "Term 1", $report->title);
	}

	if ($report->report_period == 'Progress 1') {
		$period = "Term 1";
	}

	if (strstr($report->title, "Term 1 Final")) {
		$replace = str_replace("Term 1 Final", "Term 2", $report->title);
	}

	if ($report->report_period == 'Final 1') {
		$period = "Term 2";
	}

	if (strstr($report->title, "Term 2 Progress")) {
		$replace = str_replace("Term 2 Progress", "Term 3", $report->title);
	}

	if ($report->report_period == 'Progress 2') {
		$period = "Term 3";
	}

	if ($replace) {
		$replace_echo = " -> {$replace}";
	}

	if ($period) {
		$period_echo = " - {$report->report_period} -> {$period}";
	}

	if ($go && $period && $replace) {
		$done = "  ------ DONE!!! ------ ";
		$report->report_period = $period;
		$report->title = $replace;
		$report->save();
	}

	echo "{$report->guid}: $report->title{$replace_echo}{$period_echo}{$done}<br />";
}

echo elgg_view_title('Rename Report Container Periods');

$report_containers = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'reportcard_import_container',
	'limit' => 0
));

foreach ($report_containers as $container) {
	$period = NULL;
	if ($container->report_period == 'Progress 1') {
		$period = "Term 1";
	}

	if ($container->report_period == 'Final 1') {
		$period = "Term 2";
	}

	if ($container->report_period == 'Progress 2') {
		$period = "Term 3";
	}

	if ($go && $period) {
		$done = " -> {$period} ------ DONE!!! ------";
		$container->report_period = $period;
		$container->save();
	}

	echo "{$container->guid}: {$container->title}: {$container->report_period}{$done}<br />";
}