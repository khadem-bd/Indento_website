<?php


//DATA INSERT FUNCTIONALITY WITH SUCCESS MESSAGE RETURN
function insert($tableName, $colNames, $colValues)
{
	include "connect.php";
	$sql = "INSERT INTO " . $tableName . " (";
	for ($i = 0; $i < count($colNames); $i++) {
		if ($i + 1 == count($colNames)) {
			$sql = $sql . $colNames[$i] . ")";
		} else {
			$sql = $sql . $colNames[$i] . ", ";
		}
	}
	$sql = $sql . " VALUES (";
	for ($i = 0; $i < count($colValues); $i++) {
		if ($i + 1 == count($colValues)) {
			$sql = $sql  . "?)";
		} else {
			$sql = $sql . "?,";
		}
	}

	$pre_stmt = $conn->prepare($sql);
	DynamicBindVariables($pre_stmt, $colValues);
	if ($pre_stmt->execute()) {
		$pre_stmt->close();
		$conn->close();
		return true;
	} else {
		return false;
	}
}

//DATA INSERT FUNCTIONALITY WITH INSERTED ROW ID RETURN
function insert_rowid($tableName, $colNames, $colValues)
{
	include "connect.php";
	$sql = "INSERT INTO " . $tableName . " (";
	for ($i = 0; $i < count($colNames); $i++) {
		if ($i + 1 == count($colNames)) {
			$sql = $sql . $colNames[$i] . ")";
		} else {
			$sql = $sql . $colNames[$i] . ", ";
		}
	}
	$sql = $sql . " VALUES (";
	for ($i = 0; $i < count($colValues); $i++) {
		if ($i + 1 == count($colValues)) {
			$sql = $sql  . "?)";
		} else {
			$sql = $sql . "?,";
		}
	}

	$pre_stmt = $conn->prepare($sql);
	DynamicBindVariables($pre_stmt, $colValues);
	if ($pre_stmt->execute()) {
		$last_id = $conn->insert_id;
		$pre_stmt->close();
		$conn->close();
		return $last_id;
	}
}

//DATA EDIT FUNCTIONALITY WITH SUCCESS MESSAGE RETURN
function update($tableName, $colNames, $colValues, $searchKeyName)
{
	include "connect.php";
	$sql = "UPDATE " . $tableName . " SET ";
	for ($i = 0; $i < count($colNames); $i++) {
		if ($i + 1 == count($colNames)) {
			$sql = $sql . $colNames[$i] . "=?";
		} else {
			$sql = $sql . $colNames[$i] . "=?, ";
		}
	}

	$sql = $sql . " WHERE " . $searchKeyName . "=?";

	$pre_stmt = $conn->prepare($sql);
	DynamicBindVariables($pre_stmt, $colValues);
	if ($pre_stmt->execute()) {
		$pre_stmt->close();
		$conn->close();
		return true;
	} else {
		return false;
	}
}

