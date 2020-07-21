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


class DocumentController extends Controller
{
    
    public $modelClass;
    public $path = '/document/';
    public $host = 'http://yii-application';
    public $file = 'statement1.html';
    public $numberType = 2;
    public $typeOfFile;
    public $numberDate = 1;
    public $numberProfit = 13;
    public $nameBalance = "balance";
    public $nameBuy = "buy";
    public $nameBuyStop = "buy stop";
    public function actionAddParam() 
    {
        $model = new Document();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('saveexit')) {
                return $this->redirect(['index']);
            }   else {
                return $this->redirect(['update', 'id' => $model->id]);
            }   
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
   
    public function actionUpload()
    {      
        $modelFile = new UploadForm();
       // $modelObj = $this->findModel($id); // Product, Field and other
        if (Yii::$app->request->isPost) {
            $modelFile->imageFile = UploadedFile::getInstance($modelFile, 'imageFile');
            if ($modelFile->upload($this->path)) {
                $this->file = $modelFile->imageFile->name; //statement1.html
               
                //$modelObj->image = $name;
               // $modelObj->save();
                return json_encode(['result' => 'true']);
            }
        }
        return $this->render('upload', [
            'model' => $modelFile, 
            
        ]); 
    }


    public function actionConvertDocInStr() {
        $uploaddir = "../web" . $this->path;
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir, 0755, true);
        }
        $ourFile = $uploaddir.$this->file;
        
        $text = '';
        if ($handle = fopen($ourFile,"r+")){ 
            while (!feof($handle)){ 
                $string = fgets($handle, 4096);
                $text = $text . $this->convertToUtf8($string); 
            } 
            fclose($handle); 
        } 
        //echo $text;
        $htmlDoc = $this->getHtmlDoc($text);
        $nodesWithTable = $this->getNodes($htmlDoc, "table");
        $table = $nodesWithTable->item(0);
        $nodesWithTr = $this->getNodes($table, "tr");
        $arrDate = [];
        $arrProfit = [];
        foreach ($nodesWithTr as $nodeWithTr) {
            $nodesWithTd = $this->getNodes($nodeWithTr, "td");
            $typeOperation = $nodesWithTd->item($this->numberType)->nodeValue;
            if ($typeOperation !==  $this->nameBuyStop) { //3 ittem2
                //foreach ($nodesWithTd as $nodeWithTd) {
                    //var_dump($nodesWithTd->item(1));
                    $nodeDate = $nodesWithTd->item($this->numberDate);
                    if ($nodeDate !== null) {
                        if ($nodeDate->getAttribute('class') == "msdate") {
                            $arrDate[] = $nodeDate->nodeValue;
                        }
                    }
                    if ($typeOperation == $this->nameBalance) {
                        if ($nodesWithTd->item(4) !== null) {
                            $arrProfit[] = (float)$nodesWithTd->item(4)->nodeValue;
                        }
                    } 
                    if ($typeOperation == $this->nameBuy) {
                        $nodeProfit = $nodesWithTd->item($this->numberProfit);
                        if ($nodeProfit !== null) {
                           $arrProfit[] = (float)$nodeProfit->nodeValue;
                        }
                    }
                    
                    
                    // if ($nodeWithTd->getAttribute('class') == "msdate") {
                    //     //var_dump($nodeWithTd->nodeValue);
                    //     $arrDate[] = $nodeWithTd->nodeValue;
                    // }
                    // if ($nodeWithTd->getAttribute('class') == "mspt") {
                    //     //echo $nodeWithTd->nodeValue;
                    //     $arrProfit[] = $nodeWithTd->nodeValue;
                    // }
                //}
            } 

        }
        //var_dump($arr);
        return $this->render('graf', [
            'arrDate' => $arrDate, 
            'arrProfit' => $arrProfit
        ]); 
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

