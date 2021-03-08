<?php

/**
 * @package     SJ.Archive
 * @subpackage  com_sjarchive
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * General Controller of SJ.Archive component
 *
 * @package     SJ.Archive
 * @subpackage  com_sjarchive
 * @since       0.0.1
 */

class archiveController extends JControllerLegacy
{
	/* Функция выводит информацию о выпусках, опубликованных в журнале */
    public function display ($cachable = false, $urlparams = Array())
    {

		$view = $this->getView('IssuesList','html');
		$issues_db = $this->getModel('issuesdb');

		$issues = $issues_db->selectAll();

		$view->assignRef('issues',$issues);
		$view->assignRef('language',JFactory::getLanguage()->getTag());
		
		$view->display();
    }	
}