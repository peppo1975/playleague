<? //GIUSEPPE  20/10/2016 -> filtra la classe
	$classPage = $this->requestAction('sections/className/'.$_SERVER["SERVER_NAME"]); // questo valore lo troviamo nel controller 
	
	$nameClass = $classPage["Name"];
	
	$cauzione = array();
	
	$cauzione = $this->requestAction('sections/readDeposit/'.$nameClass); // quota deposita letta da database e filtrata in base alla classe (primary, secondary, quaternary)
	
	$squadra = "";
?>
<section class="panel">
	<header class="panel-heading">
		        <? if ($nameClass == "primary"): ?>
            <h2 class="panel-title">Deposito cauzionale</h2>
        <? endif; ?>
            
        <? if ($nameClass == "quaternary"): ?>
            <h2 class="panel-title">Quota iscrizione</h2>
        <? endif; ?>
	</header>
	<div class="panel-body">
		<form class="form-horizontal form-bordered" autocomplete="off" method="post" onsubmit="return false;">
			
			
			<?if($nameClass=="primary"):?>
			
			

			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDefault">Scegli il tipo di deposito:<sup>*</sup></label>
				<div class="col-md-6">
					<input type="radio" value="<?=$cauzione[0] ?>" name="cauzione" class="cauzione" checked>&nbsp; Aggiungi deposito cauzionale di <b><?=str_replace(".",",",$cauzione[0])?>  €</b><br />			
				</div>
			</div>
			
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDefault">&nbsp;</label>
				<div class="col-md-6">
					<input type="radio" value="0" name="cauzione" class="cauzione">&nbsp; Deposito cauzionale gi&agrave; versato				
				</div>
			</div>
			
			<? elseif($nameClass=="quaternary") : ?>
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDefault">&nbsp;</label>
				<div class="col-md-6">
					<input type="radio" value="<?=$cauzione[0] ?>" name="cauzione" class="cauzione" id="1" checked>&nbsp; Quota iscrizione 1&#176 squadra <b> € <?=str_replace(".",",",$cauzione[0])?></b><br />
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDefault">&nbsp;</label>
				<div class="col-md-6">
					<input type="radio" value="<?=$cauzione[1] ?>" name="cauzione" class="cauzione" id="2" >&nbsp; Quota iscrizione 2&#176 squadra <b> € <?=str_replace(".",",",$cauzione[1])?></b><br />
				</div>
			</div>
			
			
			<div class="form-group">
				<label class="col-md-3 control-label" for="inputDefault">&nbsp;</label>
				<div class="col-md-6">
					<input type="radio" value="<?=$cauzione[2] ?>" name="cauzione" class="cauzione" id="3" >&nbsp; Quota iscrizione 1&#176 e 2&#176 squadra <b> € <?=str_replace(".",",",$cauzione[2])?></b><br />
				</div>
			</div>
			
		
		<? $squadra = "&squadra_tennis=1";?>
		<? endif ?>
		
	</form>
</div>

<div class="panel-footer">
	<ul class="pager">
		
		<li class="next" id="" style="margin-right: 10px;">
			<!--<a href="#" id="nextstep"  data-link="/subscriptions/tesseramenti?step=5&c=1&verifyid=<?=$_GET['verifyid'];?>" >Termina e vai al pagamento <i class="fa fa-angle-right"></i></a>-->
			<!--//GIUSEPPE --- -->
			<a href="#" id="nextstep"  data-link="/subscriptions/tesseramenti?step=5&d=1&c=1&verifyid=<?=$_GET['verifyid'];?>&totale=<?=$cauzione[0] ?><?=$squadra?>">Termina e vai al pagamento <i class="fa fa-angle-right"></i></a>
			
		</li>
		<li class="previous" id="validate">
			
		</li>	
	</ul>
</div>

</section>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		//$('input:radio[ id=2]').attr('checked', true);
		
		$("ul.pager li a").click(function() {
			
			location.href = $(this).attr('data-link');
			
		});
		
		$(".cauzione").change(function() {
			
			var nameClass = '<?=$nameClass?>'; 
			
			//alert($(this).attr('id'));
			
			switch(nameClass)
			{
				case 'primary':
				
				if ($(this).val() == 0) {
					
					
					//GIUSEPPE AGGIUNTA LA VARIABILE c=1		
					$("ul.pager li a:first").attr('data-link',"/subscriptions/tesseramenti?step=5&d=1&c=1&verifyid=<?=$_GET['verifyid'];?>&totale="+$(this).val());
					$("ul.pager li a:first").val('Termina iscrizione');
					
					
				} 
				else 
				{
					
					//GIUSEPPE AGGIUNTA LA VARIABILE c=1
					$("ul.pager li a:first").attr('data-link',"/subscriptions/tesseramenti?step=5&d=1&c=1&verifyid=<?=$_GET['verifyid'];?>&totale="+$(this).val());
					$("ul.pager li a:first").val('Vai al pagamento');
					
				}
				break;
				
				
				case 'quaternary':
				
				
				if($(this).attr('id') == 1)
				{
					//GIUSEPPE AGGIUNTA LA VARIABILE c=1
					$("ul.pager li a:first").attr('data-link',"/subscriptions/tesseramenti?step=5&d=1&c=1&verifyid=<?=$_GET['verifyid'];?>&totale="+$(this).val()+"&squadra_tennis=1");
					$("ul.pager li a:first").val('Termina e vai al pagamento');
				}
				
				else if ($(this).attr('id') == 2) {
					
					//GIUSEPPE AGGIUNTA LA VARIABILE c=1		
					$("ul.pager li a:first").attr('data-link',"/subscriptions/tesseramenti?step=5&d=1&c=1&verifyid=<?=$_GET['verifyid'];?>&totale="+$(this).val()+"&squadra_tennis=2");
					$("ul.pager li a:first").val('Termina e vai al pagamento');
					
				} 
				else if ($(this).attr('id') == 3) {
					
					//GIUSEPPE AGGIUNTA LA VARIABILE c=1		
					$("ul.pager li a:first").attr('data-link',"/subscriptions/tesseramenti?step=5&d=1&c=1&verifyid=<?=$_GET['verifyid'];?>&totale="+$(this).val()+"&squadra_tennis=3");
					$("ul.pager li a:first").val('Termina e vai al pagamento');
					
				} 
				
				
				
				
				break;
				
			}
			
			
			
		});
	});
	
	function test()
	{
		
		alert($("#cauzione").val());
		//location.href = '/subscriptions/tesseramenti?step=3&verifyid=' + data.id;
	}
	
</script>
