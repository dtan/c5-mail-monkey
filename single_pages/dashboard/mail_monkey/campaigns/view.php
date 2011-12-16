<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$api = new MCAPI($chimp_key);
$pkg = Package::getByHandle('mail_monkey');
$pkt = Loader::helper('concrete/urls');
$fm = Loader::helper('form');
$advice = $api->campaignAdvice($campaignId);
?>

<script type="text/javascript">/*<![CDATA[*/
$(document).ready(function() {
	$( ".custom_style" ).hover(	
			function(){ 
				$(this).addClass("ui-state-hover"); 
			},
			function(){ 
				$(this).removeClass("ui-state-hover"); 
			}
	);
} );
/*]]>*/</script>
<div>
<?php 
//===============================================================//
//===============================================================//
//
//review of the current campaign. stats...ect.  will develope more
//
//===============================================================//
//===============================================================//

if ($campaign_overview){
$retval = $api->campaignStats($campaign['id']);
?>
<script type="text/javascript">/*<![CDATA[*/
$(document).ready(function() {
	$('#orders').dataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	});
	$('#info').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
	$('#info2').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
	$('#info3').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
	$('#info4').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
});
/*]]>*/</script>
	<h1><span><?php  echo t('Campaign List')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
			<table id="monkey_sub_nav">
				<tr>
					<td>
					<?php 
					if($campaign['status'] != 'sent'){
					?>
							<a href="<?php echo $this->action('campaign_sendnow',$campaign['id'])?>" class="ui-state-default ui-corner-all custom_style"><?php echo t('Send Now')?></a>
							</td>
							<td>
							<?php 
							if($campaign['status'] == 'schedule'){
							?>
							<a href="<?php echo $this->action('unschedule_campaign',$campaign['id'])?>" class="ui-state-default ui-corner-all custom_style"><?php echo t('Unschedule This')?></a>
							<?php 
							}else{
							?>
							<a href="<?php echo $this->action('schedule_form',$campaign['id'])?>" class="ui-state-default ui-corner-all custom_style"><?php echo t('Schedule to send')?></a>
							<?php 
							}
							?>
							</td>
							<?php 
							if($campaign['status'] == 'paused' && ($campaign['type'] == 'auto' || $campaign['type'] == 'rss')){
							?>
							<td>
							<a href="<?php echo $this->action('resume_campaign',$campaign['id'])?>" class="ui-state-default ui-corner-all custom_style"><?php echo t('Resume This')?></a>
							</td>
							<?php 
							}
							?>
							<?php 
							if($campaign['status'] == 'schedule'  && ($campaign['type'] == 'auto' || $campaign['type'] == 'rss')){
							?>
							<td>
							<a href="<?php echo $this->action('pause_campaign',$campaign['id'])?>" class="ui-state-default ui-corner-all custom_style"><?php echo t('Pause This')?></a>
							</td>
							<?php 
							}
							?>
							<td>
							<a href="<?php echo $this->url('dashboard/mail_monkey/campaigns/edit/editing_campaign/'.$campaign['id'].'/')?>" class="ui-state-default ui-corner-all custom_style"><?php echo t('Edit This')?></a>
							</td>
					<?php 
					}
					?>
					<td>
						<a href="<?php echo $this->action('copy_campaign',$campaign['id'])?>" class="ui-state-default ui-corner-all custom_style"><?php echo t('Duplicate This')?></a>
					</td>
					<?php 
					if($campaign['status'] == 'sent'){
					?>
					<td>
					<a href="javascript:;" class="ui-state-default ui-corner-all custom_style" onClick="loadMyDialogDo('1');"><?php echo t('Share Report')?></a>
					</td>
					<?php 
					}
					?>
				</tr>
			</table>
		</div>
					<?php 
					//=================================//
					//=================================//
					// this is the start of the pop-up
					// send report email address form
					//=================================//
					//=================================//
					if($campaign['status'] == 'sent'){
					?>
					<script type="text/javascript">
					loadMyDialogDo = function(i) {
			
						var el = document.createElement('div')
						el.id = "myNewElementmyDialogContent"+i
						el.innerHTML = $('#send'+i).html();
						el.style.display = "none"
						$('#send'+i).parent().append(el);
							jQuery.fn.dialog.open({
								title: 'Please Enter an Email',
								element: '#myNewElementmyDialogContent'+i,
								width: 320,
								modal: false,
								height: 85
							});
					}
					</script>
					<div id="send1" style="display: none;">
						<form id='subscribe-form' action="<?php  echo $this->action('share',$campaign['id'])?>" method="post">
							<br/>
							<?php echo $fm->label('email','email address')?>
							<br/><br/>
							<?php echo $fm->text('email')?>
							<br/><br/>
							<?php echo $fm->submit('submit','Share Report')?>
						</form>
					</div>
					<?php 
					}
					//=================================//
					//=================================//
					// this is the end of the pop-up form
					//=================================//
					//=================================//
					?>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br/>
		<br style="clear: right;"/>
		<br style="clear: right;"/>
