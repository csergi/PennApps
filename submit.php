<?php
//TODO: ensure json processing for all
//TODO: add upvote/downvote functionality

function createID(){//exacts what it will be will be determined later
    //generate something that is random
    $rand = bin2hex(openssl_random_pseudo_bytes(32));
    return $rand;
}

function post($dbh ,$name, $body, $tags, $type, $thread = -1){
	$stmt = $dbh->prepare('INSERT INTO posts (id, name, body, tags, type, thread, time) VALUES (NULL, :name, :body, :tags, :type, :thread, NOW())');//create a way to deal with nonunique ids
	$id = createID();
	$stmt->bindValue(':name', $name);
	$stmt->bindValue(':body', $body);
	$stmt->bindValue(':tags', implode(',' , $tags));
	
	//if its a question type is 0; if its a comment, 1
	if($type == 0){
		$stmt->bindValue(':type', 0);
		$stmt->bindValue(':thread' , $id);	
	}else{
		$stmt->bindValue(':type', 1);
		$stmt->bindValue(':thread', $thread);
	}
	$stmt->execute();
	
	return 0;
}

function searchPosts($dbh , $searchString, $numberOfResults){
	$searchString = preg_replace('/\+s/', ' ', $searchString);
	$searchTerms = explode(' ' , $searchString);
	$threads = array();
	foreach($searchTerms as $term){
		$termStmt = $dbh->prepare('SET @term = :term');
		$termStmt->execute(array($term));
		$sql = 	"SELECT thread FROM posts WHERE
			UPPER(name) LIKE CONCAT('%', UPPER(@term), '%') OR
			UPPER(body) LIKE CONCAT('%', UPPER(@term), '%') OR
			UPPER(tags) LIKE CONCAT('%', UPPER(@term), '%') LIMIT :num";
		$searchStmt = $dbh->prepare($sql);
		$searchStmt->bindValue(':num', $numberOfResults);
		$searchStmt->execute();
		$res = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($res as $record){
			$thread = $record['thread'];
			$threads[$thread] = $thread; //forces uniqueness
		}
	}

	//convert threads to useful data
	$results = array();
	foreach($threads as $thread){
		$stmt = $dbh->prepare('SELECT * FROM posts WHERE type = 0 AND thread = :thread');
		$stmt->bindValue(':thread', $thread);
		$stmt->execute();
		//should just be the question
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$res['tags'] = explode(',' , $res['tags']);
		
		//get the number of views
		$stmt = $dbh->prepare('SELECT COUNT(*) FROM posts WHERE type = 1 AND thread = :thread');
		$stmt->bindValue(':thread', $thread);
		$stmt->execute();
		$count = $stmt->fetch(PDO::FETCH_NUM);
		$res['answerCount'] = $count[0];
		
		//add a number of answers
		array_push($results, $res);
	}
	return $results;
}

