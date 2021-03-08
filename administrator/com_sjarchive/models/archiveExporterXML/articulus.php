<?php
/**
 * Joomla Science Journal Archive Component
 * 
 * @package    SJ.Archive
 * @subpackage com_sjarchive
 * @license    GNU/GPL, see LICENSE.php
 * @link       
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class archiveExporterXMLArticulus
{
    const SCHEMA = NULL;
    protected $xml;
    protected $config = array();

    public function __construct ($title = '', $abstract = '', $issn = '')
    {
		$this->xml = new DOMDocument( "1.0", "UTF-8" );
		$this->xml->preserveWhithSpace = false;
    $this->xml->formatOutput = true;
    
    $params = JComponentHelper::getParams( 'com_sjarchive' );
    $lang = JFactory::getLanguage();
    if ($lang->getTag() == 'ru-RU') {
      $title = $params->get('title_ru');
    } else {
      $title = $params->get('title_en');
    }
    $issn = $params->get('issn');
    
    $this->config['title'] = $title;
    $this->config['abstract'] = $abstract;
    $this->config['issn'] = $issn;
    }

  public function generateXml($issue)
  {
    $articles = &$issue->articles;
    $languages = array('ru-RU', 'en-GB');
    $language_aliases = array('ru-RU' => 'RUS', 'en-GB' => 'ENG');
    $issn = JComponentHelper::getParams('com_sjarchive')->get('issn');

    $journal_node = $this->xml->appendChild(
      $this->xml->createElement('journal')
    );
    $operCard_node = $journal_node->appendChild(
      $this->xml->createElement('operCard')
    );
    $operCard_node->appendChild(
      $this->xml->createElement('operator', $this->config['issn'])
    );
    $operCard_node->appendChild(
      $this->xml->createElement('pid', time())
    );
    $operCard_node->appendChild(
      $this->xml->createElement('date', date('Y-m-d'))
    );
    $operCard_node->appendChild(
      $this->xml->createElement('cntArticle', count($articles))
    );
    $journal_node->appendChild(
      $this->xml->createElement('titleid')
    ); //@task
    $journal_node->appendChild(
      $this->xml->createElement('issn', $issn)
    ); //@task           
    $journal_node->appendChild(
      $this->xml->createElement('eissn', '2304-6600')
    ); //@task                  
    $journalInfo_node = $journal_node->appendChild(
      $this->xml->createElement('journalInfo')
    ); //@task 


    $attr =  $this->xml->createAttribute('lang');
    $attr->value = "RUS";
    $journalInfo_node->appendChild($attr);

    $journalInfo_node->appendChild(
      $this->xml->createElement('title', $this->config['title'])
    );

    $issue_node =  $journal_node->appendChild(
      $this->xml->createElement('issue')
    );

    !empty($issue->volume) ?
      $issue_node->appendChild(
        $this->xml->createElement('volume', $issue->volume)
      ) : NULL;
    $issue_node->appendChild(
      $this->xml->createElement('number', $issue->num)
    );
    !empty($issue->part) ?
      $issue_node->appendChild(
        $this->xml->createElement('part', $issue->volume)
      ) : NULL;
    $issue_node->appendChild(
      $this->xml->createElement('dateUni', $issue->year)
    );
    $issue_node->appendChild(
      $this->xml->createElement('issTitle')
    );
    $issue_node->appendChild(
      $this->xml->createElement('pages')
    );   //@task

    $articles_node = $issue_node->appendChild(
      $this->xml->createElement('articles')
    );

    foreach ($articles as $article) {
      $article_node = $articles_node->appendChild(
        $this->xml->createElement('article')
      );

      $section_value = NULL;

      $section_node = $article_node->appendChild(
        $this->xml->createElement('section')
      );

      //@TASK отслеживание языка разделов  
      foreach ($languages as $language) {
        $secTitle_node =  $section_node->appendChild(
          $this->xml->createElement('section', $article->section[$language])
        );
        $attr =  $this->xml->createAttribute('lang');
        $attr->value = $language_aliases[$language];
        $secTitle_node->appendChild($attr);

        $section_value = $article->section[$language];
      }

      $article_node->appendChild(
        $this->xml->createElement('pages', $article->pages)
      );
      $article_node->appendChild(
        $this->xml->createElement('artType', $article->artType)
      );
      $authors_node = $article_node->appendChild(
        $this->xml->createElement('authors')
      );

      foreach ($article->authors as $key => $author) {
        $author_node  = $authors_node->appendChild(
          $this->xml->createElement('author')
        );

        $attr =  $this->xml->createAttribute('num');
        $attr->value = $key;
        $author_node->appendChild($attr);

        foreach ($languages as $language) {
          $individInfo_node =  $author_node->appendChild(
            $this->xml->createElement('individInfo')
          );


          $attr =  $this->xml->createAttribute('lang');
          $attr->value = $language_aliases[$language];
          $individInfo_node->appendChild($attr);


          $individInfo_node->appendChild(
            $this->xml->createElement('surname', $author[$language]->surname)
          );
          $individInfo_node->appendChild(
            $this->xml->createElement('initials', $author[$language]->lastname)
          );
          if (!empty($author[$language]->org)) {
            $individInfo_node->appendChild(
              $this->xml->createElement('orgName', $author[$language]->org)
            );
          }
          if (!empty($author[$language]->address)) {
            $individInfo_node->appendChild(
              $this->xml->createElement('address', $author[$language]->address)
            );
          }
          if (!empty($author[$language]->other)) {
            $individInfo_node->appendChild(
              $this->xml->createElement('otherInfo', $author[$language]->other)
            );
          }
        }
      }

      $artTitles_node = $article_node->appendChild(
        $this->xml->createElement('artTitles')
      );

      foreach ($languages as $language) {
        $artTitle_node =  $artTitles_node->appendChild(
          $this->xml->createElement('artTitle', $article->title[$language])
        );


        $attr =  $this->xml->createAttribute('lang');
        $attr->value = $language_aliases[$language];
        $artTitle_node->appendChild($attr);
      }

      $abstracts_node = $article_node->appendChild(
        $this->xml->createElement('abstracts')
      );

      foreach ($languages as $language) {
        $abstract_node =  $abstracts_node->appendChild(
          $this->xml->createElement('abstract', $article->abstract[$language])
        );

        $attr =  $this->xml->createAttribute('lang');
        $attr->value = $language_aliases[$language];
        $abstract_node->appendChild($attr);
      }

      foreach ($languages as $language) {
        if (!empty($article->text[$language])) {
          $text_node = $article_node->appendChild(
            $this->xml->createElement('text', $article->text[$language])
          );

          $attr =  $this->xml->createAttribute('lang');
          $attr->value = $language_aliases[$language];
          $text_node->appendChild($attr);
        }
      }
      $codes_node =  $article_node->appendChild(
        $this->xml->createElement('codes')
      );

      if (!empty($article->doi))
        $codes_node->appendChild(
          $this->xml->createElement('doi', $article->doi)
        );
      if (!empty($article->udk))
        $codes_node->appendChild(
          $this->xml->createElement('udk', $article->udk)
        );

      $keywords_node =  $article_node->appendChild(
        $this->xml->createElement('keywords')
      );

      $kwdGroup_node =  $keywords_node->appendChild(
        $this->xml->createElement('kwdGroup')
      );

      $attr =  $this->xml->createAttribute('lang');
      $attr->value = "ANY";
      $kwdGroup_node->appendChild($attr);

      foreach ($languages as $language) {
        foreach ($article->keywords[$language] as $keyword) {
          $kwdGroup_node->appendChild(
            $this->xml->createElement('keyword', $keyword)
          );
        }
      }
      if (!empty($article->reference['ru-RU'])) {
        $references_node =  $article_node->appendChild(
          $this->xml->createElement('references')
        );

        foreach ($article->reference['ru-RU'] as $reference) {

          $references_node->appendChild(
            $this->xml->createElement('reference', $reference->reference)
          );
        }
      }
      if (!empty($article->submitedDate))
        $dates_node = $article_node->appendChild(
          $this->xml->createElement('dates')
        );
      $dates_node->appendChild(
        $this->xml->createElement('dateReceived', $article->submitedDate)
      );
      /*
                $files_node = $article_node->appendChild(
                            $this->xml->createElement('files'));
                            $files_node->appendChild(
                                $this->xml->createElement('file',$article->pdf));*/
    }

    return $this->xml->saveXML();
  }
    
    public function validate()
    {
		libxml_clear_errors ();
		if(!$this->xml->schemaValidate(self::SCHEMA)){
			throw new Exception(libxml_get_last_error()->message);
		}
	}
	
}