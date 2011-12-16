<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyController extends Controller {
	
	public function on_start() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('mail_monkey.css','mail_monkey'));
	}
	
	public function view() {
		$pkg = Package::getByHandle('mail_monkey');
		if($pkg->config('CHIMP_KEY') != null){
			$this->redirect('/dashboard/mail_monkey/lists/');
		}

	}

}