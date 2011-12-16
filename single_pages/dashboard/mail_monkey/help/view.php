<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$api = new MCAPI($chimp_key);
$pkg = Package::getByHandle('mail_monkey');
$pkt = Loader::helper('concrete/urls');
$fm = Loader::helper('form');
$advice = $api->campaignAdvice($campaignId);
?>
	<h1><span><?php  echo t('MailMonkey Help')?></span></h1>
	<div class="ccm-dashboard-inner">
		<div id="monkey_menu">
			<form id='subscribe-form' action="<?php  echo $this->action('subscribe',$campaign['listId'])?>" method="post">
			<table id="monkey_sub_nav">
				<tr>
					<td>
					<a href="http://www.mailchimp.com/kb/results" class="ui-state-default ui-corner-all custom_style big_button"><?php echo t('Get More MailChimp Help')?></a>
					</td>
				</tr>
			</table>
			</form>
		</div>
		<img src="<?php echo $pkt->getPackageURL($pkg).'/tools/header_sprite.png'?>" width="95px" class="monkey_img"/>
		<br style="clear: right;"/>
		<br/>
		<br/>
		<div id="new_campaign">
		
			<h2><?php echo t('The MailMonkey Workflow')?></h2>
			<p><?php echo t('While there is room for improvement with future releases of the MailChimp API, the MailMonkey workflow is pretty simple:')?></p>
			<blockquote>
				<ul>
					<li><?php echo t('Log into your MailChimp online account and create a new list.')?></li>
					<li><?php echo t('From your dashboard, create your first template.')?></li>
					<li><?php echo t('If you decide to create your template from your MailChimp dashboard, instead of MailMonkey, be sure to save that template to your "my templates" area of your MailChimp account.(MailMonkey will do this automatically)')?></li>
					<li><?php echo t('If you wish, edit your new template, and add editable content tags (see templates topic below).')?></li>
					<li><?php echo t('You may now start using your MailMonkey app, and create some campaigns and even some target groups.')?></li>
				</ul>
			</blockquote>
			
			<h2><?php echo t('Lists')?></h2>
			
				<h3><?php echo t('How do I add a new List?')?></h3>
				<p><?php echo t('At this time, there is currently no API support from MailChimp to allow remote creation of new lists.  Please keep checking back for updates.  In the meantime, you can manage new lists through your MailChimp Lists page.')?></p>
				
				<h3><?php echo t('How do I add members to a List?')?></h3>
				<p><?php echo t('First select the list.  Then choose "add user". You can select more than one user at a time.')?></p>
			<br/>
			<h2><?php echo t('Groups')?></h2>
			
				<h3><?php echo t('What are groups?')?></h3>
				<p><?php echo t('Groups allow you to send targeted emails to certain portions of a list, but not all.  For example, you could send a campaign to a group marked "volunteers" with a list.')?></p>
				
				<h3><?php echo t('Can I change the display type of a group after I create it?')?></h3>
				<p><?php echo t('Not at this time. The Mail Chimp API only supports group type (radio, select, checkbox, and hidden) on creation.  You may not change it once it has been created.')?></p>
				
				<h3><?php echo t('Why is my group grayed out when trying to create a campaign?')?></h3>
				<p><?php echo t('Grayed out group names while creating a campaign indicate that there are no subscribers to that sub-group. One subscribers are added to that group, it will then be available to associate a campaign to.')?></p>
			<br/>
			<h2><?php echo t('Campaigns')?></h2>
			
				<h3><?php echo t('Why is there no edit icon for a particular campaign I want to edit?')?></h3>
				<p><?php echo t('Campaigns may only be edited before they have been sent. A great way to work around this, is to simply "duplicate" a list, and use that as a starting point.')?></p>
				
				<h3><?php echo t('How do I schedule a campaign to post at specific time and date?')?></h3>
				<p><?php echo t('Simply click on the campaign name, and select "Schedule".  Then define your date and time. Campaigns my not however, be scheduled the day of.')?></p>
				
				<h3><?php echo t('Can I unschedule a campaign that has been scheduled?')?></h3>
				<p><?php echo t('Yes.  The Schedule button is changed to "unschedule" once a campaign has successfully been scheduled. Scheduling does not apply to campaigns that have already been sent.')?></p>
				
				<h3><?php echo t('Can I share campaign reports?')?></h3>
				<p><?php echo t('Yes.  Once a campaign has ben sent, a button appears to "share" an online, password protected web accessible report curtesy of Mail Chimp')?></p>
				
				<h3><?php echo t('Can I test my campaign?')?></h3>
				<p><?php echo t('Yes.  You will need to log into your MailChimp account to do this.  We recommend testing all new templates at least once before you send to your actual lists')?></p>
				
			<br/>
			<h2><?php echo t('Templates')?></h2>
				
				<h3><?php echo t('Do I have to use MailChimp Templates?')?></h3>
				<p><?php echo t('No.  You can leave the template set to none, and fully design your template via the WYSIWYG editor, or even copy and paste direct from html. Later, if you so desire, you can convert your newly created campaign to your "My Templates" area of your MailChimp Account for use with new Campaigns')?></p>
				
				<h3><?php echo t('Are there any guidelines for templates?')?></h3>
				<p><?php echo t('Yes.  There is a great resource <a href="http://www.mailchimp.com/resources/html_email_templates/">located here</a> that will help a great deal.  Below are a few MailMonkey specific guidelines to be aware of as well:')?></p>
				<blockquote>
					<p>
						<ul>
							<li><?php echo t('if you do NOT want your whole template to be ported in for editing, and would simply like the content area for editing, simply find the table or div element you would like to have editable and add mc:edit="main" inside the tag, and make sure that the id name matches the editable area.  So &lt;div id="content"> would then become &lt;div id="main" mc:edit="main">')?></li>
							<li><?php echo t('Supported areas are header, main, sidecolumn, and footer')?></li>
							<li><?php echo t('Any and all links and images must be a full url path (http://yorusite.com/image_info/image.jpg) and not a realative address (/image_info/image.jpg)')?></li>
						</ul>
					</p>
				</blockquote>
				
				<h3><?php echo t('I added the editable area, but it\'s not showing as editable in the campaign view, what should I do?')?></h3>
				<p><?php echo t('And important thing to remember is that there is a matching ID to the editable area in order for MailMonkey to read it as such.  An easy way (best practice) to ensure this, is to always add div\'s with an ID the same name as the editable area name, and then add the edit area identifier to that.')?></p>
				<p><?php echo t('Sometimes however;  CSS , tables, or divs can clash in very unbecoming ways.  We recomend at that point logging into your MailChimp account to try and trouble shoot your template using MailChimps rich template editor.  MailMonkey\'s WYSIWYG editor can only do so much, and we desire to integrate MailChimp, not replace them.')?></p>
				
				<h3><?php echo t('When I duplicate a campaign, does that also use the same template?')?></h3>
				<p><?php echo t('Yes.')?></p>
				
				<h3><?php echo t('I want to change or modify the template for a campaign, not just the editable areas, how do I do that?')?></h3>
				<p><?php echo t('There are three options in regards to modifying created campaigns:')?></p>
				<blockquote>
					<p>
						<ul>
							<li>
							<?php echo t('1.) you can simply go to the templates view and modify the root template.  You should be aware though, that any changes made to this template will then be reflected on all campaigns using this template.')?>
							</li>
							<li>
							<?php echo t('2.) you can log into your MailChimp account and edit that campaign to "swap" templates.  MailMonkey will not be supporting this feature as it could in-tale content loss, and the API does not adequately support this.')?>
							</li>
							<li>
							<?php echo t('3.) you can create a new campaign using the template you desire, and then delete the old one.')?>
							</li>
						</ul>
					</p>
				</blockquote>
		</div>
	</div>