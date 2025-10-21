<?php
  header('Cache-Control: max-age=0');

  function time_24($data)
  {
    if (strtotime(date("Y-m-d H:i:s")) > strtotime($data) || strtotime(date("Y-m-d H:i:s")) < strtotime("-1 days", strtotime($data))) {
      return false;
    }
    return true;
  }

  $is_now = time_24(substr($partita['Match']['Data'], 0, strlen("0000-00-00")) . " " . str_replace(".", ":", $partita['Match']['Ora']));
  $now = date("d/m/Y H:i:s");

// === PDF setup ===
  $fpdf->setup('P', 'mm', 'A4');
  $fpdf->SetMargins('10.0', '5.0');
  $fpdf->AddFont('Calibri', '', 'calibri.php');
  $fpdf->AddFont('Calibri', 'B', 'calibrib.php');
  $fpdf->SetFont('Calibri', '', 10);
  $fpdf->AddPage();

// intestazione data
  $fpdf->SetY(5);
  $fpdf->SetFont('Arial', 'I', 8);
  $fpdf->Cell(0, 10, sprintf("Nota gara generata il: %s", $now), 0, 0, 'C');

// testata grafica
  if (isset($_GET['playLeague'])) {
    $fpdf->Image(APP . '/webroot/img/pdf/testata-playleague-pdf-alta.jpg', 40, 13, 130);
  } else {
    $fpdf->Image(APP . '/webroot/img/pdf/head_note_gara.jpg', 30, 10, 150);
  }

// titolo e dati gara
  $fpdf->SetY(48);
  $fpdf->setX(10);
  $fpdf->SetTextColor(0, 0, 0);
  $fpdf->SetFont('Calibri', 'B', 19);
  $fpdf->Write(5, 'Nota gara: ' . $squadra['Squadre']['Denominazione']);
  $deltaY = 5;

  $fpdf->SetY(60 - $deltaY);
  $fpdf->SetX(10);
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, 'Campionato/Torneo: ');
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Campionati']['Nome']);

  $fpdf->SetY(68 - $deltaY);
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, 'Partita:');
  $fpdf->SetY(68 - $deltaY);
  $fpdf->SetX(27);
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, 'Società ospitante:');
  $fpdf->SetX(62);
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Match']['CasaNome']);

  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->SetY(74 - $deltaY);
  $fpdf->SetX(27);
  $fpdf->Write(5, 'Società ospitata:');
  $fpdf->SetX(62);
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Match']['TrasfertaNome']);

  $fpdf->SetY(83 - $deltaY);
  $fpdf->SetX(10);
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, 'Data: ');
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Match']['Data_it'] . "  ");
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, '  Ora: ');
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Match']['Ora'] . "  ");
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, '  Cat.: ');
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, '____ ');
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, '  Girone: ');
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Half']['Descrizione'] . "  ");
  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->Write(5, '  Giornata: ');
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Match']['Giornata']);

  $fpdf->SetFont('Calibri', 'B', 12);
  $fpdf->SetY(89 - $deltaY);
  $fpdf->Write(5, 'Campo: ');
  $fpdf->SetFont('Calibri', '', 12);
  $fpdf->Write(5, $partita['Campi']['Descrizione']);

// blacklist squalificati
  $blacklist = array();
  $squalificati_tempo = [];
  $squalificati_giornata = [];

  if (!empty($squalificati['SqualificatiTempo'])) {
    foreach ($squalificati['SqualificatiTempo'] as $espulso) {
      $squalificati_tempo[] = $espulso['Anagrafica'];
      $blacklist[] = $espulso['Anagrafica'];
    }
  }
  if (!empty($squalificati['SqualificatiGiornata'])) {
    foreach ($squalificati['SqualificatiGiornata'] as $espulso) {
      $squalificati_giornata[] = $espulso['Anagrafica'];
      $blacklist[] = $espulso['Anagrafica'];
    }
  }

  $offset = 0;
  if (count($squalificati_tempo)) {
    $fpdf->SetY(98 + $offset - $deltaY);
    $fpdf->SetX(10);
    $offset += 5;
    $fpdf->SetFont('Calibri', 'B', 12);
    $fpdf->Write(5, 'Squalificati a tempo: ');
    $fpdf->SetFont('Calibri', '', 10);
    $fpdf->Write(5, implode(", ", $squalificati_tempo));
  }
  if (count($squalificati_giornata)) {
    $fpdf->SetY(100 + $offset - $deltaY);
    $fpdf->SetX(10);
    $offset += 5;
    $fpdf->SetFont('Calibri', 'B', 12);
    $fpdf->Write(5, 'Squalificati giornata corrente: ');
    $fpdf->SetFont('Calibri', '', 10);
    $fpdf->Write(5, implode(", ", $squalificati_giornata));
  }

