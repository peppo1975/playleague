<? if(count($campionati)) {

echo $this->element('/site/filter_home', array('campionati' => $campionati));

} else {

	echo "Nessun campionato presente";

}

?>