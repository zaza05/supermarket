<?php session_start(); ?>
<?php require_once('../Connections/connection.php'); ?>
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

$colname_report = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_report = $_SESSION['MM_Username'];
}
mysql_select_db($database_connection, $connection);
$query_report = sprintf("SELECT id, Teller, barcode, product_name, product_price, quantity, total, paid, balance FROM sold WHERE Teller = %s ORDER BY id ASC", GetSQLValueString($colname_report, "text"));
$report = mysql_query($query_report, $connection) or die(mysql_error());
$row_report = mysql_fetch_assoc($report);
$totalRows_report = mysql_num_rows($report);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
#apDiv2 {
	position: absolute;
	width: 370px;
	height: 84px;
	z-index: 2;
	left: 313px;
	top: 28px;
}
#apDiv1 {
	position: absolute;
	width: 901px;
	height: 3427px;
	z-index: 3;
	left: 119px;
	top: 199px;
}
#apDiv3 {
	position: absolute;
	width: 975px;
	height: 242px;
	z-index: 3;
	left: 17px;
	top: 192px;
	overflow: visible;
}
#apDiv4 {
	position: absolute;
	width: 975px;
	height: 62px;
	z-index: 4;
	left: 20px;
	top: 442px;
}
</style>
</head>

<body>
<div id="apDiv2">
  <h2 align="center"><b><font color="Blue">HOPE SUPERMARKET REPORT</font></b></h2>
</div>
<div id="apDiv3">
  <table border="0" cellpadding="1" cellspacing="1">
    <tr bgcolor="#336699">
      <td width="67">ID</td>
      <td width="88">TELLER</td>
      <td width="106">BARCODES</td>
      <td width="143">PRODUCTS</td>
      <td width="141">PRICE</td>
      <td width="103">QUANTITY</td>
      <td width="82">TOTAL</td>
      <td width="82">PAID</td>
      <td width="133">CHANGE</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_report['id']; ?></td>
        <td><?php echo $row_report['Teller']; ?></td>
        <td><?php echo $row_report['barcode']; ?></td>
        <td><?php echo $row_report['product_name']; ?></td>
        <td><?php echo $row_report['product_price']; ?></td>
        <td><?php echo $row_report['quantity']; ?></td>
        <td><?php echo $row_report['total']; ?></td>
        <td><?php echo $row_report['paid']; ?></td>
        <td><?php echo $row_report['balance']; ?></td>
      </tr>
      <?php } while ($row_report = mysql_fetch_assoc($report)); ?>
  </table>
</div>
<div id="apDiv4">
  <form id="form1" name="form1" method="post" action="">
    <p>&nbsp;</p>
    <p align="center">
     <a href="pointofsale.php"> <input type="button" name="button" id="button" value="BACK" /></a>
    </p>
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($report);
?>
