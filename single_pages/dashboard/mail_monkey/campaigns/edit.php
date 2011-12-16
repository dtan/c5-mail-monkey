<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$api = new MCAPI($chimp_key);
$pkg = Package::getByHandle('mail_monkey');
$pkt = Loader::helper('concrete/urls');
$fm = Loader::helper('form');
$advice = $api->campaignAdvice($campaignId);
	
if($edit_campaign){
//===============================================================//
//===============================================================//
//
//edit a campaign.  may not be used with "sent" campaigns.
//only certen fields may be edited.
//
//===============================================================//
//===============================================================//	
?>
<div style="width: 800px">
	<h1><span><?php  echo t('Modify Campaign')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
		<?php 
		$apikey = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($apikey);
		//$content = $api->campaignTemplateContent($campaign['id']);
		//if(!$content){
		$content = $api->campaignContent($campaign['id'],false);
			//$content['main'] = $content['html'];
		//}
		$content_view = $api->campaignTemplateContent($campaign['id']);
		//var_dump($campaign);
		//exit;
		?>
			<table id="monkey_sub_nav">
				<tr>
					<td>
						<a herf="javascript:;" class="ui-state-default ui-corner-all custom_style" onClick="popup_viewer('1');"><?php echo t('View This')?></a>
						<script type="text/javascript">/*<![CDATA[*/
						popup_viewer = function(i) {
				
							var el = document.createElement('div')
							el.id = "myNewElementmyDialogContent"+i
							el.innerHTML = $('#previewer'+i).html();
							el.style.display = "none"
							$('#previewer'+i).parent().append(el);
								jQuery.fn.dialog.open({
									title: 'Your Email Preview',
									element: '#myNewElementmyDialogContent'+i,
									width: 620,
									modal: false,
									height: 500
								});
						}
						/*]]>*/</script>
						<div id="previewer1" style="display: none;">
						<iframe src="<?php echo $campaign['archive_url']?>" width="620" height="700" frameborder="0" scrolling="no"></iframe>
						</div>
					</td>
					<td>
						<a href="http://mailchimp.com" class="ui-state-default ui-corner-all custom_style" target="_blank"><?php echo t('Go to your account')?></a>
					</td>
				</tr>
			</table>
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
			/*]]>*/</script>
			<form id='campaign_form' action="<?php  echo $this->action('campaign_update',$campaign['id'])?>" method="post">
				<table cellpadding="6">
						<?php 
						$vca= count($campaign["segment_opts"]);
						//segment id number
						//loop through the number of segments
						//and assign each grouping to an array
						$segment_selected = array();
						for($i=0;$i<$vca;$i++){
							$segment_selected[] = ($campaign["segment_opts"]['conditions'][$i]['value']);
						}
						?>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('interest','Selected Segments')?>
						</td>
						<td>
						<?php echo $fm->hidden('list_Id',$campaign['list_id'])?>
						<?php echo $fm->hidden('template_id',$campaign['template_id'])?>
						<?php 
						$group_list = $api->listInterestGroupings($campaign['list_id']);
						$interest = explode(', ', $campaign['segment_text']);
						$gi = 0;//start grouing count at zero
						if(is_array($group_list)){
							foreach($group_list as $grouping){
							?>
								<h2><?php echo $grouping['name']?></h2>
									<div id="group<?php echo $grouping['id']?>"  class="interests">
									<table>
										<tr>
											<?php 
											echo '<input type="hidden" name="groups[]" value="interests-'.$grouping['id'].'"/>';
											$gt = 0;
											foreach ($grouping['groups'] as $group){
												$gt++;
												$gti++;
												
												echo '<td>';
												echo '<input type="hidden" value="'.$group['name'].'" name="subs[interests-'.$grouping['id'].'][]"/>';
												echo '<input type="checkbox" name="interest[interests-'.$grouping['id'].']['.$gti.']" value="'.$group['name'].'"';
												if(is_array($segment_selected[0])){
													//if the grouping selected is an array
													//see if the current check box id num is within that array.
													//if so, mark it as checked
													if(in_array($group['bit'], $segment_selected[0])){
														echo 'checked';
													}
												}
												//if there are no group members, disable this
												if($group['subscribers'] < 1){
													echo ' disabled';
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
									<br/><br/>
							<?php 
							$gi++;//bump the grouping count each time
							}
						}
						?>
						</td>
					</tr>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('subject','Email Subject')?>
						</td>
						<td align="left">
						<?php echo $fm->text('subject',$campaign['title'])?>
						</td>
					</tr>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('frommail','From Email')?>
						</td>
						<td align="left">
						<?php echo $fm->text('frommail',$campaign['from_email'])?>
						</td>
					</tr>
					<tr>
						<td class="form_label">
						<?php echo $fm->label('fromname','From Name')?>
						</td>
						<td align="left">
						<?php echo $fm->text('fromname',$campaign['from_name'])?>
						</td>
					</tr>

				</table>
			</div>
			<style type="text/css">
			#html_content_ifr{height: 550px!important;}
			#ccm-editor-pane{margin: 30px 35px 30px 35px;}
			#main{display: table-cell; border-color: #89522c !important; border-width: 1px !important; border-style: dotted !important;}
			#sidecolumn{display: table-cell; border-color: #89522c; border-width: 1px; border-style: dotted;}
			#main_content_frame,#sidecolumn_content_frame{ display: block; position: absolute; left: 110px; top: 400px; border-color: #b3b3b3; border-width: 1px; border-style: solid; background-color: white; padding: 15px; height: 400px; -moz-box-shadow: 10px 10px 5px #888; -webkit-box-shadow: 10px 10px 5px #888; box-shadow: 10px 10px 5px #888;}
			.mceListBoxMenu{position: absolute!important;}
			#html_sidecolumn_forecolor_menu, #html_main_forecolor_menu{position: absolute!important;}
			#main h1, #sidecolumn h1{ background-image: url(none); }
			.edit_button{margin-bottom: -28px; position: relative; font-weight: bold; letter-spacing: 1px; font-size: 14px; padding-right: 12px; padding-left: 12px; padding-bottom: 4px; padding-top: 4px;}
			</style>
			<div id="content_buffer" style="display: block;">
			   		<div style="text-align: left; height: 700px; overflow-y: scroll;">
			   		  <?php 
			   	
			   		  if(!$content_view['main']){
			   		  //if there is no special area named 'main', then we will edit the whole email
			   		  ?>
					  <?php  Loader::element('editor_init'); ?>
					  <?php  Loader::element('editor_config'); ?>
					  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
					  <?php  echo $fm->textarea('html_content', $content['html'], array('style' => 'width: 100%;font-family: sans-serif;', 'class' => 'ccm-advanced-editor'))?>
			   		  <?php 
			   		  }else{
			   		  $cnt = str_replace('body,','', $content['html']);
			   		  $cnt = str_replace('body','frame', $cnt);
			   		  $cnt = str_replace('a,','frame a,', $cnt);
			   		  $cnt = str_replace('a:','frame a:', $cnt);
			   		  ?>
					  <frame name="html_content" id="html_content"><?php echo $cnt?></frame>
					  <?php 
					  }
					  ?>
					</div>
					<?php 
					if($content_view['main']){
					?>
					<script type="text/javascript">/*<![CDATA[*/
					
						
						$(document).ready(function(){
							//add edit buttons and loadDialog to each editable area
							if(document.getElementById('main')){
								var edit_main = document.getElementById('main');
								var new_edit = document.createElement('div');
								new_edit.innerHTML='<a href="javascript:;" onClick="loadMyDialogDo(\'main\');" class="ui-state-default ui-corner-all custom_style small_button">edit</a>';
								new_edit.setAttribute('class','edit_button');
								edit_main.appendChild(new_edit);
							}

							if(document.getElementById('sidecolumn')){
								var edit_main = document.getElementById('sidecolumn');
								var new_edit = document.createElement('div');
								new_edit.innerHTML='<a href="javascript:;" onClick="loadMyDialogDo(\'sidecolumn\');" class="ui-state-default ui-corner-all custom_style small_button">edit</a>';
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
								new_edit.innerHTML='<a href="javascript:;" onClick="loadMyDialogDo(\''+i+'\');" class="ui-state-default ui-corner-all custom_style small_button">edit</a>';
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
				<br/>
				<br/>
			
				<div style="text-align: center; height: 440px; display: none;" id="main_content_frame">
					  <?php  Loader::element('editor_init'); ?>
					  <?php  Loader::element('editor_config'); ?>
					  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
					  <textarea name="html_main" id="html_main" class="ccm-advanced-editor"><?php echo $content_view['main']?></textarea>
					  <br/>
					  <div style="float: right;">
					 	 <a href="javascript:;" onClick="closeThis('main');" class="ui-state-default ui-corner-all custom_style small_button">close</a>
					  </div>
				</div>
				<div style="text-align: center; height: 440px; display: none;" id="sidecolumn_content_frame">
					  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
					  <textarea name="html_sidecolumn" id="html_sidecolumn" class="ccm-advanced-editor"><?php echo $content_view['sidecolumn']?></textarea>
					  <br/>
					  <div style="float: right;">
					  	<a href="javascript:;" onClick="closeThis('sidecolumn');" class="ui-state-default ui-corner-all custom_style">close</a>
					  </div>
				</div>
				<?php 
				}
				?>
				<input type="submit" value="Update This Campaign" class="ui-state-default ui-corner-all custom_style big_button"/>
				<a href="<?php echo $this->url('dashboard/mail_monkey/campaigns/')?>" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Cancel')?></a>
				<br style="clear: both"/>
				
			</form>
		</div>
	</div>
</div>
<?php 
}