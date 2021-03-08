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

if (!JFactory::getUser()->authorise('core.manage', 'com_messages'))
{
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

JLoader::discover('archiveExporterXML', JPATH_COMPONENT .'/models/archiveExporterXML');
JLoader::discover('archiveImporterXML', JPATH_COMPONENT .'/models/archiveImporterXML');
JLoader::discover('archiveModel',		JPATH_COMPONENT .'/models/archiveModel');
JLoader::discover('archiveDbModel',		JPATH_COMPONENT .'/models/archiveDbModel');
JLoader::discover('archiveForm',		JPATH_COMPONENT .'/models/archiveForm');
JLoader::discover('archiveCommon',		JPATH_COMPONENT .'/models/archiveCommon');
// Get an instance of the controller prefixed by HelloWorld
$controller = JControllerLegacy::getInstance('Admin');
// Perform the Request task
$controller->execute(
	JFactory::getApplication()->input->get('task','issues'));

// Redirect if set by the controller
$controller->redirect();