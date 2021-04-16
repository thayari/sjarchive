<?php
defined('_JEXEC') or die('Restricted access');
?>

<form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
	<table class="table table-responsive">
		<thead>
			<th><?php echo('#CHECK')?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.NO'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.ID'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.TITLE'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.PUBLISHED'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.HITS'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.PDF'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.USEPDF'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.USECONTENT'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.CONTENT'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.PUBDATE'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.ARTICLESNUM'))?></th>
		</thead>
		<tbody>
			<tr>
				<td colspan=11><?php echo(JTEXT::_('ARTICULUS.ISSUES.EMPTY'));?></td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="task" value="create" />
	<input type="hidden" name="option" value ="com_sjarchive" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
