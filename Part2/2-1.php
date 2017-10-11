<?php

	// MySQL接続部分
	$conn = mysql_connect( 'localhost' , 'example' , 'example' ) ;
	mysql_select_db( 'example' , $conn ) ;
	mysql_query( 'SET NAMES ujis' , $conn ) ; // MySQL4.0以下ならコメントアウト

	// 投稿処理
	if ( ! empty( $_POST["dopost"] ) && ! empty( $_POST["name"] ) ) {

		// クエリがエラーになったら、テーブルが存在しないものと見て新規作成
		if ( ! mysql_query( "SELECT count(*) FROM bbs" , $conn ) ) {
			mysql_query( "CREATE TABLE bbs (
					id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
					name VARCHAR(255) NOT NULL default '' ,
					title VARCHAR(255) NOT NULL default '' ,
					body TEXT,
					pass VARCHAR(255) NOT NULL default '' ,
					postdate DATETIME NOT NULL,
					PRIMARY KEY (id)
				) TYPE=MyISAM" , $conn ) ;
		}

		// パスワードが指定されていなければ、クッキーのパスワードで上書きする
		if ( empty( $_POST["pass"] ) && ! empty( $_COOKIE["pass"] ) ) {
			$_POST["pass"] = $_COOKIE["pass"] ;
		}

		// 名前とパスワードの一致確認
		$sql = "SELECT COUNT(*) FROM bbs WHERE name='".$_POST["name"]."' AND pass<>'".$_POST["pass"]."'" ;
		$rs = mysql_query( $sql , $conn ) ;
		if ( mysql_result( $rs , 0 , 0 ) ) {
			// 同じ名前で、パスワードの異なるレコードがあれば受付拒否
			header( "Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?message='.urlencode('その名前はすでに使われています') ) ;
			exit ;
		}

		// 投稿内容のデータベースへの書込み
		$sql = "INSERT INTO bbs SET name='".$_POST["name"]."', title='".$_POST["title"]."', body='".$_POST["body"]."', pass='".$_POST["pass"]."', postdate=NOW()" ;
		mysql_query( $sql , $conn ) ;

		// 次回の投稿・管理用に、クッキーに名前とパスワードを保存する
		setcookie( 'name' , $_POST["name"] , time()+60*60*24*30 ) ;
		setcookie( 'pass' , $_POST["pass"] , time()+60*60*24*30 ) ;

		// 投稿したらリダイレクト
		header( "Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?message='.urlencode('登録しました') ) ;
		exit ;
	}

	// メッセージ削除処理
	if ( ! empty( $_POST['delete'] ) ) {

		// パスワードが指定されていなければ、クッキーのパスワードで上書きする
		if ( empty( $_POST["pass"] ) && ! empty( $_COOKIE["pass"] ) ) {
			$_POST["pass"] = $_COOKIE["pass"] ;
		}

		// idとパスワードの一致をもって、削除する
		$sql = "DELETE FROM bbs WHERE id=".$_POST["id"]." AND pass='".$_POST["pass"]."' LIMIT 1" ;
		mysql_query( $sql , $conn ) ;
		$message = mysql_affected_rows() ? "削除しました" : "パスワードが違います" ;

		// 削除したらリダイレクト
		header( "Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?message=".urlencode($message) ) ;
		exit ;
	}


	// キャラクターセットの指定
	header( "Content-Type: text/html; charset=EUC-JP" ) ;

	// HTML開始部
	echo '<html><head><meta http-equiv="content-type" content="text/html; charset=EUC-JP" /><title>サンプルゲストブック</title></head><body>' ;

	// メッセージがあれば表示
	if ( ! empty( $_GET['message'] ) ) {
		echo '<p style="color:red;">'.$_GET['message'].'</p>' ;
	}

	// フォーム描画処理
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">' ;
	echo '名前: <input type="text" name="name" value="'.@$_COOKIE['name'].'" /><br />' ;
	echo '題名: <input type="text" name="title" /><br />' ;
	echo '内容: <br /><textarea name="body" rows="4" cols="40"></textarea><br />' ;
	echo 'パスワード: <input type="password" name="pass" size="8" value="" /><br /><br />' ;
	echo '<input type="submit" name="dopost" value="投稿" />' ;
	echo '<input type="reset" value="リセット" />' ;
	echo '</form>' ;
	echo '<hr />' ;

	// 過去の投稿表示部分 （最新順に10件のみ表示）
	$sql = "SELECT * FROM bbs ORDER BY postdate DESC LIMIT 10" ;
	$result = mysql_query( $sql , $conn ) ;
	if ( $result ) {
		while ( $row = mysql_fetch_array( $result , MYSQL_ASSOC ) ) {
			echo '<p>' ;
			echo 'No.'.$row['id'].'<br />' ;
			echo '題名:'.htmlspecialchars($row['title'],ENT_QUOTES).'<br />' ;
			echo '名前:'.htmlspecialchars($row['name'],ENT_QUOTES).'<br />' ;
			echo '日時:'.$row['postdate'].'<br />' ;
			echo '<blockquote>'.nl2br(htmlspecialchars($row['body'],ENT_QUOTES)).'</blockquote>' ;
			echo '</p><hr />' ;
		}
	}

	// 削除用
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">' ;
	echo '記事No.<input type="text" name="id" size="4" /> ' ;
	echo 'パスワード<input type="password" name="pass" size="8" value="" />' ;
	echo '<input type="submit" name="delete" value="記事削除" />' ;
	echo '</form>' ;

	// HTML終了部
	echo '</body></html>' ;

?>