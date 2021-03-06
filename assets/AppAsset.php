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

class AppAsset extends AssetBundle {
	public $basePath  = '@webroot';
	public $baseUrl   = '@web';
	public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
	public $css       = [];
	public $js        = [];

	private function getSortedFiles($iterator) {
		$aux   = iterator_to_array($iterator);
		$files = [];
		foreach ($aux as $f) {
			array_push($files, $f[0]);
		}
		sort($files);
		return $files;
	}

	private function getJsFiles($baseDir) {
		$files   = [];
		$stdPath = Yii::$app->utils->stdPath(Yii::$app->basePath."/web/js/$baseDir");
		if (file_exists($stdPath)) {
			$dirIter   = new \RecursiveDirectoryIterator($stdPath);
			$iter      = new \RecursiveIteratorIterator($dirIter);
			$regexIter = new \RegexIterator($iter, '/^.+\.js$/i', \RecursiveRegexIterator::GET_MATCH);

			foreach ($this->getSortedFiles($regexIter) as $name) {
				$path = str_replace('\\', '/', Yii::$app->basePath.'/web/');
				$file = str_replace($path, '', str_replace('\\', '/', $name));
				array_push($files, $file);
			}
		}
		return $files;
	}

	private function setJsFiles() {
		$this->js = $this->getJsFiles("common");
		$this->js = array_merge($this->js, $this->getJsFiles("controllers/".Yii::$app->params['current_page']));
	}

	private function setCssFiles() {
		$path      = new \RecursiveDirectoryIterator(Yii::$app->utils->stdPath(Yii::$app->basePath.'\web\css\common'));
		$iter      = new \RecursiveIteratorIterator($path);
		$regexIter = new \RegexIterator($iter, '/^.+\.css$/i', \RecursiveRegexIterator::GET_MATCH);
		foreach ($regexIter as $name => $object) {
			$path = str_replace('\\', '/', Yii::$app->basePath.'/web/');
			$file = str_replace($path, '', str_replace('\\', '/', $name));
			array_push($this->css, $file);
		}
		$path = Yii::$app->basePath.'\\web\\css\\'.Yii::$app->params['current_page'];
		if (file_exists(Yii::$app->utils->stdPath($path.'\\layout.css'))) {
			array_push($this->css, 'css/'.Yii::$app->params['current_page'].'/layout.css');
		}

		if (file_exists(Yii::$app->utils->stdPath($path.'\\style.css'))) {
			array_push($this->css, 'css/'.Yii::$app->params['current_page'].'/style.css');
		}
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
