<div ng-controller="studentController">
	<h2 class="title-index title-center">Calificaciones</h2>
	<div class="marks-subject" ng-repeat="(title, marks) in allMarks">
		<h3> {{ title }} </h3>
		<div class="col-lg-10 col-md-10"> 
			<table ng-show="marks.exams" class="table table-bordered">
				<thead>
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
				<thead>
					<th colspan="2"> ENTREGAS </th>
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
						<td> {{ row.nombre }} </td>
						<td> {{ row.mark }} </td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>