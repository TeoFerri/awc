<?php
session_start();
if(!isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
   $_SESSION['cart']=0;
}

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
<script>
function check2(value,element){
    if(element.checked){
      var checkboxes = document.getElementsByClassName('cb2');
        for(var i=0;i<checkboxes.length;i++){
           if(checkboxes[i]!=element)
             checkboxes[i].checked = false;
        }
   }
}
</script>
</head>
<body style="background-repeat:no-repeat"> 
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
<div class="w3-container my_bar1"><div class="w3-panel w3-orange">
<h3>REGISTRAZIONE</h3>
</div>
<div id="tabprodotti" name="all" value="">
<h1>REGISTRATI</h1>
<form action="request.php" method="post">
Sono Cliente&nbsp;&nbsp;<input type="checkbox" name="req" value="Nuovo-Cliente" class="cb2" onchange="check2(1,this)" /><br>
Sono Venditore&nbsp;&nbsp;<input type="checkbox" name="req" value="Nuovo-Venditore" class="cb2" onchange="check2(2,this)" /><br>
<br/><input type="submit" class="mybutton" value="INVIO"/>
</form>
</div>
</div>
<footer class="w3-bar w3-gray footer_fixed">Copyright Matteo Ferri(c) 2020</footer>
</body>
</html>