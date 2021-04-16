<?php
defined('_JEXEC') or die('Restricted access');
$last_section = NULL;
$document = JFactory::getDocument();
$issue = &$this->issue;
$languages = array('ru-RU','en-GB');
?>
<?php $document->setMetaData('citation_journal_title', JTEXT::_('ARTICULUS.PUBLISHER')); ?>
<?php $document->setMetaData('citation_volume', $issue->volume); ?>
<?php $document->setMetaData('citation_issue', $issue->issue); ?>
<?php $document->setMetaData('citation_date', $issue->publishedDate); ?>

<form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">

	<section role="list">
		<section>


					<label for="num"><?php echo  JTEXT::_('ARTICULUS.ISSUE.NUM') ?></label>
					<input  id="num" style="width:100%" name='article[num]' class="text-center" value="<?php echo $issue->num; ?>">

					<label for="part"><?php echo  JTEXT::_('ARTICULUS.ISSUE.PART') ?></label>
					<input  id="part" style="width:100%" name='article[part]' class="text-center" value="<?php echo $issue->part; ?>">



					<label for="year"><?php echo  JTEXT::_('ARTICULUS.ISSUE.YEAR') ?></label>
					<input  id="year" style="width:100%" name='article[year]' class="text-center" value="<?php echo $issue->year; ?>">



					<label for="special"><?php echo  JTEXT::_('ARTICULUS.ISSUE.SPECIAL') ?></label>
					<input  id="special" style="width:100%" name='article[special]' class="text-center" value="<?php echo $issue->special; ?>">

		
		</section>

		<?php foreach ($this->issue->articles as $art_key=>&$article) : ?>


		<section role="list">
				<label for="submitedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.SUBMITEDDATE') ?></label>
				<input id="submitedDate" style="width:100%" name='article[info][<?php echo $art_key?>][submitedDate]' class="text-center" value="<?php echo $article->submitedDate; ?>">
			
				<label for="editedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.EDITEDDATE') ?></label>
				<input id="editedDate" style="width:100%" name='article[info][<?php echo $art_key?>][editedDate]' class="text-center" value="<?php echo $article->editedDate; ?>">
		
				<label for="publishedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLISHDEDATE') ?></label>
				<input id="publishedDate" style="width:100%" name='article[info][<?php echo $art_key?>][publishedDate]' class="text-center" value="<?php echo $article->publishedDate; ?>">
			
				<label for="doi"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.DOI') ?></label>
				<input  id="doi" style="width:100%" name='article[info][<?php echo $art_key?>][doi]' class="text-center" value="<?php echo $article->doi; ?>">
			
				<label for="udk"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.UDK') ?></label>
				<input id="udk" style="width:100%" name='article[info][<?php echo $art_key?>][udk]' class="text-center" value="<?php echo $article->udk; ?>">
				
				<label for="artType"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.ARTTYPE') ?></label>
				<select name='article[info][<?php echo $art_key?>][artType]'>
					<option value="EDI"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.EDITOR') ?></option>
					<option selected value="RAR"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.SCIENCE') ?></option>
				</select>
				<label for="translation"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.LANGUAGE') ?></label>
				<select multiple name='article[info][<?php echo $art_key?>][translation]'>
					<option selected value="ru-RU"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TRANSLATION.RU-RU') ?></option>
					<option value="en-GB"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TRANSLATION.EN-GB') ?></option>
				</select>
				<label for="published"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLUSHED') ?></label>
				<select name='article[info][<?php echo $art_key?>][published]'>
					<option selected value="1"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLUSHED') ?></option>
					<option value="0"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.UNPUBLISHED') ?></option>
				</select>

				<label for="pdf"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PDF') ?><?php echo $article->pdf?></label>
				<input type="file"id="pdf" style="width:100%" name='article[info][pdf]' class="text-center" value="<?php echo $article->editedDate; ?>">
			</section>

		<?php foreach ($languages as $language):?>
			<section role="list">
			
				<label for="section"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.SECTION') ?>(	<?php echo $language ?>)</label>
				<input id="section" style="width:100%" name='article[info][<?php echo $art_key?>][section][<?php echo $language?>]' class="text-center" value="<?php echo $article->section[$language]; ?>">
			</section>
		<?php endforeach;?>
		<article role="item">
		<?php foreach ($languages as $language):?>
			<section class="title">
				<?php echo $language ?>
				<label for="title"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TITLE') ?></label>
				<input id="title" style="width:100%" name='article[info][<?php echo $art_key?>][title][<?php echo $language?>]' class="text-center" value="<?php echo $article->title[$language]; ?>">
			</section>
		<?php endforeach;?>	
		<?php if (!empty($article->authors)):?>
			<? $authors_cite = NULL; ?>
			<section class="authors">
			<?php foreach ($article->authors as $key=>$author) : ?>
			<div class="form-group">
				<label><?php echo  JTEXT::_('ARTICULUS.ARTICLE.AUTHOR') ?></label>
				<?php foreach ($languages as $language):?>
					<div class="form-group">
					<?php echo $language ?>
				
					<?php $authors_cite .= $author->surname . ',' . $author->firstname . ';' ?>
					<label for="surname"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.SURNAME') ?></label>
						<input id="surname" name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][surname][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->surname; ?>">
					<label for="firstname"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.FIRSTNAME') ?></label>
						<input id="firstname" name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][firstname][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->firstname; ?>">
					<label for="org"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.ORG') ?></label>
						<input style="width:100%" id="org" name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][org][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->org; ?>">
						<label for="address"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.ADDRESS') ?></label>
						<input style="width:100%" id="address" name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][address][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->address; ?>">
						<label for="other"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.OTHER') ?></label>
						<input style="width:100%" id="other" name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][other][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->other; ?>">
						<label for="email"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.EMAIL') ?></label>
						<input style="width:100%" id="org" name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][email][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->email; ?>">
					</div>
				<?php endforeach;?>	
				<?php echo  JTEXT::_('ARTICULUS.AUTHOR.SCOPUSID') ?></label>
					<input name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][scopusId][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->scopusId; ?>">
				<?php echo  JTEXT::_('ARTICULUS.AUTHOR.WOSID') ?></label>
					<input name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][wosId][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->wosId; ?>">
				<?php echo  JTEXT::_('ARTICULUS.AUTHOR.SPINCODE') ?></label>
					<input name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][spinCode][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->spinCode; ?>">
				<?php echo  JTEXT::_('ARTICULUS.AUTHOR.ORCID') ?></label>
					<input name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][ORCID][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->ORCID; ?>">
				<?php echo  JTEXT::_('ARTICULUS.AUTHOR.ELIBRARYID') ?></label>
					<input name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][elibraryID][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->elibraryID; ?>">
				<?php echo  JTEXT::_('ARTICULUS.AUTHOR.SCHOLARID') ?></label>
					<input name='article[info][<?php echo $art_key?>][authors][<?php echo $key;?>][scholarID][<?php echo $language?>]' class="text-center" value="<?php echo $author[$language]->scholarID; ?>">
			</div>
			<?php endforeach; ?>		
			<?php endif; ?>
			<section role="note">
				<input name="pages"class="text-center" value="<?php echo $article->pages; ?>">
			</section>
			<section class="abstract">
				<?php foreach ($languages as $language):?>
					<div class="form-group">
						<label for="abstract[<?php echo $language?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.ABSTRACT') ?>(		<?php echo $language ?>)</label>
						<textarea id="abstract[<?php echo $language?>]" style="width:100%" rows="7" name="article[info][<?php echo $art_key?>][abstract][<?php echo $language;?>]" ><?php echo str_replace('<br/>', "\n",$article->abstract[$language]);?></textarea>
					</div>
				<?php endforeach;?>
			</section>
			<?php if (!empty($article->keywords)):?>
				<section class="form-group" role="note">
				<?php foreach ($languages as $language):?>
					
					<div class="form-group">
						<label for="keywords[<?php echo $language?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.KEYWORDS') ?> (<?php echo $language ?>)</label>
						<textarea id="keywords[<?php echo $art_key?>][<?php echo $language?>]" style="width:100%" class="form-control" name='article[info][<?php echo $art_key;?>][keywords][<?php echo $language?>]'><?php echo implode(';',$article->keywords[$language])?></textarea>
					</div>
				<?php endforeach;?>
			<?php endif; ?>	
			<section class="reference">
			<?php foreach ($languages as $language):?>
			
			<div class="form-group">
				<label for="reference[<?php echo $language?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.REFERENCE') ?>(<?php echo $language ?>)</label>
				<textarea style="width:100%" id="reference[<?php echo $art_key?>][<?php echo $language?>]" rows="7" name='article[info][<?php echo $art_key?>][reference][<?php echo $language?>]'>
			
				<?php foreach ($article->reference[$language] as $reference):?>
					<?php echo "\n".$reference->reference;?>
					<?php endforeach;?>
				</textarea>	
			</div>
			<?php endforeach;?>
			</article>
		<?php endforeach; ?>
	</section>

	<input type="hidden" name="task" value="create" />
	<input type="hidden" name="cid" value=<?php echo $issue->issueID ?> />
	<input type="hidden" name="option" value="com_sjarchive" />
	<input type="hidden" name="boxchecked" value="0" />
</form>