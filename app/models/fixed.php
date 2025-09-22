<?

class Fixed extends AppModel
{

    var $name = 'Fixed';
    var $useTable = 'ContenutiFissi';
    var $primaryKey = 'id_contenuto';





    function __construct($id = false, $table = null, $ds = null)
    {

        parent::__construct($id, $table, $ds);

        $this->validate = array(
            'descrizione' => array(
                'rule' => 'notEmpty',
                'message' => $this->getError('REQUIRED_FIELD')
            ),
            'valore' => array(
                'rule' => 'notEmpty',
                'message' => $this->getError('REQUIRED_FIELD')
            ),
        );
    }





}

?>
