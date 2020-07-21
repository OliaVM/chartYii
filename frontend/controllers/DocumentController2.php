<?php

namespace frontend\controllers;

use dosamigos\grid\ToggleAction;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use frontend\models\UploadForm;
use frontend\models\Document;

class DocumentController extends Controller
{
    
    public $modelClass;
    public $path = '/document/';
    public $host = 'http://yii-application';
    // public $file; // = 'statement1.html';
    // public $numberType; // = 2;
    // public $typeOfFile;
    // public $numberDate = 1;
    // public $numberProfit = 13;
    // public $nameBalance = "balance";
    // public $nameBuy = "buy";
    // public $nameBuyStop = "buy stop";

    // public function init() {
    //     $this->model = new Document();
    // }

    public function actionIndex() 
    {
        $model = new Document();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //var_dump(Yii::$app->request->post('save'));
            if (Yii::$app->request->post('save') !== null) {
                //$this->numberType = $model->numberType - 1;
               
                //var_dump($this->model);
                $session = Yii::$app->session;
                $session->set('model', $model);
                return $this->redirect(['upload']);
            }  else {
                return $this->redirect(['index', 'model' => $model]);
            }   
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
   
    public function actionUpload()
    {   
        //$model;
        $modelFile = new UploadForm();
        if (Yii::$app->request->isPost) {
            $modelFile->documentFile = UploadedFile::getInstance($modelFile, 'documentFile');
            if ($modelFile->upload($this->path)) {
                $file = $modelFile->documentFile->name; //statement1.html
                $session = Yii::$app->session;
                $session->set('file', $file);
                return $this->redirect('chart'); 
            }
        }
        return $this->render('upload', [
            'model' => $modelFile, 
            
        ]); 
    }

    public function actionChart() {
        $text = $this->convertDocInStr();
        $htmlDoc = $this->getHtmlDoc($text);
        $nodesWithTable = $this->getNodes($htmlDoc, "table");
        $table = $nodesWithTable->item(0);
        $nodesWithTr = $this->getNodes($table, "tr");
        $arrDate = [];
        $arrProfit = [];
        
        $session = Yii::$app->session;
        $model = $session->get('model');

        foreach ($nodesWithTr as $nodeWithTr) {
            $nodesWithTd = $this->getNodes($nodeWithTr, "td");

            $typeOperation = $nodesWithTd->item($model->numberType - 1)->nodeValue;

            $nodeDate = $nodesWithTd->item($model->numberDate - 1);
            if ($typeOperation !== $this->nameBuyStop && $nodeDate !== null && ($typeOperation == $this->nameBalance || $typeOperation == $this->nameBuy)) {

                if ($typeOperation == $this->nameBalance) {
                    $nodeProfit = $nodesWithTd->item(4); //
                } 
                if ($typeOperation == $this->nameBuy) {
                    $nodeProfit = $nodesWithTd->item($this->numberProfit);
                }  
                if ($nodeProfit !== null) {
                    $arrProfit[] = (float)$nodeProfit->nodeValue;
                    $arrDate[] = $nodeDate->nodeValue;
                }   
            }    
        }

        return $this->render('graf', [
            'arrDate' => $arrDate, 
            'arrProfit' => $arrProfit
        ]); 
    }
       
    public function convertDocInStr() {
        $session = Yii::$app->session;
        $file = $session->get('file');

        $uploaddir = "../web" . $this->path;
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir, 0755, true);
        }
        $fileWithPath = $uploaddir . $file;
        
        $text = '';
        if ($handle = fopen($fileWithPath,"r+")){ 
            while (!feof($handle)){ 
                $string = fgets($handle, 4096);
                $text = $text . $this->convertToUtf8($string); 
            } 
            fclose($handle); 
        } 
        return $text;   
    }

    public function convertToUtf8($string) {
        $coding = mb_detect_encoding($string);
        if ($coding !== "UTF-8") {
            return iconv($coding, "UTF-8", $string); 
        } else {
            return $string;
        }
    }

    public function getHtmlDoc($html) {
        $htmlDoc = new \DOMDocument();
        $htmlDoc->loadHTML($html);
        return $htmlDoc;
    }

    public function getNodes($doc, $tag) {
        $nodes = $doc->getElementsByTagName($tag);
        return $nodes;
    }
    

   
}

