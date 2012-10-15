<?php
/**
 * Report Cards English Language Translation
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$english = array(
	// Generic
	'item:object:reportcardfile' => 'Report Card Files',
	'admin:reportcards' => 'Report Cards',
	'admin:reportcards:import' => 'Import',
	'admin:reportcards:manage' => 'Manage',

	// Page titles

	// Labels
 	'reportcards:label:reportcards' => 'Report Cards',
	'reportcards:label:directory' => 'Report Import Directory (Folder will be created)',
	'reportcards:label:directoryinfo' => 'Import Directory Info',
	'reportcards:label:nomanifests' => 'No manifests found!',
	'reportcards:label:manifests' => 'Discovered Manifests',
	'reportcards:label:manifestfile' => 'Filename',
	'reportcards:label:manifestsize' => 'File size',
	'reportcards:label:action' => 'Action',
	'reportcards:label:import' => 'Import',
	'reportcards:label:resetlabel' => 'Reset all reports',
	'reportcards:label:reset' => 'Reset',
	'reportcards:label:resetconfirm' => 'Are you sure you want to reset all reports? ALL existing report cards will be deleted!!',

	// River

	// Messages
	'reportcards:downloadfailed' => "Sorry; this file is not available at this time.",
	'reportcards:import_directory_unset' => 'Warning: Report cards import directory is not configured!',
	'reportcards:import_directory_invalid' => 'Warning: Report cards import directory is invalid!',
	'reportcards:success:createimportdirectory' => 'Successfully created import directory',
	'reportcards:success:importdirectory' => 'Successfully set import directory',
	'reportcards:error:importdirectory' => 'There was an error setting the import directory',
	'reportcards:error:createimportdirectory' => 'There was an error creating the import directory',
	'reportcards:error:invalidfile' => 'Invalid file: %s',
	'reportcards:error:invaliddirectory' => 'Invalid directory: %s',
	'reportcards:error:nodata' => 'No Data',

	// Other content
);

add_translation('en',$english);