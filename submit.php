<?php
$db_host = '  ';  //  hostname
$db_name = '  ';  //  databasename
$db_user = '  ';  //  username
$user_pw = '  ';  //  passw
$dbh = new PDO('mysql:host='.$db_host.'; dbname='.$db_name, $db_user, $user_pw);

function createID(){//exacts what it will be will be determined later
        //generate something that is secure
        $secure = bindec(openssl_random_pseudo_bytes(8));
	return $secure
}

function post($dbh , $name, $body, $tags, $type, $thread = -1){
	$stmt = $dbh->prepare('REPLACE INTO posts (id, name, body, tags, type, thread, time) VALUES (:id, :name, :body, :tags, :type, :thread, NOW(), )');//create a way to deal with nonunique ids
	$id = createID();
	$stmt->bindValue(':id', $id);
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
		array_push($results, $res);
	}
	return $results;
}

function getThread($dbh , $thread){
	$threadData = array();
	//need the question, need the answers
	$qStmt = $dbh->prepare('SELECT * FROM posts WHERE type = 0 AND thread=:thread');
	$qStmt->bindValue(':thread', $thread
}
?>
