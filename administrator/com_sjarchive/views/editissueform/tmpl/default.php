<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $issue = &$this->issue;?>
<form class="form-horizontal" role="form" enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
	<legend><?php echo JTEXT::_('ARTICULUS.ISSUE.MAIN.BLOCK')?></legend>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="issue[num]"><?php echo JTEXT::_('ARTICULUS.ISSUE.NUM')?></label>
		<input type="text" class="form-control"  placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.NUM');?>"  name="issue[num]" value="<?php echo $issue->num ?>" required pattern="[0-9]{2}"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="issue[volume]"><?php echo JTEXT::_('ARTICULUS.ISSUE.VOLUME');?></label>
		<input class="form-control"  type="text" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.VOLUME');?>" name="issue[volume]" value="<?php echo $issue->volume ?>" required pattern="[0-9]{1}"/>
	</div>
	<div class="form-group">		
		<label class="col-sm-2 control-label" for="issue[part]"><?php echo JTEXT::_('ARTICULUS.ISSUE.PART');?></label>
		<input class="form-control"  type="text" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.PART');?>" name="issue[part]" value="<?php echo $issue->part ?>" pattern="[0-9]{1}"/>
	</div>
	<div class="form-group">	
		<label  class="col-sm-2 control-label" for="issue[year]"><?php echo JTEXT::_('ARTICULUS.ISSUE.YEAR')?></label>
		<input class="form-control" type="text"  placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.YEAR')?>" name="issue[year]" value="<?php echo $issue->year ?>"  required pattern="[0-9]{4}"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="issue[doi]"><?php echo JTEXT::_('ARTICULUS.ISSUE.DOI')?></label>
		<input type="text" class="form-control"  placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.DOI');?>" value="<?php echo $issue->doi ?>"  name="issue[doi]"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="files[pdf]"><?php echo JTEXT::_('ARTICULUS.ISSUE.PDF');?></label>
		<?php echo $issue->pdf ?>
		<input class="form-control" type="file"  name="files[pdf]" accept=".pdf"/>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="files[content]"><?php echo JTEXT::_('ARTICULUS.ISSUE.CONTENT');?></label>
		<?php echo $issue->content ?>
		<input class="form-control" type="file" name="files[content]" accept=".pdf"/>
	</div>	
    
	<div class=" form-group " id="usePdf">
        <label class="control-label"   for="usePdf"><?php echo JTEXT::_('ARTICULUS.ISSUE.USEPDF');?></label>
		<div class="radio">
			<input class="form-control" id="noUsePdf" type="radio" name=issue[usePdf] value="1">
            <label class="control-label"   for="noUsePdf"><?php echo JTEXT::_('ARTICULUS.GENERAL.NO');?></label>

			<input  class="form-control" type="radio" id="yesUsePdf" name=issue[usePdf] value="0">
            <label class="control-label"   for="yesUsePdf"><?php echo JTEXT::_('ARTICULUS.GENERAL.YES');?></label>
		</div>
        <br/>
	</div>	
    
	<div class=" form-group" id="useContent">
        <label class="control-label"   for="useContent"><?php echo JTEXT::_('ARTICULUS.ISSUE.USECONTENT');?></label>
		<div class="radio ">
            <input class="form-control" id = "yesUseContent" type="radio" name=issue[useContent] value="1">
            <label class="control-label"   for="yesUseContent"><?php echo JTEXT::_('ARTICULUS.GENERAL.YES');?></label>
		 <input class="form-control" type="radio" id="noUseContent" name=issue[useContent] value="0" checked>
            <label class="control-label"    for="noUseContent"><?php echo JTEXT::_('ARTICULUS.GENERAL.NO');?></label>
		</div>
        <br/>
	</div>
	<div class="form-group">
        <legend style="cursor:pointer" class="sub-params-label"><?php echo JTEXT::_('ARTICULUS.ISSUE.SUB.BLOCK')?></legend>
		<div class=" form-group ">
		<label class="control-label" for="issue[special]"><?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIAL');?></label>
		<div class="checkbox">
			<input class="form-control" type="checkbox" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIAL');?>"  name="issue[special]"/>
		</div>
            <br/>
            </div>	
	</div>
	<div class="form-group">
		<label class=" col-sm-2 control-label" for="issue[special_comment]"><?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIALCOMMENT');?></label>
		<input class="form-control" type="text" placeholder="<?php echo JTEXT::_('ARTICULUS.ISSUE.SPECIALCOMMENT');?>"  value="<?php echo $issue->specialComment ?>"  name="issue[special_comment]"/>
	</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value ="com_sjarchive"/>
	<input type="hidden" name="controller" value ="issue"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="cid[]" value="<?php echo $issue->ID ?>"/>
</form>
