function onOpen2() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var menuEntries = [ 
    {name: "Заполнить текущую строку данными с market.yandex.ru", functionName: "fillSelectedRow2"}
  ];
  ss.addMenu("Для SuperMarket33.ru-2", menuEntries);
}

function fillSelectedRow2(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getActiveSheet();
    
  var lastClm = sheet.getLastColumn();
  
  var actCell = sheet.getActiveCell();
  
  var firstClm = 7;
  var values = sheet.getRange(1, firstClm, 1, lastClm).getValues();
  var arL = values[0].length;
  
  var nm2index = [];

  for (var i = 0; i<= values[0].length -1; i++){
    if (values[0][i] === ""){
      continue;
    }
    nm2index[values[0][i]] = i + firstClm;
  }
  var tp = nm2index['Тип'];
  
  var mrktURLClm = nm2index['market.yandex.ru'];
  
  if (typeof mrktURLClm == 'undefined') {
    Browser.msgBox("Для корректной работы скрипта необходимо добавить колонку 'market.yandex.ru'! Продолжение работы невозможно");
    return
  }
  
  //var mrktURL = sheet.getRange(actCell.getRow(), mrktURLClm, actCell.getRow(), mrktURLClm ).getValue();
  //var rng = sheet.getActiveSelection();
  //Logger.log("actCell.getRow() == "+ actCell.getRow());          
  //Logger.log("actCell.getRowIndex() == "+ actCell.getRowIndex());      
  /*
  Logger.log("mrktURLClm == "+ mrktURLClm);  
      */
  var values2 = sheet.getRange(actCell.getRowIndex(), 1, actCell.getRowIndex(), lastClm).getValues();    
  /*
  Logger.log("values2 == "+ values2);    
  Logger.log("values2[mrktURLClm] == "+ values2[0][mrktURLClm-1]);    
  */
  var mrktURL = values2[0][mrktURLClm-1];
  //Browser.msgBox("0: " + mrktURL);
  
  fillFromYAMARK2 (sheet ,nm2index, mrktURL, values2 );
  
  Browser.msgBox("Выполнено!");
  
}

function getESCStr (pstr) {
  return pstr.replace(/\*/g, "\\*").replace("(", "\\(").replace(")","\\)").replace("/","/");
}

function fillFromYAMARK2(sheet, nm2index, mrktURL, rowValues ){
  var actCell = sheet.getActiveCell();
  var curRow = actCell.getRow();
  
  var response = UrlFetchApp.fetch(mrktURL);
  var rText = response.getContentText();
  rText = rText.replace(/\n|\r|\f/g," ");
  
  
  for (var ipName in nm2index){
    var pName = ipName;
    //Logger.log("ipName === " + ipName);
    
    if (ipName === "market.yandex.ru") {
      continue;
    }

    if (ipName === "Вес") {
      //continue;
    }
    
    //ipName = "Zoom оптический....ифровой";
    
    var pnameesc = getESCStr (ipName );
    
    //pnameesc = "Отношение сигнал/шум \\(аудио\\)";
    //Browser.msgBox("6: "+ pnameesc + " --- " + nm2index[pName] );
    
    //return;
        
    //var myRE = new RegExp(pnameesc + ".*?<td>(.*?)<\\/td>", "i");
    var myRE = new RegExp(pnameesc + "</span></td><td>(.*?)<", "mi");
    
    if ( ( myArray = myRE.exec(rText)) != null ) {
      //Browser.msgBox("7: "+ pnameesc + " --- " + nm2index[ipName] + "===="+ myArray[0] + "+++"+ myArray[1]);
      //Logger.log("7: "+ pnameesc + " --- " + nm2index[ipName] + "===="+ myArray[0] + "+++"+ myArray[1]);
      //sheet.getRange(curRow , nm2index[ipName]).setValue(myArray[1]);
      rowValues[0][nm2index[ipName]-1]=myArray[1];

    }
  }
  //Logger.log("rowValues == "+ rowValues);  
  //Logger.log("actCell.getRowIndex() == "+ actCell.getRowIndex());  
  //Logger.log("rowValues[0].length == "+ rowValues[0].length);  
   //var values2 = sheet.getRange(actCell.getRowIndex(), 1, actCell.getRowIndex(), lastClm).getValues();    
  sheet.getRange(actCell.getRowIndex(), 1, 1, rowValues[0].length ).setValues(rowValues);    
  
}
