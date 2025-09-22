<?

	class Campi extends AppModel {
			
				var $name = 'Campi';
				var $useTable = 'Campi';
				var $primaryKey = 'Campo';
				
				var $hasMany = array(
				
					'Upload' => array(
					
						'className' => 'Upload',
						'foreignKey' => 'campi_id',
						'order' => array('Upload.order' => 'ASC')
					
					),
					'UploadImg' => array(
					
						'className' => 'Upload',
						'foreignKey' => 'campi_id',
						'conditions' => array('UploadImg.group' => 'image'),
						'order' => array('UploadImg.order' => 'ASC')
					
					),
								
					'CampiDisabled' => array(
					
						'className' => 'CampiDisabled',
						'foreignKey' => 'campo_id'
			
					
					),
				);
								
 				function __construct($id = false, $table = null, $ds = null) {
				
					parent::__construct($id, $table, $ds);
						
					$this->validate = 
					
					array(
					
						'Descrizione' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'notEmpty',
										'message' => $this->getError('REQUIRED_FIELD')
								),

						),
						'Email' => array(
						
							'email' => 
								array(
										'rule' => 'email',
										'allowEmpty' => true,
										'message' => $this->getError('VALID_EMAIL')
								),								
						
						),
						
						'EmailGestore' => array(
							
							'email' => 
								array(
										'rule' => 'email',
										'allowEmpty' => true,
										'message' => $this->getError('VALID_EMAIL')
								),								

						),						
						
						'Importo' => array(
								
							'notEmpty' => 
								array(
										'rule' => 'numeric',
										'message' => $this->getError('NUMERIC_FIELD')
								),
						),

						
					);
					
				}

				var $virtualFields = array(
				
					'NominativoGestore' => "CONCAT(Campi.CognomeGestore,' ',Campi.NomeGestore)",
					'img_evidenza' => "(SELECT path FROM files WHERE files.id = Campi.file_id)",
					'name_evidenza' => "(SELECT name FROM files WHERE files.id = Campi.file_id)",
					'descrizione_evidenza' => "(SELECT description FROM files WHERE files.id = Campi.file_id)",
					'title' => "Campi.Descrizione",
					'content' => "Campi.descrizione_campo",
					'id'    => "Campi.Campo",
					'countHour' => "(SELECT COUNT(*) FROM CampiOrari WHERE CampiOrari.campo_id = Campi.Campo)",
				
				);				

	}

?>
