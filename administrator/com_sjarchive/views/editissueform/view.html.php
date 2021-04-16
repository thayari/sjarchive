<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewEditIssueForm extends JViewLegacy {

	public function display($tpl = NULL)
	{
		$this->setToolBar();
		parent::display($tpl);
	}
	
	protected function setToolBar()
	{
		JToolBarHelper::title(JText::_('ARTICULUS.ISSUE.EDIT'),'generic.png');
		JToolBarHelper::addNew('issue.createArticle');
		JToolBarHelper::save('issue.save');
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
	}
}
?>
