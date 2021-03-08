<?php
defined ('_JEXEC') or die('Restricted access');


class AdminControllerIssue extends JControllerLegacy{

    /**
     * Обработчик формы создания/изменения выпуска
     *
     */
    public function save()
    {	


		$issue_form = new archiveFormIssueImport;//Обработчик данных введенных через форму
		$issue_db 	= new archiveDbModelIssue;//Mysql запросы
		$articles_db = new archiveDbModelArticles;




		try
		{
			$issue = $issue_form->getDataFromUserInput($_POST['article']);
			
			$issue_form->bindFiles($_FILES,$issue);


			$issue_db->insert($issue);
			$articles_db->insert($issue);

		
			$this->setRedirect('index.php?option=com_sjarchive&task=issue.display&cid='.$issue->ID,JTEXT::_('ARTICULUS.ISSUE.ADD.OK'));
		} catch (exception $e){
			$this->setRedirect('index.php?option=com_sjarchive&controller=issues&task=create',$e->getMessage());
		}	
	}

	public function display($cachable = false, $urlparams = array())
	{
		$issue_db 	= new archiveDbModelIssue;
		$article_db = new archiveDbModelArticles;
		$view	= $this->getView('DisplayIssue','html');

		$issue = $issue_db->selectById(JFactory::getApplication()->input->get('cid'));

		$article_db->selectByIssue($issue);
		$view->assignRef('issue',$issue); //@TASK убрать форму редактирования, добавить вывод статей
    
    	$knownLanguages = array_keys(JFactory::getLanguage()->getKnownLanguages());
		$view->assignRef('languages', $knownLanguages);
		$view->display();	
	}

	public function import()
	{
		try {
			$input = JFactory::getApplication()->input;

			$xml_importer = archiveImporterXMLFactory::getImporter(
				$input->post->get('xmltype', NULL)
			);

			$xml_importer->importXml($input->files->get('xml')['tmp_name']);

			$issue = new archiveModelIssue;

			$issue = $xml_importer->importIssue();

			$xml_importer->importArticles(
				array_keys(JFactory::getLanguage()->getKnownLanguages()),
				$issue
			);


			$view = $this->getView('EditIssue', 'html');

			$view->assignRef('issue', $issue);

			$view->display();
			
		} catch (exception $e) {
			$this->setRedirect('index.php?option=com_sjarchive', $e->getMessage());
		}
	}


	public function export()
	{
		try {
			$input = JFactory::getApplication()->input;

			$issue_db = new archiveDbModelIssue;
			$articles_db = new archiveDbModelArticles;
			//@TASK не срабатывает через IUNPUT->POST/
			$issue = $issue_db->selectById((int) $_POST['cid']);
			$articles_db->selectByIssue($issue);

			$xml_exporter = archiveExporterXMLFactory::getExporter(
				$input->post->get('xmltype', NULL, 'STRING')
			);

			archiveCommonFileTransfer::sendToUser(
				$xml_exporter->generateXml($issue),
				$input->post->get('xmltype', NULL, 'STRING') . '_' . $issue->num . '_' . $issue->year . '.xml'
			);
		} catch (Exception $e) {
			var_dump($e);
			die();
		}
		
		
	
	}

	public function delete()
	{
	
		$input = JFactory::getApplication()->input;

		$issue_db = new archiveDbModelIssue;
		$articles_db = new archiveDbModelArticles;
		$view = $this->getView('DeleteIssue','html');
		$issue_form = new archiveFormDeleteIssue;

		try	
		{

			$issue = $issue_db->selectById(array_shift(array_shift($input->post->getArray(array('cid'=>array(''))))));

			$articles_db->selectByIssue($issue);
				$view->assignRef('issue',$issue);
			$articles_db->deleteByIssue($issue);

			$issue_db->delete($issue);

			$issue_form->deletefiles($issue);
			$view->display();
		} 
		catch(exception $e) 
		{
			$this->setRedirect('index.php?option=com_sjarchive',$e->getMessage());
		}
	}

	public function edit()
	{

		$issue_db 	= new archiveDbModelIssue;
		$article_db = new archiveDbModelArticles;
	
		$view	= $this->getView('editIssue','html');

		$issue = $issue_db->selectById(array_shift($_POST['cid']));

		$article_db->selectByIssue($issue);
		$view->assignRef('issue',$issue); //@TASK убрать форму редактирования, добавить вывод статей
		$view->assignRef('languages',array_keys(JFactory::getLanguage()->getKnownLanguages()));
		$view->display();	
	}
		

	
	public function download()
	{
		$issue_db = new archiveDbModelIssue;
        $issue = $issue_db->selectById(
			JFactory::getApplication()->input->get('cid'));
			
				$tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path');

		switch (JFactory::getApplication()->input->get('ftype'))
		{
			case 'content':
			archiveCommonFileTransfer::download($tmp_path.DIRECTORY_SEPARATOR.$issue->content);

			break;
			case 'fulltext':
			archiveCommonFileTransfer::download($tmp_path.DIRECTORY_SEPARATOR.$issue->pdf);
			break;
			
			default:
			archiveCommonFileTransfer::download($tmp_path.DIRECTORY_SEPARATOR.$issue->pdf);
		}
        

	}

	public function cancel()
	{
		$this->setRedirect('index.php?option=com_sjarchive');
    }
	
	
}

