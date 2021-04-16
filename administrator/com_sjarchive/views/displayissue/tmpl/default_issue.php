<?
defined('_JEXEC') or die('Restricted access');
$last_section = NULL;
$issue = &$this->issue;
$languages = &$this->languages;
?>
</section>

<?
$params = JComponentHelper::getParams( 'com_sjarchive' );
$lang = JFactory::getLanguage();
if ($lang->getTag() == 'ru-RU') {
  $title = $params->get('title_ru');
} else {
  $title = $params->get('title_en');
}
?>

<h1><?echo $title . ' ' . $issue->num . '/' . $issue->year?></h1>

<table role="list" lang="<? echo $article->language?>" >

	<tbody>
	<? foreach ($this->issue->articles as $no=>&$article):?>
		<? if($last_section <> $article->section):?>
		<td colspan="3"role ="list">
			<h3>
				<? foreach ($languages as $language):?>
					<? if(!empty($article->section[$language])):?>
						<p class ="text-center"><span><? echo $language.': '?></span><? echo $article->section[$language] ;?></p>
					<? endif;?>
				<? endforeach;?>
			</h3>
		</td>
		</tr><tr>
		<? endif;?>
	<tr>
	<td colspan="3">
		<article role="item">
			
			<section class="title">

				<h4>
					<? foreach ($languages as $language):?>
						<p> <span><? echo $language.': '?></span>
							<a href="<? echo (JRoute::_('index.php?option=com_sjarchive&task=article.display&id='.$article->ID));?>">
								<? echo $article->title[$language]?>
							</a>
						</p>
					<? endforeach;?>
				</h4>
			</section>
	</td>
	</tr>
	<tr>
<td>	
			<section class="authors">
			<? if(isset($article->authors)) {

		    echo '<ul style="margin:0">';

          foreach ($article->authors as $author) {
            foreach ($languages as $language) {
              // $authors_cite .= $author[$language]->surname . ',' . $author[$language]->firstname . ';';
              echo '<li class="author" style="list-style-type: none;">';
              echo '<div><span>' . $language . ': </span>';
              echo '<span class="id"><small>' . $author[$language]->authorId . '</small></span>  ';
              echo '<span style="font-weight:bold;">' . $author[$language]->surname . ' ' .$author[$language]->firstname . '</span> ';
              if (!empty($author[$language]->org)) {
                echo ($author[$language]->org);
              }
              if (!empty($author[$language]->scopusId)) {
                echo ', <span>' . JTEXT::_('ARTICULUS.ARTICLE.AUTHOR.SCOPUSID') . ': ' . $author[$language]->scopusId . '</span> ';
              }
              if (!empty($author[$language]->wosId)) {
                echo ', <span>' . JTEXT::_('ARTICULUS.ARTICLE.AUTHOR.WOSID') . ': ' . $author[$language]->wosId . '</span> ';
              }
              if (!empty($author[$language]->spinCode)) {
                echo ', <span>' . JTEXT::_('ARTICULUS.ARTICLE.AUTHOR.SPINCODE') . ': ' . $author[$language]->spinCode . '</span> ';
              }
              if (!empty($author[$language]->ORCID)) {
                echo ', <span>' . JTEXT::_('ARTICULUS.ARTICLE.AUTHOR.ORCID') . ': ' . $author[$language]->ORCID . '</span> ';
              }
							if (!empty($author[$language]->elibraryID)) {
								echo ', <span>' . JTEXT::_('ARTICULUS.ARTICLE.AUTHOR.ELIBRARYID') . ': ' . $author[$language]->elibraryID . '</span> ';
							}
							if (!empty($author[$language]->scholarID)) {
								echo ', <span>' . JTEXT::_('ARTICULUS.ARTICLE.AUTHOR.SCHOLARID') . ': ' . $author[$language]->scholarID . '</span> ';
							}
            }
          }
        } ?>
			</section>
			<section>
		<? if(!empty($article->doi)):?>
			<section>
				<span>DOI:<a href="http://doi.org/<? echo $article->doi?>"> <? echo $article->doi?></a></span>
			</section>
		<? endif;?>
	
	</article>
	</td>
	<td style="padding:10px 10px;>
				<section class="file">
				<a href="<? echo (JRoute::_('index.php?option=com_sjarchive&task=article.download&id='.$article->ID));?>">
					<? echo JTEXT::_('ARTICULUS.ARTICLE.DOWNLOAD');?>
				</a>
			</section>
			</td>

		</tr>
<? endforeach;?>
	</tbody>
</table>

</section>
             