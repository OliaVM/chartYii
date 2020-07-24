<?php

namespace frontend\models;

use yii\base\Model;
use Yii;

class Document extends Model
{
    public $typeOfFile;
    public $numberType;
    public $numberDate;
    public $numberProfit;
    public $nameBalance;
    public $nameBuyStop;
    public $nameBuy;
    public $nameSell;


    public function rules()
    {
        return [
            [['typeOfFile', 'numberType', 'numberDate', 'numberProfit', 'nameBalance', 'nameBuyStop', 'nameBuy', 'nameSell'], 'required'],
            ['typeOfFile', 'string', 'max' => 30],
            [['numberType', 'numberDate', 'numberProfit'], 'integer', 'max' => 30],
            [['nameBalance', 'nameBuyStop', 'nameBuy', 'nameSell'], 'string', 'max' => 60],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'typeOfFile' => 'Формат файла',
            'numberType' => 'Номер колонки, содержащей type',
            'numberDate' => 'Номер колонки, содержащей open time',
            'numberProfit' => 'Номер колонки, содержащей profit',
            'nameBalance' => 'Название операции типа balance',
            'nameBuyStop' => 'Название операции типа buy stop',
            'nameBuy' => 'Название операции типа buy',
            'nameSell' => 'Название операции типа sell'
        ];
    }


    public function convertDocInStr($path) {
        $session = Yii::$app->session;
        $file = $session->get('file');

        $uploaddir = "../web" . $path;
        $fileWithPath = $uploaddir . $file;
        if (!file_exists($fileWithPath)) {
            throw new \Exception("File does not exist");
        }

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
        if ($htmlDoc->loadHTML($html) == false) {
            throw new \Exception("Error load html");
        }
        return $htmlDoc;
    }
    
    public function getXmlDoc($xml) {
        $doc = new \DOMDocument();
        if ($doc->loadXML($xml) == false) {
            throw new \Exception("Error load xml");
        }
        return $doc->saveXML();   
    }

    public function getNodes($doc, $tag) {
        $nodes = $doc->getElementsByTagName($tag);
        return $nodes;
    }

}