<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyTemplatesEditController extends Controller {

	public function on_start() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('jquery_yellow.ui.css','mail_monkey'));
		$this->addHeaderItem($html->css('mail_monkey.css','mail_monkey'));

		$this->set('disableThirdLevelNav', true);
		
		loader::model('monkeysee','mail_monkey');
		$pkg = Package::getByHandle('mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
	}
	
	public function view_template($templateId=null){
		loader::model('monkeysee','mail_monkey');
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		$this->set('templateId',$templateId);
	}
	
	public function template_update($id){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$content = str_replace(BASE_URL . DIR_REL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_content'));
		$content = str_replace('/index.php/download_file/',BASE_URL . DIR_REL.'/index.php/download_file/', $content);
		
		//first, remove any edit's if for some reason they did comethrough.
		//this makes sure that we do not mistakenly add them twice
		$content = str_replace('id="main" mc:edit="main"','id="main"',$content);
		$content = str_replace('id="sidecolumn" mc:edit="sidecolumn"','id="sidecolumn"',$content);
		//now go and add them all back.
		$content = str_replace('id="main"','id="main" mc:edit="main"',$content);
		$content = str_replace('id="sidecolumn"','id="sidecolumn" mc:edit="sidecolumn"',$content);
		
		$values = array('html'=>$content,'name'=>$this->post('name'));
		
		$api->templateUpdate($id,$values);
		
		if ($api->errorCode){
			echo "Unable to Segment Campaign!";
			echo "\n\tCode=".$api->errorCode;
			echo "\n\tMsg=".$api->errorMessage."\n";
		}
		
		$this->redirect('/dashboard/mail_monkey/templates/');
	}

}