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

$reportcards_banner_label = elgg_echo('reportcards:label:banner');
$reportcards_banner_form = elgg_view_form('reportcards/banner');
$reportcards_banner_module = elgg_view_module('inline', $reportcards_banner_label, $reportcards_banner_form);

$reportcard_imports_label = elgg_echo('reportcards:label:reportcardimports');
$reportcard_imports = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'reportcard_import_container',
	'pagination' => TRUE,
	'full_view' => FALSE,
));

if (!$reportcard_imports) {
	$reportcard_imports = "<label>" . elgg_echo('reportcards:label:noresults') . "</label>";
}

$reportcard_imports_module = elgg_view_module('inline', $reportcard_imports_label, $reportcard_imports);

$reset_form = elgg_view_form('reportcards/reset');

$scripts_label = elgg_echo('reportcards:label:scripts');

$scripts_module = elgg_view_module('inline', $scripts_label, $reset_form);

$content = <<<HTML
	$reportcards_banner_module
	$reportcard_imports_module
	$scripts_module
HTML;

echo $content;