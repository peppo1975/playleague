Nuovo commento di report da parte di: <?=$comment['BrComment']['author'];?> <br />
sul sito <?=Configure::read('site_name');?> <br /><br />

<b>ID REPORT</b>: <?=$report['BrReport']['id'];?> <br />
<b>TITOLO</b>: <?=$report['BrReport']['title'];?> <br /><br />

<b>Messaggio</b>: <br />
<p>
<?=$comment['BrComment']['content'];?>
</p>

<br />Clicca <a href="<?=$link;?>" title="Visualizza discussione">qui</a> per vedere la discussione.