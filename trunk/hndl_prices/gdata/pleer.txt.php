<?php
function timeStampedEcho($outSt) {
  $t = date ( 'Ymd H:i:s' );
  echo "[$t]\t$outSt";
}

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
  //echo 
  preg_match_all ( '/<tr[^>]*?class=\"product_row\"[^>]*?>(.*?)<\/tr>/ims', $ldd, $rws );
  //foreach ( $rws as $k => $v ) {
  //  echo $k . "\t====\t" . $v . "\n";
  //}
  return $rws [0];
}

function splitProductInfo($prodText) {
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
  
  // <p>Товар №: 
  //$res = preg_match ( "/<p>Товар №: ([^<]*?)</ims", $prodText, $nInfo );
  $res = preg_match ( "/<p>[^:]*?: ([^<]*?)</ims", $prodText, $nInfo );
  //echo $res."\n";
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
  
  $res = preg_match ( "/<span[^c]*?class=\"price[^>]*?>([^<]*?)</ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
  
  $res = preg_match ( "/<h1><a[^h]*?href=\"([^\"]*?)\"/ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = 'http://www.pleer.ru' . $nInfo [1];
  
  return $prodInfo;
}

function storeCVS($arr, $fName) {
  $csvProps = @fopen ( $fName, "w" );
  $locArr = array ();
  
  foreach ( $arr as $k => $v ) {
    fputcsv ( $csvProps, $v );
  }
  fclose ( $csvProps );
}

function loadPleerRuPrice($sourceURL, $tmpFName, $cvsFName, &$fullData) {
  //if (! loadStartPage ( 'http://pleer.ru/eletr-knigi.html', "./tmp/pleer.ru/eletr-knigi.html" )) {
  timeStampedEcho ( "Handling [$sourceURL]\n" );
  if (! loadStartPage ( $sourceURL, $tmpFName )) {
    die ( "UNKNOWN ERROR!\n" );
  }
  timeStampedEcho ( "[$sourceURL] loaded\n" );
  
  //$loaded = loadFile ( "./tmp/pleer.ru/eletr-knigi.html" );
  

  $loaded = loadFile ( $tmpFName );
  timeStampedEcho ( "[$sourceURL] readed in memory\n" );
  $loaded = trimUnusedChars ( $loaded );
  //echo $loaded."\n";
  

  $rws = getProdRows ( $loaded );
  timeStampedEcho ( "[$sourceURL] trimmed in lines\n" );
  //$rws = $rws[0];
  

  $prodInfoArray = array ();
  $hdr = array ('name', 'description', 'pleer_ru_code', 'pleer_ru_price', 'pleer_ru_url' );
  $prodInfoArray [] = $hdr;
  if (!count($fullData)){
    $fullData[]= $hdr;
  }
  foreach ( $rws as $k => $v ) {
    //echo $k . "\t====\t" . $v . "\n";
    $rw = splitProductInfo ( $v );
    if (! $rw) {
      continue;
    }
    $prodInfoArray [] = $rw;
    if (strlen($rw[1]) > 100) {
      $rw[1] = substr ($rw[1],0,100)."...";
    }
     
    $fullData[]= $rw;
  }
  
  //storeCVS ( $prodInfoArray, "tmp/pleer_elbooks.csv" );
  timeStampedEcho ( "[$sourceURL] trimmed in products\n" );
  
  storeCVS ( $prodInfoArray, $cvsFName );
  timeStampedEcho ( "[$sourceURL] saved in [$cvsFName]\n" );
}

$fullPrice=array();

loadPleerRuPrice ( 'http://pleer.ru/eletr-knigi.html', "./tmp/pleer.ru/eletr-knigi.html", "tmp/pleer_elbooks.csv" ,$fullPrice);
loadPleerRuPrice ( 'http://www.pleer.ru/kpk-i-kommunikatory~all.html', "./tmp/pleer.ru/kpk-komm.html", "tmp/pleer_kpk_tel.csv",$fullPrice );
loadPleerRuPrice ( 'http://pleer.ru/mediapleery.html', "./tmp/pleer.ru/mediapl.html", "tmp/pleer_mediapl.csv",$fullPrice );
loadPleerRuPrice ( 'http://pleer.ru/gps-avtonavigaciya.html', "./tmp/pleer.ru/gps-navi.html", "tmp/pleer_gpsnavi.csv",$fullPrice );
loadPleerRuPrice ( 'http://pleer.ru/vebkamery.html', "./tmp/pleer.ru/webcams.html", "tmp/pleer_webcams.csv",$fullPrice );
loadPleerRuPrice ( 'http://pleer.ru/1gb-i-bolee.html', "./tmp/pleer.ru/mp3pleer.html", "tmp/pleer_mp3pleer.csv",$fullPrice );
loadPleerRuPrice ( 'http://pleer.ru/cifrovye-fotoapparaty~all.html', "./tmp/pleer.ru/photoapp.html", "tmp/pleer_photoapp.csv",$fullPrice );

storeCVS($fullPrice, "tmp/pleer_all.csv");

timeStampedEcho ( "Done!\n" );


