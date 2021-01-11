<?php
use \koolreport\widgets\google\BarChart;
use \koolreport\widgets\google\ColumnChart;
?>

<?php
BarChart::create(array(
    "title"=>"Total Device",
    "dataSource"=>$dataDevice,
    "columns"=>array(
        "category",
        "vsmart"=>array("label"=>"VSmart","type"=>"number","prefix"=>""),
        "vbond"=>array("label"=>"VBond","type"=>"number","prefix"=>""),
        "vmanage"=>array("label"=>"VManage","type"=>"number","prefix"=>""),
        "vedge"=>array("label"=>"VEdge","type"=>"number","prefix"=>""),
    ),
    "options"=>array(
        "isStacked"=>true
    )
));
?>

<div class="row">

</div>

<?php
ColumnChart::create(array(
    "title"=>"Wan Edge",
    "dataSource"=>$dataWanEdge,
    "columns"=>array(
        "category",
        "total"=>array("label"=>"Total","type"=>"number","prefix"=>""),
        "authorized"=>array("label"=>"Authorized","type"=>"number","prefix"=>""),
        "deployed"=>array("label"=>"Deployed","type"=>"number","prefix"=>""),
        "staging"=>array("label"=>"Staging","type"=>"number","prefix"=>""),
    )
));
?>

<?php
ColumnChart::create(array(
    "title"=>"Site Health",
    "dataSource"=>$dataSiteHealth,
    "columns"=>array(
        "category",
        "up"=>array("label"=>"UP","type"=>"number","prefix"=>""),
        "warning"=>array("label"=>"WARNING","type"=>"number","prefix"=>""),
        "down"=>array("label"=>"DOWN","type"=>"number","prefix"=>"")
    )
));
?>

<?php
ColumnChart::create(array(
    "title"=>"Transport Interface",
    "dataSource"=>$dataTransportInt,
    "columns"=>array(
        "category",
        "less_than_10_mbps"=>array("label"=>"less than 10 mbps","type"=>"number","prefix"=>""),
        "10_mbps_100_mbps"=>array("label"=>"10 mbps 100 mbps","type"=>"number","prefix"=>""),
        "100_mbps_500_mbps"=>array("label"=>"100 mbps 500 mbps","type"=>"number","prefix"=>""),
        "greater_than_500_mbps"=>array("label"=>"greater than 500 mbps","type"=>"number","prefix"=>"")
    )
));
?>
