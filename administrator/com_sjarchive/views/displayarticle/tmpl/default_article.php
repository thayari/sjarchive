<?php
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$article = &$this->article;
$languages = &$this->languages;
?>


<form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">

  <section role="list" lang="<?php echo $article->language ?> >

<?php if ($last_section <> $article->section) : ?>
	<section role =" list">
    <h3>
      <?php foreach ($languages as $language) : ?>
        <p> <span><?php echo $language . ': ' ?></span>
          <?php echo $article->section[$language]; ?>
        </p>
      <?php endforeach; ?>
    </h3>
  </section>
<?php endif; ?>

<article role="item">
  <section class="title" style="margin:10px 0px">

    <h4>
      <?php foreach ($languages as $language) : ?>
        <p><span><?php echo $language . ': ' ?></span>
          <?php echo $article->title[$language] ?>
        <?php endforeach; ?>
    </h4>
  </section>

  <? if(isset($article->authors)) {
    echo '<section class="citation_author" style="margin:10px 0px">';
      foreach ($languages as $language) {
        foreach ($article->authors as $author) {
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

  <?php if (!empty($article->doi)) : ?>
    <section style="margin:10px 0px">
      <span>DOI:<a href="http://doi.org/<?php echo $article->doi ?>"> <?php echo $article->doi ?></a></span>
    </section>
  <?php endif; ?>
  <section role="note" style="margin:10px 0px">

    <?php echo JTEXT::_('ARTICULUS.ARTICLE.PAGES'); ?>:
    <?php echo $article->pages ?>
  </section>
  <section class="abstract" style="margin:10px 0px">
    <?php foreach ($languages as $language) : ?>
      <p><span><?php echo $language . ': ' ?></span><?php echo $article->abstract[$language] ?></p>
    <?php endforeach; ?>
  </section>
  <?php if (isset($article->keywords)) : ?>
    <section role="note" style="margin:10px 0px">
      <ul>
        <?php foreach ($languages as $language) : ?>
          <?php foreach ($article->keywords[$language] as $keyword) : ?>
            <li class="keyword" style="list-style-type:none;display: inline"><small><?php echo $keyword ?>;</small></li>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </ul>
    </section>
  <?php endif ?>
  <?php if (isset($article->reference)) : ?>
    <?php foreach ($languages as $language) : ?>
      <section class="reference" style="margin:10px 0px">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php echo $language ?>">
          <?php echo JTEXT::_('ARTICULUS.ARTICLE.REFERENCE'); ?>
        </a>

        <div id="collapseOne<?php echo $language ?> class="panel-collapse collapse">
          <div class="panel-body">
            <ol>
              <?
                if (!empty($article->reference[$language])) {
                  foreach ($article->reference[$language] as $reference) {
                    echo '<li class="reference"><small>' . $reference->reference . '</small></li>';
                  }
                }
              ?>
            </ol>
          </div>
        </div>
      </section>
    <?php endforeach; ?>
  <?php endif; ?>
  <section class="file" style="margin:10px 0px">
    <?php // $document->setMetaData('citation_abstract_html_url',JRoute::_('index.php?option=com_sjarchive&task=article.display&year='.$issue->year.'&num='.$issue->num.(!empty($issue->part)?'&part='.$issue->part:'').(!empty($issue->volume)?'&volume='.$issue->volume:'').(!empty($issue->special)?'&special='.$issue->special:'').'&pages='.$article->pages));
    ?>
    <?php // $document->setMetaData('citation_pdf_url',JRoute::_('index.php?option=com_sjarchive&controller=article&task=article.download&year='.$issue->year.'&num='.$issue->num.(!empty($issue->part)?'&part='.$issue->part:'').(!empty($issue->volume)?'&volume='.$issue->volume:'').(!empty($issue->special)?'&special='.$issue->special:'').'&pages='.$article->pages));
    ?>
    <a href="<?php echo (JRoute::_('index.php?option=com_sjarchive&task=article.download&id=' . $article->ID)); ?>">
      <?php echo JTEXT::_('ARTICULUS.ARTICLE.DOWNLOAD'); ?>
    </a>

  </section>
</article>
</section>
<input type="hidden" name="task" value="" />
<input type="hidden" name="option" value="com_sjarchive" />
<input type="hidden" name="controller" value="article" />
<input type="hidden" name="cid" value="<?php echo $article->ID ?>" />
</form>