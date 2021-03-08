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

class archiveModelArticle
{
    public $ID;
    public $doi;
    public $artType;
    public $position;
    public $udk;
    public $pages; 
    public $pdf; 
    public $hits = 0;
    public $published;
    public $translation;
    public $title;
    public $abstract;
    public $section;
    public $text;
    public $reference = array();
    public $keywords = array();
    public $authors = array();
    public $submitedDate ;
    public $editedDate;
    public $publishedDate;
    public $createdDate ;
    public $editDate ;

    public function fillFromDb(stdClass $db_result)
    {
        $this->ID               = $db_result->article_id;
        $this->artType          = $db_result->art_type;
        $this->pdf              = $db_result->pdf;
        $this->pages            = $db_result->pages;
        $this->published        = $db_result->published;
        $this->translation      = $db_result->translation;
        $this->udk              = $db_result->udk;
        $this->doi              = $db_result->doi;
        $this->submitedDate     = $db_result->submited_date;
        $this->editedDate       = $db_result->edited_date;
        $this->publishedDate    = $db_result->published_date;
        $this->createdDate      = $db_result->create_date;
        $this->editDate         = $db_result->edit_date;
    }

    public function fillMetaFromDb(array $db_result)
    {
        foreach ($db_result as $item)
        {
            $this->title[$item->language] = $item->title;
            $this->abstract[$item->language] = $item->abstract;
            $this->section[$item->language] = $item->section;
            $this->title[$item->language] = $item->title;
        }
    }

    
    public function fillAuthorsFromDb(array $db_result)
    {
    
        foreach ($db_result as $key=>$item)
        {
         
           $author = new archiveModelAuthor;
           $author->fillFromDb($item);

           $this->authors[$author->position][$author->language] = $author;
        }

    }

    public function fillReferenceFromDb(array $db_result)
    {

        foreach ($db_result as $item)
        {
           $reference = new archiveModelReference;
           $reference->fillFromDb($item);

           $this->reference[$item->language][] = $reference;
        }
    }

   public function fillKeywordsFromDb(array $db_result)
   {
        foreach ($db_result as $item)
        {
            $this->keywords[$item->language][] = $item->keyword;
        }
   }

}