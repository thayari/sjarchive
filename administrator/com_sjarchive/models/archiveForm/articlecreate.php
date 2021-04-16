<?php
// Защита от прямого доступа к файлу
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

// Создаем класс модели
class archiveFormArticleCreate 
{
	

	public function getDataFromUserInput($raw)
	{

		$issue = new archiveModelIssue;
		$languages = array('ru-RU','en-GB');

		$issue->num 	= $raw['num'];
		$issue->part 	= $raw['part'];
		$issue->year 	= $raw['year'];
		$issue->special = $raw['special'];

		foreach ($raw["info"] as $raw_article)
		{
			$article = new archiveModelArticle();

			foreach ($languages as $language)
			{
				$article->section[$language] = $raw_article['section'][$language];
				$article->title[$language] = $raw_article['title'][$language];

				foreach ($raw_article['authors'] as $position=>&$raw_author)
				{
					
					$author = new archiveModelAuthor;

          $author->authorId = $raw_author['authorId'][$language];
					$author->surname = $raw_author['surname'][$language];
					$author->firstname = $raw_author['firstname'][$language];
					$author->org = $raw_author['org'][$language];
					$author->wosId = $raw_author['wosId'][$language];
					$author->spinCode = $raw_author['spinCode'][$language];
					$author->scopusId = $raw_author['scopusId'][$language];
					$author->ORCID = $raw_author['ORCID'][$language];
					$author->elibraryID = $raw_author['elibraryID'][$language];
					$author->scholarID = $raw_author['scholarID'][$language];
					$author->position = $position;
					$article->authors[$position][$language] = $author;
				}

				$article->abstract[$language] = str_replace("\n","<br>",$raw_article['abstract'][$language]);
				foreach (explode(";",$raw_article['keywords'][$language]) as $raw_keyword)
				{
					$keyword = new archiveModelKeyword;
					$keyword->keyword = $raw_keyword;
					$keyword->language = $language;

					$article->keywords[$language][] = $keyword;
				}

				foreach (explode("\r\n",$raw_article['reference'][$language]) as $raw_reference)
				{
					$raw_reference = trim($raw_reference);
					if(!empty($raw_reference))
					{
						$reference = new archiveModelReference;
						$reference->reference = trim($raw_reference);
						$reference->language = $language;

						$article->reference[$language][] = $reference;
					}
				}
			}

			$article->pages = $raw_article['pages'];
			$article->doi = $raw_article['doi'];

			$issue->articles[] = $article;
			
		}// var_dump($issue);die();

		return $issue;
	}

	
	

}
