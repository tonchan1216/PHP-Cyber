<?php

	// MySQL��³��ʬ
	$conn = mysql_connect( 'localhost' , 'example' , 'example' ) ;
	mysql_select_db( 'example' , $conn ) ;
	mysql_query( 'SET NAMES ujis' , $conn ) ; // MySQL4.0�ʲ��ʤ饳���ȥ�����

	// ��ƽ���
	if ( ! empty( $_POST["dopost"] ) && ! empty( $_POST["name"] ) ) {

		// �����꤬���顼�ˤʤä��顢�ơ��֥뤬¸�ߤ��ʤ���Τȸ��ƿ�������
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

		// �ѥ���ɤ����ꤵ��Ƥ��ʤ���С����å����Υѥ���ɤǾ�񤭤���
		if ( empty( $_POST["pass"] ) && ! empty( $_COOKIE["pass"] ) ) {
			$_POST["pass"] = $_COOKIE["pass"] ;
		}

		// ̾���ȥѥ���ɤΰ��׳�ǧ
		$sql = "SELECT COUNT(*) FROM bbs WHERE name='".$_POST["name"]."' AND pass<>'".$_POST["pass"]."'" ;
		$rs = mysql_query( $sql , $conn ) ;
		if ( mysql_result( $rs , 0 , 0 ) ) {
			// Ʊ��̾���ǡ��ѥ���ɤΰۤʤ�쥳���ɤ�����м��յ���
			header( "Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?message='.urlencode('����̾���Ϥ��Ǥ˻Ȥ��Ƥ��ޤ�') ) ;
			exit ;
		}

		// ������ƤΥǡ����١����ؤν����
		$sql = "INSERT INTO bbs SET name='".$_POST["name"]."', title='".$_POST["title"]."', body='".$_POST["body"]."', pass='".$_POST["pass"]."', postdate=NOW()" ;
		mysql_query( $sql , $conn ) ;

		// �������ơ������Ѥˡ����å�����̾���ȥѥ���ɤ���¸����
		setcookie( 'name' , $_POST["name"] , time()+60*60*24*30 ) ;
		setcookie( 'pass' , $_POST["pass"] , time()+60*60*24*30 ) ;

		// ��Ƥ����������쥯��
		header( "Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?message='.urlencode('��Ͽ���ޤ���') ) ;
		exit ;
	}

	// ��å������������
	if ( ! empty( $_POST['delete'] ) ) {

		// �ѥ���ɤ����ꤵ��Ƥ��ʤ���С����å����Υѥ���ɤǾ�񤭤���
		if ( empty( $_POST["pass"] ) && ! empty( $_COOKIE["pass"] ) ) {
			$_POST["pass"] = $_COOKIE["pass"] ;
		}

		// id�ȥѥ���ɤΰ��פ��äơ��������
		$sql = "DELETE FROM bbs WHERE id=".$_POST["id"]." AND pass='".$_POST["pass"]."' LIMIT 1" ;
		mysql_query( $sql , $conn ) ;
		$message = mysql_affected_rows() ? "������ޤ���" : "�ѥ���ɤ��㤤�ޤ�" ;

		// ��������������쥯��
		header( "Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?message=".urlencode($message) ) ;
		exit ;
	}


	// ����饯�������åȤλ���
	header( "Content-Type: text/html; charset=EUC-JP" ) ;

	// HTML������
	echo '<html><head><meta http-equiv="content-type" content="text/html; charset=EUC-JP" /><title>����ץ륲���ȥ֥å�</title></head><body>' ;

	// ��å������������ɽ��
	if ( ! empty( $_GET['message'] ) ) {
		echo '<p style="color:red;">'.$_GET['message'].'</p>' ;
	}

	// �ե������������
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">' ;
	echo '̾��: <input type="text" name="name" value="'.@$_COOKIE['name'].'" /><br />' ;
	echo '��̾: <input type="text" name="title" /><br />' ;
	echo '����: <br /><textarea name="body" rows="4" cols="40"></textarea><br />' ;
	echo '�ѥ����: <input type="password" name="pass" size="8" value="" /><br /><br />' ;
	echo '<input type="submit" name="dopost" value="���" />' ;
	echo '<input type="reset" value="�ꥻ�å�" />' ;
	echo '</form>' ;
	echo '<hr />' ;

	// �������ɽ����ʬ �ʺǿ����10��Τ�ɽ����
	$sql = "SELECT * FROM bbs ORDER BY postdate DESC LIMIT 10" ;
	$result = mysql_query( $sql , $conn ) ;
	if ( $result ) {
		while ( $row = mysql_fetch_array( $result , MYSQL_ASSOC ) ) {
			echo '<p>' ;
			echo 'No.'.$row['id'].'<br />' ;
			echo '��̾:'.htmlspecialchars($row['title'],ENT_QUOTES).'<br />' ;
			echo '̾��:'.htmlspecialchars($row['name'],ENT_QUOTES).'<br />' ;
			echo '����:'.$row['postdate'].'<br />' ;
			echo '<blockquote>'.nl2br(htmlspecialchars($row['body'],ENT_QUOTES)).'</blockquote>' ;
			echo '</p><hr />' ;
		}
	}

	// �����
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">' ;
	echo '����No.<input type="text" name="id" size="4" /> ' ;
	echo '�ѥ����<input type="password" name="pass" size="8" value="" />' ;
	echo '<input type="submit" name="delete" value="�������" />' ;
	echo '</form>' ;

	// HTML��λ��
	echo '</body></html>' ;

?>