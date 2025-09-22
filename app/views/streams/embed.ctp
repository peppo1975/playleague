<div style="margin: 0 auto;" class="video-embed">
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" height="300" id="embedVideo" name="embedPlayer">
	   <param name="movie" value="http://fms.shared.streamshow.it/jwplayer/player.swf">
	   <param name="allowfullscreen" value="true">
	   <param name="allowscriptaccess" value="always">
	   <param name="flashvars" value="file=http://<?=$_SERVER['HTTP_HOST'] . $path;?>">
	   <embed id="embedVideo"
			  name="embedVideo"
			  src="http://fms.shared.streamshow.it/jwplayer/player.swf"
			  width="640"
			  height="360"
			  allowscriptaccess="always"
			  allowfullscreen="true"
			  flashvars="file=http://<?=$_SERVER['HTTP_HOST'] . $path;?>"
	   />
	</object>
<div>