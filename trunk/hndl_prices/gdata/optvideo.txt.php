<?php
// â íàëè÷èè:
//http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=28&grupp_kod=83652&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000
//http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=2&grupp_kod=83653&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000
// â ïóòè:
//http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=3&sort_otbor=4&count_fl=17&grupp_kod=83652&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000
function timeStampedEcho($outSt) {
  date_default_timezone_set ( 'Europe/Moscow' );
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

function loadStartPage($urlP, $fName) {
  // 
  try {
    // ÍÀËÈ×ÈÅ LCD-15: http://optvideo.com/new_design/new_kat/katalog.php?tab=3&grupp_kod=83652&otobr=1&count_fl=11&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB
    //$url = '';
    $url = $urlP;
    $parsedURL = null;
    $parsedURL = parse_url ( $url );
    //print_r($parsedURL=parse_url($url));
    $urlQuery = $parsedURL [query];
    //print_r($urlQuery); 
    $parsedQuery = Array ();
    parse_str ( $urlQuery, $parsedQuery );
    //print_r($parsedQuery);
    

    $fpout = @fopen ( $fName, "w" );
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

function getProdPages($ldd) {
  $rws = array ();
  //echo 
  preg_match_all ( '/a href="([^"]*?)"/ims', $ldd, $rws );
  //print_r($rws);
  //foreach ( $rws as $k => $v ) {
  //  echo $k . "\t====\t" . $v . "\n";
  //}
  return $rws [1];
}

function loadRazdel($razdelURL, $razdelFName) {
  // http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=17&grupp_kod=83652&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000
  // "tmp/optvideo/lcd.htm"
  timeStampedEcho ( "Handling <$razdelFName>\n" );
  $startURL = $razdelURL; //'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=17&grupp_kod=83652&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000';
  

  $parsedSURL = parse_url ( $startURL );
  //print_r($parsedURL=parse_url($url));
  $urlSQuery = $parsedSURL ['query'];
  //print_r($urlQuery); 
  $parsedSQuery = Array ();
  parse_str ( $urlSQuery, $parsedSQuery );
  
  loadStartPage ( $startURL, "$razdelFName.htm" );
  $loaded = loadFile ( "$razdelFName.htm" );
  $loaded = trimUnusedChars ( $loaded );
  
  $posF = strpos ( $loaded, "class=\"vnutr\"" );
  //echo "$posF\n";
  

  $loaded = substr ( $loaded, $posF );
  // 
  $posF = strpos ( $loaded, "Áðåíäû" );
  //echo "$posF\n";
  $loaded = substr ( $loaded, 0, $posF );
  //echo "$loaded\n";
  

  $prPages = getProdPages ( $loaded );
  $result = array ();
  foreach ( $prPages as $k => $v ) {
    $parsedURL = parse_url ( $v );
    $urlQuery = $parsedURL ['query'];
    //print_r ( $urlQuery );
    //print_r($parsedSURL);
    $parsedQuery = Array ();
    parse_str ( $urlQuery, $parsedQuery );
    //print_r($parsedQuery);
    foreach ( $parsedQuery as $k1 => $v1 ) {
      $parsedSQuery [$k1] = $v1;
    }
    // http://optvideo.com/new_design/new_kat/katalog.php?
    

    $url = 'http://optvideo.com/new_design/new_kat/katalog.php?' . http_build_query ( $parsedSQuery );
    //echo "$url\n";
    //$result[]="$razdelFName-$k.htm";
    loadStartPage ( $url, $result [] = "$razdelFName-$k.htm" );
  }
  return $result;
}

function parseOneFile($fName, $tp) {
  //$lcds [0]
  

  timeStampedEcho ( "Reading " . $fName."\n" );
  $loaded = loadFile ( $fName );
  $loaded = trimUnusedChars ( $loaded );
  
  $posF = strpos ( $loaded, '<hr color="#cccccc" size="1">' );
  $loaded = substr ( $loaded, $posF );
  
  $posF = strpos ( $loaded, '</table>' );
  $loaded = substr ( $loaded, 0, $posF );
  
  //echo $loaded;
  

  // class="price_header_text"
  //
  

  $readyRWS = array ();
  
  $codesRWS = array ();
  
  $pricesRWS = array ();
  
  $imagesRWS = array ();
  /*
  preg_match_all ( '/<tr[^>]*?class=\"product_row\"[^>]*?>(.*?)<\/tr>/ims', $ldd, $rws );
  */
  preg_match_all ( '/<a href="([^"]*?)"/ims', $loaded, $imagesRWS );
  //print_r ( $imagesRWS );
  

  foreach ( $imagesRWS [1] as $k => $v ) {
    $readyObj = null;
    $codeSt = $v;
    $posF = strrpos ( $codeSt, '.' );
    $codeSt = substr ( $codeSt, 0, $posF );
    
    $posF = strrpos ( $codeSt, '/' );
    $codeSt = substr ( $codeSt, $posF + 1 );
    $codesRWS [] = $codeSt;
    
    $readyObj ['code'] = $codeSt;
    
    // name="zena913145" value="
    $marker = '"zena' . $codeSt . '" value="';
    $nInfo = array ();
    $res = preg_match ( "/$marker([0123456789]*?)\./ims", $loaded, $nInfo );
    
    $pricesRWS [] = $nInfo [1];
    $readyObj ['price'] = $nInfo [1];
    $readyObj ['imageurl'] = 'http://www.optvideo.com/images/' . $codeSt . '.jpg';
    $readyObj ['optvideourl'] = 'http://optvideo.com/new_design/new_kat/view.php?kod=' . $codeSt . '';
    $readyObj ['type'] = $tp;
    
    $readyRWS [] = $readyObj;
  }
  //print_r ( $codesRWS );
  //print_r ( $pricesRWS );
  

  // <span class="txt"> </span>
  $imagesTXT = array ();
  /*
  preg_match_all ( '/<tr[^>]*?class=\"product_row\"[^>]*?>(.*?)<\/tr>/ims', $ldd, $rws );
  */
  preg_match_all ( '/<span class="txt">(.*?)<\/span>/ims', $loaded, $imagesTXT );
  //print_r ( $imagesTXT );
  foreach ( $imagesTXT [1] as $k => $v ) {
    $readyRWS [$k] ['desc'] = $v;
  }
  
  // href="http://optvideo.com/new_design/new_kat/view.php
  $namesRWS = array ();
  while ( 1 ) {
    // http://optvideo.com/new_design/new_kat/view.php
    $posF = strpos ( $loaded, 'http://optvideo.com/new_design/new_kat/view.php' );
    if (! $posF) {
      break;
    }
    $loaded = substr ( $loaded, $posF );
    $posF = strpos ( $loaded, '</a>' );
    // strrpos 
    $nm = substr ( $loaded, 0, $posF );
    $loaded = substr ( $loaded, $posF + 5 );
    
    $rPosF = strrpos ( $nm, '">' );
    $nm = substr ( $nm, $rPosF + 2 );
    $posF = strpos ( $nm, '</strong>' );
    $nm = substr ( $nm, 0, $posF );
    $nm = trim ( $nm );
    
    $namesRWS [] = $nm;
  }
  //preg_match_all ( '/href="http:\/\/optvideo\.com\/new_design\/new_kat\/view\.php([.]*?)<\/a>"/ims', $loaded, $namesRWS );
  //print_r ( $namesRWS );
  foreach ( $namesRWS as $k => $v ) {
    $readyRWS [$k] ['name'] = $v;
  }
  
  //print_r ( $readyRWS );
  return $readyRWS;
}

function parseFArray($arr, $tp) {
  $result = array ();
  foreach ( $arr as $k => $v ) {
    $oneParsed = parseOneFile ( $v, $tp );
    $result = array_merge ( $result, $oneParsed );
  }
  return $result;
}

function storeCVS($arr2Store, $fields, $hdrArr, $fName) {
  timeStampedEcho("Writing <$fName>\n");
  $csvProps = @fopen ( $fName, "w" );
  
  fputcsv ( $csvProps, $hdrArr );
  
  foreach ( $arr2Store as $k => $v ) {
    $locArr = array ();
    
    foreach ( $fields as $k1 => $v1 ) {
      $locArr [] = iconv ( "Windows-1251",'UTF-8',  $v [$v1] );
    }
    fputcsv ( $csvProps, $locArr );
  }
  fclose ( $csvProps );
}

$objFields = array ('code', 'name', 'desc', 'price', 'optvideourl', 'imageurl', 'type' );
$csvHeader = array ('optvideo_code', 'optvideo_name', 'optvideo_desc', 'optvideo_price', 'optvideo_url', 'optvideo_imageurl', 'optvideo_type' );

$allFNames = array ();
$all_vls = array ();
$all_tv_vls = array ();

$lcds = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=17&grupp_kod=83652&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/lcd" );
$allFNames [] = $lcds;
$lcds_vls = parseFArray ( $lcds, 'tv_lcd' );
$all_vls=array_merge ( $all_vls, $lcds_vls );
$all_tv_vls=array_merge ( $all_tv_vls, $lcds_vls );
storeCVS($lcds_vls,$objFields,$csvHeader,'./tmp/optvideo_tv_lcds.csv');
//print_r ( $lcds_vls );


$plazmas = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=&grupp_kod=xxx&kategory=108&grupp=&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=0&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/plazma" );
$allFNames [] = $plazmas;
$plazma_vls = parseFArray ( $plazmas, 'tv_plazma' );
$all_vls=array_merge ( $all_vls, $plazma_vls );
$all_tv_vls=array_merge ( $all_tv_vls, $plazma_vls );
storeCVS($plazma_vls,$objFields,$csvHeader,'./tmp/optvideo_tv_plazma.csv');

$led_tvs = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=&grupp_kod=xxx&kategory=119946&grupp=&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=0&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/led_tv" );
$allFNames [] = $led_tvs;
$led_vls = parseFArray ( $led_tvs, 'tv_led' );
$all_vls=array_merge ( $all_vls, $led_vls );
$all_tv_vls=array_merge ( $all_tv_vls, $led_vls );
storeCVS($led_vls,$objFields,$csvHeader,'./tmp/optvideo_tv_led.csv');
storeCVS($all_tv_vls,$objFields,$csvHeader,'./tmp/optvideo_tv_all.csv');

$dvds = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=&grupp_kod=xxx&kategory=3318&grupp=&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=0&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/dvd" );
$allFNames [] = $dvds;
$dvd_vls = parseFArray ( $dvds, 'dvd' );
$all_vls=array_merge ( $all_vls, $dvd_vls );
storeCVS($dvd_vls,$objFields,$csvHeader,'./tmp/optvideo_dvd.csv');

$port_dvds = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=&grupp_kod=xxx&kategory=72808&grupp=&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=0&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/port_dvd" );
$allFNames [] = $port_dvds;
$port_dvd_vls = parseFArray ( $port_dvds, 'port_dvd' );
$all_vls=array_merge ( $all_vls, $port_dvd_vls );
storeCVS($port_dvd_vls,$objFields,$csvHeader,'./tmp/optvideo_port_dvd.csv');

$m_med_pls = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&grupp_kod=120370&otobr=1&count_fl=27&kategory=120370&grupp=%CC%D3%CB%DC%D2%C8%CC%C5%C4%C8%C0%20%CF%CB%C5%C5%D0', "tmp/optvideo/m_med_pl" );
$allFNames [] = $m_med_pls;
$mmed_vls = parseFArray ( $m_med_pls, 'multimed' );
$all_vls=array_merge ( $all_vls, $mmed_vls );
storeCVS($mmed_vls,$objFields,$csvHeader,'./tmp/optvideo_multimed.csv');

$el_books = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=&grupp_kod=xxx&kategory=110274&grupp=&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=0&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/el_books" );
$allFNames [] = $el_books;
$el_books_vls = parseFArray ( $el_books, 'el_books' );
$all_vls=array_merge ( $all_vls, $el_books_vls );
storeCVS($el_books_vls,$objFields,$csvHeader,'./tmp/optvideo_elbooks.csv');

$monitors = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=&grupp_kod=xxx&kategory=91741&grupp=&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=0&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/monitors" );
$allFNames [] = $monitors;
$monit_vls = parseFArray ( $monitors, 'monitors' );
$all_vls=array_merge ( $all_vls, $monit_vls );
storeCVS($monit_vls,$objFields,$csvHeader,'./tmp/optvideo_monitors.csv');

$nouts = loadRazdel ( 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=&grupp_kod=xxx&kategory=91742&grupp=&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=0&selind_pstr=5&brend2=xxx&count_postr=1000', "tmp/optvideo/noutbooks" );
$allFNames [] = $nouts;
$nout_vls = parseFArray ( $nouts, 'nouts' );
$all_vls=array_merge ( $all_vls, $nout_vls );
storeCVS($nout_vls,$objFields,$csvHeader,'./tmp/optvideo_nouts.csv');

storeCVS($all_vls,$objFields,$csvHeader,'./tmp/optvideo_all.csv');

//print_r($allFNames);

//parseOneFile($lcds [0]);








