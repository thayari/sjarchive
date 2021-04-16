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

class archiveExporterXMLRss
{
    const SCHEMA = 'http://purl.org/rss/1.0/modules/content/';
    protected $xml;
    protected $config = array();

    public function __construct ($title = '', $abstract = '')
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

      $this->config['title'] = $title;
    }

    public function generateXml($issues)
    {
        $channel = $this->xml->appendChild(
            $this->xml->createElement('channel'));
        $channel->appendChild(
            $this->xml->createElement('title',$this->config['title']));
        $channel->appendChild(
                $this->xml->createElement('link','http://vestnikmgsu.ru/'));
        $channel->appendChild(
                $this->xml->createElement('description',$this->config['description']));
        /* $channel->appendChild(
                $this->xml->createElement('copyright','УТОЧНИТЬ КОГО ПИСАТЬ АВТОРОВ?'));*/
        $channel->appendChild(
                $this->xml->createElement('managingEditor','vestnikmgsu@mgsu.ru'));
        $channel->appendChild(
                $this->xml->createElement('webMaster','vestnikmgsu@mgsu.ru'));

        foreach ($issues as &$issue)
        {
                $item = $this->xml->createElement('item');      
                $item->appendChild(
                    $this->xml->createElement('title',$this->config['title'] .' №' .$issue->num .'/'.$issue->year));
                $item->appendChild(
                    $this->xml->createElement('link', $_SERVER['HTTP_HOST'].JRoute::_('index.php?option=com_sjarchive&controller=article&task=download&year='.$issue->year.'&num='.$issue->num.(!empty($issue->part)?'&part='.$issue->part:'').(!empty($issue->volume)?'&volume='.$issue->volume:'').(!empty($issue->special)?'&special='.$issue->special:''))));
				$item->appendChild($this->xml->createElement('pubDate',$issue->pubDate)); /*Вставить из настроек*/
				
                $channel->appendChild($item);
        }

        return $this->xml->saveXML();
    }
    
	public function validate(){
		libxml_clear_errors ();
		if(!$this->xml->schemaValidate(self::SCHEMA)){
			throw new Exception(libxml_get_last_error()->message);
		}
	}
	
}