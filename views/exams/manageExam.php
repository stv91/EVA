<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<div ng-controller="manageExamController" after-load>
	<script type="text/javascript"> var examID = <?= $exam ?>;</script>
	<h2 class="title-center title-manageExam"><?= $title ?></h2>
	<form id="form-manageExam" class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
		<div class="form-group">
			<label for="subject">Asignatura</label>
			 <select name="mySelect" id="mySelect" class="form-control"
		      ng-options="subject.name for subject in subjects track by subject.code"
		      ng-model="exam.subject"></select>
		</div>
		<div class="form-group">
			<label for="date">Fecha</label>
			<input type="text" class="form-control" id="date" ng-model="exam.date" placeholder="dd/mm/yyyy" ng-keyup="limit($event, 10)">
		</div>
		<div class="form-group">
			<label for="time">Hora de incio</label>
			<input type="text" id="time" ng-model="exam.startTime" placeholder="hh:mm">
		</div>
		<div class="form-group">
			<label for="duration">Duración</label>
			<input type="text" id="duration" ng-model="exam.duration" placeholder="hh:mm">
		</div>
		<div class="form-group">
			<label for="num-questions">Número de preguntas</label>
			<input type="text" id="num-questions" ng-model="exam.numQuestions" ng-keyup="limit($event, 3)">
		</div>
		<div class="form-group">
			<label for="description">Descripción</label>
			<div id="description">
				<textarea></textarea>
			</div>
		</div>
		<div class="form-group checkbox">
		    <label>
		    	<input type="checkbox" ng-model="exam.studentQuestions"> Permitir a los alumnos proponer preguntas
		    </label>
		</div>
		<div class="buttons-container">
			<button class="col-lg-2 col-md-2 col-sm-8 col-xs-12 btn btn-success btn-manageExam" ng-click="createExam()">Enviar</button>
		</div>
	</form>
</div>