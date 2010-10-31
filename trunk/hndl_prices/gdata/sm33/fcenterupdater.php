<?php

function loadPrice($fName) {
  $fp = @fopen ( $fName, "r" );
  
  $buffer = '';
  
  if ($fp) {
    while ( ! feof ( $fp ) ) {
      $buffer .= @fgets ( $fp,500000 );
    }
    @fclose ( $fp );
    return $buffer;
  } else {
    return "";
  }

}

function updateFCenterFromFile($fName, $uName, $pass) {
  $pText = loadPrice ( $fName );
  $razdels = preg_split('<a name=\"[0123456789]*\">.*?</a>', $pText /*, -1, PREG_SPLIT_OFFSET_CAPTURE*/);
}

function testFCenter() {
  $fName = '../fcenter/price.html';
  $pText = loadPrice ( $fName );
}
?>