<h2><?php echo t('Campaign Statistics')?></h2>
<?php 

if ($api->errorCode){
	echo "<div class=\"monkey_junk\">";
	echo $api->errorMessage."\n";
	echo "</div>";
} else {
//=================================//
//=================================//
//
//vars available for statistics
//
//syntax_errors
//hard_bounces
//soft_bounces
//unsubscribes
//abuse_reports
//forwards
//forwards_opens
//opens
//last_open
//unique_opens
//clicks
//unique_clicks
//users_who_clicked
//last_click	
//emails_sent
//=================================//
//=================================//
?>
		<div id="statnums" class="stat_box">
			<div class="complaints">
				<?php echo $retval['abuse_reports']?> <?php echo t('Complaints')?>
			</div>
			<table cellpadding="4" width="100%">
			<thead> 
				<tr>
					<td><?php echo t('Item')?></td>
					<td  align="right" width="55px"><?php echo t('Stat')?></td>
				</tr>
			</thead> 
			<tbody> 
				<tr>
					<td><?php echo t('Recipients')?></td><td align="right"><?php echo $retval['emails_sent']?></td>
				</tr>
				<tr>
					<td><?php echo t('Unique Opens')?></td><td align="right"><?php echo $retval['unique_opens']?></td>
				</tr>
				<tr>
					<td><?php echo t('Forwards')?></td><td align="right"><?php echo $retval['forwards']?></td>
				</tr>
				<tr>
					<td><?php echo t('Cicked')?></td><td align="right"><?php echo $retval['unique_clicks']?></td>
				</tr>
				<tr>
					<td><?php echo t('Unsubscribed')?></td><td align="right"><?php echo $retval['unsubscribes']?></td>
				</tr>
				<tr>
					<td><?php echo t('Bounces')?></td><td align="right"><?php echo $retval['hard_bounces'] + $retval['soft_bounces']?></td>
				</tr>
			</tbody>
			</table>
		</div>
  
<script type="text/javascript" class="code">/*<![CDATA[*/

$(document).ready(function(){    
    plot3 = $.jqplot('pie3', [[['Opened',<?php echo $retval['unique_opens']?>],['UnOpened',<?php echo $retval['emails_sent']-$retval['unique_opens']?>],['Unsubscribed',<?php echo $retval['unsubscribes']?>],['Hard Bounces',<?php echo $retval['hard_bounces']?>],['Soft Bounces', <?php echo $retval['soft_bounces']?>]]], {
      	seriesDefaults:{
      		renderer:$.jqplot.PieRenderer, 
      		rendererOptions:{
      			sliceMargin: 4, 
      			startAngle: -90,
      			dataLabels: 'percent',
      			showDataLabels: true
      		}, 
      		trendline:{
      			show:true
      		}
      	},
        legend:{show:true}      
    });
});
/*]]>*/</script>



    <div id="pie3" class="pie" ></div>


<?php 
}

