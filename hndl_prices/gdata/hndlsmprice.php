<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Demos
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

include ('class.img2thumb.php');
/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

require_once 'sm33/myandexexp.php';

/**
 * @see Zend_Gdata
 */
Zend_Loader::loadClass ( 'Zend_Gdata' );

/**
 * @see Zend_Gdata_ClientLogin
 */
Zend_Loader::loadClass ( 'Zend_Gdata_ClientLogin' );

/**
 * @see Zend_Gdata_Spreadsheets
 */
Zend_Loader::loadClass ( 'Zend_Gdata_Spreadsheets' );

/**
 * @see Zend_Gdata_App_AuthException
 */
Zend_Loader::loadClass ( 'Zend_Gdata_App_AuthException' );

/**
 * @see Zend_Http_Client
 */
Zend_Loader::loadClass ( 'Zend_Http_Client' );

define ( "SM_CATEGORY_LEVEL_1", "sm.категория.1", true );
define ( "SM_CATEGORY_LEVEL_2", "sm.категория.2", true );
define ( "SM_CATEGORY_LEVEL_3", "sm.категория.3", true );
define ( "SM_IS_PUBLISHED", "sm.публиковать", true );
define ( "SM_CATEGORY_REFERENCE", "sm.category.reference", true );

define ( "SM_INTERNAL_IDENTIFICATOR", "sm.internal.identificator", true );
define ( "SM_INTERNAL_RECALC_ID", "sm.internal.recacl.id", true );
define ( "SM_INTERNAL_PRODUCTDESCRIPTION", "sm.product.description", true );
define ( "SM_INTERNAL_PRODUCTSQLINSERTS", "sm.product.SQLinserts", true );

define ( "SM_INTERNAL_FULLPICTURL", "sm.product.fullpicturl", true );
define ( "SM_INTERNAL_ID", "sm.sm33.id", true );

define ( "SM_INTERNAL_PRICE", "sm.цена", true );

define ( "ADV_GGL_KEYWORDS", "advert.google.key_words", true );
define ( "ADV_GGL_WRITE", "advert.google.write_adv", true );
define ( "ADV_YNDX_WRITE", "advert.market.yandex.write_adv", true );

define ( "SM_ADV_NAME", "sm.adv.name", true );

define ( "SM_GEO_COMP_KOVROV", "KOVROV", true );
define ( "SM_GEO_COMP_VLADIMIR", "VLADIMIR", true );
/*
define ( "SM_CATEGORY_REFERENCE", "sm.category.reference", true );
define ( "SM_CATEGORY_REFERENCE", "sm.category.reference", true );
define ( "SM_CATEGORY_REFERENCE", "sm.category.reference", true );
*/

class ImageLoader {
  public function __construct($prodID, $infoURL, $dirName) {
    $this->prodID = $prodID;
    $this->infoURL = $infoURL;
    $this->dirName = $dirName;
  
  }
  
  private function readInfoFromURL($prodID, $infoURL, $fileName, $fn2, $fnPNG) {
    $fpout = @fopen ( $fileName, "w" );
    if ($fpout) {
      $ch = curl_init ();
      curl_setopt ( $ch, CURLOPT_URL, $infoURL );
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt ( $ch, CURLOPT_PROXY, 'prx01-kaz.kaz.transneft.ru:8080' );
      //curl_setopt ( $ch, CURLOPT_PROXYAUTH, CURLAUTH_NTLM );
      //curl_setopt ( $ch, CURLOPT_PROXYUSERPWD, 'galaxy7:123qwe!!' );
      //curl_setopt($ch, CURLOPT_VERBOSE, true);
      

      curl_setopt ( $ch, CURLOPT_FILE, $fpout );
      curl_setopt ( $ch, CURLOPT_HEADER, 0 );
      
      curl_exec ( $ch );
      fclose ( $fpout );
    }
    
    $resI = imagecreatefromjpeg ( $fileName );
    if (! $resI) {
      $resI = imagecreatefrompng ( $fileName );
      if ($resI) {
        //imagejpeg ( $resI, $fn2 );
        imagedestroy ( $resI );
        copy ( $fileName, $fnPNG );
        return "PNG";
      } else {
        return false;
      }
    } else {
      copy ( $fileName, $fn2 );
      imagedestroy ( $resI );
      return "JPG";
    }
    
    return true;
    
  /*
    */
  }
  public function tryLoadImage() {
    //global $options;
    $fileName = './images/' . $this->prodID . '.jpg';
    
    $fN2 = './product/' . $this->prodID . '.jpg';
    $fNp = './product/' . $this->prodID . '.png';
    
    $fpout = @fopen ( $fN2, "r" );
    if ($fpout) {
      @fclose ( $fpout );
      $neu = new Img2Thumb ( $fN2, 100, 160, './product/resized/' . $this->prodID . '_100x160.jpg' );
      return "JPG";
    }
    
    $fpout = @fopen ( $fNp, "r" );
    if ($fpout) {
      @fclose ( $fpout );
      $neu = new Img2Thumb ( $fNp, 100, 160, './product/resized/' . $this->prodID . '_100x160.png' );
      return "PNG";
    }
    
    $fpout = @fopen ( $fileName, "r" );
    $res = false;
    if (! $fpout) {
      $res = $this->readInfoFromURL ( $this->prodID, $this->infoURL, $fileName, $fN2, $fNp );
      $fpout = @fopen ( $fileName, "r" );
      $fpout = @fopen ( $fN2, "r" );
      if ($fpout) {
        @fclose ( $fpout );
        $neu = new Img2Thumb ( $fN2, 100, 160, './product/resized/' . $this->prodID . '_100x160.jpg' );
        return "JPG";
      }
      
      $fpout = @fopen ( $fNp, "r" );
      if ($fpout) {
        @fclose ( $fpout );
        $neu = new Img2Thumb ( $fNp, 100, 160, './product/resized/' . $this->prodID . '_100x160.png' );
        return "PNG";
      }
    }
    if ($fpout) {
      @fclose ( $fpout );
    }
    
    return $res;
  }

}

class GetAvailableDocuments {
  public function __construct($email, $password) {
    //
    try {
      $client = Zend_Gdata_ClientLogin::getHttpClient ( $email, $password, Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME );
    } catch ( Zend_Gdata_App_AuthException $ae ) {
      exit ( "Error: " . $ae->getMessage () . "\nCredentials provided were email: [$email] and password [$password].\n" );
    }
    
    $this->gdClient = new Zend_Gdata_Spreadsheets ( $client );
  
  }
  
  public function getCategories() {
    $res = null;
    $feed = $this->gdClient->getSpreadsheetFeed ();
    
    //$i = 0;
    foreach ( $feed->entries as $entry ) {
      //print $i . ' | ' . $entry->id->text . ' | ' . $entry->title->text . "\n";
      if ("categories" === $entry->title->text) {
        $res [URL] = $entry->id->text;
        $res [NAME] = $entry->title->text;
        return $res;
      
      }
    }
    
    return $res;
  }
  
