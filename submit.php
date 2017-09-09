<?php
$host = '  ';  //  hostname
$name = '  ';  //  databasename
$usr = '  ';  //  username
$pass = '  ';  //  passw
$dbh = new PDO("mysql:host=$host;dbname=$db", $db_user, $user_pw);

function createID(){//exacts what it will be will be determined later
    //generate something that is random
    $rand = bindec(openssl_random_pseudo_bytes(8));
    return $ran;
}

function post($dbh , $name, $body, $tags, $type, $thread = -1){
	$stmt = $dbh->prepare('REPLACE INTO posts (id, name, body, tags, type, thread, time) VALUES (NULL, :name, :body, :tags, :type, :thread, NOW(), )');//create a way to deal with nonunique ids
	$id = createID();
	$stmt->bindValue(':name', $name);
	$stmt->bindValue(':body', $body);
	$stmt->bindValue(':tags', serialize($tags));
	
	//if its a question type is 0; if its a comment, 1
	if($type == 0){
		$stmt->bindValue(':type', 0);
		$stmt->bindValue(':thread' , $id);	
	}else{
		$stmt->bindValue(':type', 1);
		$stmt->bindValue(':thread', $thread);
	}
	return 0;
}

function searchPosts($dbh , $searchString){
	$searchString = preg_replace('/\+s/', $searchString);
	$searchTerms = explode(' ' , $searchString);
	$threads = array();
	foreach($searchTerms as $term){
		$termStmt = $dbh->prepare('SET @term = :term');
		$termStmt->execute(array($term));
		$sql = 	"SELECT thread FROM posts WHERE
			name LIKE CONCAT('%', @term, '%') OR
			body LIKE CONCAT('%', @term, '%') OR
			tags LIKE CONCAT('%', @term, '%')";
		$searchStmt = $dbh->prepare($sql);
		$searchStmt->execute();
		$res = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($res as $record){
			$thread = $record['thread'];		
		}
	}

	//convert threads to useful data
	$results = array();
	foreach($threads as $thread){
		$stmt = $dbh->prepare('SELECT name,body,tags,thread FROM posts WHERE type = 0 AND thread = :thread');
		$stmt->bindValue(':thread', $thread);
		$stmt->execute();
		//should just be the question
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$res['tags'] = unserialize($res['tags']);
		array_push($results, $res);
	}
	return $results;
}

function getThread($dbh , $thread){
	$threadData = array();
	//need the question, need the answers
	
	//get the question
	$qStmt = $dbh->prepare('SELECT * FROM posts WHERE type = 0 AND thread=:thread');
	$qStmt->bindValue(':thread', $thread);
	$threadData['question'] = $qStmt->fetch(PDO::FETCH_ASSOC);
	$threadData['question']['tags'] = unserialize($threadData['question']['tags']);
	
	//get the answers
	$replyStmt = $dbh->prepare('SELECT name,body,tags,thread FROM posts WHERE type = 1 AND thread=:thread');
	$replyStmt->bindValue(':thread', $thread);
	$replyStmt->execute();
	$res = $replyStmt->fetchAll(PDO::FETCH_ASSOC);
	//determine if there are answers
	if(empty($res)){
	    $threadData['answers'] = 'none';
	}else{
	    foreach($res as $answer){
	        $answer['tags'] = unserialize['tags'];
	    }
	    $threadData['answers'] = $res;
	}
	return $threadData;
}

//start of main request processor

//set up the db conn at top of file

//get the json of the request
$requestBody = file_get_contents('php://input');
$json = json_decode($requestBody, true) or die(json_encode(array("error"=>"JSON decode failed")));

//process the request
if($json['request'] == 'post'){
    try{
        $ret = post($dbh, $json['name'], $json['body'], $json['tags'], $json['type'], $json['thread']);
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
        $results = searchPosts($dbh , $json['searchString']);
        $out['sucess'] = true;
        $out['query'] = $json['searchString'];
        $out['results'] = $results;
        echo json_encode($results);
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
}else{
    $out = array();
    out['success'] = false;
    out['error'] = 'Invalid API Request';
    echo json_encode($out);
}
?>
