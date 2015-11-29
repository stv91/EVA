<div ng-controller="messagesController">
	<div id="msg-panel">
		<div class="content">
			<div class="add-conversation-container">
				<button id="add-conversation" class="btn btn-messages col-xs-10 col-xs-offset-1" ng-click="showMessages = false">
					<span class="glyphicon glyphicon-plus-sign"></span>
					Crear conversación
				</button>
			</div>
			<div class="divider"></div>
			<div class="conversation-container">
				<h4>Mis conversaciones</h4>
				<ul class="conversations">
					<div ng-hide="conversations.length > 0">
						No hay convesaciones
					</div>
					<li id="{{conversation.id}}" ng-repeat="conversation in conversations" ng-click="selectConversation(conversation)" on-finish-render>
						<span class="glyphicon glyphicon-info-sign" ng-click="showInfo($event, conversation)"></span>
						<span class="glyphicon glyphicon-remove" ng-click="updateConversationToLeave($event,conversation)"
						data-toggle="modal" data-target="#modal-confrim"></span>
						<h5>{{ conversation.name }}</h5>
						<h6>{{ conversation.subject }}</h6>
						<ul class="members">
							<li ng-repeat="member in conversation.members"> {{ member }} </li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<span id="arrow-panel" class="glyphicon glyphicon-chevron-{{ slide }}" ng-click="togglePanel()"></span>
	</div>

	<div id="write-panel" ng-show="showMessages && conversations.length > 0">
		<div>
			<textarea></textarea>
			<button id="btn-send-msg" class="btn" ng-click="sendMessage()">Enviar</button>
		</div>
	</div>

	<div id="messages" ng-show="showMessages && conversations.length > 0">
		<ul>
			<li ng-repeat="msg in messages">
				<div class="header">
					<span>{{msg.user}} ({{msg.email}})</span> 
					<span>{{msg.date}}</span>
				</div>
				<div class="content" ng-bind-html="msg.text"></div>
			</li>
		</ul>
	</div>

	<div id="new-conversation" ng-hide="showMessages && conversations.length > 0">
		<h2> Nueva conversación </h2>
		<div id="form-manageExam" class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
			<div class="form-group">
				<label for="mySelect">Asignatura</label>
				<select name="mySelect" id="mySelect" class="form-control"
			      ng-options="subject.name for subject in subjects track by subject.code"
			      ng-model="subject" ng-change="getUsers()"></select>
			</div>
			<div class="form-group">
				<label for="name">Nombre</label>
				<input type="text" class="form-control" id="name" ng-model="convName" placeholder="Nombre de la conversacion">
			</div>
			<div id="users-to-add" class="form-group">
				<label for="users">
					Usuarios
					<span>
						<input class="ng-pristine ng-untouched ng-valid" type="checkbox" ng-model="showTeachers">
						Profesores
					</span>
					<span>
						<input class="ng-pristine ng-untouched ng-valid" type="checkbox" ng-model="showStudents">
						Alumnos
					</span>
				</label>
				<div>
					<span id="btn-add-user" class="glyphicon glyphicon-plus-sign" ng-click="addUser()"></span>
				    <select id="users" class="form-control" ng-model="user">
				    	<option ng-repeat="u in users | userFilter:this" value="{{u.email}}"> {{u.name}} ({{u.email}})</option>
				    </select>
				</div>
			    
			</div>
			<div class="users">
				<label>Participantes</label>
				<ul class="form-control">
					<li ng-repeat="u in addedUsers">{{u.name}} ({{u.email}}) <span class="glyphicon glyphicon-remove" ng-click="removeUser(u)"></span></li>
					<li> Tú (<?= Yii::$app->user->identity->email ?>)</li>
				</ul>
			</div>
			<div class="buttons-container">
				<button class="btn btn-success" ng-click="createConversation()">Crear</button>
			</div>
		</div>

	</div>

	<div ng-class="modalClass" id="modal-confrim" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Abandonar conversación</h4>
					</div>
					<div class="modal-body">
						<p>¿Estas seguro que deseas abandonar esta conversación?</p>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" ng-click="leaveConversation()">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							Sí
						</button>
						<button class="btn" data-dismiss="modal">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"  ng-click="updateConversationToLeave(null)"></span>
							No
						</button>
					</div>
				</div>
			</div>
		</div>
</div>