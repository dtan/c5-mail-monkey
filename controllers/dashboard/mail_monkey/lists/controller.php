<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyListsController extends Controller {
	
	public function on_start() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('jquery_yellow.ui.css','mail_monkey'));
		$this->addHeaderItem($html->css('mail_monkey.css','mail_monkey'));

		$this->set('disableThirdLevelNav', true);
	}
	
	public function view(){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		loader::model('monkeysee','mail_monkey');
		$pkt = Loader::helper('concrete/urls');

		$this->addHeaderItem('<script type="text/javascript" src="'.$pkt->getPackageURL($pkg).'/tools/jquery.dataTables.js"></script>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_table_jui.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_page.css"/>');
	}
	
	public function view_list($listId,$list_name=null){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		$this->set('listId',$listId);
		$this->set('list_name',$list_name);
		$this->set('view_list',true);
		loader::model('monkeysee','mail_monkey');

		$pkt = Loader::helper('concrete/urls');

		$this->addHeaderItem('<script type="text/javascript" src="'.$pkt->getPackageURL($pkg).'/tools/jquery.dataTables.js"></script>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_table_jui.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_page.css"/>');
	}
	
	public function add_user($listId,$error=null){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		loader::model('monkeysee','mail_monkey');
		$this->set('listId',$listId);
		$this->set('adduser',true);
		
		$pkt = Loader::helper('concrete/urls');

		$this->addHeaderItem('<script type="text/javascript" src="'.$pkt->getPackageURL($pkg).'/tools/jquery.dataTables.js"></script>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_table_jui.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_page.css"/>');
		
		if($error != null){
			$error = array('message'=>$error);
			$this->set('error',$error);
		}
	}
	
	public function subscribe($listId){
	
		loader::model('monkeydo','mail_monkey');
		doMonkey::subscribeUser($listId,$this->post('uID'),$this->post('interest'));

	}
	
	public function unsubscribe($listId,$email){
		loader::model('monkeydo','mail_monkey');
		doMonkey::unsubscribeUser($listId,$email);

	}
}