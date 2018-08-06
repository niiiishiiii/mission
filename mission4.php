<?php



	//データベースに接続//

	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn,$user,$password);//インスタンスを作成(newクラス名(引数))



	//データベースにテーブルを作成//

	$sql = "CREATE TABLE test"//指定した名前のテーブルを作成
	."("
	."id INT AUTO_INCREMENT PRIMARY KEY,"//カラム名　カラムのデータ型
	."name char(32),"//カラム名　カラムのデータ型(長さの決まった文字列)
	."comment TEXT,"//カラム名　カラムのデータ型(長文)
	."date DATETIME,"//カラム名　カラムのデータ型(日時)
	."pas char(32)"
	.");";
	$stmt = $pdo->query($sql);



	if(isset($_POST["submit1"]))//コメント機能
	 {
		if(!empty($_POST["namae"]) && !empty($_POST["message"]))
		 {

			//insertを使いデータを入力//

			//prepareメソッドを使い、SQL文を実行する準備を行う
			//テーブル名に対してVALUESのように:nameと:valueというパラメータを与える(個々の値が変わってもこのSQLを使える)
			$sql = $pdo -> prepare("INSERT INTO test(name,comment,date,pas) VALUES (:name,:comment,:date,:pas)");

			//:nameなどのパラメータに値を入力
			//1個目　:nameのようにさっき与えたパラメータを指定
			//2個目　それに入れる変数を指定 bindParamには変数のみ入れれる
			//3個目　型を指定(PDO::PARAM_STRは文字列という意味)


			$sql -> bindParam(':name',$namae,PDO::PARAM_STR);
			$sql -> bindParam(':comment',$komennto,PDO::PARAM_STR);
			$sql -> bindParam(':date',$d,PDO::PARAM_STR);
			$sql -> bindParam(':pas',$pas,PDO::PARAM_STR);


			$komennto = htmlspecialchars($_POST["message"]);//入力したコメントを変数に格納
			$namae = htmlspecialchars($_POST["namae"]);//入力した名前を変数に格納
			$pas = htmlspecialchars($_POST["pas1"]);//入力したパスワードを変数に格納
			$d = date("Y/m/d H:i:s");//時間を取得

			$sql -> execute();//命令などを実行するという意味



 			echo "投稿が完了しました。";
		 }

		else
		 {
			echo "名前かコメントのどちらかが未入力です。入力しなおしてください。";
		 }

	 }


	else if(($_POST["submit2"]))//削除機能
	{
		$sql = 'SELECT * FROM test';
		$results = $pdo -> query($sql);

		foreach($results as $row)//$rowの中にはテーブルのカラム名が入る
		{

		 if($row['id'] == $_POST["sakugyo"])
		  {
		 	if($row['pas'] == $_POST["pas2"])
			  {
				//入力したデータをdeleteによって削除する

				//delete from テーブル名　where 条件文;
				$id = htmlspecialchars($_POST["sakugyo"]);
				$sql = "delete from test where id = $id";
				$result = $pdo -> query ($sql);

				echo "投稿を削除しました。";

			  }
			else
			{
				echo "パスワードが間違っています。";
			}
		  }

		}
	}


	else if(isset($_POST["submit3"]))//編集機能
	 {
		$sql = 'SELECT * FROM test';
		$results = $pdo -> query($sql);

		foreach($results as $row)//$rowの中にはテーブルのカラム名が入る
		{

		 if($row['id'] == $_POST["hensyuu"])
		  {
		 	if($row['pas'] == $_POST["pas3"])
			 {
				$hennsyuubann = $row['id'];//編集したい番号を取り出す
				$hensyuunamae = $row['name'];//編集したい名前を取り出す
				$hensyuukome = $row['comment'];//編集したいコメントを取り出す

				echo "投稿の編集中です。";
			 }

			else
			{
				echo "パスワードか編集番号が間違っています。";
				$u = 1;
			}
		  }

		}
		
	 }



	else if(isset($_POST["submit4"]))//編集
	{

		if(!empty($_POST["namae"]) && !empty($_POST["message"]))
		 {
			//入力したデータをupdateによって編集//

			//update テーブル名 set カラム名　= '変更したい値' where 条件文;
			$id = htmlspecialchars($_POST["hensyuu"]);
			$nm = htmlspecialchars($_POST["namae"]);
			$kome = htmlspecialchars($_POST["message"]);
			$d = date("Y/m/d H:i:s");//時間を取得
			$pas = htmlspecialchars($_POST["pas4"]);

			$sql = "update test set name = '$nm',comment = '$kome',date = '$d',pas = '$pas' where id = $id ";
			$result = $pdo -> query($sql);


			echo "投稿の編集が完了しました。";
		 }

		else
		 {
			echo "名前かコメントのどちらかが未入力です。入力しなおしてください。";
		 }

	}
