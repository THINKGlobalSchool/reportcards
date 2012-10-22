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

$reportcards_enable_banner_label = elgg_echo('reportcards:label:enablebanner');
$reportcards_enable_banner_input = elgg_view('input/dropdown', array(
		'name' => 'banner_enable',
		'options_values' => array(
			'no' => elgg_echo('option:no'),
			'yes' => elgg_echo('option:yes')
			),
		'value' => $banner_enable
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
		<label>$reportcards_banner_content_label</label>
		$reportcards_banner_content_input
	</div>
	<div>
		$submit_input
	</div>
HTML;

echo $content;