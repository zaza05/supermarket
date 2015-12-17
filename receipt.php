<?php session_start();?>
<?php require_once('Connections/supermarket.php'); ?>
<?php require_once('Connections/supermarket.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$colname_rsUsername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUsername = $_SESSION['MM_Username'];
}
mysql_select_db($database_supermarket, $supermarket);
$query_rsUsername = sprintf("SELECT teller_username FROM teller WHERE teller_username = %s", GetSQLValueString($colname_rsUsername, "text"));
$rsUsername = mysql_query($query_rsUsername, $supermarket) or die(mysql_error());
$row_rsUsername = mysql_fetch_assoc($rsUsername);
$totalRows_rsUsername = mysql_num_rows($rsUsername);

mysql_select_db($database_connection, $supermarket);
$query_rceipt = "SELECT Teller, product_name, product_price, quantity, total, paid, balance FROM sold ORDER BY id  DESC LIMIT 1";
$rceipt = mysql_query($query_rceipt, $supermarket) or die(mysql_error());
$row_rceipt = mysql_fetch_assoc($rceipt);
$totalRows_rceipt = mysql_num_rows($rceipt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body {
	font: 100%/1.4 Verdana, Arial, Helvetica, sans-serif;
	background-color: #42413C;
	margin: 0;
	padding: 0;
	color: #000;
}

/* ~~ Element/tag selectors ~~ */
ul, ol, dl { /* Due to variations between browsers, it's best practices to zero padding and margin on lists. For consistency, you can either specify the amounts you want here, or on the list items (LI, DT, DD) they contain. Remember that what you do here will cascade to the .nav list unless you write a more specific selector. */
	padding: 0;
	margin: 0;
}
h1, h2, h3, h4, h5, h6, p {
	margin-top: 0;	 /* removing the top margin gets around an issue where margins can escape from their containing div. The remaining bottom margin will hold it away from any elements that follow. */
	padding-right: 15px;
	padding-left: 15px; /* adding the padding to the sides of the elements within the divs, instead of the divs themselves, gets rid of any box model math. A nested div with side padding can also be used as an alternate method. */
}
a img { /* this selector removes the default blue border displayed in some browsers around an image when it is surrounded by a link */
	border: none;
}
/* ~~ Styling for your site's links must remain in this order - including the group of selectors that create the hover effect. ~~ */
a:link {
	color: #42413C;
	text-decoration: underline; /* unless you style your links to look extremely unique, it's best to provide underlines for quick visual identification */
}
a:visited {
	color: #6E6C64;
	text-decoration: underline;
}
a:hover, a:active, a:focus { /* this group of selectors will give a keyboard navigator the same hover experience as the person using a mouse. */
	text-decoration: none;
}

/* ~~ this fixed width container surrounds the other divs ~~ */
.container {
	width: 400px;
	height: 600px;
	background-color: #FFF;
	margin: 0 auto; /* the auto value on the sides, coupled with the width, centers the layout */
	overflow: visible;
}

/* ~~ the header is not given a width. It will extend the full width of your layout. It contains an image placeholder that should be replaced with your own linked logo ~~ */
.header {
	background-color: #ADB96E;
}

/* ~~ This is the layout information. ~~ 

1) Padding is only placed on the top and/or bottom of the div. The elements within this div have padding on their sides. This saves you from any "box model math". Keep in mind, if you add any side padding or border to the div itself, it will be added to the width you define to create the *total* width. You may also choose to remove the padding on the element in the div and place a second div within it with no width and the padding necessary for your design.

*/

.content {
	padding: 10px 0;
	width: 60%;
	height: 90%;
}

/* ~~ The footer ~~ */
.footer {
	padding: 10px 0;
	background-color: #CCC49F;
}

/* ~~ miscellaneous float/clear classes ~~ */
.fltrt {  /* this class can be used to float an element right in your page. The floated element must precede the element it should be next to on the page. */
	float: right;
	margin-left: 8px;
}
.fltlft { /* this class can be used to float an element left in your page. The floated element must precede the element it should be next to on the page. */
	float: left;
	margin-right: 8px;
}
.clearfloat { /* this class can be placed on a <br /> or empty div as the final element following the last floated div (within the #container) if the #footer is removed or taken out of the #container */
	clear:both;
	height:0;
	font-size: 1px;
	line-height: 0px;
}
#apDiv1 {
	position: absolute;
	width: 190px;
	height: 59px;
	z-index: 1;
	left: 981px;
	top: 104px;
}
-->
</style>
<style type="text/css">
#apDiv2 {
	position: absolute;
	width: 370px;
	height: 84px;
	z-index: 2;
	left: 411px;
	top: 85px;
}
#apDiv3 {
	position: absolute;
	width: 395px;
	height: 418px;
	z-index: 3;
	left: 419px;
	top: 217px;
}
#apDiv4 {
	position: absolute;
	width: 367px;
	height: 115px;
	z-index: 1;
	left: 17px;
	top: 264px;
}
#apDiv5 {
	position: absolute;
	width: 319px;
	height: 101px;
	z-index: 4;
	left: 29px;
	top: 274px;
}
</style>
</head>

<body>
<br/><br/>

<div class="container" align="center"width="50%">
  <div class="header">
    <div id="apDiv2">
    <h2 align="center"><b><font color="Blue">CHAMPIONS TECHNOLOGY RECEIPT</font></b></h2></div> 
    <!-- end .header --></div>
<div class="content">

  <p>&nbsp;</p>
<div id="apDiv3">

   <hr width="70%" size="1" align="center" />
<font size="2"> <table width="299" height="219" border="0" align="center">
      <tr>
        <td width="126"><font size="2">Cashier Name</font></td>
        <td width="163"><?php echo $row_rceipt['Teller']; ?></td>
      </tr>
      <tr>
        <td>Product </td>
        <td ><?php echo $row_rceipt['product_name']; ?></td>
      </tr>
      <tr>
        <td>Price</td>
        <td><?php echo $row_rceipt['product_price']; ?></td>
      </tr>
      <tr>
        <td>Quantity</td>
        <td><?php echo $row_rceipt['quantity']; ?></td>
      </tr>
      <tr bgcolor="green">
        <td>Total</td>
        <td><?php echo $row_rceipt['total']; ?></td>
      </tr>
      <tr>
        <td>Paid</td>
        <td><?php echo $row_rceipt['paid']; ?></td>
      </tr>
      <tr bgcolor="green">
        <td><font size="2">Change</font></td>
        <td><?php echo $row_rceipt['balance']; ?></td>
      </tr>
    </table></font>
    <p>&nbsp;</p>
    <div id="apDiv5">
      <p><font size="2">PHONE NUMBER (+25)0785716043/(+25)0785716043</font> </p>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <hr  size="1" width="70%" align="center"/>
    <form id="form1" name="form1" method="post" action="">
      <input type="submit" name="button" id="button" value="PRINT" />
    <a href="sale.php"> <input type="button" name="button2" id="button2" value="BACK" /></a>
  </form></div></div></div>
</body>
</html>
<?php
mysql_free_result($rsUsernam);

mysql_free_result($rceipt);

mysql_free_result($rsUsername);
?>
