<?php
defined('_JEXEC') or die('Restricted access');
$curr_year = NULL;
?>

						

<table class="table table-responsive-sm">
	<thead>
		<th class="title">
			<?php echo(JTEXT::_('ARTICULUS.ISSUES.TITLE'))?>
		</th>
		<th class="use-pdf"><div class="text-center"><?php echo(JTEXT::_('ARTICULUS.ISSUES.PDF'))?></div></th>
		<th class="content"><div class="text-center"><?php echo(JTEXT::_('ARTICULUS.ISSUES.CONTENT'))?></div></th>
	</thead>
	<tbody>
			<?php foreach ($this->issues as $no=>&$issue):?>	
			<?php $link = array('controller'=>'issue','task'=>'issue.download','year'=>$issue->year,'num'=>$issue->num);?>
			
			<?php if(!empty($issue->part) or !empty($issue->special)):?>
								<?php $link['part'] = $issue->part;?>
								<?php if(!empty($issue->special)):?>
									<?php $link['special'] = $issue->special;?>
								<?php endif;?>
							<?php endif;?>
			  
							<?php if ($curr_year <> $issue->year):?>
      				<?php $curr_year = $issue->year;?>
                    <tr>
                        <td colspan="4">
                          <h3 class="year text-center"><?php echo $issue->year;?></h3>
                        </td>
                    </tr>
      			<?php endif;?>
				<tr>				
					<td>
						<div class="title">
							  <?php if ($issue->articlesNum>0):?>
							<a href="<?php echo (JRoute::_(array('controller'=>'issue','task'=>'issue.display','year'=>$issue->year,'num'=>$issue->num)));?>">
							<?php endif;?>
                              <span>
								  <strong>
                                <?php echo (JTEXT::_('ARTICULUS.ISSUE.TITLE'))?>
                                <?php if(!empty($issue->volume)): ?>
                                    <?php echo (JTEXT::_('ARTICULUS.ISSUE.VOLUME'))?> 
									<?php echo $issue->volume ?>
								<?php endif;?>
                                <?php !empty($issue->issue) ?>
                                    <?php echo (JTEXT::_('ARTICULUS.ISSUE.NUM'))?> 
									<?php echo $issue->num ?>
								<?php if(!empty($issue->part)): ?>
                                    <?php echo (JTEXT::_('ARTICULUS.ISSUE.PART'))?> 
									<?php echo $issue->part ?>
									<?php endif;?>
	
								<?php if(!empty($issue->special)): ?>
                                    <?php echo (JTEXT::_('ARTICULUS.ISSUE.SPECIAL'))?> 
									<?php endif;?>
								</span>
							</a>
							<?php  if (!empty($issue->doi)): ?>
								<p><small> <?php echo  (JTEXT::_('ARTICULUS.ISSUE.DOI'))?> <a href="http://doi.org/<?php echo $issue->doi;?>"><?php echo $issue->doi;?></a></small></p>
							<?php endif;?>
						</div>
					</td>
						<td>
						<div class="text-center">
							<?php if(empty($issue->pdf)):?>
								<?php echo JTEXT::_('ARTICULUS.ISSUE.EMPTYFILE');?>
							<?php else: ?>
								<?php $link['ftype']='pdf';?>
							<a style="width: 25px;   margin: 20px; background: url('/media/static/icons/pdf.png') no-repeat; padding-left: 25px; background-size:contain;" href="<?php echo (JRoute::_($link ));?>">
								<?php echo (JTEXT::_('ARTICULUS.ISSUE.PDF'))?>
							</a>
							<?php endif;?>
						</div>
					</td>
					<td>
						<div class="text-center">
							<?php if(empty($issue->content)):?>
								<?php echo JTEXT::_('ARTICULUS.ISSUE.EMPTYFILE');?>
							<?php else:?>
							<?php $link['ftype']='content';?>
							<a style="width: 25px;   margin: 20px; background: url('/media/static/icons/pdf.png') no-repeat; padding-left: 25px; background-size:contain;" href="<?php echo (JRoute::_( $link));?>">
								<?php echo (JTEXT::_('ARTICULUS.ISSUE.CONTENT'))?>
							</a>
								<?php endif;?>
						</div>
					</td>
				
				</tr>
 
			<?php endforeach;?>
	</tbody>
</table>