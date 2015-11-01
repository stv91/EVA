<?php 
namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
 
class Utils extends Component {

	public function makeDirs($dirpath, $mode=0777) {
   		return is_dir($dirpath) || mkdir($dirpath, $mode, true);
	}

	public function stdPath($path){
		if(PHP_OS == "Windows" || PHP_OS == "WINNT")
			return str_replace('/', '\\', $path);
		else
			return str_replace('\\', '/', $path);
	}

	function normalize ($cadena){
	    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	    $modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYBsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	    $cadena = utf8_decode($cadena);
	    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	    $cadena = strtolower($cadena);
	    return utf8_encode($cadena);
	}
}