<div class="preview-container">

Oggetto: <b><?=$data['Newsletter']['title'];?></b><br />
Layout:  &nbsp;<b><?=$data['Newsletter']['layout'];?></b><br />
Creata il: <b><?=$data['Newsletter']['created_it'];?></b><br />
Numero allegati: <b><?=count($data['Upload']);?></b>

<div class="clear"></div>

<a style="color: #1583D7; text-decoration: none;"  href="javascript:;" title="Invia newsletter" onclick="javascript:timmy_load('/admin/newsletters/send');" id="newsletterSend">Invia newsletter</a>

<?=$this->element('/email/html/' . $data['Newsletter']['layout'], array('data' => $data, 'text' => $data['Newsletter']['content'], 'uploads' => $data['Upload'], 'subject' => $data['Newsletter']['title']));?>

<input type="checkbox" class="index-select-checkbox" checked="checked" value="<?=$data['Newsletter']['id'];?>" style="opacity: 0;"/>

</div>