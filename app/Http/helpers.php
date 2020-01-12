<?php

function sortedInsert(&$sortedArray, $element, $bythis){

    logger("DEBUG helper - sortedInsert: ".$bythis." = ".$element[$bythis]);


    $arraySize = count($sortedArray);

    $enteredAndLeft = 0; // If the index has entered and left the "samecategory". 1 means entered, 2 means left. 

    for($i = $arraySize;  $i>=1; $i--){
        //logger("DEBUG helper - sortedInsert: ".$bythis." = ".$element[$bythis]);
        $arraySize = count($sortedArray);
        
        for($i = $arraySize;  $i>=1 && ( strcmp($sortedArray[$i-1][$bythis], $element[$bythis]) > 0); $i--){
            $sortedArray[$i] = $sortedArray[$i -1];
        }
        
        $sortedArray[$i] = $element;
        return "";
}


?>
