<?php
// Защита от прямого доступа к файлу
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.helper');

// Создаем класс модели
class archiveFormArticleImport
{

  public function getDataFromUserInput($raw)
  {
    foreach ($raw as $info) {
      $article = new archiveModelArticle;

      $article->ID               = $info['cid'];
      $article->artType          = $info['art_type'];
      $article->pdf              = $info['pdf'];
      $article->pages            = $info['pages'];
      $article->published        = $info['published'];
      $article->translation      = $info['translation'];
      $article->udk              = $info['udk'];
      $article->doi              = $info['doi'];
      $article->submitedDate     = $info['submitedDate'];
      $article->editedDate       = $info['editedDate'];
      $article->publishedDate    = $info['publishedDate'];
      $article->createdDate      = $info['create_date'];
      $article->editDate         = date('Y-m-d');


      foreach (array_keys(JFactory::getLanguage()->getKnownLanguages()) as $language) {
        $article->title[$language] = $info['title'][$language];
        $article->abstract[$language] = $info['abstract'][$language];
        $article->section[$language] = $info['section'][$language];
        $article->title[$language] = $info['title'][$language];

        foreach ($info['authors'] as $key => $item) {

          $author = new archiveModelAuthor;
          $author->authorId = $item['authorId'][$language];
          $author->surname = $item['surname'][$language];
          $author->lastname = $item['lastname'][$language];
          $author->org = $item['org'][$language];
          $author->address = $item['address'][$language];
          $author->other = $item['other'][$language];
          $author->email = $item['email'][$language];
          $author->scopusId = $item['scopusId'][$language];
          $author->wosId = $item['wosId'][$language];
          $author->ORCID = $item['ORCID'][$language];
          $author->spinCod = $item['spinCode'][$language];
          $author->position = $key;
          $article->authors[$key][$language] = $author;
        }

        foreach (explode(';', $info['keywords'][$language]) as $item) {
          $keyword = new archiveModelkeyword;
          $keyword->keyword = $item;
          $keyword->language = $language;

          $article->keywords[$language][] = $keyword;
        }

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

    return $article;
  }

  public function bindFiles($files, archiveModelArticle &$article)
  {

    $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path');
    $article->pdf = $files['article']['name'][$article->doi];

    archiveCommonFileTransfer::move($files['article']['tmp_name'][$article->doi], $tmp_path . DIRECTORY_SEPARATOR . $article->pdf);
  }
}
