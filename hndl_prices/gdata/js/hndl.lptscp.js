function onOpen() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var menuEntries = [
    {name: "Сделать краткий прайс и скопировать в наш прайс", functionName: "handleLaptoscopePrice"}
  ];
  ss.addMenu("Для SuperMarket33.ru", menuEntries);
}
function getLaptoscopePrice() {
	var a = DocsList.getFiles();
	//Logger.log("Files containing the string android are: " + a);
	var lpscFile;
	for ( var i = 0; i <= a.length - 1; i++) {
		if (a[i].getName() == "Ноутбуки laptopscope") {
			lpscFile = a[i];
			break;
		}
	}
	Logger.log(lpscFile.getName());
	Logger.log(lpscFile.getId());
	
  //var ss = SpreadsheetApp.openById(lpscFile.getId());
	

	return SpreadsheetApp.openById(lpscFile.getId());
}

function handleLaptoscopePrice() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var doc = ss.getSheetByName("Sheet 1");
	if (doc) {
		Logger.log(doc.getName());
		//doc.getRange(1,1,drv.length,drv[0].length).setValues(drv);
	}

	//Logger.log(ss.getName());

	var docShort = ss.getSheetByName("short.price");
	Logger.log('[' + docShort + ']');
	if (docShort) {
		docShort.clearContents();
		ss.setActiveSheet(docShort);
		ss.deleteActiveSheet();
	}

	docShort = ss.insertSheet("short.price", 1, {
		template : doc
	});
	var titles = docShort.getRange(1, 1, 1, docShort.getLastColumn()).getValues();
	Logger.log(docShort.getRange(1, 1, 1, docShort.getLastColumn())
			.getNumberFormat());
	//getNumberFormat();

	var nameIndex = titles[0].indexOf('name');
	var nameData = docShort.getRange(1, nameIndex + 1, docShort.getLastRow(), 1)
			.getValues();
	//Logger.log(nameData);

	//titles[0].indexOf('name');

	var descIndex = titles[0].indexOf('description');
	var descData = docShort.getRange(1, descIndex + 1, docShort.getLastRow(), 1)
			.getValues();
	//Logger.log(descData);

	var artclIndex = titles[0].indexOf('Артикул');
	var artclData = docShort
			.getRange(1, artclIndex + 1, docShort.getLastRow(), 1).getValues();
	//Logger.log(artclData);
	var frmts = [];
	for ( var i = artclData.length - 1; i >= 0; i--) {
		frmts[i] = [];
		frmts[i][0] = '@STRING@';
		if (i >= 1) {
			var cd2srch = 0 + parseInt("" + artclData[i][0]);
			artclData[i][0] = "" + cd2srch;
		}
	}

	var priceIndex = titles[0].indexOf('Цена');
	var priceData = docShort
			.getRange(1, priceIndex + 1, docShort.getLastRow(), 1).getValues();
	//Logger.log(priceData);

	docShort.clearContents();

	docShort.getRange(1, 1, artclData.length, 1).setNumberFormats(frmts);
	docShort.getRange(1, 1, artclData.length, 1).setValues(artclData);
	docShort.getRange(1, 2, nameData.length, 1).setValues(nameData);
	docShort.getRange(1, 3, descData.length, 1).setValues(descData);
	docShort.getRange(1, 4, priceData.length, 1).setValues(priceData);

	var lptscpPrice = getLaptoscopePrice();
	if (lptscpPrice) {
		var docPriceShort = lptscpPrice.getSheetByName("short.price.pltscp");
		if (docPriceShort) {
			docPriceShort.clearContents();
			lptscpPrice.setActiveSheet(docPriceShort);
			lptscpPrice.deleteActiveSheet();
		}
		var newPrice=docShort.copyTo(lptscpPrice);
		newPrice.setName("short.price.pltscp");
		lptscpPrice.setActiveSheet(newPrice);
		lptscpPrice.moveActiveSheet(lptscpPrice.getSheets().length);
	}

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