function onOpen() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	var menuEntries = [ {
		name : "Сделать краткий прайс",
		functionName : "createShortPrice"
	},{
		name : "Скопировать краткий прайс в выделенные таблицы",
		functionName : "copyPriceSheetToDocs"
	},{
		name : "Обновить цены в выделенных прайсах",
		functionName : "renewPricesInDocs"
	}, {
		name : "Создать список всех таблиц на новом листе",
		functionName : "fillNewSheetWithAllDocs"
	}, {
		name : "Скопировать все данные на новый лист из выбранных",
		functionName : "copyAllInfoFromSelected"
	} ];
	// Logger.log(menuEntries);
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
	docShort.deleteColumn(3);
}

function getStampedString(aStr) {
	return "["
			+ Utilities
					.formatDate(new Date(), "GMT+3", "yyyy/MM/dd'|'HH:mm:ss")
			+ "] " + aStr;

}

function renewPricesInDocs() {
	var ss = SpreadsheetApp.getActiveSpreadsheet();
	// var shortPrice = ss.getSheetByName("short.price");
	var as = ss.getActiveSheet();
	var rng = as.getActiveSelection();
	var vls = rng.getValues();

	for ( var i = 0; i <= vls.length - 1; i++) {
		var sprID = vls[i][0];
		var ssTo = SpreadsheetApp.openById(sprID);
		if (!ssTo) {
			var cll = rng.getCell(i + 1, 1);
			cll.setComment(getStampedString("Проблема. Не найден прайс?"));
			continue;
		}
		var docPriceShort = ssTo.getSheetByName("short.price.optvideo");
		var docData = ssTo.getSheetByName("Data");
		if (!ssTo || !docPriceShort || !docData) {
			var cll = rng.getCell(i + 1, 1);
			cll
					.setComment(getStampedString("Проблема. В прайсе не найдены страницы <Data> или <short.price.optvideo>?"));
			continue;
		}

		if (renewPricesFromLoadedOptvideoRu(docData, docPriceShort)) {
			var cll = rng.getCell(i + 1, 1);
			cll
					.setComment(getStampedString("Цены в прайс-листе обновлены по загруженным"));
		} else {
			var cll = rng.getCell(i + 1, 1);
			cll
					.setComment(getStampedString("Цены в прайс-листе НЕ обновлены. Обновление уже было?"));
		}
	}
}

function renewPricesFromLoadedOptvideoRu(dataSheet, priceSheet) {
	var sheetData = dataSheet;
	var sheetPrice = priceSheet;

	var titles = sheetData.getRange(1, 1, 1, sheetData.getLastColumn())
			.getValues();

	var codeIndex = titles[0].indexOf('optvideo_code');
	var priceIndex = titles[0].indexOf('optvideo_price');
	var pblcIndex = titles[0].indexOf('sm.публиковать');

	// Logger.log(codeIndex);
	// Logger.log(pblcIndex );
	var codeArr = sheetData.getRange(1, codeIndex + 1, sheetData.getLastRow(),
			1).getValues();
	// Logger.log(codeArr);
	var priceArr = sheetData.getRange(1, priceIndex + 1,
			sheetData.getLastRow(), 1).getValues();
	// Logger.log(priceArr);
	var pblcArr = sheetData.getRange(1, pblcIndex + 1, sheetData.getLastRow(),
			1).getValues();

	var titles2 = sheetPrice.getRange(1, 1, 1, sheetPrice.getLastColumn())
			.getValues();
	var codeIndex2 = titles2[0].indexOf('optvideo_code');
	var priceIndex2 = titles2[0].indexOf('optvideo_price');

	var codeArr2 = sheetPrice.getRange(1, codeIndex2 + 1,
			sheetPrice.getLastRow(), 1).getValues();
	var priceArr2 = sheetPrice.getRange(1, priceIndex2 + 1,
			sheetPrice.getLastRow(), 1).getValues();

	// var refPriceArr = sheetPrice.getRange(1, 1, sheetPrice.getLastRow(),
	// 3).getValues();
	// Logger.log(refPriceArr);
	var refPriceArrCode = [];
	var refPriceArrPrice = [];
	var refPriceArrPblc = [];

	for ( var i = 1; i <= codeArr2.length - 1; i++) {
		refPriceArrCode[i] = codeArr2[i][0].toString();
		refPriceArrPrice[i] = priceArr2[i][0];
		if (priceArr2[i][0] < 0){
			return 0;
		}
		refPriceArrPblc[i] = 1;
	}
	// Logger.log(refPriceArrCode);
	// Logger.log(refPriceArrCode[1]);
	// var asdf;
	// Logger.log(asdf=refPriceArrCode.indexOf(7296));
	// Logger.log(refPriceArrPblc );

	for ( var i = 1; i <= codeArr.length - 1; i++) {
		var cInd = -1;
		pblcArr[i][0] = 0;
		var cd2srch = 0 + parseInt("" + codeArr[i]);
		if (cd2srch == 0) {
			continue;
		}
		cd2srch = "" + cd2srch;
		cInd = refPriceArrCode.indexOf(cd2srch);
		Logger.log('codeArr[i]==' + codeArr[i] + '|cd2srch==' + cd2srch
				+ "|cInd==" + cInd);
		if (cInd >= 0) {
			priceArr[i][0] = refPriceArrPrice[cInd];
			if (priceArr[i][0] < 0){
				return 0;
			}
			pblcArr[i][0] = 1;
			refPriceArrPblc[cInd] = -1;
		}
	}

	for ( var i = 1; i <= codeArr2.length - 1; i++) {
		// refPriceArrCode[i] = codeArr2[i][0].toString();
		// refPriceArrPrice[i] = priceArr2[i][1];
		if (refPriceArrPblc[i] < 0) {
			priceArr2[i][0] = -priceArr2[i][0];
		}
		// refPriceArrPblc[i] = 1;
	}

	//Logger.log(priceArr);
	sheetData.getRange(1, codeIndex + 1, codeArr.length, 1).setValues(codeArr);
	sheetData.getRange(1, priceIndex + 1, priceArr.length, 1).setValues(
			priceArr);
	sheetData.getRange(1, pblcIndex + 1, pblcArr.length, 1).setValues(pblcArr);

	sheetPrice.getRange(1, priceIndex2 + 1, sheetPrice.getLastRow(), 1)
			.setValues(priceArr2);
	return 1;
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
		var docPriceShort = ssTo.getSheetByName("short.price.optvideo");
		if (docPriceShort) {
			docPriceShort.clearContents();
			ssTo.setActiveSheet(docPriceShort);
			ssTo.deleteActiveSheet();
		}
		var newPrice = shortPrice.copyTo(ssTo);
		newPrice.setName("short.price.optvideo");
		ssTo.setActiveSheet(newPrice);
		ssTo.moveActiveSheet(ssTo.getSheets().length);
		Utilities.formatDate(new Date(), "GMT", "yyyy-MM-dd'T'HH:mm:ss'Z'");
		var cll = rng.getCell(i + 1, 1);
		// Logger.log(cll);
		cll.setComment(getStampedString("Прайс скопирован "));
	}
}

