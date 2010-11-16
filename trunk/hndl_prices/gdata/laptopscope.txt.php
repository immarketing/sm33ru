<?php
function trimUnusedChars($pt) {
  $pt = preg_replace ( "/\n/ims", '', $pt );
  $pt = preg_replace ( "/\r/ims", '', $pt );
  $pt = preg_replace ( "/\t/ims", '', $pt );
  $pt = preg_replace ( "/&nbsp;/ims", '', $pt );
  return $pt;
}

$fName = '../laptopscope/brand.htm';
$fp = @fopen ( $fName, "r" );
$buffer = '';
while ( ! feof ( $fp ) ) {
  $buffer .= @fgets ( $fp, 500000 );
}
;
@fclose ( $fp );
$pText = $buffer;

function parseNoutInfoFile($fName, &$hdrArrs, &$vlsArrs) {
  $fp = @fopen ( $fName, "r" );
  $buffer = '';
  while ( ! feof ( $fp ) ) {
    $buffer .= @fgets ( $fp, 500000 );
  }
  ;
  @fclose ( $fp );
  $pText = $buffer;
  $pText = trimUnusedChars ( $pText );
  $sresList = preg_match_all ( '/<table class=\"stats\"[^>]*?>(.*?)<\/table>/', $pText, $rarList, PREG_PATTERN_ORDER );
  //echo $sresList . "\n";
  $fullDesc = $rarList [0] [0];
  //echo $fullDesc . "\n";
  

  $rws = preg_split ( '/<tr>(.*?)<\/tr>/ims', $fullDesc, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
  foreach ( $rws as $k => $v ) {
    $nInfo = array ();
    $res = preg_match ( "/class=\"key\"[^>]*?>([^:]*?):/ims", $v, $nInfo );
    if (! $res) {
      continue;
    }
    $kName = $nInfo [1];
    $nInfo = array ();
    $res = preg_match ( "/<td>([^<]*?)</ims", $v, $nInfo );
    if (! $res) {
      //continue;
    }
    $kVal = $nInfo [1];
    
    if (! $kVal) {
      $res = preg_match ( "/<b>([^ ]*?) /ims", $v, $nInfo );
      if (! $res) {
        continue;
      }
      $kVal = $nInfo [1];
    }
    $hdrArrs [$kName] = 1;
    $vlsArrs [$kName] = $kVal;
    //$noteInfo [] = $nInfo [1];
  //echo $kName.'=='. $kVal. "\n";
  

  }

}
/*
$fNameNoutInfo = 'tmp/laptopscope/notebook/Acer-Aspire-1425P-232G25ikk.htm';
$hdrArrs = array ();
$vlsArrs = array ();

parseNoutInfoFile ( $fNameNoutInfo, $hdrArrs, $vlsArrs );

die ();
*/

$pText = trimUnusedChars ( $pText );
$sresList = preg_match_all ( '/<div class=\"pager nojs-pages\"[^>]*?>(.*?)<\/div>/', $pText, $rarList, PREG_PATTERN_ORDER );
//echo $sres . "\n";
$fullList = $rarList [0] [0];
$rwsList = preg_split ( '/<li[^>]*?>(.*?)<\/li>/ims', $fullList, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
//echo count ( $rwsList );
$csvile = @fopen ( "tmp/laptopscope.csv", "w" );

function parseCatalogFileIntoArray($fCatName, &$resArr) {
  $fp = @fopen ( $fCatName, "r" );
  $buffer = '';
  while ( ! feof ( $fp ) ) {
    $buffer .= @fgets ( $fp, 500000 );
  }
  ;
  @fclose ( $fp );
  $pText = $buffer;
  
  //$pText = trimUnusedChars ( $pText );
  $pText = trimUnusedChars ( $pText );
  //<ul class="catalog">
  $sres = preg_match_all ( '/<ul class=\"catalog\"[^>]*?>(.*?)<\/ul>/', $pText, $rar, PREG_PATTERN_ORDER );
  //echo $sres . "\n";
  $fullPrc = $rar [0] [0];
  
  $rws = preg_split ( '/<li>(.*?)<\/li>/ims', $fullPrc, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );
  foreach ( $rws as $k => $v ) {
    $noteInfo = null;
    if ($k < 1) {
      //$noteInfo = array ('url', 'name', 'description', 'price.nal', 'price.notnal', 'infourl' );
    //$resArr [] = $noteInfo;
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
      

      $theName = preg_replace ( "%/%ims", '\/', $theName );
      $res = preg_match ( "/$theName \(([^\)]*?)\)/ims", $v, $nInfo );
      if (! $res) {
        continue;
      }
      $noteInfo [] = $nInfo [1];
      
    // echo $noteInfo[1];
    //$noteInfo [5] = 'http://www.notik.ru' . $aaa [0]; // Ссылка на описание на сайте нотик.ру
    }
    
    //fputcsv ( $csvile, $noteInfo );
    $resArr [] = $noteInfo;
  }
}

$fullNoteInfo = array ();
$noteInfo = array ('url', 'name', 'description', 'price.nal', 'price.notnal', 'infourl' );
$fullNoteInfo [] = $noteInfo;

foreach ( $rwsList as $k => $v ) {
  $nInfo = array ();
  $noteInfo = array ();
  $res = preg_match ( "/href=\"([^\"]*?)\"/ims", $v, $nInfo );
  if (! $res) {
    continue;
  }
  // $res = preg_match("/href=\"([^\"]*?)\"/ims", $rws[0],$noteInfo);
  $noteInfo [] = $nInfo [1];
  //fputcsv ( $csvile, $noteInfo );
  

  echo "tmp/laptopscope/$k\n";
  $fpout = @fopen ( "tmp/laptopscope/$k", "w" );
  if ($fpout) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, "http://www.laptopscope.ru/catalog/category/brand.htm" . $nInfo [1] );
    curl_setopt ( $ch, CURLOPT_FILE, $fpout );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    
    curl_exec ( $ch );
    fclose ( $fpout );
  }
  parseCatalogFileIntoArray ( "tmp/laptopscope/$k", $fullNoteInfo );
}

