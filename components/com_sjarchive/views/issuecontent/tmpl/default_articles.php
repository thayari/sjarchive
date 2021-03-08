<?php
defined('_JEXEC') or die('Restricted access');
$last_section = NULL;
$document = JFactory::getDocument();
$language = JFactory::getLanguage()->getTag();
$sys_languages = array_keys(JFactory::getLanguage()->getKnownLanguages());

$issue = &$this->issue;

$document->setMetaData('citation_journal_title', JTEXT::_('ARTICULUS.PUBLISHER'));
$document->setMetaData('citation_volume', $issue->volume);
$document->setMetaData('citation_issue', $issue->num);
$document->setMetaData('citation_date', $issue->pubDate);
$link = array('controller' => 'issue', 'task' => 'issue.download', 'year' => $issue->year, 'num' => $issue->num);
?>

<table class="table table-responsive-sm table-borderless" role="list" lang="<?php echo $article->language ?>">
	<thead>
		<h2 class="text-center mt-5"><?php echo JTEXT::_('ARTICULUS.ISSUE.TITLE'); ?></br>
			<small class="text-muted">
				<?php echo !empty($issue->volume) ?  JTEXT::_('ARTICULUS.ISSUE.VOLUME') . ' '	. $issue->volume : NULL ?>
				<?php echo !empty($issue->num)	  ?  JTEXT::_('ARTICULUS.ISSUE.NUM') . ' ' . $issue->num : NULL ?>
				<?php echo !empty($issue->part)   ?  JTEXT::_('ARTICULUS.ISSUE.PART') . ' '	. $issue->part : NULL ?>
				<?php echo !empty($issue->year)   ?  $issue->year : NULL ?>
				<?php echo !empty($issue->special) ?  JTEXT::_('ARTICULUS.ISSUE.SPECIAL') . $issue->special	: NULL ?>
			</small>
		</h2>

		<div class="text-center">

			<?php if (empty($issue->pdf)) : ?>
				<?php echo JTEXT::_('ARTICULUS.ISSUE.EMPTYFILE'); ?>
			<?php else : ?>
				<?php $link['ftype'] = 'pdf'; ?>
				<a style="width: 25px;   margin: 20px; background: url('/media/static/icons/pdf.png') no-repeat; padding-left: 25px; background-size:contain;" href="<?php echo (JRoute::_($link)); ?>">
					<?php echo (JTEXT::_('ARTICULUS.ISSUE.PDF')) ?>
				</a>
			<?php endif; ?>
			|
			<?php if (empty($issue->content)) : ?>
				<?php echo JTEXT::_('ARTICULUS.ISSUE.EMPTYFILE'); ?>
			<?php else : ?>
				<?php $link['ftype'] = 'content'; ?>
				<a style="width: 25px;   margin: 20px; background: url('/media/static/icons/pdf.png') no-repeat; padding-left: 25px; background-size:contain;" href="<?php echo (JRoute::_($link)); ?>">
					<?php echo (JTEXT::_('ARTICULUS.ISSUE.CONTENT')) ?>
				</a>
			<?php endif; ?>
		</div>
	</thead>
	<tbody>
		<?php foreach ($this->articles as $no => &$article) : ?>
			<?php if ($last_section <> $article->section) : ?>
				<td colspan="3" role="list">
					<h3 class="mt-4"><?php echo $article->section; ?></h3>
				</td>
				</tr>
				<tr>
				<?php endif; ?>
				<tr>
					<td colspan="3" style="padding-bottom: 0;">
						<article role="item">

							<section class="title">

								<h4><a href="<?php echo (JRoute::_(array('task' => 'article.display', 'year' => $issue->year, 'num' => $issue->num, 'pages' => $article->pages))); ?>">
										<?php echo $article->title ?>
									</a></h4>
							</section>
					</td>
				</tr>
				<tr>
					<td>
						<section class="authors">
							<?php if (isset($article->authors)) : ?>


								<ul style="margin:0px; padding:0px">
									<?php foreach ($article->authors as $key => $author) : ?>


										<li style="display: inline" class="author" style="list-style-type: none;">

											<?php $str = JFactory::getLanguage()->getTag() == 'ru-RU' ? $author->surname . ' ' . $author->lastname : $author->lastname . ' ' . $author->surname ?>
											<?php if ($key <> count($article->authors) - 1) : ?>
												<?php $str .= ','; ?>
											<?php endif ?>

											<?php echo $str ?>



										</li>
									<?php endforeach; ?>
								</ul>

							<?php endif; ?>
						</section>
							<?php if (!empty($article->doi)) : ?>
								<section class="doi">
									<span>DOI:<a href="http://doi.org/<?php echo $article->doi ?>"> <?php echo $article->doi ?></a></span>
								</section>
							<?php endif; ?>
							<hr class="style15" />
							</article>
					</td>
					<td style="padding:10px 10px; width: 135px; ">
						<section class="file">
							<a style="   background: url('/media/static/icons/pdf.png') no-repeat; padding-left: 25px; background-size:contain;" href="<?php echo (JRoute::_(array('task' => 'article.download', 'year' => $issue->year, 'num' => $issue->num, 'pages' => $article->pages))); ?>">
								<?php echo  $article->translation == 'en-GB' ? JTEXT::_('ARTICULUS.ARTICLE.DOWNLOAD') . ' (ENG)' : JTEXT::_('ARTICULUS.ARTICLE.DOWNLOAD') . ' (RUS)'; ?>
							</a>
						</section>
					</td>
					<td width=75px; style="padding:10px 10px;">
						<section role="note">
							<?php $pages = explode('-', $article->pages); ?>
							<?php echo $article->pages ?>
						</section>

					</td>

				</tr>

				<?php $last_section = $article->section; ?>
			<?php endforeach; ?>
	</tbody>
</table>

</section>