  public function getDocsArray() {
    $prodNames = array ("СВЧ печи" => "свч", "Утюги" => "утюг", "Обогреватели" => "", "СВЧ печи" => "свч", "СВЧ печи" => "свч", "СВЧ печи" => "свч", "СВЧ печи" => "свч", "СВЧ печи" => "свч", "СВЧ печи" => "свч", "СВЧ печи" => "свч", "СВЧ печи" => "свч", "СВЧ печи" => "свч" );
    $prodKeyWords = array ("СВЧ печи" => "свч, микроволновка, микроволновки", "Утюги" => "утюг, утюги", "Обогреватели" => "обогреватель, масляный обогреватель", "Видеокамеры" => "видеокамера, видеокамеры", "Хлебопечи" => "Хлебопечи, хлебопечь", "Кофеварки" => "Кофеварки, Кофеварка", "Фотоаппараты" => "Фотоаппарат,Фотоаппараты", "Домашние кинотеатры" => "Домашние кинотеатры, Домашний кинотеатр, DVD, blu-ray", "Музыкальные центры" => "Музыкальные центры, Музыкальный центр", "Телевизоры LED_ LCD" => "телевизор, телевизоры, tv, lcd, плазма", "DVD" => "DVD, blu-ray", "пылесосы" => "пылесосы, пылесос", "Фотообъективы" => "Фотообъективы,Фотообъектив", "Блендеры" => "Блендеры, Блендер", "Бритвы" => "Бритвы, Бритва", "Фены" => "Фены, Фен", "Мясорубки" => "Мясорубки, Мясорубка", "Ноутбуки laptopscope" => "Ноутбуки, ноутбук, нетбук, нетбуки", "pleer.ru_телефоны" => "Телефон, телефоны, коммуникаторы, коммуникатор, смартфон, смартфоны" );
    $prodWriteAdv = array ("СВЧ печи" => "свч, микроволновка, микроволновки", "Хлебопечи" => "Хлебопечи, хлебопечь", "Телевизоры LED_ LCD" => "телевизор, телевизоры, tv, lcd, плазма", "Ноутбуки laptopscope" => "Ноутбуки, ноутбук, нетбук, нетбуки", "pleer.ru_телефоны" => "Телефон, телефоны, коммуникаторы, коммуникатор, смартфон, смартфоны" );
    $prodWriteAdvYndx = array ("Видеокамеры" => 1, "Хлебопечи" => 1, "Кофеварки" => 1, "Фотоаппараты" => 1, "Телевизоры LED_ LCD" => 1, "Ноутбуки laptopscope" => 0, "pleer.ru_эл_книги" => 1, "pleer.ru_фотоаппараты" => 1, "pleer.ru_gps_навигаторы" => 1, "optvideo.com_магнитолы" => 1, "optvideo.com_мониторы" => 1, "optvideo.com_телевизоры" => 1, "optvideo.com_автоакустика" => 1, "optvideo.com_сабвуферы" => 1, "optvideo.com_автоусилители" => 1, "optvideo.com_швейные_машины" => 1, "optvideo.com_конвекторы" => 1, "zzzzz" );
    $res = array ();
    
    $feed = $this->gdClient->getSpreadsheetFeed ();
    
    //$i = 0;
    foreach ( $feed->entries as $entry ) {
      //print $i . ' | ' . $entry->id->text . ' | ' . $entry->title->text . "\n";
      if (
      /*
      */
      "СВЧ печи" === $entry->title->text || //

      "Утюги" === $entry->title->text || //


      "Обогреватели" === $entry->title->text || //


      "Видеокамеры" === $entry->title->text || //


      "Хлебопечи" === $entry->title->text || //


      "Кофеварки" === $entry->title->text || //


      "Фотоаппараты" === $entry->title->text || 

      "Домашние кинотеатры" === $entry->title->text || 

      "Музыкальные центры" === $entry->title->text ||

      "Телевизоры LED_ LCD" === $entry->title->text || 

      "DVD" === $entry->title->text || 

      "пылесосы" === $entry->title->text || 

      "Фотообъективы" === $entry->title->text || 

      "Блендеры" === $entry->title->text || 

      "Бритвы" === $entry->title->text || 

      "Фены" === $entry->title->text || 

      "Мясорубки" === $entry->title->text || 

      "Ноутбуки laptopscope" === $entry->title->text || 

      "pleer.ru_телефоны" === $entry->title->text || 

      "pleer.ru_медиаплееры" === $entry->title->text || 

      "pleer.ru_эл_книги" === $entry->title->text || 

      "pleer.ru_фотовспышки_minolta_sony" === $entry->title->text || 

      "pleer.ru_фотоаппараты" === $entry->title->text || 

      "pleer.ru_gps_навигаторы" === $entry->title->text || 

      "optvideo.com_магнитолы" === $entry->title->text || 

      "optvideo.com_мониторы" === $entry->title->text || 

      "optvideo.com_телевизоры" === $entry->title->text || 

      "optvideo.com_автоакустика" === $entry->title->text || 

      "optvideo.com_сабвуферы" === $entry->title->text || 

      "optvideo.com_автоусилители" === $entry->title->text || 

      "optvideo.com_швейные_машины" === $entry->title->text || 

      "optvideo.com_конвекторы" === $entry->title->text || 
      /* 
      */

      false) {
        //
        $res [$entry->title->text] [URL] = $entry->id->text;
        $res [$entry->title->text] [NAME] = $entry->title->text;
        $res [$entry->title->text] [ADV_GGL_KEYWORDS] = $prodKeyWords [$entry->title->text];
        $res [$entry->title->text] [ADV_GGL_WRITE] = $prodWriteAdv [$entry->title->text];
        
        $res [$entry->title->text] [ADV_YNDX_WRITE] = $prodWriteAdvYndx [$entry->title->text];
        
      }
      //$i ++;
    }
    
    return $res;
  }

}
/**
 * SimpleCRUD
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Demos
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

class SimpleCRUD {
  /**
   * Constructor
   *
   * @param  string $email
   * @param  string $password
   * @return void
   */
  public function __construct($email, $password) {
    try {
      $client = Zend_Gdata_ClientLogin::getHttpClient ( $email, $password, Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME );
    } catch ( Zend_Gdata_App_AuthException $ae ) {
      exit ( "Error: " . $ae->getMessage () . "\nCredentials provided were email: [$email] and password [$password].\n" );
    }
    
    $this->gdClient = new Zend_Gdata_Spreadsheets ( $client );
    $this->currKey = '';
    $this->currWkshtId = '';
    $this->currWkshtIdOpt = '';
    $this->currWkshtIdDat = '';
    $this->listFeed = '';
    $this->rowCount = 0;
    $this->columnCount = 0;
    
    $this->email = $email;
    $this->password = $password;
    
    $this->workDocs = array ();
    
    $this->categories = null;
    $this->lastUsedCatID = 1;
  }
  
  private function listSpreadsheets() {
    $feed = $this->gdClient->getSpreadsheetFeed ();
    print "== Available Spreadsheets ==\n";
    $this->printFeed ( $feed );
    die ();
  }
  
  public function promptForCategoriesSpreadsheet() {
    $v = &$this->categoriesDoc;
    
    $curDoc = $v [URL];
    $sheet = $this->gdClient->getSpreadsheetEntry ( $curDoc );
    $currKey = explode ( '/', $sheet->id->text );
    $v [CURRKEY] = $currKey [5];
    
    $query = new Zend_Gdata_Spreadsheets_DocumentQuery ();
    $query->setSpreadsheetKey ( $v [CURRKEY] );
    $feed = $this->gdClient->getWorksheetFeed ( $query );
    foreach ( $feed->entries as $entry ) {
      if (strtolower ( $entry->title->text ) === "sheet 1") {
        $currWkshtIdOpt = explode ( '/', $entry->id->text );
        $v [CURWKSHT] = $currWkshtIdOpt [8];
      }
    }
  }
  
  /**
   * promptForSpreadsheet
   *
   * @return void
   */
  public function promptForSpreadsheet() {
    // https://spreadsheets.google.com/feeds/spreadsheets/tZ-8YsN5J3RGLvy-RxALj1A
    // https://spreadsheets.google.com/feeds/spreadsheets/tVyuiTsRNkpqTuXPE4OfrEw - видеокамеры
    

    if (true) {
      
      foreach ( $this->workDocs as $k => $v ) {
        //print ' | ' . $k . ' | ' . $v . "\n";
        $curDoc = $v [URL];
        //'https://spreadsheets.google.com/feeds/spreadsheets/tVyuiTsRNkpqTuXPE4OfrEw';
        $sheet = $this->gdClient->getSpreadsheetEntry ( $curDoc );
        $currKey = explode ( '/', $sheet->id->text );
        //$this->currKey = $currKey [5];
        $this->workDocs [$k] [CURRKEY] = $currKey [5];
        $this->currKey = $currKey [5];
        //$res[$entry->title->text] = $entry->id->text; 
      //$i ++;
      }
      
    /*
      $curDoc = 'https://spreadsheets.google.com/feeds/spreadsheets/tVyuiTsRNkpqTuXPE4OfrEw';
      $sheet = $this->gdClient->getSpreadsheetEntry ( $curDoc );
      $currKey = explode ( '/', $sheet->id->text );
      $this->currKey = $currKey [5];
      */
    } else {
      /*
      $input = getInput ( "\nSelection" );
      echo "\n[" . $feed->entries [$input]->id->text . "]";
      $currKey = explode ( '/', $feed->entries [$input]->id->text );
      $this->currKey = $currKey [5];
      */
    }
  }
  
  /**
   * promptForWorksheet
   *
   * @return void
   */
  public function promptForWorksheet($theKey) {
    if (true) {
      $query = new Zend_Gdata_Spreadsheets_DocumentQuery ();
      //$query->setSpreadsheetKey ( $this->currKey );
      $query->setSpreadsheetKey ( $this->workDocs [$theKey] [CURRKEY] );
      
      for($i = 1; $i <= 10; $i ++) {
        try {
          $feed = $this->gdClient->getWorksheetFeed ( $query );
          break;
        } catch ( Exception $e ) {
          if ($i == 10) {
            throw $e;
          }
          continue;
        }
      }
      
      foreach ( $feed->entries as $entry ) {
        if (strtolower ( $entry->title->text ) === "options") {
          //echo "\nopt\n";
          //echo "\n[" . $entry->id->text . "]\n";
          $currWkshtIdOpt = explode ( '/', $entry->id->text );
          //$this->currWkshtIdOpt = $currWkshtIdOpt [8];
          $this->workDocs [$theKey] [CURWKSHTIDOPT] = $currWkshtIdOpt [8];
        }
        if (strtolower ( $entry->title->text ) === "data") {
          //echo "\ndata\n";
          //echo "\n[" . $entry->id->text . "]\n";
          $currWkshtIdDat = explode ( '/', $entry->id->text );
          //$this->currWkshtIdDat = $currWkshtIdDat [8];
          $this->workDocs [$theKey] [CURWKSHTIDDAT] = $currWkshtIdDat [8];
        }
      
      }
      //
      $currWkshtId = explode ( '/', $feed->entries [0]->id->text );
      $this->currWkshtId = $currWkshtId [8];
    
    } else {
      
      $query = new Zend_Gdata_Spreadsheets_DocumentQuery ();
      $query->setSpreadsheetKey ( $this->currKey );
      $feed = $this->gdClient->getWorksheetFeed ( $query );
      print "== Available Worksheets ==\n";
      $this->printFeed ( $feed );
      $input = getInput ( "\nSelection" );
      echo "\n[" . $feed->entries [$input]->id->text . "]\n";
      $currWkshtId = explode ( '/', $feed->entries [$input]->id->text );
      $this->currWkshtId = $currWkshtId [8];
    
    }
  }
  
  /**
   * promptForCellsAction
   *
   * @return void
   */
  public function promptForCellsAction() {
    echo "Pick a command:\n";
    echo "\ndump -- dump cell information\nupdate {row} {col} {input_value} -- update cell information\n";
    $input = getInput ( 'Command' );
    $command = explode ( ' ', $input );
    if ($command [0] == 'dump') {
      $this->cellsGetAction ();
    } else if (($command [0] == 'update') && (count ( $command ) > 2)) {
      $this->getRowAndColumnCount ();
      if (count ( $command ) == 4) {
        $this->cellsUpdateAction ( $command [1], $command [2], $command [3] );
      } elseif (count ( $command ) > 4) {
        $newValue = implode ( ' ', array_slice ( $command, 3 ) );
        $this->cellsUpdateAction ( $command [1], $command [2], $newValue );
      } else {
        $this->cellsUpdateAction ( $command [1], $command [2], '' );
      }
    } else {
      $this->invalidCommandError ( $input );
    }
  }
  
  /**
   * promptToResize
   *
   * @param  integer $newRowCount
   * @param  integer $newColumnCount
   * @return boolean
   */
  public function promptToResize($newRowCount, $newColumnCount) {
    $input = getInput ( 'Would you like to resize the worksheet? [yes | no]' );
    if ($input == 'yes') {
      return $this->resizeWorksheet ( $newRowCount, $newColumnCount );
    } else {
      return false;
    }
  }
  
  /**
   * resizeWorksheet
   *
   * @param  integer $newRowCount
   * @param  integer $newColumnCount
   * @return boolean
   */
  public function resizeWorksheet($newRowCount, $newColumnCount) {
    $query = new Zend_Gdata_Spreadsheets_DocumentQuery ();
    $query->setSpreadsheetKey ( $this->currKey );
    $query->setWorksheetId ( $this->currWkshtId );
    $currentWorksheet = $this->gdClient->getWorksheetEntry ( $query );
    $currentWorksheet = $currentWorksheet->setRowCount ( new Zend_Gdata_Spreadsheets_Extension_RowCount ( $newRowCount ) );
    $currentWorksheet = $currentWorksheet->setColumnCount ( new Zend_Gdata_Spreadsheets_Extension_ColCount ( $newColumnCount ) );
    $currentWorksheet->save ();
    $this->getRowAndColumnCount ();
    print "Worksheet has been resized to $this->rowCount rows and $this->columnCount columns.\n";
    return true;
  }
  
  /**
   * promptForListAction
   *
   * @return void
   */
  public function promptForListAction() {
    echo "\n== Options ==\n" . "dump -- dump row information\n" . "insert {row_data} -- insert data in the next available cell in a given column (example: insert column_header=content)\n" . "update {row_index} {row_data} -- update data in the row provided (example: update row-number column-header=newdata\n" . "delete {row_index} -- delete a row\n\n";
    
    $input = getInput ( 'Command' );
    $command = explode ( ' ', $input );
    if ($command [0] == 'dump') {
      $this->listGetAction ();
    } else if ($command [0] == 'insert') {
      $this->listInsertAction ( array_slice ( $command, 1 ) );
    } else if ($command [0] == 'update') {
      $this->listUpdateAction ( $command [1], array_slice ( $command, 2 ) );
    } else if ($command [0] == 'delete') {
      $this->listDeleteAction ( $command [1] );
    } else {
      $this->invalidCommandError ( $input );
    }
  }
  
  /**
   * cellsGetAction
   *
   * @return void
   */
  public function cellsGetAction() {
    $query = new Zend_Gdata_Spreadsheets_CellQuery ();
    $query->setSpreadsheetKey ( $this->currKey );
    $query->setWorksheetId ( $this->currWkshtId );
    $feed = $this->gdClient->getCellFeed ( $query );
    $this->printFeed ( $feed );
  }
  
  /**
   * cellsUpdateAction
   *
   * @param  integer $row
   * @param  integer $col
   * @param  string  $inputValue
   * @return void
   */
  public function cellsUpdateAction($row, $col, $inputValue) {
    if (($row > $this->rowCount) || ($col > $this->columnCount)) {
      print "Current worksheet only has $this->rowCount rows and $this->columnCount columns.\n";
      if (! $this->promptToResize ( $row, $col )) {
        return;
      }
    }
    $entry = $this->gdClient->updateCell ( $row, $col, $inputValue, $this->currKey, $this->currWkshtId );
    if ($entry instanceof Zend_Gdata_Spreadsheets_CellEntry) {
      echo "Success!\n";
    }
  }
  
  private function getHdrArrays($cKey, $wKey) {
    $query = new Zend_Gdata_Spreadsheets_CellQuery ();
    $query->setSpreadsheetKey ( $cKey );
    $query->setWorksheetId ( $wKey );
    $query->setMinRow ( 1 );
    $query->setMaxRow ( 1 );
    $feed = $this->gdClient->getCellFeed ( $query );
    
    $optHdrByIndex = null;
    $optIndexByHdr = null;
    
    foreach ( $feed->entries as $entry ) {
      if ($entry instanceof Zend_Gdata_Spreadsheets_CellEntry) {
        //print $entry->title->text . ' ' . $entry->content->text . "\n";
        //print $entry->getCell ()->getRow () . '|' . $entry->getCell ()->getColumn () . "|" . $entry->getCell ()->getInputValue () . "\n";
        $optHdrByIndex [$entry->getCell ()->getColumn ()] = $entry->getCell ()->getInputValue ();
        $optIndexByHdr [$entry->getCell ()->getInputValue ()] = $entry->getCell ()->getColumn ();
        ;
      
      }
      //$i ++;
    }
    
    $result ["HdrByIndex"] = $optHdrByIndex;
    $result ["IndexByHdr"] = $optIndexByHdr;
    
    return $result;
  }
  private function getOptionsHdrArrays($theKey) {
    //return $this->getHdrArrays ( $this->currKey, $this->currWkshtIdOpt );
    return $this->getHdrArrays ( $this->workDocs [$theKey] [CURRKEY], $this->workDocs [$theKey] [CURWKSHTIDOPT] );
  }
  
  private function getDataHdrArrays($theKey) {
    //return $this->getHdrArrays ( $this->currKey, $this->currWkshtIdDat );
    return $this->getHdrArrays ( $this->workDocs [$theKey] [CURRKEY], $this->workDocs [$theKey] [CURWKSHTIDDAT] );
  
  }
  
  private function createProdSQLInsert(&$curProd) {
    $pleerruID = $curProd ['Код'];
    $curID = 2000000 + $pleerruID;
    
    $prodName = addslashes ( $curProd ['Наименование'] );
    $prodSDesc = addslashes ( $curProd ['Описание'] );
    
    $fullPURL = addslashes ( $curProd [SM_INTERNAL_FULLPICTURL] );
    // $smallPURL = addslashes ( $curProd [SM_INTERNAL_SMALLPICTURL] );
    $smallPURL = $fullPURL;
    
    $fDesc = addslashes ( $curProd [SM_INTERNAL_PRODUCTDESCRIPTION] );
    
    $curProd [SM_INTERNAL_ID] = $curID;
    
    $cName = $curProd [SM_CATEGORY_LEVEL_1] . "/" . $curProd [SM_CATEGORY_LEVEL_2] . "/" . $curProd [SM_CATEGORY_LEVEL_3];
    $catID = $this->categories [$cName] ["id"];
    
    $cName2 = $curProd [SM_CATEGORY_LEVEL_1] . "/" . $curProd [SM_CATEGORY_LEVEL_2] . "/";
    $catID2 = $this->categories [$cName2] ["id"];
    
    $addr = '';
    if ($catID2 != $catID) {
      $addr = "
INSERT INTO `jos_vm_product_category_xref` VALUES($catID2,$curID,  1);
      ";
    }
    
    $artcl = "ELC$curID";
    
    $prc = $curProd [SM_INTERNAL_PRICE];
    return <<<EOT_EOT
delete from `jos_vm_product_price` where  product_id = '$curID';

delete from `jos_vm_product` where `product_id` = '$curID';      
insert `jos_vm_product` SET 
`algo_metatag`='$prodName',
`product_id` = '$curID',
`vendor_id` = '2',
`product_sku` = '$artcl',
`product_name` = '$prodName',
`product_desc` = '$fDesc',
`product_s_desc` = '$prodSDesc',
`product_thumb_image` = '$smallPURL',
`product_full_image` = '$fullPURL',
`product_publish` = 'Y',
`product_weight` = '0',
`product_weight_uom` = 'кг',
`product_length` = '0',
`product_width` = '0',
`product_height` = '0',
`product_lwh_uom` = '',
`product_unit` = '',
`product_packaging` = '0',
`product_url` = '',
`product_in_stock` = '43',
`attribute` = '',
`custom_attribute` = '',
`product_available_date` = '-86400',
`product_availability` = '',
`product_special` = 'N',
`child_options` = 'Y,N,N,N,N,Y,20%,10%,',
`quantity_options` = 'none,0,0,1',
`product_discount_id` = '1',
`mdate` = '1258580519',
`product_tax_id` = '0',
`child_option_ids` = '',
`product_order_levels` = '0,0'
;
delete from `jos_vm_product_category_xref` where `product_id` = '$curID';
INSERT INTO `jos_vm_product_category_xref` VALUES($catID,$curID,  1);

$addr

INSERT INTO `jos_vm_product_price` (product_price_id, product_id, product_price, product_currency,product_price_vdate,
product_price_edate,
cdate,
mdate,
shopper_group_id,
price_quantity_start,
price_quantity_end)
 VALUES(
 $curID , 
 $curID , 
 '$prc', 
 'RUB', 0, 0, 1258666157, 1259017978, 5, 0, 0)
 ; 

EOT_EOT;
  
  }
  
  private function createProdDescription(&$curProd, &$theKey) {
    $result = '<table border="1" width="100%"> <tbody>';
    
    $opts = $this->workDocs [$theKey] [OPTIONS];
    $optGrps = $this->workDocs [$theKey] [OPTIONSGROUP];
    $ordGrp = array ();
    foreach ( $optGrps as $k => $v ) {
      $ordGrp [$v [GROUPORDER]] = $v;
    }
    
    sort ( $ordGrp, SORT_NUMERIC );
    
    foreach ( $ordGrp as $k => $v ) {
      //echo $k . $v;
      

      // background-color: aquamarine;
      // background-color: powderblue;
      

      $result .= '<tr style="background-color: rgb(102, 204, 255);"><td colspan="2" rowspan="1" style="padding: 3px;"><span style="font-weight: bold;">' . $v [GROUPNAME] . '</span>&nbsp;&nbsp;</td></tr> ';
      $cnt = 1;
      foreach ( $v [OPTIONS] as $ko => $vo ) {
        
        if ($curProd [$vo] !== "") {
          $result .= '
  <tr style="' . (($cnt ++) % 2 ? 'background-color: rgb(204, 255, 255);' : 'background-color: rgb(153, 255, 255);') . '"><td style="padding: 3px;">&nbsp;&nbsp;&nbsp;' . addslashes ( $vo ) . '</td><td style="padding: 3px;">&nbsp;' . addslashes ( $curProd [$vo] ) . '</td></tr>
        ';
        
        }
      }
    }
    
    /*
    foreach ( $curProd as $k => $v ) {
      $result .= '
  <tr><td>&nbsp;' . addslashes ( $k ) . '</td><td>&nbsp;' . addslashes ( $v ) . '</td></tr>
        ';
    }
        */
    $result .= '</tbody></table>';
    
    $prodNameVK = $curProd ['Наименование'];
    $prodIDVK = $curProd [SM_INTERNAL_RECALC_ID];
    $prodURLVK = 'http://www.supermarket33.ru/index.php?page=shop.product_details&product_id=' . ($prodIDVK) . '&option=com_virtuemart';
    $fullPURL = $curProd [SM_INTERNAL_FULLPICTURL];
    //$prodImgURLVK = "http://www.supermarket33.ru/components/com_virtuemart/shop_image/product/$fullPURL";
    $prodImgURLVK = "http://www.supermarket33.ru/components/com_virtuemart/shop_image/product/$prodIDVK.jpg";
    
    $result .= '<script type="text/javascript">' . "if (showVKontakteButton) showVKontakteButton('$prodURLVK','$prodNameVK','Купи [$prodNameVK] на <br/> -=SuperMarket33.ru=-','$prodImgURLVK');" . 'if (showSocialSharing) showSocialSharing(1);' . '</script>';
    
    return $result;
  }
  
  private function loadCategories() {
    $v = &$this->categoriesDoc;
    //$v [CURRKEY] = $currKey [5];
    //$v [CURWKSHT]
    

    $datHeaders = $this->getHdrArrays ( $v [CURRKEY], $v [CURWKSHT] );
    
    $datHdrByIndex = $datHeaders ["HdrByIndex"];
    $datIndexByHdr = $datHeaders ["IndexByHdr"];
    
    $query = new Zend_Gdata_Spreadsheets_ListQuery ();
    $query->setSpreadsheetKey ( $v [CURRKEY] );
    $query->setWorksheetId ( $v [CURWKSHT] );
    
    for($i = 1; $i <= 10; $i ++) {
      try {
        $this->listFeed = $this->gdClient->getListFeed ( $query );
        break;
      } catch ( Exception $e ) {
        if ($i == 10) {
          throw $e;
        }
        continue;
      }
    }
    
    $feed = $this->listFeed;
    $resultData = null;
    
    foreach ( $feed->entries as $entry ) {
      if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
        // getCustomByName
        $cst = $entry->getCustom ();
        
        $nm = $cst [$datIndexByHdr ['name'] - 1]->getText ();
        $this->categories [$nm] ['id'] = $cst [$datIndexByHdr ['id'] - 1]->getText ();
        
        $id = $this->categories [$nm] ['id'] + 0;
        
        if ($id > $this->lastUsedCatID) {
          $this->lastUsedCatID = $id;
        }
        
        $this->categories [$nm] ['name'] = $nm;
        $this->categories [$nm] ['selfname'] = $cst [$datIndexByHdr ['selfname'] - 1]->getText ();
        $this->categories [$nm] ['sm.категория.1'] = $cst [$datIndexByHdr ['sm.категория.1'] - 1]->getText ();
        $this->categories [$nm] ['sm.категория.2'] = $cst [$datIndexByHdr ['sm.категория.2'] - 1]->getText ();
        $this->categories [$nm] ['sm.категория.3'] = $cst [$datIndexByHdr ['sm.категория.3'] - 1]->getText ();
        $this->categories [$nm] ['parentid'] = $cst [$datIndexByHdr ['parentid'] - 1]->getText ();
      }
    
    }
    $this->lastUsedCatID ++;
  }
  
  public function hndlData($theKey) {
    $datHeaders = $this->getDataHdrArrays ( $theKey );
    
    $datHdrByIndex = $datHeaders ["HdrByIndex"];
    $datIndexByHdr = $datHeaders ["IndexByHdr"];
    
    $query = new Zend_Gdata_Spreadsheets_ListQuery ();
    $query->setSpreadsheetKey ( $this->workDocs [$theKey] [CURRKEY] );
    $query->setWorksheetId ( $this->workDocs [$theKey] [CURWKSHTIDDAT] );
    
    for($i = 1; $i <= 10; $i ++) {
      try {
        $this->listFeed = $this->gdClient->getListFeed ( $query );
        break;
      } catch ( Exception $e ) {
        if ($i == 10) {
          throw $e;
        }
        continue;
      }
    }
    $i = 0;
    
    $feed = $this->listFeed;
    
    $resultData = null;
    
    foreach ( $feed->entries as $entry ) {
      if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
        // getCustomByName
        $cst = $entry->getCustom ();
        //print $i . ' pp ==  ' . $cst [$optIndexByHdr ["ПроБа пера"] - 1]->getText() . "\n";
        try {
          $curDat = $cst [$datIndexByHdr ["Код"] - 1]->getText ();
        } catch ( Exception $e ) {
          $curDat = null;
          continue;
        }
        try {
          $curMrkYaRu = $cst [$datIndexByHdr ["market.yandex.ru"] - 1]->getText ();
        } catch ( Exception $e ) {
          $curMrkYaRu = null;
        }
        try {
          if (! $cst [$datIndexByHdr [SM_IS_PUBLISHED] - 1]) {
            $curIsPblshd = null;
          } else {
            $curIsPblshd = $cst [$datIndexByHdr [SM_IS_PUBLISHED] - 1]->getText ();
          }
        } catch ( Exception $e ) {
          $curIsPblshd = null;
        }
        
        if (! $curIsPblshd) {
          continue;
        }
        
        if ($curIsPblshd == 0) {
          continue;
        }
        
        if ($curDat != "991845") {
          //continue;
        }
        if ($curDat === "" || $curDat === null || $curMrkYaRu === "" || $curMrkYaRu === null) {
          continue;
        }
        $resultData [$curDat] = null;
        
        //foreach ( $datIndexByHdr as $hdr ) {
        foreach ( $datHdrByIndex as $hdr ) {
          $hdrI = $datIndexByHdr [$hdr] - 1;
          try {
            if ($cst [$hdrI]) {
              $resultData [$curDat] [$hdr] = $cst [$hdrI]->getText ();
              /*
              if ($cst [$hdrI]->getText) {
              
              }
              */
            }
          } catch ( Exception $e ) {
            //continue;
          }
        }
        $catRName = $this->handleCategory ( $resultData [$curDat] );
        // XXX ffdfr
        $id = 2000000 + $curDat;
        $resultData [$curDat] ['Артикул'] = "ELC$id";
        $resultData [$curDat] [SM_CATEGORY_REFERENCE] = $catRName;
        $resultData [$curDat] [SM_INTERNAL_IDENTIFICATOR] = $curDat;
        $resultData [$curDat] [SM_INTERNAL_RECALC_ID] = $id;
        $resultData [$curDat] [SM_INTERNAL_PRODUCTDESCRIPTION] = $this->createProdDescription ( $resultData [$curDat], $theKey );
        //Ссылка на картинку
        $resultData [$curDat] [SM_INTERNAL_FULLPICTURL] = $resultData [$curDat] ['Ссылка на картинку'];
        
      //$resultData [$curDat] [SM_INTERNAL_PRODUCTSQLINSERTS] = $this->createProdSQLInsert ( $resultData [$curDat] );
      }
      $i ++;
      
    //break;
    }
    
    $result ["data"] = $resultData;
    
    return $result;
  
  }
  
  private function handleCategory($ctrgArray, $doRecurrent = true) {
    if ($doRecurrent) {
      $this->handleCategory ( array (SM_CATEGORY_LEVEL_1 => $ctrgArray [SM_CATEGORY_LEVEL_1], SM_CATEGORY_LEVEL_2 => "", SM_CATEGORY_LEVEL_3 => "" ), false );
      
      $this->handleCategory ( array (SM_CATEGORY_LEVEL_1 => $ctrgArray [SM_CATEGORY_LEVEL_1], SM_CATEGORY_LEVEL_2 => $ctrgArray [SM_CATEGORY_LEVEL_2], SM_CATEGORY_LEVEL_3 => "" ), false );
    }
    
    $cName = $ctrgArray [SM_CATEGORY_LEVEL_1] . "/" . $ctrgArray [SM_CATEGORY_LEVEL_2] . "/" . $ctrgArray [SM_CATEGORY_LEVEL_3];
    
    $topCat1 = $ctrgArray [SM_CATEGORY_LEVEL_1] . "/" . "/";
    $topCat2 = $ctrgArray [SM_CATEGORY_LEVEL_1] . "/" . $ctrgArray [SM_CATEGORY_LEVEL_2] . "/";
    
    if ($this->categories [$cName] == null) {
      // такой категории нет
      $this->categories [$cName] ["name"] = $cName;
      
      if ($ctrgArray [SM_CATEGORY_LEVEL_3] === "") {
        
        if ($ctrgArray [SM_CATEGORY_LEVEL_2] === "") {
          
          if ($ctrgArray [SM_CATEGORY_LEVEL_1] === "") {
          
          } else {
            $this->categories [$cName] ["selfname"] = $ctrgArray [SM_CATEGORY_LEVEL_1];
          }
        } else {
          $this->categories [$cName] ["selfname"] = $ctrgArray [SM_CATEGORY_LEVEL_2];
        }
      } else {
        $this->categories [$cName] ["selfname"] = $ctrgArray [SM_CATEGORY_LEVEL_3];
      }
      
      $this->categories [$cName] [SM_CATEGORY_LEVEL_1] = $ctrgArray [SM_CATEGORY_LEVEL_1];
      $this->categories [$cName] [SM_CATEGORY_LEVEL_2] = $ctrgArray [SM_CATEGORY_LEVEL_2];
      $this->categories [$cName] [SM_CATEGORY_LEVEL_3] = $ctrgArray [SM_CATEGORY_LEVEL_3];
      $this->categories [$cName] ["count"] = 1;
      $this->categories [$cName] ["id"] = $this->lastUsedCatID ++;
      
      if ($ctrgArray [SM_CATEGORY_LEVEL_2] == "" and $ctrgArray [SM_CATEGORY_LEVEL_3] == "") {
        $this->categories [$cName] ["parentid"] = 0;
      } elseif ($ctrgArray [SM_CATEGORY_LEVEL_3] == "") {
        $this->categories [$cName] ["parentid"] = $this->categories [$topCat1] ["id"];
      } else {
        $this->categories [$cName] ["parentid"] = $this->categories [$topCat2] ["id"];
      }
      
    //echo "cat no \n";
    } else {
      $this->categories [$cName] ["count"] = $this->categories [$cName] ["count"] + 1;
      //echo "cat yes \n";
    }
    
    return $this->categories [$cName] ["name"];
  }
  
  public function hndlOptions($theKey) {
    $optHeaders = $this->getOptionsHdrArrays ( $theKey );
    
    $optHdrByIndex = $optHeaders ["HdrByIndex"];
    $optIndexByHdr = $optHeaders ["IndexByHdr"];
    
    $query = new Zend_Gdata_Spreadsheets_ListQuery ();
    $query->setSpreadsheetKey ( $this->workDocs [$theKey] [CURRKEY] );
    $query->setWorksheetId ( $this->workDocs [$theKey] [CURWKSHTIDOPT] );
    $this->listFeed = $this->gdClient->getListFeed ( $query );
    $i = 0;
    
    $feed = $this->listFeed;
    
    $resultOptions = null;
    $resultOptGrp = array ();
    
    foreach ( $feed->entries as $entry ) {
      if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
        // getCustomByName
        $cst = $entry->getCustom ();
        
        //print $i . ' pp ==  ' . $cst [$optIndexByHdr ["ПроБа пера"] - 1]->getText () . "\n";
        

        $curOpt = $cst [$optIndexByHdr ["Option"] - 1]->getText ();
        
        $resultOptions [$curOpt] = null;
        $resultOptions [$curOpt] ["Option"] = $cst [$optIndexByHdr ["Option"] - 1]->getText ();
        $resultOptions [$curOpt] ["Group"] = $cst [$optIndexByHdr ["Group"] - 1]->getText ();
        $resultOptions [$curOpt] ["GroupOrder"] = $cst [$optIndexByHdr ["GroupOrder"] - 1]->getText ();
        $resultOptions [$curOpt] ["IsMain"] = $cst [$optIndexByHdr ["IsMain"] - 1]->getText ();
        
        $theGroup = $resultOptions [$curOpt] ["Group"];
        $resultOptGrp [$theGroup] [GROUPNAME] = $theGroup;
        if ($resultOptGrp [$theGroup] [GROUPORDER] == null) {
          $resultOptGrp [$theGroup] [GROUPORDER] = $resultOptions [$curOpt] ["GroupOrder"];
        }
        $resultOptGrp [$theGroup] [OPTIONS] [] = $curOpt;
      }
      $i ++;
    }
    
    $result ["options"] = $resultOptions;
    $result ["optionsgroup"] = $resultOptGrp;
    
    return $result;
  
  }
  
  /**
   * listGetAction
   *
   * @return void
   */
  public function listGetAction() {
    $query = new Zend_Gdata_Spreadsheets_ListQuery ();
    $query->setSpreadsheetKey ( $this->currKey );
    $query->setWorksheetId ( $this->currWkshtId );
    $this->listFeed = $this->gdClient->getListFeed ( $query );
    print "entry id | row-content in column A | column-header: cell-content\n" . "Please note: The 'dump' command on the list feed only dumps data until the first blank row is encountered.\n\n";
    
    $this->printFeed ( $this->listFeed );
    print "\n";
  }
  
  /**
   * listInsertAction
   *
   * @param  mixed $rowData
   * @return void
   */
  public function listInsertAction($rowData) {
    $rowArray = $this->stringToArray ( $rowData );
    $entry = $this->gdClient->insertRow ( $rowArray, $this->currKey, $this->currWkshtId );
    if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
      foreach ( $rowArray as $column_header => $value ) {
        echo "Success! Inserted '$value' in column '$column_header' at row " . substr ( $entry->getTitle ()->getText (), 5 ) . "\n";
      }
    }
  }
  
  /**
   * listUpdateAction
   *
   * @param  integer $index
   * @param  mixed   $rowData
   * @return void
   */
  public function listUpdateAction($index, $rowData) {
    $query = new Zend_Gdata_Spreadsheets_ListQuery ();
    $query->setSpreadsheetKey ( $this->currKey );
    $query->setWorksheetId ( $this->currWkshtId );
    $this->listFeed = $this->gdClient->getListFeed ( $query );
    $rowArray = $this->stringToArray ( $rowData );
    $entry = $this->gdClient->updateRow ( $this->listFeed->entries [$index], $rowArray );
    if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
      echo "Success!\n";
      $response = $entry->save ();
    
    }
  }
  
  /**
   * listDeleteAction
   *
   * @param  integer $index
   * @return void
   */
  public function listDeleteAction($index) {
    $query = new Zend_Gdata_Spreadsheets_ListQuery ();
    $query->setSpreadsheetKey ( $this->currKey );
    $query->setWorksheetId ( $this->currWkshtId );
    $this->listFeed = $this->gdClient->getListFeed ( $query );
    $this->gdClient->deleteRow ( $this->listFeed->entries [$index] );
  }
  
  /**
   * stringToArray
   *
   * @param  string $rowData
   * @return array
   */
  public function stringToArray($rowData) {
    $arr = array ();
    foreach ( $rowData as $row ) {
      $temp = explode ( '=', $row );
      $arr [$temp [0]] = $temp [1];
    }
    return $arr;
  }
  
  /**
   * printFeed
   *
   * @param  Zend_Gdata_Gbase_Feed $feed
   * @return void
   */
  public function printFeed($feed) {
    $i = 0;
    foreach ( $feed->entries as $entry ) {
      if ($entry instanceof Zend_Gdata_Spreadsheets_CellEntry) {
        print $entry->title->text . ' ' . $entry->content->text . "\n";
      } else if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
        print $i . ' | ' . $entry->id->text . ' ' . $entry->title->text . ' | ' . $entry->content->text . "\n";
      } else {
        print $i . ' | ' . $entry->id->text . ' | ' . $entry->title->text . "\n";
      }
      $i ++;
    }
  }
  
  /**
   * getRowAndColumnCount
   *
   * @return void
   */
  public function getRowAndColumnCount() {
    $query = new Zend_Gdata_Spreadsheets_CellQuery ();
    $query->setSpreadsheetKey ( $this->currKey );
    $query->setWorksheetId ( $this->currWkshtId );
    $feed = $this->gdClient->getCellFeed ( $query );
    
    if ($feed instanceof Zend_Gdata_Spreadsheets_CellFeed) {
      $this->rowCount = $feed->getRowCount ();
      $this->columnCount = $feed->getColumnCount ();
    }
  }
  
  /**
   * invalidCommandError
   *
   * @param  string $input
   * @return void
   */
  public function invalidCommandError($input) {
    echo 'Invalid input: ' . $input . "\n";
  }
  
  /**
   * promtForFeedtype
   *
   * @return void
   */
  public function promptForFeedtype() {
    
    $input = getInput ( 'Select to use either the cell or the list feed [cells or list]' );
    
    if ($input == 'cells') {
      while ( 1 ) {
        $this->promptForCellsAction ();
      }
    } else if ($input == 'list') {
      while ( 1 ) {
        $this->promptForListAction ();
      }
    } else {
      print "Invalid input. Please try again.\n";
      $this->promptForFeedtype ();
    }
  }
  
  private function getEmptyGoogleAdvertisingArray($compGeo) {
    if ($compGeo == SM_GEO_COMP_VLADIMIR) {
      return array ('Реклама магазина SuperMarket33.ru (Владимир)', '', '', '', '', '', '', '', '', 'active', 'active', 'active', 'active', 'add' );
    }
    return array ('Реклама магазина SuperMarket33.ru', '', '', '', '', '', '', '', '', 'active', 'active', 'active', 'active', 'add' );
  }
  
  private function to1251(&$iArr) {
    $res = array ();
    foreach ( $iArr as $k => $v ) {
      //$res[]=mb_convert_encoding($v,"Windows-1251",'UTF-8');
      $res [] = iconv ( 'UTF-8', "Windows-1251", $v );
    }
    return $res;
  }
  private function writeGoogleAdvertisingGroupToTempFile($destFile, $docInfo, $prodInfo, $compGeo = SM_GEO_COMP_KOVROV) {
    // одна группа на один продукт
    if (! $prodInfo ['sm.публиковать']) {
      return;
    }
    $advInfo = $this->getEmptyGoogleAdvertisingArray ( $compGeo );
    $prodName = $prodInfo [SM_ADV_NAME];
    if (! $prodName) {
      $prodName = $prodInfo ['Наименование'];
    }
    $prodName = $prodName . '^' . $prodInfo ['Код'];
    
    $groupName = "Реклама для [" . $prodName . "]";
    
    $advInfo [1] = $groupName;
    
    //array ($dv [SM_INTERNAL_IDENTIFICATOR] );
    fputcsv ( $destFile, $this->to1251 ( $advInfo ) );
    
    return $groupName;
  
  }
  
  private function writeGoogleAdvertisingMessagesToTempFile($destFile, $docInfo, $prodInfo, $groupName, $compGeo) {
    // одно/несколько объявлений на продукт
    if (! $prodInfo ['sm.публиковать']) {
      return;
    }
    
    $advInfo = $this->getEmptyGoogleAdvertisingArray ( $compGeo );
    $prodName = $prodInfo [SM_ADV_NAME];
    $prodName = $prodInfo [SM_ADV_NAME];
    if (! $prodName) {
      $prodName = $prodInfo ['Наименование'];
    }
    
    $prodID = $prodInfo [SM_INTERNAL_RECALC_ID];
    
    $prodPrice = $prodInfo [SM_INTERNAL_PRICE];
    $advInfo [1] = $groupName;
    //Купи свч daewoo kor-4125a	Доставка бесплатно	Цена в Коврове 1660 руб.	www.supermarket33.ru
    // http://www.supermarket33.ru/index.php?page=shop.product_details&product_id=2007987&option=com_virtuemart
    

    $advInfo [4] = strtolower ( $prodName );
    if ($compGeo == SM_GEO_COMP_VLADIMIR) {
      $advInfo [5] = 'На заказ. С доставкой';
      $advInfo [6] = 'Во Владимире ' . $prodPrice . 'р';
    } else {
      $advInfo [5] = 'Доставка бесплатно';
      $advInfo [6] = 'Купи в Коврове ' . $prodPrice . 'р';
    }
    
    $advInfo [7] = 'www.supermarket33.ru';
    $advInfo [8] = 'http://www.supermarket33.ru/index.php?page=shop.product_details&product_id=' . ($prodID) . '&option=com_virtuemart';
    
    fputcsv ( $destFile, $this->to1251 ( $advInfo ) );
  
  }
  
  private function writeGoogleAdvertisingKeyWordsToTempFile($destFile, $docInfo, $prodInfo, $groupName, $compGeo = SM_GEO_COMP_KOVROV) {
    if (! $prodInfo ['sm.публиковать']) {
      return;
    }
    
    $advInfo = $this->getEmptyGoogleAdvertisingArray ( $compGeo );
    $prodName = $prodInfo [SM_ADV_NAME];
    if (! $prodName) {
      $prodName = $prodInfo ['Наименование'];
    }
    $firmName = $prodInfo ['Производитель'];
    
    $kWords = $docInfo [ADV_GGL_KEYWORDS];
    
    $kwArray = preg_split ( '/,/', $kWords );
    
    $advInfo = $this->getEmptyGoogleAdvertisingArray ( $compGeo );
    $advInfo [1] = $groupName;
    $advInfo [3] = 'Broad';
    
    $advInfo [2] = $prodName;
    fputcsv ( $destFile, $this->to1251 ( $advInfo ) );
    
    $advInfo [2] = $prodInfo ['Наименование'];
    fputcsv ( $destFile, $this->to1251 ( $advInfo ) );
    
    foreach ( $kwArray as $k => $v ) {
      $advInfo [2] = $v . ' ' . $prodName;
      fputcsv ( $destFile, $this->to1251 ( $advInfo ) );
      
      $advInfo [2] = $v . ' ' . $prodInfo ['Наименование'];
      fputcsv ( $destFile, $this->to1251 ( $advInfo ) );
      
      $advInfo [2] = $v . ' ' . $firmName;
      fputcsv ( $destFile, $this->to1251 ( $advInfo ) );
    }
  
  }
  
  private function writeGoogleAdvertisingToTempFile($compGeo = SM_GEO_COMP_KOVROV, $fName = "tmp/adv_google.csv") {
    $csvile = @fopen ( $fName, "w" );
    //$advInfo = array ('id', 'name', 'selfname', 'sm.категория.1', 'sm.категория.2', 'sm.категория.3', 'parentid' );
    $advInfo = array ('Campaign', 'Ad Group', 'Keyword', 'Keyword Type', 'Headline', 'Description Line 1', 'Description Line 2', 'Display URL', 'Destination URL', 'Campaign Status', 'AdGroup Status', 'Creative Status', 'Keyword Status', 'Suggested Changes' );
    
    fputcsv ( $csvile, $advInfo );
    
    foreach ( $this->workDocs as $k => $v ) {
      $data = $this->workDocs [$k] [DATA];
      if (! $this->workDocs [$k] [ADV_GGL_WRITE]) {
        continue;
      }
      //echo "$k == $v\n";
      foreach ( $data as $dk => $dv ) {
        $gName = $this->writeGoogleAdvertisingGroupToTempFile ( $csvile, $v, $dv, $compGeo );
        $this->writeGoogleAdvertisingMessagesToTempFile ( $csvile, $v, $dv, $gName, $compGeo );
        $this->writeGoogleAdvertisingKeyWordsToTempFile ( $csvile, $v, $dv, $gName, $compGeo );
      }
      ;
    
    }
    fclose ( $csvile );
  
  }
  
  private function writeCategoriesToYML($YMLWriter) {
    /*
    $categoryInfo['id']
    $categoryInfo['name']
    $categoryInfo['parentId']
    
    
     * 
     */
    foreach ( $this->categories as $k => $v ) {
      $categoryInfo ['id'] = $v ['id'];
      $categoryInfo ['name'] = $v ['selfname'];
      $categoryInfo ['parentId'] = $v ['parentid'];
      $YMLWriter->writeCategoryElement ( $categoryInfo );
    }
  }
  
  private function writeOffersToYML($YMLWriter) {
    $YMLWriter->writeOffersElementStart ();
    foreach ( $this->workDocs as $k => $v ) {
      $data = $this->workDocs [$k] [DATA];
      if (! $this->workDocs [$k] [ADV_YNDX_WRITE]) {
        continue;
      }
      foreach ( $data as $dk => $dv ) {
        if (! $dv ['sm.публиковать']) {
          continue;
        }
        
        //$offer ['type'] = 'vendor.model';
        $offer ['available'] = 'false';
        $offer ['bid'] = NULL;
        
        $prodID = $dv [SM_INTERNAL_RECALC_ID];
        $offer ['id'] = $prodID;
        $url = 'http://www.supermarket33.ru/index.php?page=shop.product_details&product_id=' . ($prodID) . '&option=com_virtuemart';
        $pct = 'http://www.supermarket33.ru/components/com_virtuemart/shop_image/product/' . $prodID . '.jpg';
        $offer ['url'] = $url;
        $offer ['picture'] = $pct;
        
        $offer ['currencyId'] = 'RUR';
        $offer ['delivery'] = 'true';
        
        // sales_notes
        $offer ['sales_notes'] = 'Требуется предоплата.';
        
        $offer ['price'] = $dv [SM_INTERNAL_PRICE];
        
        //name
        $offer ['name'] = strtolower ( $dv ['Наименование'] );
        
        // vendor
        $offer ['vendor'] = $dv ['Производитель'];
        
        //description
        $offer ['description'] = $dv ['Описание'];
        
        // categoryId
        $offer ['categoryId'] = $this->categories [$dv [SM_CATEGORY_REFERENCE]] ["id"];
        
        //local_delivery_cost
        $offer ['local_delivery_cost'] = 0;
        
        $YMLWriter->writeOfferElement ( $offer );
        /*
        $gName = $this->writeGoogleAdvertisingGroupToTempFile ( $csvile, $v, $dv, $compGeo );
        $this->writeGoogleAdvertisingMessagesToTempFile ( $csvile, $v, $dv, $gName, $compGeo );
        $this->writeGoogleAdvertisingKeyWordsToTempFile ( $csvile, $v, $dv, $gName, $compGeo );
        */
      }
      ;
    
    }
    
    $YMLWriter->writeOffersElementFinish ();
  }
  private function writeYMLToTempFile($fName) {
    //doWriteYMLToTempFile($fName);
    $YMLWriter = new sm33_myandexexp ( $fName );
    $YMLWriter->writeHDR ();
    $YMLWriter->writeRootStart ();
    
    $shopInfo ['name'] = 'SuperMarket33.ru';
    $shopInfo ['company'] = 'ООО СУПЕРМАРКЕТ33';
    $shopInfo ['url'] = 'http://www.supermarket33.ru';
    
    $YMLWriter->writeShopElementStart ( $shopInfo );
    
    $YMLWriter->writeCurrenciesElement ();
    $YMLWriter->writeCategoriesElementStart ();
    $this->writeCategoriesToYML ( $YMLWriter );
    
    $YMLWriter->writeCategoriesElementFinish ();
    
    $YMLWriter->writeLocalDeliveryElement ( 0 );
    
    $this->writeOffersToYML ( $YMLWriter );
    
    //$csvile = @fopen ( $fName, "w" );
    //$advInfo = array ('id', 'name', 'selfname', 'sm.категория.1', 'sm.категория.2', 'sm.категория.3', 'parentid' );
    /*

    $advInfo = array ('Campaign', 'Ad Group', 'Keyword', 'Keyword Type', 'Headline', 'Description Line 1', 'Description Line 2', 'Display URL', 'Destination URL', 'Campaign Status', 'AdGroup Status', 'Creative Status', 'Keyword Status', 'Suggested Changes' );
    
    fputcsv ( $csvile, $advInfo );
    
    foreach ( $this->workDocs as $k => $v ) {
      $data = $this->workDocs [$k] [DATA];
      if (! $this->workDocs [$k] [ADV_GGL_WRITE]) {
        continue;
      }
      //echo "$k == $v\n";
      foreach ( $data as $dk => $dv ) {
        $gName = $this->writeGoogleAdvertisingGroupToTempFile ( $csvile, $v, $dv, $compGeo );
        $this->writeGoogleAdvertisingMessagesToTempFile ( $csvile, $v, $dv, $gName, $compGeo );
        $this->writeGoogleAdvertisingKeyWordsToTempFile ( $csvile, $v, $dv, $gName, $compGeo );
      }
      ;
    
    }
    */
    $YMLWriter->writeShopElementFinish ( $shopInfo );
    $YMLWriter->writeRootFinish ();
    $YMLWriter->closeFile ();
    
  //  fclose ( $csvile );
  

  }
  
  private function writeCategoriesToTempFile() {
    $csvile = @fopen ( "tmp/categories.csv", "w" );
    $catInfo = array ('id', 'name', 'selfname', 'sm.категория.1', 'sm.категория.2', 'sm.категория.3', 'parentid' );
    fputcsv ( $csvile, $catInfo );
    
    foreach ( $this->categories as $k => $v ) {
      $catInfo = array ($v ['id'], $v ['name'], $v ['selfname'], $v ['sm.категория.1'], $v ['sm.категория.2'], $v ['sm.категория.3'], $v ['parentid'] );
      fputcsv ( $csvile, $catInfo );
    }
    fclose ( $csvile );
  }
  
  private function recalcCategories() {
    return;
    $minID = 2000;
    foreach ( $this->categories as $k => $v ) {
      $this->categories [$k] ["id"] = $minID + $this->categories [$k] ["id"];
      $this->categories [$k] ["parentid"] = ($this->categories [$k] ["parentid"] > 0 ? $minID + $this->categories [$k] ["parentid"] : 0);
    }
  }
  
  private function loadPImagesFromWeb() {
    $res = "";
    foreach ( $this->workDocs as $k => $v ) {
      $data = $this->workDocs [$k] [DATA];
      echo "$k == $v\n";
      foreach ( $data as $dk => $dv ) {
        //echo "$dk == $dv\n"
        // $resultData [$curDat] [SM_INTERNAL_PRODUCTSQLINSERTS] = $this->createProdSQLInsert ( $resultData [$curDat] );
        ;
        $curID = 2000000 + $dv ['Код'];
        
        echo "Loading image $curID                         \r";
        
        $imldr = new ImageLoader ( $curID, $dv [SM_INTERNAL_FULLPICTURL], '' );
        $imgLoaded = $imldr->tryLoadImage ();
        if ($imgLoaded) {
          if ($imgLoaded == "JPG") {
            $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_FULLPICTURL] = '' . $curID . '.jpg';
            $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_SMALLPICTURL] = 'resized/' . $curID . '_100x160.jpg';
          } else if ($imgLoaded == "PNG") {
            $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_FULLPICTURL] = '' . $curID . '.png';
            $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_SMALLPICTURL] = 'resized/' . $curID . '_100x160.png';
          } else {
            $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_FULLPICTURL] = '';
            $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_SMALLPICTURL] = '';
          }
        } else {
          $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_FULLPICTURL] = '';
          $this->workDocs [$k] [DATA] [$dk] [SM_INTERNAL_SMALLPICTURL] = '';
        }
      }
    }
    echo "\nImages loaded\n";
    
    return $res;
  }
  
  private function createProductsSQLs() {
    $res = "";
    foreach ( $this->workDocs as $k => $v ) {
      $data = $this->workDocs [$k] [DATA];
      foreach ( $data as $dk => $dv ) {
        // $resultData [$curDat] [SM_INTERNAL_PRODUCTSQLINSERTS] = $this->createProdSQLInsert ( $resultData [$curDat] );
        $sql = $this->createProdSQLInsert ( $dv );
        $dv [SM_INTERNAL_PRODUCTSQLINSERTS] = $sql;
        $res .= $dv [SM_INTERNAL_PRODUCTSQLINSERTS];
      }
    }
    return $res;
  }
  
  private function createCategoriesSQLs() {
    $res = "";
    
    foreach ( $this->categories as $k => $v ) {
      $catIndex = $v ["id"];
      $catName = $v ["selfname"];
      $parIndex = $v ["parentid"];
      $res .= "
      delete from `jos_vm_category_xref` where `jos_vm_category_xref`.`category_parent_id`= $catIndex OR
      `jos_vm_category_xref`.`category_child_id`= $catIndex;
      DElete FROM `jos_vm_product_category_xref` where `jos_vm_product_category_xref`.category_id= $catIndex;
      DElete FROM `jos_vm_category` where `jos_vm_category`.category_id = $catIndex;
      ";
    }
    
    foreach ( $this->categories as $k => $v ) {
      $catIndex = $v ["id"];
      $catName = $v ["selfname"];
      $parIndex = $v ["parentid"];
      $res .= "
      INSERT INTO `jos_vm_category` VALUES($catIndex, 2, '$catName', '', '', '', 'Y', 1259018351, 1259018351, 'browse_4', 3, 'flypage.tpl', 2);
      INSERT INTO `jos_vm_category_xref`(`category_parent_id`,`category_child_id`,`category_list`) 
      VALUES($parIndex, 
      $catIndex
      ,NULL);
    	";
      /*
      INSERT INTO `jos_vm_product_category_xref` VALUES($parIndex, $catIndex, 1);    
      $this->categories[$k]["id"] = $minID + $this->categories[$k]["id"]; 
      $this->categories[$k]["parentid"] = $minID + $this->categories[$k]["parentid"];
      */
    }
    
    return $res;
    /*
    
          if (in_array ( $catName, $pleerruCategories )) {
            $ind = array_search ( $catName, $pleerruCategories );
            $ind += 10002;
            return "
      INSERT INTO `jos_vm_product_category_xref` VALUES($ind, 
      $curID
      , 1);    
      		";
          }
    */
  }
  
  private function createAndWriteSQLs() {
    $sqlFile = @fopen ( "cats.sql", "w" );
    $catSQLs = $this->createCategoriesSQLs ();
    @fwrite ( $sqlFile, $catSQLs );
    $prodSQLs = $this->createProductsSQLs ();
    @fwrite ( $sqlFile, $prodSQLs );
    @fclose ( $sqlFile );
    /*
    $sqlFile = @fopen ( "prods.sql", "w" );
    @fclose ( $sqlFile );
    */
  }
  
  public function runListDocs() {
    $this->listSpreadsheets ();
  }
  /**
   * run
   *
   * @return void
   */
  public function run($isLoadImages = 1) {
    echo '$isLoadImages==' . "$isLoadImages\n";
    $docList = new GetAvailableDocuments ( $this->email, $this->password );
    
    $docs = $docList->getDocsArray ();
    
    $this->workDocs = $docs;
    
    $this->categoriesDoc = $docList->getCategories ();
    
    $this->promptForCategoriesSpreadsheet ();
    $this->loadCategories ();
    $this->promptForSpreadsheet ();
    
    foreach ( $this->workDocs as $k => $v ) {
      echo "Handling \t $k           \r";
      $this->promptForWorksheet ( $k );
      
      $res = null;
      $res = $this->hndlOptions ( $k );
      
      $this->workDocs [$k] [OPTIONS] = $res ["options"];
      $this->workDocs [$k] [OPTIONSGROUP] = $res ["optionsgroup"];
      
      $res = null;
      $res = $this->hndlData ( $k );
      $data = $res ["data"];
      $this->workDocs [$k] [DATA] = $data;
    
    }
    
    $this->recalcCategories ();
    $this->writeCategoriesToTempFile ();
    
    foreach ( $this->workDocs as $k => $v ) {
      //print ' | ' . $k . ' | ' . $v . "\n";
    //$res[$entry->title->text] = $entry->id->text; 
    //$i ++;
    }
    
    //print ' | loadPImagesFromWeb' . "\n";
    if ($isLoadImages) {
      $this->loadPImagesFromWeb ();
    }
    
    $this->createAndWriteSQLs ();
    
    $this->writeGoogleAdvertisingToTempFile ( SM_GEO_COMP_KOVROV, "tmp/adv_google_kovrov.csv" );
    $this->writeGoogleAdvertisingToTempFile ( SM_GEO_COMP_VLADIMIR, "tmp/adv_google_vladimir.csv" );
    $this->writeYMLToTempFile ( './tmp/yandex/testyml.xml' );
    
    die ( "\nAll done                   \n" );
    
    /*
    foreach ( $this->categories as $en ) {
      echo '$en == ' . $en ["name"] . "\n";
    }
    */
    
    foreach ( $options as $entry ) {
      $optName = $entry ["Option"];
      //print "555 == ".$optName."|". $options[$optName]["Option"]."|".$options[$optName]["Group"]."\n";
    }
    
  //$this->promptForFeedtype ();
  }
}

