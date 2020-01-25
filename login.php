<?php
session_start();
$_SESSION['cart']=0;
$cart_count=0;
?>
<html id="alldoc">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/w3.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script type="text/javascript" src="ajaxlib.js"></script>
<script>
function sel1(element){
  var c2 = document.getElementById('myvenditore');
  if(element.checked){ c2.checked = false; }else{ c2.checked = true; }
}
function sel2(element){
  var c1 = document.getElementById('mycliente');
  if(element.checked){ c1.checked = false; }else{ c1.checked = true; }
}
</script>
</head>
<body onload="getitems('reset','','');" > 
<nav class="w3-bar w3-black"><ul>
<li class="my_item1"><img src="images/logo-sito.png" class="mylogo" /></li>
<?php
if(!empty($_SESSION['liv'])){
 if(($_SESSION['liv'] == 2)||($_SESSION['liv'] == 1)){
 echo "<li class=\"my_item2\"><a href=\"logout.php\">|Logout</a></li>";
 echo "<li class=\"my_item2\"><a href=\"login.php\">|Profilo</a></li>";
  }
 }else{ echo "<li class=\"my_item2\"><a href=\"login.php\">|Accesso</a></li>"; 
}
?>
<li class="my_item2"><a href="cart.php">|Acquisti</a></li>
<li class="my_item2"><a href="search.php">|Ricerca</a></li>
<?php
if(!empty($_SESSION['liv'])){
if($_SESSION['liv'] == 2){
 echo "<li class=\"my_item2\"><a href=\"modify.php\">|Modifica</a></li>";
 }
}
?>
<li class="my_item2"><a href="index.php">|Home</a></li>
<?php
 if(!empty($_SESSION['user'])){ 
  $myuser = $_SESSION['user'];
  if($myuser != ''){ echo('User: '.$myuser); }
}
?>
<li class="my_item2"><img src="images/cart-white.png" class="mylogo2"><div id="ccart" class="my_item0"><?php echo $cart_count; ?></div></li>
</ul>
</nav>
<div class="w3-container my_bar1">
<div id="tabprodotti" name="all" value=""></div>
</div>
<footer class="w3-bar w3-gray footer_fixed">Copyright Matteo Ferri(c) 2020</footer>
</body>
</html>