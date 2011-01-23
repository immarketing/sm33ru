<?php
class sm33_myandexexp {
  private function openFile() {
    $this->hFile = @fopen ( $this->fName, "w" );
  }
  public function __construct($fName) {
    $this->fName = $fName;
    $this->openFile ();
  }
  
  public function writeHDR() {
    fwrite ( $this->hFile, '<?xml version="1.0" encoding="windows-1251"?>' . "\n" );
    fwrite ( $this->hFile, '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . "\n" );
  }
  
  // YYYY-MM-DD HH:mm
  public function writeRootStart() {
    $dt = date ( 'Y-m-d H:i' );
    fwrite ( $this->hFile, '<yml_catalog date="' . $dt . '">' . "\n" );
    //fwrite ( $this->hFile, '<shop>' . "\n" );
  }
  
  private function to1251(&$Sr, $spc = 0) {
    // htmlspecialchars 
    return ($spc ? htmlspecialchars ( iconv ( 'UTF-8', "Windows-1251", $Sr ) ) : iconv ( 'UTF-8', "Windows-1251", $Sr ));
  }
  
  public function writeOffersElementStart() {
    fwrite ( $this->hFile, '<offers>'. "\n" );
  }
  public function writeOffersElementFinish() {
    fwrite ( $this->hFile, '</offers>'. "\n" );
  }
  
  public function writeOfferElement($offer) {
    // id="12341" type="vendor.model" available="true" bid="13"
    

    /*
     * $offer['id']
     */
    
    $type = ($offer ['type'] ? 'type="' . $offer ['type'] . '"' : '');
    fwrite ( $this->hFile, '<offer id="' . $offer ['id'] . '" ' . $type . ' available="' . $offer ['available'] . '" ' . ($offer ['bid'] ? '" bid="' . $offer ['bid'] . '"' : '') . '>' . "\n" );
    if ($offer ['url']) {
      fwrite ( $this->hFile, '<url>' . $this->to1251 ( $offer ['url'], 1 ) . '</url>' . "\n" );
    }
    
    // price
    if ($offer ['price']) {
      fwrite ( $this->hFile, '<price>' . $this->to1251 ( $offer ['price'] ) . '</price>' . "\n" );
    }
    
    // currencyId
    if ($offer ['currencyId']) {
      fwrite ( $this->hFile, '<currencyId>' . $offer ['currencyId'] . '</currencyId>' . "\n" );
    }
    
    // categoryId
    if ($offer ['categoryId']) {
      fwrite ( $this->hFile, '<categoryId>' . $this->to1251 ( $offer ['categoryId'], 1 ) . '</categoryId>' . "\n" );
    }
    
    // picture
    if ($offer ['picture']) {
      fwrite ( $this->hFile, '<picture>' . $offer ['picture'] . '</picture>' . "\n" );
    }
    
    // delivery
    if ($offer ['delivery']) {
      fwrite ( $this->hFile, '<delivery>' . $offer ['delivery'] . '</delivery>' . "\n" );
    }
    
    // local_delivery_cost
    if ($offer ['local_delivery_cost']) {
      fwrite ( $this->hFile, '<local_delivery_cost>' . $offer ['local_delivery_cost'] . '</local_delivery_cost>' . "\n" );
    }
    
    // name
    if ($offer ['name']) {
      fwrite ( $this->hFile, '<name>' . $this->to1251 ( $offer ['name'], 1 ) . '</name>' . "\n" );
    }
    
    // vendor
    if ($offer ['vendor']) {
      fwrite ( $this->hFile, '<vendor>' . $this->to1251 ( $offer ['vendor'], 1 ) . '</vendor>' . "\n" );
    }
    
    //description
    if ($offer ['description']) {
      fwrite ( $this->hFile, '<description>' . $this->to1251 ( $offer ['description'], 1 ) . '</description>' . "\n" );
    }
    
    // sales_notes
    if ($offer ['sales_notes']) {
      fwrite ( $this->hFile, '<sales_notes>' . $this->to1251 ( $offer ['sales_notes'], 1 ) . '</sales_notes>' . "\n" );
    }
    
    //   <url>http://best.seller.ru/product_page.asp?pid=12344</url>
    /*
    $offer['id']
    $offer['type']
    $offer['available']
    $offer['bid']
        $offer ['url'] = $url;
        $offer ['picture'] = $pct;
    
    */
    
    fwrite ( $this->hFile, '</offer>' . "\n" );
  }
  
  public function writeLocalDeliveryElement($localDelivery) {
    fwrite ( $this->hFile, '<local_delivery_cost>' . $localDelivery . '</local_delivery_cost>' . "\n" );
  }
  
  public function writeCategoryElement($categoryInfo) {
    fwrite ( $this->hFile, '<category id="' . $categoryInfo ['id'] . '" ' . ($categoryInfo ['parentId'] > 0 ? 'parentId="' . $categoryInfo ['parentId'] . '"' : '') . '>' . $this->to1251 ( $categoryInfo ['name'], 1 ) . '</category>' . "\n" );
  }
  
  public function writeCategoriesElementStart() {
    fwrite ( $this->hFile, '<categories>' . "\n" );
  }
  
  public function writeCategoriesElementFinish() {
    fwrite ( $this->hFile, '</categories>' . "\n" );
  
  }
  
  public function writeCurrenciesElement() {
    fwrite ( $this->hFile, '<currencies>' . "\n" );
    fwrite ( $this->hFile, '<currency id="RUR" rate="1"/>' . "\n" );
    fwrite ( $this->hFile, '</currencies>' . "\n" );
  
  }
  
  public function writeShopElementStart($shopInfo) {
    fwrite ( $this->hFile, '<shop>' . "\n" );
    fwrite ( $this->hFile, '<name>' . $this->to1251 ( $shopInfo ['name'] ) . '</name>' . "\n" );
    fwrite ( $this->hFile, '<company>' . $this->to1251 ( $shopInfo ['company'] ) . '</company>' . "\n" );
    fwrite ( $this->hFile, '<url>' . $this->to1251 ( $shopInfo ['url'] ) . '</url>' . "\n" );
  }
  
  public function writeShopElementFinish($shopInfo) {
    fwrite ( $this->hFile, '</shop>' . "\n" );
  }
  
  public function writeRootFinish() {
    //fwrite ( $this->hFile, '</shop>' . "\n" );
    fwrite ( $this->hFile, '</yml_catalog>' . "\n" );
  }
  
  public function closeFile() {
    fclose ( $this->hFile );
  }

}

function doWriteYMLToTempFile($fName) {
  echo "aaa";
}

?>
