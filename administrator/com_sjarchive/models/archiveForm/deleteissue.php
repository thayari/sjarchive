<?php
// Защита от прямого доступа к файлу
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

// Создаем класс модели
class archiveFormDeleteIssue
{

    public function deletefiles(archiveModelissue $issue)
    {
        $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path');

        archiveCommonFileTransfer::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.$tmp_path.DIRECTORY_SEPARATOR.$issue->content);
        archiveCommonFileTransfer::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.$tmp_path.DIRECTORY_SEPARATOR.$issue->pdf);
        foreach ($issue->articles as $article)
        {
            archiveCommonFileTransfer::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.$tmp_path.DIRECTORY_SEPARATOR.$article->pdf);   
        }
    }

}