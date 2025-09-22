<p>
	&nbsp;</p>
<p>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
</p>
<p>
	<title></title>
	<style type="text/css">
a{ text-decoration: none; color: #D299F2;}
		tr.post-message td a:hover{ text-decoration: underline;}
		table.newsletter{ font-family: Verdana,sans-serif; font-size: 11px; color: #374953; width: 700px;}
		table.disclaimer{font-family: Verdana,sans-serif; font-size: 10px; color: #374953; width: 600px; text-align: center;}
		tr, td{ border: 0 none; padding: 0;}
		tr.logo td a img{ border: none; display: block;}
		tr.post-title td a h1{color: #C6CCF3; font-size: 30px; font-weight: normal; text-shadow: 0 1px 1px #CCCCCC; margin: 20px; font-family: "Din", Arial;}
		tr.post-allegato td a img{border: none; margin: 0 0 0 20px; display: block;}
		tr.post-allegato td p, tr.post-message td p, tr.post-message td ul li, tr.post-message td ol li{color: #999999; font-family: Arial; font-size: 14px; line-height: 1.5em; margin: 20px;}
		tr.post-message td ul li, tr.post-message td ol li{ margin: 5px;}
		tr.post-message td{ border-bottom: 2px solid #fff;}
		tr.post-footer td p{color: #666; font-size: 12px; margin: 0 0 10px 20px; padding-top: 5px;}
		tr.post-footer td p a, tr.disclaimer-txt td a{color: #D299F2; text-decoration: none;}
		tr.post-footer td p a:hover, tr.disclaim:hoverer-txt td a{ text-decoration: underline;}
		tr.disclaimer-txt{ background: #fff;}	</style>
</p>
<p>
	&nbsp;</p>
<p>
	&nbsp;</p>
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
<p>
	<br />
	<br />
	&nbsp;</p>
