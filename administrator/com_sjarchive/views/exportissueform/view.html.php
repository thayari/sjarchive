<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewExportIssueForm extends JViewLegacy  
{

	public function display($tpl = NULL){
		$this->_setToolBar();
		parent::display($tpl);
	}
	
	protected function _setToolBar(){
		
		$document = JFactory::getDocument();
		
		JToolBarHelper::title(JText::_('ARTICULUS.ISSUE.EXPORT'),'generic.png');
		JToolBarHelper::custom('issue.export', 'export', 'export', JTEXT::_('ARTICULUS.TOOLBAR.EXPORT'),false);
		
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
	}
}
?>
