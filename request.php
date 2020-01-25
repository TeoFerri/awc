<?php

/* request.php è il bus connettore che gestisce eventi REST */
/* tra oggetto Awc e il database omonimo */

/* Collega le librerie del db e dell'oggetto awc */

require("config.php");
require("awc.php");

/* Crea Oggetto Awc */

$awc = new awc();

/* SEZIONE RICHIESTE CON GET */

$q = $_REQUEST["q"];
$p = $_REQUEST["p"];
$v = $_REQUEST["v"];

if ($q !== ""){

switch ($q) {

/* visualizza tutti i prodotti */

 case "all":
    $all = $awc->getallprod($p);
    echo $all;
   break;

/* visualizza tutti i prodotti in json */

 case "all_json":
    $all = $awc->getallprod($p);
    echo json_encode([
        "status" => $all==false?false:true,
        "message" => "TABELLA PRODOTTI",
        "data" => $all
      ]);
   break;

/* logout della sessione */

 case "logout":
    $_SESSION['user']='';
    $_SESSION['mail']='';
    $_SESSION['liv']= 0;
    $_SESSION['cart']= 0;
    unset($_SESSION['cart']);
    $reset=0;
    $awc->update_stato_ordini($reset);
    session_start();
    $all = $awc->getallprod('');
    echo $all;
   break;

/* logout in json */

 case "logout_json":
    $_SESSION['user']='';
    $_SESSION['mail']='';
    $_SESSION['liv']= 0;
    $_SESSION['cart']= 0;
    unset($_SESSION['cart']);
    $reset=0;
    $awc->update_stato_ordini($reset);
    session_start();
    $all = $awc->getallprod('');
    echo json_encode([
        "status" => $all==false?false:true,
        "message" => "LOGOUT",
        "data" => $all
      ]);
   break;

/* modifica prodotto */

 case "pmodify":
    $all = $awc->prodmodify();
    echo $all;
   break;

/* modifica prodotto in json */

 case "pmodify_json":
    $all = $awc->prodmodify();
    echo json_encode([
        "status" => $all==false?false:true,
        "message" => "MODIFICA PRODOTTI",
        "data" => $all
      ]);
   break;

/* scheda prodotto */

 case "schedaprod":
   if($p != ''){ $all = $awc->getschedaprod($p); }
     else { $all = $awc->getschedaprod(); }
    echo $all;
   break;

/* scheda prodotto in json */

 case "schedaprod_json":
   if($p != ''){ $all = $awc->getschedaprod($p); }
    else { $all = $awc->getschedaprod(); }
    echo json_encode([
        "status" => $all==false?false:true,
        "message" => "SCHEDA PRODOTTO",
        "data" => $all
      ]);
   break;

/* aggiunge ordini al carrello */

 case "addcart":
    $all = $awc->addtocart($p,$v);
    echo $all;
   break;

/* ordini carrello in json */

  case "addcart_json":
    $all = $awc->addtocart($p,$v);
    echo json_encode([
        "status" => $all==false?false:true,
        "message" => "AGGIUNGE ORDINI AL CARRELLO",
        "data" => $all
      ]);
   break;

/* visualizza carrello */

 case "showcart":
    $all = $awc->getcart($p);
    echo $all;
   break;

/* dati carrello in json */

 case "showcart_json":
    $all = $awc->getcart($p);
    echo json_encode([
        "status" => $all==false?false:true,
        "message"=> "CARRELLO ACQUISTI",
        "data" => $all
      ]);
   break;

/* Checkout acquisti */

 case "check-out":
    $all = $awc->getcheckout($p,$v);
    echo $all;
   break;

/* checkout in json */

case "check-out_json":
    $all = $awc->getcheckout($p,$v);
    echo json_encode([
        "status" => $all==false?false:true,
        "message"=> "CHECKOUT ACQUISTI",
        "data" => $all
      ]);
   break;

/* cancella ordine */

 case "del_order":
   $all = $awc->deleteorder($p);

/* aggiorna indice carrello */

   $ind= $_SESSION['cart']-1;
   if($ind > -1){
    $_SESSION['cart']=$ind;
   }else{ $_SESSION['cart']=0; }

   $page = $awc->headpage();
   $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
   $page .= $awc->pagecontent('SHOPPING CART');
   $page .= $awc->getcart($p);
   $page .= $awc->closepage();
   echo $page; 
  break;

/* cancella ordine in json */

 case "del_order_json":
   $all = $awc->deleteorder($p);
   echo json_encode([
        "status" => $all==false?false:true,
        "message"=> "ORDINE ".$p." CANCELLATO",
        "data" => $all
      ]);
  break;

/* Visualizza Profilo Utente Loggato */

  case "logged":
    $page = $awc->headpage();
    $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
    
   if($_SESSION['liv'] == 1){
     $page .= "<div class=\"w3-panel w3-orange\"><h3>PROFILO CLIENTE</h3></div>"; 
     $page .= $awc->panel_cliente($_SESSION['mail']);
    }else if($_SESSION['liv'] == 2){
     $page .= "<div class=\"w3-panel w3-orange\"><h3>PROFILO VENDITORE</h3></div>"; 
     $page .= $awc->panel_venditore($_SESSION['mail']);
   }
   $page .= $awc->closepage();
   echo $page;
  break;

/* Crea Nuovo prodotto da pagina Modifica */

   case "Nuovo":
      $page = $awc->headpage();
      $page .= "<script>function donuovo(){ window.location.replace('request.php?q=Nuovo');}</script>";
      $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
      $page .= "<div class=\"w3-panel w3-orange\"><h3>MODIFICA PRODOTTI</h3><input type=\"button\" class=\"mybutton mybutton2\" value=\"Nuovo Prodotto\" onclick=\"donuovo()\"></div>";
      $page .= $awc->getschedaprod('');
      $page .= $awc->closepage();
     echo $page;
   break;

/* invia Recensione sul prodotto */

   case "Recensione":
    $all = $awc->addrecensione($p,$v,$_SESSION['mail']);
    echo $all;
   break;

/* Esegue Reset Sessione prima di Nuovo Login */

   case "reset":
    $reset=0;
    $awc->update_stato_ordini($reset);
    if($_SESSION['liv'] == 0){
     $page ="<div class=\"w3-panel w3-orange\"><h3>ACCESSO</h3></div>";
     $page .= "<form action=\"request.php\" method=\"post\">";
     $page .= "<input type=\"checkbox\" onchange=\"sel1(this)\" id=\"mycliente\" name=\"myliv\" value=\"1\" /><label>Cliente</label>";
     $page .= "<input type=\"checkbox\" onchange=\"sel2(this)\" id=\"myvenditore\" name=\"myliv\" value=\"2\" /><label>Venditore</label><br/><br/>";
     $page .= "<input type=\"text\" id=\"myname\" name=\"myname\" value=\"\" placeholder=\"Your Name\" /><br/><br/>";
     $page .= "<input type=\"hidden\" name=\"req\" value=\"login\" />";
     $page .= "<input type=\"email\" id=\"myemail\" name=\"myemail\" placeholder=\"Your Email\" /><br/><br/>";
     $page .= "<input type=\"text\" id=\"mypwd\" name=\"mypwd\" value=\"\" placeholder=\"Your password\" /><br/><br/>";
     $page .= "<input type=\"submit\" value=\"OK\" class=\"mybutton\" /></form>";
     $page .= "<button value=\"Registra\" class=\"mybutton\" style=\"position:relative;top:0;left:0;width:120px;height:50px;\" onclick=\"window.location.replace('register.php')\" />REGISTRA";
    }else{
      if($_SESSION['liv'] == 1){
          $page = "<div class=\"w3-panel w3-orange\"><h3>PROFILO CLIENTE</h3></div>"; 
          $page .= $awc->panel_cliente($_SESSION['mail']);
         }else if($_SESSION['liv'] == 2){
          $page = "<div class=\"w3-panel w3-orange\"><h3>PROFILO VENDITORE</h3></div>"; 
          $page .= $awc->panel_venditore($_SESSION['mail']);
       }
      $page .= $awc->closepage();
     }
    echo $page;
   break;

  }
}

