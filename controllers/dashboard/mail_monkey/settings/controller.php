<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeySettingsController extends Controller {
	
	public function view(){

	
	}
	
	public function addit(){
	
		$pkg = Package::getByHandle('mail_monkey');
		
		$pkg->saveConfig('CHIMP_KEY', $this->post('chimp_key'));
		$pkg->saveConfig('TWEET_MONKEY', $this->post('tweet_monkey'));
		$pkg->saveConfig('TRACK_MONKEY', $this->post('track_monkey'));
		
		$this->view();
	}
}