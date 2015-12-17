<?php session_start();?>
<?php require_once('Connections/supermarket.php'); ?>
<r?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE teller SET teller_names=%s, teller_pnumber=%s, teller_email=%s, teller_location=%s WHERE id=%s",
                       GetSQLValueString($_POST['teller_names'], "text"),
                       GetSQLValueString($_POST['teller_pnumber'], "text"),
                       GetSQLValueString($_POST['teller_email'], "text"),
                       GetSQLValueString($_POST['teller_location'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_supermarket, $supermarket);
  $Result1 = mysql_query($updateSQL, $supermarket) or die(mysql_error());

  $updateGoTo = "viewaccnt.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_viewAcc = "-1";
if (isset($_GET['id'])) {
  $colname_viewAcc = $_GET['id'];
}
mysql_select_db($database_supermarket, $supermarket);
$query_viewAcc = sprintf("SELECT id, teller_names, teller_pnumber, teller_email, teller_location FROM teller WHERE id = %s", GetSQLValueString($colname_viewAcc, "int"));
$viewAcc = mysql_query($query_viewAcc, $supermarket) or die(mysql_error());
$row_viewAcc = mysql_fetch_assoc($viewAcc);
$totalRows_viewAcc = mysql_num_rows($viewAcc);
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
	overflow: visible;
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
	width: 960px;
	background-color: #FFF;
	margin: 0 auto; /* the auto value on the sides, coupled with the width, centers the layout */
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
	overflow: visible;
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
	left: 985px;
	top: 62px;
}
-->
</style>
<style type="text/css">
#apDiv2 {
	position: absolute;
	width: 370px;
	height: 83px;
	z-index: 2;
	left: 537px;
	top: 81px;
}
</style>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#apDiv3 {
	position: absolute;
	width: 190px;
	height: 59px;
	z-index: 1;
	left: 981px;
	top: 104px;
}
</style>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
</head>

<body>
<br/><br/>

<div class="container">
  <div class="header"><a href="#"><img src="_images/images_021.jpg" alt="Insert Logo Here" name="Insert_logo" width="299" height="191" id="Insert_logo" style="background-color: #C6D580; display:block;" /></a>
    <div id="apDiv3">
      <table border="0" cellpadding="1" cellspacing="1">
        <tr>
          <td>Welcome</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsUsername['teller_username']; ?></td>
          </tr>
          <?php } while ($row_rsUsername = mysql_fetch_assoc($rsUsername)); ?>
      </table>
    </div>
    <div id="apDiv2">
    <h2 align="center"><b><font color="#3333FF"H>HOPE SUPERMARKET KABUSU</font></b></h2></div> 
    <!-- end .header --></div>
  <div class="content">
    <ul id="MenuBar1" class="MenuBarHorizontal">
      <li><a href="Pointofsale.php">Home</a></li>
      <li><a href="sale.php">Sales</a></li>
      <li><a href="viewaccnt.php">View Account</a></li>
      <li><a href="transactions.php">Transactions</a></li>
      <li><a href="report.php">Report</a></li>
      <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
    </ul>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="956" height="245" border="1">
      <tr>
        <th height="239" scope="col"> <font size="2"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          <p>&nbsp;</p>
          <table width="634" align="center">
            <tr valign="baseline">
              <td width="177" align="left" nowrap="nowrap">Id:</td>
              <td width="445"><?php echo $row_viewAcc['id']; ?></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">Teller Names:</td>
              <td><input type="text" name="teller_names" value="<?php echo htmlentities($row_viewAcc['teller_names'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">Phone Number:</td>
              <td><input type="text" name="teller_pnumber" value="<?php echo htmlentities($row_viewAcc['teller_pnumber'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left"> Email:</td>
              <td><input type="text" name="teller_email" value="<?php echo htmlentities($row_viewAcc['teller_email'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">Location:</td>
              <td><input type="text" name="teller_location" value="<?php echo htmlentities($row_viewAcc['teller_location'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">&nbsp;</td>
              <td><input type="submit" value="EDIT ACCOUNT" />&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="viewaccnt.php"><input type="button" name="Reset" id="button" value="CANCEL" /></a></td>
            </tr>
          </table>
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="id" value="<?php echo $row_viewAcc['id']; ?>" />
        </form>
          <p>&nbsp;</p>
        </font></th>
      </tr>
    </table>
  </div>
    <?php include("_includes/footer2.php");?>
  <br/><br/>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
  </script>
</body>
</html>
<?php
mysql_free_result($rsUsername);

mysql_free_result($viewAcc);

mysql_free_result($rsUsername);

mysql_free_result($viewAcc);
?>
