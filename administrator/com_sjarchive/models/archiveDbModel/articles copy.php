<?php defined('_JEXEC') or die('Restricted access');
class archiveDbModelArticles  extends JModelLegacy
{

	public function selectByIssue(archiveModelIssue &$issue)

	{

		$sql = "SELECT 	a.article_id,
										`issue_id`,
										`art_type`,
										`pdf`,
										`pages`,
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
						WHERE 	issue_id = {$this->_db->quote($issue->ID)}
						ORDER BY pages ASC";
		$this->_db->setQuery($sql);

		foreach ($this->_db->loadObjectList() as $item) {
			$article = new archiveModelArticle;

			$article->fillFromDb($item);
			$sql = "SELECT 	`section`,
											`title`,
											`abstract`,
											`language`
							FROM #__sjarchive_meta
							WHERE article_id	 = {$this->_db->quote($article->ID)}";

			$this->_db->setQuery($sql);
			$article->fillMetaFromDb(
				$this->_db->loadObjectList()
			);



			$sql = "SELECT `surname`,`lastname`,`org`,`email`,`address`,`other`, `ORCID`, `scopus_id`,`language`,`author_position`
                    FROM #__sjarchive_author a
                    JOIN #__sjarchive_article_author aa 
                    ON aa.author_id = a.author_id
                    WHERE aa.article_id = {$this->_db->quote($article->ID)}
                    ORDER BY  aa.author_position ASC;";

			$this->_db->setQuery($sql);

			$article->fillAuthorsFromDb(
				$this->_db->loadObjectList()
			);

			$sql = "SELECT `reference`, `language`
                    FROM #__sjarchive_reference r 
                    WHERE 	r.article_id = {$this->_db->quote($article->ID)}";

			$this->_db->setQuery($sql);

			$article->fillReferenceFromDb(
				$this->_db->loadObjectList()
			);

			$sql = "SELECT `keyword`,`language`
                    FROM #__sjarchive_keyword k 
                    JOIN #__sjarchive_article_keyword ak
                    ON k.keyword_id=ak.keyword_id
                    WHERE ak.article_id = {$this->_db->quote($article->ID)}";

			$this->_db->setQuery($sql);

			$article->fillKeywordsFromDb(
				$this->_db->loadObjectList()
			);

			$issue->articles[] = $article;
		}
	}

