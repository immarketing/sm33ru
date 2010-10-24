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

class sm33_SM33GData {
  public function __construct($email, $password) {
    try {
      $client = Zend_Gdata_ClientLogin::getHttpClient ( $email, $password, Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME );
    } catch ( Zend_Gdata_App_AuthException $ae ) {
      exit ( "Error: " . $ae->getMessage () . "\nCredentials provided were email: [$email] and password [$password].\n" );
    }
    
    $this->gdClient = new Zend_Gdata_Spreadsheets ( $client );
  }
  
  public function getGDClient(){
    return $this->gdClient; 
  }
  
  public function getSpreadsheetByName($docName){
    $res = array ();
    
    $feed = $this->gdClient->getSpreadsheetFeed ();
    foreach ( $feed->entries as $entry ) {
      //print $i . ' | ' . $entry->id->text . ' | ' . $entry->title->text . "\n";
      if ($docName === $entry->title->text ){ //
        return new sm33_SM33GSpreadsheet($this, $entry->id->text, $entry->title->text);
      }
    }
    return null;
  }
    
  
}
?>