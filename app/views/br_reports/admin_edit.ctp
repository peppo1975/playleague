
	<?=$this->element("/backend/edit_scripts");?>
	<?=$this->element('/backend/tab_scripts');?>
	
	<?=$this->Form->create('BrReport', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica report: <span><?=$this->data['BrReport']['title'];?></span></h2>
								<ul>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<div class="tab-container">
	
		<ul class="tab-selector">
		
			<li data-index="1" class="selected"><a href="javascript:;">Segnalazione</a></li>
			<li data-index="2"><a href="javascript:;">Commenti</a></li>
		
		</ul>	
		
	<div class="tab-page tab-selected" data-index="1">	
	
	<?=$this->Form->input('id');?>
	
	<?=$this->Form->input('priority', array('disabled' => true, 'label' => 'Priorità', 'type' => 'select', 'options' => array('0' => 'Bassa','1'=>'Media','2'=>'Alta'), 'empty' => true));?>
	<?=$this->Form->input('zone_id', array('disabled' => true, 'label' => 'Zona applicazione', 'type' => 'select', 'options' => $zones, 'empty' => true));?>
	<?=$this->Form->input('category_id', array('disabled' => true, 'label' => 'Categoria', 'type' => 'select', 'options' => $categories, 'empty' => true));?>

	<?
	
		$type_requests = array(
		
		 'bug/malfunzionamento' => 'bug/malfunzionamento',
		 'richiesta sviluppo nuova procedura' => 'richiesta sviluppo nuova procedura',
		 'spiegazioni sul funzionamento' => 'spiegazioni sul funzionamento',		
		
		);
	
	?>
	
	<?=$this->Form->input('type_request', array('disabled' => true, 'label' => 'Tipo di richiesta', 'type' => 'select', 'options' => $type_requests, 'empty' => true));?>
	
	
	<div class="clear"></div>
	
	<?=$this->Form->input('title', array('readonly' => true, 'label' => 'Titolo', 'type' => 'text','class' => 'big'));?>
	
	<div class="clear"></div>
	
	<?=$this->Form->input('object', array('readonly' => true, 'label' => 'Oggetto', 'type' => 'text', 'class' => 'big'));?>
	
	<div class="clear"></div>
	
	<div class="post_content">
	
		<h3>Messaggio/Segnalazione</h3>
	
		<p>
		
			<?=$this->data['BrReport']['content'];?>
		
		</p>
	
	</div>
	
	<div class="clear"></div>
	
	<h3>Aggiungi altri allegati</h3>

	<?=$backend->getFiles('brreport_id', $this->data['BrReport']['id'], array('limit' => 5));?>
	
	</div>
	
	<div class="tab-page" data-index="2">	<!-- div commenti ajax -->
	
	<div class="comment-list">
	
	<? if(count($comments)): ?>
	
	<?
	
		//$n = count($comments);
		$n = 1;
	
	?>
	
		<? foreach($comments as $comment): ?>
		
			<div class="comment-box" data-id="<?=$comment['BrComment']['id'];?>" data-user-id="<?=$comment['BrComment']['user_id'];?>">
			
				<span class="count-comment">#<?=$n;?></span>
				<span class="date-comment"><?=$comment['BrComment']['created_it'];?></span>
				<span class="user-comment"><?=$comment['BrComment']['author'];?></span>
				<p>
					<?=$comment['BrComment']['content'];?>
				</p>
				
			</div>
			
			<? $n++; ?>
		
		<? endforeach; ?>
	
	<? endif; ?>
	
	</div>
	
	<div class="comment-ajax-form">
	
		<?=$this->Form->input('BrComment.report_id', array('type' => 'hidden', 'value' => $this->data['BrReport']['id']));?>
		<?=$this->Form->input('BrComment.user_id', array('type' => 'hidden', 'value' => $auth['id']));?>
		<?=$this->Form->input('BrComment.author', array('type' => 'hidden', 'value' => $auth['name']));?>
		<?=$this->Form->input('BrComment.email', array('type' => 'hidden', 'value' => $auth['email']));?>

		<?=$this->Form->input('BrComment.content', array('label' => 'Messaggio:'));?>
		<div class="clear"></div>
		<div class="input">
			<?=$this->Form->submit('commenta',array('type' => 'button','div' => false,'id' => 'commentAdd'));?>
		</div>
	
	</div>
	
	<script type="text/javascript">
	
	$(function(){
	
		$('.formAdd').delegate("#commentAdd","click", function(){
		
			$(".comment-ajax-form").find('div.error-message').remove();
		
			var data = $(".comment-ajax-form *").serialize();
			
			$.post('/admin/br_comments/ajax_add', data, function(ret){
			
				if(ret.error != 1) {
				
					$('.comment-list').prepend(
						'<div class="comment-box" data-id="'+ret.data.BrComment['id']+'" data-user-id="'+ret.data.BrComment['user_id']+'">' +
							'<span class="count-comment"></span>' +
							'<span class="date-comment">'+ret.data.BrComment['created_it']+'</span>&nbsp;' +
							'<span class="user-comment">'+ret.data.BrComment['author']+'</span>' +
							'<p>'+ret.data.BrComment['content']+'</p>' +
						'</div>'	
					);
					
					$(".comment-list").find('.count-comment').each(function(){
					
						var t = $(this).text(); /* - */ var n = stringa = parseInt(t.replace('#',''));
						
						if(isNaN(n)) n = 0;
						
						var new_n = parseInt(n) + 1; /* - */ $(this).html('#' + new_n + '&nbsp;');
						
					});
					
					$('#BrCommentContent').val('');
				
				} else {
				
					for(i in ret.data) {
					
						console.log('Campo: ' + i + ' - msg: ' + ret.data[i]);
						$(".comment-ajax-form").find('*[name="data[BrComment][' + i + ']"]')
											   .parent('div')
											   .append('<div class="error-message">' + ret.data[i] +'</div>');
						
					}
				
				}
			
			},'json');
		
		});
	
	});
	
	</script>
	
	</div><!-- close div commenti ajax -->
				
	<?=$this->Form->end();?>
	
	</div>
