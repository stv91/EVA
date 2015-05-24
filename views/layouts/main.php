<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <nav class="navbar navbar-default navbar-fixed-top ">
        <?php if (!Yii::$app->user->isGuest): ?>
		<div class="container-fluid">    
			<div class="navbar-header">  
				<button id="toggle-menu-btn" type="button" class="navbar-toggle collapsed">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>   
                <a class="navbar-brand" href="<?= Yii::$app->homeUrl ?>">
                    <span>EVA</span>
                    <span class="hidden-xs" >Entorno Virtual de Aprendizaje</span>
                </a>
				<a id="icon-user-mobile">
					<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
					<span id="user-name"><?= Yii::$app->user->identity->email ?></span>
				</a>
			</div>
			<div class="navbar-collapse" id="collapsable-links">
				<!--<ul class="hidden-sm nav navbar-nav navbar-links">-->
                <ul class="hidden-sm nav navbar-nav navbar-links">
					<li <?php if(Yii::$app->params['current_page'] == 'materials') echo "class=\"active\"" ?>>
                        <a href="materials.html">Materiales</a>
                    </li>
					<li <?php if(Yii::$app->params['current_page'] == 'messages') echo "class=\"active\"" ?>>
                        <a href="messages.html">Tutorías</a>
                    </li>
                    <li <?php if(Yii::$app->params['current_page'] == 'deadlines') echo "class=\"active\"" ?>>
                        <a href="deadlines.html">Entregas</a>
                    </li>
                    <li <?php if(Yii::$app->params['current_page'] == 'exams') echo "class=\"active\"" ?>>
                        <a href="exams.html">Examenes</a>
                    </li>
                    <li <?php if(Yii::$app->params['current_page'] == 'notes') echo "class=\"active\"" ?>>
                        <a href="notes.html">Calificaciones</a>
                    </li>
				</ul>
                <!--<div id="menu-sm" class="btn-group hidden-lg hidden-md hidden-xs">-->
                <div id="menu-sm" class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                       Home
                      <span class="caret"></span>
                    </a>
                    <ul class="nav dropdown-menu">
    					<li <?php if(Yii::$app->params['current_page'] == 'materials') echo "class=\"active\"" ?>>
                        <a href="materials.html">Materiales</a>
                        </li>
    					<li <?php if(Yii::$app->params['current_page'] == 'messages') echo "class=\"active\"" ?>>
                            <a href="messages.html">Tutorías</a>
                        </li>
                        <li <?php if(Yii::$app->params['current_page'] == 'deadlines') echo "class=\"active\"" ?>>
                            <a href="deadlines.html">Entregas</a>
                        </li>
                        <li <?php if(Yii::$app->params['current_page'] == 'exams') echo "class=\"active\"" ?>>
                            <a href="exams.html">Examenes</a>
                        </li>
                        <li <?php if(Yii::$app->params['current_page'] == 'notes') echo "class=\"active\"" ?>>
                            <a href="notes.html">Calificaciones</a>
                        </li>
    				</ul>
                </div>
                
                <div class="btn-group navbar-right hidden-xs">
                    <button id="user-opcions-btn" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span id="icon-user-web">
        					<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
        				</span>
                        <?= Yii::$app->user->identity->email ?> 
                        
                    </button>
                    <ul id="user-opcions-dd" class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a class="post-link" href="logout.html"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
                  </ul>
                </div>
                
			</div>
            <div class="navbar-collapse" id="user-opcions">
                <ul class="nav navbar-nav navbar-links">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li><a class="post-link" href="logout.html"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
              </ul>
            </div>
		</div>
        <?php else : ?>
            <a class="navbar-brand navbar-brand-center" href="<?= Yii::$app->homeUrl ?>">
                <span>EVA</span>
                <span class="hidden-xs" >Entorno Virtual de Aprendizaje</span>
            </a>
        <?php endif ?>
	</nav>

    <div class="container"  ng-app="EVA">
        <div id="alert-place" ng-hide="hideAlert"></div>
        <?= $content ?>
    </div>
    <!--</div>-->

    <!--<footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
