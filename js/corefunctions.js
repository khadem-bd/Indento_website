
	
	//CUSTOM AJAX FUNCTION
	
	function ajaxSubmitFormWithCustomValueAddition(targetForm, variables, variableTypes, variableNames, phpScriptFilePath, successMsg, successMsgID, errorMsgID, afterSuccessAction, afterSuccessRoutingPath){
		  var targetForm = "form"+targetForm;
		  var formData = new FormData($(targetForm)[0]);
		  var i;
		  for (i = 0; i < variables.length; i++) { 
		  	if(variableTypes[i] == "singleData"){
				formData.append(variableNames[i], variables[i]);
			}else if(variableTypes[i] == "array"){
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
			  data = trim11 (data);
			  console.log(data);
			  if(data == successMsg){
				  if(afterSuccessAction == "refresh"){
					  $(errorMsgID).html(data).slideUp(300);
					  $(successMsgID).html(data).slideDown(300).delay(1000).slideUp(300, function(){
						  $(targetForm)[0].reset();
						  location.reload(); 
					  });
				  }else if(afterSuccessAction == "relocate"){
					  $(errorMsgID).html(data).slideUp(300);
					  $(successMsgID).html(data).slideDown(300).delay(1000).slideUp(300, function(){
						  $(targetForm)[0].reset();
						  $(location).attr('href', afterSuccessRoutingPath);
					  });
				  }else{
					  $(errorMsgID).html(data).slideUp(300);
					  $(successMsgID).html(data).slideDown(300).delay(1000).slideUp(300, function(){
						  $(targetForm)[0].reset(); 
					  });
				  }
			  }else{
				  $(errorMsgID).html(data).slideDown(300);
			  }
			}
		  });
		  return false;
		
	}
	
	//REGULAR AJAX FUNCTION
	
	function ajaxSubmitForm(targetForm, phpScriptFilePath, successMsg, successMsgID, errorMsgID, afterSuccessAction, afterSuccessRoutingPath){
		  var targetForm = "form"+targetForm;
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
			  data = trim11 (data);
			  console.log(data);
			  if(data == successMsg){
				  if(afterSuccessAction == "refresh"){
					  $(errorMsgID).html(data).slideUp(300);
					  $(successMsgID).html(data).slideDown(300).delay(1000).slideUp(300, function(){
						  $(targetForm)[0].reset();
						  location.reload(); 
					  });
				  }else if(afterSuccessAction == "relocate"){
					  $(errorMsgID).html(data).slideUp(300);
					  $(successMsgID).html(data).slideDown(300).delay(1000).slideUp(300, function(){
						  $(targetForm)[0].reset();
						  $(location).attr('href', afterSuccessRoutingPath);
					  });
				  }else{
					  $(errorMsgID).html(data).slideUp(300);
					  $(successMsgID).html(data).slideDown(300).delay(1000).slideUp(300, function(){
						  $(targetForm)[0].reset(); 
					  });
				  }
			  }else{
				  $(errorMsgID).html(data).slideDown(300);
			  }
			}
		  });
		  return false;
		
	}
	
	// WHITE SPACE REMOVER IN DATA STRING
	function trim11 (str) {
		str = str.replace(/^\s+/, '');
		for (var i = str.length - 1; i >= 0; i--) {
			if (/\S/.test(str.charAt(i))) {
				str = str.substring(0, i + 1);
				break;
			}
		}
		return str;
	}
