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

class archiveExporterXMLDoaj
{
    const SCHEMA = 'https://doaj.org/static/doaj/doajArticles.xsd';
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
      $this->config['publisher']  = 'National Research Moscow State University of Civil Engineering (MGSU)';
    }

    public function generateXml($issue)
    {
        $languages = array( 'en-GB','ru-RU',);
        $language_aliases = array ('ru-RU'=>'rus','en-GB'=>'eng');
        $articles = &$issue->articles;
        $records_node = $this->xml->appendChild(
            $this->xml->createElement('records')
        );
        if (!$articles)
            throw new Exception(JTEXT::_('ARTICULUS.ARTICLES.EMPTY'));
        foreach ($articles as $article) 
        {
            $record_node = $this->xml->createElement('record');


            $record_node->appendChild(
                $this->xml->createElement('language', (strtolower($language_aliases[$article->translation]))));
            $record_node->appendChild(
                $this->xml->createElement('publisher', $this->config['publisher'])); /*Вставить из настроек*/
            $record_node->appendChild(
                $this->xml->createElement('journalTitle', 'Vestnik MGSU')); /*Вставить из настроек*/
                $record_node->appendChild(
                    $this->xml->createElement('issn', '19970935')); /*Вставить из настроек*/

            $record_node->appendChild(
                $this->xml->createElement('publicationDate', $issue->pubDate));
                if(!empty($issue->volume))
                $record_node->appendChild(
                    $this->xml->createElement('volume', $issue->volume));

                $record_node->appendChild(
                    $this->xml->createElement('issue', $issue->num)); /*Вставить из настроек*/

            $record_node->appendChild(
                $this->xml->createElement('startPage', explode('-', $article->pages)[0]));
            $record_node->appendChild(
                $this->xml->createElement('endPage', explode('-', $article->pages)[1]));

            if(!empty($article->doi))
            $record_node->appendChild(
                $this->xml->createElement('doi', $article->doi));
            elseif($article->artType === 'RAR')
            {      
                throw new Exception (JTEXT::_('ARTICULUS.ARTICLES.RAR.NODOI'));
            }
            
            $record_node->appendChild(
                    $this->xml->createElement('documentType', 'article')); /*Вставить из настроек*/
            
            foreach ($languages as $language) {

                $title_node = $record_node->appendChild(
                    $this->xml->createElement('title', $article->title[$language]));

                $attr =  $this->xml->createAttribute('language');
                $attr->value = strtolower($language_aliases[$language]);
                    $title_node->appendChild( $attr );
    
            }
            $authors_node = $record_node->appendChild(
                $this->xml->createElement('authors'));
            
            $affiliations_list = array();

                foreach ($article->authors as $author) {
                    $author_node = $authors_node->appendChild(
                        $this->xml->createElement('author'));
                    $author_node->appendChild(
                        $this->xml->createElement('name', $author['en-GB']->firstname  . ' ' .$author['en-GB']->surname ));
                    if(!empty($author['en-GB']->email))
                        $author_node->appendChild(
                            $this->xml->createElement('email', $author['en-GB']->email));
                    
                    foreach (explode(';', $author['en-GB']->org) as $org) 
                    {

                        $affiliations_id = array_search($org, $affiliations_list);
                    
                        if ($affiliations_id === FALSE)
                        {
                            $affiliations_list[] = $org;
                            $affiliations_id = count($affiliations_list);
                        }  else 
                        {
                            $affiliations_id =$affiliations_id+1;
                        }
                        $author_node->appendChild(
                            $this->xml->createElement('affiliationId', $affiliations_id)
                        );
                    }
                }
                $affiliations_node = $record_node->appendChild(
                    $this->xml->createElement('affiliationsList'));
                foreach ($affiliations_list as $affiliation_id => $affiliation_name) 
                {
                    $affiliation_node = $affiliations_node->appendChild(
                        $this->xml->createElement('affiliationName', $affiliation_name));
                    
                    $attr =  $this->xml->createAttribute('affiliationId');
                    $attr->value = $affiliation_id + 1;
                    $affiliation_node->appendChild( $attr );
                }

            foreach ($languages as $language) 
            {
                if(!empty($article->abstract[$language]))
                {     
                    $abstract_node = $record_node->appendChild(
                        $this->xml->createElement('abstract', htmlspecialchars($article->abstract[$language])));
                    $attr =  $this->xml->createAttribute('language');
                    $attr->value =strtolower($language_aliases[$language]);
                    $abstract_node->appendChild( $attr );
                } elseif($article->artType == 'RAR'){       
                    throw new Exception (JTEXT::_('ARTICULUS.ARTICLES.RAR.NOABSTRACT'));}


            }
  
            $pdf_node = $record_node->appendChild(
                $this->xml->createElement(  'fullTextUrl',
                                                $article->doi ? 'https://doi.org/'.$article->doi :
                                                'http://vestnikmgsu.ru/ru/component/sjarchive/issue/article.download/'.$issue->year.'/'.$issue->num.'/'.$article->pages
                ));
            
            $attr =  $this->xml->createAttribute('format');
            $attr->value = 'pdf';
            $pdf_node->appendChild( $attr );

            foreach ($languages as $language) 
            {
                if(!empty($article->keywords[$language]))
                {  
                    $keywords_node = $record_node->appendChild(
                        $this->xml->createElement('keywords'));

                    $attr =  $this->xml->createAttribute('language');
                    $attr->value = strtolower($language_aliases[$language]);
                    $keywords_node->appendChild( $attr );

                    foreach ($article->keywords[$language] as $keyword)
                    {
                        $keywords_node->appendChild(
                            $this->xml->createElement('keyword',$keyword));
                    }

                } elseif($article->artType == 'RAR'){ 
                    throw new Exception (JTEXT::_('ARTICULUS.ARTICLES.RAR.NOKEYWORDS'));}
            }
           
            $records_node->appendChild($record_node);
        }
  
        return $this->xml->saveXML();
    }

    public function validate()
    {
        libxml_clear_errors();
        if (!$this->xml->schemaValidate(self::SCHEMA)) {
            throw new Exception(libxml_get_last_error()->message);
        }
    }
}
