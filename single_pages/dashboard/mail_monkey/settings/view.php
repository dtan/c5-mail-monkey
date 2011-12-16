<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$fm = Loader::helper('form');
$pkg = Package::getByHandle('mail_monkey');
?>
<div style="width: 760px;">
		<h1><span><?php  echo t('Settings')?></span></h1>
		<div class="ccm-dashboard-inner">
		<form id='settings-form' action="<?php  echo $this->action('addit')?>" method="post">
			<table class="entry-form">
				<tr>
					<td class="subheader">
						<label><?php echo t('Please Enter Your Mailchimp API key.')?></label>
					</td>
				</tr>
				<tr>
					<td valign=top>
						<?php  $chimp_key = $pkg->config('CHIMP_KEY'); ?>
						<?php echo $fm->text('chimp_key',$chimp_key,array('size'=>'50'))?>
					</td>
				</tr>
				<tr>
					<td class="subheader">
						<label><?php echo t('Do you want your mails to be auto tweeted?')?></label>
					</td>
				</tr>
				<tr>
					<td valign=top>
						<?php  $tweet_monkey = $pkg->config('TWEET_MONKEY'); ?>
						<input type="checkbox" value="1" name="tweet_monkey" <?php  if($tweet_monkey==1){echo 'checked';} ?>/> Yes, please tweet my campaigns.<br/>
						<i>You must make sure that you have installed your twitter addon within your MailChimp account.</i>
					</td>
				</tr>
				<tr>
					<td class="subheader">
						<label><?php echo t('Do you want to track Google Analytics on your mail?')?></label>
					</td>
				</tr>
				<tr>
					<td valign=top>
						<?php  $track_monkey = $pkg->config('TRACK_MONKEY'); ?>
						<input type="checkbox" value="1" name="track_monkey" <?php  if($track_monkey==1){echo 'checked';} ?>/> Yes, please track my campaigns.<br/>
						<i>You must make sure that you have installed your Google addon within your MailChimp account.</i>
					</td>
				</tr>
			</table>
		<?php  
		$ih = Loader::helper('concrete/interface');	
		echo $ih->submit('Save Settings', 'settings-form');
		?>
		</form>
		<br/>
		<br/>
		<br/>
		</div>
</div>