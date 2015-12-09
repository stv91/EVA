<div ng-controller="teacherController">
	<div id="main-student" ng-show="exams.length > 0">
		<h2>Examenes Previstos</h2>
		<div class="buttons-container">
			<button class="btn btn-questions btn-add-exam"  ng-click="editExam(item)">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Nuevo examen
			</button>
		</div>
		<h3 class="subtitle col-lg-offset-1 col-md-offset-1">Listado de exámenes</h3>
		<ul class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
			<li class="exam-list" ng-repeat="item in exams" ng-click="doExam(item)">
				<div class="exam-list-header">
					<span class="title"> {{ item.subject }}</span>
					<span class="date"> {{ item.start }} </span>
					<span class="glyphicon glyphicon-chevron-down triangle" aria-hidden="true" ng-click="showContent($event)"></span>
				</div>
				<div class="exam-list-content" style="padding-top: 0">
					<div class="buttons-container">
						<button class="btn btn-questions" ng-click="manageQuestions(item)">
							<span class="glyphicon glyphicon-plus-sign"></span>
							Gestionar preguntas
						</button>
						<button class="btn btn-questions" ng-click="editExam(item)">
							<span class="glyphicon glyphicon-edit"></span>
							Editar
						</button>
						<button class="btn btn-questions" ng-click="updateDeleteItem(item)" data-toggle="modal" data-target="#modal-confrim">
							<span class="glyphicon glyphicon-remove-sign"></span>
							Eliminar
						</button>
					</div>
					<div class="content">
						<p><span class="bold">Duración:</span> {{ item.duration }}</p>
						<p><span class="bold">Nº de preguntas:</span> {{ item.numQuestions }}</p>
						<div style="font-weight: normal" ng-bind-html="item.description | html"></div>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div id="no-exams" ng-hide="exams.length > 0">
		<div>
			<h3>No tienes programado ningún exámen</h3>
			<button class="btn btn-questions"  ng-click="editExam(item)">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Nuevo examen
			</button>
		</div>
	</div>
	<div ng-class="modalClass" id="modal-confrim" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Eliminar Examen</h4>
				</div>
				<div class="modal-body">
					<p>¿Estás seguro que deseas eliminar este exámen ya programado?</p>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" ng-click="deleteExam()">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						Aceptar
					</button>
					<button class="btn" data-dismiss="modal" ng-click="updateDeleteItem(null)">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						Cancelar
					</button>
				</div>
			</div>
		</div>
	</div>
</div>