<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
loader::model('monkeysee','mail_monkey');
$pkg = Package::getByHandle('mail_monkey');
$chimp_key = $pkg->config('CHIMP_KEY');
$api = new MCAPI($chimp_key);
$pkt = Loader::helper('concrete/urls');
$fm = Loader::helper('form');
$advice = $api->campaignAdvice($campaignId);
	
if($edit_group){
	$group_list = $api->listInterestGroupings($listId);
	foreach($group_list as $group){
		if ($group['name'] == $group_name){
			$edit_group = $group;
		}
	}
?>
<div style="width: 800px">
	<h1><span><?php  echo t('Modify Group')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
			<table id="monkey_sub_nav">
				<tr>
					<td>
					</td>
					<td>
				</tr>
			</table>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<div id="new_campaign">
			<form id='subscribe-form' action="<?php  echo $this->action('update_grouping',$listId)?>" method="post">
				<br/>
				<?php 
				if($edit_group['form_field']=='checkboxes' || $edit_group['form_field']=='hidden'){
				?>
				<?php echo $fm->label('type','Display these options?')?>
				<br/><br/>
				<select class="size1of2" name="type" id="new-grouping-type"> 
					<option value="checkboxes" <?php  if($edit_group['form_field']=='checkboxes'){echo 'selected';}?>>as checkboxes (people can select more than one)</option> 
					<option value="hidden" <?php  if($edit_group['form_field']=='hidden'){echo 'selected';}?>>don't show these groups on my signup form</option> 
				</select>
				<input type="hidden" name="groupingId" value="<?php echo $edit_group['id']?>"/>
				<br/><br/>
				<?php  
				}
				?>
				<br/>
				<?php echo $fm->label('title','Groupings Title')?>
				<br/><br/>
				<?php echo $fm->text('title',$edit_group['name'])?>
				<br/><br/>
				<br/>
				  <h2><?php  echo t('Add Group Types');?></h2>
			<p><a href="javascript:;" onclick="addGroupies();">Add Group Type [+]</a></p>
				<div id="group_options_wrap">
					<?php 
					foreach($edit_group['groups'] as $sub_group){
						$gn++;
						echo '<input type="hidden" name="old_option['.$gn.']" value="'.$sub_group['name'].'"/>';
					?>
					<div id="option_<?php echo $gn?>">
						<?php echo t('Group Type Name')?>
						<input type="text" name="option[<?php echo $gn?>]" value="<?php echo $sub_group['name']?>"/>
						<a href="javascript:;" onClick="removeNode('option_<?php echo $gn?>','group_options_wrap');">[X]</a>
						<br/>
						<br/>
					</div>
					<?php 
					}
					?>
					<input type="hidden" name="group_count" id="group_count" value="<?php  if(!$gn){echo '0';}else{echo $gn;}?>" />
				</div>
					<script type="text/javascript">/*<![CDATA[*/
						function addGroupies() {
						  var dt = document.getElementById('group_options_wrap');
						  //dt.innerHTML = "something";
						  var o_node = document.getElementById('group_count');
						  var node = ++document.getElementById('group_count').value;
						  o_node.value = node;
						  
						  var divIdName = "group_fields"+node;
						  var newLinkDiv = document.createElement('div');
						  newLinkDiv.setAttribute('id','option_'+node);
						  dt.appendChild(newLinkDiv);
						  
						  var element1l = document.createElement("label");
						  element1l.innerHTML = "Group Type Name ";
						  newLinkDiv.appendChild(element1l);
	

						  var element2 = document.createElement("input");
						  element2.type = "text";
						  element2.name = "option["+node+"]";
						  newLinkDiv.appendChild(element2);
						  

						  var element3 = document.createElement('a');
						  element3.setAttribute('href','javascript:;');
						  element3.setAttribute('onClick','removeNode(\'option_'+node+'\',\'group_options_wrap\');');
						  element3.innerHTML = " [X]";
						  newLinkDiv.appendChild(element3);
						  
						  var element4 = document.createElement("span");
						  element4.innerHTML = "<br/><br/>";
						  newLinkDiv.appendChild(element4);
						  
						}
						
						function removeNode(node,parent) {
						  var d = document.getElementById(parent);
						  var olddiv = document.getElementById(node);
						  d.removeChild(olddiv);
						}
					/*]]>*/</script>
				<br/><br/>
				<input type="submit" value="Update This Group" class="ui-state-default ui-corner-all custom_style big_button"/>
				<a href="<?php echo $this->url('dashboard/mail_monkey/groups/',$listId)?>" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Cancel')?></a>
				<br/><br/>
				<br/><br/>
			</form>
		</div>
	</div>
</div>
<?php 
}