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

class archiveImporterXmlNative
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
    $data = trim((string) array_shift($this->xml->xpath(
      "//*[contains(@class,'Vestnik_UDK_DOI')] |
           //*[contains(@class,'abstract_udk-doi')] |
           //*[contains(@class,'vestnik-udk-doi')]|
           //*[contains(@class,'vestnik_udk_doi')]"
    )));
    $data = substr($data, strripos($data, ' ') + 1);

    $this->_item['issue'] = new archiveModelIssue();


    $data = explode('.', $data);

    $this->_item['issue']->num          = $data[3];
    $this->_item['issue']->part         = NULL;
    $this->_item['issue']->volume       = NULL;
    $this->_item['issue']->year         = $data[2];
    $this->_item['issue']->pubDate      = NULL;
    $this->_item['issue']->special      = 0;
    $this->_item['issue']->specialComment   = NULL;
    $this->_item['issue']->usePdf         = 0;
    $this->_item['issue']->useContent       = 0;
    $this->_item['issue']->content        = NULL;
    $this->_item['issue']->pdf            = NULL;
    $this->_item['issue']->createdDate      = date('Y-m-d H:i:s');
    $this->_item['issue']->doi             = $this->makeDoi($doi_settings);

    return $this->_item['issue'];
  }

  protected function makeDoi($doi_settings)
  {
    $issn = JComponentHelper::getParams('com_sjarchive')->get('issn');
    $doi = $doi_settings['core'] . '/' . $issn;
    $doi .= $doi_settings['useYear'] ? '.' . $this->_item['issue']->year : '';
    $doi .= $doi_settings['useNum'] ? '.' . $this->_item['issue']->num : '';
    $doi .= $doi_settings['usePart'] ? '.' . $this->_item['issue']->part : '';
    $doi .= $doi_settings['useVolume'] ? '.' . $this->_item['issue']->volume : '';


    return $doi;
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
      'doi-udk' => "//*[contains(@class,'vestnik_udk_doi')] |
        //*[contains(@class,'Vestnik_UDK_DOI')] |
        //*[contains(@class,'abstract_udk-doi')] |
        //*[contains(@class,'vestnik-udk-doi')]",
      'abstract' => array(
        'ru-RU' => '//*[contains(@class,"vestnik_abstract_ru") and not(contains(.,"АННОТАЦИЯ")) and not(contains(.,"Для цитирования: "))  and not(contains(.,"Ключевые слова:"))]/node() |
        //*[contains(@class,"ВЕСТНИК-2017_ВРЕЗ") and not(contains(.,"АННОТАЦИЯ")) and not(contains(.,"Для цитирования: "))  and not(contains(.,"Ключевые слова:"))]/node() |
        //*[contains(@class,"abstract_abstract-ru") and not(contains(.,"АННОТАЦИЯ")) and not(contains(.,"Ключевые слова:")) and not(contains(.,"ДЛЯ ЦИТИРОВАНИЯ:")) and not(contains(.,"Для цитирования:"))] |
        //*[contains(@class,"vestnik-abstract") and contains(.,"АННОТАЦИЯ")]/preceding-sibling::*[contains(@class,"vestnik-abstract")]', //подумать как вырезать ключевые слова
        'en-GB' => '//*[contains(@class,"vestnik_abstract_en") and not(contains(.,"ABSTRACT")) and not(contains(.,"For citation:"))  and not(contains(.,"Keywords:"))]/node() |
        //*[contains(@class,"ВЕСТНИК-2017_ВРЕЗ") and not(contains(.,"ABSTRACT")) and not(contains(.,"For citation:"))  and not(contains(.,"Keywords:"))]/node() |
        //*[contains(@class,"abstract_abstract-en") and not(contains(.,"ABSTRACT")) and not(contains(.,"Keywords:")) and not(contains(.,"FOR CITATION:")) and not(contains(.,"For citation:"))] |
        //*[contains(@class,"vestnik-abstract") and contains(.,"ABSTRACT")]/following-sibling::*[contains(@class,"vestnik-abstract")]/self::*[contains(@class,"vestnik-abstract") and not(contains(.,"ABSTRACT")) and not(contains(.,"For citation:"))  and not(contains(.,"Keywords:"))]/node()'
      ), //подумать как вырезать ключевые слова
      'title' => array(
        'ru-RU' => '//*[contains(@class,"ВЕСТНИК-2017_ЗАГОЛОВОК-РУС")] |
        //*[contains(@class,"vestnik_title_ru")] |
        //*[contains(@class,"abstract_title-ru")] |
        //*[contains(@class,"vestnik-title-ru")]',
        'en-GB' => '//*[contains(@class,"vestnik_title_en")] |
        //*[contains(@class,"ВЕСТНИК-2017_ЗАГОЛОВОК-АНГЛ")] |
        //*[contains(@class,"abstract_title-en")] | 
        //*[contains(@class,"vestnik-title-en")]',
      ),
      'section' => array(
        'ru-RU' => '//*[contains(@class,"vestnik_chapter")] |
        //*[contains(@class,"ВЕСТНИК-2017_РУБРИКИ")] |
        //*[contains(@class,"CHAPTER")]',
        'en-GB' => NULL
      ),
      'keywords' => array(
        'ru-RU' => '//*[contains(@class,"vestnik_abstract_ru") and contains(.,"Ключевые слова")] |
        //*[contains(@class,"ВЕСТНИК-2017_ВРЕЗ") and contains(.,"Ключевые слова")] |
        //*[contains(@class,"abstract_keywords-ru")] |
         //*[contains(@class,"abstract_keywords_ru")] |
        //*[contains(@class,"abstract_abstract-ru") and contains(.,"Ключевые слова:")] |
         //*[contains(@class,"abstract_abstract_ru") and contains(.,"КЛЮЧЕВЫЕ СЛОВА:")] |
        //*[contains(@class,"vestnik-abstract") and contains(.,"Ключевые слова")] ',
        'en-GB' => '//*[contains(@class,"vestnik_abstract_en") and contains(.,"Keywords")] |
        //*[contains(@class,"ВЕСТНИК-2017_ВРЕЗ") and contains(.,"Keywords")] |
        //*[contains(@class,"abstract_keywords-en")] | 
        //*[contains(@class,"abstract_keywords_en")] | 
        //*[contains(@class,"abstract_abstract-en") and contains(.,"Keywords:")] |
        //*[contains(@class,"abstract_keywords-en") and contains(.,"Keywords:")] |
        //*[contains(@class,"abstract_keywords_en") and contains(.,"KEYWORDS:")] |
        //*[contains(@class,"vestnik-abstract") and contains(.,"Keywords")] '
      ),
      'text' => array(
        'ru-RU' => '//descendant-or-self::*[contains(@class,"ВЕСТНИК-2017_Текст")] |
        //self::*[contains(@class,"ВЕСТНИК-2017_list")] |
        //descendant-or-self::*[contains(@class,"text") or contains(@class,"subhead2") or contains(@class,"where") or  contains(@class,"equation") or  contains(@class,"list") ] |
        //descendant-or-self::*[contains(@class,"vestnik-text") or contains(@class,"vestnik-list") or contains(@class,"vestnik-subhead2")] |
          //descendant-or-self::*[contains(@class,"vestnik_text") or contains(@class,"subhead2") or contains(@class,"where") or  contains(@class,"equation") or  contains(@class,"list") ] |
        //descendant-or-self::*[contains(@class,"vestnik_text") or contains(@class,"vestnik-list") or contains(@class,"vestnik-subhead2")] ',

      ),
      'references' => array(
        'ru-RU' => '//ancestor-or-self::*[contains(@class, "ВЕСТНИК-2017_lit1")] |
        //ancestor-or-self::*[contains(@class, "lit1")] |
        //ancestor-or-self::*[contains(@class, "lit-ru")]|
        //ancestor-or-self::*[contains(@class, "vestnik-lit1")]|
        //ancestor-or-self::*[contains(@class, "vestnik_lit1")]',

        'en-GB' => '//ancestor-or-self::*[contains(@class, "ВЕСТНИК-2017_lit1")] |
        //ancestor-or-self::*[contains(@class, "lit-en")] 
        | //ancestor-or-self::*[contains(@class, "vestnik-lit1")]|
        //ancestor-or-self::*[contains(@class, "vestnik_lit1")]'
      ),
      'cite' => array(
        'ru-RU' => '//*[contains(@class,"vestnik_abstract_ru") and contains(.,"Для цитирования:")] |
        //*[contains(@class,"ВЕСТНИК-2017_ВРЕЗ") and contains(.,"Для цитирования:")] |
        //*[contains(@class,"abstract_quote-ru")] |  //*[contains(@class,"abstract_abstract-en") and contaions(.,"ДЛЯ ЦИТИРОВАНИЯ:")] |
        //*[contains(@class,"vestnik-abstract") and contains(.,"Для цитирования:")]',
        'en-GB' => '//*[contains(@class,"vestnik_abstract_en") and contains(.,"For citation:")] |
        //*[contains(@class,"ВЕСТНИК-2017_ВРЕЗ") and contains(.,"For citation:")] |
        //*[contains(@class,"abstract_quote-en")] |  //*[contains(@class,"abstract_abstract-en") and contaions(.,"FOR CITATION:")] |
        //*[contains(@class,"vestnik-abstract") and contains(.,"For citation:")]',
      ),
      'submitedDate' => '//*[contains(@class,"vestnik_dates") and contains(.,"Поступила в редакцию")] |
      //*[contains(@class,"ВЕСТНИК-2017_в-редакцию") and contains(.,"Поступила в редакцию")] |
      //*[contains(@class,"date") and contains(.,"Поступила в редакцию")] |
      //*[contains(@class,"vestnik-в-редакцию") and contains(.,"Поступила в редакцию")] ',
      'editedDate' => '//*[contains(@class,"vestnik_dates") and contains(.,"Принята в доработанном виде")] |
      //*[contains(@class,"ВЕСТНИК-2017_в-редакцию") and contains(.,"Принята в доработанном виде")] |
      //*[contains(@class,"date") and contains(.,"Принята в доработанном виде")] | //*[contains(@class,"vestnik-в-редакцию") and contains(.,"Принята в доработанном виде")]',
      'publishedDate' => '//*[contains(@class,"vestnik_dates") and contains(.,"Одобрена для публикации")] |
      //*[contains(@class,"ВЕСТНИК-2017_в-редакцию") and contains(.,"Одобрена для публикации")] | 
      //*[contains(@class,"date") and contains(.,"Одобрена для публикации")] | //*[contains(@class,"vestnik-в-редакцию") and contains(.,"Одобрена для публикации")]',
      'authors' => array(
        'ru-RU' =>
        array(
          'list' => "//*[contains(@class, 'vestnik_authors_ru')] |
          //*[contains(@class, 'ВЕСТНИК-2017_Авторы-РУС')] |
          //*[contains(@class, 'abstract_authors-ru')] | 
          //*[contains(@class, 'vestnik-authors_ru')]",
          'affiliation' => "//*[contains(@class, 'vestnik_authors_ru')]/following::*[contains(@class,'vestnik_authors_org_ru')][1] |
          //*[contains(@class, 'ВЕСТНИК-2017_Авторы-РУС')]/following::*[contains(@class,'ВЕСТНИК-2017_Адрес_авторов')][1] |
          //*[contains(@class, 'abstract_authors-inf-ru')]",
          'bionotes' => "//*[contains(@class,'vestnik_about_the_author') and contains(.,'#surname_placeholder#')] |
          //*[contains(@class,'ВЕСТНИК-2017_об-авторах') and contains(.,'#surname_placeholder#')] |
          //*[contains(@class,'bionotes') and contains(.,'#surname_placeholder#')]",
        ),
        'en-GB' =>
        array(
          'list' => "//*[contains(@class, 'vestnik_authors_en')] |
          //*[contains(@class, 'ВЕСТНИК-2017_Авторы-АНГ')] |
          //*[contains(@class, 'abstract_authors-en')] |
          //*[contains(@class, 'vestnik-authors-en')]",
          'affiliation' => "//*[contains(@class, 'vestnik_authors_en')]/following::*[contains(@class,'vestnik_authors_org_en')][1] |
          //*[contains(@class, 'ВЕСТНИК-2017_Авторы-АНГ')]/following::*[contains(@class,'ВЕСТНИК-2017_Адрес_авторов')][1] |
          //*[contains(@class, 'abstract_authors-inf-en')] ",
          'bionotes' => "//*[contains(@class,'vestnik_about_the_author') and contains(.,'#surname_placeholder#')] | 
          //*[contains(@class,'ВЕСТНИК-2017_об-авторах') and contains(.,'#surname_placeholder#')] | 
          //*[contains(@class,'bionotes') and contains(.,'#surname_placeholder#')]",
        )
      )
    );



    $article            = new archiveModelArticle;
    $article->artType   = NULL;
    $article->position  = NULL;
    $article->udk       = NULL;

    /*Обработка Doi*/
    $data = explode('DOI:', trim((string) array_shift($this->xml->xpath($element_path['doi-udk']))));


    $article->doi = trim($data[1]);
    $article->udk = trim(str_replace($element_aliases['UDK'], '', $data[0]));
    $data = explode('.', $article->doi);
    $article->pages  = $data[4];

    /*
     * Разбить по 'DOI:' 
     * [0] - Удалить УДК и trim
     * [1] - Удалить DOI: и trim
     */

    $article->pdf = NULL;
    $article->hits = 0;
    $article->published = $articles_settings['auto_publisher'] ? 1 : 0;

    foreach ($languages as $language) {

      $article->title[$language]    = trim((string) array_shift($this->xml->xpath($element_path['title'][$language])));

      foreach ($this->xml->xpath($element_path['abstract'][$language]) as $abstract) {
        $article->abstract[$language][] = trim((string)$abstract);
      }

      $article->abstract[$language] = implode('&#13;&#10;', $article->abstract[$language]);
      if (!empty($this->xml->xpath($element_path['section'][$language])))
        $article->section[$language]  = trim((string) array_shift($this->xml->xpath($element_path['section'][$language])));

      if (!empty($this->xml->xpath($element_path['text'][$language]))) {
        foreach ($this->xml->xpath($element_path['text'][$language]) as $text) {
          $article->text[$language][] = trim((string)$text);
        }

        $article->text[$language] = implode('&#13;&#10;', $article->text[$language]);
      }
      /*Обработка References*/
      $tmp = NULL;
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

      /*
     * 
     */
      $temp = preg_split('/,[\x20\xa0\s]/', (string) array_shift($this->xml->xpath($element_path['authors'][$language]['list'])));

      foreach ($temp as $key => $item) {

        preg_match_all('/\d[,\d]*/', $item, $affiliations); //ищет вхождение вида "число" или "число, число"+, кладем вхождения во временную переменную

        $item = preg_replace('/\d[,\d]*/', '', trim($item)); //ищет вхождение вида "число" или "число, число"+
        $affiliations = array_shift($affiliations);

        //echo ($item);

        $author = new archiveModelAuthor;
        $author->position = $key + 1;

        //Если нет сокращений все считать фамилией 

        if (strpos($item, '.')) {
          $str = preg_split('/[\x20\xa0\s]/', $item, -1, PREG_SPLIT_NO_EMPTY);

          $author->surname  =  $language == 'ru-RU' ?  trim($str[1]) : trim($str[2]);
          $author->firstname =  $language == 'ru-RU' ?  trim($str[0]) : trim($str[0]) . ' ' . trim($str[1]);
        } else {
          $author->surname = $item;
        }

        if (!empty($affiliations)) {

          foreach (explode(';', trim((string) array_shift($this->xml->xpath($element_path['authors'][$language]['affiliation'])))) as $key => $organization) {

            if (($key  % 2) == 0) {

              foreach ($affiliations as $affiliation) {
                foreach (explode(',', $affiliation) as $affil) {


                  if (trim($organization)[0] == $affil) {
                    $organization = trim($organization);

                    $org_name = true;
                    $author->org[] = trim(substr($organization, 1));
                  }
                }
              }
            } else {
              if ($org_name == true) {
                if (!array_search(trim(substr($organization, 1)), $author->address)) {
                  $author->address[] = trim(substr($organization, 1));
                  $org_name = false;
                }
              }
            }
          }


          // Если аффилиации не указаны, все авторы из одной организации
          if (!empty($author->org))
            $author->org      = implode(', ', $author->org);
          if (!empty($author->address))
            $author->address  = implode(', ', $author->address);
        } else {

          $additional_data = NULL;
          $affiliation_data = explode(';', (string) array_shift($this->xml->xpath($element_path['authors'][$language]['affiliation'])));
          $author->org = trim($affiliation_data[0]);
          $author->address = trim($affiliation_data[1]);
        }


        //Не правильно разбирает язык! Проверить!
        $additional_data = NULL;

        $surname_paceholder = $language == 'ru-RU' ?  $author->surname : $author->firstname . ' ' . $author->surname;


        $additional_data = trim(str_replace(
          $element_aliases['authors'][$language],
          '',
          (string) array_shift(
            $this->xml->xpath(str_replace(
              '#surname_placeholder#',
              $surname_paceholder,
              $element_path['authors'][$language]['bionotes']
            ))
          )
        ));




        $additional_data  = explode($author->surname, $additional_data);
        $author->firstname = trim($additional_data[0], " \t\n\r\0\x0B");

        $additional_data  = explode(';', $additional_data[1]);
        $author->other    = trim(str_replace(
          array('—'),
          "",
          $additional_data[0]
        ));
        unset($additional_data[0]);
        $author->other = trim($author->other, chr(0xC2) . chr(0xA0) . chr(0x20));
        $author->language = $language;

        foreach ($additional_data as $element) {
          if (strpos($element, '@')) {

            $author->email = trim($element);

            $author->email = substr($author->email, -1) == '.' ? substr($author->email, 0, -1)  : $author->email;
          }

          if (strpos($element, 'Scopus') or strpos($element, 'Researcher ID') or strpos($element, 'RISC') or strpos($element, 'SPIN-code ') or strpos($element, 'ORCID')) {
            foreach (explode(',', $element) as $science_id) {
              if (strpos($science_id, 'Scopus')) {
                $author->scopusId = trim(str_replace(array('Scopus Author ID:', 'Scopus:'), '', $science_id));
              }

              if (strpos($science_id, 'Researcher ID')) {
                $author->wosID = trim(str_replace(array('WoS Researcher ID:'), '', $science_id));
              }
              if (strpos($science_id, 'RISC')) {
                $author->spinCode = trim(str_replace(array('RISC Author ID:', 'RISC ID:', 'ID RISC:', 'SPIN-code '), '', $science_id));
              }
              if (strpos($science_id, 'ORCID')) {
                $author->ORCID = trim(str_replace(array('ORCID:'), '', $science_id));
              }
            }
          }
        }

        $article->authors[$author->position][$language] = $author;
      }
    }

    $tmp = trim(str_replace(array($element_aliases['dates']['ru-RU']['Received'], 'г.'), '', (string) array_shift($this->xml->xpath($element_path['submitedDate']))));
    $tmp = preg_split('/[\x20\xa0\s]/', $tmp);
    $tmp[1] = $element_aliases['month']['ru-RU'][$tmp[1]];

    $tmp[1] = strlen($tmp[1]) == 1 ? '0' . $tmp[1] : $tmp[1];
    $tmp[0] = strlen($tmp[0]) == 1 ? '0' . $tmp[0] : $tmp[0];
    $tmp[2] = strlen($tmp[2]) == 1 ? '0' . $tmp[2] : $tmp[2];

    $article->submitedDate    =   $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];

    $tmp = trim(str_replace(array($element_aliases['dates']['ru-RU']['Adopted'], 'г.'), '', (string) array_shift($this->xml->xpath($element_path['editedDate']))));
    $tmp = explode(' ', $tmp);
    $tmp[1] = $element_aliases['month']['ru-RU'][$tmp[1]];
    $tmp[1] = strlen($tmp[1]) == 1 ? '0' . $tmp[1] : $tmp[1];
    $tmp[0] = strlen($tmp[0]) == 1 ? '0' . $tmp[0] : $tmp[0];
    $tmp[2] = strlen($tmp[2]) == 1 ? '0' . $tmp[2] : $tmp[2];
    $article->editedDate      =   $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];

    $tmp =  trim(str_replace(array($element_aliases['dates']['ru-RU']['Approved'], 'г.'), '', (string) array_shift($this->xml->xpath($element_path['publishedDate']))));
    $tmp = preg_split('/[\x20\xa0\s]/', $tmp);

    $tmp[1] = $element_aliases['month']['ru-RU'][$tmp[1]];
    $tmp[1] = strlen($tmp[1]) == 1 ? '0' . $tmp[1] : $tmp[1];
    $tmp[0] = strlen($tmp[0]) == 1 ? '0' . $tmp[0] : $tmp[0];
    $tmp[2] = strlen($tmp[2]) == 1 ? '0' . $tmp[2] : $tmp[2];
    $article->publishedDate   =   $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];

    $article->createdDate     =   date('Y-m-d');
    $article->editDate        =   date('Y-m-d');

    $articles[] = $article;

    $issue->articles = $articles;
  }
}