/** -------------------- Caricamento simboli assicurazione (per Tessera + SquadraCampionato) -------------------- */
  App::import('Model', 'Yearbook');
  $Yearbook = ClassRegistry::init('Yearbook');
  $assByKey = array(); // [tessera|squadraCampionato => simbolo]

  if (!empty($partecipanti) && is_object($Yearbook)) {
    $tessere = array();
    foreach ($partecipanti as $p) {
      if (!empty($p[0]['Athlete__Tessera'])) {
        $tessere[] = (int)$p[0]['Athlete__Tessera'];
      }
    }
    $tessere = array_values(array_unique(array_filter($tessere)));
    $squadraCampionatoId = (int)$squadra['SquadreCampionati']['SquadraCampionato'];

    if (!empty($tessere) && $squadraCampionatoId) {
      $idsSql = implode(',', array_map('intval', $tessere));
      $sql = "
        SELECT y.Tessera, y.SquadraCampionato, ta.Simbolo
        FROM Annuario AS y
        INNER JOIN TipiAssicurazione AS ta
          ON ta.TipoAssicurazione = y.TipoAssicurazione
        WHERE y.Tessera IN ($idsSql)
          AND y.SquadraCampionato = $squadraCampionatoId
          AND y.TipoAssicurazione IS NOT NULL
          AND y.TipoAssicurazione <> 0
      ";
      $rows = $Yearbook->query($sql);
      foreach ((array)$rows as $r) {
        $tess = isset($r['y']['Tessera']) ? (int)$r['y']['Tessera'] : 0;
        $sc   = isset($r['y']['SquadraCampionato']) ? (int)$r['y']['SquadraCampionato'] : 0;
        $sim  = isset($r['ta']['Simbolo']) ? $r['ta']['Simbolo'] : '';
        if ($tess && $sc && $sim !== '') {
          $assByKey[$tess . '|' . $sc] = $sim;
        }
      }
    }
  }

/** -------------------- Tabella partecipanti -------------------- */
  $fpdf->SetY(96 + $offset);
  $fpdf->SetX(10);

// larghezze colonne
  $l_1 = 15;
  $l_2 = 15;
  $l_3 = 58;
  $l_4 = 19;
  $l_5 = 34;
  $l_6 = 5;
  $l_7 = 12; // accorciata
  $l_9 = 8; // Tipo ass.
  $l_8 = 17;

  $l_tot = $l_1 + $l_2 + $l_3 + $l_4 + $l_5 + $l_6 + $l_7 + $l_9 + $l_8;

  $y = $fpdf->GetY();
  $x0 = $fpdf->GetX();
  $h_line = 4;
  $h_block = 8;

// === HEADER TABELLA ===
  $y = $fpdf->GetY();
  $x0 = $fpdf->GetX();
  $h_line = 4;
  $h_block = 8;

  $fpdf->SetFont('Calibri', 'B', 9); // <-- ripristino font più piccolo e bold

  // 1) Riservato all'arbitro
  $fpdf->SetXY($x0, $y);
  $fpdf->MultiCell($l_1, $h_line, "Riservato\nall'arbitro", 1, 'L');

  // 2) Numero/Dir/Tec*
  $fpdf->SetXY($x0 + $l_1, $y);
  $fpdf->MultiCell($l_2, $h_line, "Numero/\nDir/Tec*", 1, 'L');

  // 3) Cognome e Nome
  $fpdf->SetXY($x0 + $l_1 + $l_2, $y);
  $fpdf->MultiCell($l_3, $h_line, "Cognome e Nome\n ", 1, 'L');

  // 4) Data di nascita
  $fpdf->SetXY($x0 + $l_1 + $l_2 + $l_3, $y);
  $fpdf->MultiCell($l_4, $h_line, "Data di\nnascita", 1, 'L');

  // 5) Luogo di nascita
  $fpdf->SetXY($x0 + $l_1 + $l_2 + $l_3 + $l_4, $y);
  $fpdf->MultiCell($l_5, $h_line, "Luogo di nascita\n ", 1, 'L');

  // 6) Sesso
  $fpdf->SetXY($x0 + $l_1 + $l_2 + $l_3 + $l_4 + $l_5, $y);
  $fpdf->MultiCell($l_6, $h_line, "S\n ", 1, 'C');

  // 7) Cap./v. cap
  $fpdf->SetXY($x0 + $l_1 + $l_2 + $l_3 + $l_4 + $l_5 + $l_6, $y);
  $fpdf->MultiCell($l_7, $h_line, "Cap./\nv. cap", 1, 'C');

  // 9) Tipo ass.
  $fpdf->SetXY($x0 + $l_1 + $l_2 + $l_3 + $l_4 + $l_5 + $l_6 + $l_7, $y);
  $fpdf->MultiCell($l_9, $h_line, "Tipo\nass.", 1, 'C');

  // 8) N. tessera/Documento
  $fpdf->SetXY($x0 + $l_1 + $l_2 + $l_3 + $l_4 + $l_5 + $l_6 + $l_7 + $l_9, $y);
  $fpdf->MultiCell($l_8, $h_line, "N° tess./\nDoc.", 1, 'L');

  $fpdf->SetXY($x0, $y + $h_block);
  $fpdf->SetFont('Calibri', '', 9); // <-- torno normale per il corpo