/* SEZIONE di request CON POST */  

if(isset($_POST['req'])) {

  switch ($_POST['req']){

/* CLIENTE */

/* crea cliente */

    case "create-cliente":
      $all = $awc->getemail_cliente($_POST['email']);
      if (is_array($all)) {
        echo json_encode([
          "status" => 0,
          "message" => $_POST['email'] . " already exist"
        ]);
      } else {
        $pass = $awc->create_cliente($_POST['name'], $_POST['email'], $_POST['password']);
        echo json_encode([
          "status" => $pass,
          "message" => $pass ? "User Created" : "Error creating user"
        ]);
      }
    break;

/* Visualizza tutti i clienti */

    case "get-all-clienti":
     $all = $awc->getall_clienti();
     echo $all;
    break;

/* visualizza clienti in json */

   case "get-all-clienti_json":
    $all = $awc->getall_clienti();
    echo json_encode([
        "status" => $all==false?false:true,
        "message" => "LISTA CLIENTI",
        "data" => $all
      ]);
    break;

/* Trova il cliente con email */

   case "get-email-cliente":
     $all = $awc->getemail_cliente($_POST['email']);
     echo $all;
    break;

/* trova cliente in json */

   case "get-email-cliente_json":
    $all = $awc->getemail_cliente($_POST['email']);
      echo json_encode([
        "status" => $all==false?false:true,
        "message" => "CLIENTE email=".$_POST['email']."",
        "data" => $all
      ]);
    break;

/* Trova cliente per id */

    case "get-id-cliente":
     $all = $awc->getid_cliente($_POST['id']);
     echo $all;
    break;

/* Trova cliente per id in json */

   case "get-id-cliente_json":
    $all = $awc->getid_cliente($_POST['id']);
     echo json_encode([
        "status" => $all==false?false:true,
        "message" => "CLIENTE id=".$_POST['id']."",
        "data" => $all
      ]);
    break;

/* Aggiorna cliente */

    case "Invio-Cliente":
      $pass = $awc->update_cliente($_POST['nome'],$_POST['snome'],$_POST['email'],$_POST['address'],
       $_POST['city'],$_POST['prov'],$_POST['tel'],$_POST['username'],$_POST['pwd'],$_POST['card'],
        $_POST['pag'],$_POST['pref'],$_POST['privacy'],$_POST['tcard'],$_POST['myid']);
  
     if($pass == true){
      $page = $awc->headpage();
      $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
      $page .= "<div class=\"w3-panel w3-orange\"><h3>PROFILO CLIENTE</h3></div>"; 
      $page .= $awc->panel_cliente($_POST['email']);
      $page .= $awc->closepage();
      }else{
       $page = $awc->showmsg(json_encode([
        "status" => $pass,
        "message" => $pass ? "User Updated" : "Error updating user"
       ]),$_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
      }
    echo $page;
    break;

/* registrazione cliente */

   case "Nuovo-Cliente":
    echo "<script>window.location.replace('regcliente.php');</script>";
   break;

/* Registra cliente */

    case "registra-cliente":
     $reg=1;
     $pass= $awc->create_cliente($_POST['nome'],$_POST['snome'],$_POST['email'],$_POST['address'],$_POST['city'],$_POST['prov'],$_POST['tel'],$_POST['username'],$_POST['pwd'],$_POST['card'],$_POST['pag'],$_POST['pref'],$_POST['privacy'],$_POST['tcard'],$reg);
     if($pass == true){
      session_start();
      $_SESSION['user']= $_POST['nome'].' '.$_POST['snome'];
      $_SESSION['mail']= $_POST['email']; 
      $_SESSION['liv']= 1; 
      $page ="<script>window.location.replace('index.php');</script>";
     }else{
       $page = $awc->showmsg(json_encode([
        "status" => $pass,
        "message" => $pass ? "User Updated" : "Error updating user"
       ]),$_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
     }
     echo $page;
    break;

/* Cancella cliente */

    case "Cancella-Cliente":
      $pass = $awc->delete_cliente($_POST['myid']);
      $page = $awc->showmsg(json_encode([
        "status" => $pass,
        "message" => $pass ? "User Updated" : "Error updating user"
       ]),$_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
      echo $page;
    break;

/* VENDITORE */

/* Aggiorna Venditore */

   case "Invio-Venditore":
    $pass = $awc->update_venditore($_POST['nome'],$_POST['job'],$_POST['tel'],$_POST['iva'],$_POST['note'],$_POST['email'],$_POST['pwd'],$_POST['myid']);
    if($pass == true){
      $page = $awc->headpage();
      $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);          
      $page .= "<div class=\"w3-panel w3-orange\"><h3>PROFILO VENDITORE</h3></div>"; 
      $page .= $awc->panel_venditore($_POST['email']);
      $page .= $awc->closepage();
    }else{
        $page = $awc->showmsg(json_encode([
        "status" => $pass,
        "message" => $pass ? "User Updated" : "Error updating user"
       ]),$_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
    }
    echo $page;
   break;

/* Registrazione venditore */

   case "Nuovo-Venditore":
    echo "<script>window.location.replace('regvenditore.php');</script>";
   break;

/* Registra venditore 

    case "registra-venditore":
     $reg=1;    
     $pass=$awc->create_venditore($_POST['nome'],$_POST['job'],$_POST['tel'],$_POST['iva'],$_POST['note'],$_POST['email'],$_POST['pwd'],$reg);
     if($pass == true){
      session_start();
      $_SESSION['user']= $_POST['nome'];
      $_SESSION['mail']= $_POST['email']; 
      $_SESSION['liv']= 2;
      $page ="<script>window.location.replace('index.php');</script>";
     }else{
        $page = $awc->showmsg(json_encode([
        "status" => $pass,
        "message" => $pass ? "User Updated" : "Error updating user"
       ]),$_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
     }
     echo $page;
    break;

/* cancella venditore */

   case "Cancella-Venditore":
    $pass = $awc->delete_venditore($_POST['myid']);
      $page = $awc->showmsg(json_encode([
        "status" => $pass,
        "message" => $pass ? "User Updated" : "Error updating user"
       ]),$_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
     echo $page;
   break;

/* PRODOTTO */

/* Visualizza tutti i prodotti */

    case "get-allprod":
     $all = $awc->getallprod();
     echo $all;
    break;

/* tutti i prodotti in json */

  case "get-allprod_json":
    $all = $awc->getallprod();
      echo json_encode([
        "status" => $all==false?false:true,
        "message" => "TABELLA PRODOTTI",
        "data" => $all
      ]);
    break;

/* crea prodotto */

   case "Nuovo":
    $awc->create_prodotto($_POST['mynome'],$_POST['myvend'],$_POST['myprice'],
     $_POST['myquant'],$_POST['mytipo'],$_POST['mycateg'],$_POST['mycaratt'],
     '',$_POST['myrev'],$_POST['myoff'],$_POST['mycod'],$_POST['myimg'],$_POST['week']);
    $page ="<script>window.location.replace('modify.php');</script>";
    echo $page;
   break;

/* ricerca prodotti */

   case "ricerca-prodotti":
    $nome = $_POST['mynome'];
    $vend= $_POST['myvend'];
    $cat = $_POST['mycat'];
    $pricefrom = $_POST['mypricefrom'];
    $priceto = $_POST['mypriceto'];
    
    $page = $awc->headpage();
    $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
    $page .= "<div class=\"w3-panel w3-orange\"><h3>RISULTATI RICERCA</h3></div>";
    $page .= "<div id=\"fsearch\" style=\"position:relative;top:0;left:10px;float:left;display:inline;\" >";
    $page .= "<form action=\"request.php\" method=\"post\" >";
    $page .= "<input type=\"text\" name=\"mycat\" placeholder=\"Tipologia\" />";
    $page .= "<input type=\"text\" name=\"mynome\" placeholder=\"Nome Prodotto\" />";
    $page .= "<input type=\"text\" name=\"myvend\" placeholder=\"Brand\" />";
    $page .= "<input type=\"text\" name=\"mypricefrom\" placeholder=\"Prezzo da\" />";
    $page .= "<input type=\"text\" name=\"mypriceto\" placeholder=\"Prezzo a\" />";
    $page .= "<input type=\"hidden\" name=\"req\" value=\"ricerca-prodotti\" />";
    $page .= "<input type=\"submit\" value=\"INVIO\" />";
    $page .= "</form></div><br/><br/>";
    $page .=$awc->search_prodotto($nome,$vend,$cat,$pricefrom,$priceto,$_SESSION['mail']);
    $page .= $awc->closepage();
    echo($page);
   break;

/* aggiorna prodotto */

   case "INVIO":
    $awc->update_prodotto($_POST['mynome'],$_POST['myvend'],$_POST['myprice'],
    $_POST['myquant'],$_POST['mytipo'],$_POST['mycateg'],$_POST['mycaratt'],
    '',$_POST['myrev'],$_POST['myoff'],$_POST['mycod'],$_POST['myimg'],$_POST['week'],$_POST['myid']);
    $page ="<script>window.location.replace('modify.php');</script>";
     echo $page;
   break;

/* cancella prodotto */

   case "Cancella":
    $awc->delete_prodotto($_POST['myid']);
    $page ="<script>window.location.replace('modify.php');</script>";
     echo $page;
   break;

/* dirige alla pagina index.php */

   case "OK":
     echo "<script>window.location.replace('index.php');</script>";
   break;

/* CARRELLO */

/* CHECKOUT CARRELLO */

    case "docheckout":
     $page = $awc->headpage();
     $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);
     $page .= "<div class=\"w3-panel w3-orange\"><h3>GRAZIE PER L'ACQUISTO !</h3></div>"; 
     $page .= "<p><b>Acquisto Ordine n.</b>".$_POST['mynum']."&nbsp;&nbsp;<b>Costo:</b>&nbsp;".$_POST['mytot']."<br/>";
     $page .= "<b>Effettuato da:</b> ".$_POST['nome']."&nbsp;&nbsp;".$_POST['snome']."<br/>";
     $page .= "<b>Indirizzo:</b> ".$_POST['address']."&nbsp;&nbsp;<b>Citta':</b> ".$_POST['city']."&nbsp;&nbsp;<b>Provincia:</b> ".$_POST['prov']."<br/>";
     $page .= "<b>Email:</b> ".$_POST['email']."&nbsp;&nbsp;<b>Telefono:</b> ".$_POST['tel']."</b></p>";
     $page .= "<form action=\"request.php\" method=\"post\">";
     $page .= "<input type=\"submit\" name=\"req\" class=\"mybutton\" value=\"Elimina-Ordini-di-Oggi\" /><br/><br/>";
     $page .= "</form>";
     $page .= $awc->closepage();
    
    $reset=0;
    $awc->update_order($_POST['mynum'],$reset,$_POST['mytot'],$_POST['nome'],$_POST['snome'],$_POST['address'],$_POST['city'],$_POST['prov'],$_POST['email'],$_POST['tel'],$_POST['card'],$_POST['tcard']);
    $awc->update_stato_ordini($reset);

    unset($_SESSION['cart']);
    session_start();
    $_SESSION['cart']=0;

    echo $page;
   break;

/* Eliminazione Ordini per Data Odierna */

    case "Elimina-Ordini-di-Oggi":
     $timestamp = date('Y-m-d'); 
     //echo ('Today:'.$timestamp);
     $awc->del_order_bydate($timestamp);   
     $reset=0;
     $awc->update_stato_ordini($reset);
     echo "<script>window.location.replace('index.php');</script>";
    break;

/* LOGIN */

    case "login":

        $myliv=0;
        $mynome = $_POST['myname'];
        $myemail= $_POST['myemail'];
        $mypwd = $_POST['mypwd'];
        $myliv = $_POST['myliv'];

        $pass = $awc->login($myemail, $mypwd, $myliv);
        if ($pass!==false) { 
            session_start();
           
            $_SESSION['user']= $mynome;
            $_SESSION['mail']= $myemail; 
            $_SESSION['liv']= $myliv; 

            $page = $awc->headpage();
            $page .= $awc->navpage($_SESSION['cart'],$_SESSION['liv'],$_SESSION['user']);

         if($myliv == 1){

            $page .= "<div class=\"w3-panel w3-orange\"><h3>PROFILO CLIENTE</h3></div>"; 
            $page .= $awc->panel_cliente($myemail);

         }else if($myliv == 2){
          
            $page .= "<div class=\"w3-panel w3-orange\"><h3>PROFILO VENDITORE</h3></div>"; 
            $page .= $awc->panel_venditore($myemail);
            
         }
           $page .= $awc->closepage();
           echo $page;

        }else{
          session_start();
          $_SESSION['user']= $mynome;
          $_SESSION['mail']= '';
          $_SESSION['liv']= 0;
          
          echo "<script>window.location.replace('register.php');</script>";
        }
   break;

  }
}
?>