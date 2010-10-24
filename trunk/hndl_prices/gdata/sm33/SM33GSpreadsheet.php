<?php
/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

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

//require_once 'SM33GData.php';


define ( "SM_WORKSHEET_TITLE", "SM_WORKSHEET_TITLE", true );
define ( "SM_WORKSHEET_ID", "SM_WORKSHEET_ID", true );

class sm33_SM33GSpreadsheet {
  public function __construct($gdata, $docURL, $docName) {
    $this->docURL = $docURL;
    $this->docName = $docName;
    $this->gData = &$gdata;
    
    $currKey = explode ( '/', $this->docURL );
    $this->docKey = $currKey [5];
    
    $query = new Zend_Gdata_Spreadsheets_DocumentQuery ();
    $query->setSpreadsheetKey ( $this->docKey );
    $feed = $this->gData->getGDClient ()->getWorksheetFeed ( $query );
    foreach ( $feed->entries as $entry ) {
      $currWkshtTitle = $entry->title->text;
      $currWksht = explode ( '/', $entry->id->text );
      $this->worksheetsByName [$currWkshtTitle] [SM_WORKSHEET_TITLE] = $currWkshtTitle;
      $this->worksheetsByName [$currWkshtTitle] [SM_WORKSHEET_ID] = $currWksht [8];
    }
  }
  
  public function getGDClient(){
    return $this->gData->getGDClient();
  }
  
  public function updateCell( $row,  $col, $inputValue,  $wkshtName) {
    return $this->gData->getGDClient ()->updateCell ( $row, $col, $inputValue, $this->docKey, $this->getWorksheetID ( $wkshtName ) );
  }
  
  public function getSpreadsheetID() {
    return $this->docKey;
  }
  
  public function getWorksheetID($wrkName) {
    if ($this->worksheetsByName [$wrkName]) {
      return $this->worksheetsByName [$wrkName] [SM_WORKSHEET_ID];
    }
    return null;
  }
  
  public function getRowAndColumnCount($wkshtName, $forceUpdate=false) {
    if ($this->worksheetsByName [$wkshtName][ROWS] &&
    $this->worksheetsByName [$wkshtName][COLS] && 
    !$forceUpdate) {
      $res = array ();
      $res [ROWS] = $this->worksheetsByName [$wkshtName] [ROWS];
      $res [COLS] = $this->worksheetsByName [$wkshtName] [COLS];
      return $res;
    } else {
      $query = new Zend_Gdata_Spreadsheets_CellQuery ();
      $query->setSpreadsheetKey ( $this->docKey );
      $query->setWorksheetId ( $this->getWorksheetID ( $wkshtName ) );
      $feed = $this->gData->getGDClient ()->getCellFeed ( $query );
      
      $res = null;
      
      if ($feed instanceof Zend_Gdata_Spreadsheets_CellFeed) {
        $res = array ();
        $this->worksheetsByName [$wkshtName] [ROWS] = $feed->getRowCount ();
        $this->worksheetsByName [$wkshtName] [COLS] = $feed->getColumnCount ();
        
        $res [ROWS] = $this->worksheetsByName [$wkshtName] [ROWS];
        $res [COLS] = $this->worksheetsByName [$wkshtName] [COLS];
      }
      
      return $res;
    }
  }

}
?>