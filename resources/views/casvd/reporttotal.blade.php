

<?php
use koolreport\widgets\google\PieChart;use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\ColumnChart;
use \koolreport\widgets\google\BarChart;
?>


<div class="report-content">

    <div style="margin-bottom:50px;">
        <?php
        BarChart::create(array(
            "title"=>"LIST ALL TICKETS",
            "dataSource"=>$data,
            "columns"=>array(
                "Tickets",
                "Incident"=>array("label"=>"Incident","type"=>"number","prefix"=>""),
                "Request"=>array("label"=>"Request","type"=>"number","prefix"=>""),
                "Change"=>array("label"=>"Change","type"=>"number","prefix"=>""),
            )
        ));
        ?>
    </div>


</div>


