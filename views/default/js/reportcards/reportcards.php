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
	//console.log('Report Cards');
}

elgg.register_hook_handler('init', 'system', elgg.reportcards.init);