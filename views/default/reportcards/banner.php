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

$content = <<<HTML
	<div id='reportcards-notification'>
		<div id='reportcards-notification-content'>$banner_content</div>
	</div>
HTML;

echo $content;