<!-- //GIUSEPPE 2018-12-15 template email per alert certificato medico -->
<!-- la pagina è nei contenuti fissi di admin: Sito internet -> Tabella contenuti fissi -> alert_medico  -->
<?
if ($data_nascita !== "0000-00-00")
{
    $nascita = explode("-", $data_nascita);

    $data_nascita = sprintf("%s/%s/%s", $nascita[2], $nascita[1], $nascita[0]);
}
else
{
    $data_nascita = "- - - -";
}

$scadenza = explode("-", $data_scadenza);

$data_scadenza = sprintf("%s/%s/%s", $scadenza[2], $scadenza[1], $scadenza[0]);

?>
<?
$mail = sprintf($messaggio, strtoupper($nominativo), $data_nascita, (int) $giorni, $data_scadenza, $telefono, $email);
?>

<?= $mail; ?>