<?php

$workbook = new \PHPExcel();

$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Listado");

$sheet->getColumnDimension('A')->setWidth("0");
$sheet->getColumnDimension('B')->setWidth("54.14");
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);

$headerRow = 4;

$styleHeaderArray = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => 'FFFFFF'),
    ),
    'fill' => array(
        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '4F81BD'),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap' => true,
    ),
);

$stylePrecio['borders']['top'] = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb' => '000000'),
);

$stylePrecio['borders']['bottom'] = $stylePrecio['borders']['top'];
$stylePrecio['borders']['left'] = $stylePrecio['borders']['top'];
$stylePrecio['borders']['right'] = $stylePrecio['borders']['top'];

$sheet->getStyle("B{$headerRow}:F{$headerRow}")->applyFromArray($styleHeaderArray);

$sheet->setCellValue("B1", $presupuesto->getTitulo());
$sheet->setCellValue("B{$headerRow}", "Descripción");
$sheet->setCellValue("C{$headerRow}", "Precio");
$sheet->setCellValue("E{$headerRow}", "Cantidad");
$sheet->setCellValue("F{$headerRow}", "Subtotal");

$sheet->mergeCells("C{$headerRow}:D{$headerRow}");

$initialRow = $headerRow + 1;
$fila = $initialRow;

foreach ($presupuesto->getDescripciones() as $des) {
    $sheet->setCellValue("B{$fila}", $des->getDescripcion());
    $sheet->setCellValue("C{$fila}", (float) $des->getPrecioNum());
    $sheet->setCellValue("D{$fila}", $des->getUnidadMedida());
    $sheet->setCellValue("E{$fila}", (float) $des->getCantidad());
    $sheet->setCellValue("F{$fila}", "=CONCATENATE(C{$fila}*E{$fila}, \" Bs\")");
    $sheet->getStyle("C{$fila}:D{$fila}")->applyFromArray($stylePrecio);
    ++$fila;
}

$anterior = $fila - 1;

$sheet->setCellValue("E{$fila}", "TOTAL");
$sheet->setCellValue("F{$fila}", "=CONCATENATE(SUM(F{$initialRow}:F{$anterior}), \" Bs\")");

--$fila;

$sheet->getStyle("B{$headerRow}:B{$fila}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("E{$headerRow}:F{$fila}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("C{$headerRow}:D{$headerRow}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("F{$initialRow}:F{$fila}")
        ->getAlignment()
        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$writer = \PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$writer->save('php://output');
