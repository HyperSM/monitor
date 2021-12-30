

<?php
use koolreport\widgets\google\PieChart;
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\widgets\google\BarChart;
?>


<div class="report-content">

    <div style="margin-bottom:50px;">
       <?php
//        PieChart::create(array(
//            "dataSource"=>$data
//        ));

        ?>
        <?php
        BarChart::create(array(
            "title"=> $hostname,
            "dataSource"=>$data,
            "columns"=>array(
                "Host State",
                "UP"=>array("label"=>"UP","type"=>"number","prefix"=>"%"),
                "DOWN"=>array("label"=>"DOWN","type"=>"number","prefix"=>"%"),
                "UNREACT"=>array("label"=>"UNREACT","type"=>"number","prefix"=>"%"),
            )
        ));
        ?>
    </div>


</div>

<?php
//Table::create(array(
//    "dataSource"=>$data,
//    "cssClass"=>array(
//        "table"=>"table-bordered table-striped table-hover"
//    )
//));
//?>

