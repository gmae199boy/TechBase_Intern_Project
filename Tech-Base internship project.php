<!
// PHP Start
>
<?php
/*

                DB Setting

*/

//DB setting

$dsn = 'mysql:dbname=DBname;host= ? ';
$user = 'userName';
$password = 'password';
$pdo = new PDO($dsn, $user, $password);


//create

$sql = "CREATE TABLE IF NOT EXISTS mission_4_DB_3"
." ("
."number INT,"
."date TEXT,"
."name char(32),"
."comment TEXT,"
."password TEXT"
.");";
$stmt = $pdo->query($sql);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//                    情報入力


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//insert
//入力されたらinsertを実行する
if($_POST["name"] != NULL && $_POST["comment"] != NULL 
        && $_POST["hidden"] == NULL && $_POST["input_pas"] != NULL)
{
	$number = 1;
	$date = date("Y m d H:i", time());
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$password = $_POST["input_pas"];
	
	$sql = 'SELECT * FROM mission_4_DB_3';
	$stmt = $pdo->query($sql);
	$result = $stmt->fetchAll();
	foreach($result as $row)
	{
		++$number;
	}
	
	$sql = $pdo->prepare("INSERT INTO mission_4_DB_3 (number, date, name, comment, password) VALUES (:number, :date, :name, :comment,:password)");
	$sql->bindParam(':number', $number, PDO::PARAM_INT);
	$sql->bindParam(':date', $date, PDO::PARAM_STR);
	$sql->bindParam(':name', $name, PDO::PARAM_STR);
	$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql->bindParam(':password', $password, PDO::PARAM_STR);
	$sql->execute();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//            編集機能モードへの突入


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ( $_POST["recomment_number"] != NULL && $_POST["recomment_pas"] != NULL)
{
	$number = $_POST["recomment_number"];
	$password = $_POST["recomment_pas"];
	$is_recomment = false;
	
	$sql = 'SELECT * FROM mission_4_DB_3';
	$stmt = $pdo->query($sql);
	$result = $stmt->fetchAll();

	foreach($result as $row)
	{
  	  if ($row['number'] == $number && $row['password'] == $password)
  	  {
  	 	 	$recomment_number = $row['number'];
  	  		$recomment_comment = $row['comment'];
  	  		$recomment_name = $row['name'];
  	  		$recomment_pas = $row['password'];
  	  		$is_recomment = true;
  	  		
  	  		break;
  	  }
	}
	
	if ($is_recomment == false)
	{
		echo "該当する番号がないか、パスワードが間違っています。<br>";
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//                    編集機能


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST["hidden"] != NULL &&  $_POST["name"] != NULL
         && $_POST["comment"] != NULL && $_POST["input_pas"] != NULL)
{
	$number = $_POST["hidden"];
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$password = $_POST["input_pas"];
	$date = date("Y m d H:i", time())." に編集されました。";
	
	$sql = 'update mission_4_DB_3 set date=:date, name=:name, comment=:comment, password=:password where number=:number';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':date', $date, PDO::PARAM_INT);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
	$stmt->bindParam(':number', $number, PDO::PARAM_INT);
	$stmt->execute();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//                    削除機能


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_POST["delete_number"] != NULL && $_POST["delete_pas"] != NULL)
{
	$number = $_POST["delete_number"];
	$password = $_POST["delete_pas"];
	$is_delete = false;
	
	$sql = 'SELECT * FROM mission_4_DB_3';
	$stmt = $pdo->query($sql);
	$result = $stmt->fetchAll();

	foreach($result as $row)
	{
  	  if ($row['number'] == $number && $row['password'] == $password)
  	  {
  	  		/*
    		$sql = 'delete from mission_4_DB_3 where number=:number';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':number', $number, PDO::PARAM_INT);
			$stmt->execute();
			*/
			$delete = "削除されたコメントです。";
			$write_space = " ";
			$date = date("Y m d H:i", time())." に";
			
			$sql = 'update mission_4_DB_3 set date = :date, name=:name, comment=:comment, password=:password where number=:number';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':date', $date, PDO::PARAM_STR);
			$stmt->bindParam(':name', $delete, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $write_space, PDO::PARAM_STR);
			$stmt->bindParam(':password', $write_space, PDO::PARAM_STR);
			$stmt->bindParam(':number', $row['number'], PDO::PARAM_INT);
			$stmt->execute();
			
			$is_delete = true;
			
  	  		break;
  	  }
	}
	if ($is_delete == false)
	{
		echo "該当する番号がないか、パスワードが間違っています。<br>";  
	}

}
?>



<html>
   <head>
   <title> mission_4</title>
   <meta charset = "utf-8">
   </head>
   <body>
   
   <!--
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      //form
//                    情報の入力、編集機能のフォーム


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
-->
   <pre>
   <form action = "mission_4.php" method = "post">
   <input type = "hidden" name = "hidden" value = "<?php echo $recomment_number; ?>" >
      氏名：  <input type = "text" name = "name" value = "<?php if($recomment_name != NULL) {echo $recomment_name;}?>" 
                                                                                                                                                        placeholder = "氏名"><br>
  コメント：  <input type = "text" name = "comment" value = "<?php if($recomment_comment != NULL) {echo $recomment_comment;} ?>" 
                                                                                                                                                        placeholder = "コメント"><br>
パスワード：  <input type = "password" name = "input_pas" value = "<?php if($recomment_pas != NULL){echo $recomment_pas;} ?>" 
                                                                                                                                                        placeholder = "パスワード" >
                            <input type = "submit" name = "btn" value = "送信"><br>


   <br>
<!--
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      //form
//                    編集機能のフォーム


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
-->
   <form action = "mission_4.php" method = "post">
編集対処番号：<input type = "text" name = "recomment_number" placeholder = "編集対処番号"><br>
  パスワード：<input type = "password" name = "recomment_pas" placeholder = "パスワード" >
                           <input type = "submit" name = "recomment_btn" value = "編集"><br>
    </form>
    
<!--
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      //form
//                    削除機能のフォーム


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
-->

    <form action = "mission_4.php" method = "post">
削除対象番号：<input type = "text" name = "delete_number" placeholder = "削除対象番号" ><br>
  パスワード：<input type = "password" name = "delete_pas" placeholder = "パスワード" >
                            <input type = "submit" name = "delete_btn" value = "削除" ><br>
    </form>
    </pre>
    
<?php

$sql = 'SELECT * FROM mission_4_DB_3';
$stmt = $pdo->query($sql);
$result = $stmt->fetchAll();
foreach($result as $row)
{
    echo $row['number']."<br>";
    echo $row['date']."<br>";
    echo $row['name']."<br>";
    echo $row['comment']."<br>";
    echo "<br>";
}


?>
   
   
   
   
   </body>
</html>




