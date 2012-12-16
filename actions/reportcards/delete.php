<?php
/**
 * Report Cards Delete Action
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$guid = get_input('guid');

$report = get_entity($guid);

if (elgg_instanceof($report, 'object', 'reportcardfile')) {
	// Try Delete
	if ($report->delete()) {
		system_message(elgg_echo('reportcards:success:delete'));
	} else {
		register_error(elgg_echo('reportcards:error:delete'));
	}
} else {
	register_error(elgg_echo('reportcards:error:invalidfile'));
}

forward(REFERER);