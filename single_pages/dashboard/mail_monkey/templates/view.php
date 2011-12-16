<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
/**
This Example shows how to pull the Members of a List using the MCAPI.php 
class and do some basic error checking.
**/

$api = new MCAPI($chimp_key);
$pkg = Package::getByHandle('mail_monkey');
$pkt = Loader::helper('concrete/urls');
$fm=Loader::helper('form');


if($pkg->config('CHIMP_KEY') != null){

	if($add_template){
	?>
	<div style="width: 800px">
		<h1><span><?php  echo t('Add Template')?></span></h1>
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
			<?php 
	if ($api->errorCode){
		echo "Unable to Load Template Info!";
		echo "\n\tCode=".$api->errorCode;
		echo "\n\tMsg=".$api->errorMessage."\n";
	} else {
	   		?>
	   				<style type="text/css">
					#html_content_ifr{height: 650px!important;}
					#ccm-editor-pane{margin: 10px 15px 10px 15px;}
					</style>
					<div id="content_buffer" style="display: block;">
							<form id="template_form" action="<?php  echo $this->action('add_template')?>" method="post" target="_self">
							  <table cellpadding="6">
								<tr>
									<td class="form_label">
									<?php echo $fm->label('name','Template Name')?>
									</td>
									<td align="left">
									<?php echo $fm->text('name',$template_data['name'])?>
									</td>
								</tr>
								<tr>
									<td class="form_label">
									<?php echo $fm->label('tag','Template Reference')?>
									</td>
									<td align="left">
									<a href="http://www.mailchimp.com/resources/email-template-language/" target="_blank">Learn Mail Chimp Template Language</a>
									</td>
								</tr>
							  </table>
							
									<div style="text-align: left; height: 700px;" id="ccm-editor-pane">
									  <?php  Loader::element('editor_init'); ?>
									  <?php  Loader::element('editor_config'); ?>
									  <?php  Loader::element('editor_controls', array('mode'=>'full')); ?>
									  <?php  echo $fm->textarea('html_content',$retval['source'] , array('style' => 'width: 100%; font-family: sans-serif;', 'class' => 'ccm-advanced-editor','rows'=>'15'))?>
									</div>
							<br/>
							<br/>
							<br/>
							<input type="submit" value="Add This Template" class="ui-state-default ui-corner-all custom_style big_button"/>
							<a href="<?php echo $this->url('dashboard/mail_monkey/templates/')?>" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Cancel')?></a>
							<br style="clear: both"/>
							</form>
				</div>
			<?php 
	}
			?>
			</div>
		</div>
	</div>
	<?php 
	}elseif($pkg->config('CHIMP_KEY') != null){
	
		$template_list = $api->templates();
		$template_list = $template_list['user'];
	
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
		<h1><span><?php  echo t('Templates')?></span></h1>
		<div class="ccm-dashboard-inner">	
			<div id="monkey_menu">
				<table id="monkey_sub_nav">
					<tr>
						<td>
							<a href="<?php echo $this->url('dashboard/mail_monkey/templates/template_add/')?>" class="ui-state-default ui-corner-all custom_style big_button"  style="float: left;"><?php echo t('Add a Template')?></a>
						</td>
					</tr>
				</table>
			</div>
			<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
			<br style="clear: right;"/>
			<br style="clear: right;"/>
			<br/><br/>
			<table border="0" class="display" cellspacing="0" cellpadding="0" id="orders" width="100%">
				<thead> 
					<tr>
						<th width="65"></th>
						<th>Name</th>
						<th>Category</th>
						<th>Layout</th>
						<th>Preview</th>
					</tr>
				</thead> 
				<tbody> 
				<?php 
				foreach($template_list as $template){
					$t++;
				?>
					<tr>
						<td>
						<a href="<?php  echo $this->url('dashboard/mail_monkey/templates/edit/view_template/'.$template['id'].'/')?>" name="delete this" class="tooltip"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/edit.png';?>" width="12px" /><span>Edit This Template.</span></a>
	
						<a href="javascript:;" name="delete this" class="tooltip" onClick="deleteDialogDo(<?php echo $t?>);"><img src="<?php  echo $pkt->getPackageURL($pkg).'/tools/delete.png';?>" width="12px" /><span>Remove This Template</span></a>
									<div id="deletecheck<?php echo $t?>" style="display: none;">
										<form id='subscribe-form' action="<?php  echo $this->action('template_delete',$template['id'])?>" method="post">
											<br/>
											<font style="font-size: 18px; font-style: bold; color: red;">
											<?php echo t('::::WARNING::::')?>
											</font>
											<?php echo t('<br/><br/> You are about to remove "'.$template['name'].'". <br/><br/> This action may not be undone. <br/> <br/> Are you sure you want to continue?')?>
											<br/><br/>
											<?php echo $fm->submit('submit','Yes, DELETE this')?>
										</form>
									</div>
						</td>
						<td>
							<?php echo $template['name']?>
						</td>
						<td>
							<?php echo $template['category']?>
						</td>
						<td>
							<?php echo $template['layout']?>
						</td>
						<td>
						<?php 
						//var_dump($template);
						//exit;
						$ti++;
						?>
							<a href="javascript:;" onClick="loadMyDialogDo('<?php echo $ti?>');"><?php echo t('Preview This')?></a>
							<script type="text/javascript">/*<![CDATA[*/
							loadMyDialogDo = function(i) {
					
								var el = document.createElement('div')
								el.id = "myNewElementmyDialogContent"+i
								el.innerHTML = $('#preview'+i).html();
								el.style.display = "none"
								$('#preview'+i).parent().append(el);
									jQuery.fn.dialog.open({
										title: 'Your Email Preview',
										element: '#myNewElementmyDialogContent'+i,
										width: 630,
										modal: false,
										height: 500
									});
							}
							/*]]>*/</script>
							<div id="preview<?php echo $ti?>" style="display: none;">						
								<iframe src="http://us2.admin.mailchimp.com/campaigns/templates/preview-template?id=<?php echo $template['id']?>" frameborder="0" width="650" height="520" scrolling="vertical">
								</iframe>
							</div>
						</td>
					</tr>
				<?php 
				}
				?>
				</tbody>
			</table>
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
		</div>
	</div>
	<?php 
	}
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