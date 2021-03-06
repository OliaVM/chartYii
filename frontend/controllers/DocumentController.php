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
    
    public $path = '/document/';

    public function actionIndex() 
    {
        $model = new Document();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->request->post('save') !== null) {
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
        $session = Yii::$app->session;
        if ($session->has('model') == false) {
            return $this->redirect('index'); 
        }
        $modelFile = new UploadForm();
        if (Yii::$app->request->isPost) {
            $modelFile->documentFile = UploadedFile::getInstance($modelFile, 'documentFile');
            if ($modelFile->upload($this->path)) {
                $file = $modelFile->documentFile->name; //file.html
                $session->set('file', $file);
                json_encode(['status' => true]);
                return $this->redirect('chart'); 
            }
        }
        return $this->render('upload', [
            'model' => $modelFile, 
            
        ]); 
    }

    public function actionChart() {
        $session = Yii::$app->session;
        if ($session->has('model') == false || $session->has('file') == false) {
            return $this->redirect('index'); 
        }
        try {
            $model = $session->get('model');
            $text = $model->convertDocInStr($this->path);
           
            
            if ($model->typeOfFile == 0) {
                $doc = $model->getHtmlDoc($text);
            } else {
                $doc = $model->getXmlDoc($text);    
            }
     
            $nodesWithTable = $model->getNodes($doc, "table");
            $table = $nodesWithTable->item(0);
            if ($table == null) {
                throw new \Exception("Eror file structure");
            }
            $nodesWithTr = $model->getNodes($table, "tr");
            $arrDate = [];
            $arrProfit = [];
            $arrBalance = [];
            $arrCheck = [];
            if ($nodesWithTr == null) {
                throw new \Exception("Eror file structure");
            }
            foreach ($nodesWithTr as $nodeWithTr) {
                $nodesWithTd = $model->getNodes($nodeWithTr, "td");
                
                $typeOperation = $nodesWithTd->item($model->numberType - 1)->nodeValue;
                $nodeDate = $nodesWithTd->item($model->numberDate - 1);

                $profit = 0;
                $a = ($typeOperation == $model->nameBalance || $typeOperation == $model->nameBuy || $typeOperation == $model->nameSell);
                if ($typeOperation !== $model->nameBuyStop && $nodeDate !== null && $a) {

                    if ($typeOperation == $model->nameBalance) {
                        $nodeProfit = $nodesWithTd->item(4); 
                        if ($nodesWithTr->item(3) === $nodeWithTr) {
                            $balance = (float)(str_replace(' ', '', $nodeProfit->nodeValue));
                            $balanceChange = (float) 0;
                        } else {
                            $balanceChange = (float)(str_replace(' ', '', $nodeProfit->nodeValue));
                        }
                    } 

                    if ($typeOperation == $model->nameBuy) {
                        $nodeProfit = $nodesWithTd->item($model->numberProfit - 1);
                        $balanceChange = (float)(str_replace(' ', '', $nodeProfit->nodeValue));
                    }  

                    if ($typeOperation == $model->nameSell) {
                        $nodeProfit = $nodesWithTd->item($model->numberProfit - 1);
                        $balanceChange = (float)(str_replace(' ', '', $nodeProfit->nodeValue));
                    } 

                    if ($nodeProfit !== null) {
                        //$arrProfit[] = (float)$nodeProfit->nodeValue;
                        //$profit = (float)$nodeProfit->nodeValue;
                        $balance = $balance + $balanceChange;
                        $arrBalance[] = $balance;
                        $arrDate[] = $nodeDate->nodeValue;
                    }   
                }    
            }
    
        } catch(\Exception $e) {
            return $this->render('graf', [
                'error' => $e->getMessage()
            ]);
        }  

        return $this->render('graf', [
            'arrDate' => $arrDate, 
            'arrProfit' => $arrProfit,
            'arrBalance' => $arrBalance,
            'arrCheck' => $arrCheck
        ]); 
    }       
   
}

