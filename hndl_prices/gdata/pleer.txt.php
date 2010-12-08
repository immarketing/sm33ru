<?php
function trimUnusedChars($pt) {
  $pt = preg_replace ( "/\n/ims", '', $pt );
  $pt = preg_replace ( "/\r/ims", '', $pt );
  $pt = preg_replace ( "/\t/ims", '', $pt );
  $pt = preg_replace ( "/&nbsp;/ims", '', $pt );
  return $pt;
}
function loadStartPage($fromURL, $toPage) {
  // 
  try {
    $url = $fromURL;
    $fpout = @fopen ( $toPage, "w" );
    if ($fpout) {
      $ch = curl_init ();
      curl_setopt ( $ch, CURLOPT_URL, $url );
      curl_setopt ( $ch, CURLOPT_FILE, $fpout );
      curl_setopt ( $ch, CURLOPT_HEADER, 0 );
      
      curl_exec ( $ch );
      fclose ( $fpout );
      return true;
    }
  } catch ( Exception $e ) {
    throw $e;
  }
  return false;

}

function loadFile($fName) {
  $fp = @fopen ( $fName, "r" );
  $buffer = '';
  while ( ! feof ( $fp ) ) {
    $buffer .= @fgets ( $fp, 500000 );
  }
  ;
  @fclose ( $fp );
  return $buffer;
}

function getProdRows($ldd) {
  $rws = array ();
  echo preg_match_all ( '/<tr[^>]*?class=\"product_row\"[^>]*?>(.*?)<\/tr>/ims', $ldd, $rws );
  //foreach ( $rws as $k => $v ) {
  //  echo $k . "\t====\t" . $v . "\n";
  //}
  return $rws [0];
}

function splitProductInfo($prodText) {
  // <p>Товар №: 
  //$res = preg_match ( "/<p>Товар №: ([^<]*?)</ims", $prodText, $nInfo );
  $res = preg_match ( "/<p>[^:]*?: ([^<]*?)</ims", $prodText, $nInfo );
  //echo $res."\n";
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
  
  $res = preg_match ( "/<h1><a[^>]*?>([^<]*?)</ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
  
  $res = preg_match ( "/<p[^i]*?id=\"s_desc[^>]*?>([^<]*?)</ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
  
  $res = preg_match ( "/<span[^c]*?class=\"price[^>]*?>([^<]*?)</ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
    
  return $prodInfo;
}

if (! loadStartPage ( 'http://pleer.ru/eletr-knigi.html', "./tmp/pleer.ru/eletr-knigi.html" )) {
  die ( "UNKNOWN ERROR!\n" );
}

function storeCVS($arr, $fName) {
  $csvProps = @fopen ( $fName, "w" );
  $locArr = array ();
  
  foreach ( $arr as $k => $v ) {
    fputcsv ( $csvProps, $v );
  }
  fclose ( $csvProps );
}

$loaded = loadFile ( "./tmp/pleer.ru/eletr-knigi.html" );
$loaded = trimUnusedChars ( $loaded );
//echo $loaded."\n";


$rws = getProdRows ( $loaded );
//$rws = $rws[0];


$prodInfoArray = array ();
$hdr=array('code','name','desc','price');
$prodInfoArray[]=$hdr;
foreach ( $rws as $k => $v ) {
  //echo $k . "\t====\t" . $v . "\n";
  $rw = splitProductInfo ( $v );
  if (! $rw) {
    continue;
  }
  $prodInfoArray [] = $rw;
}

storeCVS ( $prodInfoArray, "tmp/pleer_elbooks.csv" );
