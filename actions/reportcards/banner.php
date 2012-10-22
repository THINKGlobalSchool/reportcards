<?php
/**
 * Report Cards Admin Banner Set Action
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$content = get_input('banner_content', FALSE);
$enabled = get_input('banner_enable', FALSE);

elgg_set_plugin_setting('banner_content', $content, 'reportcards');
elgg_set_plugin_setting('banner_enable', $enabled, 'reportcards');

system_message(elgg_echo('reportcards:success:banner'));
forward(REFERER);