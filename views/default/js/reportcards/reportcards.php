<?php
/**
 * Report Cards JS Library
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
?>
//<script>
elgg.provide('elgg.reportcards');

// Init function
elgg.reportcards.init = function () {	
	$(document).delegate('.reportcards-import-button', 'click', elgg.reportcards.importClick);
	$(document).delegate('.reportcards-reset-button', 'click', elgg.reportcards.resetClick);
}

elgg.reportcards.importClick = function(event) {
	var filename = $(this).parent().find('input[name=import_filename]').val();
	var directory = $(this).parent().find('input[name=import_directory]').val();
	
	$('#reportcards-import-output').html("<div class='elgg-ajax-loader'></div>");
	
	// Fire import action
	elgg.action('reportcards/import', {
		data: {
			filename: filename,
			directory: directory,
		},
		success: function(data) {
			if (data.status != -1) {
				var content = '';
				if (data.output) {
					content = data.output;
				} else {
					content = elgg.echo('reportcards:error:nodata');
				}
			} else {
				content = data.system_messages.error;
			}
			$('#reportcards-import-output').html(content);
		}
	});

	event.preventDefault();
}

elgg.reportcards.resetClick = function(event) {
	if (confirm($(this).attr('title'))) {
		$('#reportcards-management-output').html("<div class='elgg-ajax-loader'></div>");

		// Fire reset action
		elgg.action('reportcards/reset', {
			data: {},
			success: function(data) {
				if (data.status != -1) {
					var content = '';
					if (data.output) {
						content = data.output;
					} else {
						content = elgg.echo('reportcards:error:nodata');
					}
				} else {
					content = data.system_messages.error;
				}
				$('#reportcards-management-output').html(content);
			}
		});
	}
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.reportcards.init);