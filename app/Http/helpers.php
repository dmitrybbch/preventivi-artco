<?php

function sortedInsert(&$sortedArray, $element, $bythis){

    //logger("DEBUG helper - sortedInsert: ".$bythis." = ".$element[$bythis]);

    $arraySize = count($sortedArray);

    for($i = $arraySize;  $i>=1 && ( strcmp($sortedArray[$i-1][$bythis], $element[$bythis]) > 0); $i--){
        $sortedArray[$i] = $sortedArray[$i -1];
    }
    $sortedArray[$i] = $element;
    return "";
}




?>
