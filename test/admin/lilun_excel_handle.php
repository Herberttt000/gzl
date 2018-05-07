<?php
/**
* PHPExcel读取excel文件
* site: www.jbxue.com
*
*/
    //require_once('include/common.inc.php');
	require_once './../include/Classes/PHPExcel/IOFactory.php';
    
    $filePath = $_FILES["file"]["tmp_name"]; 
    
    $fileType = PHPExcel_IOFactory::identify($filePath); //文件名自动判断文件类型
    $objReader = PHPExcel_IOFactory::createReader($fileType);
    $objPHPExcel = $objReader->load($filePath);
    
    $currentSheet = $objPHPExcel->getSheet(0); //第一个工作簿
    $allRow = $currentSheet->getHighestRow(); //行数
    $output = array();
    $preType = '';
    
    //$qh = $currentSheet->getCell('A4')->getValue();
    //按照文件格式从第7行开始循环读取数据
    for($currentRow = 2;$currentRow<=$allRow;$currentRow++){ 
        $output[$currentRow]['xueqi'] = (int)$currentSheet->getCell('A'.$currentRow)->getValue();
        $output[$currentRow]['course_id'] = (String)$currentSheet->getCell('B'.$currentRow)->getValue(); 
        $output[$currentRow]['course_name'] = (String)$currentSheet->getCell('C'.$currentRow)->getValue();  
        $output[$currentRow]['course_index'] = (int)$currentSheet->getCell('D'.$currentRow)->getValue();        
        $output[$currentRow]['course_alias'] = (string)$currentSheet->getCell('E'.$currentRow)->getValue();        
        $output[$currentRow]['num_of_p'] = (int)$currentSheet->getCell('F'.$currentRow)->getValue();
    	$output[$currentRow]['xueshi'] = (float)$currentSheet->getCell('G'.$currentRow)->getValue();
    	$output[$currentRow]['xuankeshuxing'] = (String)$currentSheet->getCell('H'.$currentRow)->getValue();
    	$output[$currentRow]['heban'] = (String)$currentSheet->getCell('I'.$currentRow)->getValue();
    	$output[$currentRow]['kcxz'] = (String)$currentSheet->getCell('J'.$currentRow)->getValue();
    	$output[$currentRow]['teacher_id'] = (String)$currentSheet->getCell('K'.$currentRow)->getValue();
    	$output[$currentRow]['teacher_name'] = (string)$currentSheet->getCell('L'.$currentRow)->getValue();
    	$output[$currentRow]['teacher_yuanxi'] = (String)$currentSheet->getCell('M'.$currentRow)->getValue();
    	$output[$currentRow]['teacher_zc'] = (String)$currentSheet->getCell('N'.$currentRow)->getValue();
    	// $output[$currentRow]['xs_zyk'] = (float)$currentSheet->getCell('N'.$currentRow)->getValue();
    	// $output[$currentRow]['xs_sb'] = (float)$currentSheet->getCell('O'.$currentRow)->getValue();
    	// $output[$currentRow]['xs_nd'] = (float)$currentSheet->getCell('P'.$currentRow)->getValue();
    	// $output[$currentRow]['xs_zc'] = (float)$currentSheet->getCell('Q'.$currentRow)->getValue();
    	// $output[$currentRow]['xs_skzl'] = (float)$currentSheet->getCell('R'.$currentRow)->getValue();
    	// $output[$currentRow]['jsgc'] = (String)$currentSheet->getCell('S'.$currentRow)->getValue();
    	// $output[$currentRow]['jf'] = (float)$currentSheet->getCell('T'.$currentRow)->getValue();
    }
    /*
    for($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
    	echo "<table>";
    	echo "<tr>";
    	
    	echo "<td>"; echo $output[$currentRow]['teacher_id']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['course_id']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['course_name']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['class']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['period']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['nop']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['year']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xueqi']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['zhiliang']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['sj_type']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['sj_place']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xs_nop']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xs_cfk']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xs_zyk']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xs_sb']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xs_nd']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xs_zc']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['xs_skzl']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['jsgc']; echo "</td>";
    	echo "<td>"; echo $output[$currentRow]['jf']; echo "</td>";
    	echo "</tr>";
    	echo "</table>";
    }*/
?>