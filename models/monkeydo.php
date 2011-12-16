<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));  
class doMonkey Extends Object{

	public function subscribeUser($listId, $uID, $interest=array()){
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($chimp_key);
		
		
		if(!empty($interest)){
			$opt_interest = join(', ',$interest);
			
		}else{
			$opt_interest = '';
		}
		
		if(!empty($uID)){
			foreach($uID as $u){
				Loader::model('user');
				Loader::model('userinfo');
				$nu = UserInfo::getByID($u);
				$first_name = $nu->getUserFirstName();
				$last_name = $nu->getUserLastName();
			
				$merge_vars = array('FNAME'=>$first_name, 'LNAME'=>$last_name, 
				                    'INTERESTS'=>$opt_interest);
				// By default this sends a confirmation email - you will not see new members
				// until the link contained in it is clicked!
				$retval = $api->listSubscribe( $listId, $nu->uEmail, $merge_vars );
			}
		}
		
		if ($api->errorCode){
				$error= $api->errorMessage;
				$this->redirect('/dashboard/mail_monkey/lists/add_user/',$listId,$error);
		}else{
			$this->redirect('/dashboard/mail_monkey/lists/');
		}
	}

	public function unsubscribeUser($listId, $email){
		$pkg = Package::getByHandle('mail_monkey');
		$chimp_key = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($chimp_key);

		$retval = $api->listUnsubscribe( $listId, $email);
		
		$this->redirect('/dashboard/mail_monkey/lists/');
	}
	
	
}
?>