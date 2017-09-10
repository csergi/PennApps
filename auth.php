<?php 
//add the google client to the repo
require 'db.php';
require_once 'google-api-php-client-2.2.0/vendor/autoload.php';
session_start();
$authorizedRequest = true;

//handle google requests
$client = new Google_Client();
$client->setAuthConfig('../google.json');
$client->setScopes(array(Google_Service_Oauth2::USERINFO_EMAIL,Google_Service_Oauth2::USERINFO_PROFILE));
$client->setRedirectUri('http://ec2-34-229-153-170.compute-1.amazonaws.com/auth.php');
if(isset($_GET['code'])){
    $client->authenticate($_GET['code']);
    $stmt = $dbh->prepare('INSERT INTO login VALUES(?,?)');
    $uid = bin2hex(openssl_random_pseudo_bytes(8));
    $client->setAccessToken($client->getAccessToken());
    $oauth = new Google_Service_Oauth2($client);
    $usrInfo = $oauth->userinfo->get();
    $lastName = $usrInfo->getFamilyName();
    $firstName = $usrInfo->getGivenName();
    $name = $firstName . ' ' . $lastName;
    $out = array();
    $stmt->bindValue(1, $uid);
    $stmt->bindValue(2, $name);
    $stmt->execute();
    die('<script>window.location.replace("http://frontend.studentoverflow.com.s3-website-us-east-1.amazonaws.com?uid=' . $uid . '");</script>');
}

//get the json of the request
$requestBody = file_get_contents('php://input');
$json = json_decode($requestBody, true) or die(json_encode(array("error"=>"JSON decode failed") ));

$uid = 0;
if(isset($json['uid'])){$uid = $json['uid'];}
try{
    $stmt = $dbh->prepare('SELECT name FROM login WHERE uid = ?');
    $stmt->bindValue(1, $uid);
    $stmt->execute();

    $name = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $name['name'];
}catch(Exception $e){
    $authorizedRequest = false;
}

//get auth url
if($json['request'] == 'authUrl'){
    $out = array();
    $out['success'] = true;
    $out['url'] = $client->createAuthUrl();
    echo json_encode($out);
}

//logout
if($json['request'] == 'logout'){
    require 'db.php';
    $stmt = $dbh->prepare('DELETE FROM login WHERE uid = ?');
    $stmt->execute(array($uid));
    $response = array();
    $response['success'] = true;
    echo json_encode($response);
}

if($json['request'] == 'userData'){
    if($authorizedRequest == false){
        die(json_encode(array("error"=>"unauthenticated request", "success"=>false)));
    }
    $out['success'] = true;
    $out['name'] = $name;
    echo json_encode($out);
}
?>
