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

                        $query="SELECT receta.*, médico.nombre 
                        AS nombre_medico, médico.especialidad 
                        AS especialidad_medico, alumno.nombre_alumno, alumno.facultad, alumno.sexo, alumno.fecha_nacimiento 
                        FROM receta 
                        JOIN médico 
                        ON receta.dni_medico = médico.dni_medico 
                        JOIN alumno 
                        ON receta.codigo_alumno = alumno.codigo_alumno;";
                        $ejecutar=mysqli_query($db,$query);
                        $hojaActiva->setCellValue('A1','fichador_receta');
                        $hojaActiva->setCellValue('B1','fecha_de_registro');
                        $hojaActiva->setCellValue('C1','monto_total');
                        $hojaActiva->setCellValue('D1','DNI_ALUMNO');
                        $hojaActiva->setCellValue('E1','CODIGO_ALUMNO');
                        $hojaActiva->setCellValue('F1','DNI_MEDICO');
                        $hojaActiva->setCellValue('G1','NOMBRE_MEDICO');
                        $hojaActiva->setCellValue('H1','ESPECIALIDAD_MEDICO');
                        $hojaActiva->setCellValue('I1','NOMBRE_ALUMNO');
                        $hojaActiva->setCellValue('J1','FACULTAD');
                        $hojaActiva->setCellValue('K1','SEXO');
                        $hojaActiva->setCellValue('L1','FECHA_NACIMIENTO');
                        $hojaActiva->setCellValue('M1','PERIODO_ACADEMICO');
                        $contador=1;
                    while($elemento=mysqli_fetch_assoc($ejecutar)){
                        $contador=$contador+1;
                        $hojaActiva->setCellValue("A$contador",$elemento['fichador_receta']);
                        $hojaActiva->setCellValue("B$contador",$elemento['fecha']);
                        $hojaActiva->setCellValue("C$contador",$elemento['monto_total']);
                        $hojaActiva->setCellValue("D$contador",$elemento['dni_usuario']);
                        $hojaActiva->setCellValue("E$contador",$elemento['codigo_alumno']);
                        $hojaActiva->setCellValue("F$contador",$elemento['dni_medico']);
                        $hojaActiva->setCellValue("G$contador",$elemento['nombre_medico']);
                        $hojaActiva->setCellValue("H$contador",$elemento['especialidad_medico']);
                        $hojaActiva->setCellValue("I$contador",$elemento['nombre_alumno']);
                        $hojaActiva->setCellValue("J$contador",$elemento['facultad']);
                        $hojaActiva->setCellValue("K$contador",$elemento['sexo']);
                        $hojaActiva->setCellValue("L$contador",$elemento['fecha_nacimiento']);
                        $hojaActiva->setCellValue("M$contador",$elemento['periodo_academico']);
                        
                    }               
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="RegistroRecetas.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

$writer=new Xlsx($spreadsheet);
/* $writer->save('ExcelGuardadoMedico.xlsx'); */

?>