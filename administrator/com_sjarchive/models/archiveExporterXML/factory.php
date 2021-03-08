<?php defined('_JEXEC') or die('Restricted access');

 class archiveExporterXMLFactory 
{	
	public static function getExporter($publisher)
	{
        switch ($publisher)
        {
        case 'rss':
            return new archiveExporterXMLRss;
        case 'articulus':
            return new archiveExporterXMLArticulus;
        case 'doaj':
            return new archiveExporterXMLDoaj;
        case 'researchbib':
            return new archiveExporterXMLResearchbib;
        case 'doi':
            return new archiveExporterXMLDoi;
        default:
            throw new Exception ('SJArchive.UnknownXMLType');

         }
	}
	

}
