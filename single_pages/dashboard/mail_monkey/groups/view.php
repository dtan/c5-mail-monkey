<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$api = new MCAPI($chimp_key);
$pkg = Package::getByHandle('mail_monkey');
$pkt = Loader::helper('concrete/urls');
$fm=Loader::helper('form');

if($pkg->config('CHIMP_KEY') != null){
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
}); 
/*]]>*/</script>
<div style="width: 800px">
	<h1><span><?php  echo t('Groups for '.$list_name)?></span></h1>
	<div class="ccm-dashboard-inner">	
		<div id="monkey_menu">
			<table id="monkey_sub_nav">
				<tr>
					<td>
					<?php 
					if($listId != null){
					?>
					<button class="ui-state-default ui-corner-all custom_style big_button" onClick="loadMyDialogDo('1');"  style="float: left;"><?php echo t('Add a Group')?></button>
					<?php 
					}
					?>
					</td>
				</tr>
			</table>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<br style="clear: right;"/>
		<br/><br/>
					<?php 
					//=================================//
					//=================================//
					// this is the start of the pop-up
					// send report email address form
					//=================================//
					//=================================//
					?>
					<script type="text/javascript">/*<![CDATA[*/
					loadMyDialogDo = function(i) {
			
						var el = document.createElement('div')
						el.id = "myNewElementmyDialogContent"+i;
						el.innerHTML = $('#send'+i).html();
						el.style.display = "none";
						$('#send'+i).parent().append(el);
							jQuery.fn.dialog.open({
								title: 'Please fill out your group info',
								element: '#myNewElementmyDialogContent'+i,
								width: 380,
								modal: false,
								height: 325
							});
					}
					
					function checkform ( form ){
						
						  if (form.title.value == "") {
						  	form.title.style.background = '#ffe8e5';
						    alert( "Hey!  We have to have a name!" );
						    form.title.focus();
						    return false ;
						  }else{
						  	form.title.style.background = 'none';
						  }
					}
					/*]]>*/</script>
					<div id="send1" style="display: none;">
						<form id="subscribe-form" action="<?php  echo $this->action('create_groupings',$listId)?>" method="post" onsubmit="return checkform(this);">
							<br/>
							<?php echo $fm->label('type','How do you want your options to display?')?>
							<br/><br/>
							<select class="size1of2" name="type" id="new-grouping-type"> 
								<option value="checkboxes">as checkboxes (people can select more than one)</option> 
								<option value="radio">as radio buttons (people can select just one)</option> 
								<option value="dropdown">as a select menu (people can select just one)</option> 
								<option value="hidden">don't show these groups on my signup form</option> 
							</select>
							<br/><br/>
							<br/>
							<?php echo $fm->label('title','Grouping Title')?>
							<br/><br/>
							<?php echo $fm->text('title')?>
							<br/><br/>
							<br/>
							  <h2><?php  echo t('Add Group Types');?></h2>
						<p><a href="javascript:;" onclick="addGroupies();">Add Group Type [+]</a></p>
							<div id="group_options_wrap">
								
								<input type="hidden" name="group_count" id="group_count" value="0" />
								<table id="group_fields">
								</table>
							</div>
								<script type="text/javascript">/*<![CDATA[*/
									function addGroupies() {
									  var dt = document.getElementById('group_options_wrap');
									  dt.innerHTML = " ";
									  var o_node = document.getElementById('group_count');
									  var node = ++document.getElementById('group_count').value;
									  o_node.value = node;
									  
									  var divIdName = "group_fields"+node;
									  
									  var table = document.getElementById('group_fields');	
									  var rowCount = table.rows.length;
									  var row = table.insertRow(rowCount);
									  
									  var cell1 = row.insertCell(0);
									  var element1l = document.createElement("label");
									  element1l.innerHTML = "Group Type Name";
									  cell1.appendChild(element1l);

									  
									  var cell2 = row.insertCell(1);
									  var element2 = document.createElement("input");
									  element2.type = "text";
									  element2.name = "option["+node+"]";
									  cell2.appendChild(element2);
									  
									  var cell3 = row.insertCell(2);
									  var element3 = document.createElement('a');
									  element3.setAttribute('href','javascript:;');
						  			  element3.setAttribute('onClick','removeElement(\'this\');');
						  			  element3.innerHTML = " [X]";
									  cell3.appendChild(element3);
									  
									}
									
									function removeElement(node) {
									  var table = document.getElementById('group_fields');
									  table.deleteRow(node);
									}
								/*]]>*/</script>
							<br/><br/>
							<?php echo $fm->submit('submit','Create This Group')?>
						</form>
					</div>
					<?php 
					//=================================//
					//=================================//
					// this is the end of the pop-up form
					//=================================//
					//=================================//
					?>
	
	<?php 
	if (!isset($listId)){
		$lists = $api->lists();
		//var_dump($lists);
		$lists = $lists['data'];
	?>
	You must fist choose a list
		<script type="text/javascript">/*<![CDATA[*/
			function changePage(newLoc)
				 {
				   nextPage = newLoc.options[newLoc.selectedIndex].value
						
				   if (nextPage != "")
				   {
				      document.location.href = nextPage
				   }
				 }
	/*]]>*/</script>
	<form id='campaign_form' action="<?php  echo $this->action('campaign_build')?>" method="post">
				<table cellpadding="6">
					<tr>
						<td class="form_label">
						<?php echo $fm->label('listId','Select A List')?>
						</td>
						<td>
						<form>
							<select name="listId" onChange="changePage(this.form.listId)">
						<?php 
							echo '<option value="none">none</option>';
							foreach($lists as $list){
								echo '<option value="'.$this->action('view',$list['id']).'">'.$list['name'].'</option>';
							}
						?>
							</select>
						</form>
						</td>
					</tr>
				</table>
	</form>
	<?php 	
	}else{
	?>
		<table border="0" class="display" cellspacing="0" cellpadding="0" id="orders" width="100%">
			<thead> 
				<tr>
					<th width="65"></th>
					<th>Name</th>
					<th>Type</th>
					<th>Sub Groups</th>
				</tr>
			</thead> 
			<tbody> 
	<?php 
		$group_list = $api->listInterestGroupings($listId);
		//$group_list = $group_list['data'];
		//var_dump($group_list);
		//exit;
		if(!is_array($group_list)){$group_list = array();}
		foreach($group_list as $group){
		?>
			<tr>
				<td>
					<?php 
						//var_dump($group);
						//exit;	
					?>
					<a href="<?php  echo $this->url('dashboard/mail_monkey/groups/edit/edit_grouping/'.$listId.'/'.$group['name'])?>" name="delete this" class="tooltip"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/edit.png';?>" width="12px" /><span>Edit This Group.</span></a>

					<a href="javascript:;" name="delete this" class="tooltip" onClick="deleteDialogDo(1);"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/delete.png';?>" width="12px" /><span>Remove This Group</span></a>
					
								<div id="deletecheck1" style="display: none;">
									<form id='subscribe-form' action="<?php  echo $this->action('group_delete',$listId, $group['id'])?>" method="post">
										<br/>
										<font style="font-size: 18px; font-style: bold; color: red;">
										<?php echo t('::::WARNING::::')?>
										</font>
										<?php echo t('<br/><br/> You are about to remove "'.$group['name'].'". <br/><br/> This action may not be undone. <br/> <br/> Are you sure you want to continue?')?>
										<br/><br/>
										<?php echo $fm->submit('submit','Yes, DELETE this')?>
									</form>
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
				</td>
				<td><?php echo $group['name']?></td>
				<td><?php echo $group['form_field']?></td>
				<td>
					<?php 
					$gc = count($group['groups']);
					foreach($group['groups'] as $sub_group){
						$ig++;
						echo $sub_group['name'];
						if($ig != $gc){
							echo ', ';
						}
					}
					?>
				</td>
			</tr>
		<?php 
		}
	?>
			</tbody>
		</table>
	<?php 
	}
	?>
	</div>
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