// DATA DELETE FUNCTIONALITY
function delete($tablename, $searchKeyColName, $searchKey, $deleteType, $fileColName, $folderPath)
{
	if (checkDataExistance($tablename, array($searchKeyColName), array($searchKey))) {
		include "connect.php";
		if ($deleteType == "data") {
			$sql = "DELETE FROM " . $tablename . " WHERE " . $searchKeyColName . "=?";
			$pre_stmt = $conn->prepare($sql);
			if (is_int($searchKey)) {
				// Integer
				$pre_stmt->bind_param("i", $searchKey);
			} elseif (is_float($searchKey)) {
				// Double
				$pre_stmt->bind_param("d", $searchKey);
			} elseif (is_string($searchKey)) {
				// String
				$pre_stmt->bind_param("s", $searchKey);
			} else {
				// Blob and Unknown
				$pre_stmt->bind_param("b", $searchKey);
			}
			if ($pre_stmt->execute()) {
				return true;
			} else {
				return false;
			}
		} else if ($deleteType == "data_with_file") {
			$sql = "SELECT " . $fileColName . " FROM " . $tablename  . " WHERE " . $searchKeyColName . "=?";
			$pre_stmt = $conn->prepare($sql);
			DynamicBindVariables($pre_stmt, array($searchKey));
			$pre_stmt->execute();
			$result = $pre_stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				unlink($folderPath . $row[$fileColName]);
			}

			$sql = "DELETE FROM " . $tablename . " WHERE " . $searchKeyColName . "=?";
			$pre_stmt = $conn->prepare($sql);
			if (is_int($searchKey)) {
				// Integer
				$pre_stmt->bind_param("i", $searchKey);
			} elseif (is_float($searchKey)) {
				// Double
				$pre_stmt->bind_param("d", $searchKey);
			} elseif (is_string($searchKey)) {
				// String
				$pre_stmt->bind_param("s", $searchKey);
			} else {
				// Blob and Unknown
				$pre_stmt->bind_param("b", $searchKey);
			}
			if ($pre_stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
}

// CHECK DATA EXISTANCE FUNCTIONALITY
function checkDataExistance($tablename, $colNames, $colValues)
{
	include "connect.php";
	$sql = "SELECT ";
	for ($i = 0; $i < count($colNames); $i++) {
		if ($i + 1 == count($colNames)) {
			$sql = $sql . $colNames[$i];
		} else {
			$sql = $sql . $colNames[$i] . ", ";
		}
	}
	$sql = $sql . " FROM " . $tablename . " WHERE ";
	for ($i = 0; $i < count($colNames); $i++) {
		if ($i + 1 == count($colNames)) {
			$sql = $sql . $colNames[$i] . "=?";
		} else {
			$sql = $sql . $colNames[$i] . "=? AND ";
		}
	}
	$pre_stmt = $conn->prepare($sql);

	DynamicBindVariables($pre_stmt, $colValues);
	if ($pre_stmt->execute()) {
		$result = $pre_stmt->get_result();
		if ($result->num_rows > 0) {
			return true;
		} else {
			return false;
		}
		$pre_stmt->close();
		$conn->close();
	}
}

//QUERY RETURN VALUE
function getValue($table, $returnValueColName, $SearchKeyName, $searchKey)
{
	include "connect.php";
	$sql = "SELECT " . $returnValueColName . " FROM " . $table . " WHERE " . $SearchKeyName . "=?";
	//return $sql;
	$pre_stmt = $conn->prepare($sql);
	if (is_int($searchKey)) {
		// Integer
		$pre_stmt->bind_param("i", $searchKey);
	} elseif (is_float($searchKey)) {
		// Double
		$pre_stmt->bind_param("d", $searchKey);
	} elseif (is_string($searchKey)) {
		// String
		$pre_stmt->bind_param("s", $searchKey);
	} else {
		// Blob and Unknown
		$pre_stmt->bind_param("b", $searchKey);
	}
	$pre_stmt->execute();
	$result = $pre_stmt->get_result();
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row[$returnValueColName];
	} else {
		return "not found";
	}
}

function getValueWithMultipleKeys($table, $returnValueColName, $colNames, $colValues)
{
	include "connect.php"; // Ensure $conn is defined inside this file

	// Build placeholders for WHERE clause
	$whereClause = [];
	$types = '';
	$params = [];

	for ($i = 0; $i < count($colNames); $i++) {
		$whereClause[] = "`" . $colNames[$i] . "` = ?";
		$types .= is_int($colValues[$i]) ? 'i' : (is_float($colValues[$i]) ? 'd' : 's');
		$params[] = $colValues[$i];
	}

	$sql = "SELECT * FROM `$table` WHERE " . implode(" AND ", $whereClause);

	$pre_stmt = $conn->prepare($sql);
	if (!$pre_stmt) {
		return "Prepare failed: " . $conn->error;
	}

	// Bind parameters dynamically
	$pre_stmt->bind_param($types, ...$params);

	// Execute the query
	$pre_stmt->execute();
	$result = $pre_stmt->get_result();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row[$returnValueColName] ?? "column not found";
	} else {
		return "not found";
	}
}

//RETURN TOTAL ROW DATA
function getRowdata($table, $SearchKeyName, $searchKey)
{
	include "connect.php";
	$sql = "SELECT * FROM " . $table . " WHERE " . $SearchKeyName . "=?";
	$pre_stmt = $conn->prepare($sql);
	if (is_int($searchKey)) {
		// Integer
		$pre_stmt->bind_param("i", $searchKey);
	} elseif (is_float($searchKey)) {
		// Double
		$pre_stmt->bind_param("d", $searchKey);
	} elseif (is_string($searchKey)) {
		// String
		$pre_stmt->bind_param("s", $searchKey);
	} else {
		// Blob and Unknown
		$pre_stmt->bind_param("b", $searchKey);
	}
	$pre_stmt->execute();
	$result = $pre_stmt->get_result();
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row;
	} else {
		return "not found";
	}
}

