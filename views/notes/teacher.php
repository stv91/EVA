<script type="text/javascript" src="/js/jquery_tablesorter/jquery.tablesorter.min.js"></script>
<div ng-controller="teacherController">
	<div ng-show="exams.length > 0 && deadlines.length > 0">
		<h2 class="title-index title-center">Calificaciones</h2>
		<div ng-show="exams.length > 0" class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1">
			<h3>Examenes</h3>
			<table class="table table-bordered">
				<thead>
					<th> Asignatura </th>
					<th> Fecha </th>
					<th> Ver </th>
				</thead>
				<tbody>
					<tr ng-repeat="row in exams">
						<td> {{ row.subject }} </td>
						<td> {{ row.date }} </td>
						<td> 
							<button class="btn btn-marks" ng-click="showExamMarks(row)">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div ng-show="deadlines.length > 0" class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1">
			<h3>Entregas</h3>
			<table class="table table-bordered">
				<thead>
					<th> Asignatura </th>
					<th> Nombre </th>
					<th> Fecha </th>
					<th> Ver </th>
				</thead>
				<tbody>
					<tr ng-repeat="row in deadlines" on-finish-render="ngRepeatFinished">
						<td> {{ row.subject }} </td>
						<td> {{ row.name }} </td>
						<td> {{ row.date }} </td>
						<td> 
							<button class="btn btn-marks" ng-click="showDeadlineMarks(row)">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
							</button>
						</td
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div id="no-marks" ng-hide="exams.length > 0 && deadlines.length > 0">
		<h3>No se ha finalizado ningún plazo de entrega o exámen</h3>
	</div>
</div>