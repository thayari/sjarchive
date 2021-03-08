<?php
defined('_JEXEC') or die('RA');

class adminViewDisplayIssues extends JViewLegacy 
{

	public function display($tpl = NULL)
	{
			
		$this->setToolBar();
		if(!empty($this->issues))
		{
			$tpl = 'issues';
		}

		parent::display($tpl);
	}
	
	protected function setToolBar()
	{
		$document = JFactory::getDocument();

		JToolBarHelper::title(JText::_('ARTICULUS'),'generic.png');
		JToolBarHelper::addNew('create');
		JToolBarHelper::editList('issue.edit',JTEXT::_('ARTICULUS.TOOLBAR.EDITISSUE'));
		JToolBarHelper::deleteList('issue.delete','issue.delete');
		JToolBarHelper::custom('import', 'import', 'import', JTEXT::_('ARTICULUS.TOOLBAR.IMPORT'),false);	
		JToolBarHelper::custom('export', 'export', 'export', JTEXT::_('ARTICULUS.TOOLBAR.EXPORT'),false);
		JToolBarHelper::preferences('com_sjarchive');
	}
}
?>
