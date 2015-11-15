<div ng-controller="createQuestionController">
	<script> 
		var exam = <?= $exam ?>; 
		var question = <?= isset($question)? $question : "null" ?>;
	</script>
	<h2 class="title-center title-create-questions"><?= $title ?></h2>
	<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
		<div class="form-group">
			<label for="pregunta">Pregunta</label>
			<div class="form-value">
				<div ng-show="form.question.text=='' && !form.question.editing" class="clickable-text" ng-click="edit(form.question, 0)"> 
					<p>Pulsa para escribir la pregunta </p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>
				<div ng-hide="form.question.editing || form.question.text==''" class="clickable-text" ng-click="edit(form.question, 0)">
					<p>{{ form.question.text }}</p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>
				<textarea class="form-control" placeholder="Escribe aquí tu pregunta" ng-model="form.question.text" ng-blur="edited(form.question, 0)" focus="form.question.editing"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="pregunta">Respuesta correcta</label>
			<div class="form-value">	
				<div ng-show="form.correctAnswer.text=='' && !form.correctAnswer.editing" class="clickable-text" ng-click="edit(form.correctAnswer, 1)"> 
					<p>Pulsa para escribir la respuesta correcta </p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>
				<div ng-hide="form.correctAnswer.editing || form.correctAnswer.text==''" class="clickable-text" ng-click="edit(form.correctAnswer, 1)">
					<p>{{ form.correctAnswer.text }}</p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>
				<textarea class="form-control" placeholder="Escribe aquí la respuesta correcta" ng-model="form.correctAnswer.text" ng-blur="edited(form.correctAnswer, 1)" focus="form.correctAnswer.editing"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="pregunta">Otras respuestas</label>
			<div class="form-value">
				<div ng-show="addAnswer" class="clickable-text" ng-click="edit()">
					<p>Pulsa para escribir otra respuesta </p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>

				<div ng-hide="form.answer1.editing || form.answer1.text==''" class="clickable-text"class="clickable-text" ng-click="edit(form.answer1, 2)">
					<p>{{ form.answer1.text }}</p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>
				<textarea class="form-control" placeholder="Escribe aquí tu pregunta" ng-model="form.answer1.text" ng-blur="edited(form.answer1, 2)" focus="form.answer1.editing"></textarea>

				<div ng-hide="form.answer2.editing || form.answer2.text==''" class="clickable-text" ng-click="edit(form.answer2, 3)">
					<p>{{ form.answer2.text }}</p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>
				<textarea class="form-control" placeholder="Escribe aquí tu pregunta" ng-model="form.answer2.text" ng-blur="edited(form.answer2, 3)" focus="form.answer2.editing"></textarea>

				<div ng-hide="form.answer3.editing || form.answer3.text==''" class="clickable-text" ng-click="edit(form.answer3, 4)">
					<p>{{ form.answer3.text }}</p>
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</div>
				<textarea class="form-control" placeholder="Escribe aquí tu pregunta" ng-model="form.answer3.text" ng-blur="edited(form.answer3, 4)" focus="form.answer3.editing"></textarea>
			</div>
		</div>
		<div class="btn-container">
			<button class="btn btn-questions btn-questions-extra" ng-click="sendQuestion()"> Enviar </button>
		</div>

	</div>
</div>