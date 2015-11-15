<div ng-controller="doExamController">
	<div ng-hide="mark">
		<h2 class="title-center exam-title"><?= $examInfo["subject"] ?></h2>
		<div class="time-box">
			<p><span class="bold">Hora de incio: </span> <?= explode(" ", $examInfo["start"])[1] ?></p>
			<p><span class="bold">Hora de fin: </span> <?= explode(" ", $examInfo["finish"])[1] ?></p>
		</div>
		<div class="info-box">
			<h4 class="warning"> Normativa </h4>
			<ul>
				<li>Las respuestas solo se podrán enviar una sola vez</li>
				<li>Si las respuesta no son envadidas antes de la hora de fin, significará el suspenso del exámen </li>
			</ul>
		</div>
		<form class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 test" id="<?= $examInfo['id']; ?>">
			<h3 class="exam-subtitle"> Test </h3>
			<?php foreach ($questions as $question) : ?>
			<div class="question" id="<?= $question['id']; ?>">
				<p class="question-text"><?= $question['question']; ?></p>
				<div class="answers">
					<?php foreach ($question['answers'] as $answer) : ?>
					<div class="checkbox">
						<label>
							<input type="radio" name="<?= $question['id']; ?>" value="<?= $answer['id']; ?>">
							<?= $answer['text']; ?>
						</label>
					</div>
					<?php endforeach ?>
				</div>
			</div>
			<?php endforeach ?>
				<button class="btn btn-success btn-send col-lg-2 col-md-2 col-sm-8 col-xs-12" 
				data-toggle="modal" data-target="#modal-confrim"> Enviar </button>
		</form>
		<div ng-class="modalClass" id="modal-confrim" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Finalizar</h4>
					</div>
					<div class="modal-body">
						<p>¿Estas seguro que deseas finalizar el test y enviar los datos?</p>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" ng-click="sendExam()">
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
	<div ng-show="mark">
		<p ng-class="mark.class">{{ mark.text }}</p>
	</div>
</div>