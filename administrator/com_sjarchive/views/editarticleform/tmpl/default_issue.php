<?php
defined('_JEXEC') or die('Restricted access');
$last_section = NULL;
$document = JFactory::getDocument();
$article = &$this->article;
$languages = array('ru-RU','en-GB');
?>
<?// $document->setMetaData('citation_journal_title', JTEXT::_('ARTICULUS.PUBLISHER'));
// $document->setMetaData('citation_volume', $issue->volume);
// $document->setMetaData('citation_issue', $issue->issue);
// $document->setMetaData('citation_date', $issue->publishedDate); ?>

<form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
		<section role="list">
				<label for="submitedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.SUBMITEDDATE') ?></label>
				<input id="submitedDate" style="width:100%" name='article[info][submitedDate]' class="text-center" value="<?php echo $article->submitedDate; ?>">
			
				<label for="editedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.EDITEDDATE') ?></label>
				<input id="editedDate" style="width:100%" name='article[info][editedDate]' class="text-center" value="<?php echo $article->editedDate; ?>">
		
				<label for="publishedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLISHDEDATE') ?></label>
				<input id="publishedDate" style="width:100%" name='article[info][publishedDate]' class="text-center" value="<?php echo $article->publishedDate; ?>">

				<label for="createdDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.CREATEDDATE') ?></label>
				<input id="publishedDate" style="width:100%" name='article[info][createdDate]' class="text-center" value="<?php echo $article->createdDate; ?>">
			
				<label for="doi"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.DOI') ?></label>
				<input  id="doi" style="width:100%" name='article[info][doi]' class="text-center" value="<?php echo $article->doi; ?>">
			
				<label for="udk"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.UDK') ?></label>
				<input id="udk" style="width:100%" name='article[info][udk]' class="text-center" value="<?php echo $article->udk; ?>">
				
				<label for="artType"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.ARTTYPE') ?></label>
				<select name='article[info][artType]'>
					<option value="EDI"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.EDITOR') ?></option>
					<option selected value="RAR"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.SCIENCE') ?></option>
				</select>
				<label for="translation"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.LANGUAGE') ?></label>
				<select multiple name='article[info][translation]'>
					<option selected value="ru-RU"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TRANSLATION.RU-RU') ?></option>
					<option value="en-GB"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TRANSLATION.EN-GB') ?></option>
				</select>
				<label for="published"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLUSHED') ?></label>
				<select name='article[info][published]'>
					<option selected value="1"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLUSHED') ?></option>
					<option value="0"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.UNPUBLISHED') ?></option>
				</select>

				<label for="pdf"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PDF') ?><?php echo $article->pdf?></label>
				<input type="file"id="pdf" style="width:100%" name='article[<?php echo $article->doi?>]' class="text-center" value="<?php echo $article->editedDate; ?>">
			</section>

		<?php foreach ($languages as $language):?>
			<section role="list">
			
				<label for="section"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.SECTION') ?>(	<?php echo $language ?>)</label>
				<input id="section" style="width:100%" name='article[info][section][<?php echo $language?>]' class="text-center" value="<?php echo $article->section[$language]; ?>">
			</section>
		<?php endforeach;?>
		<article role="item">

    <?
      foreach ($languages as $language) {
        echo '<section class="title">' . $language;
        echo '<label for="title">' . JTEXT::_('ARTICULUS.ARTICLE.TITLE') . '</label>';
        echo '<input id="title" style="width:100%" name="article[info][title][' . $language . ']" class="text-center" value="' . $article->title[$language] . '"></section>';
      }

      if (!empty($article->authors)) {
        $authors_cite = NULL;
        echo '<section class="authors">';
        foreach ($article->authors as $key=>$author) {
          echo '<div class="form-group">';
          echo '<label><h2>' . JTEXT::_('ARTICULUS.ARTICLE.AUTHOR') . '</h2></label>';
          foreach ($languages as $language) {
            echo '<div class="form-group">' . $language;
            // $authors_cite .= $author->surname . ',' . $author->lastname . ';';
            // echo '<label for="authorId">ID</label>';
            echo '<input type="hidden" id="authorId" name="article[info][authors][' . $key . '][authorId][' . $language . ']" class="text-center" value="' . $author[$language]->authorId . '">';
            echo '<label for="surname">' . JTEXT::_('ARTICULUS.AUTHOR.SURNAME') . '</label>';
						echo '<input id="surname" name="article[info][authors][' . $key . '][surname][' . $language . ']" class="text-center" value="' . $author[$language]->surname . '">';
            echo '<label for="lastname">' . JTEXT::_('ARTICULUS.AUTHOR.LASTNAME') . '</label>';
						echo '<input id="lastname" name="article[info][authors][' . $key . '][lastname][' . $language . ']" class="text-center" value="' . $author[$language]->lastname . '">';
					  echo '<label for="org">' . JTEXT::_('ARTICULUS.AUTHOR.ORG') . '</label>';
						echo '<input style="width:100%" id="org" name="article[info][authors][' . $key . '][org][' . $language . ']" class="text-center" value="' . $author[$language]->org . '">';
						echo '<label for="address">' . JTEXT::_('ARTICULUS.AUTHOR.ADDRESS') . '</label>';
						echo '<input style="width:100%" id="address" name="article[info][authors][' . $key . '][address][' . $language . ']" class="text-center" value="' . $author[$language]->address . '">';
						echo '<label for="other">' . JTEXT::_('ARTICULUS.AUTHOR.OTHER') . '</label>';
						echo '<input style="width:100%" id="other" name="article[info][authors][' . $key . '][other][' . $language . ']" class="text-center" value="' . $author[$language]->other . '">';
						echo '<label for="email">' . JTEXT::_('ARTICULUS.AUTHOR.EMAIL') . '</label>';
						echo '<input style="width:100%" id="org" name="article[info][authors][' . $key . '][email][' . $language . ']" class="text-center" value="' . $author[$language]->email . '"></div>';
          }
          echo '<label>' . JTEXT::_('ARTICULUS.AUTHOR.SCOPUSID') . '</label>';
					echo '<input name="article[info][authors][' . $key . '][scopusId][' . $language . ']" class="text-center" value="' . $author[$language]->scopusId . '">';
				  echo '<label>' . JTEXT::_('ARTICULUS.AUTHOR.WOSID') . '</label>';
					echo '<input name="article[info][authors][' . $key . '][wosId][' . $language . ']" class="text-center" value="' . $author[$language]->wosId . '">';
				  echo '<label>' . JTEXT::_('ARTICULUS.AUTHOR.SPINCODE') . '</label>';
					echo '<input name="article[info][authors][' . $key . '][spinCode][<' . $language . ']" class="text-center" value="' . $author[$language]->spinCode . '">';
				  echo '<label>' . JTEXT::_('ARTICULUS.AUTHOR.ORCID') . '</label>';
					echo '<input name="article[info][authors][' . $key . '][ORCID][<' . $language . ']" class="text-center" value="' . $author[$language]->ORCID . '">';
					echo '<label>' . JTEXT::_('ARTICULUS.AUTHOR.ELIBRARYID') . '</label>';
					echo '<input name="article[info][authors][' . $key . '][elibraryID][<' . $language . ']" class="text-center" value="' . $author[$language]->elibraryID . '">';
					echo '<label>' . JTEXT::_('ARTICULUS.AUTHOR.SCHOLARID') . '</label>';
					echo '<input name="article[info][authors][' . $key . '][scholarID][<' . $language . ']" class="text-center" value="' . $author[$language]->scholarID . '">';
        }
      }
    ?>

			<section role="note">
				<input name="article[info][pages]"class="text-center" value="<?php echo $article->pages; ?>">
			</section>
			<section class="abstract">
				<?php foreach ($languages as $language):?>
					<div class="form-group">
						<label for="abstract[<?php echo $language?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.ABSTRACT') ?>(		<?php echo $language ?>)</label>
						<textarea id="abstract[<?php echo $language?>]" style="width:100%" rows="7" name="article[info][abstract][<?php echo $language;?>]" ><?php echo str_replace('<br/>', "\n",$article->abstract[$language]);?></textarea>
					</div>
				<?php endforeach;?>
			</section>
			<?php if (!empty($article->keywords)):?>
				<section class="form-group" role="note">
				<?php foreach ($languages as $language):?>
					
					<div class="form-group">
						<label for="keywords[<?php echo $language?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.KEYWORDS') ?> (<?php echo $language ?>)</label>
						<textarea id="keywords[<?php echo $language?>]" style="width:100%" class="form-control" name='article[info][<?php echo $art_key;?>][keywords][<?php echo $language?>]'><?php echo implode(';',$article->keywords[$language])?></textarea>
					</div>
				<?php endforeach;?>
			<?php endif; ?>	

			<section class="reference">
			<?php foreach ($languages as $language):?>

			<div class="form-group">
				<label for="reference[<?php echo $language?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.REFERENCE') ?>(<?php echo $language ?>)</label>
				<textarea style="width:100%" id="reference[<?php echo $language?>]" rows="7" name='article[info][reference][<?php echo $language?>]'>
      <? if (!empty($article->reference[$language])) {
          foreach ($article->reference[$language] as $reference) {
            echo "\n" . $reference->reference;
          }
        } ?>

				</textarea>	
			</div>
			<?php endforeach;?>
			</article>

	</section>

	<input type="hidden" name="task" value="create" />
	<input type="hidden" name="article[info][cid]" value=<?php echo $article->ID ?> />
	<input type="hidden" name="option" value="com_sjarchive" />
	<input type="hidden" name="boxchecked" value="0" />
</form>