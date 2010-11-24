
function renewPricesFromLoaded(){
  var sprsh = SpreadsheetApp.getActiveSpreadsheet();
  var sheetPrice = sprsh.getSheetByName("Prices");
  var sheetData = sprsh.getSheetByName("Data");
  var sheetData1 = sprsh.getSheetByName("Data");
 
  var titles = sheetData.getRange(1, 1, 1, sheetData.getLastColumn()).getValues();
  
  var codeIndex = titles[0].indexOf('Код');
  var priceIndex = titles[0].indexOf('Цена поставщика');
  var pblcIndex = titles[0].indexOf('sm.публиковать');
  //Logger.log(codeIndex);
  Logger.log(pblcIndex );
  var codeArr = sheetData.getRange(1, codeIndex+1, sheetData.getLastRow(), 1).getValues();
  //Logger.log(codeArr);
  var priceArr = sheetData.getRange(1, priceIndex+1, sheetData.getLastRow(), 1).getValues();
  //Logger.log(priceArr);  
  var pblcArr = sheetData.getRange(1, pblcIndex +1, sheetData.getLastRow(), 1).getValues();
 
  var refPriceArr = sheetPrice.getRange(1, 1, sheetPrice.getLastRow(), 3).getValues();
  //Logger.log(refPriceArr);
  var refPriceArrCode = [];
  var refPriceArrPrice = [];
  var refPriceArrPblc = [];
  
  for (var i=1;i<=refPriceArr.length-1;i++){
    refPriceArrCode[i] = refPriceArr[i][0].toString();
    refPriceArrPrice[i] = refPriceArr[i][1];
    refPriceArrPblc [i] = refPriceArr[i][2];
   }
  //Logger.log(refPriceArrCode);
  //Logger.log(refPriceArrCode[1]);
  //var asdf;
  //Logger.log(asdf=refPriceArrCode.indexOf(7296));
  //Logger.log(refPriceArrPblc );
 
  for (var i=1;i<=codeArr.length-1;i++){
    var cInd = -1;
    pblcArr [i][0]=0;
    var cd2srch = 0 + parseInt(""+codeArr[i]);
    if (cd2srch ==0){
      continue;
    }
    cd2srch = ""+cd2srch;
    cInd = refPriceArrCode.indexOf(cd2srch );
    Logger.log('codeArr[i]=='+codeArr[i]+'|cd2srch=='+cd2srch+"|cInd=="+cInd);
    if (cInd >= 0){
      priceArr[i][0]=refPriceArrPrice[cInd];
      pblcArr [i][0]=refPriceArrPblc [cInd];
    }
  }
  Logger.log(priceArr);
  sheetData1.getRange(1,codeIndex+1,codeArr.length,1).setValues(codeArr);
  sheetData1.getRange(1,priceIndex+1,priceArr.length,1).setValues(priceArr);
  sheetData1.getRange(1,pblcIndex+1,pblcArr.length,1).setValues(pblcArr);
}

function loadMartinsPrices(){
  // The code below will set the values for range A1:D2 to the values in an array.
  //var a = DocsList.find("martins");
  var a = DocsList.getFiles();
  //Logger.log("Files containing the string android are: " + a);
  var mrtnsFile;
  for (var i=0; i<= a.length-1;i++){
    if (a[i].getName() == "martins.ru"){
      mrtnsFile = a[i];
      break;
    }
  }
  Logger.log(mrtnsFile.getName());
  Logger.log(mrtnsFile.getId());
      
  var ss = SpreadsheetApp.openById(mrtnsFile.getId());
  Logger.log(ss.getName());
  var drv = ss.getSheets()[0].getDataRange().getValues();
  Logger.log(drv[0]);
  for (var i=0; i<= drv.length-1;i++){
    drv[i].splice(1,2);
  }

   //Logger.log(a[0].getName());
  //Logger.log(a[1].getName());
  var sprsh = SpreadsheetApp.getActiveSpreadsheet();
  var doc = sprsh.getSheetByName("Prices");
  if (doc){
    doc.getRange(1,1,drv.length,drv[0].length).setValues(drv);
  }
 }

