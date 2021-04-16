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

class archiveImporterXmlElibrary {
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

    // if ($error = libxml_get_last_error())
    //   throw new Exception($error->message);
  }

  public function importIssue($doi_settings = array('core' => '10.22227', 'useNum' => '1', 'x' => 0, 'useVolume' => 0, 'useYear' => 1, 'useDoi' => 1)) {
    
    $issn = JComponentHelper::getParams('com_sjarchive')->get('issn');
    
    $this->_item['issue'] = new archiveModelIssue();

    $doiXpath = $this->xml->xpath("//a[contains(@href, 'doi.org')]");
    $numXpath = $this->xml->xpath("//a[@title='Оглавление выпуска']");
    $yearXpath = $this->xml->xpath("//td[contains(.,'Номер')][last()]/font[1]");
  
    $articleDoi = trim((string) array_shift($doiXpath));    
    if (strlen($articleDoi) != 0) {
      $data = explode('.', $articleDoi);
      $this->_item['issue']->num = $data[3];
      $this->_item['issue']->year = $data[2];
      $this->_item['issue']->doi = substr($articleDoi, 0, 25);
    } else {
      $this->_item['issue']->num = array_shift($numXpath)->__toString();
      $this->_item['issue']->year = array_shift($yearXpath)->__toString();
      $this->_item['issue']->doi = $doi_settings['core'].'/'.$issn.'.'.$this->_item['issue']->year.'.'.$this->_item['issue']->num;
    }

    $this->_item['issue']->part         = NULL;
    $this->_item['issue']->volume       = NULL;
    $this->_item['issue']->pubDate      = NULL;
    $this->_item['issue']->special      = 0;
    $this->_item['issue']->specialComment   = NULL;
    $this->_item['issue']->usePdf       = 0;
    $this->_item['issue']->useContent   = 0;
    $this->_item['issue']->content      = NULL;
    $this->_item['issue']->pdf        	= NULL;
    $this->_item['issue']->createdDate  = date('Y-m-d H:i:s');

    return $this->_item['issue'];
  }

  public function importArticles($languages,archiveModelIssue &$issue, $articles_settings = array('auto_publisher' => 1)) {

    $element_path = array(
      'udk' => "//td[contains(text(),'УДК')]/font",
      'doi' => "//a[contains(@href, 'doi.org')]",
      'year' => "//td[contains(.,'Номер')][last()]/font[1]",
      'number' => "//a[@title='Оглавление выпуска']",
      // 'pages' => "//td[contains(.,'Номер')][last()]/font[2]",
      'pages' => "//div[contains(.,'Страницы')]/font",
      'abstract' => array(
        'ru-RU' => "//div[@id='abstract2']/p",
        'en-GB' => "//div[@id='eabstract2']/p",
        ),
      'abstract-short' => array(
        'ru-RU' => "//div[@id='abstract1']/p",
        'en-GB' => "//div[@id='eabstract1']/p",
        ),
      'title' => array(
        'ru-RU' => "//p[@class='bigtext']",
        'en-GB' => "(//tbody[contains(.,'ОПИСАНИЕ НА АНГЛИЙСКОМ ЯЗЫКЕ')])[last()]/tr/td/font[@color='#F26C4F']",
        ),
      'keywords' => "//a[contains(@href, 'keyword_items')]", // русский и английский вместе
      'authors' => array(
        'list' => "//img[contains(@class, 'help pointer')]/preceding-sibling::a/b | //img[contains(@class, 'help pointer')]/preceding-sibling::b/font | //img[contains(@class, 'help pointer')]/preceding-sibling::a | //img[contains(@class, 'help pointer')]/preceding-sibling::font | //img[contains(@class, 'help pointer')]/preceding-sibling::span/b/font", // русский и английский
        'affiliation' => "",  // русский и английский
        // 'bionotes' => "//div[contains(@class, 'tooltip')]", // русский и английский
      ),
    );


    $article            = new archiveModelArticle;
    $article->published = $articles_settings['auto_publisher'] ? 1 : 0;
    $article->artType   = NULL;
    $article->position  = NULL;
    $article->pdf       = NULL;
    $article->keywords = array(
      'ru-RU' => array(),
      'en-GB' => array(),
    );

    $xpath = array(
      'udk' => $this->xml->xpath($element_path['udk']),
      'pages' => $this->xml->xpath($element_path['pages']),
      'titleRU' => $this->xml->xpath($element_path['title']['ru-RU']),
      'titleEN' => $this->xml->xpath($element_path['title']['en-GB']),
      'keywords' => $this->xml->xpath($element_path['keywords']),
    );

    $article->udk = trim((string) array_shift($xpath['udk']));
    $article->pages = trim((string) array_shift($xpath['pages']));
    $article->title['ru-RU'] = $this->mb_ucfirst(mb_strtolower(trim((string) array_shift($xpath['titleRU']))));
    $article->title['en-GB'] = ucfirst(strtolower(trim((string) array_shift($xpath['titleEN']))));

    
    /*Обработка Doi*/	

    $article->doi = $issue->doi.'.'.$article->pages;

    /*обработка Keywords*/
    $keywords = array();
    foreach ($xpath['keywords'] as &$keyword) {
        $keywords[] = mb_strtolower($keyword->__toString());
      }

    foreach ($keywords as $value) {
      $keyword = preg_replace('/\s+/', ' ', $value);
      $keyword = preg_replace('/\'/', '`', $value);
      if (preg_match('/[a-z]/', $keyword)) {
        array_push($article->keywords['en-GB'], $keyword);
      } else {
        array_push($article->keywords['ru-RU'], $keyword);
      }
    }


    /*Обработка Authors*/
    $authors = array(
      'ru-RU' => array(
        'list' => array(),
        'bionotes' => array(),
      ),
      'en-GB' => array(
        'list' => array(),
        'bionotes' => array(),
      ),
    );

    $authorsList = $this->xml->xpath($element_path['authors']['list']);
    // $bionotesRuEn = $this->xml->xpath($element_path['authors']['bionotes']);

    foreach ($authorsList as $author) {
      $author = $author->__toString();
      // пробел после точки
      $pattern = '/\.([А-Я]|[A-Z])/';
      $replacement = '. $1';
      $author = preg_replace($pattern, $replacement, $author);
      // убрать апостроф
      $author = preg_replace('/\'/', '', $author);
      if (strlen($author) == 0) {} 
      else if (preg_match('/[A-Z]/', $author)) {
        if (ctype_upper(substr($author, 0, 2))) {
          $authors['en-GB']['list'][] = mb_convert_case($author, MB_CASE_TITLE, "UTF-8");
        } else {
          $authors['en-GB']['list'][] = $author;
        }
      } else {
        $authors['ru-RU']['list'][] = mb_convert_case($author, MB_CASE_TITLE, "UTF-8");
      }
    }

    if (count($authors['en-GB']['list']) !== count($authors['ru-RU']['list'])) {
      echo '<div>Warning: Авторы на русском и английском не совпадают!</div>';
    }

    // foreach (array_slice($bionotesRuEn, 0, count($bionotesRuEn) / 2) as $item) {
    //   $authors['ru-RU']['bionotes'][] = $item->__toString();
    // }
    // foreach (array_slice($bionotesRuEn, count($bionotesRuEn) / 2) as $item) {
    //   $authors['en-GB']['bionotes'][] = $item->__toString();
    // }



    foreach ($languages as $language) {
      foreach ($authors[$language]['list'] as $key=>$item) {
        $author = new archiveModelAuthor();
        $author->language = $language;
        $author->position = $key;
  
        $item = preg_replace( '/\s+/u', ' ' , $item);
        $space = mb_strpos($item, " ");

        $author->surname = mb_substr($item, 0, $space, 'utf-8');
        $author->firstname = trim(mb_substr($item, $space, strlen($item), 'utf-8'));
        // $author->other = $authors[$language]['bionotes'][$key];
  
        $article->authors[$author->position][$language] = $author;
      }
    }


    // affiliation
    $xpathNumbers = "//img[contains(@class, 'help pointer')]/following-sibling::font[1]/sup";
    $xpathOrganisation = "//td/child::*/following-sibling::font[@color='#000000']/following-sibling::*[1]";

    $numbers = $this->xml->xpath($xpathNumbers);
    $organisation = $this->xml->xpath($xpathOrganisation);
    array_shift($organisation);


    $organisations = array(
      'ru-RU' => array(),
      'en-GB' => array(),
    );


    foreach ($organisation as $value) {
      if ($value) {
        $str = $value->__toString();
        if (!$str) {
          $str = $value->font->__toString();
        } 
        $orgName = preg_replace('/\s+/', ' ', $str);
        $orgName = preg_replace('/\'/', '`', $orgName);
        if (preg_match('/[A-Z]/', $orgName)) {
          array_push($organisations['en-GB'], $orgName);
        } else {
          array_push($organisations['ru-RU'], $orgName);
        }
      }
    }

    for ($i=0; $i < count($numbers); $i++) { 
      $numbers[$i] = explode(',', $numbers[$i]->__toString());
    }

    
    // проверить, соответствует ли количество сносок на место работы количеству авторов
    if (count($numbers) / 2 == count($article->authors)) {
      $authorsOrgNumber['ru-RU'] = array_slice($numbers, 0, count($numbers) / 2);
      $authorsOrgNumber['en-GB'] = array_slice($numbers, count($numbers) / 2);
    } else if (count($numbers) == count($article->authors)) {
      $authorsOrgNumber['ru-RU'] = $numbers;
      echo '<div>Warning: Не определена аффилиация для английского языка</div>';
    } else {
      echo '<div>Warning: Ошибка в определении аффилиации</div>';
    }

    

    

    foreach ($languages as $language) {
      
      if (count($organisations[$language]) > 0) {
        $organisations[$language] = array_combine(range(1, count($organisations[$language])), $organisations[$language]);
      }
      
      for ($i=0; $i < count($article->authors); $i++) { 
        if ($authorsOrgNumber[$language] !== NULL) {
          foreach ($authorsOrgNumber[$language][$i] as $value) {
            array_push($article->authors[$i][$language]->org, $organisations[$language][$value]);
          }

          $article->authors[$i][$language]->org = implode(', ', $article->authors[$i][$language]->org);
        }
      }
    
      // проверить, сокращена ли аннотация
      $xpath['abstractLong'][$language] = $this->xml->xpath($element_path['abstract'][$language]);
      $xpath['abstractShort'][$language] = $this->xml->xpath($element_path['abstract-short'][$language]);
      $abstractLong = array_shift($xpath['abstractLong'][$language]);
      $abstractShort = array_shift($xpath['abstractShort'][$language]); 
      if (!$abstractLong) {
      	$abstract = $abstractShort;
      } else {
        $abstract = $abstractLong;
      }


      $article->abstract[$language] = preg_replace('/\s+/', ' ', trim((string) $abstract));
    
      $article->reference[$language] = array();
    }
  
    $article->abstract['en-GB'] = preg_replace('/\'/', '`', $article->abstract['en-GB']);
    
    if ($issue->num < 10) {
      $date = $issue->year . '-0' . $issue->num . '-01';
    } else {
      $date = $issue->year . '-' . $issue->num . '-01';
    }
    $article->submitedDate = $date;
    $article->publishedDate = $date;
    $article->editedDate = $date;

    
    $article->createdDate = date('Y-m-d');
    $article->editDate = date('Y-m-d');

    // $this->checkInputErrors($article);

    $articles[] = $article;

    $issue->articles = $articles;
  }

  function checkInputErrors($article) {
    echo '<pre>';
    var_dump($article);
    echo '</pre>';
    die();
  }

  protected function mb_ucfirst($str, $encoding='UTF-8')
    {
      $str = mb_ereg_replace('^[\ ]+', '', $str);
      $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
             mb_substr($str, 1, mb_strlen($str), $encoding);
      return $str;
    }

}
