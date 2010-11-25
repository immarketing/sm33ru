<?php
//http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=28&grupp_kod=83652&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000
function trimUnusedChars($pt) {
  $pt = preg_replace ( "/\n/ims", '', $pt );
  $pt = preg_replace ( "/\r/ims", '', $pt );
  $pt = preg_replace ( "/\t/ims", '', $pt );
  $pt = preg_replace ( "/&nbsp;/ims", '', $pt );
  return $pt;
}

function loadStartPage() {
  // 
  try {
    $url = 'http://optvideo.com/new_design/new_kat/katalog.php?tab=3&selind_sort=2&sort_otbor=3&count_fl=28&grupp_kod=83652&kategory=57&grupp=LCD%20-%D2%C5%CB%C5%C2%C8%C7%CE%D0%DB&brend_from_head=xxx&brend_osn=xxx&brend2fromaj=xxx&grupp2fromaj=xxx&otobr=1&selind_pstr=5&brend2=xxx&count_postr=1000';
    $fpout = @fopen ( "../optvideo/katalog.htm", "w" );
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

loadStartPage();
