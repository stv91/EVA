<div ng-controller="loginController" class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xs-12">
	<h1 id="login-title">Login</h1>
	<form  name="LoginForm" ng-submit="submit()">
	  <div class="form-group">
	    <label for="email">Email</label>
	    <input type="email" class="form-control" id="email" placeholder="Email" ng-model="email">
	  </div>
	  <div class="form-group">
	    <label for="password">Contraseña</label>
	    <input type="password" class="form-control" id="password" placeholder="Password" ng-model="password">
	  </div>
	  <div class="form-group">
	    <div>
	      <div class="checkbox">
	        <label>
	          <input type="checkbox" ng-model="rememberME"> Recordarme
	        </label>
	      </div>
	    </div>
	  </div>
	  <div class="form-group">
	  	<div class="col-sm-offset-4 col-sm-4 col-xs-12">
	      	<button type="submit" class="btn btn-default btn-lg col-xs-12" 
		  	data-placement="top" data-container="body" data-duration="3" bs-alert="alert" dismissable="false" container="#alert-place">
		  		Login
			</button>
	    </div>
	  </div>
	</form>
</div>