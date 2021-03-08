<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class archiveViewArticleContent extends JViewLegacy  {

	public function display($tpl = null)
	{

		if( !empty($this->issue) && !empty($this->article))
		{

			$tpl = 'article';
		}
		parent::display($tpl);
	}
	

}
?>
