<? $id = $squadra['Squadre']['Squadra']; ?>
<? $storia = str_replace("\r\n","",$squadra['Squadre']['Storia']); ?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">

	function centerImg() {
		
		var div = $('.squadra-img');
		var img = div.children('img');
	
		var first = div.width() / 2;
		var second= img.width() / 2;
		
		var margin_top = first - second;
		
		img.css('margin-left',margin_top);
		
		$('img').css('opacity',1);
		
	}	



$(function() {
	
	$('img').css('opacity',0);
	
	$(".saveStory").live('click', function(){
	
		var squadra_id = '<?=$id;?>';
		
		$('.ok-message').remove();
		
		ajaxLoader('show');
		
		$.post('/squadres/saveStory/' + squadra_id, {"storia":$("#story-textarea").val()}, function(){

			ajaxLoader('hide');
			
			$('<div class="ok-message alert alert-success">Dati salvati con successo.</div>').insertAfter($("#story-textarea"));
			
			$('.ok-message').fadeOut(5000);
			
		});
		

	
	});
	
	$("#UploadTag").live('change', function(){
		
		if($(this).val() == 'Trofeo') {
			$("#UploadYearTrofeo").parent('div').removeClass('hidden');
			$("#UploadYearTrofeo").parent('div').addClass('required');
		}
		else {
			$("#UploadYearTrofeo").parent('div').addClass('hidden');
			$("#UploadYearTrofeo").parent('div').removeClass('required');
		}
		
	});	

});	

$(document).ready(function(){
	
	if(location.hash == '#error') {
		
		$('#UploadPercorso').parent('div.file').append('<div class="error-message">Dimensione massima file: 500kb, Estensioni ammesse: jpeg,png</div>');
		
	}
	
	var config = {
		toolbar:
		[
			['Undo','Redo','-', 'Cut','Copy','Paste','PasteText','PasteFromWord','-'],
			['Find','Replace','SelectAll','RemoveFormat'],
			['Bold', 'Italic', 'Underline', 'Strike'],
			['Link', 'Unlink', 'Anchor'],
			['NumberedList','BulletedList','Outdent','Indent','Blockquote']
		]
	};   			

	$('#story-textarea').ckeditor(config);	
	$('#story-textarea').val($("#hidden-textarea").val());	

});

$(window).load(function() {

	centerImg();
	
});

</script>	
<?
//Ripartizione upload

$uploads = array();
foreach($squadra['Upload'] as $upload) {
	if($upload['tag'] == '') $upload['tag'] = 'Gallery';
	$uploads[$upload['tag']][] = $upload;
}

//Logo
if(isset($uploads['Logo'][0])) {
	
	$logo = $thumbnail->link(array('path' => $uploads['Logo'][0]['path'], 'h' => 50, 'q' => 100, 'f' => 'png')); 
	
} else {
	
	$logo = $thumbnail->link(array('path' => '/img/website/icon_profile_default.png', 'w' => 50, 'h' => 50, 'zc' => 1, 'f' => 'png'));
	
}

?>



<?
//Ripartizione upload

$uploads = array();
foreach($squadra['Upload'] as $upload) {
	if($upload['tag'] == '') $upload['tag'] = 'Gallery';
	$uploads[$upload['tag']][] = $upload;
}

//Logo
if(isset($uploads['Logo'][0])) {
	
	$logo = $thumbnail->link(array('path' => $uploads['Logo'][0]['path'], 'h' => 50, 'q' => 100, 'f' => 'png')); 
	
} else {
	
	$logo = $thumbnail->link(array('path' => '/img/website/icon_profile_default.png', 'w' => 50, 'h' => 50, 'zc' => 1, 'f' => 'png'));
	
}

?>	
<script type="text/javascript" src="/js/layout.js"></script>
<div role="main" class="main">

	<div style="background: #f5f5f5; margin-bottom: 20px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb" style="margin-bottom: 0">
						<li><a href="/">Home</a></li>
						<li class="active">Modifica squadra</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		
		<div class="row">
			<div class="col-md-9">
		

			<div class="contents-box" id="bg-retino">
			

				<h3 class="title-profile-menu"><img class="team-logo" src="<?=$logo;?>" /> <span><?=$squadra['Squadre']['Denominazione'];?></span> <span class="team-manage">Modifica squadra</span></h3>
			<div class="clear"></div>
			<?=$this->element('site/squadre/' . $element, array('squadra' => $squadra, 'uploads' => $uploads));?>
		
			<div class="clear"></div>
			</div><!-- close contents-box -->
		 </div><!-- close wrapper-box-contents -->

		 <div class="col-md-3">

				<aside class="sidebar">
					<h4 class="heading-primary">Gestione account</h4>
						<ul class="nav nav-list narrow">
				<li><a href="/gestione/profilo/<?=$this->Session->read('Login.data.id');?>/Athlete" title="Informazioni personali">Informazioni personali</a></li>
				<li><a href="/gestione/vota" title="Votazioni">Votazioni</a></li>
				<li class="active"><a href="/gestione/squadre" title="Gestione squadre">Gestione squadre</a></li>
				<li class="edit-team"><a class="edit-team-button" href="/squadre/<?=$id;?>/1/<?=strtolower(Inflector::Slug($squadra['Squadre']['Denominazione'],'-'));?>" title="chiudi">Chiudi</a></li>
			</ul>
			<?
			/*
			<h1 class="profile-name-title">Gestione profilo atleta // <span><?=$this->Session->read('Login.data.cognome');?> <?=$this->Session->read('Login.data.nome');?></span></h1>
			*/
			?>
			<div class="clear"></div>

		 </div>
	</div>
	</div>
</div><!-- close wrapper-box -->							