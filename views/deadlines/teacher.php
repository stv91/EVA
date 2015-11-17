<div ng-controller="teacherController">
	<div id="main-deadlines" ng-show="deadlines.length > 0">
		<h2>Entregas Previstas</h2>
		<div class="buttons-container">
			<button class="btn btn-deadlines btn-add-deadline"  ng-click="createDeadline()">
				<span class="glyphicon glyphicon-plus-sign"></span>
				Nueva entrega
			</button>
		</div>
		<h3 class="subtitle col-lg-offset-1 col-md-offset-1">Listado de entregas</h3>
		<ul class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
			<li class="deadline-list" ng-repeat="item in deadlines" after-load>
				<div class="deadline-list-header">
					<span class="title">
						<h4>{{ item.subjectName }}</h4>
						<h5>{{ item.name }}</h5>
					</span>
					<span class="date"> {{ item.date }} </span>
					<span class="glyphicon glyphicon-chevron-down triangle" aria-hidden="true" ng-click="showContent($event)"></span>
				</div>
				<div class="deadline-list-content" style="padding-top: 0">
					<div class="buttons-container buttons-margin">
						<button class="btn btn-deadlines" ng-click="editDeadline(item)">
							<span class="glyphicon glyphicon-edit"></span>
							Editar
						</button>
						<button class="btn btn-deadlines" ng-click="updateDeleteItem(item)" data-toggle="modal" data-target="#modal-confrim">
							<span class="glyphicon glyphicon-remove-sign"></span>
							Eliminar
						</button>
					</div>
					<div class="content">
						<div ng-bind-html="item.description | html"></div>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div id="no-deadlines" ng-hide="deadlines.length > 0">
		<h3>No tienes programada ninguna entrega</h3>
	</div>
	<div ng-class="modalClass" id="modal-confrim" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Eliminar Examen</h4>
				</div>
				<div class="modal-body">
					<p>¿Estás seguro que deseas eliminar esta entrega ya programada?</p>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" ng-click="deleteDeadline()">
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