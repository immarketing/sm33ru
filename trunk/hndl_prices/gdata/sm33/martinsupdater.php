<?php
class MartinsRuPriceUpdater {
  public function __construct($priceFile, $email, $password) {
    Zend_Loader::loadClass ( 'sm33_SM33GData' );
    Zend_Loader::loadClass ( 'sm33_SM33GSpreadsheet' );
    Zend_Loader::loadClass ( 'Zend_Gdata_Docs' );
    
    $this->email = $email;
    $this->password = $password;
    
    $this->gConnector = new sm33_SM33GData ( $email, $password );
    $this->mrtnsPrice = $this->gConnector->getSpreadsheetByName ( "martins.ru" );
    $rr = $this->mrtnsPrice->getRowAndColumnCount ( "Sheet 1" );
    $this->priceFile = $priceFile;
    $this->highestXLSRow = 0;
  }
  
  public function try2upload($fName, $realName) {
    $service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
    $client = Zend_Gdata_ClientLogin::getHttpClient ( $this->email, $this->password, $service );
    //$client=$this->gConnector->getGDClient();
    $docs = new Zend_Gdata_Docs ( $client );
    $newDocumentEntry = $docs->uploadFile($fName, $realName,
      null, Zend_Gdata_Docs::DOCUMENTS_LIST_FEED_URI);
  }
  
  public function updateColumn($pName, $cl, $vl) {
    $rr = $this->mrtnsPrice->getRowAndColumnCount ( "Sheet 1" );
    $rCount = 0 + $rr [ROWS]->getText ();
    for($i = 2; $i <= $rCount; $i ++) {
      $this->mrtnsPrice->updateCell ( $i, $cl, $vl, $pName );
    }
  }
  
  public function try2add() {
    for($row = 15; $row <= $this->highestXLSRow; $row ++) {
      // id $this->priceArray [$row] [1]
      $code = 0 + $this->priceArray [$row] [1];
      $mdl = $this->priceArray [$row] [2];
      $prc = 0 + $this->priceArray [$row] [4];
      if ($code > 0) {
        //timeStampedEcho ( '$code==' . "$code" . "|\t" . '$mdl==' . "$mdl" . "|\t" . '$prc==' . "$prc\n" );
        if ($this->dataGData2Add [$code]) {
          /*
          $vls = array ();
          $vls ['code'] = $code;
          $vls ['model'] = $mdl;
          $vls ['description'] = '';
          $vls ['price'] = $prc;
          $vls ['ispublished'] = 2;
          $vls ['updatetime'] = date ( 'Ymd H:i:s' );
          //$entr = $this->gConnector->getGDClient ()->updateRow ( $this->dataGData [$code], $vls );
          try {
            $entr = $this->gConnector->getGDClient ()->insertRow ( $vls, $this->mrtnsPrice->getSpreadsheetID (), $this->mrtnsPrice->getWorksheetID ( 'Sheet 1' ) );
          } catch ( Exception $e ) {
            continue;
          }
          
          //$this->gConnector->getGDClient()->
          if ($entr instanceof Zend_Gdata_Spreadsheets_ListEntry) {
            $this->dataGData2Add [$code] = null;
            //echo "Success!\n";
            $response = $entr->save ();
          }
          */
          
          $this->dataGData [$code] [CODE] = $code;
          $this->dataGData [$code] [MODEL] = $mdl;
          //$this->dataGData [$code] [DESCRIPTION] = $this->priceArray [$row][3];
          $this->dataGData [$code] [PRICE] = $prc;
          $this->dataGData [$code] [ISPUBLISHED] = '2';
          $this->dataGData [$code] [UPDATETIME] = date ( 'Ymd H:i:s' );
          
          $this->dataGData2Add [$code] = null;
        
        }
      } else {
        continue;
      }
    }
  
  }
  public function try2update() {
    for($row = 15; $row <= $this->highestXLSRow; $row ++) {
      // id $this->priceArray [$row] [1]
      $code = 0 + $this->priceArray [$row] [1];
      $mdl = $this->priceArray [$row] [2];
      $prc = 0 + $this->priceArray [$row] [4];
      if ($code > 0) {
        //timeStampedEcho ( '$code==' . "$code" . "|\t" . '$mdl==' . "$mdl" . "|\t" . '$prc==' . "$prc\n" );
        if ($this->dataGData [$code]) {
          //$cst = $this->dataGData [$code]->getCustom ();
          $curDat = 0 + $this->dataGData [$code] [CODE]; //0 + $cst [0]->getText ();
          $pppppp = 0 + $this->dataGData [$code] [PRICE]; //$cst [3]->getText ();
          

          if ($prc == $pppppp) {
            $vls = array ();
            $this->dataGData [$code] [ISPUBLISHED] = 1; //$vls ['ispublished'] = 1;
            $this->dataGData [$code] [UPDATETIME] = date ( 'Ymd H:i:s' ); //$vls ['updatetime'] = date ( 'Ymd H:i:s' );
            //$this->dataGData [$code] [DESCRIPTION] = $this->priceArray [$row][3];
            $this->dataGData2Add [$code] = null;
            continue;
            /*
            try {
              $entr = $this->gConnector->getGDClient ()->updateRow ( $this->dataGData [$code], $vls );
            } catch ( Exception $e ) {
              continue;
            }
            if ($entr instanceof Zend_Gdata_Spreadsheets_ListEntry) {
              $this->dataGData2Add [$code] = null;
              continue;
            }
            */
          }
          /*
          $vls = array ();
          $vls ['code'] = $code;
          $vls ['model'] = $mdl;
          $vls ['description'] = '';
          $vls ['price'] = $prc;
          $vls ['ispublished'] = 1;
          $vls ['updatetime'] = date ( 'Ymd H:i:s' );
          */
          
          $this->dataGData [$code] [CODE] = $code;
          $this->dataGData [$code] [MODEL] = $mdl;
          //$this->dataGData [$code] [DESCRIPTION] = $this->priceArray [$row][3];
          $this->dataGData [$code] [PRICE] = $prc;
          $this->dataGData [$code] [ISPUBLISHED] = '11';
          //$this->dataGData [$code] [ISPUBLISHED] = 0;
          $this->dataGData [$code] [UPDATETIME] = date ( 'Ymd H:i:s' );
          
          $this->dataGData2Add [$code] = null;
          
        /*
          try {
            $entr = $this->gConnector->getGDClient ()->updateRow ( $this->dataGData [$code], $vls );
          } catch ( Exception $e ) {
            continue;
          }
          //$this->gConnector->getGDClient()->
          if ($entr instanceof Zend_Gdata_Spreadsheets_ListEntry) {
            $this->dataGData2Add [$code] = null;
          }
          */
        }
      } else {
        continue;
      }
    }
  }
  
