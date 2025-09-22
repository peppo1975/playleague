<? $fpdf->setup('L','mm','a4'); ?>
<? $fpdf->SetMargins('0','0'); ?>

<? $fpdf->SetAutoPageBreak(false,0); ?>

<? $avversari = array('Casa','Trasferta'); ?>

	<?$i = 0;?>
	<?$y = 0;?>
	<?$x = 0;?>

<? foreach ($matches as $partita): ?>

	<? foreach ($avversari as $avversario): ?>

	<? $r = $i%4;?>
	<? $fpdf->AddFont('CalibriB','','calibrib.php'); ?>
	<? $fpdf->AddFont('CalibriI','','calibrii.php'); ?>	
	<? $fpdf->AddFont('Calibri','','calibri.php'); ?>	
	<? $fpdf->SetFont('CalibriB','',8); ?>
	<? $fpdf->SetTextColor(232,24,40); ?>

	<?if($r == 0):?>
	
		<? $fpdf->AddPage(); ?>	
		<? $k = 0; ?>
		<?for($j = 5; $j<210; $j+=5) {

			$fpdf->Line(148,3+$k,148,$j);
			
			$k += 5;
		
		}?>
		<? $k = 0; ?>
		<?for($j = 5; $j<340; $j+=5) {

			$fpdf->Line(3+$k,96,$j,96);
			
			$k += 5;
		
		}?>		

		<? $i = 0; ?>
		<? $x = 0; ?>
		<? $y = 0; ?>
		
	<?elseif($r == 1):?>
		
		<? $x = 150; ?>
		<? $y = 0; ?>
		
	<?elseif($r == 2):?>
	
		<? $x = 0; ?>
		<? $y = 100; ?>
	
	<?elseif($r == 3):?>
	
		<? $x = 150; ?>
		<? $y = 100; ?>
	
	<?endif;?>
	
	<?  
	
		//$fpdf->Image(APP . '/webroot/img/logo_midland_pdf.jpg',105+$x,50+$y,30);
		
                //GIUSEPPE 2022-09-13 -------------------------------------------
                if($partita['Campionati']['PlayLeague']==0)
                    $fpdf->Image(APP . '/webroot/img/logo_midland_pdf.jpg',105+$x,50+$y,30);
                if($partita['Campionati']['PlayLeague']==1)
                    $fpdf->Image(APP . '/webroot/img/pdf/playleague-logo-ricevute.jpg',105+$x,50+$y,30); // inserire logo playleague
                // --------------------------------------------------------------
        
		$fpdf->SetY(10+$y);  $fpdf->SetX(5+$x);
		
		$sc = 0;
		
		if ($avversario == 'Trasferta') $sc = 1;

		$fpdf->Write(5,'cod. ' . str_pad($partita['Match']['Calendario'],9,"0",STR_PAD_LEFT) . $sc);
		
		$fpdf->SetX(95+$x);
		
		
		$fpdf->SetFont('CalibriB','',14);
		
		$fpdf->Write(5,'Data ');
		$fpdf->SetTextColor(0,0,0);
		
		$fpdf->Write(5,date("d/m/Y",strtotime($partita['Match']['Data'])));
		
		$fpdf->SetY(20+$y);
		
		$fpdf->SetFont('CalibriB','',16);
		$fpdf->SetTextColor(232,24,40);
		$fpdf->SetX(5+$x);
		$fpdf->Write(5,'Riceviamo da');
		
		$fpdf->SetY(27+$y);
		
		$fpdf->SetFont('CalibriB','',16);
		$fpdf->SetTextColor(0,0,0);
		$fpdf->SetX(5+$x);
		$fpdf->Write(5,$partita[$avversario . "Info"]['Squadre']['Denominazione']);
		
		$fpdf->SetY(37+$y);
		
		$fpdf->SetFont('CalibriB','',16);
		$fpdf->SetTextColor(232,24,40);
		$fpdf->SetX(90+$x);
		$fpdf->Write(5,'Euro ');
		
		
		if ($partita['Campi']['Importo'] == "") $partita['Campi']['Importo'] = "0,00";
		
		if($partita[$avversario]['Pagato'] == 'Si') $pagamento = ' GIA\' PAGATO';
		else $pagamento = str_replace(".",",",$partita['Campi']['Importo']);
		
		$fpdf->SetFont('CalibriB','',16);
		$fpdf->SetTextColor(0,0,0);
		$fpdf->SetX(105+$x);
		$fpdf->Write(5, $pagamento);
		
		$fpdf->SetY(55+$y);
		
		$fpdf->SetFont('CalibriB','',16);
		$fpdf->SetTextColor(232,24,40);
		$fpdf->SetX(5+$x);
		$fpdf->Write(5,'Per quota relativa alla gara:');
		
		$fpdf->SetY(62+$y);
		$fpdf->SetFont('CalibriI','',12);
		$fpdf->SetTextColor(0,0,0);
		$fpdf->SetX(5+$x);
		$fpdf->Write(5,$partita["CasaInfo"]['Squadre']['Denominazione'] . ' - ' . $partita["TrasfertaInfo"]['Squadre']['Denominazione']);
		
		$fpdf->SetY(69+$y);
		
		$fpdf->SetFont('Calibri','',12);
		$fpdf->SetTextColor(232,24,40);
		$fpdf->SetX(5+$x);
		$fpdf->Write(5,'presso l\'impianto ');
		$fpdf->SetFont('CalibriI','',12);
		$fpdf->SetTextColor(0,0,0);	
		$fpdf->Write(5,$partita['Campi']['Descrizione']);
		$fpdf->SetY(77+$y);
		$fpdf->SetX(5+$x);
		$fpdf->SetFont('CalibriB','',12);	
		$fpdf->Write(5,$partita['Campionati']['Nome']);		
		$fpdf->SetY(88+$y);
		$fpdf->SetX(5+$x);
		$fpdf->SetFont('CalibriB','',12);	
		$fpdf->Write(5,'Ore: ' .  $partita['Match']['Ora'] . " " . "Girone: " . $partita['Half']['Descrizione']);		
		
		
	?>
	
	<?$i++;?>
	
	<? endforeach; ?>

<? endforeach; ?>

<?$fpdf->output('files/pdf/gestione_campionati_ricevute_campi_'.date("d_m_Y").'.pdf', 'F'); ?>
<?=json_encode(array('link' => '/files/pdf/gestione_campionati_ricevute_campi_'.date("d_m_Y").'.pdf'));?>

