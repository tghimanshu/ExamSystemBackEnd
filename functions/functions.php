<?php
function getQuestionsFromExcel($fileUrl)
{
	if ($xlsx = SimpleXLSX::parse($fileUrl)) {
		$jsonQ = array();
		$i = 0;
		$qs = array();
		foreach ($xlsx->rows() as $val => $c) {
			if (trim($c[0]) != "") {
				array_push($qs, trim($c[0]));
			}
		}
		$dups = array();
		foreach (array_count_values($qs) as $val => $c) {
			if ($c > 1) array_push($dups, array($val, $c));
		}
		print_r($dups);
		if (count($dups) != 0) {
			foreach ($dups as $key => $val) {
				echo "<h4 class='mb-0'>" . $val[0] . " repeated " . $val[1] . " times</h4> <br />";
			}
			die();
		}
		foreach ($xlsx->rows() as $r => $row) {
			if ($i == 0) {
				$i++;
				continue;
			} else {
				$answers = array($row[1], $row[2], $row[3], $row[5]);
				foreach (array_count_values($answers) as $key => $c) {
					if ($c > 1) die("<h1>Duplicate options in question no: " . $i);
				}
				$jsonQ[$i - 1]['qId'] = $i - 1;
				$jsonQ[$i - 1]['question'] = html_entity_decode($row[0]);
				$jsonQ[$i - 1]['answer1'] = html_entity_decode($row[1]);
				$jsonQ[$i - 1]['answer2'] = html_entity_decode($row[2]);
				$jsonQ[$i - 1]['answer3'] = html_entity_decode($row[3]);
				$jsonQ[$i - 1]['answer4'] = html_entity_decode($row[4]);
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


function getStudentsFromExcel($fileUrl)
{
	if ($xlsx = SimpleXLSX::parse($fileUrl)) {
		$jsonS = array();
		$i = 0;
		foreach ($xlsx->rows() as $r => $row) {
			if ($i == 0 || $i == 1) {
				$i++;
				continue;
			} else {
				$jsonS[$i - 1]['rollNo'] = $row[0];
				$jsonS[$i - 1]['name'] = $row[1];
				$jsonS[$i - 1]['email'] = $row[2];
				$jsonS[$i - 1]['phone'] = $row[3];
				$jsonS[$i - 1]['pass'] = generatePassword();
				$i++;
			}
		}
		// print_r(json_encode($jsonQ));
		return $jsonS;
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

function storeUploadedImages($files, $folder)
{
	// File upload configuration 
	$targetDir = "uploads/" . $folder . "/";
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
	$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
	$fileNames = array_filter($files['name']);
	if (!empty($fileNames)) {
		foreach ($files['name'] as $key => $val) {
			// File upload path 
			$fileName = basename($files['name'][$key]);
			$targetFilePath = $targetDir . $fileName;

			// Check whether file type is valid 
			$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
			if (in_array($fileType, $allowTypes)) {
				// Upload file to server 
				if (!is_dir($targetDir)) {
					mkdir($targetDir);
				}
				move_uploaded_file($files["tmp_name"][$key], $targetFilePath);
			} else {
				$errorUploadType .= $files['name'][$key] . ' | ';
			}
		}
		return $targetDir;
	} else {
		$statusMsg = 'Please select a file to upload.';
	}
}

function sendMail($to_email, $subject, $body)
{
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: Best Programmers  <himnesh234@gmail.com>";
	if (mail($to_email, $subject, $body, $headers)) {
		// echo "Email successfully sent to $to_email...";
	} else {
		echo "Email sending failed to send: $to_email...";
	}
}

function getResult($student_id, $paper_id)
{
	global $con;
	// For Student's Answer
	$query = mysqli_query($con, "SELECT * FROM `answers` WHERE `student_id` = $student_id AND `paper_id` = $paper_id;");
	$data = mysqli_fetch_assoc($query);
	$answers = json_decode(urldecode($data['answers']));
	// For Real Answers
	$query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `ID` = $paper_id;");
	$paperData = mysqli_fetch_assoc($query);
	$a = json_decode(urldecode($paperData['answers']), true);
	// Calculation of marks
	$marks = 0;
	if ($answers) {
		foreach ($answers as $key => $answer) {
			if ($a[$answer->qId]['answer'] == $answer->answer) {
				$marks++;
			}
		}
	}
	return $marks;
}

// function generateResult()
// {
// 	$data = [
// 		['Integer', 123],
// 		['Float', 12.35],
// 		['Percent', '12%'],
// 		['Datetime', '2020-05-20 02:38:00'],
// 		['Date', '2020-05-20'],
// 		['Time', '02:38:00'],
// 		['Datetime PHP', new DateTime('2021-02-06 21:07:00')],
// 		['String', 'Long UTF-8 String in autoresized column'],
// 		['Hyperlink', 'https://github.com/shuchkin/simplexlsxgen'],
// 		['Hyperlink + Anchor', '<a href="https://github.com/shuchkin/simplexlsxgen">SimpleXLSXGen</a>'],
// 		['RAW string', "\0" . '2020-10-04 16:02:00']
// 	];
// SimpleXLSXGen::fromArray($data)->saveAs('datatypes.xlsx') or die("eror");
// }
