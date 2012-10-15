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
	$form_vars = reportcards_import_prepare_form_vars($import);
	echo elgg_view_form('reportcards/editimport', array(
		'name' => 'reportcards-import-edit-form', 
		'id' => 'reportcards-import-edit-form',
		'action' => elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/reportcards/reportimport/edit'),
	), $form_vars);
} else {
	forward('admin/reportcards/manage');
}