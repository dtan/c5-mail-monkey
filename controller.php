<?php  

defined('C5_EXECUTE') or die(_("Access Denied."));

class MailMonkeyPackage extends Package {

	protected $pkgHandle = 'mail_monkey';
	protected $appVersionRequired = '5.3.0';
	protected $pkgVersion = '1.1.6';
	
	public function getPackageDescription() {
		return t("A MailChimp Integration");
	}
	
	public function getPackageName() {
		return t("Mail Monkey");
	}
	
	public function install() {
		$pkg = parent::install();
		
		//install blocks
	  	BlockType::installBlockTypeFromPackage('monkey_block', $pkg);	
		
		$this->load_required_models();
		
		// install pages
		$cp = SinglePage::add('/dashboard/mail_monkey/', $pkg);
		$cp->update(array('cName'=>t('Mail Monkey'), 'cDescription'=>t('A MailChimp Integration')));
		SinglePage::add('/dashboard/mail_monkey/lists/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/lists/edit/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/groups/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/groups/edit/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/templates/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/templates/edit/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/campaigns/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/campaigns/edit/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/settings/', $pkg);
		SinglePage::add('/dashboard/mail_monkey/help/', $pkg);
	}
	
	function load_required_models() {
		Loader::model('single_page');
		Loader::model('collection');
		Loader::model('page');
		loader::model('block');
		Loader::model('collection_types');
		Loader::model('/attribute/categories/collection');
		Loader::model('/attribute/types/select/controller');
	}	
}
?>