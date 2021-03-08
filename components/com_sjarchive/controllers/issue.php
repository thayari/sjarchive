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

class archiveControllerIssue extends JControllerLegacy
{

	public function display($cachable = false, $urlparams = Array())
	{     

		$articles_db = $this->getModel('articlesdb');	
		$issue_db = $this->getModel('issuedb');
		$view = $this->getView('IssueContent','html');

		$issue = $issue_db->selectByParams(
			JFactory::getApplication()->input->get('year','INT'),
			JFactory::getApplication()->input->get('num','INT'),
			JFactory::getApplication()->input->get('part','INT'),
			JFactory::getApplication()->input->get('special','INT'));

		$view->assignRef('issue',$issue);

		$articles = $articles_db->selectByIssue($issue->issueID,JFactory::getLanguage()->getTag());
		$view->assignRef('articles',$articles);

		$view->display();
		
	}
	


	public function download()
	{

		$file_transfer = $this->getModel('filetransfer');
		$issue_db = $this->getModel('issuedb');
		//$issue_db->hit(JRequest::getVar('cid',NULL,'GET'));
		
		$file = $issue_db->selectFileByParams(
			JFactory::getApplication()->input->get('year','INT'),
			JFactory::getApplication()->input->get('num','INT'),
			JFactory::getApplication()->input->get('ftype','string'),
			JFactory::getApplication()->input->get('part','INT'),
			JFactory::getApplication()->input->get('special','INT'));

		// $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path_archive');
    $tmp_path = 'media/com_sjarchive/issues';

		$file_transfer->download($tmp_path.DIRECTORY_SEPARATOR.$file);
	
		
	}

	public function current()
	{
		$file_transfer = $this->getModel('filetransfer');
		$issue_db = $this->getModel('issuedb');
    // $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path_archive');
    $tmp_path = 'media/com_sjarchive/issues';
		$file_transfer->download($tmp_path.DIRECTORY_SEPARATOR.$issue_db->selectLast());	
	}
/*
	public function feed()
	{
		$file_transfer = $this->getModel('filetransfer');
		$feed = JFactory::getApplication()->input->get('feed');
	//	$feed_path = JComponentHelper::getParams('com_sjarchive')->get('feed_path');
		switch ($feed){
			case 'rss':
				$file_transfer->sendToUSer('.\media\rss\sjarchive_rss.xml');
				break;
			default:
				break;
		}
		
		

	}*/

	
}