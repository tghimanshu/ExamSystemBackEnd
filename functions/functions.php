<?php
function getQuestionsFromExcel($fileUrl)
{

	if ($xlsx = SimpleXLSX::parse($fileUrl)) {
		$jsonQ = array();
		$i = 0;
		foreach ($xlsx->rows() as $r => $row) {
			if ($i == 0) {
				$i++;
				continue;
			} else {
				$jsonQ[$i - 1]['qId'] = $i - 1;
				$jsonQ[$i - 1]['question'] = $row[0];
				$jsonQ[$i - 1]['answer1'] = $row[1];
				$jsonQ[$i - 1]['answer2'] = $row[2];
				$jsonQ[$i - 1]['answer3'] = $row[3];
				$jsonQ[$i - 1]['answer4'] = $row[4];
				$i++;
			}
		}
		// print_r(json_encode($jsonQ));
		return json_encode($jsonQ);
	} else {
		echo SimpleXLSX::parseError();
	}
}

function getAnswersFromExcel($fileUrl)
{

	if ($xlsx = SimpleXLSX::parse($fileUrl)) {
		$jsonQ = array();
		$i = 0;
		foreach ($xlsx->rows() as $r => $row) {
			if ($i == 0) {
				$i++;
				continue;
			} else {
				$jsonQ[$i - 1]['questionNo'] = $i - 1;
				if (str_contains($row[5], '1')) {
					$jsonQ[$i - 1]['answer'] = 1;
				} else if (str_contains($row[5], '2')) {
					$jsonQ[$i - 1]['answer'] = 2;
				} else if (str_contains($row[5], '3')) {
					$jsonQ[$i - 1]['answer'] = 3;
				} else if (str_contains($row[5], '4')) {
					$jsonQ[$i - 1]['answer'] = 4;
				}
				$i++;
			}
		}
		// print_r(json_encode($jsonQ));
		return json_encode($jsonQ);
	} else {
		echo SimpleXLSX::parseError();
	}
}

function getStudentsFromExcel($fileUrl)
{
	if ($xlsx = SimpleXLSX::parse($fileUrl)) {
		$jsonS = array();
		$i = 0;
		foreach ($xlsx->rows() as $r => $row) {
			if ($i == 0) {
				$i++;
				continue;
			} else {
				$jsonS[$i - 1]['qId'] = $i - 1;
				$jsonS[$i - 1]['question'] = $row[0];
				$jsonS[$i - 1]['answer1'] = $row[1];
				$jsonS[$i - 1]['answer2'] = $row[2];
				$jsonS[$i - 1]['answer3'] = $row[3];
				$jsonS[$i - 1]['answer4'] = $row[4];
				$i++;
			}
		}
		// print_r(json_encode($jsonQ));
		return json_encode($jsonS);
	} else {
		echo SimpleXLSX::parseError();
	}
}

function storeUploadedFile($file)
{
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($file["name"]);
	$uploadOk = 1;
	$excelFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}

	if ($file["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	if ($excelFileType != "xls" && $excelFileType != "xlsx") {
		echo "Sorry, only xls and xlsx files are allowed.";
		$uploadOk = 0;
	}

	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	} else {
		if (move_uploaded_file($file["tmp_name"], $target_file)) {
			echo "The file " . htmlspecialchars(basename($file["name"])) . " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
	return $target_file;
}

function generatePassword()
{
	// * TODO: CHECK IF PASSWORD EXISTS - IF NECESSARY
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array();
	$alphaLength = strlen($alphabet) - 1;
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass);
}
