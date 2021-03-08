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

class archiveModelIssue
{
    public $ID;
    public $num              = NULL;
    public $part             = NULL;
    public $volume           = NULL;
    public $year             = 0;
    public $pubDate          = NULL;
    public $special          = 0;
    public $specialComment   = NULL;
    public $usePdf           = 0;
    public $useContent       = 0;
    public $content          = NULL;
    public $pdf              = NULL;
    public $createdDate      =   NULL;
    public $doi;
    public $hits;
    public $published;

    public $articles = array();


    public function validateModel ()
    {
        //@ToDo
    }

    public function fillFromDb(stdClass $db_result)
    {
        $this->ID               = $db_result->issueID;
        $this->num              = $db_result->num;
        $this->part             = $db_result->part;
        $this->volume           = $db_result->volume;
        $this->year             = $db_result->year;
        $this->pubDate          = $db_result->pubDate;
        $this->special          = $db_result->special;
        $this->specialComment   = $db_result->specialComment;
        $this->usePdf           = $db_result->usePdf;
        $this->useContent       = $db_result->useContent;
        $this->content          = $db_result->content;
        $this->pdf              = $db_result->pdf;
        $this->createdDate      = $db_result->issueID;
        $this->doi              = isset($db_result->doi) ?$db_result->doi : NULL;
        $this->hits             = $db_result->hits ;
        $this->published        = $db_result->published; 
      

    }

    public function makeContentPath($filename)
    {

        if(!empty($this->num)&&!empty($this->year))
              $this->content = $this->makegeneralPath().$filename;          
    }

    public function makePdfPath($filename)
    {

        if(!empty($this->num)&&!empty($this->year))
            $this->pdf = $this->makegeneralPath().$filename; 
    
    }

    public function makegeneralPath()
    {
        return $this->year.DIRECTORY_SEPARATOR.$this->num.DIRECTORY_SEPARATOR;
    }
  
    public function bindArticle($article)
    {
      $this->articles[] = $article;
    }
    
}
