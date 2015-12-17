<?php session_start();?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO product (product_name, product_price,quantity, barcode, made_date, expiring_date, supplier) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['product_name'], "text"),
                       GetSQLValueString($_POST['product_price'], "int"),
					   GetSQLValueString($_POST['quantity'], "int"),
                       GetSQLValueString($_POST['barcode'], "text"),
                       GetSQLValueString($_POST['made_date'], "text"),
                       GetSQLValueString($_POST['expiring_date'], "text"),
                       GetSQLValueString($_POST['supplier'], "text"));

  mysql_select_db($database_supermarket, $supermarket);
  
   	$pname = $_POST['product_name'];
  	$query_Recordset1 = "SELECT * FROM product WHERE  product_name= '{$pname}'";
	
	$Recordset1 = mysql_query($query_Recordset1, $supermarket) or die(mysql_error());
	//exit;
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	if($totalRows_Recordset1 > 0){
	    $row_Recordset1 = mysql_fetch_array($Recordset1);
		$row_Recordset1['quantity'];
		// Full texts 	id 	product_name 	product_price 	quantity 
		 $newAmt = $row_Recordset1['quantity'] + $_POST['quantity'];
		
		 $update = mysql_query("UPDATE product SET quantity= '$newAmt' WHERE product.product_name = '{$pname}'");
	}else{
		 $Result1 = mysql_query($insertSQL, $supermarket) or die(mysql_error());
	}
	

  $insertGoTo = "addproduct.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
if($row_Recordset1['product_name']=$_POST['product_name']){
$row_Recordset1['quantity']=($row_Recordset1['quantity'])+($_POST['quantity']);
 
	 
	
}
$colname_rsUsername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUsername = $_SESSION['MM_Username'];
}
mysql_select_db($database_supermarket, $supermarket);
$query_rsUsername = sprintf("SELECT username FROM login WHERE username = %s", GetSQLValueString($colname_rsUsername, "text"));
$rsUsername = mysql_query($query_rsUsername, $supermarket) or die(mysql_error());
$row_rsUsername = mysql_fetch_assoc($rsUsername);
$totalRows_rsUsername = mysql_num_rows($rsUsername);

mysql_select_db($database_supermarket, $supermarket);

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
	font-family: Verdana, Arial, Helvetica, sans-serif;
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
	font-family: Verdana, Arial, Helvetica, sans-serif;
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
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
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
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
</head>

<body>
<br/><br/>

<div class="container">
  <div class="header"><a href="#"><img src="_images/images_021.jpg" alt="Insert Logo Here" name="Insert_logo" width="299" height="191" id="Insert_logo" style="background-color: #C6D580; display:block;" /></a>
    <div id="apDiv2">
      <h2 align="center"><b><font color="#3333FF">HOPE SUPERMARKET KABUSU</font></b></h2></div>
    <!-- end .header --></div>
  <div class="content">
    <ul id="MenuBar1" class="MenuBarHorizontal">
      <li><a href="home.php">Home</a>      </li>
      <li><a href="#" class="MenuBarItemSubmenu">Product</a>
        <ul>
          <li><a href="addproduct.php">Add</a></li>
          <li><a href="deleteproduct.php">Delete</a></li>
          <li><a href="editproduct.php">Edit</a></li>
        </ul>
      </li>
      <li><a href="#" class="MenuBarItemSubmenu">Reports</a>
        <ul>
          <li><a href="totalsold.php">Totol Sold</a></li>
          <li><a href="instock.php">Instock</a></li>
</ul>
      </li>
      <li><a href="#" class="MenuBarItemSubmenu">Teller</a>
        <ul>
          <li><a href="addteller.php">Add</a></li>
          <li><a href="deleteteller.php">Delete</a></li>
          <li><a href="editteller.php">Edit</a></li>
        </ul>
      </li>
      <li><a href="#" class="MenuBarItemSubmenu">Messages</a>
        <ul>
          <li><a href="readmessage.php">Read</a></li>
          <li><a href="sendmessage.php">Send</a></li>
        </ul>
      </li>
      <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
    </ul>
    <div id="apDiv1">
      <table border="0" cellpadding="1" cellspacing="1">
        <tr>
          <td width="213">Welcome</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsUsername['username']; ?></td>
          </tr>
          <?php } while ($row_rsUsername = mysql_fetch_assoc($rsUsername)); ?>
      </table>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="960" height="296" border="1">
      <tr>
        <th height="290" scope="col"> <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
          <font size="2"><table width="633" align="center">
            <tr valign="baseline">
              <td width="269" align="left" nowrap="nowrap">Product<font size="2"> Name</font></td>
              <td width="352"><input type="text" name="product_name" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">Product<font size="2"> Price</font></td>
              <td><input type="text" name="product_price" value="" size="32" /></td>
            </tr>
             <tr valign="baseline">
              <td nowrap="nowrap" align="left"><font size="2">Quantity</font></td>
              <td><input type="text" name="quantity" value="" size="32" /></td>
            </tr
            ><tr valign="baseline">
              <td nowrap="nowrap" align="left">Barcode</td>
              <td><input type="text" name="barcode" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">Made<font size="2"> Date</font></td>
              <td><input type="text" name="made_date" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">Expiring<font size="2"> Date</font></td>
              <td><input type="text" name="expiring_date" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">Supplier:</td>
              <td><input type="text" name="supplier" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="left">&nbsp;</td>
              <td><input type="submit" value="ADD PRODUCT" /></td>
            </tr>
          </table></font>
          <input type="hidden" name="MM_insert" value="form2" />
        </form>
          <p>&nbsp;</p></th>
      </tr>
    </table>
  </div>
  <?php include("_includes/footer1.php");?>
  <br/><br/>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
  </script>
</body>
</html>
<?php
mysql_free_result($rsUsername);

mysql_free_result($Recordset1);

mysql_free_result($rsInsert);
?>
