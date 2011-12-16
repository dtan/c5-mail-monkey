<?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardMailMonkeyCampaignsController extends Controller {
	public function on_start() {
		$html = Loader::helper('html');
		//$this->addHeaderItem($html->css('jquery_yellow.ui.css','mail_monkey'));
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
	
	public function view_campaign($campaignId){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		$this->set('track_monkey',$pkg->config('TRACK_MONKEY'));
		$this->set('campaign_overview',true);
		
		$apikey = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($apikey);
		
		$opt['campaign_id'] = $campaignId;
		$campaign = $api->campaigns($opt);
		$campaign = $campaign['data'][0];
		$this->set('campaign',$campaign);

		$pkt = Loader::helper('concrete/urls');
		$url= $pkt->getPackageURL($pkg);

		$this->addHeaderItem('<script type="text/javascript" src="'.$url.'/tools/jquery.dataTables.js"></script>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$url.'/tools/demo_table_jui.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$url.'/tools/demo_page.css"/>');
		$this->addHeaderItem('<link rel="stylesheet" type="text/css" href="'.$url.'/tools/jquery.jqplot.css"/>');
		$this->addHeaderItem('
		  <script language="javascript" type="text/javascript" src="'.$url.'/tools/jquery.jqplot.js"></script>
  		  <script language="javascript" type="text/javascript" src="'.$url.'/tools/plugins/jqplot.pieRenderer.min.js"></script>');
	}
	
	public function create_campaign($error=null){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		$this->set('create',true);
		loader::model('monkeysee','mail_monkey');
		
		if($error != null){
			$error = array('message'=>$error);
			$this->set('error',$error);
		}
	}
	
	public function campaign_build(){
		loader::model('monkeysee','mail_monkey');
		$pkg = Package::getByHandle('mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$tweet_monkey = $pkg->config('TWEET_MONKEY');
		$track_monkey = $pkg->config('TRACK_MONKEY');
		$api = new MCAPI($apikey);

		$sort_subs = $this->post('group');
		
		if(is_array($sort_subs)){
			foreach($sort_subs as $key=>$values){
				$grouping_ID = $key;
				$interest = 'interests-'.$grouping_ID;
				$group_num_size = strlen($grouping_ID);
				//echo $grouping_ID.' : processed ,';
				$value_text ='';
				$i = 0;
				foreach($values as $value){
					if($i){$value_text .= ', ';}
					$value_text .= $value;
					$i++;
					//echo $group_name.' : processed ,';
				}
				$conditions[] = array('field'=>$interest, 'op'=>'all', 'value'=>$value_text);
			}
		}

		if(!empty($conditions)){
			$seg = array('match'=>'all', 'conditions'=>$conditions);
		}else{
			$seg = null;
		}
	    
	    if ($api->errorCode){
			echo $api->errorMessage;
		}
		
		$type = 'regular';
		
		if($tweet_monkey==1){
			$opts['analytics'] = 'google';
			$opts['analytics_tag'] = $this->post('subject').'_'.date('Y-m-d');
		}else{
			$opts['analytics'] = 'N';
		}
		
		if($track_monkey==1){
			$opts['auto_tweet'] = true;
		}else{
			$opts['auto_tweet'] = false;
		}
		
		$opts['list_id'] = $this->post('listId');
		$opts['subject'] = $this->post('subject');
		$opts['from_email'] = $this->post('frommail'); 
		$opts['from_name'] = $this->post('fromname');
		$opts['generate_text'] = true;
		$opts['auto_footer'] = true;
		$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);
		
		$opts['authenticate'] = true;
		
		if($this->post('template')!='none'){
			
			$opts['template_id'] = $this->post('template');
					
			$content_pre = str_replace(BASE_URL . DIR_REL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_main'));
			$content_pre = str_replace('/index.php/download_file/',BASE_URL . DIR_REL.'/index.php/download_file/', $content_pre);	
			if($this->post('html_sidecolumn')!=null){
				$scontent_pre = str_replace(BASE_URL . DIR_REL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_sidecolumn'));
				$scontent_pre = str_replace('/index.php/download_file/',BASE_URL . DIR_REL.'/index.php/download_file/', $scontent_pre);
				
				$content = array('html_main'=>$content_pre, 'html_sidecolumn'=>$scontent_pre);
			}else{
				$content = array('html_main'=>$content_pre);
			}
		}else{
			$content = str_replace(BASE_URL.'/index.php/download_file/','/index.php/download_file/', $this->post('html_content'));
			$content = str_replace('/index.php/download_file/',BASE_URL.'/index.php/download_file/', $content);

			$content = array('html'=>$content);
		}

		$retval = $api->campaignCreate($type, $opts, $content,$seg);
		
		if ($api->errorCode){
			$error= $api->errorMessage;
			$this->redirect('/dashboard/mail_monkey/campaigns/create_campaign/'.$error.'/');
		}
		
		$this->redirect('/dashboard/mail_monkey/campaigns/');
	}
	
	
	
	public function campaign_delete($id){
		$pkg = Package::getByHandle('mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		loader::model('monkeysee','mail_monkey');
		$api = new MCAPI($apikey);

		$retval = $api->campaignDelete($id);
		
		$this->redirect('/dashboard/mail_monkey/campaigns/');
	}
	
	
	public function copy_campaign($id){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$retval = $api->campaignReplicate($id);
		
		$this->redirect('/dashboard/mail_monkey/campaigns/');
	
	}
	
	public function schedule_form($id,$error=null){
		$pkg = Package::getByHandle('mail_monkey');
		$this->set('chimp_key',$pkg->config('CHIMP_KEY'));
		$this->set('schedule',true);
		$this->set('title',$title);
		$this->set('campaignId',$id);
		loader::model('monkeysee','mail_monkey');
		
		if($error != null){
			$error = array('message'=>$error);
			$this->set('error',$error);
		}
	}
	
	public function set_schedule(){
		$pkg = Package::getByHandle('mail_monkey');
		$dt = Loader::helper('form/date_time');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		//2018-04-01 09:05:21
		$date = date('Y-m-d H:i:s',strtotime($dt->translate('date_do')));
		$id = $this->post('id');

		$retval = $api->campaignSchedule($id, $date);
		
			if ($api->errorCode){
				$error= $api->errorMessage;
				$this->redirect('/dashboard/mail_monkey/campaigns/schedule_form/'.$id.'/'.$error.'/');
			}else{
				$this->redirect('/dashboard/mail_monkey/campaigns/');
			}
	}
	
	public function unschedule_campaign($id){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$retval = $api->campaignUnschedule($id);
		
		$this->redirect('/dashboard/mail_monkey/campaigns/');
	}
	
	public function pause_campaign($id){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$retval = $api->campaignPause($id);

		$this->redirect('/dashboard/mail_monkey/campaigns/view_campaign/'.$id.'/');
	}
	
	public function resume_campaign($id){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$retval = $api->campaignResume($id);
		
		$this->redirect('/dashboard/mail_monkey/campaigns/view_campaign/'.$id.'/');
	}
	
	public function campaign_sendnow($id){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$retval = $api->campaignSendNow($id);
		
		$this->redirect('/dashboard/mail_monkey/campaigns/');
	}
	
	public function share($id){
		$pkg = Package::getByHandle('mail_monkey');
		loader::model('monkeysee','mail_monkey');
		$apikey = $pkg->config('CHIMP_KEY');
		$api = new MCAPI($apikey);
		
		$opts['to_email'] = $this->post('email');
		
		$retval = $api->campaignShareReport($id,$opts);
		
		$this->redirect('/dashboard/mail_monkey/campaigns/view_campaign/'.$id.'/');
		
	}
}