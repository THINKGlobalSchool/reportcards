<?php
/**
 * Report Cards Generic Module Reportcard endpoint
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars
 */

$owner_guid = elgg_extract('owner_guid', $vars);

// Filter values
$display = elgg_extract('display', $vars, 'latest');
$year = elgg_extract('year', $vars, 'all');
$period = elgg_extract('period', $vars, 'all');

// Display label/input
$display_label = elgg_echo('reportcards:label:display');
$display_input = elgg_view('input/dropdown', array(
	'id' => 'reportcards-module-filter-display',
	'class' => 'reportcards-module-filter',
	'options_values' => array(
		'latest' => elgg_echo('reportcards:label:latest'),
		'all' => elgg_echo('reportcards:label:all'),
	),
	'value' => $display,
));

// Register display filter menu item
elgg_register_menu_item('reportcards-module-filter-menu', array(
	'name' => 'reportcards_filter_display',
	'text' => "<label>$display_label:</label> $display_input",
	'href' => FALSE,
	'priority' => 100,
));

// Default report card options
$default_reportcard_options = array(
	'type' => 'object',
	'subtype' => 'reportcardfile',
	'owner_guid' => $owner_guid,
	'full_view' => FALSE,
	'limit' => 5,
);

// Filter which are displayed
if ($display == 'latest') {
	$import_options = array(
		'type' => 'object',
		'subtype' => 'reportcard_import_container',
		'limit' => 1,
	);

	$imports = elgg_get_entities($import_options); 
	
	if ($imports && $imports[0]) {
		$filter_reportcard_options = array(
			'relationship' => REPORTCARD_IMPORT_RELATIONSHIP,
			'relationship_guid' => $imports[0]->guid,
			'inverse_relationship' => TRUE
		);
	} else {
		// Nothing!
		$filter_reportcard_options = array();
	}
} else if ($display == 'all') {	
	$report_metadata_options = array(
		'type' => 'object',
		'subtype' => 'reportcard_import_container',
		'threshold' => 0,
		'limit' => 5,
		'tag_name' => 'report_year',
	);
	// This is just temporary so we can grab 'report_year' and 'report_period' from elgg_get_tags
	elgg_register_tag_metadata_name('report_year');
	elgg_register_tag_metadata_name('report_period');
	
	$years = elgg_get_tags($report_metadata_options);
	
	$report_metadata_options['tag_name'] = 'report_period';

	$periods = elgg_get_tags($report_metadata_options);
	
	$year_options = array('all' => elgg_echo('reportcards:label:all'));
	foreach ($years as $y) {
		$year_options[$y->tag] = $y->tag;
	}
	
	$period_options = array();
	foreach ($periods as $p) {
		$period_options[$p->tag] = $p->tag;
	}
	
	// Sort the periods descending
	krsort($period_options);

	// Reverse array
	$period_options = array_reverse($period_options, TRUE);

	// Add 'all' item
	$period_options['all'] = elgg_echo('reportcards:label:all');

	// Reverse back into proper order
	$period_options = array_reverse($period_options, TRUE);

	// Year label/input
	$year_label = elgg_echo('reportcards:label:year');
	$year_input = elgg_view('input/dropdown', array(
		'id' => 'reportcards-module-filter-year',
		'class' => 'reportcards-module-filter',
		'options_values' => $year_options,
		'value' => $year,
	));

	// Register year filter menu item
	elgg_register_menu_item('reportcards-module-filter-menu', array(
		'name' => 'reportcards_filter_year',
		'text' => "&nbsp;<label>$year_label:</label> $year_input",
		'href' => FALSE,
		'priority' => 200,
	));
	
	// Period label/input
	$period_label = elgg_echo('reportcards:label:period');
	$period_input = elgg_view('input/dropdown', array(
		'id' => 'reportcards-module-filter-period',
		'class' => 'reportcards-module-filter',
		'options_values' => $period_options,
		'value' => $period,
	));

	// Register period filter menu item
	elgg_register_menu_item('reportcards-module-filter-menu', array(
		'name' => 'reportcards_filter_period',
		'text' => "&nbsp;<label>$period_label:</label> $period_input",
		'href' => FALSE,
		'priority' => 300,
	));
	
	
	$filter_reportcard_options = array();

	if ($year != 'all') {
		$filter_reportcard_options['metadata_name_value_pairs'][] = array(
			'name' => 'report_year',
			'value' => $year,
		);
	}

	if ($period != 'all') {
		$filter_reportcard_options['metadata_name_value_pairs'][] = array(
			'name' => 'report_period',
			'value' => $period,
		);
	}
}

// Combine defaults and filter options
$reportcard_options = array_merge($default_reportcard_options, $filter_reportcard_options);

// Check for parent mode
$ia = elgg_get_ignore_access();
if (elgg_extract('parent_mode', $vars, FALSE)) {
	elgg_set_ignore_access(TRUE);
}

// Get report cards with filtered options
$report_cards = elgg_list_entities_from_relationship($reportcard_options);

elgg_set_ignore_access($ia);

if (!$report_cards) {
	$report_cards = "<div class='elgg-list reportcards-no-results'><label>" . elgg_echo('reportcards:label:noresults') . "</label></div>";
}

$filter_menu = elgg_view_menu('reportcards-module-filter-menu', array(
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority',
));

echo $filter_menu;
echo $report_cards;