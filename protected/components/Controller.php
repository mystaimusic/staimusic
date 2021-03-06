<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function __construct($id,$module=null){
		parent::__construct($id,$module); 
		if (isset($_POST['_lang']))
		{
			$app = Yii::app();
			$user = $app->user;
			if($app->user->hasState('oldLang')){
				$oldLang = $app->user->getState('oldLang');
			}else{
				$oldLang = "en_us";
			}
			$currentUrl = $app->request->url;
			$newUrl = Utilities::translateUrl($currentUrl, $oldLang);
			if($newUrl!=null){
				$this->redirect($newUrl);
			}
		}else{
			$onurl = Yii::app()->request->url;
			$baseUrl = Yii::app()->request->baseUrl;
			//echo Yii::trace(CVarDumper::dumpAsString("-----------> sono in Controller"),'vardump');
			//echo Yii::trace(CVarDumper::dumpAsString($baseUrl),'vardump');
			if($onurl=="/"){
			//if(Utilities::endsWith($onurl,"index.php")){
				//echo Yii::trace(CVarDumper::dumpAsString("-----------> sono in Controller if statement"),'vardump');
				$langPref = Utilities::getLanguagePrefix(Yii::app()->language);
				$this->redirect(Yii::app()->getBaseUrl(true)."/".$langPref);
			}
		}
	}
	
	public function createMultilanguageReturnUrl($lang='en'){
		if (count($_GET)>0){
			$arr = $_GET;
			$arr['_lang']= $lang;
		}
		else
			$arr = array('_lang'=>$lang);
		return $this->createUrl('', $arr);
	}
}