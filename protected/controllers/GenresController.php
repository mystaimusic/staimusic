<?php

class GenresController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','viewBandsPerGenres','viewRandomBandsPerGenres'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	
	public function actionViewBandsPerGenres($genid,$genImagePath,$genDescription)
    {
    	$genre=Genres::model()->findByPk($genid);
    	$bands=$genre->bands;
    	$tags=$genre->tags;
    	//$songs=$bands->songs;
    	
    	//echo Yii::trace(CVarDumper::dumpAsString("--------> sono in actionViewSongsPerGenres"),'vardump');
    	//echo Yii::trace(CVarDumper::dumpAsString($tags),'vardump');
    	/*foreach($bands as $band)
    	{
    		$bandId = $band->BANDID;
    		$songsModel = Songs::model()->findAllByAttributes(array('BANDID'=>$bandId));
        	echo Yii::trace(CVarDumper::dumpAsString($songsModel),'vardump');
    	}*/
    	
    	$this->render('/bands/selectedBand',array(
			'bands'=>$bands,
    		'tags'=>$tags,
			'fromGenres'=>true,
			'genImagePath'=>$genImagePath,
    		'genDescription'=>$genDescription,
    		'genreId'=>$genid,
		));
    	

    	//echo Yii::trace(CVarDumper::dumpAsString($songs),'vardump');        
    }
    
    public function actionViewRandomBandsPerGenres($genid,$genImagePath,$genDescription)
    {
    	echo Yii::trace(CVarDumper::dumpAsString("----------> sono in actionViewRandomBandsPerGenres"),'vardump');
    	$genre=Genres::model()->findByPk($genid);
    	$bandsDB=$genre->bands;
    	$tags=$genre->tags;
    	
    	echo Yii::trace(CVarDumper::dumpAsString(count($bandsDB)),'vardump');
    	//$randomBands = array_rand($input, 2);
    	//echo Yii::trace(CVarDumper::dumpAsString($randomBands),'vardump');
    	
    	$bandsIdStr='';
    	$max = count($bandsDB);
    	//$max = Bands::model()->count();
    	$bands = array();
    	$limitNum = max($max,15);
    	//$bandsIdArr = array_rand($bandsDB, $max);
    	echo Yii::trace(CVarDumper::dumpAsString($bandsDB),'vardump');
    	//foreach($bandsIdArr as $bandId) {
    	for($i =0; ($i<15 && $i<$max); $i++){
    		$bandId = array_rand($bandsDB, 1);
    		//echo Yii::trace(CVarDumper::dumpAsString($bandsId),'vardump');
    		//$bandId = $bandsIdArr[$i];
    		echo Yii::trace(CVarDumper::dumpAsString($bandId),'vardump');
    		$band = Bands::model()->findByPk($bandId);
    		echo Yii::trace(CVarDumper::dumpAsString($band),'vardump');
    		if(!is_null($band)) {
	    		$bands[$i] = $band;
	    		if($i==0){
	    			$bandsIdStr .= $band->BANDID;
	    		}else{
	    			$tmpVar = '#' . $band->BANDID;
	    			$bandsIdStr .= $tmpVar;
	    		}
    		}
    		unset($bandId);
    	}
    	
		$this->render('/bands/selectedBand',array(
			'bands'=>$bands,
    		'tags'=>$tags,
			'fromGenres'=>true,
			'genImagePath'=>$genImagePath,
    		'genDescription'=>$genDescription,
    		'genreId'=>$genid,
			'bandsIdStr'=>$bandsIdStr,
		));
    
    }
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Genres;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Genres']))
		{
			$model->attributes=$_POST['Genres'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->GENREID));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Genres']))
		{
			$model->attributes=$_POST['Genres'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->GENREID));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Genres');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Genres('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Genres']))
			$model->attributes=$_GET['Genres'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Genres the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Genres::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Genres $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='genres-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
