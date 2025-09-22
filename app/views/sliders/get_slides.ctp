<? 
	
	$ret = array();
	
	foreach ($slides as $slide) {
	
		$tmp = $slide['Slider'];
		
		if (count($slide['Upload'])) {
			
			$path = $thumbnail->link(array('path' => $slide['Upload'][0]['path'],'w' => 277,'h' => 202,'zc' => 1,'aoe' => 1,'f' => 'png'));
			
			$tmp['path'] = $path;
			
			$ret[] = $tmp;
			
		}
		
	}
?>
<?=json_encode(array('slider' => $ret));?>	
