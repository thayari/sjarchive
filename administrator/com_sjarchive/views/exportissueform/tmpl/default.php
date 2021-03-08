<?php
defined('_JEXEC') or die('Restricted access');
?>
<?php $issues = &$this->issues;?>
<form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
	<section class="issue-import">
	<select name="cid">
		<?php foreach ($issues as $issue) :?>
			<option value ="<?php echo $issue->ID;?>"><?php echo $issue->year?> <?php echo $issue->num?></option>
		<?php endforeach?>
	</select>
		<fieldset>
			<legend><?php echo JTEXT::_('ARTICULUS.ISSUE.EXPORT.BLOCK')?></legend>
			
			<label for="xmltype">Формат данных</label>
			<select name="xmltype">
                <option value ="doaj"><?php echo JText::_('ARTICULUS.ISSUE.EXPORT.DOAJ')?></option>
				<option value ="doi"><?php echo JText::_('ARTICULUS.ISSUE.EXPORT.CROSSREF')?></option>
				<option value="researchbib"><?php echo JText::_('ARTICULUS.ISSUE.EXPORT.RESEARCHBIB')?></option>
				<option value="articulus"><?php echo JText::_('ARTICULUS.ISSUE.EXPORT.ARTICULUS')?></option>
				<option value="jats"><?php echo JText::_('ARTICULUS.ISSUE.EXPORT.JATS')?></option>
				<option value="rss"><?php echo JText::_('ARTICULUS.ISSUE.EXPORT.RSS')?></option>
			</select>
		</fieldset>
	</section>

	<input type="hidden" name="task" value="issue.export" />
	<input type="hidden" name="option" value ="com_sjarchive" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