  public function saveCSVFile($fName) {
    $fp = fopen ( $fName, 'w' );
    if ($fp) {
      $vls = array ('code', 'model', 'description', 'price', 'ispublished', 'updatetime' );
      fputcsv ( $fp, $vls );
      
      foreach ( $this->dataGData as $cd => $ar ) {
        //fputcsv ( $fp, $fields );
        $vls = array ($this->dataGData [$cd] [CODE], $this->dataGData [$cd] [MODEL], $this->dataGData [$cd] [DESCRIPTION], $this->dataGData [$cd] [PRICE], $this->dataGData [$cd] [ISPUBLISHED], $this->dataGData [$cd] [UPDATETIME] );
        fputcsv ( $fp, $vls );
      
      }
      fclose ( $fp );
    }
  
  }
  
  public function loadGDocInMem() {
    $query = new Zend_Gdata_Spreadsheets_ListQuery ();
    $query->setSpreadsheetKey ( $this->mrtnsPrice->getSpreadsheetID () );
    $query->setWorksheetId ( $this->mrtnsPrice->getWorksheetID ( 'Sheet 1' ) );
    $this->dataFeed = $this->gConnector->getGDClient ()->getListFeed ( $query );
    
    foreach ( $this->dataFeed->entries as $entry ) {
      $cst = $entry->getCustom ();
      $curDat = 0 + $cst [0]->getText ();
      if ($curDat > 0) {
        $this->dataGData [$curDat] [ENTRY] = $entry;
        $this->dataGData [$curDat] [CODE] = $cst [0]->getText ();
        $this->dataGData [$curDat] [MODEL] = $cst [1]->getText ();
        $this->dataGData [$curDat] [DESCRIPTION] = $cst [2]->getText ();
        $this->dataGData [$curDat] [PRICE] = $cst [3]->getText ();
        $this->dataGData [$curDat] [ISPUBLISHED] = $cst [4]->getText ();
        $this->dataGData [$curDat] [ISPUBLISHED] = 0;
        $this->dataGData [$curDat] [UPDATETIME] = $cst [5]->getText ();
        //$this->dataGData2Add [$curDat] = $entry;
      }
    }
  }
  
