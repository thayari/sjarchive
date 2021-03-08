<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewEditArticleForm extends JViewLegacy  {

	public function display($tpl = null)
	{


			if(!empty($this->article))
			{
				$tpl = 'issue';
			}
		 else 
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
		JToolBarHelper::save('article.save');
		JToolBarHelper::cancel('cancel');
	}
	

}
?>
