<div id="materialsController" ng-controller="materialsController" after-load>
	<script type="text/javascript">
	<?php 
		if(isset($materialID)) {
			echo 'materialID = '.$materialID.';';
		}
		else {
			echo 'materialID = null;';	
		}
	?>
	</script>
	<div id="search">
		<button id="show-search" class="btn" data-toggle="modal" data-target="#search-modal">
			<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
		</button>
		<button id="show-upload" class="btn" data-toggle="modal" data-target="#upload-modal">
			<span class="glyphicon glyphicon-open" aria-hidden="true"></span>
		</button>

		<!-- MODALS -->
		<div id="materilas-modals">
			<!-- MODAL SEARCH -->
			<div class="modal fade modal-fullscreen force-fullscreen" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="search-modal" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Buscar</h4>
						</div>
						<div class="modal-body">
							<form>
								<div class="form-group">
									<label for="search-mobile">Busqueda</label>
									<input type="text" class="form-control" id="search-mobile" ng-model="search.text" placeholder="Texto a buscar"></div>
								<div class="form-group">
									<label for="subject-mobile">Asignatura</label>
									<select id="subject-mobile" class="form-control" ng-model="search.subject">
										<option value="-1"> TODAS </option>
										<option ng-repeat="subject in subjects" value="{{ subject.code }}">{{ subject.name  }}</option>
									</select>
								</div>
								<div class="form-group">
									<label>Tipo de materiales</label>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="oficials" ng-model="search.oficials" checked>Oficiales</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="no-oficials" ng-model="search.noOficials" checked>No oficiales</label>
									</div>
								</div>
								<div class="form-group">
									<label for="subject-mobile">Curso</label>
									<select id="subject-mobile" class="form-control  input-sm" 
						    		ng-model="search.course" ng-options="course as course for course in courses"></select>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
								Buscar
							</button>
						</div>
					</div>
				</div>
			</div>

		</div>
		<!-- CONTENT PAGE -->
		<div id="search-pc" class="hidden-xs">
			<form class="form-inline">
				<div id="search-opc" class="btn-group noClosable">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li>
							<div>
								<label for="subject-mobile">Curso</label>
							</div>
							<select class="form-control" ng-model="search.course" ng-options="course as course for course in courses"></select>
						</li>
						<li class="divider"></li>
						<li>
							<div>
								<label for="subject-mobile">Tipo de materiales</label>
							</div>
							<div class="checkbox-materials">
								<input type="checkbox" name="oficials" ng-model="search.oficials" >Oficiales</div>
							<div class="checkbox-materials">
								<input type="checkbox" name="no-oficials" ng-model="search.noOficials" >No oficiales</div>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#" data-toggle="modal" data-target="#upload-modal">
								<span class="glyphicon glyphicon-open" aria-hidden="true"></span>
								Subir Material
							</a>
						</li>
					</ul>
				</div>
				<!--<select id="subject-pc" class="form-control" ng-model="search.subject" ng-options="subject.id as subject.name for subject in subjects"></select>-->
				<select id="subject-pc" class="form-control" ng-model="search.subject">
					<option value="-1"> TODAS </option>
					<option ng-repeat="subject in subjects" value="{{ subject.code }}">{{ subject.name  }}</option>
				</select>
				<input type="text" class="form-control" id="search-mobile" ng-model="search.text" placeholder="Texto a buscar">
				<button id="search-btn-pc" class="btn btn-success" ng-click="searchMaterials()">
					<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
					<span>Buscar</span>
				</button>
			</form>		
		</div>
	</div>
	<div id="materials-results" ng-hide="material_prew">
		<div>
			<div ng-repeat="(subject, materials) in results">
				<h2 class="col-xs-12 col-sm-12 col-md-12 col-lg-12 subject-title">{{ subject }}</h2>
				<ul>
					<li ng-repeat="material in materials" matetias-offset-directive
					 class="col-xs-12 col-sm-6 col-md-4 col-lg-4 result">
						<a href="#" ng-click="askMaterial(material.id)">
							<span class="flaticon-{{ material.type }}"></span>
							<div>
								<span class="lineElipsis">{{ material.name }}</span>
								<span>{{ material.date }}</span>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
	<div id="doc-viewer" ng-show="material_prew">
		<h2 class="subject-title-prev">{{ material }} <span class="glyphicon glyphicon-trash edit" ng-show="owner" data-toggle="modal" data-target="#delete-modal-pc"></span> </h2>
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 result" 
			ng-hide="type == 'pdf' || type == 'odt' || type == 'odp'">
			<a href="{{ material_src }}">
				<span class="flaticon-{{ type }}"></span>
				<div>
					<span class="lineElipsis"> {{ material }} </span>
					<span > {{ date }} </span>
				</div>
			</a>
		</div>
		<div id="viewer-iframe" ng-show="type == 'pdf' || type == 'odt' || type == 'odp'"></div>
		
		<h3>Descripción <span class="glyphicon glyphicon-edit edit" ng-show="owner" ng-click="editing = true; setTinyContent()"></span></h3>
		<div id="material-desc" class="material-desc" ng-bind-html="description" ng-hide="editing"></div>
		<div id="descriptionEdit" ng-show="editing">
			<textarea></textarea>
		</div>
		<div id="#comments-container">
			<h3>Comentarios</h3>
			<div class="material-comemnts">  </div>
			<div id="commentEdit">
				<textarea></textarea>
			</div>
			<div id="#comments">
				<div ng-repeat="comment in comments" id = "{{ comment.id }}">
					<div class="comment">
						<div class="comment-header">
							<span> {{ comment.name }} {{ comment.surname }} </span>
							<span title="responder" class="glyphicon glyphicon-share-alt reply-comment" aria-hidden="true" ng-click="replyComment(comment, $event)"></span>
							<span> {{ comment.date }}</span>
						</div>
						<div class="comment-content" ng-bind-html="comment.content"></div>
					</div>
					<div class="commentReply"  ng-repeat="commentReply in comment.replies">
						<div class="comment-header">
							<span> {{ commentReply.name }} {{ commentReply.surname }} </span>
							<span> {{ commentReply.date }}</span>
						</div>
						<div class="comment-content" ng-bind-html="commentReply.content"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- MODAL UPLOAD -->
	<div ng-class="modalClass" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="upload-modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Subir archivo</h4>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label for="search-mobile">Archivo</label>
							<span id="file-selector" upload-file>
								<button id="input-file-mobile" type="button" class="form-control">Examinar</button>
								<label for="input-file-mobile">No se ha seleccioando ningun archivo</label>
								<input type="file" name="materialFile"></span>
						</div>
						<div class="form-group">
							<label for="subject-mobile-upload">Asignatura</label>
							<select name="subject" id="subject-mobile-upload" class="form-control">
								<option ng-repeat="subject in subjects" value="{{ subject.code }}">{{ subject.name  }}</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn">
						<span class="glyphicon glyphicon-open" aria-hidden="true"></span>
						Subir
					</button>
				</div>
			</div>
		</div>
	</div>


	<!-- MODAL DELETE MATERIAL -->
	<div ng-class="modalClass" id="delete-modal-pc" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Eliminar Material</h4>
				</div>
				<div class="modal-body">
					<p>Esta a punto de eliminar este material. ¿Esta seguro de que desea hacerlo?</p>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" ng-click="deleteMaterial()">
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						Aceptar
					</button>
					<button class="btn" data-dismiss="modal">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						Cancelar
					</button>
				</div>
			</div>
		</div>
	</div>

</div>