//QUERY RETURN VALUE
function getValueWithCompanyCode($table, $returnValueColName, $SearchKeyName, $searchKey, $company_code)
{
	include "connect.php";
	$sql = "SELECT " . $returnValueColName . " FROM " . $table . " WHERE company_code=? AND " . $SearchKeyName . "=?";
	$pre_stmt = $conn->prepare($sql);

	// Check if preparation was successful
	if (!$pre_stmt) {
		return "Error preparing statement: " . $conn->error;
	}

	// Determine the types of the parameters (company_code should be a string)
	if (is_int($searchKey)) {
		$type = "is"; // i for int, s for string (assuming company_code is string)
	} elseif (is_float($searchKey)) {
		$type = "ds"; // d for double, s for string
	} elseif (is_string($searchKey)) {
		$type = "ss"; // s for both string parameters
	} else {
		$type = "bs"; // b for blob, s for string
	}

	// Bind parameters
	$pre_stmt->bind_param($type, $company_code, $searchKey);
	$pre_stmt->execute();
	$result = $pre_stmt->get_result();

	// Check if any rows returned
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row[$returnValueColName];
	} else {
		return "not found";
	}
}


//POSTED VALUE STERILIZATION	
function sterilizeValue($value)
{
	include "connect.php";
	$sterilized = stripslashes($value);
	$sterilized = mysqli_real_escape_string($conn, $sterilized);
	return $sterilized;
}


//DYNAMIC BIND PARAM FOR PREPARED STATEMENT

function DynamicBindVariables($stmt, $params)
{
	if ($params != null) {
		// Generate the Type String (eg: 'issisd')
		$types = '';
		foreach ($params as $param) {
			if (is_int($param)) {
				// Integer
				$types .= 'i';
			} elseif (is_float($param)) {
				// Double
				$types .= 'd';
			} elseif (is_string($param)) {
				// String
				$types .= 's';
			} else {
				// Blob and Unknown
				$types .= 'b';
			}
		}

		// Add the Type String as the first Parameter
		$bind_names[] = $types;

		// Loop thru the given Parameters
		for ($i = 0; $i < count($params); $i++) {
			// Create a variable Name
			$bind_name = 'bind' . $i;
			// Add the Parameter to the variable Variable
			$$bind_name = $params[$i];
			// Associate the Variable as an Element in the Array
			$bind_names[] = &$$bind_name;
		}

		// Call the Function bind_param with dynamic Parameters
		call_user_func_array(array($stmt, 'bind_param'), $bind_names);
	}
	return $stmt;
}

// SINGLE FILE UPLOAD
function singleFileUpload($file, $uploadPath)
{
	$imgFileName = $file["name"];
	$imgNewFileName = preg_replace('/\s+/', '', $imgFileName);
	$status = false;
	$errorMsg = "";

	// Check if file is uploaded
	if (isset($file['error']) && $file['error'] != UPLOAD_ERR_OK) {
		$errorMsg = "Upload error: " . $file['error'];
		$status = false;
	}

	// Check if the file is successfully moved
	if (move_uploaded_file($file['tmp_name'], $uploadPath . $imgNewFileName)) {
		$errorMsg = "File uploaded successfully!";
		$status = true;
	} else {
		$errorMsg = "Failed to move uploaded file!";
	}

	$data = array("imageFileName" => $imgNewFileName, "status" => $status, "error" => $errorMsg);
	return $data;
}


// MULTIPLE FILES UPLOAD
function multipleFilesUpload($files, $uploadPath, $tableName, $colNames, $colValues)
{
	if (isset($files) && !empty($files)) {
		$no_files = count($files['name']);
		for ($i = 0; $i < $no_files; $i++) {
			if ($files["error"][$i] > 0) {
				echo "Error: " . $files["error"][$i] . "<br>";
			} else {
				$imgFileName = $files["name"][$i];
				$imgNewFileName = preg_replace('/\s+/', '', $imgFileName);

				if (file_exists($uploadPath . $files["name"][$i])) {
					echo 'File: ' . $files["name"][$i] . ' already exists';
				} else {
					move_uploaded_file($files["tmp_name"][$i], $uploadPath . $files["name"][$i]);
					rename($uploadPath . $imgFileName, $uploadPath . $imgNewFileName);
					$arr = array();
					for ($n = 0; $n < count($colValues); $n++) {
						if ($colValues[$n] != "Insert_Dynamic_Image_Value") {
							array_push($arr, $colValues[$n]);
						} else {
							array_push($arr, $imgNewFileName);
						}
					}
					insert($tableName, $colNames, $arr);
				}
			}
		}
		return true;
	} else {
		return false;
	}
}

