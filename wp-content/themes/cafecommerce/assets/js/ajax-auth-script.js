jQuery(document).ready(function(a){a("form#login, form#register").on("submit",function(e){if(!a(this).valid())return!1;a("p.status",this).show().text(ajax_auth_object.loadingmessage),action="ajaxlogin",username=a("form#login #username").val(),password=a("form#login #password").val(),email="",security=a("form#login #security").val(),"register"==a(this).attr("id")&&(action="ajaxregister",username=a("#signonname").val(),password=a("#signonpassword").val(),email=a("#email").val(),security=a("#signonsecurity").val()),ctrl=a(this),a.ajax({type:"POST",dataType:"json",url:ajax_auth_object.ajaxurl,data:{action:action,username:username,password:password,email:email,security:security},success:function(e){a("p.status",ctrl).text(e.message),1==e.loggedin&&(document.location.href=ajax_auth_object.redirecturl)}}),e.preventDefault()})});