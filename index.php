<?php

/*Inizializza la sessione */
session_start();
/* se non sessione esiste o vuota pongo $_SESSION['cart'] = 0 */
if(!isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
   $_SESSION['cart']=0;
}

/* controllo numero del carrello */
if(!empty($_SESSION['cart'])) {
$cart_count = $_SESSION['cart'];
}else{ $cart_count = 0; }
?>
<html id="alldoc">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/w3.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script type="text/javascript" src="ajaxlib.js"></script>
</head>
<?php if(!empty($_SESSION['mail'])){ ?>
 <body onload="getitems('all','<?php echo $_SESSION['mail']; ?>','');">
<?php 
}else{ 
?>
<body onload="getitems('all','','');">
<?php 
}
?>
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
<div class="w3-panel w3-orange"><h3>CATALOGO PRODOTTI</h3></div>
<div id="tabprodotti" name="all" value=""></div>
</div>
<footer class="w3-bar w3-gray footer_free">Copyright Matteo Ferri(c) 2020</footer>
</body>
</html>