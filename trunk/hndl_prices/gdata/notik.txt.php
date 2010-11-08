<?php
$fName = '../notik/fullprice.htm';
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

//!!!!!!!!!!!!!!!!
$sres = preg_match_all ( '/<table class=\"fullprice\"[^>]*?>(.*)<\/tr><\/table>/', $pText, $rar, PREG_PATTERN_ORDER );
echo $sres . "\n";
//!!!!!!!!!!!!!!!!
//echo $rar[0][0];
$fullPrc = $rar [0] [0];
$rws = preg_split ( '/<tr>(.*?)<\/tr>/ims', $fullPrc, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
echo count ( $rws );

$csvile = @fopen ( "tmp/notik.csv", "w" );

foreach ( $rws as $k => $v ) {
  /*
  $ile = @fopen ( "tmp/notik/$k", "w" );
  @fwrite ( $ile, $v );
  fclose ( $ile );
  */
  $noteInfo = null;
  if ($k < 1) {
    //$noteInfo [0] = 'Код';
    $noteInfo = array ('code', 'model', 'description', 'price.nal', 'price.notnal', 'infourl');
    
  /*
    $noteInfo [1] = '������������';
    $noteInfo [2] = '��������';
    $noteInfo [3] = '���� ���';
    $noteInfo [4] = '���� ������.';
    $noteInfo [5] = '������ �� ����';
    */
  } else {
    //$rw=null;
    $nout1delim = preg_split ( '/<td[^>]*?>(.*?)<\/td>/ims', $v, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
    //echo count ( $nout1delim );
    $noteInfo [0] = $nout1delim [0]; // Код (�������)
    $noteInfo [1] = preg_replace ( '/<a[^>]*?>/ims', '', $nout1delim [2] ); // Наименование
    $sres = preg_match ( "/<a href='([^']*?)'>/i", $nout1delim [2], $rar );
    //echo $nout1delim [2] . "\n";
    //echo count ( $rar [0] ) . "\n";
    //echo $rar [0] [0] . "\n";
    $noteInfo [2] = $nout1delim [3]; // Описание
    $noteInfo [3] = $nout1delim [6] + 0; // Цена нал.
    $noteInfo [4] = $nout1delim [7] + 0; // Цена безнал.
    

    $sres = preg_match ( "/<a href='([^']*?)'>/i", $nout1delim [2], $rar );
    $aaa = preg_split ( "/<a href='([^']*?)'>/i", $rar [0], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
    //echo $rar[0]."\n";
    $noteInfo [5] = 'http://www.notik.ru' . $aaa [0]; // Ссылка на описание на сайте нотик.ру
  }
  
  fputcsv ( $csvile, $noteInfo );
}
fclose ( $csvile );
die ();

$nout1delim = preg_split ( '/<td[^>]*?>(.*?)<\/td>/ims', $rws [1], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
//echo count ( $nout1delim );
$noteInfo = null;
$noteInfo [0] = $nout1delim [0]; // ��� (�������)
$noteInfo [1] = preg_replace ( '/<a[^>]*?>/ims', '', $nout1delim [2] ); // ������������
$sres = preg_match ( "/<a href='([^']*?)'>/i", $nout1delim [2], $rar );
echo $rar [0] . "\n";
$aaa = preg_split ( "/<a href='([^']*?)'>/i", $rar [0], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
echo count ( $aaa ) . "\n";
echo $aaa [0] . "\n";

echo $nout1delim [2] . "\n";
echo count ( $rar [0] ) . "\n";
echo $rar [0] [0] . "\n";

echo $noteInfo [1] . "\n";
echo preg_replace ( '/<a[^>]*?>/ims', '', $noteInfo [1] ) . "\n";

foreach ( $nout1delim as $k => $v ) {
  $ile = @fopen ( "tmp/notik/1.z/$k", "w" );
  @fwrite ( $ile, $v );
  fclose ( $ile );
  //$rw=null;
}

//!-------------------------------------------------
// <table width="100%" class="fullprice">
// </table>
//$razdels = preg_split('/<a name=\"[0123456789]*?\">.*?<\/a>/ims', $pText, -1, PREG_SPLIT_DELIM_CAPTURE);
$razdels = preg_split ( '/<table width=\"100\%\" class=\"fullprice\">(.*?)<\/table>/ims', $pText, - 1, PREG_SPLIT_DELIM_CAPTURE );
$razdels = preg_split ( '/table[^>]*?class=/ims', $pText, - 1 );
//$razdels = preg_split ( '/<\/table>/ims', $razdels[0], - 1 );
echo count ( $razdels ) . "\n";
echo $razdels [0];

$rar [] = null;
$sres = preg_match ( '/
<table width=\"100\%\" class=\"fullprice\">(.*?)<\/table>/ims', $pText, $rar );

$sres = preg_match ( '/<table.*?>(.*?)<\/table>/ims', $pText, $rar );
$sres = preg_match_all ( '/<table [^>]*?>(.*?)<\/table>/ims', $pText, $rar );
$sres = preg_match_all ( '/fullprice(.*?)table>/', $pText, $rar );
$sres = preg_match_all ( '/fullprice(.*?)table>/', $pText, $rar );
// class="fullprice" width="100%"> 
$sres = preg_match_all ( '/<table[^>]*?>(.*?)<\/table>/', $pText, $rar, PREG_PATTERN_ORDER );

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
  $rw = null;
  
  if ($k < 1) {
    continue;
  }
  
  $nout1 = $v;
  $nout1delim = preg_split ( '/<td[^>]*?>(.*?)<\/td>/ims', $v, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
  $rw [] = $nout1delim [0]; // ���
  

  $ntdescriptionarr = preg_split ( '/<a[^>]*?>(.*?)<\/a>/ims', $nout1delim [1], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
  
  $rw [] = $ntdescriptionarr [0]; // ������������ � ������
  $rw [] = $nout1delim [1]; // ������������ � ������
  $rw [] = $nout1delim [5]; // ����
  

  $ntdescriptionarr = preg_split ( '/"/ims', $nout1delim [1], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
  $rw [] = $ntdescriptionarr [1];
  fputcsv ( $csvile, $rw );
}
;
fclose ( $csvile );

$nout1 = $rws [1];
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


echo ($nout1delim [1] . "\n");
echo ($nout1delim [5] . "\n");

$ntdescription = $nout1delim [1];
//$ntdescriptionarr = preg_split ( '/<a href=\"[^\"]*?\">(.*?)<\/a>/ims', $ntdescription, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
$ntdescriptionarr = preg_split ( '/<a[^>]*?>(.*?)<\/a>/ims', $ntdescription, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
//echo count ( $ntdescriptionarr )."\n";
echo ($ntdescriptionarr [0] . "\n");

$ntdescriptionarr = preg_split ( '/"/ims', $ntdescription, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
//echo count ( $ntdescriptionarr )."\n";
echo ($ntdescriptionarr [1] . "\n"); // ������ �� �������� ������ �� �������








