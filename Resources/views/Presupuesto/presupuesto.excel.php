<?php

$workbook = new \PHPExcel();

$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Listado");

$sheet->getColumnDimension('A')->setWidth("0");
$sheet->getColumnDimension('B')->setWidth("54.14");
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);

$styleArray = array(
    'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
    'fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '4F81BD'))
);

$sheet->getStyle('B2:E2')->applyFromArray($styleArray);

$sheet->setCellValue("B2", "Descripción");
$sheet->setCellValue("C2", "Precio");
$sheet->setCellValue("D2", "Cantidad");
$sheet->setCellValue("E2", "Subtotal");

$fila = 3;

foreach ($presupuesto->getDescripciones() as $des) {
    $sheet->setCellValue("B{$fila}", $des->getDescripcion());
    $sheet->setCellValue("C{$fila}", $des->getPrecio());
    $sheet->setCellValue("D{$fila}", $des->getCantidad());
    $sheet->setCellValue("E{$fila}", $des->getSubtotal());
    ++$fila;
}
--$fila;

$sheet->getStyle("B2:E{$fila}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle("B2:E{$fila}")
        ->getAlignment(
        )->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$writer = \PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$writer->save('php://output');
