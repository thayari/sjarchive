<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewCreateIssueForm extends JViewLegacy {

	public function display($tpl = NULL)
	{
		$this->setToolBar();
		parent::display($tpl);
	}
	
	protected function setToolBar()
	{
		JToolBarHelper::title(JText::_('ARTICULUS.ISSUE.CREATE'),'generic.png');
		JToolBarHelper::save('issue.save');
		JToolbarHelper::save2new('issue.save2new');
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
	}
}
?>
