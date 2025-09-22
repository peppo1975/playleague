<p>
	&nbsp;</p>
<table align="center" cellspacing="0" class="newsletter">
	<tbody>
		<tr class="logo">
			<td align="left">
				<a href="http://www.yourgame.it" title="http://www.yourgame.it"> <img alt="logo" src="http://www.midlandsport.it/files/uploads/nl_tenn1.jpg" style="width: 960px; height: 227px;" /></a></td>
		</tr>
		<tr class="post-footer">
			<td>
				<h1>
					<a href="http://www.yourgame.it" title="<?=$data['Newsletter']['title'];?>"><?=$data['Newsletter']['title'];?></a></h1>
			</td>
		</tr>
		<tr class="post-title">
			<td align="left">
				<a href="http://www.yourgame.it" title="<?=$data['Newsletter']['title'];?>"> </a><a href="http://www.yourgame.it"><? if(!isset($uploads)) $uploads = array();
				
				foreach($uploads as $tmp) {
					if($tmp['isEvidenza'] == 1 && $tmp['group'] == 'image') { $evidenza = $tmp; break; }
				}
				
				if(isset($evidenza)):
					
					?>			
				
				<img src="<?=Configure::read('server_name');?><?=$thumbnail->link(array('path' => $evidenza['path'], 'w' => 960, 'h' => 220, 'zc' => 1));?>" alt="<?=$evidenza['name'];?>" />

				
				<? endif; ?>
				 </a></td>
		</tr>
		<tr class="post-allegato">
			<td align="left">
				<?=$text;?></td>
		</tr>
		<tr class="post-message">
			<td style="text-align: center;">
				<a href="http://www.midlandsport.it">Midland Sport</a> | Via Pagnini, 13/R 50134 Firenze - T. 055 4630649 F. 055 0138719 <a href="mailto:info@midlandeuropa.com" title="mail to">store@midlandsport.it</a> P.I. 06132910487</td>
		</tr>
	</tbody>
</table>
<table align="center" cellspacing="0" class="newsletter" height="40" width="567">
	<tbody>
		<tr class="post-allegato">
			<td style="text-align: center;">
				&nbsp;</td>
		</tr>
		<tr class="post-message">
			<td style="text-align: center;">
				&nbsp;</td>
		</tr>
	</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="disclaimer" height="18" width="600">
	<tbody>
		<tr class="dislaimer-txt">
			<td>
				<?=strip_tags($disclaimer,'<a>');?></td>
		</tr>
	</tbody>
</table>
<p>
	&nbsp;</p>
