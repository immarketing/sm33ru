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


$csvile = @fopen ( "tmp/fcenter.csv", "w" );
// $rws - ������ �����-�����
foreach ( $rws as $k => $v ) {
  $ile = @fopen ( "tmp/1/$k", "w" );
  @fwrite ( $ile, $v );
  fclose ( $ile );
  $rw=null;
  
  if ($k <1){
    continue;
  }
  
  $nout1=$v;
  $nout1delim = preg_split ( '/<td[^>]*?>(.*?)<\/td>/ims', $v, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
  $rw[]=$nout1delim[0]; // ���
  
  $ntdescriptionarr = preg_split ( '/<a[^>]*?>(.*?)<\/a>/ims', $nout1delim[1], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE ); 
  
  $rw[]=$ntdescriptionarr[0]; // ������������ � ������
  $rw[]=$nout1delim[1]; // ������������ � ������
  $rw[]=$nout1delim[5]; // ����
  
  $ntdescriptionarr = preg_split ( '/"/ims', $nout1delim[1], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
  $rw[]=$ntdescriptionarr[1];
  fputcsv ($csvile,$rw);
}
;
fclose ( $csvile );

$nout1=$rws[1];
//echo($nout1);
//$nout1 - ������ � ��������� ������
$nout1delim = preg_split ( '/<td[^>]*?>(.*?)<\/td>/ims', $nout1, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
foreach ( $nout1delim as $k => $v ) {
  $ile = @fopen ( "tmp/1/z/$k", "w" );
  @fwrite ( $ile, $v );
  fclose ( $ile );
}
;
// $nout1delim - ����������� �������� ������
// $nout1delim[0] - ��� ������
// $nout1delim[1] - �������� ������ ��������� (������ � ��������)
// $nout1delim[5] - ���� ������

echo($nout1delim[1]."\n");
echo($nout1delim[5]."\n");

$ntdescription = $nout1delim[1];
//$ntdescriptionarr = preg_split ( '/<a href=\"[^\"]*?\">(.*?)<\/a>/ims', $ntdescription, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
$ntdescriptionarr = preg_split ( '/<a[^>]*?>(.*?)<\/a>/ims', $ntdescription, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE ); 
//echo count ( $ntdescriptionarr )."\n";
echo($ntdescriptionarr[0]."\n");

$ntdescriptionarr = preg_split ( '/"/ims', $ntdescription, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE ); 
//echo count ( $ntdescriptionarr )."\n";
echo($ntdescriptionarr[1]."\n"); // ������ �� �������� ������ �� �������








