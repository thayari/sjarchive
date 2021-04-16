<?php
defined('_JEXEC') or die('RA');
jimport('joomla.application.component.view');
class adminViewDisplayArticle extends JViewLegacy  {

	public function display($tpl = null)
	{

		if( !empty($this->article))
		{

			$tpl = 'article';
		}

		JToolBarHelper::title(JText::_('ARTICULUS.ARTICLE.VIEW'),'generic.png');
		JToolBarHelper::custom('article.delete', 'article.delete', 'article.delete', JTEXT::_('ARTICULUS.ARTICLE.DELETE'),false);	
		JToolBarHelper::custom('article.edit', 'article.edit', 'article.edit', JTEXT::_('ARTICULUS.ARTICLE.EDIT'),false);	
		
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		parent::display($tpl);
	}
	

}
?>