?>

<?php

if((isset($_POST["submit1"])) || (isset($_POST["submit2"])) || (empty($_POST["hensyuu"])) || (isset($u)) )//通常時のフォーム欄
{
?>

	<!--通常時のフォーム欄 -->

	<!DOCTYPE HTML>
	<head>
	<meta charset="UTF-8">
	</head>
	<body>

	<form action = "mission4.php" method="post">

	<br />
	名前:
	<input type = "text" name = "namae" value = ""><br />
	コメント:
	<input type = "text" name = "message" value = ""><br />
	パスワード:
	<input type = "text" name = "pas1" value = ""><br />
	<input type = "submit" name = "submit1" value = "送信"><br />

	削除対象番号:
	<input type = "text" name = "sakugyo" value = ""><br />
	パスワード:
	<input type = "text" name = "pas2" value = ""><br />
	<input type = "submit" name = "submit2" value = "送信"><br />

	編集対象番号:
	<input type = "text" name = "hensyuu" value = ""><br />
	パスワード:
	<input type = "text" name = "pas3" value = ""><br />
	<input type = "submit" name = "submit3" value = "送信"><br />

	<br />
	※削除したいときは、削除対象番号と対応するパスワードのみ記述してください。<br />
	※編集したいときは、削除対象番号と対応するパスワードのみ記述してください。


	</form>
	</body>

	</html>

	<hr>

<?php

	//入力したデータをselectによって表示//

	//select カラム名　from テーブル名　where 条件文;
	$sql = 'SELECT * FROM test';
	$results = $pdo -> query($sql);
	foreach($results as $row)//$rowの中にはテーブルのカラム名が入る
	{
	 echo $row['id'].',';
	 echo $row['name'].',';
	 echo $row['comment'].',';
	 echo $row['date'].'<br>';
	}	
}
?>


<?php

if(isset($_POST["submit3"]) && empty($u))//編集が押された時
{

?>
	<!--編集用フォーム欄 -->

	<!DOCTYPE HTML>
	<head>
	<meta charset="UTF-8">
	</head>
	<body>

	<br />
	<form action = "mission4.php" method="post">
	名前:
	<input type = "text" name = "namae" value = "<?=$hensyuunamae?>"><br />
	コメント:
	<input type = "text" name = "message" value = "<?=$hensyuukome?>"><br />
	パスワード:
	<input type = "text" name = "pas4" value = ""><br />
	<input type = "submit" name = "submit4" value = "送信"><br />

	<input type = "hidden" name = "hensyuu" value = "<?=$hennsyuubann?>"><br />

	</form>
	</body>
	</html>

	<hr>
<?php

	//入力したデータをselectによって表示//

	//select カラム名　from テーブル名　where 条件文;
	$sql = 'SELECT * FROM test';
	$results = $pdo -> query($sql);
	foreach($results as $row)//$rowの中にはテーブルのカラム名が入る
	{
	 echo $row['id'].',';
	 echo $row['name'].',';
	 echo $row['comment'].',';
	 echo $row['date'].'<br>';
	}
}


if(isset($_POST["submit4"]))//編集後、元のフォームに戻す
{

?>

	<!--通常時のフォーム欄 -->

	<!DOCTYPE HTML>
	<head>
	<meta charset="UTF-8">
	</head>
	<body>

	<form action = "mission4.php" method="post">

	<br />
	名前:
	<input type = "text" name = "namae" value = ""><br />
	コメント:
	<input type = "text" name = "message" value = ""><br />
	パスワード:
	<input type = "text" name = "pas1" value = ""><br />
	<input type = "submit" name = "submit1" value = "送信"><br />

	削除対象番号:
	<input type = "text" name = "sakugyo" value = ""><br />
	パスワード:
	<input type = "text" name = "pas2" value = ""><br />
	<input type = "submit" name = "submit2" value = "送信"><br />

	編集対象番号:
	<input type = "text" name = "hensyuu" value = ""><br />
	パスワード:
	<input type = "text" name = "pas3" value = ""><br />
	<input type = "submit" name = "submit3" value = "送信"><br />

	<br />
	※削除したいときは、削除対象番号と対応するパスワードのみ記述してください。<br />
	※編集したいときは、削除対象番号と対応するパスワードのみ記述してください。


	</form>
	</body>

	</html>

	<hr>

<?php
	//入力したデータをselectによって表示//

	//select カラム名　from テーブル名　where 条件文;
	$sql = 'SELECT * FROM test';
	$results = $pdo -> query($sql);
	foreach($results as $row)//$rowの中にはテーブルのカラム名が入る
	{
	 echo $row['id'].',';
	 echo $row['name'].',';
	 echo $row['comment'].',';
	 echo $row['date'].'<br>';
	}	
}
?>


