<?


	class EmailModel extends AppModel {
			
				var $name = 'EmailModel';
				var $useTable = 'timmy_mails';
				var $primaryKey = 'id';
				
				var $virtualFields = array(
				'total' => "(SELECT COUNT(*) FROM timmy_spools WHERE mail_id = EmailModel.id)",
				'status' => "IF(
				(SELECT COUNT(*) FROM timmy_spools WHERE mail_id = EmailModel.id AND sent = 1) < (SELECT COUNT(*) FROM timmy_spools WHERE mail_id = EmailModel.id)
				,'In corso','Completato')",
				'inviate' => "(SELECT COUNT(*) FROM timmy_spools WHERE timmy_spools.mail_id = EmailModel.id AND timmy_spools.sent = 1 AND timmy_spools.error = 0)",
				'errori' => "(SELECT COUNT(*) FROM timmy_spools WHERE timmy_spools.mail_id = EmailModel.id AND timmy_spools.sent = 1 AND timmy_spools.error = 1)",
				'coda' => "(SELECT COUNT(*) FROM timmy_spools WHERE timmy_spools.mail_id = EmailModel.id AND timmy_spools.sent = 0)"

				);
				
				
	}

?>
