<div class="modal fade" id="ajax_login" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajax_login">ورود به حساب کاربری</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<form id="login" class="ajax-auth" action="login" method="post">
			<div class="row">
				<div class="col">
					<?php wp_nonce_field('ajax-login-nonce', 'security'); ?> 
					<div class="form-group">
						 <input id="username" type="text" class="form-control" name="username" placeholder="نام کاربری">
					</div>
					<div class="form-group">
						<input id="password" type="password" class="form-control" name="password" placeholder="رمز عبور">
					</div>
					<p class="status"></p>
					<div class="form-group">
						<a class="text-link" href="<?php bloginfo('url')?>/password-reset" target="_blank" >کلمه عبور خود را فراموش کرده اید؟</a>
					</div>
					<input class="btn btn-primary btn-block submit_button" type="submit" value="ورود">
				</div>
			</div>
		</form>
      </div>
	  <div class="modal-footer">
		<div class="form-group mx-auto">
        کاربر جدید هستید؟ <a class="text-link" href="" data-toggle="modal" data-target="#ajax_register" data-dismiss="modal" id="register">ثبت نام در کافه کامرس</a>
		</div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="ajax_register" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ajax_register">ثبت نام</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<form id="register" class="ajax-auth" action="register" method="post">
			<div class="row">
				<div class="col">
					<?php wp_nonce_field('ajax-register-nonce', 'signonsecurity'); ?>       
					<div class="form-group">
						<input id="signonname" type="text" name="signonname" class="form-control" placeholder="نام کاربری">
					</div>
					<div class="form-group">
						<input id="email" type="text" class="form-control" name="email" placeholder="ایمیل">
					</div>
					<div class="form-group">
						 <input id="signonpassword" type="password" class="form-control" name="signonpassword" placeholder="رمز عبور">
					</div>
					<div class="form-group">
						<input type="password" id="password2" class="form-control" name="password2" placeholder="تکرار رمز عبور">
					</div>
					<p class="status"></p>
					 <input class="btn btn-primary btn-block submit_button" type="submit" value="عضویت">
				</div>
			</div>
		</form>
      </div>
	  <div class="modal-footer">
		<div class="form-group mx-auto">
        قبلا ثبت نام کرده اید؟<a class="text-link" href="" data-toggle="modal" data-target="#ajax_login" data-dismiss="modal" id="login">وارد شوید</a>
		</div>
      </div>
    </div>
  </div>
</div>