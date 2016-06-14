<?php require_once('Connections/baglan.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$src=$_FILES['file']['tmp_name'];
	$foto="foto/".$_FILES['file']['name'];
	copy($src,$foto);
  $insertSQL = sprintf("INSERT INTO bilgiler (id, ad, soyad, foto) VALUES (%s, %s, %s, '$foto')",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['ad'], "text"),
                       GetSQLValueString($_POST['soyad'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_okn = "SELECT * FROM bilgiler";
$okn = mysql_query($query_okn, $baglan) or die(mysql_error());
$row_okn = mysql_fetch_assoc($okn);
$totalRows_okn = mysql_num_rows($okn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resim Yükle</title>
</head>

<body>

<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Id:</td>
      <td><input type="text" name="id" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ad:</td>
      <td><input type="text" name="ad" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Soyad:</td>
      <td><input type="text" name="soyad" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Foto:</td>
      <td><label>
        <input type="file" name="file" id="file" />
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Kayıt Ekle" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p align="center">&nbsp;</p>
<div align="center">
  <table border="1">
    <tr>
      <td>id</td>
      <td>ad</td>
      <td>soyad</td>
      <td>foto</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_okn['id']; ?></td>
        <td><?php echo $row_okn['ad']; ?></td>
        <td><?php echo $row_okn['soyad']; ?></td>
        <td><img src="<?php echo $row_okn['foto']; ?>" width="100" height="100"/></td>
      </tr>
      <?php } while ($row_okn = mysql_fetch_assoc($okn)); ?>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($okn);
?>
