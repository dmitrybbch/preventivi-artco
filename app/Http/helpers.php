<?php

function sortedInsert(&$sortedArray, $element, $bythis){

    //logger("DEBUG helper - sortedInsert: ".$bythis." = ".$element[$bythis]);


    $arraySize = count($sortedArray);

    $enteredAndLeft = 0; // If the index has entered and left the "samecategory". 1 means entered, 2 means left. 

    for($i = $arraySize;  $i>=1 && ( strcmp($sortedArray[$i-1]['subcategoria'], $element['subcategoria']) > 0); $i--){
        if( $enteredAndLeft == 0 &&  strcmp($sortedArray[$i-1]['categoria'], $element['categoria']) == 0  )
            $enteredAndLeft++;
        if( $enteredAndLeft == 1 &&  strcmp($sortedArray[$i-1]['categoria'], $element['categoria']) > 0   )
            $enteredAndLeft++;
        if($enteredAndLeft == 2)
            $sortedArray[$i] = $sortedArray[$i -1];
    }
    $sortedArray[$i] = $element;
    return "";
}


?>
