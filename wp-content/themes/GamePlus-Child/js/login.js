function toSign() {
    $('.signin-container').addClass('active');
    $('.login-container').removeClass('active');
}

function toLogin() {
    $('.login-container').addClass('active');
    $('.signin-container').removeClass('active');
}

$('li:contains("registered with your email address"):last').text('اکانتی با همین ایمیل ثبت شده است, اگر مالک آن اکانت هستید وارد شوید');