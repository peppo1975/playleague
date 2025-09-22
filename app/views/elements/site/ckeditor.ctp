			<?
			$config['toolbar'] = array(
				array( 'Source', '-', 'Undo','Redo','-', 'Cut','Copy','Paste','PasteText','PasteFromWord','-' ),
				array( 'Find','Replace','SelectAll','RemoveFormat' ),
				array( 'Bold', 'Italic', 'Underline', 'Strike' ),
				array( 'Image', 'Link', 'Unlink', 'Anchor' ),
				array( 'NumberedList','BulletedList','Outdent','Indent','Blockquote' )
			);
			?> 
			
			<?=$cksource->ckeditor($name,array('config' => $config, 'width' => 400, 'escape' => false, 'id' => 'prova_' . time()));?>