//CHECK IF INPUT TYPE FILE HAS SELECTED ANY FILE
function fileSelected($file)
{
	if ($file["size"] > 0) {
		return true;
	} else {
		return false;
	}
}

//FILE MOVE ACTION
function moveFile($oldroute, $newRoute)
{
	if (rename($oldroute, $newRoute)) {
		return true;
	} else {
		return false;
	}
}

//DELETE A FILE
function deletefile($filePath)
{
	// Check if the file exists before attempting to delete it
	if (file_exists($filePath)) {
		if (unlink($filePath)) {
			return "File deleted successfully";
		} else {
			return "Error: Could not delete the file";
		}
	} else {
		return "Error: File does not exist";
	}
}

//validate image file
function validateFile($imageFile)
{
	// Allowed file extensions
	$allowedExtensions = ['jpg', 'png', 'webp', 'svg'];

	// Extract the file extension
	$fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));

	// Check if the file has a valid extension
	if (!in_array($fileExtension, $allowedExtensions)) {
		return "Invalid file type. Only JPG, PNG WEBP and SVG file types are allowed.";
	}

	// Check if the file size is less than 1 MB
	if ($imageFile['size'] > 1 * 1024 * 1024) { // 1 MB in bytes
		return "The file size is too large. File size should be less than 1 MB.";
	}

	// // Check the image dimensions
	// $imageDimensions = getimagesize($imageFile['tmp_name']);
	// if (!$imageDimensions) {
	// 	return "File is not a valid image.";
	// }

	// $width = $imageDimensions[0];
	// $height = $imageDimensions[1];

	// if ($width !== 130 || $height !== 130) {
	// 	return "Image file dimensions should be width: 130px and height: 130px.";
	// }

	// If all checks pass, return success message
	return "File is valid";
}



// FILE REPLACE ACTION
function fileReplace($file, $uploadPath, $table, $colName, $searchKeyName, $searchKey)
{
	include "connect.php";
	$imgFileName = $file["name"];
	$imgNewFileName = preg_replace('/\s+/', '', $imgFileName);

	$sql = "SELECT " . $colName . " FROM " . $table . " WHERE " . $searchKeyName . "=?";
	$pre_stmt = $conn->prepare($sql);
	$pre_stmt->bind_param("i", intval($searchKey));
	$pre_stmt->execute();
	$result = $pre_stmt->get_result();
	$row = $result->fetch_assoc();
	$oldfileName = $row[$colName];
	if (move_uploaded_file($file['tmp_name'], $uploadPath . $file['name'])) {
		unlink($uploadPath . $oldfileName);
		rename($uploadPath . $imgFileName, $uploadPath . $imgNewFileName);
		update($table, array($colName), array($imgNewFileName, intval($searchKey)), $searchKeyName);
		return true;
	} else {
		return false;
	}
}

//ENCRYPT DATA
function encrypt($value)
{
	$val = base64_encode($value);
	$val = reverse($val);
	return $val;
}

//DECRYPT DATA
function decrypt($value)
{
	$val = reverse($value);
	$val = base64_decode($val);
	return $val;
}

//STRING REVERSE
function reverse($str)
{
	return strrev($str);
}

//CREATE FOLDER
function createFolder($route, $name)
{
	$foldername = preg_replace('/\s+/', '', $name);
	$foldername = strtolower($foldername);
	$dirpath = $route . $foldername;
	if (!file_exists($dirpath)) {
		mkdir($dirpath, 0777, true);
	}
}

//DELETE FILE OR DIRECTORY
function deleteDirectory($dirPath)
{
	if (!is_dir($dirPath)) {
		return false; // Return false if the path is not a directory
	}

	$objects = scandir($dirPath);
	foreach ($objects as $object) {
		if ($object != "." && $object != "..") {
			$path = $dirPath . DIRECTORY_SEPARATOR . $object;
			if (is_dir($path)) {
				// Recursively delete subdirectory
				if (!deleteDirectory($path)) {
					return false; // If subdirectory deletion fails, return false
				}
			} else {
				// Delete file
				if (!unlink($path)) {
					return false; // If file deletion fails, return false
				}
			}
		}
	}

	// Attempt to delete the main directory
	return rmdir($dirPath); // Return true if successful, false otherwise
}



