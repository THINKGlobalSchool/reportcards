<?php
/**
 * Report Cards Helper Library
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

/**
 * Prepare the edit form vars for report imports
 *
 * @param ElggObject $import
 * @return array
 */
function reportcards_import_prepare_form_vars($import = NULL) {

	// input names => defaults
	$values = array(
		'title' => '',
		'guid' => NULL,
	);

	if ($import) {
		foreach (array_keys($values) as $field) {
			$values[$field] = $import->$field;
		}
	}

	if (elgg_is_sticky_form('reportcards-import-edit-form')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('reportcards-import-edit-form', $field);
		}
	}

	elgg_clear_sticky_form('reportcards-import-edit-form');

	return $values;
}

/**
 * Import with XML object
 *
 * @param string $file_name
 * @param string $reports_directory
 * @param bool   $log_output
 * @return bool
 */
function reportcards_import_from_file($file_name, $reports_directory, $log_output = FALSE) {
	set_time_limit(0);

	if ($log_output) {
		$log = "<pre>";
	}

	// Make sure file and report directory exist
	if (file_exists($file_name) && file_exists($reports_directory)) {
		// Make sure we have one trailing slash on directory string
		$reports_directory = rtrim($reports_directory, '/').'/';
		
		// Load XML
		$xml = simplexml_load_file($file_name);

		// Check for valid SimpleXMLElement
		if ($xml instanceof SimpleXMLElement) {
			$title = (string)$xml->title;
			$year = (int)$xml->year;
			$period = (string)$xml->period;
			$published = (string)$xml->published;

			$log .= "REPORT IMPORT: {$title}\r\n";
			$log .= "------------------------------------------------\r\n";
			$log .= "YEAR:          {$year}\r\n";
			$log .= "PERIOD:        {$period}\r\n";
			$log .= "PUBLISHED:     {$published}\r\n";
			$log .= "------------------------------------------------\r\n";

			$report_count = $xml->reports->report->count();
			
			// Check for report import container with this publish date
			$report_containers = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'reportcard_import_container',
				'limit' => 1,
				'wheres' => "e.time_created = {$published}",
			));

			// If we have a report
			if (!$report_containers) {
				// Owned by site?
				$site = elgg_get_site_entity();
				
				$report_container = new ElggObject();
				$report_container->subtype = 'reportcard_import_container';
				$report_container->owner_guid = $site->guid;
				$report_container->access_id = ACCESS_LOGGED_IN;
				$report_container->title = $title;
				$report_container->report_year = $year;
				$report_container->report_period = $period;
				$report_container->report_published = $published;
				$report_container->save();
				
				// Double-tap save to set time created to report published date
				$report_container->time_created = $published;
				$report_container->save();
				
				$log .= "Created Report Import Container: {$report_container->guid}\r\n";
				
				if ($report_count) {
					$log .= "Counted {$report_count} report(s)\r\n\r\n";
					$reports = array();

					foreach ($xml->reports->report as $report) {
						$guid = (int)$report->user->guid;
						$name = (string)$report->user->name;
						$filename = (string)$report->filename;

						$log .= "{$guid} - {$name} - {$filename}\r\n";
						$reports[] = array(
							'guid' => $guid,
							'filename' => $filename,
						);
					}

					$log .= "\r\nProcessing {$report_count} Report(s)\r\n\r\n";

					foreach ($reports as $report) {
						$user = get_entity($report['guid']);
						if (elgg_instanceof($user, 'user')) {
							$log .= "Found user: {$user->name}\r\n";
							$report_origin = $reports_directory . $report['filename'];
							if (file_exists($report_origin)) {
								// Create file (@TODO this will need to be more advanced)
								$report_entity = new ElggFile();
								$report_entity->subtype = 'reportcardfile';
								$report_entity->owner_guid = $user->guid;
								$report_entity->access_id = ACCESS_PRIVATE; // Private to report user
								$report_entity->title = $title;
								$report_entity->report_year = $year;
								$report_entity->report_period = $period;
								$report_entity
								->report_published = $published;
								$report_entity->report_filename = $report['filename'];

								$prefix = 'reportcards/';

								$report_entity->setFilename($prefix . $report_entity->report_filename);

								$filestore_name = $report_entity->getFilenameOnFilestore();

								try {
									$report_entity->open('write');
									$report_entity->write(file_get_contents($report_origin));
									$report_entity->close();
									$file_written = file_exists($filestore_name);
								} catch (Exception $e) {
									$file_written = FALSE;
								}

								// Copy file to filestore and save entity
								if ($file_written && $report_entity->save()) {
									// Set time_created on entity to the published date
									$report_entity->time_created = $published;
									$report_entity->save();
									
									// Create relationship between this report, and the container
									add_entity_relationship(
										$report_entity->guid,
										REPORTCARD_IMPORT_RELATIONSHIP,
										$report_container->guid
									);
									
									$download_url = $report_entity->getURL();
									$log .= "	-> Created report: {$report_entity->guid}\r\n";
									$log .= "	-> Filename:       {$filestore_name}\r\n";
									$log .= "	-> Download:       {$download_url}\r\n";
									$log .= "	-> Container:      {$report_container->guid}\r\n";
								} else {
									$log .= "	-> Error saving report entity!\r\n";
								}
							} else {
								$log .= "	-> Cannot find report file: {$report['filename']}\r\n";
							}
							$log .= "\r\n";
						} else {
							// No user!
							$log .= "Invalid user for guid: {$report['guid']}\r\n";
						}
					}
				} else {
					// No reports found
					$log .= "No reports";
				}
				
			} else {
				// We already have a report container.. don't import again
				$container = $report_containers[0];
				$url = $container->getURL();
				$title = $container->title;
				$log .= "Report import exists for date: {$published} - See: <a target='_blank' href='{$url}'>$title</a>\r\n";
			}
		} else {
			// XML file was invalid
			$log .= "Invalid XML File";
		}
	} else {
		// Can't find given file
		$log .= "Cannot find file: $file_name";
	}
	
	// Return output if requested
	if ($log_output) {
		$log .= "</pre>";
		return $log;
	}
}


// Recursive glob()
function glob_recursive($pattern, $flags = 0) {
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

/**
 * Delete all system report cards
 */
function reportcards_reset($log_output = FALSE) {
	set_time_limit(0);
	$options = array(
		'type' => 'object',
		'subtypes' => array('reportcardfile', 'reportcard_import_container'),
		'limit' => 0,
	);
	
	$entities = new ElggBatch('elgg_get_entities', $options);
	
	$log .= "DELETING REPORT CARDS:\r\n";
	$log .= "----------------------\r\n\r\n";
	
	$count = 0;
	foreach ($entities as $entity) {
		if ($entity->getSubtype() == 'reportcard_import_container') {
			$log .= "Deleting Container: " . $entity->guid . "\r\n";	
		} else if ($entity->getSubtype() == 'reportcardfile') {
			$log .= "Deleting File: " . $entity->guid . "\r\n";	
		}

		$entity->delete();
		$count++;
	}
	
	if (!$count) {
		$log .= "Nothing to delete..\r\n";
	}

	if ($log_output) {
		return "<pre>" . $log . "</pre>";
	}
}

// Helper function to clean up file size
function reportcards_format_size($path) {
    $bytes = sprintf('%u', filesize($path));

    if ($bytes > 0) {
        $unit = intval(log($bytes, 1024));
        $units = array('B', 'KB', 'MB', 'GB');

        if (array_key_exists($unit, $units) === true)
        {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }
    return $bytes;
}