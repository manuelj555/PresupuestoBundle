<?php
$workbook = new \PHPExcel();

$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Listado");
//dimensiones de las columnas
$sheet->getColumnDimension('A')->setWidth("52.86");
$sheet->getColumnDimension('B')->setWidth("5.43");
$sheet->getColumnDimension('C')->setWidth("11.71");
$sheet->getColumnDimension('D')->setWidth("8.43");
$sheet->getColumnDimension('E')->setWidth("5.86");
$sheet->getColumnDimension('F')->setWidth("2.70");
//a partir de que fila se mostrarán las descripciones, la primera son las cabeceras
$headerRow = 4;
//estilos para el título
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
//estilos para los títulos de las columnas
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
//estilos para los precios y subtotales
$styleBordeExterno['borders']['top'] =
        $styleBordeExterno['borders']['bottom'] =
        $styleBordeExterno['borders']['left'] =
        $styleBordeExterno['borders']['right'] = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb' => '000000'),
);
//seteamos los estilos del título y las cabeceras
$sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray($styleHeaderArray);
$sheet->getStyle("A1:F1")->applyFromArray($titleStyle);
//seteamos los valores de titulo y cabeceras
$sheet->setCellValue("A1", $presupuesto->getTitulo());
$sheet->setCellValue("A{$headerRow}", "Descripción");
$sheet->setCellValue("B{$headerRow}", "Precio");
$sheet->setCellValue("D{$headerRow}", "Cantidad");
$sheet->setCellValue("E{$headerRow}", "Subtotal");
//unimos las columnas B y C de la cabecera para el precio y la palabra Bs
$sheet->mergeCells("B{$headerRow}:C{$headerRow}");
//unimos las columnas E y F de la cabecera para el subtotal y la palabra Bs
$sheet->mergeCells("E{$headerRow}:F{$headerRow}");
//unimos las columnas A1 y E1 donde vá el título
$sheet->mergeCells("A1:F1");
//acá sacamos la fila a partir de donde van las descripciones, luego de las cabeceras
$initialRow = $headerRow + 1;
$fila = $initialRow;
//recorremos las descripciones del presupuesto y seteamos los valores en la celdas
foreach ($presupuesto->getDescripciones() as $des) {
    $sheet->setCellValue("A{$fila}", $des->getDescripcion());
    $sheet->setCellValue("B{$fila}", (float) $des->getPrecioNum());
    $sheet->setCellValue("C{$fila}", $des->getUnidadMedida());
    $sheet->setCellValue("D{$fila}", (float) $des->getCantidad());
    $sheet->setCellValue("E{$fila}", "=B{$fila}*D{$fila}");
    $sheet->setCellValue("F{$fila}", "Bs");
    //aplicamos los estilos para las celdas para que parescan tener merge
    $sheet->getStyle("B{$fila}:C{$fila}")->applyFromArray($styleBordeExterno);
    $sheet->getStyle("E{$fila}:F{$fila}")->applyFromArray($styleBordeExterno);
    ++$fila;
}
//anterior tendrá el valor de la ultima fila con descripción
$ultima = $fila - 1;
//seteamos los totales
$sheet->setCellValue("D{$fila}", "TOTAL");
$sheet->setCellValue("E{$fila}", "=SUM(E{$initialRow}:E{$ultima})");
$sheet->setCellValue("F{$fila}", "Bs");

$sheet->getStyle("E{$fila}:F{$fila}")->applyFromArray($styleBordeExterno);

$sheet->getStyle("A{$headerRow}:A{$ultima}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
$sheet->getStyle("A{$headerRow}:A{$ultima}")->getAlignment()
        ->setWrapText(true);

$sheet->getStyle("D{$headerRow}:D{$fila}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("B{$headerRow}:C{$headerRow}")->getBorders()
        ->getAllBorders()
        ->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

$sheet->getStyle("A{$initialRow}:F{$ultima}")
        ->getAlignment()
        ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

$writer = \PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$writer->save('php://output');
