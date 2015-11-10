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
                        <a href="/materials/index.html"><?=  Yii::$app->params['pages']['materials']; ?></a>
                    </li>
					<li <?php if(Yii::$app->params['current_page'] == 'messages') echo "class=\"active\"" ?>>
                        <a href="/messages/index.html"><?=  Yii::$app->params['pages']['messages']; ?></a>
                    </li>
                    <li <?php if(Yii::$app->params['current_page'] == 'deadlines') echo "class=\"active\"" ?>>
                        <a href="/deadlines/index.html"><?=  Yii::$app->params['pages']['deadlines']; ?></a>
                    </li>
                    <li <?php if(Yii::$app->params['current_page'] == 'exams') echo "class=\"active\"" ?>>
                        <a href="/exams/index.html"><?=  Yii::$app->params['pages']['exams']; ?></a>
                    </li>
                    <li <?php if(Yii::$app->params['current_page'] == 'notes') echo "class=\"active\"" ?>>
                        <a href="/notes/index.html"><?=  Yii::$app->params['pages']['notes']; ?></a>
                    </li>
				</ul>
                <!--<div id="menu-sm" class="btn-group hidden-lg hidden-md hidden-xs">-->
                <div id="menu-sm" class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                      <?=  Yii::$app->params['pages'][Yii::$app->params['current_page']]; ?>
                      <span class="caret"></span>
                    </a>
                    <ul class="nav dropdown-menu">
                        <li <?php if(Yii::$app->params['current_page'] == 'index') echo "class=\"active\"" ?>>
                            <a href="/"><?=  Yii::$app->params['pages']['index']; ?></a>
                        </li>
    					<li <?php if(Yii::$app->params['current_page'] == 'materials') echo "class=\"active\"" ?>>
                            <a href="/materials/index.html"><?=  Yii::$app->params['pages']['materials']; ?></a>
                        </li>
    					<li <?php if(Yii::$app->params['current_page'] == 'messages') echo "class=\"active\"" ?>>
                            <a href="/messages/index.html"><?=  Yii::$app->params['pages']['messages']; ?></a>
                        </li>
                        <li <?php if(Yii::$app->params['current_page'] == 'deadlines') echo "class=\"active\"" ?>>
                            <a href="/deadlines/index.html"><?=  Yii::$app->params['pages']['deadlines']; ?></a>
                        </li>
                        <li <?php if(Yii::$app->params['current_page'] == 'exams') echo "class=\"active\"" ?>>
                            <a href="/exams/index.html"><?=  Yii::$app->params['pages']['exams']; ?></a>
                        </li>
                        <li <?php if(Yii::$app->params['current_page'] == 'notes') echo "class=\"active\"" ?>>
                            <a href="/notes/index.html"><?=  Yii::$app->params['pages']['notes']; ?></a>
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
                        <?php
                        if(count(Yii::$app->user->identity->degrees) > 1) {
                            foreach(Yii::$app->user->identity->degrees as $degree) {
                                $class = null;
                                $currentDegree = Yii::$app->session["currentDegree"];
                                if($currentDegree == $degree["degree"]){
                                    $class = "degree-selected";
                                }
                            ?>
                                <li><a class="option-degree <?php if($class != null) echo $class; ?>" href="<?= Yii::$app->homeUrl ?>" degree="<?= $degree["degree"]; ?>"><?= $degree["name"]; ?></a></li>
                        <?php }?>
                        <li class="divider"></li>
                        <?php }?>
                        <li><a class="post-link" href="logout.html"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
                  </ul>
                </div>
                
			</div>
            <div class="navbar-collapse" id="user-opcions">
                <ul class="nav navbar-nav navbar-links">
                    <?php
                    if(count(Yii::$app->user->identity->degrees) > 1) {
                        foreach(Yii::$app->user->identity->degrees as $degree) {?>
                            <li><a class="option-degree" href="<?= Yii::$app->homeUrl ?>" degree="<?= $degree["degree"]; ?>"><?= $degree["name"]; ?></a></li>
                    <?php }}?>
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
