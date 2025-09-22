<script type="text/javascript">
$(function() {

	$("#PostDisabled").change(function() {

		if($("#PostDisabled").val() == 0) {
			$("#PostPublished").removeAttr('disabled');
		}
		else $("#PostPublished").attr('disabled','disabled');

	});

	if($("#PostDisabled").val() == 0) {
		$("#PostPublished").removeAttr('disabled');
	} else $("#PostPublished").attr('disabled','disabled');

});

</script>