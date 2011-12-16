<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
/**
This Example shows how to pull the Members of a List using the MCAPI.php 
class and do some basic error checking.
**/

$api = new MCAPI($chimp_key);
$pkg = Package::getByHandle('mail_monkey');
$pkt = Loader::helper('concrete/urls');
?>

<script type="text/javascript">/*<![CDATA[*/
$(document).ready(function() 
    { 
    	$('#orders').dataTable({"bJQueryUI": true,"sPaginationType": "full_numbers"});
    	
		$( ".custom_style" ).hover(	
			function(){ 
				$(this).addClass("ui-state-hover"); 
			},
			function(){ 
				$(this).removeClass("ui-state-hover"); 
			}
		);
    } 
); 
/*]]>*/</script>
<div style="width: 800px">
<?php 
if (isset($view_list)){
?>
	<h1><span><?php  echo t('User List for '.$list_name)?></span></h1>
	<div class="ccm-dashboard-inner">
		<div style="float: left">
			<form id='subscribe-form' action="<?php  echo $this->action('subscribe',$listId)?>" method="post">
			<table id="monkey_sub_nav">
				<tr>
					<td>
						<br/>
						<a href="<?php echo $this->action('add_user', $listId)?>" class="ui-state-default ui-corner-all custom_style big_button">Add Users</a>
					</td>
					<td>
						<br/>
						<a href="<?php echo $this->url('dashboard/mail_monkey/groups/'.$listId.'/')?>" class="ui-state-default ui-corner-all custom_style big_button">Edit Groups</a>
					</td>
				</tr>
			</table>
			</form>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<br/>
		<br/>
		<table border="0" class="display" cellspacing="0" cellpadding="0" id="orders" width="100%">
			<thead> 
				<tr>
					<th width="65"></th>
					<th><?php echo t('Name')?></th>
					<th width="35"><?php echo t('Email')?></th>
					<th><?php echo t('Since')?></th>
					<th><?php echo t('Interests')?></th>
				</tr>
			</thead> 
			<tbody> 
<?php 
	$retval = $api->listMembers($listId, 'subscribed', null, 0, 5000 );
	$retval = $retval['data'];

	if ($api->errorCode){
		echo "Unable to load listMembers()!";
		echo "\n\tCode=".$api->errorCode;
		echo "\n\tMsg=".$api->errorMessage."\n";
		echo "Members returned: ". sizeof($retval). "\n";
	} else {
		echo "Members returned: ". sizeof($retval). "\n";
		foreach($retval as $member){
		  $dd += 1;
		  //echo $member['name']." - ".$member['email']." - ".$member['timestamp']."\n";
		  $memval = $api->listMemberInfo( $listId, $member['email'] );
		  $memval = $memval['data'][0];
		  //var_dump($memval);

		 $fm = Loader::helper('form');
?>
		<tr>
			<td>
				<a href="<?php  echo $this->url('dashboard/mail_monkey/lists/edit/'.$member['email'].'/'.$listId.'/'.$list_name.'/')?>" name="edit this" class="tooltip"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/edit.png';?>" width="12px" /><span>Edit This Member.</span></a>
				<a href="javascript:;" name="delete this" class="tooltip" onClick="deleteDialogDo(<?php echo $dd?>);"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/delete.png';?>" width="12px" /><span>Remove this user from this list</span></a>
				
					<div id="deletecheck<?php echo $dd?>" style="display: none;">
						<form id='subscribe-form' action="<?php  echo $this->url('/dashboard/mail_monkey/lists/unsubscribe', $listId, $memval['email']);?>" method="post">
							<br/>
							<font style="font-size: 18px; font-style: bold; color: red;">
							<?php echo t('::::WARNING::::')?>
							</font>
							<?php echo t('<br/><br/> You are about to remove "'.$memval['merges']['FNAME'].' '.$memval['merges']['LNAME'].'". <br/><br/> This action may not be undone. <br/> <br/> Are you sure you want to continue?')?>
							<br/><br/>
							<?php echo $fm->submit('submit','Yes, DELETE this')?>
						</form>
					</div>
			</td>
			<td><?php echo $memval['merges']['FNAME']?> <?php echo $memval['merges']['LNAME']?></td>
			<td><?php echo $memval['email']?></td>
			<td><?php echo $memval['timestamp']?></td>
			<td>
			<?php 
			if(is_array($memval['merges']['GROUPINGS'])){
				foreach($memval['merges']['GROUPINGS'] as $grouping){
					echo $grouping['groups'];
				}
			}
			?>
			</td>
		</tr>
<?php 
		}
	}
?>
			</tbody> 
		</table>
	</div>
	<script type="text/javascript">/*<![CDATA[*/
	deleteDialogDo = function(i) {

		var el = document.createElement('div')
		el.id = "deletecheckDialogContent"+i
		el.innerHTML = $('#deletecheck'+i).html();
		el.style.display = "none"
		$('#deletecheck'+i).parent().append(el);
			jQuery.fn.dialog.open({
				title: 'Please Confirm',
				element: '#deletecheckDialogContent'+i,
				width: 300,
				modal: false,
				height: 120
			});
	}
	/*]]>*/</script>
<?php 

}elseif(isset($adduser)){
?>
	<h1><span><?php  echo t('Add New Users')?></span></h1>
	<div class="ccm-dashboard-inner">
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<div id="new_campaign">
			<form id='campaign_form' action="<?php  echo $this->action('subscribe',$listId)?>" method="post">

			<?php 
			$fm = Loader::helper('form');
			$up = Loader::helper('form/user_selector');
			echo '<h2>'.$fm->label('uID','Choose Users').'</h2>';
			echo $up->selectMultipleUsers('uID');
			?>
			<br/><br/>
			<?php 
			$group_list = $api->listInterestGroupings($listId);
			if(is_array($group_list)){
				foreach($group_list as $grouping){
				?>
					<h2><?php echo $grouping['name']?></h2>
						<div id="group<?php echo $grouping['id']?>"  class="interests">
						<table>
							<tr>
								<?php 
								$gt = 0;
								foreach ($grouping['groups'] as $group){
									$gt++;
									$gti++;
									echo '<td>';
									echo '<input type="checkbox" name="interest['.$gti.']" value="'.$group['name'].'">';
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
			<br/><br/>
			<input type="submit" value="Add Users Now" class="ui-state-default ui-corner-all custom_style big_button"/>
			<br style="clear: both;"/>
			</form>
		</div>
	</div>
<?php 

}elseif($pkg->config('CHIMP_KEY') != null){
	$retval = $api->lists();
	$retval = $retval['data'];
?>
	<h1><span><?php  echo t('Mail Chimp Lists')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
			<i>To add new lists, log into your MailChimp account and select "create new list"</i>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" style="float: right; padding-right: 15px;"/>
		<br style="clear: right;"/>
		<br/>
		<table border="0" class="display" cellspacing="0" cellpadding="0" id="orders" width="100%">
			<thead> 
				<tr>
					<th width="65"></th>
					<th width="35">ID</th>
					<th>Name</th>
					<th>Subscribed</th>
					<th>UnSubscribed</th>
					<th>Cleaned</th>
				</tr>
			</thead> 
			<tbody> 
<?php 
if ($api->errorCode){
	echo "Unable to load lists()!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	foreach ($retval as $list){
	//	echo "Id = ".$list['id']." - ".$list['name']." - ".$list['web_id']."\n";
	//	echo "\tSub = ".$list['member_count']."\tUnsub=".$list['unsubscribe_count']."\tCleaned=".$list['cleaned_count']."\n";
?>
	<tr>
		<td>
		<?php 
		//var_dump($list);
		//exit
		?>
		</td>
		<td><?php echo $list['id']?></td>
		<td><a href="<?php echo $this->url('dashboard/mail_monkey/lists/view_list/'.$list['id'].'/'.$list['name'])?>"><?php echo $list['name']?></a></td>
		<td><?php echo $list['stats']['member_count']?></td>
		<td><?php echo $list['stats']['unsubscribe_count']?></td>
		<td><?php echo $list['stats']['cleaned_count']?></td>
	</tr>
<?php 
	}
}		
?>	
			</tbody> 
		</table>
	</div>
<?php 
}else{
?>
<div id="monkey_dashboard">
	<div id="monkey_header"></div>
	<a href="http://eepurl.com/bAs89" class="monkey_sign" target="_blank"></a>
	<a href="https://us2.admin.mailchimp.com/account/api/" class="monkey_log" target="_blank"></a>
</div>
<?php 
}
?>
</div>