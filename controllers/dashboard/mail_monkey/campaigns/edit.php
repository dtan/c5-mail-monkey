<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyCampaignsEditController extends Controller {

	public function on_start() {
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('jquery_yellow.ui.css','mail_monkey'));
		$this->addHeaderItem($html->css('mail_monkey.css','mail_monkey'));

		$this->set('disableThirdLevelNav', true);
	}

	public function editing_campaign($id,$error=null){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('edit_campaign',true);
		$apikey = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($apikey);
		$opt['campaign_id'] = $id;
		$campaign = $api->campaigns($opt);
		$campaign = $campaign['data'][0];
		$this->set('campaign',$campaign);
		if($error != null){
			$error = array('message'=>$error);
			$this->set('error',$error);
		}
	}
	
	
	public function campaign_update($id){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$tweet_monkey = $pkg->config('TWEET_MONKEY');
		$track_monkey = $pkg->config('TRACK_MONKEY');
		$api = new MCAPI($apikey);
		
		$interests = $this->post('interest');
		$groups = $this->post('groups');
		$subs = $this->post('subs');

		$conditions = array();

		if(is_array($interests)){
			foreach($groups as $group){
				$opt_interest['field'] = $group;
				$opt_interest['op'] = 'all';
				$i=0;
				$opt_interests = '';
				if($interests[$group]){
					foreach($interests[$group] as $option){
						if($i){$opt_interests .= ', ';}
						$opt_interests .= $option;
						$i++;
					}
					$opt_interest['value'] = $opt_interests;
					$conditions[] = $opt_interest;
				}
			}
		}else{
			$conditions = '';
		}

		
		//$seg['conditions'][] = array('field'=>'fname', 'op'=>'like', 'value'=>'bob');
		//var_dump($conditions);
		//exit;

		if(!empty($conditions)){
			 $opts['segment_opts'] = array('match'=>'all', 'conditions'=>$conditions);
			 $api->campaignSegmentTest($this->post('list_Id'),$opts['segment_opts']);
			 if ($api->errorCode){
			 	echo "Unable to Segment Campaign!";
			 	echo "\n\tCode=".$api->errorCode;
			 	echo "\n\tMsg=".$api->errorMessage."\n";
			 }
		}else{
			$opts['segment_opts'] = array('match'=>'all', 'conditions'=>$conditions);
		}

		//var_dump($opts['segment_opts']);
		//exit;
		
		if($tweet_monkey==1){
			$opts['auto_tweet'] = true;
		}else{
			$opts['auto_tweet'] = false;
		}
		
		if($tweet_monkey==1){
			$opts['analytics'] = 'google';
			$opts['analytics_tag'] = $this->post('subject').'_'.date('Y-m-d');
		}else{
			$opts['analytics'] = 'N';
		}
		
		$opts['auto_footer'] = false;
		$opts['subject'] = $this->post('subject');
		$opts['title'] = $this->post('subject');
		
		
		if($this->post('frommail')){
			$opts['from_email'] = $this->post('frommail'); 
		}
		if($this->post('fromname')){
			$opts['from_name'] = $this->post('fromname');
		}
		
		$opts['generate_text'] = true;
		
		$content = str_replace(BASE_URL . DIR_REL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_main'));
		$content = str_replace('/index.php/download_file/',BASE_URL . DIR_REL.'/index.php/download_file/', $content);
		if($this->post('template_id') && $this->post('template_id')!=0){			
			if($this->post('html_sidecolumn')!=null){
				$scontent = str_replace(BASE_URL . DIR_REL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_sidecolumn'));
				$scontent = str_replace('/index.php/download_file/',BASE_URL . DIR_REL.'/index.php/download_file/', $scontent);
				
				$opts['content'] = array('html_main'=>$content, 'html_sidecolumn'=>$scontent);
			}else{
				$opts['content'] = array('html_main'=>$content);
			}
		}else{
			$content = str_replace(BASE_URL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_content'));
			$content = str_replace('/index.php/download_file/',BASE_URL.'/index.php/download_file/', $content);

			$opts['content'] = array('html'=>$content);
		}
		
		
		//if($this->post('html_side')){
		//	$content_side = str_replace(BASE_URL . DIR_REL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_side'));
		//	$content_side = str_replace('/index.php/download_file/',BASE_URL . DIR_REL.'/index.php/download_file/', $content_side);
		//	$opts['content'] = array('html_main'=>$content, 'html_sidecolumn'=>$content_side);
		//	}
		
		//if($this->post('displaying')=='full'){
		//	$opts['content'] = array('html'=>$content);
		//}
		
		
		//var_dump($opts['content']);
		//exit;
		
		//if($this->post('template')!=null){
		//	$opts['template_id'] = $this->post('template');
		//}
	
		
		foreach($opts as $key=>$value){

			$retval = $api->campaignUpdate($id, $key, $value);
		}
		
		if ($api->errorCode){
			$error= $api->errorMessage;
			$this->redirect('/dashboard/mail_monkey/campaigns/edit/editing_campaign/'.$id.'/'.$this->post('subject').'/'.$error.'/');
		}
		
		$this->redirect('/dashboard/mail_monkey/campaigns/');
	
	}
	
}