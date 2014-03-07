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
$child_tab = get_input('banner_child_tab', FALSE);
$show_for_report = get_input('banner_current_report', 0);

elgg_set_plugin_setting('banner_content', $content, 'reportcards');
elgg_set_plugin_setting('banner_enable', $enabled, 'reportcards');
elgg_set_plugin_setting('banner_child_tab', $child_tab, 'reportcards');
elgg_set_plugin_setting('banner_current_report', $show_for_report, 'reportcards');

system_message(elgg_echo('reportcards:success:banner'));
forward(REFERER);