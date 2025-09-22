<?php
$default_array = [
	"label" => false,
	"div" => false,
	"class" => "form-control"
	];

$fields = [
	"Cognome"           => ["readonly" => "readonly"],
	"Nome"              => ["readonly" => "readonly"],
	"Indirizzo"         => ["readonly" => "readonly"],
	"Cap"               => ["readonly" => "readonly"],
	"Localita"          => ["readonly" => "readonly"],
	"Provincia"         => ["readonly" => "readonly"],
	"Telefono"          => ["class" => "form-control isNumber"],
	"Cellulare"         => ["class" => "form-control isNumber"],
	"Lavoro"            => ["class" => "form-control isNumber"],
	"Email"             => ["readonly" => "readonly"],
	"Fax"               => ["class" => "form-control isNumber"],
	"Codice Fiscale"    => ["readonly" => "readonly"],
	"Luogo di nascita"  => ["readonly" => "readonly"],
	"Data di nascita"   => ["readonly" => "readonly"],
];
$names = [
	"Codice Fiscale"    => "CodiceFiscale",
	"Luogo di nascita"  => "LuogoNascita",
	"Data di nascita"   => "DataNascita_it",
];

$fields1 = [
	"Password"          => ['type' => 'password'],
	"Conferma password" => ['type' => 'password', 'class' => 'form-control confirm_password'],
];
$names1 = [
	"Password"          => "password",
	"Conferma password" => "password_confirm",
];


?>
<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title">Gestione avatar</h2>
	</header>
	<div class="panel-body">
		

			<!-- hidden -->
			<?=$this->Form->input('Atleta');?>

			<div class="avatar-athlete">
				<? if(!empty($this->data['Athlete']['avatar'])): ?>
					<script type="text/javascript">
				
						$(function(){
						
							$('.delete-avatar').click(function(){
							
								if(confirm('Sicuro di voler eliminare il tuo avatar?')) {
								
								$.get('/users/delete_avatar/' + $(this).attr('data-athlete') + '/Athlete', function(){
									
									location.reload();
									
								})
								
								}
							
							});
						
						});
				
					</script>
				
					<div class="row">
						<div class="avatar-img col-md-2">
							<span class="img-thumbnail">
								<?=$thumbnail->show(array('path' => $this->data['Athlete']['avatar'], 'w' => 50, 'h' => 50, 'zc' => 1));?>
							</span>
						</div>
						<div class="avatar-manage col-md-6">
							<a href="javascript:;" data-athlete="<?=$this->data['Athlete']['Atleta'];?>" class="delete-avatar" title="elimina avatar di <?=$this->data['Athlete']['Cognome'];?> <?=$this->data['Athlete']['Nome'];?>">
								<span class="show_form_upload btn btn-primary pull-left mb-xl" style="margin-top: 15px;">
									Elimina avatar
								</span>
							</a>
						</div>
					</div>
					<!-- hidden field -->
					<?=$this->Form->input('avatar', array('type' => 'hidden'));?>
					<!-- close hidden field -->		
				<? else: ?>
					<script type="text/javascript">
					
					$(function(){
						
						$('.show_form_upload').click(function(){
							
							if($('.form_upload').hasClass('hidden')) {
								$('.form_upload').removeClass('hidden').slideDown('slow');
							} else {
								$('.form_upload').addClass('hidden').slideUp('slow');
							}
							
						});
					
					});
					
					</script>	
				
					<div class="row">
						<div class="avatar-img col-md-2">
							<a href="javascript:;" class="show_form_upload img-thumbnail">
								<?=$thumbnail->show(array('path' => '/img/website/icon_profile_default.png', 'w' => 50, 'h' => 50, 'zc' => 1, 'f' => 'png'));?>
							</a>
						</div>
						<div class="avatar-manage col-md-6">
							<?=$this->Form->input('Upload.percorso', array('type' => 'file', 'label' => ''));?>
							<?=$this->Form->input('Upload.tag', array('type' => 'hidden', 'value' => 'avatar'));?>
							<?=$this->Form->input('Upload.isEvidenza', array('type' => 'hidden', 'value' => 1));?>
						</div>
						<div class="col-md-3">
							<a href="javascript:;" class="show_form_upload btn btn-primary pull-left mb-xl" onclick="$(this).closest('form').submit();" style="margin-top: 22px;">
								Carica foto personale/avatar
							</a>
							<div class="form_upload hidden"></div>
						</div>
					</div>	
				<? endif; ?>
			</div>

	</div>
</section>

<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title">Gestione profilo utente</h2>
	</header>
	<div class="panel-body">
			<?php foreach( $fields as $name => $field ): ?>
				<div class="form-group">
					<label class="col-md-3 control-label" for="inputDefault"><?=$name?>:</label>
					<div class="col-md-6">
						<?php if(isset($names[$name])): ?>
							<?=$this->Form->input($names[$name], array_merge($default_array, $fields[$name])) . "\n";?>
						<?php else: ?>
							<?=$this->Form->input($name, array_merge($default_array, $fields[$name])) . "\n";?>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			
			
		
			

			<?=$this->Form->input('do_not_convert',array('type' => 'hidden'));?>
			
	</div>
</section>

<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title">Gestione password</h2>
	</header>
	<div class="panel-body">
		
			
			<?php foreach( $fields1 as $name => $field ): ?>
				<div class="form-group">
					<label class="col-md-3 control-label" for="inputDefault"><?=$name?>:</label>
					<div class="col-md-6">
						<?php if(isset($names1[$name])): ?>
							<?=$this->Form->input($names1[$name], array_merge($default_array, $fields1[$name])) . "\n";?>
						<?php else: ?>
							<?=$this->Form->input($name, array_merge($default_array, $fields1[$name])) . "\n";?>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			
			
	</div>
</section>