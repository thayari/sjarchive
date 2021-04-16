<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewDisplayIssue extends JViewLegacy  {

	public function display($tpl = null)
	{
		$this->setToolBar();
		if(!empty($this->issue) )
		{

			if(!empty($this->issue->articles))
			{
				$tpl = 'issue';
			}
		} else 
		{
			$tpl = 'error';
		}
		parent::display($tpl);
	}

	protected function setToolBar()
	{
		$document = JFactory::getDocument();

		JToolBarHelper::title(JText::_('ARTICULUS'),'generic.png');
		// JToolBarHelper::addNew('create');
		// JToolBarHelper::editList('edit');
    JToolBarHelper::back(JText::_('ARTICULUS.TOOLBAR.BACK'), '/administrator/index.php?option=com_sjarchive');
	}
	

}
?>
