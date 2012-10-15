<?php
/**
 * Report Cards Admin Management
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
elgg_load_js('elgg.reportcards');

$reset_form = elgg_view_form('reportcards/reset');

$content = <<<HTML
	$reset_form
	<div id='reportcards-management-output'>
	</div>
HTML;

echo $content;