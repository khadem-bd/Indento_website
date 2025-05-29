//AJAX FUNCTION WITH CUSTOM VALUE
function ajaxSubmitFormWithCustomValueAddition(targetForm, variables, variableTypes, variableNames, phpScriptFilePath, successMsgText, successMsgTextID, successMsgID, errorMsgTextID, errorMsgID, afterSuccessAction, afterSuccessRoutingPath) {
	var targetForm = "form" + targetForm;
	var formData = new FormData($(targetForm)[0]);
	var i;
	for (i = 0; i < variables.length; i++) {
		if (variableTypes[i] == "singleData") {
			formData.append(variableNames[i], variables[i]);
		} else if (variableTypes[i] == "array") {
			for (var n = 0; n < variables[i].length; n++) {
				formData.append(variableNames[i], variables[i][n]);
			}
		}
	}

	$.ajax({
		url: phpScriptFilePath,
		type: 'POST',
		data: formData,
		async: true,
		cache: false,
		contentType: false,
		processData: false,
		success: function (data) {
			console.log(data);  // Log raw response before parsing
			data = trim11(data);
			if (data == successMsgText) {
				$(errorMsgID).removeClass("show");
				setTimeout(function () {
					$(errorMsgTextID).html("");
				}, 1000);

				$(successMsgTextID).html(data);
				$(successMsgID).addClass("show");

				if (afterSuccessAction == "refresh") {
					setTimeout(function () {
						$(targetForm)[0].reset();
						location.reload();
					}, 1000);
				} else if (afterSuccessAction == "relocate") {
					setTimeout(function () {
						$(targetForm)[0].reset();
						$(location).attr('href', afterSuccessRoutingPath);
					}, 1000);
				} else {
					setTimeout(function () {
						$(targetForm)[0].reset();
					}, 1000);
				}
			} else {
				$(errorMsgTextID).html(data);
				$(errorMsgID).addClass("show");
			}
		}
	});
	return false;
}
//REGULAR AJAX FUNCTION
function ajaxForm(targetForm, phpScriptFilePath, successMsgText, successMsgTextID, successMsgID, errorMsgTextID, errorMsgID, afterSuccessAction, afterSuccessRoutingPath) {
	var targetForm = "form" + targetForm;
	var formData = new FormData($(targetForm)[0]);

	$.ajax({
		url: phpScriptFilePath,
		type: 'POST',
		data: formData,
		async: true,
		cache: false,
		contentType: false,
		processData: false,
		success: function (data) {
			console.log(data);  // Log raw response before parsing
			data = trim11(data);
			if (data == successMsgText) {
				$(errorMsgID).removeClass("show");
				setTimeout(function () {
					$(errorMsgTextID).html("");
				}, 1000);

				$(successMsgTextID).html(data);
				$(successMsgID).addClass("show");

				if (afterSuccessAction == "refresh") {
					setTimeout(function () {
						$(targetForm)[0].reset();
						location.reload();
					}, 3000);
				} else if (afterSuccessAction == "relocate") {
					setTimeout(function () {
						$(targetForm)[0].reset();
						$(location).attr('href', afterSuccessRoutingPath);
					}, 3000);
				} else {
					setTimeout(function () {
						$(targetForm)[0].reset();
					}, 3000);
				}
			} else {
				$(errorMsgTextID).html(data);
				$(errorMsgID).addClass("show");
			}
		}
	});
	return false;
}

// WHITE SPACE REMOVER IN DATA STRING
function trim11(str) {
	str = str.replace(/^\s+/, '');
	for (var i = str.length - 1; i >= 0; i--) {
		if (/\S/.test(str.charAt(i))) {
			str = str.substring(0, i + 1);
			break;
		}
	}
	return str;
}