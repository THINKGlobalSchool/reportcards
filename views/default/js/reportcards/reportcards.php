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
	// Click handler for admin import
	$(document).delegate('.reportcards-import-button', 'click', elgg.reportcards.importClick);

	// Click handler for admin reset
	$(document).delegate('.reportcards-reset-button', 'click', elgg.reportcards.resetClick);

	// Change handler for display filter change
	$(document).delegate('#reportcards-module-filter-display', 'change', elgg.reportcards.filterDisplayChange);
	
	// Change handler for period filter change
	$(document).delegate('#reportcards-module-filter-period', 'change', elgg.reportcards.filterPeriodChange);
	
	// Change handler for year filter change
	$(document).delegate('#reportcards-module-filter-year', 'change', elgg.reportcards.filterYearChange);

/**$("div#reportcards-notification")
	.appendTo('.elgg-layout-one-sidebar-right')
	.slideToggle('slow');**/


	// Add home notification
	$('.reportcard-home-module')
		.prepend($("div#reportcards-home-notification"));
	
	// Slide in home notification	
	$("div#reportcards-home-notification").slideToggle('slow');

	// Add pp notification
	$('#pp_top')
		.append($("div#reportcards-pp-notification"));
		
	// Slide in home notification	
	$("div#reportcards-pp-notification").slideToggle('slow');
}

// Click handler for admin import
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

// Click handler for admin reset
elgg.reportcards.resetClick = function(event) {
	if (confirm($(this).attr('title'))) {
		$('#reportcards-reset-output').html("<div class='elgg-ajax-loader'></div>");

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
				$('#reportcards-reset-output').html(content);
			}
		});
	}
	event.preventDefault();
}

// Change handler for display filter change
elgg.reportcards.filterDisplayChange = function(event) {
	$module = $('#reportcards-module');
	$display_input = $module.find('div.options > input#display');
	$display_input.val(escape($(this).val()));

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

// Change handler for display filter change
elgg.reportcards.filterPeriodChange = function(event) {
	$module = $('#reportcards-module');
	$period_input = $module.find('div.options > input#period');
	$period_input.val(escape($(this).val()));

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

// Change handler for display filter change
elgg.reportcards.filterYearChange = function(event) {
	$module = $('#reportcards-module');
	$year_input = $module.find('div.options > input#year');
	$year_input.val(escape($(this).val()));

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

// Hook into module populated event for reportcards module to draw attention to it
elgg.reportcards.modulePopulated = function(event, type, params, value) {
	if (params.container.attr('id') == 'reportcards-module' && window.location.hash && window.location.hash == '#rc') {
		$('.reportcard-pp-module').effect("pulsate", { times: 3 }, 600);
	}
}

elgg.register_hook_handler('init', 'system', elgg.reportcards.init);
elgg.register_hook_handler('generic_populated', 'modules', elgg.reportcards.modulePopulated);