<?php 
$pkg = Package::getByHandle('mail_monkey');
if($pkg->config('CHIMP_KEY') != null){
	loader::model('monkeysee','mail_monkey');
	$apikey = $pkg->config('CHIMP_KEY');
	$api = new MCAPI($apikey);
	$retval = $api->lists();
	$fm = Loader::helper('form');
	?>
	<h2><?php echo t('Choose a List')?></h2>
	<br/>
	<select name="list">
		<?php 
			foreach($retval['data'] as $list){
				echo '<option value="'.$list['id'].'">'.$list['name'].'</option>';
			}
		?>
	</select>
	<br/>
	<br/>
	<h2><?php echo t('Options')?></h2>
	<input type="checkbox" name="show_groups" value="1" <?php  if($show_groups==1){echo 'checked';}?> /> <?php echo t('Show Groups')?>
	<br/>
	<br/>
<?php 
}else{
	echo 'you need to set your API key in the settings panel.';
}
?>