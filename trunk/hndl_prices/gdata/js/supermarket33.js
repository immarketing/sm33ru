function onOpen() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var menuEntries = [
    {name: "Заполнить текущую строку данными с market.yandex.ru", functionName: "fillSelectedRow"},
    {name: "Загрузить цены из прайс-листа Martins.ru", functionName: "loadMartinsPrices"},
    {name: "Обновить цены по загруженным", functionName: "renewPricesFromLoaded"}
  ];
  ss.addMenu("Для SuperMarket33.ru", menuEntries);
}

function fillSelectedRow(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getActiveSheet();
    
  var lastClm = sheet.getLastColumn();
  
  var actCell = sheet.getActiveCell();
  
  var firstClm = 7;
  var values = sheet.getRange(1, firstClm, 1, lastClm).getValues();
  var arL = values[0].length;
  
  var nm2index = [];
  
  for (var i = 0; i<= values[0].length -1; i++){
    nm2index[values[0][i]] = i + firstClm;
  }
  var tp = nm2index['Тип'];
  
  var mrktURLClm = nm2index['market.yandex.ru'];
  
  if (typeof mrktURLClm == 'undefined') {
    Browser.msgBox("Для корректной работы скрипта необходимо добавить колонку 'market.yandex.ru'! Продолжение работы невозможно");
    return
  }
  
  var mrktURL = sheet.getRange(actCell.getRow(), mrktURLClm, actCell.getRow(), mrktURLClm ).getValue();
  
  //Browser.msgBox("0: " + mrktURL);
  
  fillFromYAMARK (sheet ,nm2index, mrktURL );
  
  Browser.msgBox("Выполнено!");
  
}

function getESCStr (pstr) {
  return pstr.replace(/\*/g, "\\*").replace("(", "\\(").replace(")","\\)").replace("/","/");
}

function fillFromYAMARK(sheet, nm2index, mrktURL ){
  var actCell = sheet.getActiveCell();
  var curRow = actCell.getRow();
  
  var response = UrlFetchApp.fetch(mrktURL);
  var rText = response.getContentText();
  rText = rText.replace(/\n|\r|\f/g," ");
  
  for (ipName in nm2index){
    var pName = ipName;
    
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
      sheet.getRange(curRow , nm2index[ipName ]).setValue(myArray[1]);
      
      //return;
    }
  }
  
}

function getYAMARK(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheets()[0];

  //var myValue = Browser.inputBox("Введите URL страницы market.yandex.ru");

  // http://market.yandex.ru/model.xml?modelid=6215947&hid=90633&text=BBK+DV+417SI&srnum=31
  //var response = UrlFetchApp.fetch(myValue);
  var response = UrlFetchApp.fetch("http://market.yandex.ru/model.xml?modelid=6215947&hid=90633&text=BBK+DV+417SI&srnum=31");
  
  var rText = response.getContentText();
  
  //Browser.msgBox(rText);
  
  //var myRE = /td class\=\"label\".*Поддерживаемые форматы.*<td>(.*)\<\/td\>/mig;
  //var myRE = /Поддерживаемые форматы.*?<td>(.*?)<\/td>/i;
  var myRE = new RegExp("Поддерживаемые форматы.*?<td>(.*?)<\\/td>", "i");
  
  if ( ( myArray = myRE.exec(rText)) != null ) {
  //if ( ( myArray = rText.match(myRE)) != null ) {
    Browser.msgBox("Пробелы есть!");
    sheet.getRange(10, 1).setValue(myArray[0]);    
    sheet.getRange(11, 1).setValue(myArray[1]);    
  } else {
    Browser.msgBox("Пробелов нет!");  
  }
 
}
