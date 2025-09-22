<p>
	&nbsp;</p>
<p>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
</p>
<table align="center" cellspacing="0" class="newsletter">
	<thead>
		<tr class="logo">
			<!-- head logo -->
			<td style="text-align: center;">
				<img alt="" src="http://www.midlandsport.it/files/uploads/forum_2014_ok22.png" style="width: 529px; height: 151px;" /></td>
		</tr>
		<!-- close head logo -->
	</thead>
	<tfoot align="center">
		<tr class="post-footer">
			<!-- footer -->
			<td>
				<p>
					578 Toscana.it | <a href="mailto:info@midlandeuropa.com" title="mail to">info@578toscana.it</a></p>
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
								
										<img src="<?=Configure::read('server_name');?><?=$thumbnail->link(array('path' => $evidenza['path'], 'w' => 960, 'h' => 220, 'zc' => 1));?>" alt="<?=$evidenza['name'];?>" />

								
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
