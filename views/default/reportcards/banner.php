<?php
/**
 * Report Cards Banner View
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

if (get_input('tab') == 'student') {
	return FALSE;
}

$banner_content = elgg_get_plugin_setting('banner_content', 'reportcards');

if (get_input('show_parent_link')) {
	$child_tab = elgg_get_plugin_setting('banner_child_tab', 'reportcards');

	$banner_content .= elgg_view('output/url', array(
		'text' => 'Click Here',
		'href' => elgg_get_site_url() . 'home?tab='  . $child_tab,
		'class' => 'reportcards-notification-shortcut',
	));	
}


$content = <<<HTML
	<div id='reportcards-home-notification'>
		<div id='reportcards-notification-content'>$banner_content</div>
	</div>
HTML;

echo $content;