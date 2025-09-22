<?

App::Import('Vendor','fpdf/fpdf');

if (!defined('PARAGRAPH_STRING')) define('PARAGRAPH_STRING', '~~~');

	class fpdfHelper extends FPDF {

		var $helpers = array(); 
		
		function setup ($orientation='P',$unit='mm',$format='A4') {
		   
		   $this->FPDF($orientation, $unit, $format); 

		}
		
/* 		function Header() {
		
			$this->Image(WWW_ROOT.DS.'img/prova.jpg',10,8,33);  

			$this->SetFont('Arial','B',15);
			//Move to the right
			$this->Cell(80);
			//Title
			$this->Cell(30,10,'Titolo',1,0,'C');
			//Line break
			$this->Ln(20);
		}

		function Footer() {
		
			//Position at 1.5 cm from bottom
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Page number
			$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
		} */

		function basicTable($header,$data) {
			//Header
			foreach($header as $col) {
			
				if(strlen($col) < 14) $l = 30;
					else $l = 55;
				
				$this->Cell($l,7,$col,1);
				
			}
			$this->Ln();
			//Data
			foreach($data as $row) {
			
				foreach($row as $col) {
				
					if(strlen($col) < 14) $l = 30;
					else if(strlen($col) > 20) $l = 60;
						else $l = 55;
				
					$this->Cell($l,6,$col,1);
				
				}
				$this->Ln();
			
			}
		} 	

		function fpdfOutput ($name = 'page.pdf', $destination = 'S') {

			return $this->Output($name, $destination);
			
		}
		
	}