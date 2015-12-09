<div ng-controller="studentController">
	<div id="main-deadlines" ng-show="deadlines.length > 0">
		<h2 class="title-center title-index"> Próximas entregas </h2>
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
				<div class="deadline-list-content">
					<div class="buttons-files">
						<div class="upload">
							<form id="{{ item.id }}" action="uploadfile.html" submit="false" method="post" enctype="multipart/form-data">
								<span id="file-selector" upload-file>
									<button id="input-file" class="btn btn-deadlines">Examinar</button>
									<label for="input-file">No se ha seleccioando ningun archivo</label>
									<input type="file" name="file">
									<input type="hidden" name = "id" value="{{ item.id }}">
								</span>
							</form>
							<button class="btn btn-deadlines" ng-click="uploadFile(item)">
								<span class="glyphicon glyphicon-open" aria-hidden="true"></span>
								Subir
							</button>
						</div>
						<div ng-show="item.file">
							<a href="/deadlines/{{ item.path }}">
								<span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
								{{ stdName(item.file) }}
							</a>	
						</div>
					</div>
					<div class="content">
						<div ng-bind-html="item.description"></div>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div id="no-deadlines" ng-hide="deadlines.length > 0">
		<div>
			<h3>No tienes ninguna futura entrega</h3>
		</div>
	</div>
</div>