echo '<br/><br/>';
$track_monkey = $pkg->config('TRACK_MONKEY');
			if($track_monkey == '1'){
?>
						<h2><?php echo t('Campaign Analytics')?></h2>
						<?php 
						
						$stats = $api->campaignAnalytics($campaign['id']);
						
						if ($api->errorCode){
							echo "<div class=\"monkey_junk\">";
							echo $api->errorMessage."\n";
							echo "</div>";
						} else {
						    echo "Visits: ".$stat['visits']."\n";
						    echo "Pages: ".$rpt['pages']."\n";
						    echo "Goals ".$rpt['type']."\n";
						    if ($stat['goals']){
						        foreach($stat['goals'] as $goal){
						            echo "\t".$goal['name']." => ".$goal['conversions']."\n";
						        }
						    }
						}
				}
						?>
			</tbody> 
		</table>
<?php 
echo '<br/><br/>';
?>
<br style="clear: both;"/>
<h2><?php echo t('Campaign Clicks')?></h2>
		<table border="0" class="display" cellspacing="0" cellpadding="0" id="info3" width="100%">
			<thead> 
				<tr>
					<th><?php echo t('url')?></th>
					<th><?php echo t('Clicks')?></th>
					<th><?php echo t('Unique Clicks')?></th>
				</tr>
			</thead> 
			<tbody> 
<?php 
$stats = $api->campaignClickStats($campaign['id']);

    if (sizeof($stats)==0){
        echo "No stats for this campaign yet!\n";
    } else {
    	if(is_array($stats)){
		    foreach($stats as $url=>$detail){
		    	echo "<tr>";
			    echo '<td><a href="'.$url.'">'.$url.'</a></td>';
			    echo "<td>".$detail['clicks']."</td>";
			    echo "<td>".$detail['unique']."</td>";
			    echo "</tr>";
		    }
		 }
    }
?>
			</tbody> 
		</table>
	</div>	
	
