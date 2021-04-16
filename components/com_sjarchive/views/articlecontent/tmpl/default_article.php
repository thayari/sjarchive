<?php
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$article = &$this->article;
$issue = &$this->issue;
?>
<?php $document->setMetaData('citation_journal_title', JTEXT::_('ARTICULUS.ISSUE.TITLE')); ?>
<?php $document->setMetaData('prism.publicationName', JTEXT::_('ARTICULUS.ISSUE.TITLE')); ?>
<?php $document->setMetaData('citation_publisher', JTEXT::_('ARTICULUS.ISSUE.PUBLISHER')); ?>
<?php $document->setMetaData('eprints.publication', JTEXT::_('ARTICULUS.ISSUE.TITLE')); ?>
<?php $document->setMetaData('citation_issn', JTEXT::_('ARTICULUS.ISSN')); ?>
<?php $document->setMetaData('prism.issn', JTEXT::_('ARTICULUS.ISSN')); ?>
<?php $document->setMetaData('citation_volume', $issue->volume); ?>
<?php $document->setMetaData('prism.volume', $issue->volume); ?>
<?php $document->setMetaData('citation_issue', $issue->num); ?>
<?php $document->setMetaData('eprints.volume', $issue->num); ?>
<?php $document->setMetaData('prism.number', $issue->num); ?>
<?php $document->setMetaData('DC.issued', $issue->year); ?>
<?php $document->setMetaData('prism.publicationDate', $issue->year); ?>
<?php // $document->setMetaData('citation_date', $issue->publishedDate); // undefined property ?>
<?php $document->setMetaData('eprints.date', $issue->year); ?>
<?php $document->setMetaData('citation_publication_date', $issue->year); ?>
<?php // $document->setMetaData('citation_firstpage', $pages[0]); ?>
<?php // $document->setMetaData('citation_lastpage', $pages[1]); // undefined variables ?>

<section role="list" lang="<?php echo $article->language ?> >

<?php if ($last_section <> $article->section) : ?>
	<section role="list">
	<h3>
		<div class="text-center"><?php echo $article->section; ?></div>
	</h3>
</section>
<?php endif; ?>