function popularPosts($dbh, $numberOfResults){
    $stmt = $dbh->prepare('SELECT * FROM posts WHERE type = 0 ORDER BY views DESC LIMIT :num');
    $stmt->bindValue(':num', $numberOfResults);
    $stmt->execute();
    
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function getThread($dbh , $thread){
	$threadData = array();
	//need the question, need the answers
	
	//increment the view count
	$stmt = $dbh->prepare('UPDATE posts SET views = views + 1 WHERE type = 0 AND thread=:thread');
	$stmt->bindValue(':thread', $thread);
	$stmt->execute();
	
	//get the question
	$qStmt = $dbh->prepare('SELECT * FROM posts WHERE type = 0 AND thread=:thread');
	$qStmt->bindValue(':thread', $thread);
	$qStmt->execute();
	$threadData['question'] = $qStmt->fetch(PDO::FETCH_ASSOC);
	$threadData['question']['tags'] = explode(',' , $threadData['question']['tags']);
	
	//get the number of answers
	$stmt = $dbh->prepare('SELECT COUNT(*) FROM posts WHERE type = 1 AND thread=:thread');
	$stmt->bindValue(':thread', $thread);
	$stmt->execute();
	$res = $stmt->fetch(PDO::FETCH_NUM);
	$threadData['answerCount'] = $res[0];
	
	//get the answers
	$replyStmt = $dbh->prepare('SELECT * FROM posts WHERE type = 1 AND thread=:thread');
	$replyStmt->bindValue(':thread', $thread);
	$replyStmt->execute();
	$res = $replyStmt->fetchAll(PDO::FETCH_ASSOC);
	//determine if there are answers
	if(empty($res)){
	    $threadData['answers'] = 'none';
	}else{
	    foreach($res as $answer){
	        $answer['tags'] = explode(',' , $answer['tags']);
	    }
	    $threadData['answers'] = $res;
	}
	return $threadData;
}

function vote($dbh, $id, $vote){
    if($vote == 'up'){
        $stmt = $dbh->prepare('UPDATE posts SET upvotes = upvotes + 1 WHERE id = :id');
        $stmt->bindValue($id);
        $stmt->execute();
    }else if($vote == 'down'){
        $stmt = $dbh->prepare('UPDATE posts SET downvotes = downvotes + 1 WHERE id = :id');
        $stmt->bindValue($id);
        $stmt->execute();
    }else{
        die(json_encode(array("error" => "invalid JSON")));
    }
    return 0;
}

//start of main request processor

//set up the db conn
require 'db.php';
require_once 'google-api-php-client-2.2.0/vendor/autoload.php';

$authorizedRequest = true;

//handle google requests
$client = new Google_Client();
$client->setAuthConfig('google.json');
$client->setScopes(array(Google_Service_Oauth2::USERINFO_EMAIL,Google_Service_Oauth2::USERINFO_PROFILE));
$name = "";
$email = "";
try{
    $client->setAccessToken($_COOKIE['token']);
    $oauth = new Google_Service_Oauth2($client);
    $usrInfo = $oauth->userinfo->get();
    $lastName = $usrInfo->getFamilyName();
    $firstName = $usrInfo->getGivenName();
    $displayName = $firstName . ' ' . $lastName;
    $email = $usrInfo->getEmail();
}catch(Exception $e){
    $authorizedRequest = false;
}

//get the json of the request
$requestBody = file_get_contents('php://input');
$json = json_decode($requestBody, true) or die(json_encode(array("error"=>"JSON decode failed") ));

//process the request
if($json['request'] == 'post'){
    if($authorizedRequest == false){//if they are not signed in
        die(json_encode(array("error"=>"unauthenticated request")));
    }
    try{
        if($json['type'] == 0){
            $ret = post($dbh, $name, $json['body'], $json['tags'], $json['type']);
        }else if($json['type'] == 1){
            $ret = post($dbh, $name, $json['body'], $json['tags'], $json['type'], $json['thread']);
        }
        $out = array();
        if($ret == 0){
            $out['success'] = true;
            echo json_encode($out);
        }else{
            $out['success'] = false;
            echo json_encode($out);
        }
    }catch(Exception $e){
        $out['sucess'] = false;
        echo json_encode($out);
    }
}else if($json['request'] == 'search'){
    $out = array();
    try{
        $results = searchPosts($dbh , $json['query'], $json['numberOfResults']);
        $out['sucess'] = true;
        $out['query'] = $json['query'];
        $out['results'] = $results;
        echo json_encode($out);
    }catch(Exception $e){
        $out['success'] = false;
        echo json_encode($out);
    }
}else if($json['request'] == 'thread'){
    $out = array();
    try{
        $result = getThread($dbh , $json['thread']);
        $result['success'] = true;
        echo json_encode($result);
    }catch(Exception $e){
        $out['success'] = false;
        echo json_encode($out);
    }
}else if($json['request'] == 'popular'){
    $out = array();
    try{
        $out = popularPosts($dbh, $json['numberOfResults']);
        $out['success'] = true;
        echo json_encode($out);
    }catch(Exception $e){
        $out['success'] = false;
        echo $json_encode($out);
    }
}else if($json['request'] == 'vote'){
    if($authorizedRequest == false){
        die(json_encode(array("error"=>"unauthenticated request")));
    }
    $out = array();
    try{
        $out = vote($dbh, $json['id'] , $json['vote']);
        $out['success'] = true;
        echo json_encode($out);
    }catch(Exception $e){
        $out['success'] = false;
        echo json_encode($out);
    }
}else{
    $out = array();
    $out['success'] = false;
    $out['error'] = 'Invalid API Request';
    echo json_encode($out);
}
?>
