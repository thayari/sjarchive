<?php

/**
 * Joomla Science Journal Archive Component
 * 
 * @package    SJ.Archive
 * @subpackage com_sjarchive
 * @license    GNU/GPL, see LICENSE.php
 * @link       
 */
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class archiveImporterXmlNeu
{
  const SCHEMA = NULL;
  protected $xml;
  protected $config = array();

  public function __construct($title = '', $abstract = '')
  {
    $params = JComponentHelper::getParams( 'com_sjarchive' );
    $lang = JFactory::getLanguage();
    if ($lang->getTag() == 'ru-RU') {
      $title = $params->get('title_ru');
    } else {
      $title = $params->get('title_en');
    }

    $this->config['title'] = $title;
    $this->config['abstract'] = $abstract;
  }

  



  public function validate()
  {
    libxml_clear_errors();

    if (!$this->xml->schemaValidate(self::SCHEMA)) {
      throw new Exception(libxml_get_last_error()->message);
    }
  }

  public function importXml($file)
  {
    libxml_clear_errors();
    libxml_use_internal_errors(true);

    if (JFile::exists($file)) {

      $html = new DOMDocument('1.0', 'utf-8');

      $html->loadHtmlFile($file);

      $this->xml = simplexml_import_dom($html);
    } else {
      throw new Exception('file not exists');
    }
    if ($error = libxml_get_last_error())

      throw new Exception($error->message);
  }

  public function importIssue($doi_settings = array('core' => '10.22227', 'issn' => '2073-8412', 'useNum' => '1', 'x' => 0, 'useVolume' => 0, 'useYear' => 1, 'useDoi' => 1))
  {
    $article_doi_xpath = $this->xml->xpath('//*[contains(@class,"abstract-en") and contains(.,"Для цитирования")]');
    $article_doi = trim((string) array_shift($article_doi_xpath));
    $article_doi = str_replace(' ', '', explode('DOI:', $article_doi)[1]);
    $data = explode('.', $article_doi);

    $this->_item['issue'] = new archiveModelIssue();

    $this->_item['issue']->num          = $data[3];
    $this->_item['issue']->part         = NULL;
    $this->_item['issue']->volume       = NULL;
    $this->_item['issue']->year         = $data[2];
    $this->_item['issue']->pubDate      = NULL;
    $this->_item['issue']->special      = 0;
    $this->_item['issue']->specialComment   = NULL;
    $this->_item['issue']->usePdf           = 0;
    $this->_item['issue']->useContent       = 0;
    $this->_item['issue']->content        = NULL;
    $this->_item['issue']->pdf            = NULL;
    $this->_item['issue']->createdDate      = date('Y-m-d H:i:s');
    $this->_item['issue']->doi          = $article_doi;

    return $this->_item['issue'];
  }

  public function importArticles($languages, archiveModelIssue &$issue, $articles_settings = array('auto_publisher' => 1))
  {

    $element_aliases = array(
      'UDK' => 'УДК',
      'abstract' => array(
        'ru-RU' => 'АННОТАЦИЯ',
        'en-GB' => 'ABSTRACT'
      ),
      'title' => '',
      'keywords' => array(
        'ru-RU' => 'Ключевые слова:',
        'en-GB' => 'Keywords:'
      ),
      'cite' => array(
        'ru-RU' => 'Для цитирования:',
        'en-GB' => 'For citation:'
      ),
      'authors' => array(
        'ru-RU' => array('Об авторах:', 'Об авторе:'),
        'en-GB' => 'Bionotes:'
      ),
      'dates' => array(
        'ru-RU' => array(
          'Received' => 'Поступила в редакцию',
          'Adopted' => 'Принята в доработанном виде',
          'Approved' => 'Одобрена для публикации'
        ),
        'en-GB' => array(
          'Received' => 'Received',
          'Adopted' => 'Adopted in a modified form',
          'Approved' => 'Approved for publication'
        ),
      ),
      'month' => array(
        'ru-RU' => array(
          'января' => '01', 'февраля' => '02', 'марта' => '03', 'апреля' => '04', 'мая' => '05', 'июня' => '06', 'июля' => '07', 'августа' => '08', 'сентября' => '09', 'октября' => '10', 'ноября' => '11', 'декабря' => '12'
        ),
        'en-GB' => array(
          'January' => '01', 'February' => '02', 'March' => '03', 'April' => '04', 'May' => '05', 'June' => '06', 'July' => '07', 'August' => '08', 'September' => '09', 'October' => '10', 'November' => '11', 'December' => '12'
        )
      )

    );
    $element_path = array(
      'udk' => '//*[contains(@class,"UDK")]',
      'doi' => '//*[contains(@class,"abstract-en") and contains(.,"Для цитирования")]',
      'abstract' => array(
        'ru-RU' => '//*[contains(@class,"abstract-ru") and not(contains(.,"Ключевые слова"))]',
        'en-GB' => '//*[contains(@class,"abstract-en") and not(contains(.,"Keywords")) and not(contains(.,"@")) and not(contains(.,"For citation")) and not(contains(.,"Для цитирования"))]'
      ), 
      'title' => array(
        'ru-RU' => '//*[contains(@class,"title-ru")]',
        'en-GB' => '//*[contains(@class,"subhead-en")]',
      ),
      'section' => array(
        'ru-RU' => NULL,
        'en-GB' => NULL
      ),
      'keywords' => array(
        'ru-RU' => '//*[contains(@class,"abstract-ru") and contains(.,"Ключевые слова")]',
        'en-GB' => '//*[contains(@class,"abstract-en") and contains(.,"Keywords")]'
      ),
      'text' => array(
        'ru-RU' => '//*[contains(@class,"text")]',
        'en-GB' => NULL,
      ),
      'references' => array(
        'ru-RU' => '//*[contains(@class,"lit-ru")]',
        'en-GB' => '//*[contains(@class,"lit-en")]'
      ),
      'cite' => array(
        'ru-RU' => '//*[contains(@class,"abstract-en") and contains(.,"Для цитирования")]',
        'en-GB' => '//*[contains(@class,"abstract-en") and contains(.,"For citation")]',
      ),
      'submitedDate' => NULL,
      'editedDate' => NULL,
      'publishedDate' => NULL,
      'authors' => array(
        'ru-RU' =>
        array(
          'list' => "//*[contains(@class, 'author-ru')]",
          'info' => "//*[contains(@class, 'author-info-ru')]",
        ),
        'en-GB' =>
        array(
          'list' => NULL,
          'info' => '//*[contains(@class,"abstract-en") and contains(.,"@")]',
        )
      )
    );

    $article            = new archiveModelArticle;
    $article->artType   = NULL;
    $article->position  = NULL;
    $article->udk       = NULL;

    /*Обработка Doi*/
    $doi_xpath = $this->xml->xpath($element_path['doi']);
    $doi = trim((string) array_shift($doi_xpath));
    $doi = explode('DOI:', $doi);
    $article->doi = str_replace(' ', '', $doi[1]);

    /*Обработка УДК*/
    $udk_xpath = $this->xml->xpath($element_path['udk']);
    $udk = trim((string) array_shift($udk_xpath));
    $udk = str_replace('УДК ', '', $udk);

    $article->udk = $udk;

    $data = explode('.', $article->doi);
    $article->pages  = $data[4];

    $article->pdf = NULL;
    $article->hits = 0;
    $article->published = $articles_settings['auto_publisher'] ? 1 : 0;

    foreach ($languages as $language) {

      $article->title[$language]    = trim((string) array_shift($this->xml->xpath($element_path['title'][$language])));

      foreach ($this->xml->xpath($element_path['abstract'][$language]) as $abstract) {
        $article->abstract[$language] = trim((string)$abstract);
      }

      $fulltext_xpath = $this->xml->xpath($element_path['text'][$language]);

      if (!empty($fulltext_xpath)) {
        foreach ($fulltext_xpath as $text) {
          $article->text[$language][] = trim((string)$text);
        }

        $article->text[$language] = implode('&#13;&#10;', $article->text[$language]);
      } else {
        $article->text[$language] = NULL;
      }
      /*Обработка References*/

      foreach ($this->xml->xpath($element_path['references'][$language]) as $item) {
        $reference = new archiveModelReference;
        $reference->reference = trim(preg_replace('/^(\d)+.?/', '', (string)$item));
        $reference->language = $language;
        $article->reference[$language][] = $reference;
      }

      /*обработка Keywords*/
      foreach (explode(',', str_replace($element_aliases['keywords'], '', (string) array_shift($this->xml->xpath($element_path['keywords'][$language])))) as $item) {
        $article->keywords[$language][]  = trim($item);
      }

      /*Обработка Authors*/

      $authorsRaw = array(
        'list' => array(),
        'info' => array(),
        'position' => array(),
      );

      
      $authorInfo = $this->xml->xpath($element_path['authors'][$language]['info']);
      foreach ($authorInfo as $key => $value) {
        $authorsRaw['info'][] = $value->__toString();
        $authorsRaw['position'][] = $key;
      }

      if ($language == 'ru-RU') {
        $authorFIO = $this->xml->xpath($element_path['authors'][$language]['list']);
        foreach ($authorFIO as $value) {
          $authorsRaw['list'][] = trim(preg_replace('/[\s,]/', ' ', $value->__toString()));
        }
      }

      foreach ($authorsRaw['position'] as $value) {
        $author = new archiveModelAuthor;

        $author->position = $value;

        $temp = explode(';', $authorsRaw['info'][$value]);

        if ($language == 'ru-RU') {
          $authorFIO = explode(' ', $authorsRaw['list'][$value]);

          $author->lastname = array_shift($authorFIO);
          
          $author->surname = trim(implode(' ', $authorFIO));
          
          $author->other = trim($temp[0]);

        } else {
          $authorNameOther = explode('—', $temp[0]);
          $authorFIO = str_replace('Bionotes: ', '', trim($authorNameOther[0]));
          $authorFIO = preg_replace('/\s/', ' ', $authorFIO);
          $authorFIO = explode(' ', $authorFIO);

          $author->lastname = array_pop($authorFIO);
          $author->surname = implode(' ', $authorFIO);
          
          $author->other = trim($authorNameOther[1]);
        }

        $author->org = trim($temp[1]);
        $author->address = trim($temp[2]);
        $author->language = $language;
        
        foreach ($temp as $key => $value) {
          $value = trim($value);
          if (strpos($value, 'Scopus')) {
            $author->scopusId = trim(str_replace(array('Scopus Author ID:', 'Scopus:'), '', $value));
          }
          if (strpos($value, 'Researcher')) {
            $author->wosID = trim(str_replace(array('WoS Researcher ID:', 'ResearcherID:'), '', $value));
          }
          if (preg_match('/(RISC)|(SPIN)|(РИНЦ)/', $value)) {
            $author->spinCode = trim(str_replace(array('RISC Author ID:', 'RISC ID:', 'ID RISC:', 'SPIN-code:', 'РИНЦ ID:', 'SPIN-код:'), '', $value));
          }
          if (strpos($value, 'ORCID')) {
            $author->ORCID = trim(str_replace(array('ORCID:'), '', $value));
          }
          if (strpos($value, '@')) {
            $author->email = $value;
          }
        }

        $article->authors[$author->position][$language] = $author;
        
      }

    }

    if ($issue->num < 10) {
      $date = $issue->year . '-0' . $issue->num . '-01';
    } else {
      $date = $issue->year . '-' . $issue->num . '-01';
    }
    $article->submitedDate = $date;
    $article->publishedDate = $date;
    $article->editedDate = $date;


    $article->createdDate     =   date('Y-m-d');
    $article->editDate        =   date('Y-m-d');

    $articles[] = $article;

    $issue->articles = $articles;


  }
}
