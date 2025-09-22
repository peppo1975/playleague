<script type="text/javascript">
$(document).ready(function() {
		$(".remember-box input").change(function() {

			if ($(this).is(':checked')) {

				$(".remember-box input").not($(this)).removeAttr('checked');

			}


		});

		var signsub = 0;
		$("#frmSignIn").submit(function(e) {

			e.stopPropagation();
			e.preventDefault();
			if (signsub == 1) return false;

			$(".login-error").hide();
			$(".radio-error").hide();
			$(".error-box").fadeOut(400);

			var error = false;
			if($(this).find("[type=radio]:checked").val() === undefined)
				{
					$(".radio-error").show();
					error = true;
					$(".error-box").fadeIn(400);
					return false;
				}
			$.post('/sections/login',$(this).serialize(),function (ret) {
				

				

				if (ret.login_error == 1) 
				{
					$(".login-error").show();
					error = true;
				}

				

				if(error)
				{
					$(".error-box").fadeIn(400);
				}
				else {

					if (location.href.indexOf('/tesseramenti') > -1) {
						location.reload();
					} else location.href = ret.redirect;
				}


				signsub = 0;

			},'json');
			return false;
		});

		$("#frmSignUp").submit(function(e) {

			e.stopPropagation();
			e.preventDefault();

			$(".radio-error").hide();
			$(".error-box").fadeOut(400);

			if($(this).find("[type=radio]:checked").val() === undefined)
			{
				$(".radio-error").show();
				error = true;
				$(".error-box").fadeIn(400);
				return false;
			}

			var valz = ($("input[name='optionsRadios']:checked").val());
			location.href = valz;

			return false;

		});

		var resetsub = 0;
		$("#frmResetPassword").submit(function(e) {

			e.stopPropagation();
			e.preventDefault();
			if (resetsub == 1) return false;

			$(".recover-error").fadeOut(400);
			$.post($(this).attr('action'),$(this).serialize(),function (ret) {

				if (ret.found == 0) $(".recover-error").fadeIn(400);
				if (ret.found == 1) {

					$("#frmResetPassword").hide();
					$(".recover-success").fadeIn(400);

				}


				resetsub = 0;

			},'json');
			return false;
		});

});
</script>
<? if (substr_count($_SERVER['SERVER_NAME'], "midlangs")): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71803071-1', 'auto');
  ga('send', 'pageview');

</script>
<? else: ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-3962222-32', 'auto');
  ga('send', 'pageview');

</script>

<? endif; ?>
