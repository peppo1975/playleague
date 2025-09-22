<?

	class Upload extends AppModel {
			
				var $name = 'Upload';
				var $useTable = 'files';
				var $order = array('Upload.order' => 'ASC');
				
	
	}
			function beforeValidate() {
					
				if (!empty( $this->data['Upload']['published'])) {
					
						$this->dmy2ymd($this->data['Upload']['published']);
						
				
					
					}
					
				}

?>
