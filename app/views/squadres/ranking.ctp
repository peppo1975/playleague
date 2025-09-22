<?

$query_list = array();

do
{
    $query_list[] = $row_Recordset;
}
while ($row_Recordset = mysql_fetch_assoc($result));


$rename = "";

$list_query = array();

$array_clear = array();

foreach ($query_list as $unique_query)
{

    $rename['sc']['IdSquadra'] = $unique_query['IdSquadra'];
    $rename['s']['NomeSquadra'] = $unique_query['squadra'];
    $rename['g']['SetTennis'] = $unique_query['set_tennis'];

    $list_query[] = $rename;
}




foreach ($list_query as $query)
{
    $decode_set = json_decode($query['g']['SetTennis'], true);

    if (isset($decode_set['athletes']))
    {
        if ($query['sc']['IdSquadra'] != "")
        {
            $result = read_tennis_players($query);
            //echo json_encode($result);
            foreach ($result as $i)
            {
                $array_clear[] = $i;
            }
        }
    }
}




// ordinamento bubble sort in base al nome cognome --------------------------

while (true)
{
    $temp;
    $scambio = false;

    for ($i = 0; $i < count($array_clear) - 1; $i++)
    {

        if ($array_clear[$i]['0']['anagrafica'] > $array_clear[$i + 1]['0']['anagrafica'])
        {
            //if ((int) $array_clear[$i]['0']['id_atleta_tennis'] > (int) $array_clear[$i + 1]['0']['id_atleta_tennis']) {
            $temp = $array_clear[$i + 1];
            $array_clear[$i + 1] = $array_clear[$i];
            $array_clear[$i] = $temp;
            $scambio = true;
        }
    }

    if (!$scambio)
        break;
}


// SOMMO I PUNTEGGI ---------------------------------------------------------------------

$atl_tenn;

$switch = true;

$array_final = array();

$array_final_temp = array();

foreach ($array_clear as $atl_tenn)
{
    $id = $atl_tenn['0']['id_atleta_tennis'];

    $array_final_temp[$id]['sc']['IdSquadra'] = $atl_tenn['sc']['IdSquadra'];

    $array_final_temp[$id]['s']['NomeSquadra'] = $atl_tenn['s']['NomeSquadra'];

    $array_final_temp[$id]['0']['anagrafica'] = $atl_tenn['0']['anagrafica'];

    $array_final_temp[$id]['0']['sesso'] = $atl_tenn['0']['sesso'];

    $array_final_temp[$id]['0']['goals'] += $atl_tenn['0']['goals'];

    $array_final_temp[$id]['0']['id_atleta_tennis'] = $atl_tenn['0']['id_atleta_tennis'];
}

// in $array_final_temp gli indici non vanno da 0,1,2,3... ma seguono l'id atleta. Sono comunque ordinati alfabeticamente.
// con questo ciclo creo un array $array_final, ordinato alfabeticamente e con gli indici pari a 0,1,2....n
foreach ($array_final_temp as $i)
{
    $array_final[] = $i;
}


// ordinamento bubble sort in base ai punti aleta --------------------------

while (true)
{
    $temp;

    $scambio = false;

    for ($i = 0; $i < count($array_final) - 1; $i++)
    {
        if ((int) $array_final[$i]['0']['goals'] < (int) $array_final[$i + 1]['0']['goals'])
        {
            $temp = $array_final[$i + 1];
            $array_final[$i + 1] = $array_final[$i];
            $array_final[$i] = $temp;
            $scambio = true;
        }
    }

    if (!$scambio)
        break;
}
?>


