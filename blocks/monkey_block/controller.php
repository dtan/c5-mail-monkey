<?php  

	defined('C5_EXECUTE') or die(_("Access Denied."));
	class MonkeyBlockBlockController extends BlockController {

		protected $btTable = 'btMailMonkey';
		protected $btInterfaceWidth = "350";
		protected $btInterfaceHeight = "180";
		
		/** 
		 * Used for localization. If we want to localize the name/description we have to include this
		 */
		public function getBlockTypeDescription() {
			return t("Add your MailChimp Forms");
		}
		
		public function getBlockTypeName() {
			return t("MailChimp Block");
		}
		
		public function view($thanks=null){

			if($thanks){
				$this->set('thanks',$thanks);
			}

		}
		
		public function action_signmeup(){
			$pkg = Package::getByHandle('mail_monkey');
			$chimp_key = $pkg->config('CHIMP_KEY');
			loader::model('monkeysee','mail_monkey');
			$api = new MCAPI($chimp_key);
			
			$interest = $this->post('interest');

			foreach($interest as $id=>$name_array){
				$names = '';
				$i=0;
				foreach($name_array as $name){
				 	if($i){ $names .= ','; }
				 	$names .= $name;
				 	$i++;
				}
				$groupedup[] = array('id'=>$id, 'groups'=>$names);
			}

			$id = $this->post('id');
			$first_name = $this->post('first_name');
			$last_name = $this->post('last_name');
			$email = strtolower($this->post('email'));
			
			if(is_array($groupedup)){
				$merge_vars = array('FNAME'=>$first_name, 'LNAME'=>$last_name,'GROUPINGS'=>$groupedup);
			}else{
				$merge_vars = array('FNAME'=>$first_name, 'LNAME'=>$last_name);
			}

			$retval = $api->listSubscribe( $id, $email, $merge_vars );
			
			if ($api->errorCode){
				$error= $api->errorMessage;
				$this->view($thanks=$error);
			}else{
				$this->view($thanks=1);
			}
		
		}
		
		function save($args) {

			$args['show_groups'] = ($args['show_groups']) ? '1' : '0';

			parent::save($args);
		
		}
		
	}
?>