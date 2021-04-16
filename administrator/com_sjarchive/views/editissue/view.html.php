<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewEditIssue extends JViewLegacy  {

	public function display($tpl = null)
	{

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
		$this->setToolBar();
		parent::display($tpl);
	}

	protected function setToolBar()
	{
		$document = JFactory::getDocument();

		JToolBarHelper::title(JText::_('ARTICULUS'),'generic.png');
		JToolBarHelper::save('issue.save');
		JToolBarHelper::cancel('cancel');
	}
	

}
?>
