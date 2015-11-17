<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<div ng-controller="createDeadlineController">
	<script type="text/javascript"> var deadlineID = <?= $deadline ?>;</script>
	<h2 class="title-center title-manageDeadline"><?= $title ?></h2>
	<form id="form-manageDeadline" class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
		<div class="form-group">
			<label for="subject">Asignatura</label>
			 <select name="mySelect" id="mySelect" class="form-control"
		      ng-options="subject.name for subject in subjects track by subject.code"
		      ng-model="deadline.subject"></select>
		</div>
		<div class="form-group">
			<label for="time">Nombre</label>
			<input type="text" class="form-control" id="time" ng-model="deadline.name" placeholder="nombre" ng-keyup="limit($event, 256)">
		</div>
		<div class="form-group">
			<label for="date">Fecha tope</label>
			<input type="text" class="form-control" id="date" ng-model="deadline.date" placeholder="dd/mm/yyyy" ng-keyup="limit($event, 10)">
		</div>
		<div class="form-group">
			<label for="description">Descripción</label>
			<div id="description">
				<textarea></textarea>
			</div>
		</div>
		<div class="buttons-container">
			<button class="col-lg-2 col-md-2 col-sm-8 col-xs-12 btn btn-success btn-manageDeadline" ng-click="createDeadline()">Enviar</button>
		</div>
	</form>
</div>