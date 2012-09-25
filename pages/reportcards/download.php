<?php
/**
 * Report Cards Download
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

// Get the guid
$guid = get_input("guid");

// Get the file
$report = get_entity($guid);
if (!$report) {
	register_error(elgg_echo("reportcards:downloadfailed"));
	forward();
}

$mime = "application/pdf";
$filename = $report->report_filename;

// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime");

//header("Content-Disposition: inline; filename=\"$filename\""); /* Use this to display PDF in browser */
header("Content-Disposition: attachment; filename=\"$filename\"");

ob_clean();
flush();
readfile($report->getFilenameOnFilestore());
exit;
