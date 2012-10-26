<?php
/**
 * Report Cards Admin Statistics
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
elgg_load_css('elgg.reportcards');
$guid = get_input('guid');

$report = get_entity($guid);

if (elgg_instanceof($report, 'object', 'reportcardfile')) {
	$views_title = elgg_echo('reportcards:label:viewers');
	$view_info_title = elgg_echo('reportcards:label:viewinfo');
	
	$options = array(
		'guid' => $report->guid,
		'type' => 'object',
		'subtype' => 'reportcardfile',
		'annotation_name' => 'reportcard_view',
		'limit' => 0,
		'count' => TRUE,
	);
	
	// Total view count
	$total_view_count = elgg_get_annotations($options);

	// Group by owner_guid foir unique viewers
	$options['group_by'] = 'n_table.owner_guid';
	$options['count'] = FALSE;
	
	// Viewer info
	$views = elgg_get_annotations($options);
	
	$unique_view_count = count($views);
	
	$view_content = '';
	
	// Build viewers content
	foreach ($views as $view) {
		$user = get_entity($view->owner_guid);
		$user_link = elgg_view('output/url', array(
			'text' => "<span class='reportcards-admin-statistics-userlink'>"  . $user->name . "</span>",
			'href' => $user->getURL(),
		));
		
		$user_icon = elgg_view_entity_icon($user, 'tiny');
		
		$view_content .= elgg_view_image_block($user_icon, $user_link);
	}
	
	if (!$view_content) {
		$view_content = elgg_echo('reportcards:label:noviews');
	}
	
	// View info content
	$total_label = elgg_echo('reportcards:label:totalviews');
	$unique_label = elgg_echo('reportcards:label:uniqueviews');
	
	$view_info_content = <<<HTML
		<div>
			<label>$total_label:</label>&nbsp;$total_view_count
		</div>
		<div>
			<label>$unique_label:</label>&nbsp;$unique_view_count
		</div>
HTML;
	
	echo elgg_view_module('inline', $view_info_title, $view_info_content);
	echo elgg_view_module('inline', $views_title, $view_content);
} else {
	forward('admin/reportcards/manage');
}