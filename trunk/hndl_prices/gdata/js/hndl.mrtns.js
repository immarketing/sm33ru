function onOpen() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var menuEntries = [ {
		name : "Создать список всех таблиц на новом листе",
		functionName : "fillNewSheetWithAllDocs"
	}, {
		name : "Скопировать все данные на новый лист из выбранных",
		functionName : "copyAllInfoFromSelected"
	} ];
	// Logger.log(menuEntries);
	ss.addMenu("Для SuperMarket33.ru", menuEntries);
}

function copyAllInfoFromSelected() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var shortPrice = ss.getSheetByName("short.price");
	var as = ss.getActiveSheet();
	var rng = as.getActiveSelection();
	var vls = rng.getValues();

	var allCodes = [];
	var allPrices = [];
	var allPubl = [];

	for ( var i = 0; i <= vls.length - 1; i++) {
		var sprID = vls[i][0];
		var ssTo = SpreadsheetApp.openById(sprID);
		Logger.log(sprID);
		if (!ssTo) {
			continue;
		}
		var sheetData = ssTo.getSheetByName("Data");
		if (!sheetData) {
			continue;
		}
		var titles = sheetData.getRange(1, 1, 1, sheetData.getLastColumn())
				.getValues();
		var codeIndex = titles[0].indexOf('Код');
		var priceIndex = titles[0].indexOf('sm.цена');
		var publIndex = titles[0].indexOf('sm.публиковать');

		Logger.log(codeIndex);
		var codeData = sheetData.getRange(2, codeIndex + 1,
				sheetData.getLastRow() - 1, 1).getValues();
		allCodes = allCodes.concat(codeData);

		Logger.log(priceIndex);
		var priceData = sheetData.getRange(2, priceIndex + 1,
				sheetData.getLastRow() - 1, 1).getValues();
		allPrices = allPrices.concat(priceData);

		Logger.log(publIndex);
		var publData = sheetData.getRange(2, publIndex + 1,
				sheetData.getLastRow() - 1, 1).getValues();
		allPubl = allPubl.concat(publData);

		/*
		 * var docPriceShort = ssTo.getSheetByName("short.price.plrru"); if
		 * (docPriceShort) { docPriceShort.clearContents();
		 * ssTo.setActiveSheet(docPriceShort); ssTo.deleteActiveSheet(); } var
		 * newPrice = shortPrice.copyTo(ssTo);
		 * newPrice.setName("short.price.plrru"); ssTo.setActiveSheet(newPrice);
		 * ssTo.moveActiveSheet(ssTo.getSheets().length);
		 */
	}
	// var ss = SpreadsheetApp.getActiveSpreadsheet();
	var allDatas = ss.insertSheet("all.datas");

	var titles = [ "code", "model", "description", "price", "ispublished",
			"updatetime" ];
	var ttls = [];
	ttls[0] = titles;
	allDatas.getRange(1, 1, 1, 6).setValues(ttls);

	var allCodes2 = allCodes;
	var allPrices2 = allPrices;
	var allPubl2 = allPubl;

	for ( var i = allCodes.length - 1; i >= 0; i--) {
		if (allCodes[i] == 0) {
			//allCodes2 = 
			allCodes2.splice(i, 1);
			//allPrices2 = 
			allPrices2.splice(i, 1);
			//allPubl2 = 
			allPubl2.splice(i, 1);
		}
	}

	allDatas.getRange(2, 1, allCodes2.length, 1).setValues(allCodes2);
	allDatas.getRange(2, 4, allPrices2.length, 1).setValues(allPrices2);
	allDatas.getRange(2, 5, allPubl2.length, 1).setValues(allPubl2);

}

function putAllDocsOn(sheet) {
	var a = DocsList.getFiles();
	// Logger.log("Files containing the string android are: " + a);
	var lpscFile;
	var allData = [];
	allData[0] = [];
	allData[0][0] = "name";
	allData[0][1] = "id";
	allData[0][2] = "folders";
	for ( var i = 0; i <= a.length - 1; i++) {
		allData[i + 1] = [];
		allData[i + 1][0] = a[i].getName();
		allData[i + 1][1] = a[i].getId();
		var prnts = a[i].getParents();
		var prntsS = "";
		for ( var j = 0; j <= prnts.length - 1; j++) {
			prntsS = prntsS + prnts[j].getName() + ";";
		}
		allData[i + 1][2] = prntsS;
	}
	// Logger.log(lpscFile.getName());
	// Logger.log(lpscFile.getId());

	sheet.getRange(1, 1, allData.length, 3).setValues(allData);

	// return SpreadsheetApp.openById(lpscFile.getId());
}

function fillNewSheetWithAllDocs() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var docsAll = ss.insertSheet("AllDocs");
	putAllDocsOn(docsAll);
	ss.setActiveSheet(docsAll);
}
