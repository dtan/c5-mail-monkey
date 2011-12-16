<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyHelpController extends Controller {
	
	public function on_start() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('jquery_yellow.ui.css','mail_monkey'));
		$this->addHeaderItem($html->css('mail_monkey.css','mail_monkey'));
	}	
	public function view(){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		loader::model('monkeysee','mail_monkey');

	}
	
}