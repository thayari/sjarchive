<?php
// Защита от прямого доступа к файлу
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.helper');

// Создаем класс модели
class archiveFormIssueCreate
{


    public function getDataFromUserInput($raw)
    {
        $issue = new archiveModelIssue;
        $issue->num     = $raw['issue']['num'];
        $issue->volume  = $raw['issue']['volume'];
        $issue->part    = $raw['issue']['part'];
        $issue->year    = $raw['issue']['year'];
        $issue->doi     = $raw['issue']['doi'];
        $issue->useContent = $raw['issue']['useContent'];
        $issue->usePdf  = $raw['issue']['usePdf'];
        $issue->special = $raw['issue']['special'];
        $issue->specialComment = $raw['issue']['special_comment'];
   var_dump($issue);die();
        return $issue;
    }

    public function bindFiles ($files,archiveModelIssue &$issue)
    {
        //@TASK Фильтры
        $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path');
        $issue->makePdfPath($files['name']['pdf']);
        archiveCommonFileTransfer::move($files['tmp_name']['pdf'],JPATH_ROOT.DIRECTORY_SEPARATOR.$tmp_path.DIRECTORY_SEPARATOR.$issue->pdf);
        $issue->makeContentPath($files['name']['content']);
        archiveCommonFileTransfer::move($files['tmp_name']['content'],JPATH_ROOT.DIRECTORY_SEPARATOR.$tmp_path.DIRECTORY_SEPARATOR.$issue->content);
    }
}
