<?
	header( "Content-Type: text/html; charset=EUC-JP" ) ;
?>
<html><body>
<form method="post" action="">
  <input type="hidden" name="var_hidden" value="1" />
  <input type="text" name="var_text" maxlength="12" />
  <input type="checkbox" name="var_checkbox" value="�׻�������" />
  <input type="radio" name="var_radio" value="��" />
  <input type="radio" name="var_radio" value="��" />
  <select name="var_select">
    <option value="0">�ߤ���</option>
    <option value="1">���</option>
    <option value="2">�Хʥ�</option>
  </select>
  <input type="submit" />
</form>
</body></html>
