<?php
// Защита от прямого доступа к файлу
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.helper');

// Создаем класс модели
class archiveFormIssueImport
{


  public function getDataFromUserInput($raw)
  {
    /**
     * Получаем информацию о выпуске, проверяем существование, фильтруем
     */
   
    $issue = new archiveModelIssue;
    $issue->num          = isset($raw['num']) ? (int) $raw['num'] : NULL;
    $issue->volume       = isset($raw['volume']) ? (int) $raw['volume'] : NULL;
    $issue->part         = isset($raw['part']) ? (int) $raw['part'] : NULL;
    $issue->year         = isset($raw['year']) ? (int) $raw['year'] : NULL;
    $issue->doi          = isset($raw['doi']) ?  $raw['doi'] : NULL;
    $issue->special      = isset($raw['special']) && $raw['special'] == "on" ? 1 : 0;
    $issue->specialComment   = isset($raw['special_comment']) ? $raw['special_comment'] : NULL;
    $issue->published        = $raw['published'] == "on" ? 1 : 0;
    $issue->createdDate = isset($raw['createdDate']) ? $raw['createdDate'] : date('Y-m-d H:i:s');
    $issue->pubDate = isset($raw['pubDate']) ? $raw['pubDate'] : date('Y-m-d H:i:s');

    /**
     * Получаем информацию о каждой статье, проверяем существование, фильтруем
     */

    if (isset($raw['info']) && !empty($raw['info'])) {
      foreach ($raw['info'] as $position => $info) {


        $article = new archiveModelArticle;

        $article->pdf              = isset($info['pdf']) && !empty($info['pdf']) ? $info['pdf'] : NULL;
        $article->pages            = isset($info['pages']) && !empty($info['pages']) ? $info['pages'] : NULL;
        $article->published        = isset($info['published']) && !empty($info['published']) && $info['published'] == 'on' ? 1 : 0;
        $article->translation      = isset($info['translation']) && !empty($info['translation']) ? $info['translation'] : NULL;
        $article->udk          = isset($info['udk']) && !empty($info['udk']) ? $info['udk'] : NULL;
        $article->doi          = isset($info['doi']) && !empty($info['doi']) ? $info['doi'] : NULL;
        $article->artType          = isset($info['art_type']) && !empty($info['art_type']) ? $info['art_type'] : NULL;

        $article->submitedDate     =  isset($info['submitedDate']) && !empty($info['submitedDate']) ? $info['submitedDate'] : NULL;
        $article->editedDate     =  isset($info['editedDate']) && !empty($info['editedDate']) ? $info['editedDate'] : NULL;
        $article->publishedDate     =  isset($info['publishedDate']) && !empty($info['publishedDate']) ? $info['publishedDate'] : NULL;
        $article->createdDate     =  isset($info['create_date']) && !empty($info['create_date']) ? $info['create_date'] : NULL;
        $article->editDate     =  isset($info['edit_date']) && !empty($info['edit_date']) ? $info['edit_date'] : NULL;

        $article->position = $position + 1;

        foreach (array_keys(JFactory::getLanguage()->getKnownLanguages()) as $language) {

          $article->title[$language] = isset($info['title'][$language]) && !empty($info['title'][$language]) ? $info['title'][$language] : NULL;
          $article->abstract[$language] = isset($info['abstract'][$language]) && !empty($info['abstract'][$language]) ? $info['abstract'][$language] : NULL;
          $article->section[$language] = isset($info['section'][$language]) && !empty($info['section'][$language]) ? $info['section'][$language] : NULL;
          $article->text[$language] = isset($info['text'][$language]) && !empty($info['text'][$language]) ? $info['text'][$language] : NULL;


          foreach ($info['authors'] as $key => $item) {

            $author = new archiveModelAuthor;

            $author->surname = isset($item['surname'][$language]) && !empty($item['surname'][$language]) ? $item['surname'][$language] : NULL;
            $author->lastname = isset($item['lastname'][$language]) && !empty($item['lastname'][$language]) ? $item['lastname'][$language] : NULL;
            $author->org = isset($item['org'][$language]) && !empty($item['org'][$language]) ? $item['org'][$language] : NULL;
            $author->address = isset($item['address'][$language]) && !empty($item['address'][$language]) ? $item['address'][$language] : NULL;
            $author->other = isset($item['other'][$language]) && !empty($item['other'][$language]) ? $item['other'][$language] : NULL;
            $author->email = isset($item['email'][$language]) && !empty($item['email'][$language]) ? $item['email'][$language] : NULL;
            $author->scopusId = isset($item['scopusId'][$language]) && !empty($item['scopusId'][$language]) ? $item['scopusId'][$language] : NULL;
            $author->wosId = isset($item['wosId'][$language]) && !empty($item['wosId'][$language]) ? $item['wosId'][$language] : NULL;
            $author->ORCID = isset($item['ORCID'][$language]) && !empty($item['ORCID'][$language]) ? $item['ORCID'][$language] : NULL;
            $author->spinCode = isset($item['spinCode'][$language]) && !empty($item['spinCode'][$language]) ? $item['spinCode'][$language] : NULL;
            $author->elibraryID = isset($item['elibraryID'][$language]) && !empty($item['elibraryID'][$language]) ? $item['elibraryID'][$language] : NULL;
            $author->scholarID = isset($item['scholarID'][$language]) && !empty($item['scholarID'][$language]) ? $item['scholarID'][$language] : NULL;
            $author->position = $key;
            $author->language = $language;
            $article->authors[$key][$language] = $author;
            
          }

          $article->keywords[$language] = array();
          if (isset($info['keywords'][$language])) {
            foreach (explode(';', $info['keywords'][$language]) as $item) {
              $keyword = new archiveModelkeyword;
              $keyword->keyword = $item;
              $keyword->language = $language;

              $article->keywords[$language][] = $keyword;
            }
          }
          $article->reference[$language] = array();
          if (isset($info['reference'][$language])) {
            foreach (explode("\r\n", $info['reference'][$language]) as $item) {
              if (!empty(trim($item))) {
                $reference = new archiveModelReference;
                $reference->reference = $item;
                $reference->language = $language;

                $article->reference[$language][] = $reference;
              }
            }
          }
        }

        $issue->articles[] = $article;
      }
    }

    return $issue;
  }

  public function bindFiles($files, archiveModelIssue &$issue)
  {
    $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path'); // NULL

    if (!empty($files['files']['name']['pdf'])) {
      $issue->makePdfPath($files['files']['name']['pdf']);

      archiveCommonFileTransfer::move($files['files']['tmp_name']['pdf'], $tmp_path . DIRECTORY_SEPARATOR . $issue->pdf);

      //@TASK убрать в настройки путь

    }
    if (!empty($files['files']['name']['content'])) {
      $issue->makeContentPath($files['files']['name']['content']);
      archiveCommonFileTransfer::move($files['files']['tmp_name']['content'], $tmp_path . DIRECTORY_SEPARATOR . $issue->content);
    }
    if (!empty($issue->articles)) {
      foreach ($issue->articles as &$article) {
        if (!empty($files['article']['name'][$article->doi])) {
          $article->pdf = $issue->makegeneralPath() . $files['article']['name'][$article->doi];
          archiveCommonFileTransfer::move($files['article']['tmp_name'][$article->doi], $tmp_path . DIRECTORY_SEPARATOR . $article->pdf);
        }
      }
    }
  }
}
