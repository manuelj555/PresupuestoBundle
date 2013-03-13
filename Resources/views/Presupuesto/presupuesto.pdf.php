<?php
$phpexcel = require 'presupuesto.excel.php';

$writer = \PHPExcel_IOFactory::createWriter($phpexcel, 'PDF');

$writer->save('pdf.pdf');
