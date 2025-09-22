<?


	class Spool extends AppModel {
			
				var $name = 'Spool';
				var $useTable = 'timmy_spools';
				var $primaryKey = 'id';
				
				var $belongsTo = array(
					'EmailModel' => array(
					'className' => 'EmailModel',
					'foreignKey' => 'mail_id'
					)
				); 
				
				var $virtualFields = array(
				
					'modified_it' => "DATE_FORMAT(Spool.modified, '%d/%m/%Y %H:%i:%s')"
				
				);							
				
	}

?>
