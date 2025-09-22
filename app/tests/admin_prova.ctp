<?
$objPHPExcel = new PHPExcel();

// Set properties
echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

// Set default font
echo date('H:i:s') . " Set default font\n";
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

// Add some data, resembling some different data types
echo date('H:i:s') . " Add some data\n";
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'String');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Simple');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'PHPExcel');

$objPHPExcel->getActiveSheet()->setCellValue('A2', 'String');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Symbols');
$objPHPExcel->getActiveSheet()->setCellValue('C2', '!+&=()~§±ζώ');

$objPHPExcel->getActiveSheet()->setCellValue('A3', 'String');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'UTF-8');
$objPHPExcel->getActiveSheet()->setCellValue('C3', '??????? MS Excel ????? ?? PHP ????????');

$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Number');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Integer');
$objPHPExcel->getActiveSheet()->setCellValue('C4', 12);

$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Number');
$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Float');
$objPHPExcel->getActiveSheet()->setCellValue('C5', 34.56);

$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Number');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Negative');
$objPHPExcel->getActiveSheet()->setCellValue('C6', -7.89);

$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Boolean');
$objPHPExcel->getActiveSheet()->setCellValue('B7', 'True');
$objPHPExcel->getActiveSheet()->setCellValue('C7', true);

$objPHPExcel->getActiveSheet()->setCellValue('A8', 'Boolean');
$objPHPExcel->getActiveSheet()->setCellValue('B8', 'False');
$objPHPExcel->getActiveSheet()->setCellValue('C8', false);

// Rename sheet
echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Datatypes');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('prova.xls');


// Echo memory peak usage
echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";
?>