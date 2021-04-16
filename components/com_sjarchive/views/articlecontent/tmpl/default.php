<?php
defined('_JEXEC') or die('Restricted access');
?>

<form enctype="multipart/form-data" action="index.php?option=com_articulus" method="POST" name="adminForm" id="adminForm">
	
<?php echo JTEXT::_('ARTICULUS.ARTICLE.EMPTY');?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value ="com_articulus" />
	<input type="hidden" name="controller" value ="article" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
