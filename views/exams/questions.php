<div ng-controller="questionsController">
	<script> var examID = <?= $exam ?>; </script>
	<div class="all-questions" ng-show="questions.length > 0">
		<h2>Preguntas Registradas</h2>
		<div class="buttons-container">
			<button class="btn btn-questions btn-add-exam"  ng-click="addQuestion()">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Nueva pregunta
			</button>
		</div>
		<h3 class="subtitle col-lg-offset-1 col-md-offset-1">Listado de preguntas</h3>
		<ul class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
			<li ng-repeat="question in questions" class="question">
				<div class="is-valid">
					{{ question.validated == 1? "VALIDADA" : "NO VALIDADA"}}
				</div>
				<div class="action-buttons">
					<button class="btn btn-questions" ng-click="editQuestion(question)">
						<span class="glyphicon glyphicon-edit"></span>
					</button>
					<button class="btn btn-questions" ng-click="updateQuestionToDelete(question)" data-toggle="modal" data-target="#modal-confrim" style="color:#c17977;">
						<span class="glyphicon glyphicon-remove-sign"></span>
					</button>
				</div>
				<div>
					<h5>Pregunta</h5>
					<p class="question-value">{{ question.question }}</p>
				</div>
				<div>
					<h5>Respuesta Correcta</h5>
					<ul>
						<li class="question-value">{{ question.correct_answer }}</li>
					</ul>
				</div>
				<div>
					<h5>Otras Respuestas</h5>
					<ul class="other-answer">
						<li class="question-value">{{ question.answer1 }}</li>
						<li class="question-value" ng-show="question.answer2">{{ question.answer2 }}</li>
						<li class="question-value" ng-show="question.answer3">{{ question.answer3 }}</li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
	<div class="no-questions" ng-hide="questions.length > 0">
		<p>Aun no hay ninguna pregunta</p>
		<div class="buttons-container">
			<button class="btn btn-questions">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Añadir pregunta
			</button>
		</div>
	</div>

	<div ng-class="modalClass" id="modal-confrim" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Finalizar</h4>
					</div>
					<div class="modal-body">
						<p>¿Estás seguro que deseas eliminar esta pregunta?</p>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" ng-click="deleteQuestion()">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							Aceptar
						</button>
						<button class="btn" data-dismiss="modal" ng-click="updateQuestionToDelete(null)">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							Cancelar
						</button>
					</div>
				</div>
			</div>
		</div>
</div>