	public function insert(archiveModelIssue $issue)
	{
		$articles = &$issue->articles;

		$languages = array('ru-RU', 'en-GB');

		foreach ($articles as &$article) {

			$sql = "SET @foreign_key_checks = 0;";
			$sql .= "INSERT INTO #__sjarchive_article (
				`issue_id`,
				`art_type`,
				`position`,
				`udk`,
				`pages`,
				`pdf`,
				`hits`,
				`published`,
				`translation`,
				`submited_date`,
				`edited_date`,
				`published_date`,
				`doi`,
				`create_date`,
				`edit_date`)
				VALUES (
						{$this->_db->quote($issue->ID)},
						{$this->_db->quote($article->artType)},
						{$this->_db->quote($article->position)},
						{$this->_db->quote($article->udk)},
						{$this->_db->quote($article->pages)},
						{$this->_db->quote($article->pdf)},
						{$this->_db->quote($article->hits)},
						{$this->_db->quote($article->published)},
						{$this->_db->quote($article->translation)},
						{$this->_db->quote($article->submitedDate)},
						{$this->_db->quote($article->editedDate)},
						{$this->_db->quote($article->publishedDate)},
						{$this->_db->quote($article->doi)},
						{$this->_db->quote($article->createdDate)},
						{$this->_db->quote($article->editDate)})
				ON DUPLICATE KEY UPDATE `article_id` = LAST_INSERT_ID(article_id),
					`art_type` = {$this->_db->quote($article->artType)},
					`position` = {$this->_db->quote($article->position)},
					`udk`= {$this->_db->quote($article->udk)},
					`pages`= {$this->_db->quote($article->pages)},
					`pdf`= {$this->_db->quote($article->pdf)},
					`hits`= {$this->_db->quote($article->hits)},
					`published`= {$this->_db->quote($article->published)},
					`translation`= {$this->_db->quote($article->translation)},
					`submited_date`={$this->_db->quote($article->submitedDate)},
					`edited_date`= {$this->_db->quote($article->editedDate)},
					`published_date`= {$this->_db->quote($article->publishedDate)},
					`doi`= {$this->_db->quote($article->doi)},
					`create_date`= {$this->_db->quote($article->createdDate)},
					`edit_date`= {$this->_db->quote($article->editDate)};
				SET @article_id = LAST_INSERT_ID();";
			if ($languages) {
				foreach ($languages as $language) {
					if ($article->text[$language]) {
						$sql .= "INSERT IGNORE INTO #__sjarchive_fulltext (
										`article_id`, `fulltext`, `language`)
							VALUES (
									@article_id,
									{$this->_db->quote($article->text[$language])},
									{$this->_db->quote($language)});";
					}

					$sql .= "INSERT IGNORE INTO #__sjarchive_meta (
									`article_id`,
									`language`,
									`title`,
									`abstract`,
									`section`)
						VALUES (
								@article_id,
								{$this->_db->quote($language)},
								{$this->_db->quote($article->title[$language])},
								{$this->_db->quote($article->abstract[$language])},
								{$this->_db->quote($article->section[$language])});";


					if ($article->keywords[$language]) {
						foreach ($article->keywords[$language] as &$keyword) {
							$sql .= "INSERT INTO #__sjarchive_keyword (`keyword`)
											VALUES ({$this->_db->quote($keyword->keyword)})
											ON DUPLICATE KEY UPDATE keyword_id = LAST_INSERT_ID(keyword_id);
											INSERT IGNORE INTO 	#__sjarchive_article_keyword (
																					`article_id`,
																					`keyword_id`,
																					`language`)
											VALUES (@article_id,LAST_INSERT_ID(),{$this->_db->quote($keyword->language)});";
						}
					}
					if ($article->reference[$language]) {
						foreach ($article->reference[$language] as $position => &$reference) {
							$sql .= "INSERT IGNORE INTO #__sjarchive_reference (
                                              `article_id`,
																							`position`,
																							`reference`,
																							`language`)
                              VALUES (@article_id,
                                      {$this->_db->quote($position)},
                                      {$this->_db->quote($reference->reference)},
                                      {$this->_db->quote($language)});";
						}
					}
					foreach ($article->authors as  $author) {
						$sql .= "INSERT INTO #__sjarchive_author (
										`surname`,
										`lastname`,
										`email`,
										`scopus_id`,
										`ORCID`,
										`spin_code`,
										`wos_id`)
							VALUES ({$this->_db->quote($author[$language]->surname)},
									{$this->_db->quote($author[$language]->lastname)},
									{$this->_db->quote($author[$language]->email)},
									{$this->_db->quote($author[$language]->scopusId)},
									{$this->_db->quote($author[$language]->ORCID)},
									{$this->_db->quote($author[$language]->spinCode)},								
									{$this->_db->quote($author[$language]->wosId)}
									)
							ON DUPLICATE KEY UPDATE 
									`author_id` 	= LAST_INSERT_ID(author_id);
							INSERT IGNORE INTO #__sjarchive_article_author (
								`article_id`,
								`author_id`,
								`org`,
								`address`,
								`language`,
								`author_position`,
								`other`)
							VALUES (@article_id,
									LAST_INSERT_ID(),
									{$this->_db->quote($author[$language]->org)},
									{$this->_db->quote($author[$language]->address)},
									{$this->_db->quote($author[$language]->language)},
									{$this->_db->quote($author[$language]->position)},
									{$this->_db->quote($author[$language]->other)}); ";
					}
				}
			}
			$sql .= "SET foreign_key_checks = `1`;";

			echo '<pre>';
			var_dump($this->_db->setQuery($sql));
			echo '</pre>';
			die();

			$this->_db->setQuery($sql);

			try {
				$this->_db->execute();
			} catch (Exception $e) {
				JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
				echo ($e->getMessage());
				die();
				return false;
			}
		}
	}

	public function deleteByIssue(archiveModelIssue $issue)
	{

		foreach ($issue->articles as $article) {
			$ids[] = $article->ID;
		}

		$ids = implode(',', $ids);

		$sql = "
						DELETE FROM #__sjarchive_fulltext WHERE article_id IN ({$ids});
						DELETE FROM #__sjarchive_meta WHERE article_id IN ({$ids});
						DELETE FROM #__sjarchive_article_statistic WHERE article_id IN ({$ids});
						DELETE FROM #__sjarchive_reference WHERE article_id IN ({$ids});
						DELETE FROM #__sjarchive_article_keyword WHERE article_id IN ({$ids});
						DELETE FROM #__sjarchive_article_author WHERE article_id IN ({$ids});
						DELETE FROM #__sjarchive_article WHERE article_id IN ({$ids}); 		
					";

		$this->_db->setQuery($sql);

		try {
			$this->_db->execute();
		} catch (Exception $e) {

			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			var_dump($e->getMessage());
			die();
			return false;
		}
	}
}
