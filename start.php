<?php
/**
 * Report Cards Start
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

elgg_register_event_handler('init', 'system', 'reportcards_init');

// Report Cards Init
function reportcards_init() {
	// Register and load library
	elgg_register_library('reportcards', elgg_get_plugins_path() . 'reportcards/lib/reportcards.php');
	elgg_load_library('reportcards');
	
	// Register CSS
	$rc_css = elgg_get_simplecache_url('css', 'reportcards/css');
	elgg_register_simplecache_view('css/reportcards/css');	
	elgg_register_css('elgg.reportcards', $rc_css);
	
	// Register tag dashboards JS library
	$nm_js = elgg_get_simplecache_url('js', 'reportcards/reportcards');
	elgg_register_simplecache_view('js/reportcards/reportcards');	
	elgg_register_js('elgg.reportcards', $nm_js);
	
	// Debug
	elgg_load_css('elgg.reportcards');
	elgg_load_js('elgg.reportcards');
}
