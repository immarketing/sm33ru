<?php
$fName = '../fcenter/price.html';
$fp = @fopen ( $fName, "r" );
$buffer = '';
while ( ! feof ( $fp ) ) {
  $buffer .= @fgets ( $fp, 500000 );
}
;
@fclose ( $fp );
$pText = $buffer;

$pText = preg_replace ( "/\n/ims", '', $pText );
$pText = preg_replace ( "/\r/ims", '', $pText );
$pText = preg_replace ( "/\t/ims", '', $pText );
$pText = preg_replace ( "/&nbsp;/ims", '', $pText );

//$razdels = preg_split('/<a name=\"[0123456789]*?\">.*?<\/a>/ims', $pText, -1, PREG_SPLIT_DELIM_CAPTURE);
$razdels = preg_split ( '/<a name=\"[0123456789]*?\">(.*?)<\/a>/ims', $pText, - 1, PREG_SPLIT_DELIM_CAPTURE );
echo count ( $razdels );

foreach ( $razdels as $k => $v ) {
  $ile = @fopen ( "tmp/$k", "w" );
  @fwrite ( $ile, $v );
  fclose ( $ile );
}
;

$nouts = $razdels [8];
$rws = preg_split ( '/<tr>(.*?)<\/tr>/ims', $nouts, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
echo count ( $rws );
foreach ( $rws as $k => $v ) {
  if (strpos ( $v, '<td class=l>' ) > 0) {
  
  } else {
    $rws [$k] = null;
  }
}
;

foreach ( $rws as $k => $v ) {
  $ile = @fopen ( "tmp/1/$k", "w" );
  @fwrite ( $ile, $v );
  fclose ( $ile );
}
;

$nout1=$rws[1];
echo($nout1);
$nout1delim = preg_split ( '/<td[^>]*?>(.*?)<\/td>/ims', $nout1, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
foreach ( $nout1delim as $k => $v ) {
  $ile = @fopen ( "tmp/1/z/$k", "w" );
  @fwrite ( $ile, $v );
  fclose ( $ile );
}
;









