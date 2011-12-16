<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyTemplatesController extends Controller {

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

		$pkt = Loader::helper('concrete/urls');

		$this->addHeaderItem('<script type="text/javascript" src="'.$pkt->getPackageURL($pkg).'/tools/jquery.dataTables.js"></script>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_table_jui.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_page.css"/>');

	}
	
	public function template_add(){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$this->set('add_template',true);
	}
	
	public function add_template(){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$content = str_replace('/index.php/download_file/',BASE_URL . DIR_REL.'/index.php/download_file/', $this->post('html_content'));
		
		//first, remove any edit's if for some reason they did comethrough.
		//this makes sure that we do not mistakenly add them twice
		$content = str_replace('id="main" mc:edit="main"','id="main"',$content);
		$content = str_replace('id="sidecolumn" mc:edit="sidecolumn"','id="sidecolumn"',$content);
		//now go and add them all back.
		$content = str_replace('id="main"','id="main" mc:edit="main"',$content);
		$content = str_replace('id="sidecolumn"','id="sidecolumn" mc:edit="sidecolumn"',$content);
		
		$api->templateAdd($this->post('name'), $content);
		
		$this->redirect('/dashboard/mail_monkey/templates/');
	}
	
	public function template_delete($id){
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($chimp_key);
		
		$api->templateDel($id);
		$this->redirect('/dashboard/mail_monkey/templates/');
	}
}