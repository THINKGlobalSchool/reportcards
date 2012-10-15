<?php
/**
 * Report Cards Admin Reset Form
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$reset_label = elgg_echo('reportcards:label:resetlabel');
$reset_input = elgg_view('output/url', array(
	'text' => elgg_echo('reportcards:label:reset'),
	'href' => '#',
	'title' => elgg_echo('reportcards:label:resetconfirm'),
	'class' => 'elgg-button elgg-button-action reportcards-reset-button',
));

$content = <<<HTML
	<div>
		<label>$reset_label</label><br /><br />
		$reset_input
	</div>
	<div id='reportcards-reset-output'>
	</div>
HTML;

echo $content;