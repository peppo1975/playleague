<?
	
	class ChampCategory extends AppModel {
		
		var $name = 'ChampCategory';
		var $useTable = 'CampionatiCategorie';
		
		var $hasMany = array(
		
		'Campionati' => array(
		'className' => 'Campionati',
		'foreignKey' => 'Categoria'
		),
		
		'Upload' => array(
		
		'className' => 'Upload',
		'foreignKey' => 'cat_id',
		'order' => array('Upload.order' => 'ASC')
		
		),
		); 
		
		
		
		
		var $virtualFields = array(
		
		'published_it' => "DATE_FORMAT(ChampCategory.data_inizio,'%d/%m/%Y')",
		
		);
		
		
		
		function __construct($id = false, $table = null, $ds = null) {
			
			parent::__construct($id, $table, $ds);
			
			$this->validate = 
			
			array(
			'Nome' => array(
			
			'notEmpty' => 
			array(
			'rule' => 'notEmpty',
			'message' => $this->getError('REQUIRED_FIELD') 
			)
			),
			
			
			);
			
		}
		
		
	}							