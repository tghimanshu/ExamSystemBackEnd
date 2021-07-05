<?php
function getQuestionsFromExcel()
{

	if ($xlsx = SimpleXLSX::parse('book.xlsx')) {
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

function getAnswersFromExcel()
{

	if ($xlsx = SimpleXLSX::parse('book.xlsx')) {
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
