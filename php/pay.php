<?php
session_start();
require("db.php");

$plan = $_GET['plan'];
$amt = $_GET['amt'];
$user_email = $_SESSION['user'];

$res = $db->query("SELECT * FROM users WHERE email = '$user_email'");
$data = $res->fetch_assoc();
$name = $data['full_name'];

require("../src/Instamojo.php");


$api = new Instamojo\Instamojo('test_7db52d04b7dfe3d7648a8558ecd', 'test_dc10a68831ae11f4dd080edce08', 'https://test.instamojo.com/api/1.1/');
//$api = new Instamojo\Instamojo('e43c9167ad734ba91ac556219ecf9a59','e6a43cb5577873fc4c9ff2519775bc49');

try {
    $response = $api->paymentRequestCreate(array(
        "purpose" => "My drive " . $plan . " plan",
        "amount" => $amt,
        "send_email" => true,
        "email" => $user_email,
        "buyer_name" => $name,
        "redirect_url" => "http://localhost/Testing/PROJECT/php/update_plan.php?plan=".$plan
    ));
    $main_url = $response['longurl'];

    Header("Location:$main_url");
    
 } catch (Exception $e) {
    print('Error: ' . $e->getMessage());
}
?>