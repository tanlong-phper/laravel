<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>浩联全球购运营后台</title>
		<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
		<meta name="renderer" content="webkit|ie-comp|ie-stand">
		<meta name="robots" content="noindex,nofollow">
		<link href="{{ asset('css/admin_login.css') }}" rel="stylesheet" />
		<style>
			#login_btn_wraper{
				text-align: center;
			}
			#login_btn_wraper .tips_success{
				color:#fff;
			}
			#login_btn_wraper .tips_error{
				color:#DFC05D;
			}
			#login_btn_wraper button:focus{outline:none;}
		</style>
		<script>
			if (window.parent !== window.self) {
					document.write = '';
					window.parent.location.href = window.self.location.href;
					setTimeout(function () {
							document.body.innerHTML = '';
					}, 0);
			}
		</script>
		
	</head>
<body>
	<div class="wrap">
		<h1><a>浩联全球购运营后台</a></h1>
		<form method="post" name="login" autoComplete="off" class="js-ajax-form">
			<div class="login">
				<ul>
					<li>
						<input type="hidden" name="_token"  value="{{csrf_token()}}"/>
						<input class="input" name="username" type="text"  placeholder="请输入帐号"/>
					</li>
					<li>
						<input class="input" type="password" name="password" placeholder="请输入密码"/>
					</li>
				</ul>
				<div class="tips_error"></div>
				<div id="login_btn_wraper">
					<button type="submit" name="submit" class="btn js-ajax-submit">登录</button>
				</div>
			</div>
		</form>
	</div>

	<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
	<script src="{{ asset('static/layer/layer.js') }}"></script>
<script>
$(function(){
	$('.js-ajax-form').submit(function () {
		var data  ={
			_token : $('input[name=_token]').val(),
			username : $('input[name=username]').val(),
			password : $('input[name=password]').val()
		};
		if(!data.username){
			$('.tips_error').html('用户名不能为空');
			return false;
		}
		if(!data.password){
			$('.tips_error').html('密码不能为空');
			return false;
		}
		$.post("{{asset('users/login')}}",data,function(msg){
			if(msg.status == 1){
				$('.tips_error').html('登录成功');
				window.location.href = "{{asset('/')}}";
			}else{
				$('.tips_error').html(msg.info);
//				showMsg(msg.info);
			}
		},'json');
		return false;
	});

});
/**
 * layer提示
 */
function showMsg(msg){
	layer.alert(msg, {
		skin: 'layer-ext-moon',
		title: '提示',
		icon: 2,
	});
}
</script>
</body>
</html>
