<?php defined('_JEXEC') or die('Restricted access');
class archiveModelIssueDb  extends JModelLegacy 
{	
	public function selectById($issue_id)
	{
		$sql = "SELECT issue_id as issueID,num, volume, part, year, special, special_comment as specialComment, pub_date as pubDate, use_content as useContent, use_pdf as usePdf, hits,published,pdf,content, doi
				FROM #__sjarchive_issue
				WHERE 	issue_id  = {$this->_db->quote($issue_id)}";
		$this->_db->setQuery($sql);
		try{
			$result = $this->_db->loadObject();
		}
		catch (Exception $e)
		{

			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			
			return NULL;
		}

		return $result;
	}
	
	public function selectByArticleId($article_id)
	{
		$sql = "SELECT i.issue_id as issueID,num, volume, part, year, special, special_comment as specialComment, pub_date as pubDate, use_content as useContent, use_pdf as usePdf, i.hits,i.published,i.pdf,content, doi
				FROM #__sjarchive_issue i
				JOIN #__sjarchive_article a ON i.issue_id=a.issue_id 
				WHERE 	a.article_id  = {$this->_db->quote($article_id)}";
		$this->_db->setQuery($sql);
		
		try
		{
			$result = $this->_db->loadObject();
		}
		catch (Exception $e)
		{

			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			
			return NULL;
		}

		return $result;
	}			 
	
	public function selectByParams($issue_year, $issue_num,$issue_part=0,$special=0)
	{
		

		$sql = "SELECT issue_id as issueID, num, volume, part, year, special, special_comment as specialComment, pub_date as pubDate, use_content as useContent, use_pdf as usePdf,pdf,content,doi
				FROM #__sjarchive_issue i
				WHERE 	i.year = {$this->_db->quote($issue_year)}
					AND	i.num  = {$this->_db->quote($issue_num)}
					AND i.part = {$this->_db->quote($issue_part)}
					AND i.special = {$this->_db->quote($special)}
					";

		$this->_db->setQuery($sql);
		try
		{
	
			$result = $this->_db->loadObject();
		}
		catch (Exception $e)
		{

			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			
			return NULL;
		}

		return $result;
	}
	
	public function selectLast()
	{
	
		$sql = "SELECT issue_id as issueID, year, volume, num, part, special, special_comment as specialComment, pub_date as pubDate, use_content as useContent, use_pdf as usePdf,pdf,content,doi
				FROM #__sjarchive_issue i 
				ORDER BY year DESC, num DESC, part DESC";
				

		$this->_db->setQuery($sql);
		try
		{
			$result = $this->_db->loadObject();
		}
		catch (Exception $e)
		{

			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			
			return NULL;
		}

		return $result->pdf;
	}
	
	public function selectFileByParams($issue_year, $issue_num,$ftype,$issue_part=0,$issue_special=0){
  
		switch ($ftype){
			case 'content':		
				$sql = "SELECT content 
						FROM #__sjarchive_issue i
						WHERE 	i.num  = {$this->_db->quote($issue_num)}
							AND i.year = {$this->_db->quote($issue_year)}
							AND i.part = {$this->_db->quote($issue_part)}
							AND i.special = {$this->_db->quote($issue_special)} ";
				break;
			case 'pdf':	
				$sql = "SELECT pdf 
						FROM #__sjarchive_issue i
						WHERE 	i.num  = {$this->_db->quote($issue_num)}
							AND i.year = {$this->_db->quote($issue_year)}
							AND i.part = {$this->_db->quote($issue_part)}
							AND i.special = {$this->_db->quote($issue_special)} ";
				break;
		}
		$this->_db->setQuery($sql);
		try
		{
			$result =  $this->_db->loadResult();	

		}
		catch (Exception $e)
		{

			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			
			return NULL;
		}
		return $result;
	}
	

	
	public function hit($issue_id)
	{
		$sql = "UPDATE #__sjarchive_issue i
				SET i.hits = i.hits+1
				WHERE i.issue_id = {$this->_db->quote($issue_id)}";

		$this->_db->setQuery($sql);
		try
		{
			$this->_db->query();
		}
		catch (Exception $e)
		{

			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			
			return NULL;
		}
		
	}
}
