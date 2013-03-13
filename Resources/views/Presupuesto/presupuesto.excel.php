<?php
$workbook = new \PHPExcel();

$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Listado");

$sheet->getColumnDimension('A')->setWidth("55.56");
$sheet->getColumnDimension('B')->setWidth("5.43");
$sheet->getColumnDimension('C')->setWidth("8");
$sheet->getColumnDimension('D')->setWidth("9");
$sheet->getColumnDimension('E')->setWidth("8.43");
$sheet->getColumnDimension('F')->setWidth("2.70");

$headerRow = 4;

$titleStyle = array(
    'font' => array(
        'size' => 14,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'wrap' => true,
    ),
);

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
    ),
);

$stylePrecio['borders']['top'] = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb' => '000000'),
);

$stylePrecio['borders']['bottom'] = $stylePrecio['borders']['top'];
$stylePrecio['borders']['left'] = $stylePrecio['borders']['top'];
$stylePrecio['borders']['right'] = $stylePrecio['borders']['top'];

$sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray($styleHeaderArray);
$sheet->getStyle("A1:F1")->applyFromArray($titleStyle);

$sheet->setCellValue("A1", $presupuesto->getTitulo());
$sheet->setCellValue("A{$headerRow}", "Descripción");
$sheet->setCellValue("B{$headerRow}", "Precio");
$sheet->setCellValue("D{$headerRow}", "Cantidad");
$sheet->setCellValue("E{$headerRow}", "Subtotal");

$sheet->mergeCells("B{$headerRow}:C{$headerRow}");
$sheet->mergeCells("E{$headerRow}:F{$headerRow}");
$sheet->mergeCells("A1:E1");

$initialRow = $headerRow + 1;
$fila = $initialRow;

foreach ($presupuesto->getDescripciones() as $des) {
    $sheet->setCellValue("A{$fila}", $des->getDescripcion());
    $sheet->setCellValue("B{$fila}", (float) $des->getPrecioNum());
    $sheet->setCellValue("C{$fila}", $des->getUnidadMedida());
    $sheet->setCellValue("D{$fila}", (float) $des->getCantidad());
    $sheet->setCellValue("E{$fila}", "=B{$fila}*D{$fila}");
    $sheet->setCellValue("F{$fila}", "Bs");
    $sheet->getStyle("B{$fila}:C{$fila}")->applyFromArray($stylePrecio);
    $sheet->getStyle("E{$fila}:F{$fila}")->applyFromArray($stylePrecio);
    ++$fila;
}

$anterior = $fila - 1;

$sheet->setCellValue("D{$fila}", "TOTAL");
$sheet->setCellValue("E{$fila}", "=SUM(E{$initialRow}:E{$anterior})");
$sheet->setCellValue("F{$fila}", "Bs");

$sheet->getStyle("D{$fila}:F{$fila}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle("E{$fila}:F{$fila}")->applyFromArray($stylePrecio);


--$fila;

$sheet->getStyle("A{$headerRow}:A{$fila}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("A{$headerRow}:A{$fila}")->getAlignment()->setWrapText(true);

$sheet->getStyle("D{$headerRow}:D{$fila}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("B{$headerRow}:C{$headerRow}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("A{$initialRow}:F{$fila}")
        ->getAlignment()
        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

return $workbook;
