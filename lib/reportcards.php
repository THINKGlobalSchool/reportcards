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
 * Import with XML object
 *
 * @param string $file_name
 * @param string $reports_directory
 * @param bool   $log_output
 * @return bool
 */
function reportcards_import_from_file($file_name, $reports_directory, $log_output = FALSE) {
	if ($log_output) {
		$log = "<pre>";
	}

	// Make sure file and report directory exist
	if (file_exists($file_name) && file_exists($reports_directory)) {
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
							$report_entity->report_period = $year;
							$report_entity->report_published = $year;
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
								$download_url = elgg_get_site_url() . 'reportcards/download/' . $report_entity->guid;
								$log .= "	-> Created report: {$report_entity->guid}\r\n";
								$log .= "	-> Filename:       {$filestore_name}\r\n";
								$log .= "	-> Download:       {$download_url}\r\n";
							} else {
								$log .= "	-> Error saving report entity!\r\n";
							}
						} else {
							$log .= "	-> Cannot find report file: {$report['filename']}\r\n";
						}
						$log .= "\r\n";
					} else {
						$log .= "Invalid user for guid: {$report['guid']}\r\n";
					}
				}
				
			} else {
				// No reports found
				$log .= "No reports";
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

/**
 * Delete all system report cards
 */
function reportcards_reset() {
	$options = array(
		'type' => 'object',
		'subtype' => 'reportcardfile',
	);
	
	$entities = new ElggBatch('elgg_get_entities', $options);
	
	foreach ($entities as $entity) {
		elgg_dump("Deleting: " . $entity->guid);
		elgg_dump($entity->getFilenameOnFilestore());	
		$entity->delete();
	}
}