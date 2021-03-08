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

class archiveImporterXMLArticulus
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

		if (JFile::exists($file)) {
			$this->xml = simplexml_load_file($file);
		} else {
			throw new Exception('file not exists');
		}
		if ($error = libxml_get_last_error())

			throw new Exception($error->message);
	}

	public function importIssue($doi_settings = array('core' => '10.22227', 'issn' => '2073-8412', 'useNum' => '1', 'x' => 0, 'useVolume' => 0, 'useYear' => 1, 'useDoi' => 1))
	{
		$this->_item['issue'] = new archiveModelIssue();
		$this->_item['issue']->num				= (int) $this->xml->issue->number;
		$this->_item['issue']->part	 			= (int) $this->xml->issue->part;
		$this->_item['issue']->volume 			= (int) $this->xml->issue->volume;
		$this->_item['issue']->year 			= (int) $this->xml->issue->dateUni;
		$this->_item['issue']->pubDate  		= array_shift(explode(' ', (string) $this->xml->operCard->date));
		$this->_item['issue']->special			= 0;
		$this->_item['issue']->specialComment 	= NULL;
		$this->_item['issue']->usePdf 			= 0;
		$this->_item['issue']->useContent 		= 0;
		$this->_item['issue']->content			= NULL;
		$this->_item['issue']->pdf				= NULL;
		$this->_item['issue']->createdDate		= date('Y-m-d H:i:s');
		$this->_item['issue']->doi 				= NULL;

		return $this->_item['issue'];
	}

	public function importArticles($languages, &$issue, $articles_settings = array('auto_publisher' => 1))
	{
		$language_aliases = array('ru-RU' => 'RUS', 'en-GB' => 'ENG');

		foreach ($this->xml->xpath('issue/articles/article') as $no => $node) {
			$article = new archiveModelArticle();


			foreach ($languages as $language_tag) 
			{
				$language = $language_aliases[$language_tag];


				$article->artType	= (string) array_shift($node->xpath("artType"));
				$article->position  = (int) $no + 1;
				$article->udk		= (string) $node->codes->udk;
				$article->pages		= (string) $node->pages;
				$article->pdf		= NULL;
				$article->hits		= 0;
				$article->published = $articles_settings['auto_publisher'] ? 1 : 0;


				if (!empty(array_shift($node->xpath("text[@lang='{$language}']")))) 
				{
					$article->translation .= $language_tag . ';';
				} else {
					$article->translation = false;
				}
				$article->submitedDate = (string) $node->dates->dateReceived;
				$article->editedDate = NULL;
				$article->publishedDate = NULL;
				$article->doi = (string) $node->codes->doi;
				$article->createdDate = date('Y-m-d H:i:s');
				$article->editDate = date('Y-m-d H:i:s');
				
				$article->title[$language_tag]		= (string) array_shift($node->xpath("artTitles/artTitle[@lang='{$language}']"));
				$article->abstract[$language_tag]	= array_shift($node->xpath("abstracts/abstract[@lang='{$language}']/node()"));
				$article->abstract[$language_tag]	= ($article->abstract[$language_tag] instanceof SimpleXMLElement) ? strip_tags($article->abstract[$language_tag]->asXML(), '<i>,<sub>,<sup>,<b>,<u>,<strong>') : null;
				$article->section[$language_tag]	= (string) array_shift($node->xpath("self::*/preceding-sibling::*[1]/secTitle[@lang='{$language}']"));

				$article->text[$language_tag] = empty(array_shift($node->xpath("text[@lang='{$language}']"))) ? null : (string) array_shift($node->xpath("text[@lang='{$language}']"));
			
				if ($language == 'RUS') {

					foreach ($node->xpath('references/reference') as $item) 
					{

						$reference = new archiveModelReference;
						$reference->reference = trim(preg_replace('/^(\d)+.?/', '', $item));
						$reference->language = $language_tag;
						$article->reference[$language_tag][] = $reference;

	
					}
				}
				
				foreach ($node->xpath('keywords/kwdGroup/keyword') as $item) 
				{
					if (strtoupper($this->keywordLangDetect((string) $item)) == strtoupper($language)) 
					{
						$article->keywords[$language_tag][] = (string) $item;
					}
				}

				
				foreach ($node->xpath('authors/author') as $node2) {
					$author = new archiveModelAuthor();
					
					$author->position	= (int)	   array_shift($node2->xpath("@num"));
					$author->surname	= (string) array_shift($node2->xpath("individInfo[@lang='{$language}']/surname"));
					$author->lastname	= (string) array_shift($node2->xpath("individInfo[@lang='{$language}']/initials"));
					$author->email	= (string) array_shift($node2->xpath("individInfo[@lang='{$language}']/email"));
					$author->org	= (string) array_shift($node2->xpath("individInfo[@lang='{$language}']/orgName"));
					$author->address	= (string) array_shift($node2->xpath("individInfo[@lang='{$language}']/address"));
					$author->other	= (string) array_shift($node2->xpath("individInfo[@lang='{$language}']/otherInfo"));
					$author->scopusId = NULL;
					$author->ORCID = NULL;
					$author->wosID = NULL;
					$author->spinCode = NULL;
					$article->authors[$author->position][$language_tag] = $author;
				}
				
				
		
			}
			$issue->articles[] 	= $article;

		}
	}

	protected function keywordLangDetect($string)
	{


		//$string = trim(mb_strtolower($string, 'UTF8'));
		$string = trim($string, 'UTF8');
		$langs = array(
			'RUS' => array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'э', 'ю', 'ъ', 'ы', 'ь', 'я', 'й'),
			'ENG' => array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'n', 'm', 'o', 'p', 'u', 'q', 'z', 'x', 'w', 's', 'r', 'v', 't', 'y', 'h')
		);

		$num = array();

		foreach ($langs as $value => $lang) {
			$num[$value] = 0;

			foreach ($lang as $char) {
				if ($count = substr_count($string, $char)) {

					$num[$value] += $count;
				}
			}
			//if (mb_strlen($string, 'UTF8') / 1.5 < $num[$value])
			if (strlen($string, 'UTF8') / 1.5 < $num[$value])
				return $value;
		}
		return 'ANY';
	}
}
