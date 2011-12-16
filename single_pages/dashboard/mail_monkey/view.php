<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$pkg = Package::getByHandle('mail_monkey');
if($pkg->config('CHIMP_KEY') == null){
?>
<div id="monkey_dashboard">
	<div id="monkey_header"></div>
	<a href="http://eepurl.com/bAs89" class="monkey_sign"></a>
	<a href="http://mailchimp.com" class="monkey_log"></a>
</div>
<?php 
}else{

}
?>