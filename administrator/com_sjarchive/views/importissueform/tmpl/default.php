<?php
defined('_JEXEC') or die('Restricted access');
?>

<form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
	<section class="issue-import">
		<fieldset>
			
			<legend><?php echo JTEXT::_('ARTICULUS.ISSUE.IMPORT.BLOCK')?></legend>
			<label for="xml">Имя файла</label>
			<input type="file"  name="xml"/>
			
			<label for="xmltype">Формат данных</label>
			<select name="xmltype">
				<option value="neu">Real estate Native Layout</option>
        <option value="elibrary">elibrary.ru HTML</option>
				<option value="layout">VestnikMGSU Native Layout</option>
				<option value="ojs">OJS Native XML</option>
				<option value="sarticle">Sarticle</option>
				<option value="articulus">Articulus</option>
				<option value="jats">JATS Xml</option>
			</select>
		</fieldset>
	</section>

	<input type="hidden" name="task" value="issue.import" />
	<input type="hidden" name="option" value ="com_sjarchive" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
