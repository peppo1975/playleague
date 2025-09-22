<p>
	<style type="text/css">
a{ text-decoration: none; color: #222E6E;}
		tr.post-message td a:hover{ text-decoration: underline;}
		table.newsletter{ font-family: Verdana,sans-serif; font-size: 11px; color: #374953; width: 700px;}
		table.disclaimer{font-family: Verdana,sans-serif; font-size: 10px; color: #374953; width: 600px; text-align: center;}
		tr, td{ border: 0 none; padding: 0;}
		tr.logo td a img{ border: none; display: block;}
		tr.post-title td h1{color: #C6CCF3; font-size: 30px; font-weight: normal; text-shadow: 0 1px 1px #CCCCCC; margin: 20px; font-family: "Din", Arial;}
		tr.post-allegato td a img{border: none; margin: 0 0 0 0px; display: block;}
		tr.post-allegato td p, tr.post-message td p, tr.post-message td ul li, tr.post-message td ol li{color: #999999; font-family: Arial; font-size: 14px; line-height: 1.5em; margin: 20px;}
		tr.post-message td ul li, tr.post-message td ol li{ margin: 5px;}
		tr.post-message td{ border-bottom: 2px solid #fff;}
		tr.post-footer td p{color: #666; font-size: 12px; margin: 0 0 10px 20px; padding-top: 5px;}
		tr.post-footer td p a, tr.disclaimer-txt td a{color: #222E6E; text-decoration: none;}
		tr.post-footer td p a:hover, tr.disclaim:hoverer-txt td a{ text-decoration: underline;}
		tr.disclaimer-txt{ background: #fff;}	</style>
</p>
<p>
	&nbsp;</p>
<table align="center" cellspacing="0" class="newsletter">
	<thead>
		<tr class="logo">
			<!-- head logo -->
			<td align="left">
				<a href="http://www.midlandsport.it" style="vertical-align: middle;" title="http://www.midlandsport.it"> <img alt="logo" src="http://www.midlandsport.it/img/website/logo-midland.png" style="display: inline;" /> <img alt="" src="http://www.midlandsport.it/img/website/header-graphic.png" style="display: inline; margin-left: 10px;" /> </a></td>
		</tr>
		<!-- close head logo -->
	</thead>
	<tfoot align="center">
		<tr class="post-footer">
			<!-- footer -->
			<td>
				<p>
					<a href="http://www.midlandsport.it"> Midland Global Sport SSDRL</a> | Via Pagnini, 13/R 50134 Firenze - T. 055 4630649 F. 055 0138719 <a href="mailto:info@midlandeuropa.com" title="mail to">info@midlandsport.it</a> P.I. 05820810488</p>
			</td>
		</tr>
	</tfoot>
	<tbody style="background-color: #f5f5f5;">
		<tr class="post-title">
			<!-- post -->
			<td align="left">
				<a href="http://www.midlandsport.it" title="<?=$data['Newsletter']['title'];?>"> </a>
				<h1>
					<a href="http://midlandsport.it/files/pdf/bollettini_18_01_2016_569d177a54a4e.pdf" title="<?=$data['Newsletter']['title'];?>"><?=$data['Newsletter']['title'];?></a></h1>
			</td>
		</tr>
		<tr class="post-allegato">
			<td align="left">

				<table border=0 cellpadding="5" cellspacing="5">	
				<tr>	
								<?foreach($uploads as $i =>  $tmp): ?>
								<?
									$evidenza = $tmp;
								?>
							
							
								<td>

									<a href="<? if (!empty($tmp['title'])):?>http://<?=$tmp['title'];?><?else:?>#<?endif;?>">
										<img src="<?=Configure::read('server_name');?><?=$thumbnail->link(array('path' => $evidenza['path'], 'w' => 310, 'h' => 220, 'zc' => 1));?>" alt="<?=$evidenza['name'];?>" />

									</a>
								</td>

								<? if (($i+1) % 3 == 0): ?>
									</tr><tr>
								<? endif; ?>
								<? endforeach; ?>
					 </tr>
				</table>

</td>
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
