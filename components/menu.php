<?php 
namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
 
class Menu extends Component
{
	public $guess = [];
	public $teacher = [];
	public $student = [];
}