<?php

function sortedInsert(&$sortedArray, $element, $bythis){

    logger("DEBUG helper: inserimento".$element['Computer']);

    $arraySize = count($sortedArray);

    for($i = $arraySize;  $i>=1 && ( strcmp($sortedArray[$i-1]['Computer'], $element['Computer']) > 0); $i--){
        $sortedArray[$i] = $sortedArray[$i -1];
    }
    $sortedArray[$i] = $element;
    return "";
}




?>
