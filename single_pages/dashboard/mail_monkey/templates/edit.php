<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$api = new MCAPI($chimp_key);
$fm=Loader::helper('form');
$pkg = Package::getByHandle('mail_monkey');
$pkt = Loader::helper('concrete/urls');

//$template_info = $api->templateInfo((int)$templateId, 'user');
?>
<div style="width: 800px">
	<h1><span><?php  echo t('Edit Template')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
		<?php 
		$apikey = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($apikey);
		$retval = $api->templateInfo($templateId);
		$templates = $api->templates();
		foreach($templates['user'] as $template){
			if ($template['id'] == $templateId){
				$template_data = $template;
			}
		}
		//var_dump($template_data);
		//exit;
		?>
			<table id="monkey_sub_nav">
				<tr>
					<td>
						<button class="ui-state-default ui-corner-all custom_style" onClick="loadMyDialogDo('1');"><?php echo t('Preview This')?></button>
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
						<div id="preview1" style="display: none;">						
							<iframe src="http://us2.admin.mailchimp.com/campaigns/templates/preview-template?id=<?php echo $template_data['id']?>" frameborder="0" width="650" height="520" scrolling="vertical">
							</iframe>
						</div>
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
						<form id='template_form' action="<?php  echo $this->action('template_update',$templateId)?>" method="post">
						  <table cellpadding="6">
							<tr>
								<td class="form_label">
								<?php echo $fm->label('name','Template Name')?>
								</td>
								<td align="left">
								<?php echo $fm->text('name',$template_data['name'])?>
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
						<br style="clear: both;"/>
						<br style="clear: both;"/>
						<br style="clear: both;"/>
						<input type="submit" value="Update This Template" class="ui-state-default ui-corner-all custom_style big_button"/>
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
