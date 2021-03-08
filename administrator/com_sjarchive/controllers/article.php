<?php
defined('_JEXEC') or die('Restricted access');


class AdminControllerArticle extends JControllerLegacy
{
  public function display($cachable = false, $urlparams = false)
  {
    $article_db = new archiveDbModelArticle;
    $view  = $this->getView('DisplayArticle', 'html');

    $article = $article_db->selectById(JFactory::getApplication()->input->get('id'));

    $languages = array_keys(JFactory::getLanguage()->getKnownLanguages());

    $view->assignRef('article', $article); //@TASK убрать форму редактирования, добавить вывод статей
    $view->assignRef('languages', $languages);
    $view->display();
  }

  public function edit()
  {
    $article_db = new archiveDbModelArticle;
    $view  = $this->getView('EditArticleForm', 'html');
    
    $view->assignRef(
      'article',
      $article_db->selectById(
        $_POST['cid']
      )
    ); //@TASK убрать форму редактирования, добавить вывод статей
    
    $view->display();
  }
  public function delete()
  {
    $article_db = new archiveDbModelArticle;
    $article = $article_db->selectById(
      JFactory::getApplication()->input->get('cid')
    );
    $article_db->delete($article);

    $this->setRedirect('index.php?option=com_sjarchive');
  }

  public function save()
  {
    $input = JFactory::getApplication()->input;
    $article_form = new archiveFormArticleImport; //Обработчик данных введенных через форму
    $issue_form = new archiveFormIssueImport;
    $articles_db = new archiveDbModelArticles;
    $issue = new archiveModelIssue;
    $issue_db   = new archiveDbModelIssue;

    try {
      $article = $article_form->getDataFromUserInput($_POST['article']);
      $issue = $issue_db->selectByArticleId($article);
      $issue->bindArticle($article);
      $issue_form->bindFiles($_FILES, $issue);

      $issue_db->insert($issue);
      $articles_db->insert($issue);

      $this->setRedirect('index.php?option=com_sjarchive&controller=article&task=article.display&id=' . $article->ID, JTEXT::_('ARTICULUS.ISSUE.ADD.OK'));
    } catch (exception $e) {
      $this->setRedirect('index.php?option=com_sjarchive&controller=issues&article.display', $e->getMessage());
    }
  }

  public function download()
  {
    $article_db = new archiveDbModelArticle;
    $article = $article_db->selectById(
      JFactory::getApplication()->input->get('id')
    );

    $tmp_path = JComponentHelper::getParams('com_sjarchive')->get('archive_path');
    archiveCommonFileTransfer::download($tmp_path . DIRECTORY_SEPARATOR . $article->pdf);
  }
}
