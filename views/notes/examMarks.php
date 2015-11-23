<script type="text/javascript" src="/js/jquery_tablesorter/jquery.tablesorter.min.js"></script>
<script type="text/javascript"> var examID = <?= $exam ?>; </script>
<div ng-controller="examMarksController">
	<div class="title-marks">
		<h2> <?= $title ?> </h2>
		<h3> <?= $date ?> </h3>
	</div>
	<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1">
		<table class="table table-bordered">
			<thead>
				<th> Alumno </th>
				<th> Nota </th>
			</thead>
			<tbody>
				<?php foreach ($marks as $row) : ?>
				<tr>
					<td> <?= $row['name'] ?> </td>
					<td class="mark-value"> 
						<span ng-click="editMark(<?= $row['student'] ?>)"><?= $row['mark'] ?> </span>
						<input name="<?= $row['student'] ?>" class="form-control" value="<?= $row['mark'] ?>" ng-blur="validateMark(<?= $row['student'] ?>)">
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>