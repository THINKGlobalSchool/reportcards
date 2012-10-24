<?php
/**
 * Report Cards Admin Remote Copy Action
 *
 * @package ReportCards
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2012
 * @link http://www.thinkglobalschool.com/
 */

$copy_script = elgg_get_plugins_path() . 'reportcards/scripts/test.sh';

echo "<pre>";
echo exec($copy_script, $out, $ret);
echo "</pre>";