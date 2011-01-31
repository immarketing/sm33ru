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
  /*
  $res = preg_match ( "/<h1><a[^>]*?>([^<]*?)</ims", $prodText, $nInfo );
  */
  $res = preg_match ( "/22px[^<]*?<a[^>]*?>([^<]*?)</ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
  
  $res = preg_match ( "/<p[^i]*?id=\"s_desc[^>]*?>([^<]*?)</ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = $nInfo [1];
  
  // <p>РўРѕРІР°СЂ в„–: 
  //$res = preg_match ( "/<p>РўРѕРІР°СЂ в„–: ([^<]*?)</ims", $prodText, $nInfo );
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
  /*
  $res = preg_match ( "/<h1><a[^h]*?href=\"([^\"]*?)\"/ims", $prodText, $nInfo );
  */
  $res = preg_match ( "/22px[^<]*?<a[^h]*?href=\"([^\"]*?)\"/ims", $prodText, $nInfo );
  if (! $res) {
    return null;
  }
  $prodInfo [] = 'http://www.pleer.ru' . $nInfo [1];
  
  $res = preg_match ( "/Наличие[^В]*?Временно недоступен/ims", $prodText, $nInfo );
  if ($res) {
    $prodInfo [] = 0;
  } else {
    if (preg_match ( "/Наличие[^о]*?ожидается/ims", $prodText, $nInfo )) {
      $prodInfo [] = 0;
    
    } else {
      $prodInfo [] = 1;
    
    }
  
  }
  
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

function loadPleerRuPrice($sourceURL, $tmpFName, $cvsFName, &$fullData, $tp) {
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
  $hdr = array ('name', 'description', 'pleer_ru_code', 'pleer_ru_price', 'pleer_ru_url' , 'nalichie', 'type');
  $prodInfoArray [] = $hdr;
  if (! count ( $fullData )) {
    $fullData [] = $hdr;
  }
  foreach ( $rws as $k => $v ) {
    //echo $k . "\t====\t" . $v . "\n";
    $rw = splitProductInfo ( $v );
    if (! $rw) {
      continue;
    }
    $rw[]=$tp;
    $prodInfoArray [] = $rw;
    if (strlen ( $rw [1] ) > 100) {
      $rw [1] = substr ( $rw [1], 0, 100 ) . "...";
    }
    
    $fullData [] = $rw;
  }
  
  //storeCVS ( $prodInfoArray, "tmp/pleer_elbooks.csv" );
  timeStampedEcho ( "[$sourceURL] trimmed in products\n" );
  
  storeCVS ( $prodInfoArray, $cvsFName );
  timeStampedEcho ( "[$sourceURL] saved in [$cvsFName]\n" );
}

$fullPrice = array ();

loadPleerRuPrice ( 'http://pleer.ru/eletr-knigi.html', "./tmp/pleer.ru/eletr-knigi.html", "tmp/pleer_elbooks.csv", $fullPrice, 'elknigi' );
loadPleerRuPrice ( 'http://www.pleer.ru/kpk-i-kommunikatory~all.html', "./tmp/pleer.ru/kpk-komm.html", "tmp/pleer_kpk_tel.csv", $fullPrice, 'kpk_tel' );
loadPleerRuPrice ( 'http://pleer.ru/mediapleery.html', "./tmp/pleer.ru/mediapl.html", "tmp/pleer_mediapl.csv", $fullPrice, 'mediapl' );
loadPleerRuPrice ( 'http://pleer.ru/gps-avtonavigaciya.html', "./tmp/pleer.ru/gps-navi.html", "tmp/pleer_gpsnavi.csv", $fullPrice, 'gpsnav' );
loadPleerRuPrice ( 'http://pleer.ru/vebkamery.html', "./tmp/pleer.ru/webcams.html", "tmp/pleer_webcams.csv", $fullPrice, 'webcams' );
loadPleerRuPrice ( 'http://pleer.ru/1gb-i-bolee.html', "./tmp/pleer.ru/mp3pleer.html", "tmp/pleer_mp3pleer.csv", $fullPrice, 'mp3pleer' );
loadPleerRuPrice ( 'http://pleer.ru/cifrovye-fotoapparaty~all.html', "./tmp/pleer.ru/photoapp.html", "tmp/pleer_photoapp.csv", $fullPrice, 'photoapp' );

//http://www.pleer.ru/dlya-canon_v.html
loadPleerRuPrice ( 'http://www.pleer.ru/dlya-canon_v.html', "./tmp/pleer.ru/photo_vsp_canon.html", "tmp/pleer_photo_vsp_canon.csv", $fullPrice, 'photo_vsp_canon' );
loadPleerRuPrice ( 'http://www.pleer.ru/dlya-nikon_v.html', "./tmp/pleer.ru/photo_vsp_nikon.html", "tmp/pleer_photo_vsp_nikon.csv", $fullPrice, 'photo_vsp_nikon' );
loadPleerRuPrice ( 'http://www.pleer.ru/vspyshki-dlya-olympus.html', "./tmp/pleer.ru/photo_vsp_olimpus.html", "tmp/pleer_photo_vsp_olimpus.csv", $fullPrice, 'photo_vsp_olimpus' );
loadPleerRuPrice ( 'http://www.pleer.ru/vspyshki-dlya-panasonic.html', "./tmp/pleer.ru/photo_vsp_panas.html", "tmp/pleer_photo_vsp_panas.csv", $fullPrice, 'photo_vsp_panas' );
loadPleerRuPrice ( 'http://www.pleer.ru/vs-for-pentax.html', "./tmp/pleer.ru/photo_vsp_pentax.html", "tmp/pleer_photo_vsp_pentax.csv", $fullPrice, 'photo_vsp_pentax' );
loadPleerRuPrice ( 'http://www.pleer.ru/dlya-minolta_v.html', "./tmp/pleer.ru/photo_vsp_minolta.html", "tmp/pleer_photo_vsp_minolta.csv", $fullPrice, 'photo_vsp_minolta' );

loadPleerRuPrice ( 'http://pleer.ru/dlya-canon.html', "./tmp/pleer.ru/photo_obj_canon.html", "tmp/pleer_photo_obj_canon.csv", $fullPrice, 'photo_obj_canon' );
loadPleerRuPrice ( 'http://pleer.ru/dlya-nikon.html', "./tmp/pleer.ru/photo_obj_nikon.html", "tmp/pleer_photo_obj_nikon.csv", $fullPrice, 'photo_obj_nikon' );
loadPleerRuPrice ( 'http://pleer.ru/obektivy-dlya-olympus.html', "./tmp/pleer.ru/photo_obj_olimpus.html", "tmp/pleer_photo_obj_olimpus.csv", $fullPrice, 'photo_obj_olimpus' );
loadPleerRuPrice ( 'http://pleer.ru/obektivy-dlya-pentax.html', "./tmp/pleer.ru/photo_obj_pentax.html", "tmp/pleer_photo_obj_pentax.csv", $fullPrice, 'photo_obj_pentax' );
loadPleerRuPrice ( 'http://pleer.ru/dlya-minolta.html', "./tmp/pleer.ru/photo_obj_sony.html", "tmp/pleer_photo_obj_sony.csv", $fullPrice, 'photo_obj_sony' );

storeCVS ( $fullPrice, "tmp/pleer_all.csv" );

timeStampedEcho ( "Done!\n" );



















