<div class="row login">
	<div class="col-md-4"></div>
	<div class="col-md-4 dashboard" id="dashboard">

		<form class="form" role="form" method="post" action="<?= base_url().'Home/login'?>"  accept-charset="utf-8">
				<div class="col-md-12">
					<h2 class="modified">Authorized Personel Only</h2>
					<div class="row">
						<div class="col-md-8 loginbuttonDiv">
							<div class="form-group">
								<input type ="text"  class="form-control logininput" name="email" id="email" placeholder="Email">
								<span class="help-block notif"></span>
							</div>
								<div class="form-group">
									<input type ="password" class="form-control logininput" name="password" placeholder="Password">
									<span class="help-block notif"></span>
								</div>
						</div>
						<div class="col-md-4 loginbuttonDiv">
							<div class="form-group">
								<button type ="submit" class="form-control btn-primary loginbutton">Login</button>
							</div>
						</div>
					</div>
				</div>
		</form>
	</div>
		<div class="col-md-4"></div>
</div>