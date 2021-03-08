<?php
defined('_JEXEC') or die('RA');

class archiveViewIssuesList extends JViewLegacy 
{

	public function display($tpl = NULL)
	{
			
		if(!empty($this->issues))
		{
			switch ($this->language)
			{
				case 'ru-RU':
					$tpl = 'ru_issues';
				break;
				case 'en-GB':
					$tpl = 'en_issues';
				break;
			}
			
		//	$tpl = 'issues';
		}

		parent::display($tpl);
	}
}
?>
