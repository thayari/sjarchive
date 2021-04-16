<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewDeleteIssue extends JViewLegacy  {

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
		parent::display($tpl);
	}

	protected function setToolBar()
	{
		$document = JFactory::getDocument();

		JToolBarHelper::title(JText::_('ARTICULUS'),'generic.png');
		JToolBarHelper::cancel('cancel');
	}
	

}
?>
