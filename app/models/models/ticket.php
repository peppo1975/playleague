<?
	class Ticket extends AppModel {
	
		var $name = 'Ticket';
		var $belongsTo = array(
		'User' => array(
		'className' => 'User',
		'foreignKey' => 'user_id'
		),
		'Site' => array(
		'className' => 'Site',
		'foreignKey' => 'site_id'
		)
		); 
		var $virtualFields = array(
		'created_it' => "DATE_FORMAT(Ticket.created,'%d/%m/%Y %H:%i:%s')",
		'modified_it' => "DATE_FORMAT(Ticket.modified,'%d/%m/%Y %H:%i:%s')",
		'stato' => "(IF(Ticket.disabled = 1,'Chiuso','Aperto'))",
		'tipo_it' => "(IF(Ticket.tipo = 0,'Back Office (BO)','Front Office (FO)'))",
		'gravita_it' => "(CASE Ticket.gravita WHEN 0 THEN 'Alta' WHEN 1 THEN 'Media' WHEN 2 THEN 'Bassa' END)"
		);
		var $validate = array(
		
			'nome' => array(
			'rule' => 'notEmpty',
			'message' => 'Campo nome ticket obbligatorio.'
			)
		
		);

	}
?>