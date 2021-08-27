<?php
// Replace with your username
$user = "mischiefmanage";
// Replace with your API KEY (We have sent API KEY on activation email, also available on panel)
$password = "tL54pz9N4MdjCH";
// Replace with the destination mobile Number to which you want to send sms
$msisdn = "9082233297";
// Replace if you have your own Six character Sender ID, or check with our support team.
$sid = "SMSHUB";
// $sid = "SMSHUB";
// Replace with client name
$name = "Himanshu";
// Replace if you have OTP in your template.
$OTP = "6765R";
// Replace with your Message content
$msg = "Dear ram, Your OTP for cloudwireless.in is : 6547";
$msg = urlencode($msg);

$fl = "0";
// if you are using transaction sms api then keep gwid = 2 or if promotional then remove this parameter
$gwid = "2";
// For Plain Text, use "txt" ; for Unicode symbols or regional Languages like hindi/tamil/kannada use "uni"
$type = "txt";

echo "Started";
//--------------------------------------
//step1
$cSession = curl_init();
//step2
curl_setopt($cSession, CURLOPT_URL, "http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?
user=" . $user . "&password=" . $password . "&msisdn=" . $msisdn . "&sid=" . $sid . "&msg=" . $msg . "&fl=0&gwid=2");
curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cSession, CURLOPT_HEADER, false);
//step3
$result = curl_exec($cSession);
echo "getting result" . $result;
//step4
curl_close($cSession);
//step5
echo $result;
