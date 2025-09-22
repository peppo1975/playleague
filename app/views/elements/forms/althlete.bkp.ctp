<script type="text/javascript">

$(function(){
	
	$("body").delegate('.isNumber','keydown',function(e) {
	
		var code = e.keyCode;
			
		if(isNaN(String.fromCharCode(code)) && code != 8 && code != 40 && code != 38 && code != 37 && code != 39 && code != 116 && code != 9 && code != 46) return false;
		
	});
	
});

</script>

<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Informazioni personali</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		
		<div class="row">
			<div class="col-md-9">
				
			<h2>Informazioni personali</h2>
<!--
	<ul class="tab-profile-menu">
		<li class="selected"><a href="/gestione/profilo/<?=$this->data['Athlete']['Atleta'];?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
		<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
		<li><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>
	</ul>-->
	<?
	/*
	<h1 class="profile-name-title">Gestione profilo atleta // <span><?=$this->data['Athlete']['Cognome'];?> <?=$this->data['Athlete']['Nome'];?></span></h1>
	*/
	?>
			
<div id="athlete-form">	

	<?=$this->Form->create('Athlete', array('url' => '/gestione/profilo/' . $this->data['Athlete']['Atleta'] . '/' . 'Athlete','type' => 'file', 'id' => 'profile-form',


        'class'         => 'form-horizontal',
        'inputDefaults' => array(
            'format'  => array( 'before', 'between','label',
                                'input', 'error', 'after' ),
            'class' => 'form-control',
            'div'     => array( 'class' => 'form-group' ),
            'label'   => array( 'class' => 'control-label' ),
            'between' => '<div class="col-lg-12">',
            'after'   => '</div>',
            'error'   => array( 'attributes' => array( 'wrap'  => 'span',
                                                       'class' => 'text-danger' ) 

	))));?>

	<fieldset>
	<?=$this->Form->input('Atleta');?>
	<? 			echo $this->Session->flash();
			
	?>
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
		<div class="clear"></div>
		
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
		<div class="form_upload hidden">
		</div>
		</div>
		</div>
		<div class="clear"></div>
	
	<? endif; ?>
	
	</div>
	<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('Cognome', array('readonly' => true));?>
	</div>
	<div class="col-md-6">
	<?=$this->Form->input('Nome', array('readonly' => true));?>
	</div>
	</div>
	<div class="clear"></div>
	<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('Indirizzo', array('readonly' => true));?>
	</div>
	<div class="col-md-6">
	<?=$this->Form->input('Cap', array('readonly' => true));?>
	</div>
	</div>
	<div class="clear"></div>
		<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('Localita', array('readonly' => true));?>
	</div>
	<div class="col-md-6">
	<?=$this->Form->input('Provincia', array('readonly' => true));?>
</div>
</div>
	<div class="clear"></div>
			<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('Telefono', array('class' => 'form-control isNumber'));?>
	</div>
	<div class="col-md-6">
	<?=$this->Form->input('Cellulare', array('class' => 'form-control isNumber'));?>
	</div>
	</div>
	<div class="clear"></div>
				<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('Lavoro',array('label' => 'Telefono lavoro', 'class' => 'form-control isNumber'));?>
	</div>
	<div class="col-md-6">
	<?=$this->Form->input('Email', array('readonly' => true));?>
	</div>
	</div>
	<div class="clear"></div>
				<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('password', array('type' => 'password','label' => 'Password'));?>
	</div>
	<div class="col-md-6">
	<?=$this->Form->input('password_confirm', array('type' => 'password','label' => 'Conferma password', 'class' => 'form-control confirm_password'));?>
	</div>
	</div>
	<div class="clear"></div>
				<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('Fax', array('class' => 'form-control isNumber'));?>	
	</div>
	<div class="col-md-6">

	<?=$this->Form->input('CodiceFiscale', array('label' => 'Codice fiscale', 'readonly' => true));?>	
	</div>
	</div>
	<div class="clear"></div>
	
				<div class="row">
	<div class="col-md-6">
	<?=$this->Form->input('LuogoNascita',array('label' => 'Luogo di nascita', 'readonly' => true));?>
		</div>
	<div class="col-md-6">
	<?=$this->Form->input('DataNascita_it',array('label' => 'Data di nascita', 'readonly' => true));?>
	</div>
	</div>
	<div class="clear"></div>
	<!-- <?=$this->Form->input('Sesso',
	array(
	
	'type' => 'radio',
	'options' => array( 'Maschio'=>'M', 'Femmina'=>'F' ),
	'disabled' => true,

	));?>

	<div class="clear"></div> -->
	
	<?=$this->Form->input('do_not_convert',array('type' => 'hidden'));?>
	
					<?=$this->Form->submit('Modifica profilo',array('type' => 'submit','class' => 'btn btn-primary pull-right mb-xl'));?>
	
		</fieldset>

	<?=$this->Form->end();?>
</div>
		
			</div><!-- close contents-box -->

			<div class="col-md-3">
				<aside class="sidebar">
					<h4 class="heading-primary">Gestione account</h4>
						<ul class="nav nav-list narrow">
		<li class="active"><a href="/gestione/profilo/<?=$this->data['Athlete']['Atleta'];?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
		<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
		<li><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>

						</ul>
				</aside>
			</div>

		</div><!-- close wrapper-box-contents -->
		
</div><!-- close wrapper-box -->
</div>
