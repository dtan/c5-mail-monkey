<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
loader::model('monkeysee','mail_monkey');
$pkg = Package::getByHandle('mail_monkey');
$chimp_key = $pkg->config('CHIMP_KEY');
$api = new MCAPI($chimp_key);
$pkt = Loader::helper('concrete/urls');
$fm = Loader::helper('form');
$advice = $api->campaignAdvice($campaignId);
	

$group_list = $api->listInterestGroupings($listId);
$memvals = $api->listMemberInfo( $listId, $email);
$memvals = $memvals['data'][0];
$interest = array();
if($memvals['merges']['GROUPINGS']){
	foreach ($memvals['merges']['GROUPINGS'] as $grouping){
		$sup_groups = explode(', ', $grouping['groups']);
		foreach ($sup_groups as $group){
			array_push($interest , $group);
		}
	}
}

?>
<div style="width: 800px">
	<h1><span><?php  echo t('Modify User')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
			<table id="monkey_sub_nav">
				<tr>
					<td>
					</td>
				</tr>
			</table>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<div id="new_campaign">
			<form id='subscribe-form' action="<?php  echo $this->action('update_member',$listId,$list_name)?>" method="post">
			<?php 
					//var_dump($memvals['merges']);
			?>
			
			<table cellpadding="6">
				<tr>
					<td>
						<?php echo $fm->label('fname', 'First Name')?>
					</td>
					<td>
						<?php echo $fm->text('fname',$memvals['merges']['FNAME'])?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $fm->label('lname', 'Last Name')?>
					</td>
					<td>
						<?php echo $fm->text('lname',$memvals['merges']['LNAME'])?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $fm->label('email', 'Email Address')?>
					</td>
					<td>
						<?php echo $fm->hidden('old_email',$memvals['merges']['EMAIL'])?>
						<?php echo $fm->text('email',$memvals['merges']['EMAIL'])?>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
					<?php 
					if($group_list){
						foreach($group_list as $grouping){
						?>
							<h2><?php echo $grouping['name']?></h2>
								<div id="group<?php echo $grouping['id']?>"  class="interests">
								<table>
									<tr>
										<?php 
										$gt = 0;
										$gti = 0;
										echo '<input type="hidden" name="groups[]" value="'.$grouping['id'].'"/>';
										foreach ($grouping['groups'] as $group){
											$gt++;
											$gti++;
											echo '<td>';
											echo '<input type="checkbox" name="interest['.$grouping['id'].']['.$gti.']" value="'.$group['name'].'"';
											if(in_array($group['name'], $interest)){
												echo 'checked';
											}
											echo '>';
											echo $group['name'];
											echo '</td>';
											if($gt==4){
											echo '</tr>';
											$gt=0;
											}
										}
										if($gt<4){
											$gt++;
											echo '<td></td>';
											if($gt==4){
											echo '</tr>';
											}
										}
										?>
									</tr>
								</table>
								</div>
						<?php 
						}
					}
					?>
					</td>
				</tr>
			</table>
			<br/><br/>
							<input type="submit" value="Update This User" class="ui-state-default ui-corner-all custom_style big_button"/>
							<a href="<?php  echo $this->url('dashboard/mail_monkey/lists/view_list/'.$listId.'/'.$list_name.'/')?>" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Cancel')?></a>
			</form>
			<div style="clear:both;"></div>
		</div>
	</div>
</div>