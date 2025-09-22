<?=$this->element('/site/impianti/info');?>
<? if($data[0]['Campi']['countHour'] > 0): ?>
<?=$this->element('/site/impianti/booking');?>
<? endif; ?>