//GET FOLDER NAME
function getFolderName($name)
{
	$foldername = preg_replace('/\s+/', '', $name);
	$foldername = strtolower($foldername);
	return $foldername;
}

function login($auth_table, $userID_colName, $user_ID_colValue, $auth_password_colName, $password_table, $password_colName, $password_table_search_key_name, $user_inserted_pwd)
{

	// $user_ID_colValue = encrypt($user_ID_colValue);
	$user_inserted_pwd = encrypt($user_inserted_pwd);

	if (checkDataExistance($auth_table, array($userID_colName), array($user_ID_colValue))) { // check if this user exists

		$id = getValue($auth_table, "id", $userID_colName, $user_ID_colValue);
		$pwd_1 = getValue($auth_table, $auth_password_colName, $userID_colName, $user_ID_colValue);
		$pwd_2 = getValue($password_table, $password_colName, $password_table_search_key_name, $id);
		$password = $pwd_1 . $pwd_2;
		$password = decrypt($password);
		$password = sterilizeValue($password);
		// return $password;
		if ($password === decrypt($user_inserted_pwd)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function string_reverse($str)
{
	return strrev($str);
}

function generateRandomString($length = 10)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}


function getPagination($tableName, $company_code, $orderSequence, $id, $nextBtnID, $prevBtnID)
{
	include "connect.php";
	$mainID = $id;
	$sql = "SELECT * FROM " . $tableName . " WHERE company_code='" . $company_code . "' ORDER BY id " . $orderSequence; // added a space before WHERE
	$result  = $conn->query($sql);
	$counter = 0;
	$html = "";
	$html .= "<div class='pagination-wrapper'>
					<a id='" . $prevBtnID . "' class='paginationPrev disabled' href='javascript:void(0)'>Prev</a> 
					<a id='" . $nextBtnID . "' class='paginationNext' href='javascript:void(0)'>Next</a>
				  </div>";

	$html .= "<ul class='pagination'>";
	if ($mainID == "null") {
		$firsttime = true;
		while ($row = $result->fetch_assoc()) {
			if ($counter % 10 === 0) {
				$id = $row['id'];
				if ($firsttime) {
					$html .= "<li class='currentPosition'><a data-id='" . $id . "' href='javascript:void(0)'>" . $id . "</a></li>";
					$firsttime = false;
				} else {
					$html .= "<li><a data-id='" . $id . "' href='javascript:void(0)'>" . $id . "</a></li>";
				}
			}
			$counter++;
		}
	} else {
		while ($row = $result->fetch_assoc()) {
			if ($counter % 10 === 0) {
				$id = $row['id'];
				if ($mainID == $row['id']) {
					$html .= "<li class='currentPosition'><a data-id='" . $id . "' href='javascript:void(0)'>" . $id . "</a></li>";
				} else {
					$html .= "<li><a data-id='" . $id . "' href='javascript:void(0)'>" . $id . "</a></li>";
				}
			}
			$counter++;
		}
	}
	$html .= "</ul>";
	return $html;
}

function basicTabularDataListing($html_tableheaders, $db_tablename, $db_ColNames, $orderColumnName, $orderBySequence, $company_code, $title, $paginationID, $paginationDirection, $searchKey, $userID, $createPermissionColName, $editPermissionColName, $deletePermissionColName)
{

	//child helper functions;
	function genericCreateAuthorization($status, $btnID, $btnText, $modalID)
	{
		if ($status == "Yes" || $status == "yes") {
			return "<a id='" . $btnID . "' href='javascript:void(0)' data-target='" . $modalID . "' class='btn btn-primary'>" . $btnText . "</a>";
		}
	}

	function genericEditAuthorization($status, $linkClass, $dataKey, $modalID)
	{
		if ($status == "Yes" || $status == "yes") {
			return "<a class='icons edit-icon " . $linkClass . "' data-key='" . $dataKey . "' data-target='" . $modalID . "' href='javascript:void(0)'>
								<i data-feather='edit'></i>
							</a>";
		}
	}

	function genericDeleteAuthorization($status, $dataURL, $dataKey)
	{
		if ($status == "Yes" || $status == "yes") {
			return "<a class='icons delete-icon btn-delete' href='javascript:void(0)' data-url='" . $dataURL . "' data-key='" . $dataKey . "'>
							<i data-feather='x-circle'></i>
						</a>";
		}
	}

	include "connect.php";
	$createBtnText = "Create New " . $title;
	$title = strtolower(str_replace(' ', '', $title));
	$createBtnID = "createNew_" . $title;
	$modalID = $title . "_createNewModal";
	$searchInputID = "searchInput_" . $title;
	$searchBtnID = "searchBtn_" . $title;
	$editClassName = "edit_" . $title;
	$editModalID = $title . "_editModal";
	$nextBtnID = $title . "nextBtn";
	$prevBtnID = $title . "prevBtn";
	$tableID = $title . "_table";
	$html = "";

	$createStatus = getValue("user_permission_table", $createPermissionColName, "user_id", $userID);
	$editStatus = getValue("user_permission_table", $editPermissionColName, "user_id", $userID);
	$deleteStatus = getValue("user_permission_table", $deletePermissionColName, "user_id", $userID);

	if ($searchKey == "null") {
		if ($paginationID == "null") {
			$html = $html . "<div class='fileTableSearchContainer'>
							<div class='row'>
								<div class='col-sm-12 col-md-6 col-lg-6 text-left'>
									" . genericCreateAuthorization($createStatus, $createBtnID, $createBtnText, $modalID) . "
								</div>
								<div class='col-sm-12 col-md-6 col-lg-6 text-right'>
									<div class='form-group'>
										<input id='" . $searchInputID . "' type='text' class='form-control' placeholder='Search Record'>
										<a id='" . $searchBtnID . "' class='search-btn' href='javascript:void(0)'><i data-feather='search'></i></a>
									</div>
								</div>
							</div>
						</div>";

			$html = $html . "<div class='file-list-table'><table id='" . $tableID . "' class='table table-bordered'><thead><tr>";
			for ($n = 0; $n < count($html_tableheaders); $n++) {
				$html = $html . "<th>" . $html_tableheaders[$n] . "</th>";
			}
			$html = $html . "</thead>";
			$sql = "SELECT * FROM " . $db_tablename . " WHERE company_code='$company_code' ORDER BY " . $orderColumnName . " " . $orderBySequence . " LIMIT 10";
			$result  = $conn->query($sql);
			$rowcount = mysqli_num_rows($result);
			if ($rowcount > 0) {
				$html = $html . "<tbody>";
				while ($row = $result->fetch_assoc()) {
					$html = $html . "<tr>";
					for ($n = 0; $n < count($db_ColNames); $n++) {
						$html = $html . "<td>" . $row[$db_ColNames[$n]] . "</td>";
					}

					$html = $html .
						"<td>" . genericEditAuthorization($editStatus, $editClassName, encrypt($row['id']), $editModalID) . "</td>";
					$uniqueKey = $row['id'] . "-" . $db_tablename;
					$uniqueKey = encrypt($uniqueKey);
					$html = $html .
						"<td>" . genericDeleteAuthorization($deleteStatus, "controllers/common/delete-data.php", $uniqueKey) . "</td>
						</tr>";
				}
				$html = $html . "</tbody></table></div>";
				$html = $html . getPagination($db_tablename, $company_code, $orderBySequence, $paginationID, $nextBtnID, $prevBtnID);
			} else {
				$html = $html . "</table></div>";
			}
		} else {
			$sql = "";
			if ($paginationDirection == "next") {
				$sql = "SELECT * FROM " . $db_tablename . " WHERE company_code='$company_code' AND id < '" . $paginationID . "' ORDER BY " . $orderColumnName . " " . $orderBySequence . " LIMIT 10";
			} else if ($paginationDirection == "prev") {
				// Reverse the order for fetching previous records
				$reverseOrder = $orderBySequence == "ASC" ? "DESC" : "ASC";
				$sql = "SELECT * FROM " . $db_tablename . " WHERE company_code='$company_code' AND id > '" . $paginationID . "' ORDER BY " . $orderColumnName . " " . $reverseOrder . " LIMIT 10";
			}

			$result  = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$html = $html . "<tr>";
				for ($n = 0; $n < count($db_ColNames); $n++) {
					$html = $html . "<td>" . $row[$db_ColNames[$n]] . "</td>";
				}
				$uniqueKey = $row['id'] . "-" . $db_tablename;
				$uniqueKey = encrypt($uniqueKey);
				$html = $html .
					"<td>" . genericEditAuthorization($editStatus, $editClassName, encrypt($row['id']), $editModalID) . "</td>";

				$html = $html .
					"<td>" . genericDeleteAuthorization($deleteStatus, "controllers/common/delete-data.php", $uniqueKey) . "</td></tr>";
			}
		}
	} else {
		$sql = "SELECT * FROM " . $db_tablename . " WHERE company_code='$company_code' AND (";

		for ($n = 0; $n < count($db_ColNames); $n++) {
			if ($n + 1 == count($db_ColNames)) {
				$sql = $sql . $db_ColNames[$n] . " LIKE '%$searchKey%')";
			} else {
				$sql = $sql . $db_ColNames[$n] . " LIKE '%$searchKey%' OR ";
			}
		}

		$sql = $sql . " ORDER BY id DESC";
		$result  = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$html = $html . "<tr>";
			for ($n = 0; $n < count($db_ColNames); $n++) {
				$html = $html . "<td>" . $row[$db_ColNames[$n]] . "</td>";
			}
			$uniqueKey = $row['id'] . "-" . $db_tablename;
			$uniqueKey = encrypt($uniqueKey);
			$html = $html .
				"<td>" . genericEditAuthorization($editStatus, $editClassName, encrypt($row['id']), $editModalID) . "</td>";

			$html = $html .
				"<td>" . genericDeleteAuthorization($deleteStatus, "controllers/common/delete-data.php", $uniqueKey) . "</td></tr>";
		}
	}

	$data = array(
		"html" => $html,
		"createBtnID" => $createBtnID,
		"searchInputID" => $searchInputID,
		"searchBtnID" => $searchBtnID,
		"editClassName" => $editClassName,
		"nextBtnID" => $nextBtnID,
		"prevBtnID" => $prevBtnID,
		"tableID" => $tableID,
	);
	return $data;
}


