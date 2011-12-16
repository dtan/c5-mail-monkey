<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyGroupsEditController extends Controller {
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

	public function edit_grouping($id,$name){
		$this->set('edit_group',true);
		$this->set('listId',$id);
		$this->set('group_name',$name);

		if($error != null){
			$error = array('message'=>$error);
			$this->set('error',$error);
		}
	}
	
	public function update_grouping($id){
		loader::model('monkeysee','mail_monkey');
		$pkg = Package::getByHandle('mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$this->set('edit_group',true);
		$this->set('listId',$id);
		$this->set('group_name',$name);
		
		$options = $this->post('option');
		$oc = count($options);
		$old_options = $this->post('old_option');
		$ooc = count($old_options);
		
		for($i=1;$i<=$oc+10;$i++){
			if($options[$i] && $old_options[$i]){
				//var_dump( $options[$i].' updated ,');
				$api->listInterestGroupUpdate($id,$old_options[$i],$options[$i],$this->post('groupingId'));
				
			}elseif(!$options[$i] && $old_options[$i]){
				//var_dump( $old_options[$i].' deleted ,');
				$api->listInterestGroupDel($id,$old_options[$i],$this->post('groupingId'));
				
			}elseif($options[$i] && !$old_options[$i]){
				//var_dump( $options[$i].' added ,');
				$api->listInterestGroupAdd($id,$options[$i],$this->post('groupingId'));
				
			}
		}
		

		
		//echo 'updated group #'.$this->post('groupingId').' , '.$this->post('title').' , '.$this->post('type');
		
		$api->listInterestGroupingUpdate($this->post('groupingId'),'name',$this->post('title'));
		
		$api->listInterestGroupingUpdate($this->post('groupingId'),'type',$this->post('type'));
		
		$this->redirect('/dashboard/mail_monkey/groups/view/'.$id.'/');
		
	}
}