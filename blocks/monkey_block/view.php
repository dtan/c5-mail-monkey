<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
?>
<div id="monkey_mail_signup">
<?php  $fm = Loader::helper('form');?>
	<h1><?php echo t('Mailing List')?></h1>
	<?php 
	if(!$thanks){
		loader::model('monkeysee','mail_monkey');
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($chimp_key);
		$pkt = Loader::helper('concrete/urls');
		$fm = Loader::helper('form');
		$advice = $api->campaignAdvice($campaignId);
			
		$group_list = $api->listInterestGroupings($list);
	?>
	<form action="<?php echo $this->action('signmeup')?>" method="post">
		<?php echo t('First Name')?><br/>
		<?php echo $fm->text('first_name',array('size'=>'30'))?><br/><br/>
		
		<?php echo t('Last Name')?><br/>
		<?php echo $fm->text('last_name',array('size'=>'30'))?><br/><br/>
		
		<?php echo t('Email')?><br/>
		<?php echo $fm->text('email',array('size'=>'30'))?><br/><br/>
		
		<?php echo $fm->hidden('id',$list)?>
		
		<?php 
		if($show_groups==1 && is_array($group_list)){
		?>
			<?php 
			foreach($group_list as $grouping){
			?>
				<b><?php echo $grouping['name']?></b>
					<div id="group<?php echo $grouping['id']?>"  class="interests">
					<table>
						<tr>
							<?php echo '<input type="hidden" value="'.$grouping['id'].'" name="group_ids[]" />'?>
							<?php 
							$gt = 0;
							foreach ($grouping['groups'] as $group){
								$gt++;
								$gti++;
								echo '<td>';
								echo '<input type="checkbox" name="interest['.$grouping['id'].'][]" value="'.$group['name'].'">';
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
					<br/><br/>
			<?php 
				}
			}
			?>
		
		<?php echo $fm->submit('submit','yes, sign me up')?>
	</form>
	<?php 
	}else{
		if($thanks == 1){
			echo 'Thanks for signing up!';
		}else{
			echo $thanks;
		}
	}
	?>
</div>