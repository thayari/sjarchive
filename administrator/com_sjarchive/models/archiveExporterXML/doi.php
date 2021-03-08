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

class archiveExporterXMLDoi
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
       
        $doi_batch_node = $this->xml->appendChild(
            $this->xml->createElement('doi_batch'));
        
        $attr =  $this->xml->createAttribute('version');
        $attr->value = '4.4.2';
        $doi_batch_node->appendChild( $attr );

        $attr =  $this->xml->createAttribute('xmlns');
        $attr->value = 'http://www.crossref.org/schema/4.4.2';
        $doi_batch_node->appendChild( $attr );

        $attr =  $this->xml->createAttribute('xmlns:xsi');
        $attr->value = 'http://www.w3.org/2001/XMLSchema-instance';
        $doi_batch_node->appendChild( $attr );

        $attr =  $this->xml->createAttribute('xsi:schemaLocation');
        $attr->value = 'http://www.crossref.org/schema/4.4.2 http://www.crossref.org/schema/deposit/crossref4.4.2.xsd';
        $doi_batch_node->appendChild( $attr );		

        $attr =  $this->xml->createAttribute('xmlns:jats');
        $attr->value = 'http://www.ncbi.nlm.nih.gov/JATS1';
        $doi_batch_node->appendChild( $attr );		

        $attr =  $this->xml->createAttribute('xmlns:mml');
        $attr->value = 'http://www.w3.org/1998/Math/MathML';
        $doi_batch_node->appendChild( $attr );		

		$head_node = $this->xml->createElement('head');
			$head_node->appendChild(
                $this->xml->createElement('doi_batch_id',time().'-'.uniqid()));
			$head_node->appendChild(
                $this->xml->createElement('timestamp',date("YmdHis")));
            
            $depositor_node = $this->xml->createElement('depositor');
				$depositor_node->appendChild(
					$this->xml->createElement('depositor_name','mgsu'));
				$depositor_node->appendChild(
					$this->xml->createElement('email_address','vestnikmgsu@mgsu.ru'));
			$head_node->appendChild($depositor_node);
			$head_node->appendChild(
                $this->xml->createElement('registrant','CrossRef'));

		$doi_batch_node->appendChild($head_node);
			
		$body_node = $this->xml->createElement('body');
			
			$journal_node = $this->xml->createElement ('journal');
			
			$journal_metadata_node = $this->xml->createElement('journal_metadata');
			
				$journal_metadata_node ->appendChild(
					$this->xml->createElement('full_title','Vestnik MGSU'));
				$journal_metadata_node ->appendChild(
					$this->xml->createElement('abbrev_title','Vestnik MGSU'));
			
                    $issn_node = $this->xml->createElement('issn','19970935');
                    
                    $attr =  $this->xml->createAttribute('media_type');
                    $attr->value = 'print';
                    $issn_node->appendChild( $attr );

					$journal_metadata_node->appendChild($issn_node);
                        
                    $issn_node = $this->xml->createElement('issn','23046600');
                    
                    $attr =  $this->xml->createAttribute('media_type');
                    $attr->value = 'electronic';
                    $issn_node->appendChild( $attr );

					$journal_metadata_node->appendChild($issn_node);
			
					$doi_data_node = $this->xml->createElement('doi_data');
						$doi_data_node->appendChild(
                            $this->xml->createElement('doi','10.22227/2073-8412'));
						$doi_data_node->appendChild(
                            $this->xml->createElement('resource','http://www.vestnikmgsu.ru/'));
					$journal_metadata_node->appendChild($doi_data_node);
				
			$journal_node->appendChild($journal_metadata_node);

			$journal_issue_node = $this->xml->createElement('journal_issue');
			
				$publication_date_node = $this->xml->createElement('publication_date');
                        
                    $attr =  $this->xml->createAttribute('media_type');
                    $attr->value = 'print';
                    $publication_date_node->appendChild( $attr );
						
						$publication_date_node->appendChild(
							$this->xml->createElement('month',$issue->num));
						$publication_date_node->appendChild(
							$this->xml->createElement('year',$issue->year));
                $journal_issue_node->appendChild($publication_date_node);

                $publication_date_node = $this->xml->createElement('publication_date');
                        
                $attr =  $this->xml->createAttribute('media_type');
                $attr->value = 'online';
                $publication_date_node->appendChild( $attr );
                    
                    $publication_date_node->appendChild(
                        $this->xml->createElement('month',$issue->num));
                    $publication_date_node->appendChild(
                        $this->xml->createElement('year',$issue->year));
            $journal_issue_node->appendChild($publication_date_node);
				
				$journal_issue_node->appendChild(
					$this->xml->createElement('issue',$issue->num));
					
					$doi_data_node = $this->xml->createElement('doi_data');
						$doi_data_node->appendChild(
                            $this->xml->createElement('doi',$issue->doi));
						$doi_data_node->appendChild(
                            $this->xml->createElement('resource','http://vestnikmgsu.ru/component/sjarchive/issue/issue.display/'.$issue->year.'/'.$issue->num));
			
				$journal_issue_node->appendChild($doi_data_node);
			
			$journal_node->appendChild($journal_issue_node);

            foreach ($articles as $article)
            {
                if(!empty($article->doi))
                {
                    $journal_article_node  = $this->xml->createElement('journal_article');
                    
                    $attr =  $this->xml->createAttribute('publication_type');
                    $attr->value = 'full_text';
                    $journal_article_node->appendChild( $attr );
					
					/*Заголовки статьи*/
					$titles_node = $this->xml->createElement('titles');
					
						$titles_node->appendChild(
							$this->xml->createElement('title',$article->title['en-GB']));
						
						$titles_node->appendChild(
							$this->xml->createElement('original_language_title',$article->title[$article->language]));
							
					$journal_article_node->appendChild($titles_node);
                    

                    /*Авторы*/
                    if(!empty($article->authors))
                    {
                        $contributors_node= $this->xml->createElement('contributors');
                        
                            foreach ($article->authors as $author)
                            {
							/*	$organization_node =  $this->xml->createElement('organization',$author['en-GB']->org);
                                
                                $attr =  $this->xml->createAttribute('contributor_role');
                                $attr->value = 'author';
                                $organization_node->appendChild( $attr );
                               
                                $attr =  $this->xml->createAttribute('sequence');
                                $attr->value = $author['en-GB']->position;
                                $organization_node->appendChild( $attr );

                                $organization_node->appendChild( $attr );
                                $contributors_node ->appendChild($organization_node); 
                                
                                $controrganization_nodeinutor_org = NULL;*/
								
								$person_name_node =  $this->xml->createElement('person_name');
                               
                                $attr =  $this->xml->createAttribute('contributor_role');
                                $attr->value = 'author';
                                $person_name_node->appendChild( $attr );

                                $attr =  $this->xml->createAttribute('sequence');
                                $attr->value = $author['en-GB']->position == 1 ? 'first': 'additional';
                                $person_name_node->appendChild( $attr );

                                $attr =  $this->xml->createAttribute('language');
                                $attr->value = 'en';
                                $person_name_node->appendChild( $attr );


                                if(!empty($author['en-GB']->lastname))
                                    $person_name_node->appendChild(
                                        $this->xml->createElement('given_name',$author['en-GB']->lastname));

                                $person_name_node->appendChild( $this->xml->createElement('surname',$author['en-GB']->surname));
                            
                                foreach (explode(';', $author['en-GB']->org) as $affiliation)
                                {
                                    $person_name_node->appendChild( $this->xml->createElement('affiliation',$affiliation));
                                
                                }

                                if(!empty($author['en-GB']->ORCID))
                                $person_name_node->appendChild(
                                    $this->xml->createElement('ORCID','https://orcid.org/'.$author['en-GB']->ORCID));

                                $contributors_node ->appendChild($person_name_node); 



						$journal_article_node->appendChild($contributors_node);
                    
                    }}

          
                    $abstract_node = $this->xml->createElement('jats:abstract');
                 
                    $abstract_node->appendChild(
                        $this->xml->createElement('jats:p',html_entity_decode($article->abstract['en-GB'])));
                    
                        $journal_article_node->appendChild($abstract_node);

                    /*Дата публикации - печатная версия*/
                    
                $publication_date_node = $this->xml->createElement('publication_date');
                        
                $attr =  $this->xml->createAttribute('media_type');
                $attr->value = 'print';
                $publication_date_node->appendChild( $attr );
                    
                    $publication_date_node->appendChild(
                        $this->xml->createElement('month',$issue->num));
                    $publication_date_node->appendChild(
                        $this->xml->createElement('year',$issue->year));

					$journal_article_node->appendChild($publication_date_node);
					
					/*Дата публикации - онлайн версия*/
                    $publication_date_node = $this->xml->createElement('publication_date');
                        
                    $attr =  $this->xml->createAttribute('media_type');
                    $attr->value = 'online';
                    $publication_date_node->appendChild( $attr );
                        
                        $publication_date_node->appendChild(
                            $this->xml->createElement('month',$issue->num));
                        $publication_date_node->appendChild(
                            $this->xml->createElement('year',$issue->year));
    
                        $journal_article_node->appendChild($publication_date_node);
					
					/*Страницы*/
					$pages_node = $this->xml->createElement('pages');
					
						$pages_node->appendChild(
							$this->xml->createElement('first_page',explode('-', $article->pages)[0]));
						$pages_node->appendChild(
							$this->xml->createElement('last_page',explode('-', $article->pages)[1]));
							
					$journal_article_node->appendChild($pages_node);
					
					/*DOI*/
					$doi_data_node = $this->xml->createElement('doi_data');

						$doi_data_node->appendChild(
							$this->xml->createElement('doi',$article->doi));
						$doi_data_node->appendChild(
							$this->xml->createElement('resource','http://vestnikmgsu.ru/component/sjarchive/issue/article.display/'.$issue->year.'/'.$issue->num.'/'.$article->pages));
							
                    $journal_article_node->appendChild($doi_data_node);
                    
                  $citation_list_node = $this->xml->createElement('citation_list');
                    $i=1;
                    foreach ($article->reference['en-GB'] as $key=>$reference)
                    {
                        $citation_node  = $this->xml->createElement('citation');
                    
                        $attr =  $this->xml->createAttribute('key');
                        $attr->value = 'ref'.$i;
                        $citation_node->appendChild( $attr );
                        
                        $citation_node->appendChild(
                            $this->xml->createElement('unstructured_citation', htmlspecialchars($reference->reference)));
                            
                        $citation_list_node->appendChild($citation_node);     
                        $i++;
                    }


                    $journal_article_node->appendChild($citation_list_node);

                    $journal_node->appendChild($journal_article_node);	
               
                }
            
            }
           
            $body_node->appendChild($journal_node);

            $doi_batch_node->appendChild($body_node);

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
