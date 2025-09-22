<?

class Yearbook extends AppModel
{

    var $name = 'Yearbook';
    var $useTable = 'Annuario';
    var $primaryKey = 'Annuario';
    var $belongsTo = array(
        'Athlete' => array(
            'className' => 'Athlete',
            'foreignKey' => 'Atleta'
        ),
        'TipiAssicurazione' => array(
            'className' => 'TipiAssicurazione',
            'foreignKey' => 'TipoAssicurazione'
        ),
        'SquadreCampionati' => array(
            'className' => 'SquadreCampionati',
            'foreignKey' => 'SquadraCampionato',
        ),
    );
    var $virtualFields = array(
        'DataVidimazione_it' => "DATE_FORMAT(DataVidimazione,'%d/%m/%Y')",
        'NomeGirone' => '(SELECT Descrizione FROM GironiCampionati WHERE GironeCampionato = (SELECT GironeCampionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = Yearbook.SquadraCampionato) )',
        'NomeAtleta' => "CONCAT(Athlete.Cognome,' ',Athlete.Nome)",
        'NomeSquadra' => "(SELECT Denominazione FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra)",
        'TipoSport' => "(SELECT sport FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra)",
        'NomeSquadraCampionato' => "CONCAT((SELECT Denominazione FROM Squadre WHERE SquadreCampionati.Squadra = Squadre.Squadra),' / ',(SELECT Campionati.Nome FROM Campionati WHERE SquadreCampionati.Campionato = Campionati.Campionato))",
        'NomeAssicurazione' => '(SELECT Descrizione FROM TipiAssicurazione WHERE Yearbook.TipoAssicurazione = TipiAssicurazione.TipoAssicurazione)',
        'inUso' => "(IF((SELECT Campionati.InCorso FROM Campionati WHERE Campionati.Campionato = (SELECT SquadreCampionati.Campionato FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = Yearbook.SquadraCampionato)) = 'Si',0,1))",
        'Squadra' => "(SELECT SquadreCampionati.Squadra FROM SquadreCampionati WHERE SquadreCampionati.SquadraCampionato = Yearbook.SquadraCampionato LIMIT 1)",
    );

    function beforeDelete()
    {

        $id = $this->id;

        App::Import('Model', 'Yearbook');
        $Yearbook = new Yearbook;

        $Yearbook->read(null, $id);
        $Yearbook->set('SquadraCampionato', '');
        //$Yearbook->set('DataVidimazione', 0);
        $Yearbook->save();

        exit;
    }

    function __construct($id = false, $table = null, $ds = null)
    {

        parent::__construct($id, $table, $ds);

        $this->validate = array(
                    'Tessera' => array(
                        'notEmpty' =>
                        array(
                            'rule' => 'notEmpty',
                            'message' => $this->getError('REQUIRED_FIELD')
                        )
                    ),
                    'DataVidimazione' => array(
                        array('rule' => 'notEmpty',
                            'message' => $this->getError('REQUIRED_FIELD')
                        )
                    ),
                    'SquadraCampionatoSearch' => array(
                        array('rule' => 'notEmpty',
                            'message' => $this->getError('REQUIRED_FIELD')
                        )
                    ),
                    'AtletaSearch' => array(
                        array('rule' => 'notEmpty',
                            'message' => $this->getError('REQUIRED_FIELD')
                        )
                    ),
                    'TipoAssicurazione' => array(
                        array('rule' => 'notEmpty',
                            'message' => $this->getError('REQUIRED_FIELD')
                        )
                    ),
        );
    }

    function beforeSave()
    {

        if (!empty($this->data['Yearbook']['DataVidimazione']))
        {

            $this->dmy2ymd($this->data['Yearbook']['DataVidimazione']);
        }

        return parent::beforeSave();
    }

}

?>
