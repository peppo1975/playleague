<p>
	<img alt="" src="http://www.midlandsport.it/files/uploads/SFONDO_NL_YG.jpg" style="width: 960px; height: 720px;" /></p>
<table align="center" cellspacing="0" class="newsletter">
	<thead>
		<tr class="logo">
			<!-- head logo -->
			<td align="left">
				<a href="http://www.midlandsport.it" title="http://www.midlandsport.it"> &nbsp;</a></td>
		</tr>
		<!-- close head logo -->
	</thead>
	<tfoot align="center">
		<tr class="post-footer">
			<!-- footer -->
			<td>
				<p>
					<a href="http://www.midlandsport.it">Asd Midland Firenze</a> | Via Pagnini, 13/R 50134 Firenze - T. 055 4630649 F. 055 0138719 <a href="mailto:info@midlandeuropa.com" title="mail to">info@midlandeuropa.com</a> P.I. 05820810488</p>
			</td>
		</tr>
	</tfoot>
	<tbody style="background-color: #f5f5f5;">
		<tr class="post-title">
			<!-- post -->
			<td align="left">
				<a href="http://www.midlandsport.it" title="<?=$data['Newsletter']['title'];?>"> </a>
				<h1>
					<a href="http://www.midlandsport.it" title="<?=$data['Newsletter']['title'];?>"><?=$data['Newsletter']['title'];?></a></h1>
			</td>
		</tr>
		<tr class="post-allegato">
			<td align="left">
				<a href="http://www.midlandsport.it"> <? if(!isset($uploads)) $uploads = array();
			
								foreach($uploads as $tmp) {
									if($tmp['isEvidenza'] == 1 && $tmp['group'] == 'image') { $evidenza = $tmp; break; }
								}
							
								if(isset($evidenza)):
								
								?>			
								
										<img src="<?=Configure::read('server_name');?><?=$thumbnail->link(array('path' => $evidenza['path'], 'w' => 212, 'h' => 160, 'zc' => 1));?>" alt="<?=$evidenza['name'];?>" />

								
								<? endif; ?>
					 </a></td>
		</tr>
		<tr class="post-message">
			<td align="left">
				<?=$text;?></td>
		</tr>
		<!-- close post -->
	</tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" class="disclaimer">
	<tbody>
		<tr class="dislaimer-txt">
			<td>
				<div class="disclaimer-content">
					<?=strip_tags($disclaimer,'<a>');?></div>
			</td>
		</tr>
	</tbody>
</table>
<p>
	&nbsp;</p>
