<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewImportIssueForm extends JViewLegacy  {

	public function display($tpl = NULL){
		$this->_setToolBar();
		parent::display($tpl);
	}
	
	protected function _setToolBar(){
		
		$document = JFactory::getDocument();
		
		JToolBarHelper::title(JText::_('ARTICULUS.ISSUE.IMPORT'),'generic.png');
		JToolBarHelper::save('issue.import');
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
	}
}
?>
