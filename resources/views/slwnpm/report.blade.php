<?php
use \koolreport\widgets\google\BarChart;
?>

<?php
BarChart::create(array(
    "title"=>"Total Node",
    "dataSource"=>$result1,
    "columns"=>array(
        "category",
        "NodeUp"=>array("label"=>"Node Up","type"=>"number","prefix"=>""),
        "NodeDown"=>array("label"=>"Node Down","type"=>"number","prefix"=>""),
//        "IntUp"=>array("label"=>"Interface Up","type"=>"number","prefix"=>""),
//        "IntDown"=>array("label"=>"Interface Down","type"=>"number","prefix"=>""),
    ),
    "options"=>array(
        "isStacked"=>true
    )
));
?>

<?php
BarChart::create(array(
    "title"=>"Total Interface",
    "dataSource"=>$result2,
    "columns"=>array(
        "category",
        "IntUp"=>array("label"=>"Interface Up","type"=>"number","prefix"=>""),
        "IntDown"=>array("label"=>"Interface Down","type"=>"number","prefix"=>""),
    ),
    "options"=>array(
        "isStacked"=>true
    )
));
?>
