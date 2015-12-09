<div ng-controller="studentController">
	<div id="main-student" ng-show="exams.length > 0">
		<h2>Examenes Previstos</h2>
		<ul class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
			<li class="exam-list {{ item.open == 1? 'to-do' : '' }}" ng-repeat="item in exams" ng-click="doExam(item)">
				<div class="exam-list-header {{ item.open == 1? 'to-do' : '' }}">
					<span class="title"> {{ item.subject }} <label class="to-do">{{ item.open == 1? ' [ABIERTO] ' : '' }} </label> </span>
					<span class="date"> {{ item.start }} </span>
					<span class="glyphicon glyphicon-chevron-down triangle" aria-hidden="true" ng-show="item.open == 0" ng-click="showContent($event)"></span>
				</div>
				<div class="exam-list-content" ng-style="item.studentQuestions == 1? {'padding-top': '0px'} : {'padding-top': '30px'}">
					<div class="buttons-container">
						<button class="btn btn-questions" ng-click="propouseQuestions(item)" ng-show="item.studentQuestions == 1">Proponer preguntas</button>	
					</div>
					<div class="content">
						<p><span class="bold">Duración:</span> {{ item.duration }}</p>
						<p><span class="bold">Nº de preguntas:</span> {{ item.numQuestions }}</p>
						<div ng-bind-html="item.description | html"></div>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div id="no-exams" ng-hide="exams.length > 0">
		<div>
			<h3>No tienes programado ningún exámen</h3>
		</div>
	</div>
</div>