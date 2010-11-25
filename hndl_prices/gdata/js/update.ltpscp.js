function renewPricesFromLoadedLaptoscope() {
	var sprsh = SpreadsheetApp.getActiveSpreadsheet();
	var sheetData = sprsh.getSheetByName("Data");
	// var sheetData1 = sprsh.getSheetByName("Data");
	var sheetPrice = sprsh.getSheetByName("short.price.pltscp");

	var titles = sheetData.getRange(1, 1, 1, sheetData.getLastColumn())
			.getValues();

	var codeIndex = titles[0].indexOf('Артикул');
	var priceIndex = titles[0].indexOf('Цена');
	var pblcIndex = titles[0].indexOf('sm.публиковать');

	// Logger.log(codeIndex);
	// Logger.log(pblcIndex );
	var codeArr = sheetData.getRange(1, codeIndex + 1, sheetData.getLastRow(), 1)
			.getValues();
	// Logger.log(codeArr);
	var priceArr = sheetData.getRange(1, priceIndex + 1, sheetData.getLastRow(),
			1).getValues();
	// Logger.log(priceArr);
	var pblcArr = sheetData.getRange(1, pblcIndex + 1, sheetData.getLastRow(), 1)
			.getValues();

	var titles2 = sheetPrice.getRange(1, 1, 1, sheetPrice.getLastColumn())
			.getValues();
	var codeIndex2 = titles2[0].indexOf('Артикул');
	var priceIndex2 = titles2[0].indexOf('Цена');
	
	var codeArr2 = sheetPrice.getRange(1, codeIndex2 + 1, sheetPrice.getLastRow(),
			1).getValues();
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
		Logger.log('codeArr[i]==' + codeArr[i] + '|cd2srch==' + cd2srch + "|cInd=="
				+ cInd);
		if (cInd >= 0) {
			priceArr[i][0] = refPriceArrPrice[cInd];
			pblcArr[i][0] = 1;
			refPriceArrPblc[cInd]=-1;
		}
	}
	
	for ( var i = 1; i <= codeArr2.length - 1; i++) {
		//refPriceArrCode[i] = codeArr2[i][0].toString();
		//refPriceArrPrice[i] = priceArr2[i][1];
		if (refPriceArrPblc[i] < 0){
			priceArr2[i][0] = -priceArr2[i][0];
		}
		//refPriceArrPblc[i] = 1;
	}
	
	Logger.log(priceArr);
	sheetData.getRange(1, codeIndex + 1, codeArr.length, 1).setValues(codeArr);
	sheetData.getRange(1, priceIndex + 1, priceArr.length, 1)
			.setValues(priceArr);
	sheetData.getRange(1, pblcIndex + 1, pblcArr.length, 1).setValues(pblcArr);
	
	sheetPrice.getRange(1, priceIndex2 + 1,sheetPrice.getLastRow(), 1).setValues(priceArr2);
}
