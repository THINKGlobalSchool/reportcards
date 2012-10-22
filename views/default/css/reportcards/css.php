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
	body > div#reportcards-notification {
		position: fixed;
	}
}

div#reportcards-notification {
	background: rgba(255, 255, 255, 0.06);
	border-color: #DDD;
    border-style: solid;
    border-width: 0 2px 2px;
	-webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
    top: 0;
    color: #FFF;
    font-weight: bold;
    height: 0;
    left: 50%;
	margin-left: -253px; /* 179px *//* Half the width for horizontal centering */
    overflow: hidden;
    padding: 2px 10px 4px;
    position: absolute;
    width: 395px;
    z-index: 5000;
	display: none;
	text-align: center;
	-webkit-box-shadow: 4px 5px 5px #333;
	-moz-box-shadow: 4px 5px 5px #333;
	box-shadow: 4px 5px 5px #333;
}
/*</style>*/