<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Iscrizione campionati http://www.midlandsport.it</title>
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
		tr.disclaimer-txt{ background: #fff;}
	</style>
	
</head>
<body style="margin 0 auto;" >
	<table align="center" class="newsletter" cellspacing="0">
		<thead>
		<tr class="logo">
			<!-- head logo -->
			<td align="left">

					

				<img src="http://www.midlandsport.it/img/signupft.jpg" alt=""/>

</td>
		</tr>
		</thead>
		<tbody style="background-color: #f5f5f5;">
			<tr class="post-message">
				<td align="left">

				<div style="padding: 10px;">


Gentile <?=$product["nome"]?> <?=$product["cognome"]?>,<br>
ti confermiamo l’acquisto di:<br>
<b><?=$product["product_name"]?>  &nbsp;€ <?=$product["product_price"]?></b><br>
in data <?=date("d/m/Y H:i");?>.
<br><br>
Riepilogo dati:<br>
Nome: <b><?=$product["nome"]?></b><br>
Cognome: <b><?=$product["cognome"]?></b><br>
Email: <b><?=$product["email"]?></b><br>
Telefono: <b><?=$product["telefono"]?></b>

</div>

				</td>
			</tr><!-- close post -->
			
		</tbody>

	</table>
	
	
</body>
</html>