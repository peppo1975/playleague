<?php


header('Cache-Control: max-age=0');

class PDF extends FPDF
{


    function BasicTableArray($header, $column_size, $partite, $data)
    {
        //header ---------------

        $this->createHeader($header, $column_size, $partite, $data);
        // ------------------------
        // Data
        $indexline = 0;
        foreach ($partite as $row)
        {
            if ($this->GetY() > 254) // per aggiungere pagina dopo 15 righe altrimenti si sfasa la cella
            {

                $this->createHeader($header, $column_size, $partite, $data);
                // ------------------------

                $indexline = 0;
            }
            unset($row['td_gg']);
            unset($row['td_data']);

            $row['td_girone'] = str_replace(["<span>", "</span>"], "", $row['td_girone']);

            $index = 0;

            foreach ($row as $col)
            {
                $c_width = $column_size[$index]; // cell width 
                $c_height = 15; // cell height
                $text = $col;
//              
//                  if($index == 6)
//                   $text = $col." ". $this->GetY(); 

                $x_axis = $this->GetX();
                $this->vcell($c_width, $c_height, $x_axis, $text);
                $index++;
            }
            $indexline++;
            $this->Ln();
        }
    }


    function createHeader($header, $column_size, $partite, $data)
    {
        $this->SetFont('Calibri', '', 19);
        $this->Ln();
        $this->Cell(100, 10, $data, 0);
        $this->Ln();

        // Header
        $this->SetFont('Calibri', '', 9);
        foreach ($column_size as $key => $size)
        {
            $col = $header[$key];
            $this->Cell($size, 7, $col, 1);
        }
        $this->Ln();
    }


    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }


    //https://stackoverflow.com/questions/23542244/wrap-text-in-fpdf-in-php
    function vcell($c_width, $c_height, $x_axis, $text)
    {
        $w_w = $c_height / 3; //lo voglio su tre righe
        $w_w_1 = $w_w + 2;
        $w_w1 = $w_w + $w_w + $w_w + 3;
        $w_w2 = $w_w + $w_w + $w_w + $w_w + 3; // for 3 rows wrap

        $len = strlen($text); // check the length of the cell and splits the text into 7 character each and saves in a array 

        $c_width_t = $c_width / 2;

        if ($len > $c_width_t)
        {

            $expl = explode(" ", trim($text, " "));

//            $to_write = "";
            $to_write = [];
            $w_text_2 = [];
            $indexW = 0;
            $all = "";

            foreach ($expl as $key => $value_text)
            {
//                $to_write .= $value_text . " ";
                $to_write[] = $value_text;

                if ($key == 0)
                {
                    $indexW = 0;
                    $to_write = [];
                    $to_write[] = $value_text;
                    $w_text_2[$indexW] = $value_text;
                    continue;
                }

                if ((strlen(implode(" ", $to_write))) >= $c_width_t)
                {
//                    $to_write = "";
                    $to_write = [];
                    $indexW++;
//                    $to_write .= $value_text . " ";
                    $to_write[] = $value_text;
                    $w_text_2[$indexW] = implode(" ", $to_write);
                }
                else
                {
//                    $w_text_2[$indexW] = $to_write;
                    $w_text_2[$indexW] = implode(" ", $to_write);
                }
            }

            // completare ...

            if (isset($w_text_2[0]))
            {
                $this->SetX($x_axis);
                $this->Cell($c_width, $w_w_1, $w_text_2[0], '', '', '');
            }

            if (isset($w_text_2[1]))
            {
                $this->SetX($x_axis);
                $this->Cell($c_width, $w_w1, $w_text_2[1], '', '', '');
            }

            if (isset($w_text_2[2]))
            {
                $this->SetX($x_axis);
                $this->Cell($c_width, $w_w2, $w_text_2[2], '', '', '');
            }

            $this->SetX($x_axis);
            $this->Cell($c_width, $c_height, '', 'LTRB', 0, 'L', 0);
        }
        else
        {
            $this->SetX($x_axis);
            $this->Cell($c_width, $c_height, $text, 'LTRB', 0, 'L', 0);
        }
    }


}


$fpdf = new PDF();

$fpdf->SetMargins('5.0', '10.0', '10.0');
$fpdf->AddFont('Calibri', '', 'calibri.php');
$fpdf->AddFont('Calibri', 'B', 'calibri.php');
$fpdf->SetFont('Calibri', '', 9);



$res = [];

foreach ($pdf_matches_list as $match)
{
    $key = "{$match['td_gg']} {$match['td_data']}";
    unset($match['td_data_timestamp']);
    $res[$key][] = $match;
}

$header = array('Manifestazione', 'Girone', 'Ora', 'Casa', 'Trasferta', 'Campo', 'Arbitro');
$column_size = [38, 18, 10, 38, 38, 30, 30];

$fpdf->AddPage();
$fpdf->AliasNbPages();

foreach ($res as $data => $partite)
{
    $fpdf->BasicTableArray($header, $column_size, $partite, $data);
}

$fpdf->Output();
?>