<?
print $backend->formIndex('ChampCategory',
                array(
                    'Categoria' =>
                    array(
                        'field' => 'ChampCategory.Nome',
                        'order' => true,
                    ),
                    'Fattore correttivo ' =>
                    array(
                        'field' => 'ChampCategory.fattore_campionato',
                        'order' => true,
                    ),
                    // GIUSEPPE 03/10/2016  ------------
                    'Sport' =>
                    array(
                        'field' => 'ChampCategory.sport',
                        'order' => true,
                    ),
                    // --------------
                    'Data inizio torneo' =>
                    array(
                        'field' => 'ChampCategory.published_it',
                        'order' => true
                    )
                )
                , array(
            'defaultOrder' => 'ChampCategory.Nome',
            'defaultDir' => 'ASC',
            'pageTitle' => 'Tabella categorie campionati',
));
?>

<!--//GIUSEPPE 2019-11-20 **********************************************************-->
<script>

    $("table tr").each(function (index)
    {
        //console.log(index + ": " + $(this));

        celle = $("td", this);

        sport = celle.eq(3).html();
        data = celle.eq(4).html();
        console.log(data);

        if (sport === "CALCIO")
        {
            celle.eq(2).html("");
        }
        if (data === '00/00/0000')
        {
            celle.eq(4).html("");
        }

    });
</script>
<!--//******************************************************************************-->