// corpo tabella
  foreach ($partecipanti as $partecipante) {
    $data_nascita = explode("-", $partecipante['Athlete']['DataNascita']);
    $nomeCompleto = trim($partecipante['Athlete']['Cognome'] . " " . $partecipante['Athlete']['Nome']);

    if (!in_array($nomeCompleto, $blacklist)) {
      $citta = '';
      if (!empty($city[$partecipante['Athlete']['CityNascita']]['city_name'])) {
        $citta = $city[$partecipante['Athlete']['CityNascita']]['city_name'];
      }

      $tess = !empty($partecipante[0]['Athlete__Tessera']) ? (int)$partecipante[0]['Athlete__Tessera'] : 0;
      $sc   = (int)$squadra['SquadreCampionati']['SquadraCampionato'];
      $key  = $tess . '|' . $sc;
      $simbolo = isset($assByKey[$key]) ? $assByKey[$key] : '';

      $fpdf->Cell($l_1, 5, "", 1);
      $fpdf->Cell($l_2, 5, "", 1);
      $fpdf->Cell($l_3, 5, $nomeCompleto, 1);
      $fpdf->Cell($l_4, 5, sprintf("%s/%s/%s", $data_nascita[2], $data_nascita[1], $data_nascita[0]), 1);
      $fpdf->Cell($l_5, 5, mb_strimwidth(ucwords(strtolower($citta)), 0, 17, ""), 1);
      $fpdf->Cell($l_6, 5, isset($partecipante['Athlete']['Sesso'][0]) ? $partecipante['Athlete']['Sesso'][0] : '', 1, 'C');
      $fpdf->Cell($l_7, 5, "", 1);
	  $fpdf->Cell($l_9, 5, substr($simbolo, 0, 2), 1, 0, 'C');
      $fpdf->Cell($l_8, 5, "", 1);
      $fpdf->Ln();
    }
  }

// righe vuote fino a fine tabella
  $num_righe = 20;
  $num_righe_fine_pagina = 30;
  $righe_vuote = $num_righe - count($partecipanti);
  if (count($partecipanti) > 16) $righe_vuote = $num_righe_fine_pagina - count($partecipanti);
  if (count($partecipanti) > 30) $righe_vuote = 5;
  if (count($partecipanti) > 50) $righe_vuote = 7;

  for ($i = 0; $i < $righe_vuote; $i++) {
    $fpdf->Cell($l_1, 5, "", 1);
    $fpdf->Cell($l_2, 5, "", 1);
    $fpdf->Cell($l_3, 5, "", 1);
    $fpdf->Cell($l_4, 5, "", 1);
    $fpdf->Cell($l_5, 5, "", 1);
    $fpdf->Cell($l_6, 5, "", 1);
    $fpdf->Cell($l_7, 5, "", 1);
    $fpdf->Cell($l_9, 5, "", 1);
    $fpdf->Cell($l_8, 5, "", 1);
    $fpdf->Ln();
  }

  $fpdf->Cell($l_tot, 5, "*Indicare il numero di maglia del giocatore o segnalare se Dirigente con sigla DIR. o tecnico/allenatore con sigla TEC.", 1);
  $fpdf->Ln();

  $image_H = 53;
  $fpdf->Cell($l_tot, $image_H + 1, "", 1);
  $fpdf->SetX(11);
  $fpdf->Image(APP . "webroot/img/pdf/spazio-arbitro-nota-gara_def.jpg", $fpdf->GetX(), $fpdf->GetY() + 0.5, $l_tot - 2, $image_H);
  $fpdf->SetX(143);
  $fpdf->Cell(40, 15, $squadra['Squadre']['Denominazione']);
  $fpdf->SetY($fpdf->GetY() + $image_H + 1.1);
  $fpdf->SetFont('Calibri', 'B', 9);
  $fpdf->Multicell($l_tot, 4, "Tutti i partecipanti alla gara sono a conoscenza ed accettano i regolamenti sia tecnici sia economici previsti dalla manifestazione. La posizione sanitaria deve essere in regola come previsto dalle norme, in caso differente è a rischio e pericolo del partecipante.", 1);

  // pagina bianca finale se numero dispari
  if ($fpdf->PageNo() % 2 == 1) {
    $fpdf->AddPage();
  }

// output
  $fpdf->Output(
    Inflector::slug($partita['Match']['CasaNome']) . "-" .
    Inflector::slug($partita['Match']['TrasfertaNome']) . "-" .
    Inflector::slug($partita['Match']['Data_it']) . "-$squadra_id.pdf",
    'I'
  );
?>