<article role="item">
	<section class="title" style="margin:10px 0px">
		<?php $document->setMetaData('citation_title', $article->title); ?>
		<?php $document->setMetaData('DC.title', $article->title); ?>
		<?php $document->setMetaData('eprints.title', $article->title); ?>

		<h4>
			<?php echo $article->title ?>
		</h4>
	</section>

	<?php if (isset($article->authors)) : ?>
		<?$authors_cite='';?>
		<section class="citation_author" style="margin:10px 0px">
			<ul>
				<?php foreach ($article->authors as $author) : ?>
					<li class="author" style="list-style-type: none; margin:5px">
						<div>
							<?php if (!empty($author->ORCID)) : ?>
								<span><a style="background: url('/media/com_sjarchive/static/ORCID.svg')   5px no-repeat; padding:0px 20px; background-size:25px;" href="https://orcid.org/<?php echo $author->ORCID; ?>"> </a></span>
							<?php endif; ?>
							<?php if (!empty($author->wos_id)) : ?>
								<span><a style="background: url('/media/com_sjarchive/static/publons.svg')  5px  no-repeat; padding:0px 20px;  background-size:25px;" href="https://publons.com/researcher/<?php echo $author->wos_id; ?>"></a> </span>
							<?php endif; ?>
							<?php if (!empty($author->scopus_id)) : ?>
								<span><a style="background: url('/media/com_sjarchive/static/scopus.svg')  5px no-repeat; padding: 0px 20px; background-size:25px;" href="https://www2.scopus.com/authid/detail.uri?authorId=<?php echo $author->scopus_id; ?>"></a> </span>
							<?php endif; ?>

							<?php if (!empty($author->spin_code)) : ?>
								<span><a style="background: url('/media/com_sjarchive/static/elibrary.png')  5px no-repeat; padding: 0px 20px; background-size:20px;" href="https://elibrary.ru/author_items.asp?authorid=<?php echo $author->spin_code; ?>"></a> </span>
							<?php endif; ?>

							<span style="font-weight:bold;"><?php echo ($author->surname . ' ' . $author->firstname); ?></span>



							<?php if (!empty($author->org)) : ?>
								- <?php echo ($author->org) ?>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
				<?php $document->setMetaData('citation_authors', $author->surname . ' ' . $author->firstname); ?>
				<?php $document->setMetaData('citation_author_institution', $author->org); ?>
				<?php $document->setMetaData('eprints.creators_name', $author->surname . ' ' . $author->firstname); ?>
				<?php $document->setMetaData('dc.creator', $author->surname . ' ' . $author->firstname); ?>

			</ul>
		</section>
	<?php endif; ?>
	<?php if (!empty($article->doi)) : ?>
		<section style="margin:10px 0px">
			<?php $document->setMetaData('citation_doi', $article->doi); ?>
			<?php $document->setMetaData('prism.doi', $article->doi); ?>
			<?php $document->setMetaData('dc.identifier.doi', 'doi:' . $article->doi); ?>
			<span>DOI:<a href="http://doi.org/<?php echo $article->doi ?>"> <?php echo $article->doi ?></a></span>
		</section>
	<?php endif; ?>
	<section role="note" style="margin:10px 0px">
		<?php $pages = explode('-', $article->pages); ?>
		<?php $document->setMetaData('citation_firstpage', $pages[0]); ?>
		<?php $document->setMetaData('citation_firstpage', $pages[0]); ?>
		<?php $document->setMetaData('eprints.pagerange', $pages[0] . '-' . $pages[1]); ?>
		<?php $document->setMetaData('prism.startingPage', $pages[1]); ?>
		<?php $document->setMetaData('prism.endingPage', $pages[1]); ?>
		<?php echo JTEXT::_('ARTICULUS.ARTICLE.PAGES'); ?>:
		<?php echo $article->pages ?>
	</section>
	<section class="abstract" style="margin:10px 0px">
		<?php $document->setMetaData('DC.Description', $article->abstract); ?>
		<?php echo $article->abstract; ?>
	</section>
	<?php if (isset($article->keywords) && !empty($article->keywords)) : ?>
		<section role="note" style="margin:10px 0px">
			<ul>
				<?php foreach ($article->keywords as $keyword) : ?>
					<?php if (!empty($keyword)) : ?>
						<li class="keyword" style="list-style-type:none;display: inline"><small><?php echo $keyword ?>;</small></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif ?>
	<?php if (!empty($article->reference)) : ?>
		<section class="reference mt-5">
			<a style="background: url('https://image.flaticon.com/icons/svg/1617/1617438.svg')no-repeat; background-size:14px; padding-left: 20px;" data-toggle="collapse" href="#collapseOne">
				<?php echo JTEXT::_('ARTICULUS.ARTICLE.REFERENCE'); ?>
			</a>

			<div id="collapseOne" class="panel-collapse collapse">
				<div class="panel-body">
					<ol>
						<?php foreach ($article->reference as $reference) : ?>
							<?php if (preg_match('/\d+[.]\d+[\/]\S+/', $reference->reference, $doi_match)) : ?>
								<li class="reference"><small><?php echo  str_replace($doi_match[0], '<a href="http://doi.org/' . $doi_match[0] . '">' . $doi_match[0] . '</a>', $reference->reference); ?></small></li>
							<?php else : ?>
								<li class="reference"><small><?php echo $reference->reference ?></small></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ol>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<section class="file mt-2">
		<?php $document->setMetaData('citation_abstract_html_url', JRoute::_(array('controller' => 'article', 'task' => 'article.download', 'year' => $issue->year, 'num' => $issue->num, 'pages' => $article->pages))); ?>
		<?php $document->setMetaData('citation_pdf_url', JRoute::_(array('controller' => 'article', 'task' => 'article.download', 'year' => $issue->year, 'num' => $issue->num, 'pages' => $article->pages))); ?>
		<a style="   background: url('/media/static/icons/pdf.png') no-repeat; padding-left: 25px; background-size:contain;" href="<?php echo (JRoute::_(array('task' => 'article.download', 'year' => $issue->year, 'num' => $issue->num, 'pages' => $article->pages))); ?>">
			<?php echo  $article->translation == 'en-GB' ? JTEXT::_('ARTICULUS.ARTICLE.DOWNLOAD') . ' (ENG)' : JTEXT::_('ARTICULUS.ARTICLE.DOWNLOAD') . ' (RUS)'; ?>
		</a>
	</section>
</article>
</section>