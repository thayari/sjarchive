<?php defined('_JEXEC') or die('Restricted access'); ?>

<form class="form-horizontal" role="form" enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
	<legend><?php echo JTEXT::_('ARTICULUS.ISSUE.MAIN.BLOCK')?></legend>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="article[num]"><?php echo JTEXT::_('ARTICULUS.ISSUE.NUM')?></label>
		<input type="text" class="form-control"  placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.NUM');?>"  name="article[num]" required pattern="[0-9]{2}"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="article[volume]"><?php echo JTEXT::_('ARTICULUS.ISSUE.VOLUME');?></label>
		<input class="form-control"  type="text" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.VOLUME');?>" name="article[volume]" required pattern="[0-9]{1}"/>
	</div>
	<div class="form-group">		
		<label class="col-sm-2 control-label" for="article[part]"><?php echo JTEXT::_('ARTICULUS.ISSUE.PART');?></label>
		<input class="form-control"  type="text" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.PART');?>" name="article[part]"  pattern="[0-9]{1}"/>
	</div>
	<div class="form-group">	
		<label  class="col-sm-2 control-label" for="article[year]"><?php echo JTEXT::_('ARTICULUS.ISSUE.YEAR')?></label>
		<input class="form-control" type="text"  placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.YEAR')?>" name="article[year]"  required pattern="[0-9]{4}"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="article[doi]"><?php echo JTEXT::_('ARTICULUS.ISSUE.DOI')?></label>
		<input type="text" class="form-control"  placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.DOI');?>"  name="article[doi]"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="files[pdf]"><?php echo JTEXT::_('ARTICULUS.ISSUE.PDF');?></label>
		<input class="form-control" type="file"  name="files[pdf]" accept=".pdf"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="files[content]"><?php echo JTEXT::_('ARTICULUS.ISSUE.CONTENT');?></label>
		<input class="form-control" type="file" name="files[content]" accept=".pdf"/>
	</div>	
    
	<div class=" form-group " id="usePdf">
        <label class="control-label"   for="usePdf"><?php echo JTEXT::_('ARTICULUS.ISSUE.USEPDF');?></label>
		<div class="radio">
			<input class="form-control" id="noUsePdf" type="radio" name=article[usePdf] value="1">
            <label class="control-label"   for="noUsePdf"><?php echo JTEXT::_('ARTICULUS.GENERAL.NO');?></label>

			<input  class="form-control" type="radio" id="yesUsePdf" name=article[usePdf] value="0">
            <label class="control-label"   for="yesUsePdf"><?php echo JTEXT::_('ARTICULUS.GENERAL.YES');?></label>
		</div>
        <br/>
	</div>	
    
	<div class=" form-group" id="useContent">
        <label class="control-label"   for="useContent"><?php echo JTEXT::_('ARTICULUS.ISSUE.USECONTENT');?></label>
		<div class="radio ">
            <input class="form-control" id = "yesUseContent" type="radio" name=article[useContent] value="1">
            <label class="control-label"   for="yesUseContent"><?php echo JTEXT::_('ARTICULUS.GENERAL.YES');?></label>
		 <input class="form-control" type="radio" id="noUseContent" name=article[useContent] value="0" checked>
            <label class="control-label"    for="noUseContent"><?php echo JTEXT::_('ARTICULUS.GENERAL.NO');?></label>
		</div>
        <br/>
	</div>
	<div class="form-group">
        <legend style="cursor:pointer" class="sub-params-label"><?php echo JTEXT::_('ARTICULUS.ISSUE.SUB.BLOCK')?></legend>
		<div class=" form-group ">
		<label class="control-label" for="article[special]"><?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIAL');?></label>
		<div class="checkbox">
			<input class="form-control" type="checkbox" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIAL');?>"  name="issue[special]"/>
		</div>
            <br/>
            </div>	
	</div>
	<div class="form-group">
		<label class=" col-sm-2 control-label" for="article[special_comment]"><?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIALCOMMENT');?></label>
		<input class="form-control" type="text" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIALCOMMENT');?>"  name="issue[special_comment]"/>
	</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value ="com_sjarchive"/>
	<input type="hidden" name="controller" value ="issue"/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