<?php 
//===============================================================//
//===============================================================//
//
//form for creating a new campaign
//
//===============================================================//
//===============================================================//

}elseif($create){
?>
	<h1><span><?php  echo t('Create A New Campaign')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
			<form id='subscribe-form' action="<?php  echo $this->action('subscribe',$listId)?>" method="post">
			<table id="monkey_sub_nav">
				<tr>
					<td>
					</td>
					<td>
				</tr>
			</table>
			</form>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<div id="new_campaign">
			<script type="text/javascript">/*<![CDATA[*/
			function changePage(newLoc)
				 {
				   nextPage = newLoc.options[newLoc.selectedIndex].value
						
				   if (nextPage != "")
				   {
				      document.location.href = nextPage
				   }
				 }
				 
			 function checkform ( form )
				{
				
				  if (form.subject.value == "") {
				  	form.subject.style.background = '#ffe8e5';
				    alert( "Hey!  We need a subject!" );
				    form.subject.focus();
				    return false ;
				  }else{
				  	form.subject.style.background = 'none';
				  }
				  
				  
				  if (form.fromname.value == "") {
				  	form.fromname.style.background = '#ffe8e5';
				    alert( "Hey!  We need to know who it's from!" );
				    form.fromname.focus();
				    return false ;
				  }else{
				  	form.fromname.style.background = 'none';
				  }
				
				  var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				  if (reg.test(form.frommail.value) == false) {
				  	form.frommail.style.background = '#ffe8e5';
				    alert( "There is something wrong with your email address. Please make sure that it is typed correctly." );
				    form.frommail.focus();
				    return false ;
				  }else{
				  	form.frommail.style.background = 'none';
				  }
				  // ** END **
				  return true ;
				}
			/*]]>*/</script>
			
			<form id='campaign_form' action="<?php  echo $this->action('campaign_build')?>" method="post" onsubmit="return checkform(this);">
				<table cellpadding="6">
					<tbody>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('listId','Select A List')?>
						</td>
						<td>
						<?php 
							$lists = $api->lists();
							$lists = $lists['data'];
							$listId = $_GET['listId'];
						?>
							<select name="listId_select" onChange="changePage(this.form.listId_select)">
						<?php 
							echo '<option value="'.$this->action('create_campaign').'">no list</option>';
							foreach($lists as $list){
								echo '<option value="'.$this->action('create_campaign').'?listId='.$list['id'];
								if($_GET['templateId']){
									echo '&templateId='.$_GET['templateId'];
								}
								echo '"';
								
								if($_GET['listId']==$list['id']){
									echo 'selected';
								}
								
								echo '>'.$list['name'].'</option>';
							}
						?>
							</select>
							<input type="hidden" name="listId" value="<?php echo $listId?>" />
						</td>
					</tr>
					<?php 
					if ($listId && $listId!='none'){
					?>
					<tr>
						<td class="form_label"><?php echo $fm->label('template','Template')?></td>
						<td>
						<?php 
						$apikey = $pkg->config('CHIMP_KEY');
						loader::model('monkeysee','mail_monkey');
						$api = new MCAPI($apikey);
						$templates = $api->templates();
						
						if($_GET['templateId']){
							$templateId = $_GET['templateId'];
						}else{
							$templateId = 'none';
						}
						echo '<select name="templateId" onChange="changePage(this.form.templateId)">';
						echo '<option value="'.$this->action('create_campaign').'?listId='.$listId.'&templateId=none">no template</option>';
						foreach($templates['user'] as $template){
							$i++;
							echo '<option value="'.$this->action('create_campaign').'?templateId='.$template['id'];
							
							if($_GET['listId']){
								echo '&listId='.$_GET['listId'];
							}
							echo '"';
							
							if($_GET['templateId']==$template['id']){
								echo 'selected';
							}
							
							echo '>'.$template['name'].'</option>';
							
							$previews[$i]= array('preview'=>$template['preview_image'],'name'=>$template['name']);
						}
						echo '</select>';
						?>
							<input type="hidden" name="template" value="<?php echo $templateId?>" />
						<a href="javascript:;" onClick="loadMyDialogDo('2');"><?php echo t('See Previews')?></a>
						<script type="text/javascript">/*<![CDATA[*/
						loadMyDialogDo = function(i) {
				
							var el = document.createElement('div')
							el.id = "myNewElementmyDialogContent"+i
							el.innerHTML = $('#preview'+i).html();
							el.style.display = "none"
							$('#preview'+i).parent().append(el);
								jQuery.fn.dialog.open({
									title: 'Available Template Previews',
									element: '#myNewElementmyDialogContent'+i,
									width: 450,
									modal: false,
									height: 300
								});
						}
						/*]]>*/</script>
						<div id="preview2" style="display: none;">
							<?php 
								foreach ($previews as $preview){
									if($preview['preview']){
										echo '<div style="float: left; margin: 8px;">';
										echo '<img src="'.$preview['preview'].'" alt="preview"/><br/>';
										echo $preview['name'];
										echo '</div>';
									}
								}
							?>
						</div>
						</td>
					</tr>
					<?php  
					if($listId){
					?>
					<tr>
						<td class="form_label"><?php echo $fm->label('options','Group Segments')?></td>
						<td>
						<?php 
						$gp = 0 ;
						$group_list = $api->listInterestGroupings($listId);
						if(!empty($group_list)){
							foreach($group_list as $grouping){
							
								echo '<input type="hidden" name="groups[]" value="interests-'.$grouping['id'].'"/>';
							?>
								<h2><?php echo $grouping['name']?></h2>
									<div id="group<?php echo $grouping['id']?>" style="display: block; margin-bottom: 15px;" class="interests">
									<table>
										<tr>
											<?php 
											$gt = 0;
											foreach ($grouping['groups'] as $group){
												$gt++;
												$gti++;
												echo '<td>';
												echo '<input type="checkbox" name="group['.$grouping['id'].']['.$gti.']" value="'.$group['name'].'"';
												if($group['subscribers'] < 1){
													echo ' disabled';
												}
												echo '>';
												echo $group['name'];
												echo '</td>';
												if($gt==3){
												echo '</tr>';
												$gt=0;
												}
											}
											if($gt<3){
												$gt++;
												echo '<td></td>';
												if($gt==3){
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
					<?php 
					}
					?>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('subject','Email Subject')?>
						</td>
						<td>
						<?php echo $fm->text('subject')?>
						</td>
					</tr>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('frommail','From Email')?>
						</td>
						<td>
						<?php echo $fm->text('frommail')?>
						</td>
					</tr>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('fromname','From Name')?>
						</td>
						<td>
						<?php echo $fm->text('fromname')?>
						</td>
					</tr>
					<tr>
						<td valign="top" class="form_label">
						<?php echo $fm->label('htmltype','Content (html)')?>
						</td>
						<td>
		
						</td>
					</tr>
					<?php 
					}
					?>
					</tbody>
				</table>
		</div>
		<style type="text/css">
		#html_content_ifr{height: 600px!important;}
		#ccm-editor-pane{margin: 10px 35px 10px 35px;}
		</style>
		<?php 
		if (($listId && $listId!='none') && $templateId == 'none'){
		?>
		<div style="text-align: center; height: 780px;" id="ccm-editor-pane">
						  <?php  Loader::element('editor_init'); ?>
						  <?php  Loader::element('editor_config'); ?>
						  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
						  <?php  echo $fm->textarea('html_content', $html_content, array('style' => 'width: 100%;font-family: sans-serif;', 'class' => 'ccm-advanced-editor'))?>
		
		<br style="clear: both;"/>
		<br style="clear: both;"/>
		<input type="submit" value="Create This Campaign" name="Create This Campaign" class="ui-state-default ui-corner-all custom_style big_button"/>
		<a href="<?php echo $this->action('view')?>" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Cancel')?></a>
		</div>
		<br style="clear: both"/>
		<?php 
		}elseif($listId){
		$template_info = $api->templateInfo($templateId,'user');
		//$template_info = $template_info['default_content'];
		//var_dump($template_info);
		//exit;
		?>
		<style type="text/css">
		#html_content_ifr,#html_main_ifr,#html_sidecolumn_ifr{height: 350px!important;}
		#ccm-editor-pane{margin: 30px 35px 30px 35px;}
		#main{display: table-cell; border-color: #89522c; border-width: 1px; border-style: dotted;}
		#sidecolumn{display: table-cell; border-color: #89522c; border-width: 1px; border-style: dotted;}
		#main_content_frame,#sidecolumn_content_frame{ display: block; position: absolute; left: 110px; top: 600px; border-color: #b3b3b3; border-width: 1px; border-style: solid; background-color: white; padding: 15px; height: 400px!important; -moz-box-shadow: 10px 10px 5px #888; -webkit-box-shadow: 10px 10px 5px #888; box-shadow: 10px 10px 5px #888;}
		.mceListBoxMenu{position: absolute!important;}
		#html_sidecolumn_forecolor_menu, #html_main_forecolor_menu{position: absolute!important;}
		#main h1, #sidecolumn h1, #content_buffer h1{ background-image: url(none); }
		.edit_button{margin-bottom: -28px; position: relative; font-weight: bold; letter-spacing: 1px; font-size: 14px; padding-right: 12px; padding-left: 12px; padding-bottom: 4px; padding-top: 4px;}
		</style>
	   		<div style="text-align: left; max-height: 780px; overflow: scroll;">
	   		  <?php 
	   		  if(!$template_info['default_content']['main']){
	   		  //if there is no special area named 'main', then we will edit the whole email
	   		  ?>
			  <?php  Loader::element('editor_init'); ?>
			  <?php  Loader::element('editor_config'); ?>
			  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
			  <textarea name="html_content" id="html_content" class="main_content_editable ccm-advanced-editor"><?php echo $template_info['source']?></textarea>
	   		  <?php 
	   		  }else{
	   		  $cnt = str_replace('body,','', $template_info['source']);
	   		  $cnt = str_replace('body','frame', $cnt);
	   		  $cnt = str_replace('a,','frame a,', $cnt);
	   		  $cnt = str_replace('a:','frame a:', $cnt);
	   		  ?>
			  <frame name="html_content" id="html_content"><?php echo $cnt?></frame>
			  <?php 
			  }
			  ?>
			</div>
			<br style="clear: both"/>
			<div>
				<input type="submit" value="Create This Campaign" class="ui-state-default ui-corner-all custom_style big_button"/>
				<a href="<?php echo $this->url('dashboard/mail_monkey/campaigns/')?>" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Cancel')?></a>
			</div>
			<br style="clear: both"/>
			<br style="clear: both"/>
			
			<script type="text/javascript">/*<![CDATA[*/
				$(document).ready(function(){
					//add edit buttons and loadDialog to each editable area

				
					
					if(document.getElementById('main')){
						var edit_main = document.getElementById('main');
						var new_edit = document.createElement('div');
						new_edit.innerHTML='<a href="javascript:;" onClick="loadMyDialogDo(\'main\');" class="ui-state-default ui-corner-all custom_style">edit</a>';
						new_edit.setAttribute('class','edit_button');
						edit_main.appendChild(new_edit);
					}

					if(document.getElementById('sidecolumn')){
						var edit_main = document.getElementById('sidecolumn');
						var new_edit = document.createElement('div');
						new_edit.innerHTML='<a href="javascript:;" onClick="loadMyDialogDo(\'sidecolumn\');" class="ui-state-default ui-corner-all custom_style">edit</a>';
						new_edit.setAttribute('class','edit_button');
						edit_main.appendChild(new_edit);
					}
				});
			
				closeThis = function(i) {
					$('#'+i+'_content_frame').hide();
					var temp_content = $('#html_'+i+'_ifr').contents().find('#tinymce').html();
					
					if(document.getElementById(i)){
						$('#'+i).html(temp_content);
						var new_edit = document.createElement('div');
						new_edit.innerHTML='<a href="javascript:;" onClick="loadMyDialogDo(\''+i+'\');" class="ui-state-default ui-corner-all custom_style">edit</a>';
						new_edit.setAttribute('class','edit_button');
						var edit_main = document.getElementById(i);
						edit_main.appendChild(new_edit);
					}	
				}
			
				loadMyDialogDo = function(i) {
				//=========================================
				//jqDialog and tinyMCE are not working together
				//=========================================
					//var el = document.createElement('div')
					//el.id = "myNewElementmyDialogContent"+i
					//el.innerHTML = $('#'+i+'_content_frame').html();
					//el.style.display = "none";
					$('#'+i+'_content_frame').show();
					//$('#ccm-editor-pane').hide();
					//$('#'+i+'_content_frame').parent().append(el);
					//	jQuery.fn.dialog.open({
					//		title: 'Editable Content',
					//		element: '#'+i+'_content_frame',
					//		width: 620,
					//		modal: false,
					//		height: 500
					//	});
				}
			/*]]>*/</script>

			<div style="text-align: center; display: none;" id="main_content_frame">
				  <?php  Loader::element('editor_init'); ?>
				  <?php  Loader::element('editor_config'); ?>
				  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
				  <textarea name="html_main" id="html_main" class="ccm-advanced-editor"><?php echo $template_info['default_content']['main']?></textarea>
				  <br/>
				  <div style="float: right;">
				 	 <a href="javascript:;" onClick="closeThis('main');" class="ui-state-default ui-corner-all custom_style">close</a>
				  </div>
			</div>
			<div style="text-align: center; height: 440px; display: none;" id="sidecolumn_content_frame">
				  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
				  <textarea name="html_sidecolumn" id="html_sidecolumn" class="ccm-advanced-editor"><?php echo $template_info['default_content']['sidecolumn']?></textarea>
				  <br/>
				  <div style="float: right;">
				  	<a href="javascript:;" onClick="closeThis('sidecolumn');" class="ui-state-default ui-corner-all custom_style">close</a>
				  </div>
			</div>
		<?php 
		}
		?>
		</form>
	</div>
<?php 
//===============================================================//
//===============================================================//
//
//set Scheduling for a particular campaign
//
//===============================================================//
//===============================================================//	
}elseif($schedule){
?>
	<h1><span><?php  echo t('Modify Campaign')?></span></h1>
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
			<div id="schedule_form">
				<?php 
				$dt = Loader::helper('form/date_time');
				?>
				<h2><?php echo $title?></h2>
				<form id='subscribe-form' action="<?php  echo $this->action('set_schedule')?>" method="post">
				<?php echo t('campaignId: ')?><?php echo $campaignId?>
				<br/><br/>
				<?php echo $fm->label('date_do','Please choose the date of scheduled posting')?>
				<br/>
				<?php echo $dt->datetime('date_do',$date_do)?>
				<?php echo $fm->hidden('id',$campaignId)?>
				<br style="clear: both;"/>
				<br style="clear: both;"/>
				<input type="submit" value="Schedule This Campaign" class="ui-state-default ui-corner-all custom_style big_button"/>
				<a href="<?php echo $this->action('view')?>" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Cancel')?></a>
				</form>
			</div>
		</div>
	</div>
<?php 
//===============================================================//
//===============================================================//
//
//the main campaign list
//
//===============================================================//
//===============================================================//	
}elseif($pkg->config('CHIMP_KEY') != null){
?>
<script type="text/javascript">/*<![CDATA[*/
$(document).ready(function() {
	$('#orders').dataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	});
	$('#info').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
	$('#info2').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
	$('#info3').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
	$('#info4').dataTable( {
		"bJQueryUI": true,
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": true 
	} );
});
/*]]>*/</script>
	<h1><span><?php  echo t('Campaign List')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
			<table id="monkey_sub_nav">
				<tbody>
				<tr>
					<td align="left">
					<a href="<?php  echo $this->action('create_campaign')?>" class="ui-state-default ui-corner-all custom_style big_button" style="float: left;"><?php echo t('Create A New Campaign')?></a>
					</td>
					<td>
				</tr>
				</tbody>
			</table>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<br/>
		<br/>
		<table border="0" class="display" cellspacing="0" cellpadding="0" id="orders" width="100%">
			<thead> 
				<tr>
					<th width="65"></th>
					<th><?php echo t('ID')?></th>
					<th><?php echo t('Title')?></th>
					<th><?php echo t('Status')?></th>
					<th><?php echo t('Type')?></th>
					<th><?php echo t('Send Time')?></th>
					<th><?php echo t('Emails Sent')?></th>
				</tr>
			</thead> 
			<tbody> 
<?php 
	$retval = $api->campaigns();
	if ($api->errorCode){
		echo "\n\tMsg=".$api->errorMessage."\n";
	} else {
		$retval = $retval['data'];
		echo "Campaigns returned: ". sizeof($retval). "\n";
		foreach($retval as $camval){
			$dd += 1;
			//echo "Campaign Id: ".$c['id']." - ".$c['title']."\n";
       	 	//echo "\tStatus: ".$c['status']." - type = ".$c['type']."\n";
        	//echo "\tsent: ".$c['send_time']." to ".$c['emails_sent']." members\n";
?>
		<tr>
			<td>
				<?php 
					if($camval['status']!='sent'){
				?>
				<a href="<?php  echo $this->url('dashboard/mail_monkey/campaigns/edit/editing_campaign/'.$camval['id'].'/')?>" name="edit this" class="tooltip"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/edit.png';?>" width="12px" /><span>Edit This Campaign. <i>(Only Campaigns that have not been sent may be edited.)</i></span></a>
				<?php 
				}
				?>
				<a href="<?php  echo $this->action('copy_campaign',$camval['id'])?>" class="tooltip"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/page_white_paste.png';?>" width="12px"  name="copy this"/><span>Duplicate This Campaign</span></a>
				<a href="javascript:;" name="delete this" class="tooltip" onClick="deleteDialogDo(<?php echo $dd?>);"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/delete.png';?>" width="12px" /><span>Remove This Campaign</span></a>
				
							<div id="deletecheck<?php echo $dd?>" style="display: none;">
								<form id='subscribe-form' action="<?php  echo $this->action('campaign_delete',$camval['id'])?>" method="post">
									<br/>
									<font style="font-size: 18px; font-style: bold; color: red;">
									<?php echo t('::::WARNING::::')?>
									</font>
									<?php echo t('<br/><br/> You are about to remove "'.$camval['title'].'". <br/><br/> This action may not be undone. <br/> <br/> Are you sure you want to continue?')?>
									<br/><br/>
									<?php echo $fm->submit('submit','Yes, DELETE this')?>
								</form>
							</div>
			</td>
			<td><?php echo $camval['id']?></td>
			<td><a href="<?php echo $this->url('dashboard/mail_monkey/campaigns/view_campaign/'.$camval['id'].'/'.$camval['title'].'/'.$camval['send_time'])?>"><?php echo $camval['title']?></a></td>
			<td><?php echo $camval['status']?></td>
			<td><?php echo $camval['type']?></td>
			<td><?php echo $camval['send_time']?></td>
			<td><?php echo $camval['emails_sent']?></td>
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