<?php
$fName = '../laptopscope/brand.htm';
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

//<ul class="catalog">
$sres = preg_match_all ( '/<ul class=\"catalog\"[^>]*?>(.*?)<\/ul>/', $pText, $rar, PREG_PATTERN_ORDER );
//echo $sres . "\n";
$fullPrc = $rar [0] [0];

$rws = preg_split ( '/<li>(.*?)<\/li>/ims', $fullPrc, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
echo count ( $rws );

$sresList = preg_match_all ( '/<div class=\"pager nojs-pages\"[^>]*?>(.*?)<\/div>/', $pText, $rarList, PREG_PATTERN_ORDER );
//echo $sres . "\n";
$fullList = $rarList [0] [0];
$rwsList = preg_split ( '/<li[^>]*?>(.*?)<\/li>/ims', $fullList, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
echo count ( $rwsList );
$csvile = @fopen ( "tmp/laptopscope.csv", "w" );

foreach ( $rwsList as $k => $v ) {
  $nInfo = array ();
  $noteInfo = array ();
  $res = preg_match ( "/href=\"([^\"]*?)\"/ims", $v, $nInfo );
  if (! $res) {
    continue;
  }
  // $res = preg_match("/href=\"([^\"]*?)\"/ims", $rws[0],$noteInfo);
  $noteInfo [] = $nInfo [1];
  fputcsv ( $csvile, $noteInfo );
  
  $fpout = @fopen ( "tmp/laptopscope/$k", "w" );
  if ($fpout) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, "http://www.laptopscope.ru/catalog/category/brand.htm" . $nInfo [1] );
    curl_setopt ( $ch, CURLOPT_FILE, $fpout );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    
    curl_exec ( $ch );
    fclose ( $fpout );
  }

}

foreach ( $rws as $k => $v ) {
  $noteInfo = null;
  if ($k < 1) {
    $noteInfo = array ('url', 'name', 'description', 'price.nal', 'price.notnal', 'infourl' );
  } else {
    $nInfo = array ();
    $noteInfo = array ();
    
    //$res = preg_match("/href=\"([^\"]*?)\"/ims", $rws[1],$noteInfo);
    $res = preg_match ( "/href=\"([^\"]*?)\"/ims", $v, $nInfo );
    
    if (! $res) {
      continue;
    }
    // $res = preg_match("/href=\"([^\"]*?)\"/ims", $rws[0],$noteInfo);
    $noteInfo [] = $nInfo [1];
    
    $theName = "";
    
    //$res = preg_match("/<a class=\"name\"[^\>]*?\>([^<]*?)<\/a>/ims", $v,$nInfo );
    $res = preg_match ( "/class=\"name\"[^>]*?>([^<]*?)</ims", $v, $nInfo );
    if (! $res) {
      continue;
    }
    $noteInfo [] = $nInfo [1];
    $theName = $nInfo [1];
    
    // class="stats"
    $res = preg_match ( "/class=\"stats\"[^>]*?>([^<]*?)</ims", $v, $nInfo );
    if (! $res) {
      continue;
    }
    $noteInfo [] = $nInfo [1];
    
    // class="price"
    $res = preg_match ( "/class=\"price\"[^>]*?>[^0123456789]*?([^ ]*?) /ims", $v, $nInfo );
    if (! $res) {
      continue;
    }
    $noteInfo [] = $nInfo [1];
    
    // class="model"
    $res = preg_match ( "/$theName \(([^\)]*?)\)/ims", $v, $nInfo );
    if (! $res) {
      continue;
    }
    $noteInfo [] = $nInfo [1];
    
  // echo $noteInfo[1];
  //$noteInfo [5] = 'http://www.notik.ru' . $aaa [0]; // Ссылка на описание на сайте нотик.ру
  }
  
  fputcsv ( $csvile, $noteInfo );
}
fclose ( $csvile );
die ();
    


