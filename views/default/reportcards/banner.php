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

$banner_content = elgg_get_plugin_setting('banner_content', 'reportcards');

if (elgg_in_context('home')) {
	$id = 'reportcards-home-notification';
} else if (elgg_in_context('parentportal') && get_input('tab') != 'student') {
	$id = 'reportcards-pp-notification';	
	$banner_content .= elgg_view('output/url', array(
		'text' => 'Click Here',
		'href' => elgg_get_site_url() . 'parentportal?tab=student#rc',
		'class' => 'reportcards-notification-shortcut',
	));
}

$content = <<<HTML
	<div id='$id'>
		<div id='reportcards-notification-content'>$banner_content</div>
	</div>
HTML;

echo $content;