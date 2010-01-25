<?php
/* NoiseCal.php
 *
 * - Eric Fortin < kenyu73@gmail.com >
 *
 * - Original author(s):
 *   	Simson L. Garfinkel < simsong@acm.org >
 *   	Michael Walters < mcw6@aol.com > 
 * See Readme file for full details
 */

if (!defined('MEDIAWIKI')) {
	die('This file is a MediaWiki extension, it is not a valid entry point');
}
$gCalendarVersion = "v3.8.4 (8/18/2009)";
// $wgShowExceptionDetails = true;

require_once('DefaultConfiguration.php');
require_once("common.php");
require_once("CalendarArticles.php");
require_once("ical.class.php");
require_once("Calendar.php");
require_once("NoiseEvent.php");

$wgExtensionCredits['parserhook'][] = array(
    'name' => 'NoiseCal',
    'author' => 'Noisebridgers',
#    'url'=>'http://www.mediawiki.org/wiki/Extension:Calendar_(Kenyu73)',
#    'description'=>'MediaWiki Calendar',
    'version' => $ncVersion
);

$path = dirname( __FILE__ );

$wgExtensionFunctions[] = 'wfCalendarExtension';
$wgExtensionMessagesFiles['wfCalendarExtension'] = "$path/calendar.i18n.php";


//$wgHooks['LanguageGetMagic'][]       = 'wfCalendarFunctions_Magic';

 // function adds the wiki extension
function wfCalendarExtension() {
	global $wgParser, $wgHooks;
	global $wgCalendarSidebarRef;
	$wgParser->setHook('calendar', 'displayCalendar');
	wfLoadExtensionMessages('wfCalendarExtension' ); 
 //   if ( isset($wgCalendarSidebarRef) ) $wgHooks['SkinTemplateOutputPageBeforeExec'][] = 
//		'wfCalendarSkinTemplateOutputPageBeforeExec';
}

// Hook to inject Calendar into sidebar
function wfCalendarSkinTemplateOutputPageBeforeExec( &$skin, &$tpl ) {
    global $wgCalendarSidebarRef;
    $html = displayCalendar('', array('simplemonth' => true, 'fullsubscribe' => $wgCalendarSidebarRef));
    $html .= displayCalendar('', array('date' => 'today', 'fullsubscribe' => $wgCalendarSidebarRef));
    $html .= displayCalendar('', array('date' => 'tomorrow', 'fullsubscribe' => $wgCalendarSidebarRef));
    if ( $html ) $tpl->data['sidebar']['calendar'] = $html;
    return true;
}

?>