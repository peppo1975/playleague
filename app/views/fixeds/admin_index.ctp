<?

print $backend->formIndex('Fixed', array(
            'Nome variabile' =>
            array(
                'field' => 'Fixed.descrizione',
                'order' => true,
            ),
            'Valore variabile' =>
            array(
                'field' => 'Fixed.valore',
                'order' => true,
            ),
            'Note' =>
            array(
                'field' => 'Fixed.note',
                'order' => true,
            )
                )
                , array(
            'defaultOrder' => 'Fixed.descrizione',
            'defaultDir' => 'ASC',
            'pageTitle' => 'Tabella contenuti fissi',
            'quickSearch' => array('Fixed.descrizione', 'Fixed.valore'),
                )
);
?>
