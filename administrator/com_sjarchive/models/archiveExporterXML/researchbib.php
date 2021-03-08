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

class archiveExporterXMLResearchbib
{
    const SCHEMA = '';
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
        $languages = array('ru-RU', 'en-GB');

        $articles = &$issue->articles;


		$records_node = $this->xml->appendChild($this->xml->createElement('records'));
		if(!$articles)
			throw new Exception (JTEXT::_('ARTICULUS.ARTICLES.EMPTY'));
		
        foreach ($articles as $article)
        {
          if($article->artType=="RAR"){
            $record_node = $this->xml->createElement('record');

            $record_node->appendChild(
                $this->xml->createElement('title',$article->title['en-GB']));
                            
            $authors_node = $record_node->appendChild(
                $this->xml->createElement('authors'));		
            foreach ($article->authors as $author)
            {
                $author_tmp = $author['en-GB']->surname;
                $author_tmp .= !empty($author['en-GB']->lastname) ? ' '.$author['en-GB']->lastname : NULL;
                
                $authors_node->appendChild(
                    $this->xml->createElement(  'author',$author_tmp
                                                
                                                ));
            }			
                        
            $record_node->appendChild(
                $this->xml->createElement('startPage',explode('-', $article->pages)[0]));
            $record_node->appendChild(
                $this->xml->createElement('endPage',explode('-', $article->pages)[1]));
                
            $keywords_node = $record_node->appendChild(
                                $this->xml->createElement('keywords'));	

            if (!empty($article->keywords['en-GB']))
            {
                foreach ($article->keywords['en-GB'] as &$keyword)
                {
                    $keywords_node->appendChild(
                        $this->xml->createElement('keyword',$keyword));	
                }
            } else if ($article->artType =='RAR')
                throw new Exception();   

            if (!empty($article->abstract['en-GB']))
            {
                $record_node->appendChild(
                    $this->xml->createElement('abstract',$article->abstract['en-GB']));
            } else if ($article->artType =='RAR')
            throw new Exception();   

            $record_node->appendChild($this->xml->createElement('fullTextUrl',
                                                $article->doi ? 'https://doi.org/'.$article->doi :
                                                'http://vestnikmgsu.ru/ru/component/sjarchive/issue/article.download/'.$issue->year.'/'.$issue->num.'/'.$article->pages
                ));

                $records_node->appendChild($record_node);	
          }
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
