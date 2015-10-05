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

// Try to get the file
$report = get_entity($guid);

// No report, BUT, we could have a parent trying to download (Not going to bother with ACL's)
if (!$report) {
	// Make sure report file exists, regardless of access settings
	if (elgg_entity_exists($guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
		$db_prefix = elgg_get_config('dbprefix');

		// Run a quick query without breaking out of the access system
		// to check if this user is a parent of the reports owner
		$q = "SELECT owner_guid FROM {$db_prefix}entities
			WHERE guid = $guid";

		$result = get_data($q);

		// If we can pull up the owner guid for the entity..
		if (count($result && $result[0]) && elgg_entity_exists($result[0]->owner_guid)) {
			$report_owner_guid = $result[0]->owner_guid;

			// Get parents children (using build in PP functions)
			$children = parentportal_get_parents_children(elgg_get_logged_in_user_guid());

			// See if this owner_guid is one of the parent's children
			foreach ($children as $child) {
				if ($child->guid == $report_owner_guid) {
					// Break access and get the file
					elgg_set_ignore_access(TRUE);
					$report = get_entity($guid);
					break;
				}
			}
		}
	}
	// Not a parent, no access, fail
	if (!$report) {
		register_error(elgg_echo("reportcards:downloadfailed"));
		forward();
	}
}

$mime = "application/pdf";
$filename = $report->report_filename;

// Create 'viewed' annotation
create_annotation($report->guid, "reportcard_view", "1", "integer", elgg_get_logged_in_user_guid(), ACCESS_PRIVATE);

// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime");
header("Content-Length: {$report->getSize()}");
header("Content-Disposition: inline; filename=\"$filename\""); /* Use this to display PDF in browser */
//header("Content-Disposition: attachment; filename=\"$filename\"");



ob_clean();
flush();
readfile($report->getFilenameOnFilestore());
// Make sure to set access off either way
elgg_set_ignore_access(FALSE);
exit;
