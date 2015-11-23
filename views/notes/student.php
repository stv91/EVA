<script type="text/javascript" src="/js/jquery_tablesorter/jquery.tablesorter.min.js"></script>
<div ng-controller="studentController">
	<div ng-show="allMarks">
		<h2 class="title-index title-center">Calificaciones</h2>
		<div class="marks-subject" ng-repeat="(title, marks) in allMarks" on-finish-render="ngRepeatFinished">
			<h3> {{ title }} </h3>
			<div class="col-lg-10 col-md-10"> 
				<table ng-show="marks.exams" class="table table-bordered exam-table">
					<thead class="first-header">
						<th colspan="2"> EXÁMENES </th>
					</thead>
					<thead>
						<th> Fecha </th>
						<th> Nota </th>
					</thead>
					<tbody>
						<tr ng-repeat="row in marks.exams"
						class="{{ row.mark >= 5.0? 'success' : 'danger' }}">
							<td> {{ row.date }} </td>
							<td> {{  row.mark }} </td>
						</tr>
					</tbody>
				</table>
				<table ng-show="marks.deadlines" class="table table-bordered">
					<thead class="first-header">
						<th colspan="3"> ENTREGAS </th>
					</thead>
					<thead>
						<th> Fecha </th>
						<th> Nombre </th>
						<th> Nota </th>
					</thead>
					<tbody>
						<tr ng-repeat="row in marks.deadlines"
						class="{{ row.mark >= 5.0? 'success' : 'danger' }}">
							<td> {{ row.date }} </td>
							<td> {{ row.name }} </td>
							<td> {{ row.mark }} </td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="no-marks" ng-hide="allMarks">
		<h3>No se ha publicado ninguna nota</h3>
	</div>
</div>