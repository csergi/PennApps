<?php 
//add the google client to the repo
require 'db.php';
require_once 'google-api-php-client-2.2.0/vendor/autoload.php';

$authorizedRequest = true;

//handle google requests
$client = new Google_Client();
$client->setAuthConfig('google.json');
$client->setScopes(array(Google_Service_Oauth2::USERINFO_EMAIL,Google_Service_Oauth2::USERINFO_PROFILE));
$client->setRedirectUri('http://ec2-34-229-153-170.compute-1.amazonaws.com/auth.php');
if(isset($_GET['code'])){
    $client->authenticate($_GET['code']);
    setcookie('token', $client->getAccessToken() ,  time() + 3600 , '/');
    $_COOKIE['token'] = $client->getAccessToken(); //set up for use in this script.
    echo '<script>window.location.href="/index.html";</script>';
}

try{
    $client->setAccessToken($_COOKIE['token']);
}catch(Exception $e){
    $authorizedRequest = false;
}

//get the json of the request
$requestBody = file_get_contents('php://input');
$json = json_decode($requestBody, true) or die(json_encode(array("error"=>"JSON decode failed") ));

//get auth url
if($json['request'] == 'authUrl'){
    $out = array();
    $out['success'] = true;
    $out['url'] = $client->createAuthUrl();
    echo json_encode($out);
}

//logout
if($json['request'] == 'logout' && isset($_COOKIE['token'])){
    require 'db.php';
    $stmt = $dbh->prepare('DELETE FROM auth WHERE oauthToken = ?');
    $stmt->execute(array($_COOKIE['token']));
    $client->revokeToken();
    setcookie('token' , '' , time() + (3600) , '/');
    unset($_COOKIE['token']);
    $response = array();
    $response['success'] = true;
    echo json_encode($response);
}

if($json['request'] == 'userData'){
    if($authorizedRequest == false){
        die(json_encode(array("error"=>"unauthenticated request", "success"=>false)));
    }
    $oauth = new Google_Service_Oauth2($client);
    $usrInfo = $oauth->userinfo->get();
    $lastName = $usrInfo->getFamilyName();
    $firstName = $usrInfo->getGivenName();
    $name = $firstName . ' ' . $lastName;
    $email = $usrInfo->getEmail();
    $out = array();
    $out['success'] = true;
    $out['name'] = $name;
    $out['email'] = $email;
    echo json_encode($out);
}
?>
