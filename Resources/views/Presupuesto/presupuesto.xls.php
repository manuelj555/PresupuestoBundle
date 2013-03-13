<?php

$phpexcel = require 'presupuesto.excel.php';

$writer = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');

$writer->save('php://output');
