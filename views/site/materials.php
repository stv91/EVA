<?php //$this->registerJsFile(Yii::$app->request->baseUrl.'/js/controllers/materialsController.js', ['position' => \yii\web\View::POS_END]); ?>

<div ng-controller="materialsController">
	<button id="show-search" class="btn" data-toggle="modal" data-target="#search-modal">
		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
	</button>
	<button id="show-upload" class="btn" data-toggle="modal" data-target="#upload-modal">
		<span class="glyphicon glyphicon-open" aria-hidden="true"></span>
	</button>
	
	<!-- MODALS -->
	<div id="materilas-modals">
		<!-- MODAL SEARCH -->
		<div class="modal fade modal-fullscreen force-fullscreen" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						<p>One fine body…</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- MODAL UPLOAD -->
		<div class="modal fade modal-fullscreen force-fullscreen" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						<p>One fine body…</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<!-- CONTENT PAGE -->
	<p>materiales</p>
</div>