<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class archiveViewIssueContent extends JViewLegacy  {

	public function display($tpl = null)
	{

		if( !empty($this->issue) && !empty($this->articles))
		{
			$tpl = 'articles';
		}
		parent::display($tpl);
	}
	

}
?>
