<?php defined('_JEXEC') or die('Restricted access');
class archiveModelIssuesDb extends JModelLegacy {	
	//@TASK подготовленный запрос
	public function selectAll()
	{
		$sql = "SELECT	issue_id as issueID,
						num, 
						volume, 
						part, 
						year,
						special, 
						special_comment as specialComment,
						pdf,
						content, 
						pub_date as pubDate, 
						use_content as useContent,
						use_pdf as usePdf, 
						hits, 
						doi,
						published,
						(SELECT COUNT(article_id) FROM #__sjarchive_article a WHERE i.issue_id = a.issue_id ) as articlesNum
				FROM #__sjarchive_issue i
				WHERE published ='1'
				ORDER BY year DESC, num DESC, part DESC, volume DESC";
		$this->_db->setQuery($sql);
		try
		{
			return $this->_db->loadObjectList();
		}
		catch (Exception $e)
		{
			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			return NULL;
		}
	
	}
}
