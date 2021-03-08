<?php defined('_JEXEC') or die('Restricted access');
class archiveModelArticleDb extends JModelLegacy 
{	
	protected function selectArticleKeywords($article_id,$language='ru-RU')
	{
		
		$sql = "	SELECT `keyword` 
					FROM #__sjarchive_keyword k 
					JOIN #__sjarchive_article_keyword ak
					ON k.keyword_id=ak.keyword_id
					WHERE ak.article_id = {$this->_db->quote($article_id)} AND 
                     ak.language IN ({$this->_db->quote($language)},'ANY');";

			$this->_db->setQuery($sql);
      
			try
			{

				return $this->_db->loadColumn();	
			}
			catch (Exception $e)
			{
				JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
				return NULL;
			}
      
    
	}

	protected function selectArticleAuthors ($article_id,$language='ru-RU')
	{
		$sql = "SELECT `surname`,`lastname`,`org`,`email`,`address`,`other`, `ORCID`, `scopus_id`,`wos_id`,`spin_code`
		FROM #__sjarchive_author a
		JOIN #__sjarchive_article_author aa 
		ON aa.author_id = a.author_id
		WHERE aa.article_id = {$this->_db->quote($article_id)} AND
			  aa.language = {$this->_db->quote($language)}
		ORDER BY  aa.author_position ASC";
		$this->_db->setQuery($sql);
		try
		{
			$result =  $this->_db->loadObjectList(); 

		}
		catch (Exception $e)
		{
			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			return NULL;
		}

		return $result;
	}
	protected function selectArticleReferences($article_id,$language='ru-RU')
	{
		$sql = "SELECT `reference`, `language`
			FROM #__sjarchive_reference r 
			WHERE 	r.article_id = {$this->_db->quote($article_id)} AND
					r.language = {$this->_db->quote($language)}";

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
	public function selectById($article_id,$language='ru-RU')
	{
		$sql = "SELECT 	a.article_id,
						`issue_id`,
						`art_type`,
						`pdf`,
						m.section,
						m.title,
						m.abstract,
						`position`,
						`pages`,
						m.language,
						`published`,
						`translation`,
						`udk`, 
						`doi`,
						`submited_date`,
						`edited_date`,
						`published_date`,
						`create_date`,
						`edit_date`
				FROM #__sjarchive_article a
				JOIN #__sjarchive_meta m 
				ON a.article_id = m.article_id
				WHERE a.article_id	 = {$this->_db->quote($article_id)} AND
						m.language = {$this->_db->quote($language)}
				ORDER BY position ASC";
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
	
	public function selectByParams ($issue_id,$pages,$language='ru-RU')
	{
		$sql = "SELECT 	a.article_id,
			`issue_id`,
			`art_type`,
			`pdf`,
			m.section,
			m.title,
			m.abstract,
			`position`,
			`pages`,
			m.language,
			`published`,
			`translation`,
			`udk`, 
			`doi`,
			`submited_date`,
			`edited_date`,
			`published_date`,
			`create_date`,
			`edit_date`
		FROM #__sjarchive_article a
		JOIN #__sjarchive_meta m 
		ON a.article_id = m.article_id
		WHERE a.issue_id	 	= {$this->_db->quote($issue_id)} AND
			  a.pages 			= {$this->_db->quote($pages)} AND
			  m.language 		= {$this->_db->quote($language)}
		ORDER BY position ASC";

		
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
		if(!empty($result))
		{
			$result->authors = $this->selectArticleAuthors($result->article_id,$language); 
			$result->keywords = $this->selectArticleKeywords($result->article_id,$language); 
			$result->reference = $this->selectArticleReferences($result->article_id,$language);
		}
		return $result;
	}

	public function selectFileByParams($issue_id,$pages)
	{
		$sql = "SELECT pdf 
		FROM #__sjarchive_article i
		WHERE 	issue_id  = {$this->_db->quote($issue_id)}
			AND pages = {$this->_db->quote($pages)} ";	

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
}
