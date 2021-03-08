<?php
defined('_JEXEC') or die('Restricted access');
$curr_year = NULL;
$params = JComponentHelper::getParams( 'com_sjarchive' );
$lang = JFactory::getLanguage();
if ($lang->getTag() == 'ru-RU') {
  $title = $params->get('title_ru');
} else {
  $title = $params->get('title_en');
}
?>

</style>
<form enctype="multipart/form-data" action="index.php?option=com_sjarchive" method="POST" name="adminForm" id="adminForm">
	<table class="table table-responsive">
		<thead>
			<th class="no">
				<?php echo(JTEXT::_('ARTICULUS.ISSUES.NO'))?>
			</th>
			<th class="checkbox">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->issues );?>);" />
			</th>
			<th class="id">
				<?php echo(JTEXT::_('ARTICULUS.ISSUES.ID'))?>
			</th>
			<th class="title">
				<?php echo(JTEXT::_('ARTICULUS.ISSUES.TITLE'))?>
			</th>
			<th class="published">
				<?php echo(JTEXT::_('ARTICULUS.ISSUES.PUBLISHED'))?>
			</th>
			<th class="hits">
				<?php echo(JTEXT::_('ARTICULUS.ISSUES.HITS'))?>
			</th>
			<th class="pdf"><?php echo(JTEXT::_('ARTICULUS.ISSUES.PDF'))?></th>
			<th class="use-pdf"><?php echo(JTEXT::_('ARTICULUS.ISSUES.USEPDF'))?></th>
			<th class="use-content"><?php echo(JTEXT::_('ARTICULUS.ISSUES.USECONTENT'))?></th>
			<th class="content"><?php echo(JTEXT::_('ARTICULUS.ISSUES.CONTENT'))?></th>
			<th class="pubdate"><?php echo(JTEXT::_('ARTICULUS.ISSUES.PUBDATE'))?></th>
			<th><?php echo(JTEXT::_('ARTICULUS.ISSUES.ARTICLESNUM'))?></th>
		</thead>
		<tbody>
				<?php foreach ($this->issues as $no=>&$issue):?>		
					<tr>
						<td>
							<div class="no">
								<?php echo $no+1?>
							</div>
						</td>				
						<td>
							<div class="checkbox">
								<?php echo(JHTML::_('grid.id', $no, $issue->ID));?>
							</div>
						</td>
						<td>
							<div class="id">
								<?php echo $issue->ID?>
							</div>
						</td>
						<td>
							<div class="title">
								<a href="<?php echo (JRoute::_("index.php?option=com_sjarchive&task=issue.display&cid={$issue->ID}"));?>">
									<?php echo $title?> <?php echo $issue->num;?>/<?php echo $issue->year;?>
								</a>
							</div>
						</td>
						<td>
							<div class="published">
								<?php echo(JHTML::_('grid.published',$issue, $no));?>
							</div>
						</td>
						<td>
							<div class="hits">
								<?php echo $issue->hits?>
							</div>
						</td>
						<td>
							<div class="pdf">
								<?php if(empty($issue->pdf)):?>
									<?php echo JTEXT::_('ARTICULUS.ISSUE.EMPTYFILE');?>
								<?php else: ?>
								<a href="<?php echo (JRoute::_("index.php?option=com_sjarchive&task=issue.download&cid={$issue->ID}&ftype=pdf"));?>">
									<?php echo (JTEXT::_('ARTICULUS.ISSUE.PDF'))?>
								</a>
								<?php endif;?>
							</div>
						</td>
						<td>
							<div class="use-pdf">
								<?php echo (($issue->usePdf?JTEXT::_('ARTICULUS.ISSUE.USEPDF.YES'):JTEXT::_('ARTICULUS.ISSUE.USEPDF.NO')));?>
							</div>
						</td>
						<td>
							<div class="content">
								<?php if(empty($issue->content)):?>
									<?php echo JTEXT::_('ARTICULUS.ISSUE.EMPTYFILE');?>
								<?php else:?>
								<a href="<?php echo (JRoute::_("index.php?option=com_sjarchive&task=issue.download&cid={$issue->ID}&ftype=content"));?>">
									<?php echo (JTEXT::_('ARTICULUS.ISSUE.CONTENT'))?>
								</a>
								<?php endif;?>
							</div>
						</td>
						<td><div class="use-content"><?php echo ($issue->useContent?JTEXT::_('ARTICULUS.ISSUE.USECONTENT.YES'):JTEXT::_('ARTICULUS.ISSUE.USECONTENT.NO'))?></div></td>
						<td><div class="pub-date"><?php echo $issue->pubDate?></div></td>
						<td><div class="pub-date"><?php //echo $issue->articlesNum?></div></td>
					</tr>
				<?php endforeach;?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="create" />
	<input type="hidden" name="option" value ="com_sjarchive" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
