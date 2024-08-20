<?php

session_start();
$auth=$_SESSION['auth'];
if(!$auth){
    header('Location:../login.php');
}

require '../vendor/autoload.php';

include_once('../config/database.php');

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
                        $query="SELECT medicamento.id_medicamento, 
                        medicamento.codigo_siga, 
                        petitorio.denomin_comun_internac_o_principio_activo, medicamento.cantidad, 
                        medicamento.fecha_vencimiento, medicamento.precio_unitaario,
                        petitorio.minimo_stock 
                        FROM medicamento 
                        INNER JOIN petitorio 
                        ON medicamento.codigo_siga = petitorio.codigo_siga;";
                        $ejecutar=mysqli_query($db,$query);
                        $hojaActiva->setCellValue('A1','codigo_inventario');
                        $hojaActiva->setCellValue('B1','codigo_siga');
                        $hojaActiva->setCellValue('C1','denomin_comun_internac_o_principio_activo');
                        $hojaActiva->setCellValue('D1','Cantidad');
                        $hojaActiva->setCellValue('E1','Fecha_vencimiento');
                        $hojaActiva->setCellValue('F1','Precio_unitario');
                        $hojaActiva->setCellValue('G1','Minimo_stock');
                        $contador=1;
                    while($elemento=mysqli_fetch_assoc($ejecutar)){
                        $contador=$contador+1;
                        $hojaActiva->setCellValue("A$contador",$elemento['id_medicamento']);
                        $hojaActiva->setCellValue("B$contador",$elemento['codigo_siga']);
                        $hojaActiva->setCellValue("C$contador",$elemento['denomin_comun_internac_o_principio_activo']);
                        $hojaActiva->setCellValue("D$contador",$elemento['cantidad']);
                        $hojaActiva->setCellValue("E$contador",$elemento['fecha_vencimiento']);
                        $hojaActiva->setCellValue("F$contador",$elemento['precio_unitaario']);
                        $hojaActiva->setCellValue("G$contador",$elemento['minimo_stock']);
                    }               
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ExcelInventarioFarmacia.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

$writer=new Xlsx($spreadsheet);
/* $writer->save('ExcelGuardadoMedico.xlsx'); */

?>