function getCompanyInitials($company_code)
{
	include "connect.php";
	$name = getValue("client_company", "company_name", "company_code", $company_code);
	$initials = "";
	$words = explode(' ', $name);
	foreach ($words as $word) {
		$initials .= strtoupper($word[0]);
	}
	return $initials;
}

function moduleCreateAuthorization($status, $btnID, $btnText)
{
	if ($status == "Yes" || $status == "yes") {
		return '<a id="' . $btnID . '" href="javascript:void(0)" class="btn btn-primary">' . $btnText . '</a>';
	}
}

function moduleEditAuthorization($status, $linkClass, $dataKey)
{
	if ($status == "Yes" || $status == "yes") {
		return '<a class="icons edit-icon ' . $linkClass . '" data-key="' . $dataKey . '" href="javascript:void(0)">
						<i data-feather="edit"></i>
					</a>';
	}
}

function moduleDeleteAuthorization($status, $dataURL, $dataKey)
{
	if ($status == "Yes" || $status == "yes") {
		return '<a class="icons delete-icon btn-delete" href="javascript:void(0)" data-url="' . $dataURL . '" data-key="' . $dataKey . '">
						<i data-feather="x-circle"></i>
					</a>';
	}
}

function getNextSerialNo($company_code, $target_year)
{
	include "connect.php"; // Assumes $mysqli is initialized

	// Escape inputs
	$company_code = $conn->real_escape_string($company_code);
	$target_year = (int) $target_year;

	$sql = "
			SELECT serial_number
			FROM file_serial_number
			WHERE company_code = '$company_code'
			AND year = (
				SELECT MAX(year)
				FROM file_serial_number
				WHERE year <= $target_year AND company_code = '$company_code'
			)
			ORDER BY serial_number DESC
			LIMIT 1
		";

	$result = $conn->query($sql);

	if ($result && $row = $result->fetch_assoc()) {
		return $row['serial_number'] + 1;
	} else {
		return 1;
	}
}

