<?
	header( "Content-Type: text/html; charset=EUC-JP" ) ;
?>
<html><body>
<form method="post" action="">
  <input type="hidden" name="var_hidden" value="1" />
  <input type="text" name="var_text" maxlength="12" />
  <input type="checkbox" name="var_checkbox" value="要資料送付" />
  <input type="radio" name="var_radio" value="男" />
  <input type="radio" name="var_radio" value="女" />
  <select name="var_select">
    <option value="0">みかん</option>
    <option value="1">リンゴ</option>
    <option value="2">バナナ</option>
  </select>
  <input type="submit" />
</form>
</body></html>
