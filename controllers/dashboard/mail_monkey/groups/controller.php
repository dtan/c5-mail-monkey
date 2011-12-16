<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyGroupsController extends Controller {

	public function on_start() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('jquery_yellow.ui.css','mail_monkey'));
		$this->addHeaderItem($html->css('mail_monkey.css','mail_monkey'));
		
		$this->set('disableThirdLevelNav', true);
	}
	
	public function view($listId=null){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		loader::model('monkeysee','mail_monkey');
		
		if(isset($listId)){
			$pkg = Package::getByHandle('mail_monkey');
			$chimp_key = $pkg->config('CHIMP_KEY');
			loader::model('monkeysee','mail_monkey');
			$api = new MCAPI($chimp_key);
			$retval = $api->lists();
			$retval = $retval['data'];
			foreach($retval as $list){
				if ($list['id'] == $listId){
					$this->set('listId',$list['id']);
					$this->set('list_name',$list['name']);
				}
			}
		}

		$pkt = Loader::helper('concrete/urls');

		$this->addHeaderItem('<script type="text/javascript" src="'.$pkt->getPackageURL($pkg).'/tools/jquery.dataTables.js"></script>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_table_jui.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_page.css"/>');

	}
	
	public function view_groups(){
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($chimp_key);
		$retval = $api->lists();
		foreach($retval as $list){
			if ($list['id'] == $this->get('listId')){
				$this->set('listId',$list['id']);
				$this->set('list_name',$list['name']);
			}
		}
	}
	
	public function create_groupings($listId){
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($chimp_key);
		
		$api->listInterestGroupingAdd($listId,$this->post('title'),$this->post('type'),$this->post('option'));
		
		$this->redirect('/dashboard/mail_monkey/groups/view/'.$listId.'/');

	}
	
	public function group_delete($listId,$id){
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($chimp_key);
		
		$api->listInterestGroupingDel($id);
		$this->redirect('/dashboard/mail_monkey/groups/view/'.$listId.'/');

	}
	
	
}