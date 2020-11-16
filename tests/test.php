<!DOCTYPE html>
<html>
  <body>
    <?php
      $note=
      '<?xml version="1.0" encoding="UTF-8"?><UDSObjectList> <UDSObject> <Handle>cr:783932</Handle> <Attributes> <Attribute DataType="2001"> <AttrName>id</AttrName> <AttrValue>783932</AttrValue> </Attribute> <Attribute DataType="2002"> <AttrName>ref_num</AttrName> <AttrValue>2517724</AttrValue> </Attribute> <Attribute DataType="2002"> <AttrName>summary</AttrName> <AttrValue>adasd</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>priority</AttrName> <AttrValue>0</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>category</AttrName> <AttrValue/> </Attribute> <Attribute DataType="2005"> <AttrName>affected_resource</AttrName> <AttrValue>54D21BFB6922F943A3DD8D28268AC1E3</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>status</AttrName> <AttrValue>OP</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>group</AttrName> <AttrValue>4E511A826431814CB48FD97E3D2040F6</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>assignee</AttrName> <AttrValue/> </Attribute> <Attribute DataType="2005"> <AttrName>zmain_tech</AttrName> <AttrValue/> </Attribute> <Attribute DataType="2004"> <AttrName>open_date</AttrName> <AttrValue>1602148283</AttrValue> </Attribute> <Attribute DataType="2004"> <AttrName>last_mod_dt</AttrName> <AttrValue>1602148292</AttrValue> </Attribute> <Attribute DataType="2001"> <AttrName>sla_violation</AttrName> <AttrValue>0</AttrValue> </Attribute> </Attributes> </UDSObject> <UDSObject> <Handle>cr:783832</Handle> <Attributes> <Attribute DataType="2001"> <AttrName>id</AttrName> <AttrValue>783832</AttrValue> </Attribute> <Attribute DataType="2002"> <AttrName>ref_num</AttrName> <AttrValue>2517716</AttrValue> </Attribute> <Attribute DataType="2002"> <AttrName>summary</AttrName> <AttrValue>test</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>priority</AttrName> <AttrValue>0</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>category</AttrName> <AttrValue>pcat:402413</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>affected_resource</AttrName> <AttrValue>69A4C42E2C8C5F4BAC61911F4262E770</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>status</AttrName> <AttrValue>RE</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>group</AttrName> <AttrValue>BF7E95755AB1D14FA103AA7B067E9C2C</AttrValue> </Attribute> <Attribute DataType="2005"> <AttrName>assignee</AttrName> <AttrValue/> </Attribute> <Attribute DataType="2005"> <AttrName>zmain_tech</AttrName> <AttrValue>9834336E076F0243B32B20B773B2DC22</AttrValue> </Attribute> <Attribute DataType="2004"> <AttrName>open_date</AttrName> <AttrValue>1602061434</AttrValue> </Attribute> <Attribute DataType="2004"> <AttrName>last_mod_dt</AttrName> <AttrValue>1602062732</AttrValue> </Attribute> <Attribute DataType="2001"> <AttrName>sla_violation</AttrName> <AttrValue>0</AttrValue> </Attribute> </Attributes> </UDSObject> </UDSObjectList> ';


    $xml = simplexml_load_string($note);
    $arr = json_decode(json_encode($xml), true);
    print_r($xml);
    // echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
    // var_dump($xml);

    foreach ($arr as $item) {
      foreach ($item as $key) {
        echo $key;
      }
      echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";
    }
    ?>
  </body>
</html>
