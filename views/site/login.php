<!--<div class="col-sm-6 col-sm-offset-4 col-md-6 col-md-offset-4 col-lg-6 col-lg-offset-4 col-xs-12">
	<form class="form-horizontal" action="login.html" method="post">
		<div class="form-group">
			<label class="col-sm-2 col-md-2 col-lg-2 col-xs-12 control-label" for="email">Email</label>
			<div class="col-sm-10 col-md-10 col-lg-10 col-xs-12">
				<input id="email" class="form-control" type="email" name="LoginForm[email]">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 col-md-2 col-lg-2 col-xs-12 control-label" for="password">Contraseña</label>
			<div class="col-sm-10 col-md-10 col-lg-10 col-xs-12">
				<input id="password" class="form-control" type="password" name="LoginForm[password]">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10 col-lg-offset-2 col-lg-10 col-xs-12">
				<div class="checkbox">
					<label class="control-label" for="recordar">
						<input id="recordar" class="form-control" type="checkbox" name="LoginForm[rememberME]">Recordar
					</label>
				</div>
			</div>
		</div>
        <div class="form-group">
			<input type="submit" class="form-control" value="Login">	
		</div>
	</form>
</div>-->

<div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-xs-12">
	<form>
	  <div class="form-group">
	    <label for="email">Email</label>
	    <input type="email" class="form-control" id="email" placeholder="Email" name="LoginForm[email]">
	  </div>
	  <div class="form-group">
	    <label for="password">Contraseña</label>
	    <input type="password" class="form-control" id="password" placeholder="Password" name="LoginForm[password]">
	  </div>
	  <div class="form-group">
	    <div>
	      <div class="checkbox">
	        <label>
	          <input type="checkbox" name="LoginForm[rememberME]"> Recordarme
	        </label>
	      </div>
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-4 col-sm-4 col-xs-12">
	      <button type="submit" class="btn btn-default btn-lg col-xs-12">Login</button>
	    </div>
	  </div>
	</form>
</div>