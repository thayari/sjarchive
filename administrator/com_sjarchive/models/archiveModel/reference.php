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

class archiveModelReference
{
    public $reference;
    public $language;

    public function fillFromDb($db_result)
    {
        $this->reference    = $db_result->reference;
        $this->language     = $db_result->language;
    }
}