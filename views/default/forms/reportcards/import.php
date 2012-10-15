<?php
/**
 * Report Cards Admin Import
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$import_directory = elgg_extract('import_directory', $vars);

// Can't be too careful.. check for directory
if (is_dir($import_directory)) {
	// Find manifests in directory
	$manifests = glob($import_directory . "/*.xml");
	
	// If we've got one (or more)
	if (count($manifests) > 0) {
		$manifests_table = "<table class='elgg-table'>
			<tr>
				<th>" . elgg_echo('reportcards:label:manifestfile')  . "</th>
				<th>" . elgg_echo('reportcards:label:manifestsize') . "</th>
				<th>" . elgg_echo('reportcards:label:action') . "</th>
			</tr>
		";
		foreach($manifests as $manifest) {
			$manifests_table .= "<tr>";
			$manifests_table .= "<td>$manifest</td>";
			$manifests_table .= "<td>" . reportcards_format_size($manifest) . "</td>";
			
			$import = elgg_view('input/submit', array(
				'name' => 'import',
				'class' => 'reportcards-import-button elgg-button elgg-button-action',
				'value' => elgg_echo('reportcards:label:import'),
			));
			
			$import_filename = elgg_view('input/hidden', array(
				'name' => 'import_filename',
				'value' => $manifest,
			));
			
			$import_directory = elgg_view('input/hidden', array(
				'name' => 'import_directory',
				'value' => $import_directory,
			));
			
			$manifests_table .= "<td>
									$import
									$import_filename 
									$import_directory
			</td>";

			$manifests_table .= "</tr>";
		}
		$manifests_table .= "</table>";
		
		$manifests_label = elgg_echo('reportcards:label:manifests');
	
		$content = <<<HTML
			<br />
			<label>$manifests_label</label>
			$manifests_table
HTML;

		echo $content;
		
	} else {
		// No manifests
		echo elgg_echo('reportcards:label:nomanifests');
	}
} else {
	// Bail
	echo elgg_echo('reportcards:import_directory_invalid');
}