function validateFileNumber($file_no)
{
	$parts = explode("/", $file_no);
	if (count($parts) === 3) {
		if ($parts[2] == null || $parts[2] == "") {
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

function fileSerialEntry($file_no, $company_code)
{
	$parts = explode("/", $file_no);
	$serial = $parts[1];
	$year = $parts[2]; 

	insert("file_sereal", array("serial_no", "company_code"), array($serial, $company_code));
	insert("file_serial_number", array("serial_number", "year", "company_code"), array($serial, $year, $company_code));
}

function deleteSerialNo($file_no, $company_code){
	$parts = explode("/", $file_no);
	$serial = $parts[1];
	$year = $parts[2]; 

	if(removeSerial($serial, null, $company_code) && removeSerial($serial, $year, $company_code)){
		return true;
	}else{
		return false;
	}
}

function removeSerial($serial, $year, $company_code) {
	include "connect.php";
	if($year == null || $year == ""){
		// Prepare and bind
		$stmt = $conn->prepare("DELETE FROM file_sereal WHERE serial_no = ? AND company_code = ?");
		$stmt->bind_param("is", $serial, $company_code);
		// Execute the statement
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
		// Close the statement and connection
		$stmt->close();
		$conn->close();
	}else{
		// Prepare and bind
		$stmt = $conn->prepare("DELETE FROM file_serial_number WHERE serial_number = ? AND year=? AND company_code = ?");
		$stmt->bind_param("iis", $serial, $year, $company_code);
		// Execute the statement
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
		// Close the statement and connection
		$stmt->close();
		$conn->close();
	}
	
}

function deleteCorrespondingLC($fileNo, $company_code) {
	include "connect.php";

	$sql = "DELETE FROM lc_opening WHERE file_no = ? AND company_code = ?";
	$stmt = $conn->prepare($sql);

	if ($stmt === false) {
		die("Prepare failed: " . $conn->error);
	}

	$stmt->bind_param("ss", $fileNo, $company_code);

	if ($stmt->execute()) {
		return true;
	} else {
		return false;
	}

	$stmt->close();
	$conn->close();
}

function deleteCorrespondingShipment($fileNo, $company_code) {
	include "connect.php";

	$sql = "DELETE FROM debit_note_invoice WHERE file_no = ? AND company_code = ?";
	$stmt = $conn->prepare($sql);

	if ($stmt === false) {
		die("Prepare failed: " . $conn->error);
	}

	$stmt->bind_param("ss", $fileNo, $company_code);

	if ($stmt->execute()) {
		return true;
	} else {
		return false;
	}

	$stmt->close();
	$conn->close();
}

function getFileSourceType($file_no, $company_code){
	include "connect.php";
	$sql = "SELECT * FROM file_generate WHERE file_no='$file_no' AND company_code='$company_code'";
	$result  = $conn->query($sql);
    $row = $result->fetch_assoc();
	return $row['source_type'];
}

function getTotalExceptedQty($fileNo, $company_code){
	include "connect.php";
	$file_id = getValueWithMultipleKeys("file_generate", "id", array("file_no", "company_code"), array($fileNo, $company_code));
	$total_expected_Qty = 0.00;
	$sql = "SELECT * FROM profoma_invoice WHERE parental_id='$file_id'";
	$result  = $conn->query($sql);
    while($row = $result->fetch_assoc()){
		$total_expected_Qty = $total_expected_Qty + number_format($row['quantity'], 2, '.', '');
	}
	return $total_expected_Qty;
}

function isValidEmail($email) {
    // Remove illegal characters from email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function containsSlang($message) {
    // Define a list of slang or inappropriate words
    $slangWords = array(
        'wtf', 'omg', 'lmao', 'rofl', 'brb', 'smh', 'btw', 'idk',
        'af', 'tbh', 'fml', 'yolo', 'bae', 'lit', 'savage', 'thot', 'baal', 
		'chudirbhai', 'chudir bhai', 'khankirpola', 'magi', 'khankimagi', 
		'bessha', 'abaal', 'hauwwa', 'chut maranirpola', 'laura', 'bhoda',
		'voda', 'putki', 'dhon', 'bara', 'shauwwa', 'shauwwa choda', 'choda',
		'chudi', 'lingo'
    );

    // Normalize the message to lowercase
    $message = strtolower($message);

    // Tokenize the message by words
    $words = preg_split('/\W+/', $message, -1, PREG_SPLIT_NO_EMPTY);

    // Check if any slang word is present
    foreach ($words as $word) {
        if (in_array($word, $slangWords)) {
            return true;
        }
    }

    return false;
}

?>