<?php
/** Enable email notifications for all site users **/
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
admin_gatekeeper();

echo elgg_view_title('Rename Report Periods');
echo "<pre>";
$go = get_input('go');


$ts = get_input('ts', false);

// Nuke by timestamp
if ($ts) {
	$report_cards = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'reportcardfile',
		'limit' => 0,
		'metadata_name' => 'report_published',
		'metadata_value' => $ts
	));

	$count = 1;
	foreach ($report_cards as $report) {
		$count++;
		echo "Name: {$report->title} - " . $report->getURL();
	
		if ($go) {
			$report->delete(); 
			echo " -> DELETED";
		}	

		echo "\r\n";
	}

	echo "\r\n{$count}";
}

$period = get_input('period', false);

if ($period) {

	$report_cards = elgg_get_entities_from_metadata(array(
                'type' => 'object',
                'subtype' => 'reportcardfile',
                'limit' => 0,
        ));


	foreach ($report_cards as $report) {
		$period = $replace = NULL;	
		if (strstr($report->title, "Term 4 Report 2014-2015")) {
			$period = "Period 4";
			$replace = str_replace("Term 4 Report 2014-2015" , "Period 4 Report 2014-2015", $report->title);
		}

		if (strstr($report->title, "Term 3 Report 2014-2015")) {
                        $period = "Period 3";
			$replace = str_replace("Term 3 Report 2014-2015" , "Period 3 Report 2014-2015", $report->title);
                }

		if ($period && $replace && $go) {
			$report->report_period = $period;
			$report->title = $replace;
			$report->save();
			echo "Will update: $report->title --> $replace ($period)\r\n\r\n";
		}
	}
}

$cont = get_input('cont', FALSE);
if ($cont) {
	$report_containers = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'reportcard_import_container',
		'limit' => 0
	));

	foreach ($report_containers as $container) {
		
		$period = $replace = NULL;
		if ($container->title == "Term 4 Report 2014-2015") {
			$replace = str_replace("Term 4 Report 2014-2015" , "Period 4 Report 2014-2015", $container->title);
			$period = "Period 4";	
		}

		if ($container->title ==  "Term 3 Report 2014-2015") {
                        $replace = str_replace("Term 3 Report 2014-2015" , "Period 3 Report 2014-2015", $container->title);
                        $period = "Period 3";   
                }

		 if ($period && $replace && $go) {
                        $container->report_period = $period;
                        $container->title = $replace;
                        $container->save();
                        echo "Will update: $container->title --> $replace ($period)\r\n\r\n";
                }
	}
}
