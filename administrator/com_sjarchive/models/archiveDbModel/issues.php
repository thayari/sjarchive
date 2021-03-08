<?php defined('_JEXEC') or die('Restricted access');
class archiveDbModelIssues  extends JModelLegacy
{

    public function selectIssues()
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
						published

				FROM #__sjarchive_issue i
				ORDER BY year DESC, num DESC, part DESC, volume DESC";
		$this->_db->setQuery($sql);
		try
		{
            $issues = array();
            foreach ($this->_db->loadObjectList() as $raw)
            {
                $issue = new archiveModelIssue;

                $issue->fillFromDb(
                    $raw
                );

                $issues[]=$issue;
            }

            return $issues;
            
		}
		catch (Exception $e)
		{
			JLog::add($e->getMessage(), JLog::ERROR, 'com_sjarchive');
			return NULL;
        }
	}
}
