<?php
/**
 * Report Cards Admin View Import
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$guid = get_input('guid');

$import = get_entity($guid);

if (elgg_instanceof($import, 'object', 'reportcard_import_container')) {
	echo elgg_view_entity($import, array('full_view' => TRUE));
} else {
	forward('admin/reportcards/manage');
}