<div ng-controller="studentController">
	<div id="main-student" ng-show="exams.length > 0">
		<h2>Examenes Previstos</h2>
		<ul class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
			<li class="exam-list" ng-repeat="item in exams">
				<div class="exam-list-header">
					<span class="title"> {{ item.subject }}  </span>
					<span class="date"> {{ item.start }} </span>
					<span class="glyphicon glyphicon-chevron-down triangle" aria-hidden="true" ng-click="showContent($event)"></span>
				</div>
				<div class="exam-list-content">
					<div class="content">
						<p><span class="bold">Duración:</span> {{ item.duration }}</p>
						<div ng-bind-html="item.description"></div>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div id="no-exams" ng-hide="exams.length > 0">
		<h3>No tienes programado ningún exámen</h3>
	</div>
</div>