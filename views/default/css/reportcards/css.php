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

div#reportcards-home-notification {
	background: #162024;
	/**border-color: #85161D;
    border-style: solid;
    border-width: 0 2px 2px;**/
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
    color: #FFF;
    font-weight: bold;
    height: auto;
    overflow: hidden;
    padding: 4px 10px;
	margin: 6px 0px;
    position: relative;
    width: auto;
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

.reportcards-admin-statistics-userlink {
	display: block;
	font-weight: bold;
	margin-top: 5px;
}

/*</style>*/