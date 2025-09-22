<script type="text/javascript">


	$(function() {
		var groups = '';
		$(".index-select-checkbox:checked").each(function() {
		
			groups += $(this).val() + ',';
		
		});
		
		groups += '0';
		
		$.post('/admin/newsletter_users/countgroups/50',{ "groups": groups },function (total_pages) {
		
		
			total_pages = parseInt(total_pages);
			var records = 50;
			
			$(".total-ex").html('circa ' + (total_pages*records));

			var i = 0;
		
			function repeatx() {
		
		
						$.post('/admin/newsletter_users/exportx2/' + i + '/' + records + '/' + total_pages,{ "groups": groups },function(data) {

								i++;
								var curperc = ((i+1)/parseInt(total_pages))*100;
								$(".percent").html(curperc.toFixed(2) + "%");
								$(".current-ex").html((i*records));
							
								if (i < total_pages) repeatx();
								else {
							
									location.href = data.link;
								
								}
		
						},'json');
		
			}
		
			repeatx();
		
		},'html');
	
	/*
		var total_pages = $("ul.paging li:last a").html();
		var records = $("select[name='limit']").val();
		
		
		
	
		$(".total-ex").html('circa ' + (total_pages*records));

		var i = 0;
		
		function repeatx() {
		
		
					$.get('/admin/newsletter_users/exportx2/' + i + '/' + records + '/' + total_pages,function(data) {

							i++;
							var curperc = ((i+1)/parseInt(total_pages))*100;
							$(".percent").html(curperc.toFixed(2) + "%");
							$(".current-ex").html((i*records));
							
							if (i < total_pages) repeatx();
							else {
							
								location.href = data.link;
								
							}
		
					},'json');
		
		}
		
		repeatx();
	*/
	});

</script>
<div id="exportForm" style="width: 450px; height: 120px;">


<h4>Esportazione in corso...</h4>
<br />
<div class="percent" style="text-align: center; font-weight: bold; font-size: 14px;">0%</div><br />
<div class="status" style="text-align: center;"><span class="current-ex">0</span> di <span class="total-ex">0</span> contatti esportati</div>


</div>