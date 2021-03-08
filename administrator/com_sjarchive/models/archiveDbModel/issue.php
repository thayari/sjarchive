<?php defined('_JEXEC') or die('Restricted access');
class archiveDbModelIssue  extends JModelLegacy
{

    public function selectById($issue_id)
    {
        $sql = "SELECT  issue_id as issueID,
                        num, 
                        volume,
                        part, 
                        year,
                        special,
                        special_comment as specialComment,
                        pub_date as pubDate,
                        use_content as useContent,
                        use_pdf as usePdf,
                        hits,published,
                        pdf,
                        content, 
                        doi
        FROM #__sjarchive_issue
        WHERE 	issue_id  = {$this->_db->quote($issue_id)}";
        $this->_db->setQuery($sql);
        try {

            $issue = new archiveModelIssue;

            $issue->fillFromDb(
                $this->_db->loadObject()
            );

            return $issue;
        } catch (Exception $e) {
            JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
        }
    }
  
    public function selectByArticleId(ArchiveModelArticle $article)
    {

        $sql = "SELECT  i.issue_id as issueID,
                        num, 
                        volume,
                        part, 
                        year,
                        special,
                        special_comment as specialComment,
                        pub_date as pubDate,
                        use_content as useContent,
                        use_pdf as usePdf,
                        i.hits,i.published,
                        i.pdf,
                        content, 
                        i.doi
        FROM #__sjarchive_issue as i
        JOIN #__sjarchive_article as a ON i.issue_id = a.issue_id 
        WHERE 	a.article_id  = {$this->_db->quote($article->ID)}";
      

        $this->_db->setQuery($sql);
        try {

            $issue = new archiveModelIssue;

            $issue->fillFromDb(
                $this->_db->loadObject()
            );
            return $issue;
        } catch (Exception $e) {
            JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
          var_dump($e->getMessage());die();
        }
    }
  
  

    public function insert(archiveModelIssue &$issue)
    {

        $sql = "INSERT INTO #__sjarchive_issue (
            num,
            part,
            volume,
            year,
            pub_date,
            special,
            special_comment,
            use_pdf,
            use_content,
            content,
            pdf,
            created_date,
            doi)
            VALUES ({$this->_db->quote($issue->num)},
                    {$this->_db->quote($issue->part)},
                    {$this->_db->quote($issue->volume)},
                    {$this->_db->quote($issue->year)},
                    {$this->_db->quote($issue->pubDate)},
                    {$this->_db->quote($issue->special)},
                    {$this->_db->quote($issue->specialComment)},
                    {$this->_db->quote($issue->usePdf)},
                    {$this->_db->quote($issue->useContent)},
                    {$this->_db->quote($issue->content)},
                    {$this->_db->quote($issue->pdf)},
                    {$this->_db->quote($issue->createdDate)},
                    {$this->_db->quote($issue->doi)})
            ON DUPLICATE KEY UPDATE issue_id = LAST_INSERT_ID(issue_id)";
        $this->_db->setQuery($sql);
        $this->_db->query();
        try {
        $issue->ID =  $this->_db->insertid();
        } catch (Exception $e) {
        JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
        return NULL;
        }
    }

    public function delete(archiveModelIssue $issue)
    {
        $sql = "DELETE FROM #__sjarchive_issue
                WHERE issue_id IN ({$issue->ID})
                ";

		$this->_db->setQuery($sql);

		try {
			return $this->_db->execute();
		} catch (Exception $e) {
			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			return NULL;
		}
    }
}
