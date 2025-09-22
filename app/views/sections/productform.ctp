<div role="main" class="main">


	<div class="container"  id="main-custom">
		
		<div class="row">
			<div class="col-md-9">
				
			<h2>Procedura acquisto: <span style="color: #05e516"><?=$name?></span> - <span style="color: #05e516">€ <?=$price?></span> </h2>


				<div class="row">
					<div class="col-md-12">
<form action="/sections/productdati" method="POST" id="ds">							

<fieldset>

<div class="row">
<div class="col-md-6">
	<div class="form-group">
		<div class="col-lg-12">
			<label>Nome*</label>
			<input name="nome" value="<?=$user['nome']?>" type="text" class="form-control" required>
		</div>
	</div>
				</div>
<div class="col-md-6">
	<div class="form-group">
		<div class="col-lg-12">
			<label>Cognome*</label>
			<input name="cognome" value="<?=$user['cognome']?>" type="text" class="form-control" required>
		</div>
	</div>
</div>
</div>
				<div class="clear"></div>
		<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-lg-12">
							<label>Email*</label>
							<input name="email" value="<?=$user['email']?>" type="text" class="form-control" required>
						</div>
					</div>
				</div>
				<input type="hidden" name="uniqid" value="<?=$uniqid?>">
				<input type="hidden" name="product_name" value="<?=$name?>">
				<input type="hidden" name="product_price" value="<?=$price?>">
				<input type="hidden" name="redirect" value="<?=$redirect?>">

				<div class="col-md-6">
					<div class="form-group">
						<div class="col-lg-12">
							<label>Telefono*</label>
							<input name="telefono" value="<?=$user['telefono']?>" type="text" class="form-control" required>
						</div>
					</div>
				</div>
			</div>
				<div class="clear"></div>
					
				<div class="col-md-6">
				<div class="input">
					<input type="submit" value="Procedi" class="btn btn-primary pull-left mb-xl" required>
				</div>		
				</div>
</fieldset>
</div>
					</div>
				</div>

			<div class="clear"></div>	

				
			</div><!-- close contents-box -->

			

		</div><!-- close wrapper-box-contents -->
		
</div><!-- close wrapper-box -->
</div>
<script>
$(function(){
	$("form#ds").validate({
		messages: {
			nome: {
				required: "Questo campo è obbligatorio."
			},
			cognome: {
				required: "Questo campo è obbligatorio."
			},
			email: {
				required: "Questo campo è obbligatorio."
			},
			telefono: {
				required: "Questo campo è obbligatorio."
			},
		}
	});
})
</script>