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
$spreadsheet->getProperties()->setCreator("MarkoRobles")->setTitle("Mi Excel");
$spreadsheet->setActiveSheetIndex(0);
$hojaActiva=$spreadsheet->getActiveSheet();
$hojaActiva->getColumnDimension('A')->setWidth(15);
$hojaActiva->getColumnDimension('B')->setWidth(15);
$hojaActiva->getColumnDimension('C')->setWidth(15);
$hojaActiva->getColumnDimension('D')->setWidth(15);
$hojaActiva->getColumnDimension('E')->setWidth(15);
$hojaActiva->getColumnDimension('F')->setWidth(15);
$hojaActiva->getColumnDimension('G')->setWidth(15);
$hojaActiva->getColumnDimension('H')->setWidth(15);
                        $query="SELECT * FROM `merma`;";
                        $ejecutar=mysqli_query($db,$query);
                        $hojaActiva->setCellValue('A1','Id_merma');
                        $hojaActiva->setCellValue('B1','Id_medicamento');
                        $hojaActiva->setCellValue('C1','Lote');
                        $hojaActiva->setCellValue('D1','Cantidad');
                        $hojaActiva->setCellValue('E1','Motivo');
                        $hojaActiva->setCellValue('F1','Fecha_Registro');
                        $hojaActiva->setCellValue('G1','Dni_Administrador');
                        $hojaActiva->setCellValue('H1','Nombre_Administrador');
                        $contador=1;
                    while($elemento=mysqli_fetch_assoc($ejecutar)){
                        $contador=$contador+1;
                        $hojaActiva->setCellValue("A$contador",$elemento['id_merma']);
                        $hojaActiva->setCellValue("B$contador",$elemento['id_medicamento']);
                        $hojaActiva->setCellValue("C$contador",$elemento['lote']);
                        $hojaActiva->setCellValue("D$contador",$elemento['cantidad']);
                        $hojaActiva->setCellValue("E$contador",$elemento['motivo']);
                        $hojaActiva->setCellValue("F$contador",$elemento['fecha_registro']);
                        $hojaActiva->setCellValue("G$contador",$elemento['DNI_ADMIN']);
                        $hojaActiva->setCellValue("H$contador",$elemento['NOMBRE_ADMIN']);
                    }               
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ExcelGuardado.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

$writer=new Xlsx($spreadsheet);
/* $writer->save('ExcelGuardadoMedico.xlsx'); */

?>