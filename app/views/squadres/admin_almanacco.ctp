<? $ext_ammesse = array('png','jpg','jpeg'); ?>
<? $fpdf->setup('P','mm',array('170','240')); ?>
<? $fpdf->SetFont('Helvetica','',6); ?>
<? $fpdf->SetMargins('10','10.0');
 $fpdf->SetPadding('1.5');
 $fpdf->SetSpacing('2','2');
 ?>

	<? foreach($almanacco as $campionato): ?>

		<?ob_start();?>	
		<table width="150" border="0">
			<tr><td align="center" style="bold" size="36px"><?=$campionato['InfoCampionato']['Nome'];?></td></tr>
		</table>
		<?$pagina_campionato = ob_get_clean();?>

		<? $fpdf->AddPage(); ?>
		<? $fpdf->htmltable($pagina_campionato);?>
		<? $fpdf->SetY(150); ?>
		<? $fpdf->Image(APP . '/webroot/img/logo_midland_pdf.jpg',70,50,30); ?>
		
		<? foreach($campionato['Gironi'] as $girone): ?>	

			<?ob_start();?>	
			<table width="150" border="0">
				<tr>
					<td align="center" style="bold" size="28px"><?=$girone['InfoGirone']['Descrizione'];?></td>
				</tr>
			</table>
			<?$pagina_girone = ob_get_clean();?>	
			
			<? $fpdf->AddPage(); ?>
			<? $fpdf->htmltable($pagina_girone);?>
			<? $fpdf->Image(APP . '/webroot/img/logo_midland_pdf.jpg',70,50,30); ?>
			
			<? foreach($girone['Squadre'] as $squadra): ?>
			
			<? 
			
				/*
				print "<pre>";
				print_r($squadra['Squadre']);
				print "</pre>";
				
				print "<pre>";
				print_r($squadra['Upload']['Squadra'][0]); 
				print "</pre>";
				
				var_dump(is_file(APP . 'webroot/' . $squadra['Upload']['Squadra'][0]['path']));
				*/
			
			
			?>
			
				<?ob_start();?>	
				
				<table width="150" border="1">
					<tr>
						<? if(isset($squadra['Upload']['Logo'][0]) && is_file(APP . 'webroot' . $squadra['Upload']['Logo'][0]['path']) && in_array($squadra['Upload']['Logo'][0]['ext'], $ext_ammesse)): ?>
						
						<?
						
					   	if($squadra['Upload']['Logo'][0]['ext'] == 'png') {
					   		
							$originalFile = APP . 'webroot' . $squadra['Upload']['Logo'][0]['path'];
							$info_png = pathinfo($squadra['Upload']['Logo'][0]['path']);
							$file_name    = $info_png['filename'] . '.' . 'jpg';
							$outputFile   = APP . 'webroot/files/uploads/' . $file_name; 
						    $image = imagecreatefrompng($originalFile);
						    imagejpeg($image, $outputFile, 100);
						    imagedestroy($image);
						    $squadra['Upload']['Logo'][0]['path'] = '/files/uploads/' . $file_name;					   		
					   		
					   	}						
					    
					    $thumbnail_link = $thumbnail->link(array('path' => $squadra['Upload']['Logo'][0]['path'], 'w' => 100, 'q' => '60', 'f' => 'jpg'));
					    $info_image = getimagesize(APP . 'webroot' . $thumbnail_link);
					    
					    $height    = $info_image[2]/2.83464567;
					    $width_img = $info_image[1]/2.83464567;
					    $width     = ((125/4) - (($info_image[1]/2.83464567)/2));
					    
					    $info = pathinfo($thumbnail_link);
					    copy(APP . 'webroot' . $thumbnail_link, APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension']);
					    
						?>		
									
							<td height="<?=$height;?>" width="<?=$width_img;?>">
								<img align="center" src="<?=APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension'];?>" />
							</td>
							
					
						
						<? endif; ?>
						<td align="center" style="bold" size="20px"><?=$squadra['Squadre']['Denominazione'];?></td>
					</tr>
				</table>
				
				<? if(isset($squadra['Upload']['Coccarda']) && !empty($squadra['Upload']['Coccarda'])): ?>
		
				<table width="150" border="1">	
				
				<tr border="1" align="center">						
				
					<? foreach($squadra['Upload']['Coccarda'] as $coccarda): ?>
					
						<?
						 if(is_file(APP . 'webroot' . $coccarda['path']) && in_array($coccarda['ext'], $ext_ammesse)) {
							
						   	if($coccarda['ext'] == 'png') {
						   		
								$originalFile_coccarda = APP . 'webroot' . $coccarda['path'];
								$info_png_coccarda = pathinfo($coccarda['path']);
								$file_name_coccarda    = $info_png_coccarda['filename'] . '.' . 'jpg';
								$outputFile_coccarda   = APP . 'webroot/files/uploads/' . $file_name_coccarda; 
							    $image_coccarda = imagecreatefrompng($originalFile_coccarda);
							    imagejpeg($image_coccarda, $outputFile_coccarda, 100);
							    imagedestroy($image_coccarda);
							    $coccarda['path'] = '/files/uploads/' . $file_name_coccarda;					   		
						   		
						   	}
						    $thumbnail_link_coccarda = $thumbnail->link(array('path' => $coccarda['path'], 'w' => 50, 'q' => '60', 'f' => 'jpg'));
						    $info_image = getimagesize(APP . 'webroot' . $thumbnail_link_coccarda);
						    
						    $height_coccarda    = $info_image[2]/2.83464567;
						    $width_img_coccarda = $info_image[1]/2.83464567;
						    $width_coccarda     = ((125/4) - (($info_image[1]/2.83464567)/2));
						    
						    $info_coccarda= pathinfo($thumbnail_link_coccarda);
						    copy(APP . 'webroot' . $thumbnail_link_coccarda, APP . 'webroot/files/tmp/images/' . $info_coccarda['filename'] . '.' . $info_coccarda['extension']);								   											
							
						?>
						
						<td align="center" height="<?=$height_coccarda;?>" width="<?=$width_img_coccarda;?>">
							<img align="center" src="<?=APP . 'webroot/files/tmp/images/' . $info_coccarda['filename'] . '.' . $info_coccarda['extension'];?>" />
						</td>								
						
						<?	
							
						}
						?>
					<? endforeach; ?>
					
					<td>&nbsp;</td>

				</tr>						
		
				</table>
				
				<? endif; ?>					
				
				<? if(isset($squadra['Upload']['Squadra'][0]) && is_file(APP . 'webroot' . $squadra['Upload']['Squadra'][0]['path']) && in_array($squadra['Upload']['Squadra'][0]['ext'], $ext_ammesse)): ?>
				
				<?
				
			   	if($squadra['Upload']['Squadra'][0]['ext'] == 'png') {
			   		
					$originalFile = APP . 'webroot' . $squadra['Upload']['Squadra'][0]['path'];
					$info_png = pathinfo($squadra['Upload']['Squadra'][0]['path']);
					$file_name    = $info_png['filename'] . '.' . 'jpg';
					$outputFile   = APP . 'webroot/files/uploads/' . $file_name; 
				    $image = imagecreatefrompng($originalFile);
				    imagejpeg($image, $outputFile, 100);
				    imagedestroy($image);
				    $squadra['Upload']['Squadra'][0]['path'] = '/files/uploads/' . $file_name;					   		
			   		
			   	}					
			    
			    $thumbnail_link = $thumbnail->link(array('path' => $squadra['Upload']['Squadra'][0]['path'], 'w' => 700, 'q' => '60', 'f' => 'jpg'));
			    $info_image = getimagesize(APP . 'webroot' . $thumbnail_link);
			    
			    $height    = $info_image[2]/2.83464567;
			    $width_img = $info_image[1]/2.83464567;
			    $width     = ((125/4) - (($info_image[1]/2.83464567)/2));
			    
			    $info = pathinfo($thumbnail_link);
			    copy(APP . 'webroot' . $thumbnail_link, APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension']);
			    
				?>
				
				<table width="150" border="1" cellspacing="0" cellpadding="0">					
					<tr>
						<td colspan="4" align="center" valign="bottom" size="10px" style="bold">LA FOTO SOCIALE</td>
					</tr>				
					<tr border="1" align="center">
						<td width="<?=$width;?>">&nbsp;</td>
						<td align="center" height="<?=$height;?>" width="<?=$width_img;?>">
							<img align="center" src="<?=APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension'];?>" />
						</td>
					</tr>
				</table>
				
				<? else: ?>	
				
				<table width="150" border="1">
					<tr>
						<td style="bold" align="center" size="8px">FOTO SQUADRA NON PERVENUTA</td>
					</tr>
				</table>							
				
				<? endif; ?>
				
				<? if(isset($squadra['Atleti']) && count($squadra['Atleti'])): ?>
				
				<table width="150" border="1">
					
					<tr>
						<td style="bold" colspan="2" align="center" size="10px">LA ROSA ATTUALE</td>
					</tr>
					
					<tr>
						<td align="left" size="10px">Nome</td>
						<td align="left" size="10px">Nome</td>
					</tr>
					
					
					<? for($i = 0; $i < count($squadra['Atleti']); $i=$i+2): ?>
					
						<tr>
							<td size="9px" style="bold">
								<?=(isset($squadra['Atleti'][$i]['Yearbook']['NomeAtleta']))? $squadra['Atleti'][$i]['Yearbook']['NomeAtleta']:'';?>
							</td>
							<td size="9px" style="bold">
								<?=(isset($squadra['Atleti'][$i+1]['Yearbook']['NomeAtleta']))? $squadra['Atleti'][$i+1]['Yearbook']['NomeAtleta']:'';?>
							</td>							
						</tr>	
						
					<? endfor; ?>					
					
					

				</table>					
				
				<? endif; ?>
				

				<?$pagina_squadra_prima = ob_get_clean();?>	
			
				<?ob_start();?>
				
				<table width="150" border="1">
					<tr>
						<? if(isset($squadra['Upload']['Logo'][0]) && is_file(APP . 'webroot' . $squadra['Upload']['Logo'][0]['path']) && in_array($squadra['Upload']['Logo'][0]['ext'], $ext_ammesse)): ?>
						
						<?
						
					   	if($squadra['Upload']['Logo'][0]['ext'] == 'png') {
					   		
							$originalFile = APP . 'webroot' . $squadra['Upload']['Logo'][0]['path'];
							$info_png = pathinfo($squadra['Upload']['Logo'][0]['path']);
							$file_name    = $info_png['filename'] . '.' . 'jpg';
							$outputFile   = APP . 'webroot/files/uploads/' . $file_name; 
						    $image = imagecreatefrompng($originalFile);
						    imagejpeg($image, $outputFile, 100);
						    imagedestroy($image);
						    $squadra['Upload']['Logo'][0]['path'] = '/files/uploads/' . $file_name;					   		
					   		
					   	}						
					    
					    $thumbnail_link = $thumbnail->link(array('path' => $squadra['Upload']['Logo'][0]['path'], 'w' => 100, 'q' => '60', 'f' => 'jpg'));
					    $info_image = getimagesize(APP . 'webroot' . $thumbnail_link);
					    
					    $height    = $info_image[2]/2.83464567;
					    $width_img = $info_image[1]/2.83464567;
					    $width     = ((125/4) - (($info_image[1]/2.83464567)/2));
					    
					    $info = pathinfo($thumbnail_link);
					    copy(APP . 'webroot' . $thumbnail_link, APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension']);
					    
						?>		
									
							<td height="<?=$height;?>" width="<?=$width_img;?>"><img align="center" src="<?=APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension'];?>" /></td>
							
					
						
						<? endif; ?>
						<td align="center" style="bold" size="20px"><?=$squadra['Squadre']['Denominazione'];?></td>
					</tr>
				</table>
				
				<?$pagina_squadra_intestazione = ob_get_clean();?>	
				
				<?ob_start();?>				
				
				<? if(isset($squadra['Squadre']['Storia']) && !empty($squadra['Squadre']['Storia'])): ?>
				
				<? 
					
					$limit = 90; 
					$text  = html_entity_decode($squadra['Squadre']['Storia']);
					$l     = strlen($text);
					$page  = ceil($l/$limit);
					
					$comunication = '';
					
					for($i = 0; $i < $page; $i++) {
						
						$comunication .= substr($text, $i*$limit, $limit) . "\n\n";
						
					}
					
					$comunication = nl2br($comunication);
				
				?>				
				
				<table width="150" border="1" cellpadding="2" cellspacing="2">
					<tr>
						<td align="center" size="10px" style="bold">BREVE STORIA</td>
					</tr>					
					<tr>
						<?
						
							$storia = $squadra['Squadre']['Storia'];							
							$storia = strip_tags($storia);			
							$storia = html_entity_decode($storia, ENT_QUOTES, 'UTF-8');											
							$storia = trim($storia);
							
						?>
						<td size="9px" align="left" cellpadding="2" cellspacing="2"><?=$storia;?></td>
					</tr>
				</table>
				
				<? $isSet_storia = 1; ?>					
				
				<? endif; ?>
				
				<? if(isset($squadra['SquadreAlbo']) && count($squadra['SquadreAlbo'])): ?>
				
				<table width="150" border="1">
					
					<tr>
						<td align="center" colspan="2" size="10px" style="bold">ALBO D'ORO</td>
					</tr>						
					
					<tr>
						<td size="10px" width="100" style="bold">Campionato</td>
						<td size="10px">Posizione</td>
					</tr>				
				
					<? foreach($squadra['SquadreAlbo'] as $albo): ?> 
					
					<tr>
						<td style="bold" size="9px" width="100"><?=$albo['Campionato'];?></td>
						<td size="9px"><?=$albo['Posizione'];?></td>
					</tr>						
					
					<? endforeach; ?>
					
				</table>
				
				<? $isSet_albo = 1; ?>
				
				<? endif; ?>
				
				<?$pagina_storia_albo = ob_get_clean();?>	
				
				<?ob_start();?>		
				
				<? if(isset($squadra['Upload']['Sponsor']) && count($squadra['Upload']['Sponsor'])): ?>
				
				<table width="150" border="0">
				
				<tr height="20px">
					<td height="20px">&nbsp;</td>
				</tr>
				
				<? $sponsors = array(); ?>
				
				<? foreach($squadra['Upload']['Sponsor'] as $sponsor): ?>
				
				   	<?
				   	
				   	if(!is_file(APP . 'webroot' . $sponsor['path']) || !in_array($sponsor['ext'], $ext_ammesse)) continue;
				   	
				   	if($sponsor['ext'] == 'png') {
				   		
						$originalFile = APP . 'webroot' . $sponsor['path'];
						$info_png = pathinfo($sponsor['path']);
						$file_name    = $info_png['filename'] . '.' . 'jpg';
						$outputFile   = APP . 'webroot/files/uploads/' . $file_name; 
					    $image = imagecreatefrompng($originalFile);
					    imagejpeg($image, $outputFile, 100);
					    imagedestroy($image);
					    $sponsor['path'] = '/files/uploads/' . $file_name;					   		
				   		
				   	}
				   	
				    $thumbnail_link_sponsor = $thumbnail->link(array('path' => $sponsor['path'], 'w' => 340, 'q' => '60'));
				    $info_sponsor_image_sponsor = getimagesize(APP . 'webroot' . $thumbnail_link_sponsor);
				    
				    $height_sponsor    = $info_sponsor_image_sponsor[2]/2.83464567;
				    $width_sponsor_img = $info_sponsor_image_sponsor[1]/2.83464567;
				    $width_sponsor     = ((125/4) - (($info_sponsor_image_sponsor[1]/2.83464567)/2));
				    
				    $info_sponsor = pathinfo($thumbnail_link_sponsor);
				    copy(APP . 'webroot' . $thumbnail_link_sponsor, APP . 'webroot/files/tmp/images/' . $info_sponsor['filename'] . '.' . $info_sponsor['extension']);
				    
				    $isSet_sponsor = 1;
				    
					?>						
				
					
				
					<tr border="1" align="center">
						<td width="<?=$width_sponsor;?>">&nbsp;</td>
						<td align="center" height="<?=$height_sponsor;?>" width="<?=$width_sponsor_img;?>"><img align="center" src="<?=APP . 'webroot/files/tmp/images/' . $info_sponsor['filename'] . '.' . $info_sponsor['extension'];?>" /></td>
					</tr>
					
				<? endforeach; ?>
					
				</table>				
				
				<? endif; ?>
				
				<? if(isset($squadra['Upload']['SponsorEsterno'][0]) && is_file(APP . 'webroot' . $squadra['Upload']['SponsorEsterno'][0]['path']) && in_array($squadra['Upload']['SponsorEsterno'][0]['ext'], $ext_ammesse)): ?>
				
				<?
				
			   	if($squadra['Upload']['SponsorEsterno'][0]['ext'] == 'png') {
			   		
					$originalFile = APP . 'webroot' . $squadra['Upload']['SponsorEsterno'][0]['path'];
					$info_png = pathinfo($squadra['Upload']['SponsorEsterno'][0]['path']);
					$file_name    = $info_png['filename'] . '.' . 'jpg';
					$outputFile   = APP . 'webroot/files/uploads/' . $file_name; 
				    $image = imagecreatefrompng($originalFile);
				    imagejpeg($image, $outputFile, 100);
				    imagedestroy($image);
				    $squadra['Upload']['SponsorEsterno'][0]['path'] = '/files/uploads/' . $file_name;					   		
			   		
			   	}					
			    
			    $thumbnail_link = $thumbnail->link(array('path' => $squadra['Upload']['SponsorEsterno'][0]['path'], 'w' => 750, 'q' => '60', 'f' => 'jpg'));
			    $info_image = getimagesize(APP . 'webroot' . $thumbnail_link);
			    
			    $height    = $info_image[2]/2.83464567;
			    $width_img = $info_image[1]/2.83464567;
			    $width     = ((125/4) - (($info_image[1]/2.83464567)/2));
			    
			    $info = pathinfo($thumbnail_link);
			    copy(APP . 'webroot' . $thumbnail_link, APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension']);
			    
				?>
				
				<table width="150" border="0" cellspacing="0" cellpadding="0">
					<tr height="20px">
						<td height="20px" colspan="2">&nbsp;</td>
					</tr>											
					<tr align="center">
						<td width="<?=$width;?>">&nbsp;</td>
						<td align="center" height="<?=$height;?>" width="<?=$width_img;?>"><img align="center" src="<?=APP . 'webroot/files/tmp/images/' . $info['filename'] . '.' . $info['extension'];?>" /></td>
					</tr>
				</table>
				
				<? endif; ?>								
								
				<?$pagina_sponsor = ob_get_clean();?>
				
				<? if(!isset($isSet_storia) && !isset($isSet_albo)): ?>
				
					<? $pagina_squadra_prima .= $pagina_sponsor; ?>
				
				<? endif; ?>
				
				<? $fpdf->AddPage(); ?>
				<? $fpdf->htmltable($pagina_squadra_prima);?>						
				
				<? if(isset($isSet_storia) || isset($isSet_albo)): ?>
				
				<? $pagina_squadra_seconda = $pagina_squadra_intestazione . $pagina_storia_albo . $pagina_sponsor; ?>
				
				<? $fpdf->AddPage(); ?>
				<? $fpdf->htmltable($pagina_squadra_seconda);?>
				
				<? endif; ?>
				
				<? unset($isSet_storia); unset($isSet_sponsor); unset($isSet_albo); unset($pagina_squadra_intestazione); unset($pagina_squadra_prima); unset($pagina_squadra_seconda); unset($pagina_storia_albo); unset($pagina_sponsor); ?>					
			
			<? endforeach; ?>			
		
		<? endforeach; ?>
	
	<? endforeach; ?>

<? $fpdf->output('files/pdf/almanacco.pdf'); ?>
<?=json_encode(array('link' => '/files/pdf/almanacco.pdf'));?>