function loadFromURL($fOUT, $url) {
  $fpout = @fopen ( "$fOUT", "w" );
  if ($fpout) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, "$url" );
    curl_setopt ( $ch, CURLOPT_FILE, $fpout );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    
    curl_exec ( $ch );
    fclose ( $fpout );
  }
}

//$fNameNoutInfo = 'tmp/laptopscope/notebook/Acer-Aspire-1425P-232G25ikk.htm';


$hdrArrs = array ();
$hdrArrs ['name'] = 1;
$hdrArrs ['description'] = 1;

$allPropArray = array ();

foreach ( $fullNoteInfo as $k => $v ) {
  if ($k <= 1) {
    continue;
  }
  $iriName = $v [0]; //$fullNoteInfo [2] [0];
  $iriName = preg_split ( '%/%ims', $iriName );
  $iriName = $iriName [2];
  
  if (! $iriName) {
    continue;
  }
  loadFromURL ( "tmp/laptopscope/notebook/$iriName", "http://www.laptopscope.ru" . $v [0]/*$fullNoteInfo [2] [0]*/ );
  
  $vlsArrs = array ();
  
  $vlsArrs ['name'] = $v [1];
  $vlsArrs ['description'] = $v [2];
  
  parseNoutInfoFile ( "tmp/laptopscope/notebook/$iriName", $hdrArrs, $vlsArrs );
  
  $allPropArray [] = $vlsArrs;
  
  echo "tmp/laptopscope/notebook/$iriName\n";
  
//break;


//fputcsv ( $csvile, $v );
}

$csvProps = @fopen ( "tmp/laptopscope_props.csv", "w" );
$locArr = array ();

foreach ( $hdrArrs as $k => $v ) {
  $locArr [] = $k;
}
fputcsv ( $csvProps, $locArr );

foreach ( $allPropArray as $k => $v ) {
  $locArr = array ();
  foreach ( $hdrArrs as $k1 => $v1 ) {
    $locArr [] = $v [$k1];
  }
  fputcsv ( $csvProps, $locArr );
}
/*

$iriName=$fullNoteInfo[2][0];
$iriName=preg_split('%/%ims',$iriName);
$iriName=$iriName[2];
loadFromURL("tmp/laptopscope/notebook/$iriName","http://www.laptopscope.ru".$fullNoteInfo[2][0]);
*/

fclose ( $csvProps );
fclose ( $csvile );

die ();
    


