<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;
use Yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $css = [];
    public $js = [];
    
    private function setJsFiles(){
        $path = new \RecursiveDirectoryIterator(Yii::$app->basePath . '\web\js\common');
        $iter = new \RecursiveIteratorIterator($path);
        $regexIter = new \RegexIterator($iter, '/^.+\.js$/i', \RecursiveRegexIterator::GET_MATCH);
        foreach($regexIter as $name => $object){
           $file = str_replace(Yii::$app->basePath . '\\web\\', '', $name);
            array_push($this->js, str_replace('\\', '/', $file));
        }
        if(file_exists ( Yii::$app->basePath.'\\web\\js\\controllers\\' . Yii::$app->params['current_page'] . 'Controller.js' ))
            array_push($this->js, 'js/controllers/' . Yii::$app->params['current_page'] . 'Controller.js');
    }
    
    private function setCssFiles(){
        $path = new \RecursiveDirectoryIterator(Yii::$app->basePath . '\web\css\common');
        $iter = new \RecursiveIteratorIterator($path);
        $regexIter = new \RegexIterator($iter, '/^.+\.css$/i', \RecursiveRegexIterator::GET_MATCH);
        foreach($regexIter as $name => $object){
            $file = str_replace(Yii::$app->basePath . '\\web\\', '', $name);
            array_push($this->css, str_replace('\\', '/', $file));
        }
        $path = Yii::$app->basePath.'\\web\\css\\'. Yii::$app->params['current_page'];
        if(file_exists ( $path  . '\\layout.css' ))
            array_push($this->css, 'css/' . Yii::$app->params['current_page'] . '/layout.css');
        if(file_exists ( $path . '\\style.css' ))
            array_push($this->css, 'css/' . Yii::$app->params['current_page'] . '/style.css');
    }
    
    function __construct() {
        $this->setJsFiles();
        $this->setCssFiles();
    }
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
