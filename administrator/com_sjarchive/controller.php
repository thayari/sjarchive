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


class AdminController extends JControllerLegacy
{
	/* Выводит данные о выпусках */
    public function issues ()
    {
		$view = $this->getView('DisplayIssues','html');
		$issues_db = new archiveDbModelIssues;
		$selectedIssues = $issues_db->selectIssues();
		$view->assignRef('issues',$selectedIssues);
		
		$view->display();	
    }

    /**
	 * Выводит форму создания выпуска
	 */ 
   	public function create()
    {
		$this->getView('CreateIssueForm','html')
			 ->display();

	}


	/*
	 * Выводит форму редактирования выпуска
	 * @int $cid - идентификатор выпуска
	 */ 
    public function edit()
    {
		try
		{

			$issue_db = $this->getModel('issuedb');
			$view = $this->getView('EditIssueForm','html');

			$issue = $issue_db->selectById(
				array_shift(JRequest::getVar('cid',NULL,'POST')));

			$view->assignRef('issue',$issue);

			$view->display();
		} catch (Exception $e){

			var_dump($e);
			// @ToDo Перенаправление на страницу ошибки, вывод ошибок
		}
	}
	/*
	 * Выводит форму импорта выпуска
	 */ 
    public function import()
    {
		//вызвать модель $issues_db 	 = $this->getModel('issuesdb');
		$view = $this->getView('ImportIssueForm','html');

		$view->display();	
	}
	/*
	 * Выводит форму экспорта выпуска
	 */ 
	public function export()
	{
		$issue_db = new archiveDbModelIssues;
		$view = $this->getView('ExportIssueForm','html');

		$view->assignRef('issues',$issue_db->selectIssues());
		$view->display();
	}

	public function cancel()
	{
		$this->setRedirect('index.php?option=com_sjarchive');
	}
}