  public function loadXLSInMem() {
    require_once './Classes/PHPExcel/IOFactory.php';
    $objPHPExcel = PHPExcel_IOFactory::load ( $this->priceFile );
    
    $objWorksheet = $objPHPExcel->getSheet ( 0 );
    $highestRow = $objWorksheet->getHighestRow (); // e.g. 10
    

    $this->highestXLSRow = $highestRow;
    
    for($row = 15; $row <= $highestRow; ++ $row) {
      $cc = 0 + $objWorksheet->getCellByColumnAndRow ( 1, $row )->getValue ();
      $this->dataGData2Add [$cc] = $cc;
      $this->priceArray [$row] [0] = $objWorksheet->getCellByColumnAndRow ( 0, $row )->getValue ();
      $this->priceArray [$row] [1] = $objWorksheet->getCellByColumnAndRow ( 1, $row )->getValue ();
      $this->priceArray [$row] [2] = $objWorksheet->getCellByColumnAndRow ( 2, $row )->getValue ();
      $this->priceArray [$row] [3] = $objWorksheet->getCellByColumnAndRow ( 3, $row )->getValue ();
      $this->priceArray [$row] [4] = $objWorksheet->getCellByColumnAndRow ( 4, $row )->getValue ();
    }
  }
}

function timeStampedEcho($outSt) {
  $t = date ( 'Ymd H:i:s' );
  echo "[$t]\t$outSt";
}

function updateMartinsFromFile($priceFile, $email, $password) {
  timeStampedEcho ( "martins.ru updater started\n" );
  $mtnupdtr = new MartinsRuPriceUpdater ( $priceFile, $email, $password );
  timeStampedEcho ( "martins.ru updater created\n" );
  
  //$mtnupdtr->updateColumn ( "Sheet 1", 5, 0 );
  //$mtnupdtr->updateColumn ( "Sheet 1", 6, date ( 'Ymd H:i:s' ) );
  //timeStampedEcho ( "martins.ru cols updated\n" );
  $mtnupdtr->loadXLSInMem ();
  timeStampedEcho ( "XLS loaded\n" );
  $mtnupdtr->loadGDocInMem ();
  timeStampedEcho ( "GDOC loaded\n" );
  $mtnupdtr->try2update ();
  timeStampedEcho ( "Updated\n" );
  
  $mtnupdtr->try2add ();
  timeStampedEcho ( "Added\n" );
  
  $mtnupdtr->saveCSVFile ( 'tmp/martins.ru.csv' );
  timeStampedEcho ( "temporary CSV writed\n" );
  
  //$mtnupdtr->try2upload ( 'tmp/martins.ru.csv','martins.ru' );
  //timeStampedEcho ( "temporary CSV uploaded\n" );
  die ();
  //die ();
  require_once './Classes/PHPExcel/IOFactory.php';
  
  //$path = 'sm33';
  //set_include_path ( get_include_path () . PATH_SEPARATOR . $path );
  

  Zend_Loader::loadClass ( 'sm33_SM33GData' );
  Zend_Loader::loadClass ( 'sm33_SM33GSpreadsheet' );
  
  $gConnector = new sm33_SM33GData ( $email, $password );
  
  $mrtnsPrice = $gConnector->getSpreadsheetByName ( "martins.ru" );
  
  echo $mrtnsPrice->getWorksheetID ( "Sheet 1" ) . "\n";
  $rr = $mrtnsPrice->getRowAndColumnCount ( "Sheet 1" );
  echo $rr [ROWS] . "\n";
  echo $rr [COLS] . "\n";
  
  $rCount = 0 + $rr [ROWS]->getText ();
  echo '$rCount==' . "$rCount\n";
  
  //for($i = 2; $i <= $rr [ROWS]; $i ++) {
  for($i = 2; $i <= $rCount; $i ++) {
    $mrtnsPrice->updateCell ( $i, 5, 0, "Sheet 1" );
  }
  
  die ();
  
  $objPHPExcel = PHPExcel_IOFactory::load ( $priceFile );
  
  $objWorksheet = $objPHPExcel->getSheet ( 0 );
  $highestRow = $objWorksheet->getHighestRow (); // e.g. 10
  

  for($row = 2; $row <= $highestRow; ++ $row) {
    $v1 = $objWorksheet->getCellByColumnAndRow ( 1, $row )->getValue ();
    $v2 = $objWorksheet->getCellByColumnAndRow ( 4, $row )->getValue ();
    
    echo "$v1\t|$v2\n";
  }

}
?>