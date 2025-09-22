<?php if($n): ?>
	<div class="col-xs-12" style="text-align: center">
		<ul class="pagination" style="margin: 0 auto;"></ul>
	</div>

<br>
<br>





<script type="text/javascript" src="/js/jquery.twbsPagination.min.js"></script>
<script>
$(function(){
	if($('.pagination').length)
	{
		$('.pagination').twbsPagination({
	        totalPages: <?=$n?>,
	        visiblePages: 7,
	        href: "<?=$url = $this->requestAction('/pages/getPageUrl/' . $page_id); ?>" + "/{{number}}",
	        first: "<i class=\"fa fa-step-backward\"></i>",
	        last: "<i class=\"fa fa-step-forward\"></i>",
	        next: "<i class=\"fa fa-forward\"></i>",
	        prev: "<i class=\"fa fa-backward\"></i>"
	    });
	}
})

    
</script>
<?php endif; ?>