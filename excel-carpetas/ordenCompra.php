<?php

session_start();

$auth=$_SESSION['auth'];
if(!$auth){
    header('Location:../login.php');
}

require '../vendor/autoload.php';

include_once('../config/database.php');
header("X-Frame-Options: DENY");
$db=conectarDB();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet=new Spreadsheet();
$spreadsheet->getProperties()->setCreator("AndersonAndres")->setTitle("ExcelInventario");
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva=$spreadsheet->getActiveSheet();
$hojaActiva->getColumnDimension('A')->setWidth(15);
$hojaActiva->getColumnDimension('B')->setWidth(15);
$hojaActiva->getColumnDimension('C')->setWidth(15);
$hojaActiva->getColumnDimension('D')->setWidth(15);
$hojaActiva->getColumnDimension('E')->setWidth(15);
$hojaActiva->getColumnDimension('F')->setWidth(15);
$hojaActiva->getColumnDimension('G')->setWidth(15);
$hojaActiva->getColumnDimension('H')->setWidth(20);
$hojaActiva->getColumnDimension('I')->setWidth(20);
$hojaActiva->getColumnDimension('J')->setWidth(20);
$hojaActiva->getColumnDimension('K')->setWidth(20);
$hojaActiva->getColumnDimension('L')->setWidth(15);

                        $query="SELECT * FROM `orden_de_compra` WHERE 1;";
                        $ejecutar=mysqli_query($db,$query);
                        $hojaActiva->setCellValue('A1','id_orden');
                        $hojaActiva->setCellValue('B1','fecha');
                        $hojaActiva->setCellValue('C1','proveedor');
                        $hojaActiva->setCellValue('D1','monto_total');
                        $hojaActiva->setCellValue('E1','dni_usuario');
                        
                        $contador=1;
                    while($elemento=mysqli_fetch_assoc($ejecutar)){
                        $contador=$contador+1;
                        $hojaActiva->setCellValue("A$contador",$elemento['id_orden']);
                        $hojaActiva->setCellValue("B$contador",$elemento['fecha']);
                        $hojaActiva->setCellValue("C$contador",$elemento['proveedor']);
                        $hojaActiva->setCellValue("D$contador",$elemento['monto_total']);
                        $hojaActiva->setCellValue("E$contador",$elemento['dni_usuario']);                    
                    }               
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="RegistroOrdenDeCompra.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

$writer=new Xlsx($spreadsheet);
/* $writer->save('ExcelGuardadoMedico.xlsx'); */

?>