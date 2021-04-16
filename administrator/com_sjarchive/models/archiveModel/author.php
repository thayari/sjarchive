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

class archiveModelAuthor
{
  public $authorId;
  public $surname;
  public $lastname;
  public $org = array();
  public $address;
  public $other = NULL;
  public $email = NULL;
  public $scopusId = NULL;
  public $ORCID = NULL;
  public $wosId = NULL;
  public $spinCode = NULL;
  public $elibraryID = NULL;
  public $scholarID = NULL;
  public $position;
  public $language;

  public function fillFromDb(stdClass $db_result)
  {
    $this->authorId     = $db_result->author_id;
    $this->surname      = $db_result->surname;
    $this->lastname     = $db_result->lastname;
    $this->org          = $db_result->org;
    $this->address      = $db_result->address;
    $this->other        = $db_result->other;
    $this->email        = $db_result->email;
    $this->scopusId     = $db_result->scopus_id;
    $this->ORCID        = $db_result->ORCID;
    $this->wosId        = $db_result->wos_id;
    $this->spinCode     = $db_result->spin_code;
    $this->elibraryID   = $db_result->elibrary_id;
    $this->scholarID    = $db_result->scholar_id;
    $this->position     = $db_result->author_position;
    $this->language     = $db_result->language;
  }
}
