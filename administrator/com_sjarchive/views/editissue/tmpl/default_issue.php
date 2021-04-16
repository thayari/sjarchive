<?php
defined('_JEXEC') or die('Restricted access');
$last_section = NULL;
$document = JFactory::getDocument();
$issue = &$this->issue;
$languages = array('ru-RU', 'en-GB');

/*
* обработка импорта статьи
*
*/

?>


<div class="container">
  <form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
    <h4><?php echo JTEXT::_('ARTICULUS.ISSUE.INFO') ?></h4>
    <div class="row">
      <div class="col-xs-2" style="float:left; margin:5px;">
        <label for="num" class="sr-only"><?php echo  JTEXT::_('ARTICULUS.ISSUE.NUM') ?>:</label>
        <input type="text" id="num" name='article[num]' pattern="\d[0-9]{1,2}" class="form-control" value="<?php echo $issue->num; ?>">
      </div>
      <div class="col-xs-2" style="float:left; margin:5px;">
        <label for="part" class="sr-only"><?php echo  JTEXT::_('ARTICULUS.ISSUE.PART') ?>:</label>
        <input type="text" id="part" pattern="\d[0-9]{1,2}" name='article[part]' class="form-control" value="<?php echo $issue->part; ?>">
      </div>
      <div class="col-xs-2" style="float:left; margin:5px;">
        <label for="volume" class="sr-only"><?php echo  JTEXT::_('ARTICULUS.ISSUE.VOLUME') ?>:</label>
        <input type="text" id="volume" pattern="\d[0-9]{1,3}" name='article[volume]' class="form-control" value="<?php echo $issue->volume; ?>">
      </div>
      <div class="col-xs-2" style="margin:5px;">
        <label for="year" class="sr-only"><?php echo  JTEXT::_('ARTICULUS.ISSUE.YEAR') ?>:</label>
        <input type="text" id="year" pattern="\d[0-9]{1,4}" name='article[year]' class=" form-control" value="<?php echo $issue->year; ?>">
      </div>
      <div class="col-xs-2" style="margin:5px;">
        <label for="doi" class="sr-only">DOI:</label>
        <input type="text" id="doi" name='article[doi]' class=" form-control" value="<?php echo $issue->doi; ?>">
      </div>
      <div class="col-xs-2" style="margin:5px;">
        <label for="doi" class="sr-only"><?php echo  JTEXT::_('ARTICULUS.ISSUE.CREATEDDATE') ?>:</label>
        <input type="text" id="createdDate" name='article[createdDate]' class=" form-control" value="<?php echo $issue->createdDate; ?>">
        <div class="col-xs-2" style="margin:5px;">
          <label for="special" style="float:left; padding-right:10px" class="sr-only"><?php echo  JTEXT::_('ARTICULUS.ISSUE.SPECIAL') ?>:</label>
          <input id="special" type="checkbox" name='article[special]' class="form-control" <?php echo !empty($issue->special) ? "checked" : ""; ?>">
        </div>
      </div>
    </div>
    <hr>

    <?php foreach ($this->issue->articles as $art_key => &$article) : ?>

      <h4><?php echo JTEXT::_('ARTICULUS.ARTICLE.INFO'); ?></h4>
      <div class="row">
        <div class="col-xs-2" style=" float:left; margin:5px;">
          <label for="doi"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.DOI') ?></label>
          <input pattern="[A-Za-z0-9_]+([-.][A-Za-z0-9_]+)*\/[A-Za-z0-9_]+([-.][A-Za-z0-9_]+)*" type="text" class="text-center form-control" id="doi" name='article[info][<?php echo $art_key ?>][doi]' value="<?php echo $article->doi; ?>">
        </div>
        <div class="col-xs-2" style="margin:5px;">
          <label for="udk"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.UDK') ?></label>
          <input type="text" class="text-center form-control" id="udk" name='article[info][<?php echo $art_key ?>][udk]' value="<?php echo $article->udk; ?>">
        </div>
        <div class="col-xs-2">
          <label style="float:left; padding-right:10px" for="published"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLUSHED') ?></label>
          <input type="checkbox" name='article[info][<?php echo $art_key ?>][published]' <?php echo !empty($article->published) ? "checked" : "" ?>>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-2">
          <label for="artType"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.ARTTYPE') ?></label>
          <select class="text-center form-control" name='article[info][<?php echo $art_key ?>][art_type]'>
            <option <?php echo $article->artType == 'EDI' ? "selected" : "" ?> value="EDI"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.EDITOR') ?></option>
            <option <?php echo $article->artType == 'RAR' ||  empty($article->artType) ? "selected" : "" ?> value="RAR"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TYPE.SCIENCE') ?></option>
          </select>
        </div>
        <div class="col-xs-2">
          <label for="translation"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.LANGUAGE') ?></label>
          <select class="text-center form-control" multiple name='article[info][<?php echo $art_key ?>][translation]'>
            <option <?php echo strpos($article->translation, 'en-GB') !== FALSE ? "selected" : "" ?> value="en-GB"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TRANSLATION.EN-GB') ?></option>
            <option <?php echo strpos($article->translation, 'ru-RU') !== FALSE ||  empty($article->translation) ? "selected" : "" ?> value="ru-RU"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TRANSLATION.RU-RU') ?></option>
          </select>
        </div>

        <div class="col-xs-2">
          <label for="pdf"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PDF') ?><?php echo $article->pdf ?></label>
          <input class="text-center form-control" type="file" id="pdf" name='article[<?php echo $article->doi ?>]' value="<?php echo $article->editedDate; ?>">
        </div>
      </div>
      <hr>
      <h4><?php echo JTEXT::_('ARTICULUS.ARTICLE.GENERAL'); ?></h4>
      <!----СТРАНИЦЫ статьи--->

      <div style="margin:5px" class="row">
        <label for="pages"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PAGES') ?></label>
        <input type="text" id="pages" name="article[info][<?php echo $art_key ?>][pages]" value="<?php echo $article->pages; ?>">
      </div>
      <!----Секция статьи--->

      <div class="row" style="margin:5px" style="width:100%">
        <?php foreach ($languages as $language) : ?>
          <div style="width:45%; float:left; margin:5px">
            <label style="width:50%; " for="section"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.SECTION') ?>( <?php echo $language ?>)</label>
            <input type="text" style="width:95%" id="section" name='article[info][<?php echo $art_key ?>][section][<?php echo $language ?>]' class="text-center form-control" value="<?php echo $article->section[$language]; ?>">
          </div>
        <?php endforeach; ?>
      </div>

      <!----Заголовок статьи--->
      <div class="row" style="margin:5px" style="width:100%">
        <?php foreach ($languages as $language) : ?>
          <div style="width:45%; float:left; margin:5px">
            <label style="width:50%; for=" title"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TITLE') ?>( <?php echo $language ?>)</label>
            <input type="text" style="width:95%" id="title" name='article[info][<?php echo $art_key ?>][title][<?php echo $language ?>]' class="text-center form-control" value="<?php echo $article->title[$language]; ?>">
          </div>
        <?php endforeach; ?>
      </div>

      <!----АННОТАЦИЯ статьи--->
      <div class="row" style="width:100%">
        <?php foreach ($languages as $language) : ?>
          <div style="width:45%; float:left; margin:5px">
            <label for="abstract[<?php echo $language ?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.ABSTRACT') ?>( <?php echo $language ?>)</label>
            <textarea style="width:95%" id="abstract[<?php echo $language ?>]" rows="15" name="article[info][<?php echo $art_key ?>][abstract][<?php echo $language; ?>]"><?php echo str_replace('<br/>', "\n", $article->abstract[$language]); ?></textarea>
          </div>
        <?php endforeach; ?>
      </div>

      <!----ТЕКСТ статьи--->
      <div class="row" style="width:100%">
        <?php foreach ($languages as $language) : ?>
          <div style="width:45%; float:left; margin:5px">
            <label for="text"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.TEXT') ?>(<?php echo $language ?>)</label>
            <textarea style="width:95%" id="text" name='article[info][<?php echo $art_key ?>][text][<?php echo $language ?>]' class="text-center form-control"><?php echo $article->text[$language]; ?></textarea>
          </div>
        <?php endforeach; ?>
      </div>

      <!----КЛЮЧЕВЫЕ СЛОВА статьи--->
      <?php if (!empty($article->keywords)) : ?>
        <div class="row" style="width:100%">
          <?php foreach ($languages as $language) : ?>

            <div style="width:45%; float:left; margin:5px">
              <label for="keywords[<?php echo $language ?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.KEYWORDS') ?> (<?php echo $language ?>)</label>
              <textarea style="width:95%" id="keywords[<?php echo $art_key ?>][<?php echo $language ?>]" class="form-control" name='article[info][<?php echo $art_key; ?>][keywords][<?php echo $language ?>]'><?php echo implode(';', $article->keywords[$language]) ?></textarea>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        </div>

        <hr class="clearfix">

        <!----АВТОРЫ статьи--->
        <?php if (!empty($article->authors)) : ?>
          <h4><?php echo  JTEXT::_('ARTICULUS.ARTICLE.AUTHORS') ?></h4>
          <?php foreach ($article->authors as $key => $author) : ?>

            <?php foreach ($languages as $language) : ?>
              <div style="float:left; width:50%">
                <h5><?php echo $language ?></h5>
                <div class="row" style="width:100%">
                  <div class="col-xs-2" style="float:left; margin:5px;">
                    <label for="surname"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.SURNAME') ?></label>
                    <input type="text" id="surname" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][surname][<?php echo $language ?>]' value="<?php echo $author[$language]->surname; ?>">
                  </div>
                  <div class="col-xs-2" style="margin:5px;">
                    <label for="firstname"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.FIRSTNAME') ?></label>
                    <input type="text" id="firstname" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][firstname][<?php echo $language ?>]' value="<?php echo $author[$language]->firstname; ?>">
                  </div>
                  <div class="col-xs-2" style="float:left; margin:5px;">
                    <label for="org"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.ORG') ?></label>
                    <textarea id="org" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][org][<?php echo $language ?>]'><?php echo $author[$language]->org; ?></textarea>
                  </div>
                  <div class="col-xs-2" style="margin:5px;">
                    <label for="address"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.ADDRESS') ?></label>
                    <textarea id="address" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][address][<?php echo $language ?>]'><?php echo $author[$language]->address; ?></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-2">
                    <label for="other"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.OTHER') ?></label>
                    <textarea id="other" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][other][<?php echo $language ?>]'><?php echo $author[$language]->other; ?></textarea>
                  </div>
                  <div class="col-xs-2">
                    <label for="email"><?php echo  JTEXT::_('ARTICULUS.AUTHOR.EMAIL') ?></label>
                    <input type="text" id="email" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][email][<?php echo $language ?>]' value="<?php echo $author[$language]->email; ?>">
                  </div>
                </div>
              </div>
              <div class="row">
              <div class="col-xs-2" style="float:left; margin:5px">
                <label><?php echo  JTEXT::_('ARTICULUS.AUTHOR.SCOPUSID') ?></label>
                <input type="text" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][scopusId][<?php echo $language ?>]' value="<?php echo $author[$language]->scopusId; ?>">
              </div>
              <div class="col-xs-2" style="float:left; margin:5px">
                <label><?php echo  JTEXT::_('ARTICULUS.AUTHOR.WOSID') ?></label>
                <input type="text" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][wosId][<?php echo $language ?>]' value="<?php echo $author[$language]->wosId; ?>">
              </div>
              <div class="col-xs-2" style="float:left; margin:5px">
                <label><?php echo  JTEXT::_('ARTICULUS.AUTHOR.SPINCODE') ?></label>
                <input type="text" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][spinCode][<?php echo $language ?>]' value="<?php echo $author[$language]->spinCode; ?>">
              </div>
              <div class="col-xs-2" style="float:left; margin:5px">
                <label><?php echo  JTEXT::_('ARTICULUS.AUTHOR.ORCID') ?></label>
                <input type="text" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][ORCID][<?php echo $language ?>]' value="<?php echo $author[$language]->ORCID; ?>">
              </div>
              <div class="col-xs-2" style="float:left; margin:5px">
                <label><?php echo  JTEXT::_('ARTICULUS.AUTHOR.ELIBRARYID') ?></label>
                <input type="text" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][elibraryID][<?php echo $language ?>]' value="<?php echo $author[$language]->elibraryID; ?>">
              </div>
              <div class="col-xs-2" style="float:left; margin:5px">
                <label><?php echo  JTEXT::_('ARTICULUS.AUTHOR.SCHOLARID') ?></label>
                <input type="text" name='article[info][<?php echo $art_key ?>][authors][<?php echo $key; ?>][scholarID][<?php echo $language ?>]' value="<?php echo $author[$language]->scholarID; ?>">
              </div>
            </div>
            <?php endforeach; ?>

          <?php endforeach; ?>
          <hr>
        <?php endif; ?>

        <div class="row" style="margin:5px" style="width:100%">
          <?php foreach ($languages as $language) : ?>

            <div style="width:45%; float:left; margin:5px">

              <label for="reference[<?php echo $language ?>]"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.REFERENCE') ?>(<?php echo $language ?>)</label>
              <?php $str = NULL; ?>

              <?php foreach ($article->reference[$language] as $reference) : ?>
                <?php $str .= trim($reference->reference) . "\r\n"; ?>
              <?php endforeach; ?>

              <textarea style="width:95%" id="reference[<?php echo $art_key ?>][<?php echo $language ?>]" rows="40" name='article[info][<?php echo $art_key ?>][reference][<?php echo $language ?>]'><?php echo trim($str) ?></textarea>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="row">

          <div class="col-xs-2">
            <label for="submitedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.SUBMITEDDATE') ?></label>
            <input class="text-center form-control" type="date" id="submitedDate" name='article[info][<?php echo $art_key ?>][submitedDate]' value="<?php echo $article->submitedDate; ?>">
          </div>
          <div class="col-xs-2">
            <label for="editedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.EDITEDDATE') ?></label>
            <input class="text-center form-control" type="date" id="editedDate" name='article[info][<?php echo $art_key ?>][editedDate]' value="<?php echo $article->editedDate; ?>">
          </div>
          <div class="col-xs-2">
            <label for="publishedDate"><?php echo  JTEXT::_('ARTICULUS.ARTICLE.PUBLISHDEDATE') ?></label>
            <input class="text-center form-control" type="date" id="publishedDate" name='article[info][<?php echo $art_key ?>][publishedDate]' value="<?php echo $article->publishedDate; ?>">
          </div>

        <?php endforeach; ?>






        <input type="hidden" name="task" value="create" />
        <input type="hidden" name="cid" value=<?php echo $issue->ID ?> />
        <input type="hidden" name="option" value="com_sjarchive" />
        <input type="hidden" name="boxchecked" value="0" />
  </form>
</div>