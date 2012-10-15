<?php
/**
 * Report Cards Admin Import Action
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$filename = get_input('filename', FALSE);
$directory = get_input('directory', FALSE);

if (!file_exists($filename)) {
	register_error(elgg_echo('reportcards:error:invalidfile', array($filename)));
	forward(REFERER);
}

if (!is_dir($directory)) {
	register_error(elgg_echo('reportcards:error:invaliddirectory', array($directory)));
	forward(REFERER);
}

$output = reportcards_import_from_file($filename, $directory, TRUE);

echo $output;
forward(REFERER);