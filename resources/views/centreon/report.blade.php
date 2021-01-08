

<?php
use koolreport\widgets\google\PieChart;use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\widgets\google\BarChart;
?>


<div class="report-content">

    <div style="margin-bottom:50px;">
        <?php
        BarChart::create(array(
            "title"=>"HOST STATE IN MONTH ",
            "dataSource"=>$data,
            "columns"=>array(
                "Month",
                "UP"=>array("label"=>"UP","type"=>"number","prefix"=>""),
                "DOWN"=>array("label"=>"DOWN","type"=>"number","prefix"=>""),
                "UNREACT"=>array("label"=>"UNREACT","type"=>"number","prefix"=>""),
            )
        ));
        ?>
    </div>


</div>


