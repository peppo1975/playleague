<p>
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700&amp;display=swap" rel="stylesheet" />
</p>
<table border="0" cellpadding="0" cellspacing="0" style="margin: 50px auto 0px; background-color: #ffffff; font-family: 'Raleway', sans-serif; line-height: 1.8em; color: #000000;" width="760px">
	<thead style="background-color: #f4f4f4;">
		<tr>
			<td align="left">
				<a href="https://www.mgstennis.it"> <img alt="" src="https://www.midlandsport.it/files/uploads/banner-newsletter-mgstennis.jpg" style="width: 760px;" /> </a></td>
		</tr>
	</thead>
	<tfoot>
		<tr class="dislaimer-txt">
			<td align="center" style="padding:40px 20px 20px; font-size: 12px;">
				<?=strip_tags($disclaimer,'<a>');?></td>
		</tr>
	</tfoot>
	<tbody style="background-color: #f4f4f4;">
		<tr class="post-title">
			<!-- post -->
			<td align="left" style="padding: 20px;">
				<h1 style="font-weight: 700;">
					<?=$data['Newsletter']['title'];?></h1>
			</td>
		</tr>
		<tr class="post-allegato">
			<td align="left">
				<? if(!isset($uploads)) $uploads = array();
				
				foreach($uploads as $tmp) {
					if($tmp['isEvidenza'] == 1 && $tmp['group'] == 'image') { $evidenza = $tmp; break; }
				}
				
				if(isset($evidenza)):
					
					?>			

					<img src="<?=Configure::read('server_name');?><?=$thumbnail->link(array('path' => $evidenza['path'], 'w' => 760, 'h' => 450, 'zc' => 1));?>" alt="<?=$evidenza['name'];?>" />


				<? endif; ?>
				</td>
		</tr>
		<tr class="post-message">
			<td align="left" style="padding: 20px 20px 60px; font-size: 15px; line-height: 25px; ">
				<?=$text;?></td>
		</tr>
		<tr>
			<td align="center" style="padding:0 20px 40px; font-size: 13px;">
				<p style="width: 180px; border-top: 1px solid #999999;">
					&nbsp;</p>
				<p>
					<a href="https://www.facebook.com/mgstennis/" style="text-decoration:none;"> <img src="https://www.midlandsport.it/files/uploads/social-facebook.png" style="width: 37px; height: 37px;" /> </a> <a href="https://twitter.com/MidlandSport" style="text-decoration:none;"> <img src="https://www.midlandsport.it/files/uploads/social-twitter.png" style="width: 37px; height: 37px;" /> </a></p>
				<p>
					<strong style="font-weight: 700;">Midland Global Sport SSDRL</strong><br />
					Via Pagnini, 13/R 50134 Firenze - T. 055 4630649 - <a href="mailto:info@mgstennis.it" style="color: #29a9e1; text-decoration:none;">info@mgstennis.it</a></p>
			</td>
		</tr>
	</tbody>
</table>
