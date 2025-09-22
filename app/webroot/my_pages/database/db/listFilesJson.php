
<?php

$list = [];

foreach (glob("../db/*.gz", GLOB_BRACE) as $filename)
{
    $nomefile = pathinfo($filename); //array contenente nome, estensione e percorso del file
    $list[] = $nomefile[basename];
}

print_r(json_encode(array_reverse($list)));
?>

