<?
	class Post extends AppModel {
	
		var $name = 'Post';
		var $belongsTo = array(
			'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
			),
			'Ticket' => array(
			'className' => 'Ticket',
			'foreignKey' => 'ticket_id'
			),
		); 
		var $hasAndBelongsToMany = array(
				'Screenshoot' =>
				array(
				'className' => 'Screenshoot',
				'joinTable' => 'postscreen',
				'foreignKey' => 'post_id',
				'associationForeignKey' => 'screenshoot_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
				)
		); 
		var $virtualFields = array(
		'created_it' => "DATE_FORMAT(Post.created,'%d/%m/%Y %H:%i:%s')",
		'modified_it' => "DATE_FORMAT(Post.modified,'%d/%m/%Y %H:%i:%s')",
		);

	}
?>