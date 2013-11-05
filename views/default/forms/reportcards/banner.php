<?php
/**
 * Report Cards Banner Form
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$banner_content = elgg_get_plugin_setting('banner_content', 'reportcards');
$banner_enable = elgg_get_plugin_setting('banner_enable', 'reportcards');
$banner_child_tab = elgg_get_plugin_setting('banner_child_tab', 'reportcards');

$reportcards_enable_banner_label = elgg_echo('reportcards:label:enablebanner');
$reportcards_enable_banner_input = elgg_view('input/dropdown', array(
		'name' => 'banner_enable',
		'options_values' => array(
			'no' => elgg_echo('option:no'),
			'yes' => elgg_echo('option:yes')
			),
		'value' => $banner_enable
));

$role_dashboard_tabs = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'role_dashboard_tab',
	'limit' => 0
));

$dashboard_tab_array = array();

foreach ($role_dashboard_tabs as $tab) {
	$dashboard_tab_array[$tab->guid] = $tab->title;
}

$reportcards_child_tab_label = elgg_echo('reportcards:label:childtab');
$reportcards_child_tab_input = elgg_view('input/dropdown', array(
		'name' => 'banner_child_tab',
		'options_values' => $dashboard_tab_array,
		'value' => $banner_child_tab
));

$reportcards_banner_content_label = elgg_echo('reportcards:label:bannercontent');
$reportcards_banner_content_input = elgg_view('input/longtext', array(
	'name' => 'banner_content',
	'value' => $banner_content
));

$submit_input = elgg_view('input/submit', array(
	'name' => 'submit', 
	'value' => elgg_echo('save')
));

$content = <<<HTML
	<div>
		<label>$reportcards_enable_banner_label</label>
		$reportcards_enable_banner_input
	</div>
	<div>
		<label>$reportcards_child_tab_label</label>
		$reportcards_child_tab_input
	</div/>
	<div>
		<label>$reportcards_banner_content_label</label>
		$reportcards_banner_content_input
	</div>
	<div>
		$submit_input
	</div>
HTML;

echo $content;