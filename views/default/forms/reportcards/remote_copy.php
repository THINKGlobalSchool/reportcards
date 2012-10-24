<?php
/**
 * Report Cards Admin Remote Copy Form
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$submit_input = elgg_view('input/submit', array(
	'name' => 'remote_copy',
	'class' => 'elgg-button elgg-button-action reportcards-copy-button',
	'value' => elgg_echo('reportcards:label:startcopy'),
));

$content = <<<HTML
	<div>
		$submit_input
	</div>
HTML;

echo $content;