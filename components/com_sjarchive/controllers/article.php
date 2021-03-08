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

class archiveControllerArticle extends JControllerLegacy
{
	/* Функция выводит информацию о выпусках, опубликованных в журнале */

	
	public function display($cachable = false, $urlparams = Array())
	{
      
		$article_db = $this->getModel('articledb');	
		$issue_db = $this->getModel('issuedb');
		$view = $this->getView('ArticleContent','html');
		
		$issue = $issue_db->selectByParams(
			JFactory::getApplication()->input->get('year','INT'),
			JFactory::getApplication()->input->get('num','INT'));

		$view->assignRef('issue',$issue);

		$article = $article_db->selectByParams(
			$issue->issueID,JFactory::getApplication()->input->get('pages'), JFactory::getLanguage()->getTag());

		$view->assignRef('article',$article);
		
		$view->display();	
	}

	public function download()
	{
		$file_transfer = $this->getModel('filetransfer');
		$issue_db = $this->getModel('issuedb');
		$article_db = $this->getModel('articledb');
		$issue_db->hit(JRequest::getVar('cid',NULL,'GET'));
		
		$issue = $issue_db->selectByParams(
			JFactory::getApplication()->input->get('year','INT'),
			JFactory::getApplication()->input->get('num','INT'));

    	$file = $article_db->selectFileByParams($issue->issueID,
												JFactory::getApplication()->input->get('pages'));   
												
		// $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path_archive'); //null
		$tmp_path = 'media\com_sjarchive\issues';

		$file_transfer->download($tmp_path.DIRECTORY_SEPARATOR.$file);
	}
}