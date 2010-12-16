function onOpen() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var menuEntries = [ {
		name : "Сделать краткий прайс",
		functionName : "createShortPrice"
	}, {
		name : "Скопировать краткий прайс в выделенные таблицы",
		functionName : "copyPriceSheetToDocs"
	}, {
		name : "Создать список всех таблиц на новом листе",
		functionName : "fillNewSheetWithAllDocs"
	} ];
	Logger.log(menuEntries);
	ss.addMenu("Для SuperMarket33.ru", menuEntries);
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

function copyPriceSheetToDocs() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var shortPrice = ss.getSheetByName("short.price");
	var as = ss.getActiveSheet();
	var rng = as.getActiveSelection();
	var vls = rng.getValues();

	for ( var i = 0; i <= vls.length - 1; i++) {
		var sprID = vls[i][0];
		var ssTo = SpreadsheetApp.openById(sprID);
		if (!ssTo) {
			continue;
		}
		var docPriceShort = ssTo.getSheetByName("short.price.plrru");
		if (docPriceShort) {
			docPriceShort.clearContents();
			ssTo.setActiveSheet(docPriceShort);
			ssTo.deleteActiveSheet();
		}
		var newPrice = shortPrice.copyTo(ssTo);
		newPrice.setName("short.price.plrru");
		ssTo.setActiveSheet(newPrice);
		ssTo.moveActiveSheet(ssTo.getSheets().length);
	}
}

function createShortPrice() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var doc = ss.getSheetByName("Sheet 1");
	if (doc) {
		Logger.log(doc.getName());
		// doc.getRange(1,1,drv.length,drv[0].length).setValues(drv);
	}
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
	docShort.deleteColumn(5);
	docShort.deleteColumn(2);
}
