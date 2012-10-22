<?php
/**
 * Report Cards CSS
 * 
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */
?>
/*<style>*/
.reportcard-listing-link {
	display: inline-block;
	margin-top: 5px;
	font-weight: bold;
}

.reportcards-no-results {
	padding-top: 5px;
}

@media screen {
	body > div#reportcards-home-notification {
		position: fixed;
	}
}

div#reportcards-home-notification,
div#reportcards-pp-notification {
	background: #162024;
	/**border-color: #85161D;
    border-style: solid;
    border-width: 0 2px 2px;**/
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
    top: 0;
    color: #FFF;
    font-weight: bold;
    height: auto;
    overflow: hidden;
    padding: 4px 10px;
	margin-bottom: 10px;
    position: relative;
    width: auto;
    z-index: 5000;
	display: none;
	text-align: center;
	-webkit-box-shadow: 0 2px 2px #444;
	-moz-box-shadow: 0 2px 2px #444;
	box-shadow: 0 2px 2px #444;
}

#reportcards-notification-content p {
	display: inline;
}

a.reportcards-notification-shortcut {
	margin-left: 10px;
	color: #EEE;
}

/*</style>*/