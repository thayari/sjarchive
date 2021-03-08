<?php
/**
 * Joomla Science Journal Archive Component
 * 
 * @package    SJ.Archive
 * @subpackage com_sjarchive
 * @license    GNU/GPL, see LICENSE.php
 * @link       
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//@TASK что это?
//JHtml::_('behavior.tabstate');

	
// Get an instance of the controller prefixed by HelloWorld
$controller = JControllerLegacy::getInstance('Archive');

// Perform the Request task
$controller->execute(
	JFactory::getApplication()->input->get('task','issues'));

// Redirect if set by the controller
$controller->redirect();