<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeylistsEditController extends Controller {
	public function on_start() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('jquery_yellow.ui.css','mail_monkey'));
		$this->addHeaderItem($html->css('mail_monkey.css','mail_monkey'));

		$this->set('disableThirdLevelNav', true);
	}
	
	public function view($email=null,$listId=null,$list_name=null,$error=null){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		loader::model('monkeysee','mail_monkey');
		$pkt = Loader::helper('concrete/urls');
		
		$this->set('email',$email);
		$this->set('listId',$listId);
		$this->set('list_name',$list_name);

		$this->addHeaderItem('<script type="text/javascript" src="'.$pkt->getPackageURL($pkg).'/tools/jquery.dataTables.js"></script>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_table_jui.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$pkt->getPackageURL($pkg).'/tools/demo_page.css"/>');
		
		if($error != null){
			$error = array('message'=>$error);
			$this->set('error',$error);
		}
	}
	
	public function update_member($listId,$list_name){
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($chimp_key);
		
		$interests = $this->post('interest');
		$groups = $this->post('groups');

		if(is_array($interests)){
			foreach($groups as $group){
				$opt_interest['id'] = $group;
				$i=0;
				$opt_interests = '';
				if($interests[$group]){
					foreach($interests[$group] as $option){
						if($i){$opt_interests .= ',';}
						$opt_interests .= $option;
						$i++;
					}
				}else{
					$opt_interests = '';
				}
				$opt_interest['groups'] = $opt_interests;
				$options_collect[] = $opt_interest;
			}
		}else{
			$options_collect = '';
		}

		$merge_vars = array('FNAME'=>$this->post('fname'), 'LNAME'=>$this->post('lname'), 'EMAIL'=>strtolower($this->post('email')), 'GROUPINGS'=>$options_collect);

		$retval = $api->listUpdateMember($listId,$this->post('old_email'), $merge_vars, 'html', true);
	
		if ($api->errorCode){
				$error= $api->errorMessage;
				$this->redirect('/dashboard/mail_monkey/lists/edit/', $this->post('email'), $listId, $list_name, $error);
		}else{
			$this->redirect('/dashboard/mail_monkey/lists/view_list/'.$listId.'/'.$list_name.'/');
		}
	}
}
?>