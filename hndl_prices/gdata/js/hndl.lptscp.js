function handleLaptoscopePrice(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var doc = ss.getSheetByName("Sheet 1");
  if (doc){
  	Logger.log(doc.getName());
    //doc.getRange(1,1,drv.length,drv[0].length).setValues(drv);
  }
  
  //Logger.log(ss.getName());
  
  var docShort = ss.getSheetByName("short.price");
  Logger.log('['+docShort+']');
  if (docShort){
  	docShort.clearContents();
  	ss.setActiveSheet(docShort);
  	ss.deleteActiveSheet();
  }

	docShort = ss.insertSheet("short.price",1,{template:doc});
	var titles = docShort.getRange(1, 1, 1, docShort.getLastColumn()).getValues();
	Logger.log(docShort.getRange(1, 1, 1, docShort.getLastColumn()).getNumberFormat());
	//getNumberFormat();
  
  var nameIndex = titles[0].indexOf('name');
  var nameData = docShort.getRange(1, nameIndex+1, docShort.getLastRow(), 1).getValues();
  //Logger.log(nameData);
  
  //titles[0].indexOf('name');
  
  var descIndex = titles[0].indexOf('description');
  var descData = docShort.getRange(1, descIndex+1, docShort.getLastRow(), 1).getValues();
  //Logger.log(descData);

  var artclIndex = titles[0].indexOf('Артикул');
  var artclData = docShort.getRange(1, artclIndex+1, docShort.getLastRow(), 1).getValues();
  //Logger.log(artclData);
  var frmts=[];
  for (var i = artclData.length -1; i>=0 ; i--){
  	frmts[i]=[];
  	frmts[i][0]='@STRING@';
  }

  var priceIndex = titles[0].indexOf('Цена');
  var priceData = docShort.getRange(1, priceIndex+1, docShort.getLastRow(), 1).getValues();
  //Logger.log(priceData);
  
  docShort.clearContents();
  
  docShort.getRange(1,1,artclData.length,1).setNumberFormats(frmts);
  docShort.getRange(1,1,artclData.length,1).setValues(artclData);
  docShort.getRange(1,2,nameData.length,1).setValues(nameData);
  docShort.getRange(1,3,descData.length,1).setValues(descData);
  docShort.getRange(1,4,priceData.length,1).setValues(priceData);
  
  //@STRING@
  
  //sheetData1.getRange(1,codeIndex+1,codeArr.length,1).setValues(codeArr);
  
  //deleteColumn
  
  /*
  for (var i = titles[0].length -1; i>=0 ; i--){
  	if (!(i in [nameIndex,descIndex,artclIndex,priceIndex])){
  		docShort.deleteColumn(i);
  	}
  }
  */
  
  /*
  if (docShort){
  	Logger.log(docShort.getName());
    //doc.getRange(1,1,drv.length,drv[0].length).setValues(drv);
  }
  */

}