/**
 * getInput
 *
 * @param  string $text
 * @return string
 */
function getInput($text) {
  echo $text . ': ';
  return trim ( fgets ( STDIN ) );
}

$email = "sm33.bot@gmail.com";
$pass = "Rty6$52hgsgt";

//$argv[]='--updatemartins=..\martins\martins.xls';
//$argv [] = '--makesql';
//$argv[]='--updatefcenter=..\fcenter\price.html';


foreach ( $argv as $argument ) {
  
  $argParts = explode ( '=', $argument );
  if ($argParts [0] == '--updatemartins') {
    $updatemartins = $argParts [1];
    if ($updatemartins) {
      require_once 'sm33/martinsupdater.php';
      updateMartinsFromFile ( $updatemartins, $email, $pass );
      die ();
    }
  }
  if ($argParts [0] == '--makesql') {
    $sample = new SimpleCRUD ( $email, $pass );
    $sample->run ();
    die ();
  }
  if ($argParts [0] == '--makesqlnoimages') {
    echo "\nmakesqlnoimages\n";
    $sample = new SimpleCRUD ( $email, $pass );
    $sample->run ( 0 );
    die ();
  }
  if ($argParts [0] == '--listdocs') {
    $sample = new SimpleCRUD ( $email, $pass );
    $sample->runListDocs ();
    die ();
  }
  if ($argParts [0] == '--updatefcenter') {
    $updatefcenter = $argParts [1];
    if ($updatefcenter) {
      require_once 'sm33/fcenterupdater.php';
      updateFCenterFromFile ( $updatefcenter, $email, $pass );
      die ();
    }
  }
}

echo "Use commands
\t--updatemartins=_file_name_  	\t to update martins.ru price
\t--makesql                    	\t to create SQL file
\t--makesqlnoimages				\t to create SQL file without images
\t--listdocs                	\t to list available docs
\t--updatefcenter=_file_name_  	\t to update fcenter.ru price
";

/*
// process command line options
echo "Use commands
\t--updatemartins=_file_name_  \t to update martins.ru price
\t--makesql                    \t to create SQL file
\t--listdocs                   \t to list available docs
\t--updatefcenter=_file_name_  \t to update fcenter.ru price
";

/*
// process command line options

if (($email == null) || ($pass == null)) {
  $email = getInput ( "Please enter your email address [example: username@gmail.com]" );
  $pass = getInput ( "Please enter your password [example: mypassword]" );
}
*/
