<?php

/* Libreria config.php gestisce connessione al Db awc */

require("config.php");

/* AWC OGGETTO GENERALE DELL'APPLICAZIONE */

class awc{
  /* attributi di awc */
  private $pdo = null;  
  private $stmt = null;
  public $error = "";
  public $lastID = null;
  public $mypage = null;

/* metodo COSTRUTTORE di awc */

  function __construct(){
   /* ciclo try/catch */
    try {
      $this->pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_DB.";charset=".DB_CHAR,
        DB_USER, DB_PASS, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false,
        ]
      );
    /* in caso di errore catch esegue eccezione $ex */
    } catch (Exception $ex) { die($ex->getMessage()); }

  }

/* metodo distruttore di AWC */
  function __destruct(){
    if ($this->stmt!==null) { $this->stmt = null; }
    if ($this->pdo!==null) { $this->pdo = null; }
  }

/* Query esegue le richieste SQL al database awc */

  function query($sql, $cond=[]){
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($cond);
    } catch (Exception $ex) { 
      $this->error = $ex->getMessage();
      return false;
    }
    $this->stmt = null;
    return true;
  }

/* start inizia le richieste pdo al db */
 function start() {
  // start() : auto-commit off
    $this->pdo->beginTransaction();
  }

/* end conclude richieste pdo al db con possibilità di recupero */
/* richieste precedenti (rollback) */

  function end($commit=1) {
  // end() : commit or roll back?
    if ($commit) { $this->pdo->commit(); }
    else { $this->pdo->rollBack(); }
  }


/* funzioni di scrittura della pagina html */

  function headpage(){
    $html ="<html id=\"alldoc\"><head><meta charset=\"utf-8\" />";
    $html .="<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
    $html .="<link href=\"css/w3.css\" rel=\"stylesheet\">";
    $html .="<link href=\"css/style.css\" rel=\"stylesheet\">";
    $html .="<script type=\"text/javascript\" src=\"ajaxlib.js\"></script>";
    return $html;
   }

/* navapage() scrive la sezione di navigazione della pagina Html */

  function navpage($cart,$liv,$usr){
   if(!empty($cart)) {
     $ccount = $cart;
    }else{ $ccount=0; }
    $html = "</head><body><nav class=\"w3-bar w3-black\"><ul>";
    $html .="<li class=\"my_item1\"><img src=\"images/logo-sito.png\" class=\"mylogo\"></li>";
     if(!empty($liv)){
       if(($liv == 2)||($liv == 1)){
        $html .= "<li class=\"my_item2\"><a href=\"logout.php\">|Logout</a></li>";
        $html .= "<li class=\"my_item2\"><a href=\"login.php\">|Profilo</a></li>";
       }
     }else{ $html .= "<li class=\"my_item2\"><a href=\"login.php\">|Accesso</a></li>"; }
    $html .= "<li class=\"my_item2\"><a href=\"cart.php\">|Acquisti</a></li>";
    $html .= "<li class=\"my_item2\"><a href=\"search.php\">|Ricerca</a></li>";
     if(!empty($liv)){
       if($liv == 2){
         $html .= "<li class=\"my_item2\"><a href=\"modify.php\">|Modifica</a></li>";
        }
      }
    $html .= "<li class=\"my_item2\"><a href=\"index.php\">|Home</a></li>";
  if(!empty($usr)){ 
      if($usr != ''){ $html .='User: '.$usr; }
    }
   $html .= "<li class=\"my_item2\"><img src=\"images/cart-white.png\" class=\"mylogo2\"><div id=\"ccart\" class=\"my_item0\">";
   $html .= $ccount."</div></li></ul></nav>";
   $html .= "<div class=\"w3-container\" class=\"my_bar1\">";
  return $html;
  }

/* pagecontent() è la funzione dei Contenuti della pagina Html */

  function pagecontent($title){
   $html ="<div class=\"w3-panel w3-orange\"><h3>".$title."</h3></div>";
   $html .="<div id=\"tabprodotti\" name=\"all\" value=\"\"></div><br/><br/>";
   return $html;
  }

/* closepage() è la funzione del Footer della pagina Html */

  function closepage(){
    $html = "</div><footer class=\"w3-bar w3-gray footer_fixed\">Copyright (c) 2019</footer></body></html>";
   return $html;
  }

/* showmsg() visualizza messaggi da json sulla pagina Html */

  function showmsg($msg,$cart,$liv,$usr){
    $html ="<html id=\"alldoc\"><head><meta charset=\"utf-8\" />";
    $html .="<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
    $html .="<link href=\"css/w3.css\" rel=\"stylesheet\">";
    $html .="<link href=\"css/style.css\" rel=\"stylesheet\">";
    $html .="<script type=\"text/javascript\" src=\"ajaxlib.js\"></script>";

    if(!empty($cart)) {
     $ccount = $cart;
    }else{ $ccount=0; }
    $html = "</head><body style=\"background-repeat:no-repeat\"><nav class=\"w3-bar w3-black\"><ul>";
    $html .="<li style=\"position:relative;left:0;margin:0;padding:0;display:inline;\"><img src=\"images/logo-sito.png\" style=\"position:relative; float:left; display:inline; top:-5px; width:60px; height:60px; padding-right:5px\"></li>";
     if(!empty($liv)){
       if(($liv == 2)||($liv == 1)){
        $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><a href=\"logout.php\" style=\"text-decoration:none\">|Logout</a></li>";
        $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><a href=\"login.php\" style=\"text-decoration:none\">|Profilo</a></li>";
       }
     }else{ $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><a href=\"login.php\" style=\"text-decoration:none\">|Accesso</a></li>"; }
    $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><a href=\"cart.php\" style=\"text-decoration:none\">|Acquisti</a></li>";
    $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><a href=\"search.php\" style=\"text-decoration:none\">|Ricerca</a></li>";
     if(!empty($liv)){
       if($liv == 2){
         $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><a href=\"modify.php\" style=\"text-decoration:none\">|Modifica</a></li>";
        }
      }
    $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><a href=\"index.php\" style=\"text-decoration:none\">|Home</a></li>";
  if(!empty($usr)){ 
      if($usr != ''){ $html .='User: '.$usr; }
    }
   $html .= "<li style=\"position:relative; float:right; display:inline; padding-left:30px\"><img src=\"images/cart-white.png\" style=\"position:relative; float:left; display:inline; top:-5px; width:40px; height:40px; padding-right:5px\"><div id=\"ccart\" style=\"float:right;display:inline;\">";

/* scrive $ccount del carrello */

   $html .= $ccount."</div></li></ul></nav>";
   $html .= "<div class=\"w3-container\" style=\"padding:10px,16px; margin-top:1%;\">";
    $html .= "<div class=\"w3-panel w3-orange\"><h3>".$msg."</h3></div>";
    $html .= "</div><footer class=\"w3-bar w3-gray\" style=\"position:fixed;bottom:0;left:0;\">Copyright (c) 2019</footer>";
    $html .= "</body></html>";
    return $html;
  }

  /* CLIENTE */

 /* getall_clienti() trova tutti i clienti */

  function getall_clienti(){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `clienti`");
    $this->stmt->execute();
    $users = $this->stmt->fetchAll();
    return count($users)==0 ? false : $users;
  }

/* getemail_cliente() Trova tutti i clienti per email e password */

  function getemail_cliente($email,$pwd){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `clienti` WHERE `email`=?");
    $cond = [$email];
    $this->stmt->execute($cond);
    $user = $this->stmt->fetchAll();
    $find=false;
/* check se id e password sono uguali allora è trovato */
    if(($user[0]['id'] > 0)&&($user[0]['password'] == $pwd)){ $find=true; }
    return $find;  //count($user)==0 ? false : $user[0];
  }

/* getid_cliente() trova tutti i clienti per id */

  function getid_cliente($id){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `clienti` WHERE `id`=?");
    $cond = [$id];
    $this->stmt->execute($cond);
    $user = $this->stmt->fetchAll();
    return count($user)==0 ? false : $user[0];
  }

/* create_cliente crea il cliente nel db */

  function create_cliente($name,$sname,$email,$address,$city,$prov,$tel,$acc,$pwd,$card,$pag,$pref,$priv,$tcard,$myreg){
    $myliv=1;
    return $this->query(
      "INSERT INTO `clienti` (`nome`,`cognome`,`email`,`indirizzo`,`citta`,`provincia`,`telefono`,
   `account`,`password`,`carta`,`pagamento`,`preferenze`,`privacy`,`tipocarta`,`livello`,`registrato`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
      [$name,$sname,$email,$address,$city,$prov,$tel,$acc,$pwd,$card,$pag,$pref,$priv,$tcard,$myliv,$myreg]);
  }

/* update_cliente aggiorna cliente nel db */

  function update_cliente($name,$sname,$email,$address,$city,$prov,$tel,$acc,$pwd,$card,$pag,$pref,$priv,$tcard,$id){
    $q = "UPDATE `clienti` SET `nome`=?,`cognome`=?,`email`=?,`indirizzo`=?,`citta`=?,`provincia`=?,`telefono`=?,`account`=?,`password`=?,`carta`=?,`pagamento`=?,`preferenze`=?,`privacy`=?,`tipocarta`=?";
    $cond = [$name,$sname,$email,$address,$city,$prov,$tel,$acc,$pwd,$card,$pag,$pref,$priv,$tcard];
    $q .= " WHERE `id`=?";
    $cond[] = $id;
    return $this->query($q, $cond);
  }

/* delete_cliente cancella cliente nel db tramite suo id */

  function delete_cliente($id){
    return $this->query(
      "DELETE FROM `clienti` WHERE `id`=?",[$id]);
   }

/* panel_cliente crea il Pannello Gestione Cliente */

  function panel_cliente($email){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `clienti` WHERE `email`=?");
    $cond = [$email];
    $this->stmt->execute($cond);
/* fetchAll restituisce in $user i dati del Cliente trovato */
    $user = $this->stmt->fetchAll();
  
    $page="<form action=\"request.php\" method=\"post\" style=\"position:relative;top:-10px;left:0;\" >";
    $page.="<input type=\"text\" name=\"nome\" value=\"".$user[0]['nome']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"snome\" value=\"".$user[0]['cognome']."\" required /><br/>";
    $page.="<input type=\"email\" name=\"email\" value=\"".$user[0]['email']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"address\" value=\"".$user[0]['indirizzo']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"city\" value=\"".$user[0]['citta']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"prov\" value=\"".$user[0]['provincia']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"tel\" value=\"".$user[0]['telefono']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"username\" value=\"".$user[0]['account']."\" required /><br/>";
    $page.="<input type=\"password\" name=\"pwd\" value=\"".$user[0]['password']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"tcard\" value=\"".$user[0]['tipocarta']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"card\" value=\"".$user[0]['carta']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"pag\" value=\"".$user[0]['pagamento']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"pref\" value=\"".$user[0]['preferenze']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"privacy\" value=\"".$user[0]['privacy']."\" required /><br/>";
    $page.="<input type=\"hidden\" name=\"myid\" value=\"".$user[0]['id']."\" >";
    $page.="<input type=\"submit\" name=\"req\" value=\"Invio-Cliente\" class=\"mybutton\" >";
    $page.="<input type=\"submit\" name=\"req\" value=\"Cancella-Cliente\" class=\"mybutton\" >";
    $page.="</form>";
    return $page;
  }


/* VENDITORE */

/* getall_venditori trova tutti i venditori nel db */

 function getall_venditori(){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `venditore`");
    $this->stmt->execute();
    $users = $this->stmt->fetchAll();
    $find=$user[0]['id'];
    return $find;  //count($users)==0 ? false : $users;
  }

/* getemail_venditore trova tutti i venditori per email */

  function getemail_venditore($email,$pwd){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `venditore` WHERE `email`=?");
    $cond = [$email];
    $this->stmt->execute($cond);
    $user = $this->stmt->fetchAll();
    $find=false;
    if(($user[0]['id'] > 0)&&($user[0]['password'] == $pwd)){ $find=true; }
    return $find;  //count($user)==0 ? false : $user[0];
  }

/* getid_venditore trova tutti i venditori per id */

  function getid_venditore($id){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `venditore` WHERE `id`=?");
    $cond = [$id];
    $this->stmt->execute($cond);
    $user = $this->stmt->fetchAll();
    return count($user)==0 ? false : $user[0];
  }

/* create_venditore crea venditore nel db */

  function create_venditore($name,$job,$tel,$iva,$note,$email,$pwd,$myreg){
    $myliv=2;
    return $this->query(
      "INSERT INTO `venditore` (`nome`,`attivita`,`telefono`,`iva`,`recensioni`,`email`,`password`,`livello`,`registrato`) VALUES (?,?,?,?,?,?,?,?,?)",
      [$name,$job,$tel,$iva,$note,$email,$pwd,$myliv,$myreg]);
  }

/* update_venditore aggiorna venditore nel db */

  function update_venditore($name,$job,$tel,$iva,$note,$email,$pwd,$id){
   $data = ['nome' => $name, 
  'attivita' => $job,
  'telefono' => $tel,
  'iva' => $iva,
  'recensioni' => $note,
  'email' => $email,
  'password' => $pwd,
  'id' => $id ];

   $sql = "UPDATE venditore SET nome=:nome,attivita=:attivita,telefono=:telefono,iva=:iva,recensioni=:recensioni,email=:email,password=:password WHERE id=:id";

   return $this->query($sql, $data);
  }

/* delete_venditore cancella cliente nel db tramite suo id */

  function delete_venditore($id){
    return $this->query(
      "DELETE FROM `venditore` WHERE `id`=?",[$id]);
  }


/* Pannello gestione venditore */

 function panel_venditore($email){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `venditore` WHERE `email`=?");
    $cond = [$email];
    $this->stmt->execute($cond);
    $user = $this->stmt->fetchAll();
    //count($user)==0 ? false : $user[0];
    $page="<form action=\"request.php\" method=\"post\">";
    $page.="<input type=\"text\" name=\"nome\" value=\"".$user[0]['nome']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"job\" value=\"".$user[0]['attivita']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"iva\" value=\"".$user[0]['iva']."\" required /><br/>";
    $page.="<input type=\"email\" name=\"email\" value=\"".$email."\" required /><br/>";
    $page.="<input type=\"text\" name=\"tel\" value=\"".$user[0]['telefono']."\" required /><br/>";
    $page.="<input type=\"text\" name=\"note\" value=\"".$user[0]['recensioni']."\" required /><br/>";
    $page.="<input type=\"password\" name=\"pwd\" value=\"".$user[0]['password']."\" required /><br/>";
    $page.="<input type=\"hidden\" name=\"myid\" value=\"".$user[0]['id']."\" >";  
    $page.="<input type=\"submit\" name=\"req\" value=\"Cancella-Venditore\" class=\"mybutton\" ><br/>";
    $page.="<input type=\"submit\" name=\"req\" value=\"Invio-Venditore\" class=\"mybutton\" >";
    $page.="</form>";
    return $page;
  }


/* PRODOTTI  */

/* getid_prodotto trova il prodotto per id */

function getid_prodotto($id){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `prodotti` WHERE `id`=?");
    $cond = [$id];
    $this->stmt->execute($cond);
    $prod = $this->stmt->fetchAll();
    return count($prod)==0 ? false : $prod[0];
  }

/* getemail_prodotto trova e visualizza prodotti acquistati con email utente */

function getemail_prodotto($email){
  $this->stmt = $this->pdo->prepare("SELECT * FROM orders WHERE email=?"); //stato = 0 AND 
  $cond = [$email];
  $this->stmt->execute($cond);

   if($this->stmt->rowCount() != 0) {
     $table ="<div class=\"w3-responsive\"><table class=\"w3-table-all\" style=\"color:black\">";
     $table .="<tr><th>Num</th><th>Nome</th><th>Ordine</th><th>Data</th><th>Prezzo</th></tr>";
       $ind=0;
       while($row= $this->stmt->fetch()){ 

  /* ordini che non hanno fatto checkout e stato=1 hanno costo = 0 */

        if($row['costo'] > 0){
         $ind++;
         $table .= "<tr><td>".$ind."</td>";
         $table .="<td>".$row['nome']." ".$row['cognome']."</td>";
         $table .= "<td>".$row['order_num']."</td>";
         $table .= "<td>".$row['order_date']."</td>";
         $table .= "<td>".$row['costo']."</td></tr>";   
        }
      }
     $table .="</table></div>";
    } 

   return $table;
  }

/* getallprod trova e visualizza tutti i prodotti */

function getallprod($param){
    $mysession = $param;

    $this->stmt = $this->pdo->prepare("SELECT * FROM `prodotti`");
    $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
    $this->stmt->execute();

    $table ="<div class=\"w3-responsive\"><table class=\"w3-table-all\" style=\"color:black\"><tr><th>ID</th>";
    $table .="<th>Nome</th><th>Tipologia</th><th>Tipo</th><th>Caratteristiche</th>";
    $table .="<th>Q.ta'</th><th>Produttore</th><th>Spedizione</th>";
    $table .="<th>Revisioni</th><th>Offerte</th><th>Prezzo</th>";
    $table .="<th>Immagine</th></tr>";

   if($this->stmt->rowCount() != 0) {

    while($row= $this->stmt->fetch()){

     if($row['disponibilita'] > 0){

/* tabella $table se prodotto è disponibile  */

      $table .= "<tr><td>".$row['id']."</td>";
      $table .= "<td>".$row['nome']."</td>";
      $table .= "<td>".$row['tipologia']."</td>";
      $table .= "<td>".$row['tipo']."</td>";
      $table .= "<td>".$row['caratteristiche']."</td>";
      $table .= "<td>".$row['disponibilita']."</td>";
      $table .= "<td>".$row['venditore']."</td>";
      $table .= "<td>".$row['spedizione']."</td>";
      $table .= "<td>".$row['revisioni']."</td>";
      $table .= "<td style=\"color:red;\">".$row['from_day']." ".$row['offerte']."</td>";
      $table .= "<td>".$row['prezzo']."</td>";
      $table .= "<td><img src=\"images/".$row['img']."\" width=\"150\" ><br/>";

      $table .= "<input type=\"button\" value=\"Add to Cart\" onclick=\"getaddcart('".$row['id']."','".$mysession."');\" class=\"mybutton my_item4\"></td></tr>";
      $table .= "<tr><td colspan=\"12\"><input type=\"text\" name=\"wrecens\" id=\"wrecens\" placeholder=\"Vostra Recensione...\" class=\"my_item5\" >";
      $table .= "<input type=\"button\" onclick=\"sendnote('Recensione','".$row['id']."')\" value=\"Invio\" class=\"mybutton my_item6\" /></td></tr>";
      
   }else{ 

/* tabella $table se prodotto non disponibile */

      $table .= "<tr><td>".$row['id']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['nome']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['tipologia']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['tipo']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['caratteristiche']."</td>";
      $table .= "<td style=\"color:red;\">".$row['disponibilita']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['venditore']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['spedizione']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['revisioni']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['from_day']." ".$row['offerte']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\">".$row['prezzo']."</td>";
      $table .= "<td style=\"text-decoration: line-through;\"><img src=\"images/".$row['img']."\" width=\"150\" ><br/>";

      $table .= "<input type=\"button\" value=\"Non Disponibile\" class=\"mybutton_not my_item4\"></td></tr>";
     
     }
    }
   }

   $table .= "</table></div>";
   return $table;
 }


/* prodmodify Modifica(trova e visualizza) prodotti per venditore registrato */

function prodmodify(){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `prodotti`");
    $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
    $this->stmt->execute();

    $table  ="<div class=\"w3-responsive\"><table class=\"w3-table-all\" style=\"color:black\"><tr><th>ID</th><th>Sel</th>"; 
    $table .="<th>Nome</th><th>Tipologia</th><th>Tipo</th><th>Caratteristiche</th>";
    $table .="<th>Q.ta'</th><th>Produttore</th><th>Spedizione</th>";
    $table .="<th>Revisioni</th><th>Offerte</th><th>Prezzo</th>";
    $table .="<th>Immagine</th></tr>";

   if($this->stmt->rowCount() != 0) {

    while($row= $this->stmt->fetch()){
      $table .= "<tr><td>".$row['id']."</td>";
      $table .= "<td><input type=\"checkbox\" id=\"sel".$row['id']."\" name=\"selez[]\" value=\"".$row['id']."\" onclick=\"getitems('schedaprod','".$row['id']."','');\"></td>"; 
      $table .= "<td>".$row['nome']."</td>";
      $table .= "<td>".$row['tipologia']."</td>";
      $table .= "<td>".$row['tipo']."</td>";
      $table .= "<td>".$row['caratteristiche']."</td>";
      $table .= "<td>".$row['disponibilita']."</td>";
      $table .= "<td>".$row['venditore']."</td>";
      $table .= "<td>".$row['spedizione']."</td>";
      $table .= "<td>".$row['revisioni']."</td>";
      $table .= "<td style=\"color:red;\">".$row['from_day']." ".$row['offerte']."</td>";
      $table .= "<td>".$row['prezzo']."</td>";
      $table .= "<td><img src=\"images/".$row['img']."\" width=\"150\"></td></tr>";
    }
   }

   $table .= "</table></div>";
   return $table;
  }

/* getschedaprod è la scheda gestione prodotti */

function getschedaprod($param){
   if($param != ''){
    $myparam = $param;
    $this->stmt = $this->pdo->prepare("SELECT * FROM `prodotti` WHERE id=?");
    $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
    $this->stmt->execute([$param]);
    $mydata = $this->stmt->fetchAll();
   }else{ $myparam=''; }

$scheda ="";
if($myparam != ''){
 $scheda .="<h3>SCHEDA PRODOTTO</h3>";
 $scheda .="<form action=\"request.php\" method=\"post\">";
 $scheda .="<input type=\"text\" id=\"mynome\" name=\"mynome\" placeholder=\"".$mydata[0]['nome']."\" value=\"".$mydata[0]['nome']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"myvend\" name=\"myvend\" placeholder=\"".$mydata[0]['venditore']."\" value=\"".$mydata[0]['venditore']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"mycateg\" name=\"mycateg\" placeholder=\"".$mydata[0]['tipologia']."\" value=\"".$mydata[0]['tipologia']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"mytipo\" name=\"mytipo\" placeholder=\"".$mydata[0]['tipo']."\" value=\"".$mydata[0]['tipo']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"myprice\" name=\"myprice\" placeholder=\"".$mydata[0]['prezzo']."\" value=\"".$mydata[0]['prezzo']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"myquant\" name=\"myquant\" placeholder=\"".$mydata[0]['disponibilita']."\" value=\"".$mydata[0]['disponibilita']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"mycaratt\" name=\"mycaratt\" placeholder=\"".$mydata[0]['caratteristiche']."\" value=\"".$mydata[0]['caratteristiche']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"myrev\" name=\"myrev\" placeholder=\"".$mydata[0]['revisioni']."\" value=\"".$mydata[0]['revisioni']."\"><br/>";
 $scheda .="<select id=\"week\" name=\"week\" style=\"position:relative;top:0;left:0;width:205px;\"><option value=\"Lunedi\">Lunedi'</option><option value=\"Martedi\">Martedi'</option><option value=\"Mercoledi\">Mercoledi'</option><option value=\"Giovedi\">Giovedi'</option><option value=\"Venerdi\">Venerdi'</option><option value=\"Sabato\">Sabato</option><option value=\"Domenica\">Domenica</option></select><br/>";
 $scheda .="<input type=\"text\" id=\"myoff\" name=\"myoff\" placeholder=\"".$mydata[0]['offerte']."\" value=\"".$mydata[0]['offerte']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"mycod\" name=\"mycod\" placeholder=\"".$mydata[0]['codice']."\" value=\"".$mydata[0]['codice']."\"><br/>";
 $scheda .="<input type=\"text\" id=\"myimg\" name=\"myimg\" placeholder=\"".$mydata[0]['img']."\" value=\"".$mydata[0]['img']."\"><br/>";
 $scheda .="<img src=\"images/".$mydata[0]['img']."\" width=\"150\" height=\"auto\"><br/>";
 $scheda .="<input type=\"hidden\" name=\"myid\" value=\"".$mydata[0]['id']."\" >"; 
 $scheda .="<input type=\"submit\" name=\"req\" value=\"Cancella\" class=\"mybutton\" >";
 $scheda .="<input type=\"submit\" name=\"req\" value=\"INVIO\" class=\"mybutton\" >";
 $scheda .="</form>";
 } else {
 $scheda .="<h3>SCHEDA PRODOTTO</h3>";
 $scheda .="<form action=\"request.php\" method=\"post\">";
 $scheda .="<input type=\"text\" id=\"mynome\" name=\"mynome\" placeholder=\"Nome Prodotto\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"myvend\" name=\"myvend\" placeholder=\"Produttore\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"mycateg\" name=\"mycateg\" placeholder=\"Categoria\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"mytipo\" name=\"mytipo\" placeholder=\"Tipo\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"myprice\" name=\"myprice\" placeholder=\"Prezzo\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"myquant\" name=\"myquant\" placeholder=\"Quantita'\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"mycaratt\" name=\"mycaratt\" placeholder=\"Caratteristiche\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"myrev\" name=\"myrev\" placeholder=\"Revisioni\" value=\"\"><br/>";
 $scheda .="<select id=\"week\" name=\"week\" style=\"position:relative;top:0;left:0;width:205px;\"><option value=\"Lunedi\">Lunedi'</option><option value=\"Martedi\">Martedi'</option><option value=\"Mercoledi\">Mercoledi'</option><option value=\"Giovedi\">Giovedi'</option><option value=\"Venerdi\">Venerdi'</option><option value=\"Sabato\">Sabato</option><option value=\"Domenica\">Domenica</option></select><br/>";
 $scheda .="<input type=\"text\" id=\"myoff\" name=\"myoff\" placeholder=\"Offerte\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"mycod\" name=\"mycod\" placeholder=\"Codice\" value=\"\"><br/>";
 $scheda .="<input type=\"text\" id=\"myimg\" name=\"myimg\" placeholder=\"Nome File Immagine\" value=\"\"><br/>"; 
 $scheda .="<input type=\"submit\" name=\"req\" value=\"Nuovo\" class=\"mybutton\" >";
 $scheda .="</form>";
}
 return $scheda;
}

/* create_prodotto crea prodotto nel db */

function create_prodotto($name,$vend,$price,$disp,$tipo,$tipolog,$caratt,$sped,$rev,$offer,$cod,$url,$week){
    return $this->query(
      "INSERT INTO `prodotti` (`nome`,`venditore`,prezzo,`disponibilita`,`tipo`,`tipologia`,`caratteristiche`,`spedizione`,`revisioni`,`offerte`,`codice`,`img`,`from_day`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
      [$name, $vend,$price,$disp,$tipo,$tipolog,$caratt,$sped,$rev,$offer,$cod,$url,$week]
    );
  }

/* update_prodotto aggiorna prodotto nel db */

  function update_prodotto($name,$vend,$price,$disp,$tipo,$tipolog,$caratt,$sped,$rev,$offer,$cod,$url,$week,$id){
    $q = "UPDATE `prodotti` SET `nome`=?,`venditore`=?,`prezzo`=?,`disponibilita`=?,`tipo`=?,`tipologia`=?,`caratteristiche`=?,`spedizione`=?,`revisioni`=?,`offerte`=?,`codice`=?,`img`=?,`from_day`=?";
    $cond = [$name,$vend,$price,$disp,$tipo,$tipolog,$caratt,$sped,$rev,$offer,$cod,$url,$week];
    $q .= " WHERE `id`=?";
    $cond[] = $id;
    return $this->query($q, $cond);
  }

/* delete_prodotto cancella il prodotto nel db */

  function delete_prodotto($id){
    return $this->query("DELETE FROM `prodotti` WHERE `id`=?",[$id]);
  }

/* search_prodotto ricerca e visualizza prodotto */

 function search_prodotto($nome,$vend,$cat,$pricefrom,$priceto,$param){
   $mysession = $param;
   $sql = "SELECT * FROM `prodotti` WHERE tipologia = :tipologia OR nome = :nome OR venditore = :venditore OR prezzo = :prezzo"; 
   $this->stmt = $this->pdo->prepare($sql);
   $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
   $this->stmt->execute(array(
    ':tipologia' => $cat, 
    ':nome' => $nome,
    ':venditore' => $vend, 
    ':prezzo' => $pricefrom
    ));
   
   $table ="<div class=\"w3-responsive\"><table class=\"w3-table-all\" style=\"color:black\"><tr><th>ID</th>";
   $table .="<th>Nome</th><th>Tipologia</th><th>Tipo</th><th>Caratteristiche</th>";
   $table .="<th>Q.ta'</th><th>Produttore</th><th>Revisioni</th>";
   $table .="<th>Offerte</th><th>Spedizione</th><th>Prezzo</th>";
   $table .="<th>Immagine</th></tr>";

   if($this->stmt->rowCount() != 0) {

/* prodotti trovati */

    while($row= $this->stmt->fetch()){
      $table .= "<tr><td>".$row['id']."</td>";
      $table .= "<td>".$row['nome']."</td>";
      $table .= "<td>".$row['tipologia']."</td>";
      $table .= "<td>".$row['tipo']."</td>";
      $table .= "<td>".$row['caratteristiche']."</td>";
      $table .= "<td>".$row['disponibilita']."</td>";
      $table .= "<td>".$row['venditore']."</td>";
      $table .= "<td>".$row['revisioni']."</td>";
      $table .= "<td>".$row['from_day']." ".$row['offerte']."</td>";
      $table .= "<td>".$row['spedizione']."</td>";
      $table .= "<td>".$row['prezzo']."</td>";
      $table .= "<td><img src=\"images/".$row['img']."\" width=\"150\" ><br/>";
      $table .= "<input type=\"button\" value=\"Add to Cart\" onclick=\"getaddcart('".$row['id']."','".$mysession."');\"";
      $table .= " style=\"margin:0;position:relative;width:100%;background-color:orange;color:white;border-radius:6px;\">";
      $table .= "</td></tr>";
    }
   } else {

/* Nessun risultato trovato */

     $table .= "<tr><td colspan=\"12\" style=\"background-color:red;\">NESSUN RISULTATO</td></tr>";
   }
   return $table;
  }


/* addrecensione aggiunge recensione Utente nel db e sulla pagina Html */

function addrecensione($id,$nota,$log){
 $q = "UPDATE `prodotti` SET `revisioni`=?";
 $cond = [$nota];
 $q .= " WHERE `id`=?";
 $cond[] = $id;
 $this->query($q, $cond);
 $table=$this->getallprod($log);
 return $table;
}


/* CARRELLO */

/* getid_order restituisce Ordine per id */

function getid_order($id){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `orders` WHERE `id`=?");
    $cond = [$id];
    $this->stmt->execute($cond);
    $order = $this->stmt->fetchAll();
    return count($order)==0 ? false : $order[0];
  }

/* getstatus_order restituisce lo stato ordine */

function getstatus_order($val){
 $this->stmt = $this->pdo->prepare("SELECT * FROM `orders` WHERE `stato`=?");
 $cond = [$val];
 $this->stmt->execute($cond);
 $result = array();
 $result = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
 return $result;
}

/* getnumordini() restituisce il numero di Ordini totali esistenti nel db */

function getnumordini(){
 $nRows = $this->pdo->query("select count(*) from orders")->fetchColumn(); 
 return $nRows;
}

/* addtocart aggiunge il prodotto al carrello acquisti */

function addtocart($id,$param){
 $myemail=$param; //email user sessione
 $cc=$_SESSION['cart'];

 $this->stmt = $this->pdo->prepare("SELECT * FROM `prodotti` WHERE `id`=?");
 $cond = [$id];
 $this->stmt->execute($cond);
 $prod = $this->stmt->fetchAll();

 //cerca ultimo id in orders
 $this->stmt = $this->pdo->prepare("SELECT MAX(id) AS max_id FROM `orders`");
 $this->stmt->execute();
 $invNum = $this->stmt -> fetch(PDO::FETCH_ASSOC);
 $mynum = $invNum['max_id'];

 $order=$this->getid_order($mynum);

 $cdate = date('Y-m-d');  //data attuale
 $qty=1;  // quantita' dell'articolo inserito = 1
 $status_on=1;  //stato dell'ordine = 1 cioe' attivo
 $status_off=0; //stato dell'ordine eseguito = 0

if($order[0]['stato'] == 1){

   $this->query("INSERT INTO `orders_items` (`order_id`,`product_id`,`product_name`,`product_price`,`quantity`,`date`,`product_image`) VALUES (?,?,?,?,?,?,?)",[$mynum,$id,$prod[0]['nome'],$prod[0]['prezzo'],$qty,$cdate,$prod[0]['img']]);

 }else{
  $this->query("INSERT INTO `orders` (`order_num`,`order_date`,`stato`,`email`) VALUES (?,?,?,?)",[$mynum+1,$cdate,$status_on,$myemail]);
   $this->query("INSERT INTO `orders_items` (`order_id`,`product_id`,`product_name`,`product_price`,`quantity`,`date`,`product_image`) VALUES (?,?,?,?,?,?,?)",[$mynum+1,$id,$prod[0]['nome'],$prod[0]['prezzo'],$qty,$cdate,$prod[0]['img']]);
   
  $this->less_disponibilita($id);
 }

 $_SESSION['cart']=$cc+1;  //$myid;
 return $cc+1;
}

/* deleteorder cancella ordine acquisto effettuato tramite suo id */

function deleteorder($id){
  $this->more_disponibilita($id);
  return $this->query("DELETE FROM `orders_items` WHERE `id`=?",[$id]);
 }

/* update_order aggiorna ordine nel db */

function update_order($id,$stato,$costo,$nome,$cognome,$indirizzo,$city,$prov,$email,$tel,$codicecarta,$tipocarta){
 $data = ['stato' => $stato, 
  'costo' => $costo,
  'nome' => $nome,
  'cognome' => $cognome,
  'indirizzo' => $indirizzo,
  'city' => $city,
  'prov' => $prov,
  'email' => $email,
  'tel' => $tel,
  'codicecarta' => $codicecarta,
  'tipocarta' => $tipocarta,
  'id' => $id ];

 $sql = "UPDATE orders SET stato=:stato,costo=:costo,nome=:nome,cognome=:cognome,indirizzo=:indirizzo,city=:city,prov=:prov,email=:email,tel=:tel,codicecarta=:codicecarta,tipocarta=:tipocarta WHERE id=:id";

 $this->stmt = $this->pdo->prepare($sql);
 $this->stmt->execute($data);

 return false;
}

/* update_order_stato aggiorna lo stato di un Ordine */

function update_order_stato($stato,$id){
 $data = ['stato' => $stato, 'id' => $id ];
 $sql = "UPDATE orders SET stato=:stato WHERE id=:id";
 $this->stmt = $this->pdo->prepare($sql);
 $this->stmt->execute($data);
 return false;
}

/* update_stato_ordini aggiorna lo stato di Tutti gli Ordini */

function update_stato_ordini($stato){
 return $this->query("UPDATE `orders` SET stato=?",[$stato]);
}
 
/* setorder_stato aggiorna Ordine nel db */

 function setorder_stato($id,$status){
    $q = "UPDATE `orders` SET `stato`=?";
    $cond = [$status];
    $q .= " WHERE `id`=?";
    $cond[] = $id;
    return $this->query($q, $cond);
  }

/* getcart Visualizza Ordini del Carrello Utente per email */

 function getcart($param){
    
    $myemail = $param;

    $this->stmt = $this->pdo->prepare("SELECT * FROM orders JOIN orders_items ON orders.id = orders_items.order_id WHERE orders.stato = 1 AND orders.email = ?"); 

    $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
    $this->stmt->execute([$myemail]);

    $table='';
  
  if($this->stmt->rowCount()=='0') {
    $table .="<div style=\"background-color:red;color:white;width:100%;\"><h4><b>CARRELLO VUOTO !</b></h4></div>";
   }else{

    $table .="<div class=\"w3-responsive\"><table class=\"w3-table-all\" style=\"color:black\"><tr>";
    $table .="<th>Ordine</th><th>Id</th><th>Elimina</th><th>Prodotto</th><th>Q.ta'</th><th>Prezzo</th><th>Data</th><th></th></tr>";
    $totale=0;
    $invia = 0;
 
    while($row= $this->stmt->fetch()){
     
      $table .= "<tr><td>".$row['order_id']."</td>";
      $table .= "<td>".$row['id']."</td>";
      $table .= "<td><input type=\"checkbox\" id=\"sel".$row['id']."\" name=\"selez[]\" value=\"".$row['id']."\" onclick=\"getpage('del_order','".$row['id']."','".$myemail."')\"></td>";
      $table .= "<td>".$row['product_name']."</td>";
      $table .= "<td>".$row['quantity']."</td>";
      $table .= "<td>".$row['product_price']."</td>";

      $totale=$totale+$row['product_price'];

      $table .= "<td>".$row['date']."</td>";
      $table .= "<td><img src=\"images/".$row['product_image']."\" width=\"150\" \"></td>";

      $myorder = $row['order_id'];
     }

    $table .= "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><b>Totale:".$totale."</b></td><td><input type=\"button\" class=\"mybutton\" value=\"Checkout\" onclick=\"getitems('check-out','".$totale."','".$myorder."')\"></td></tr>";
    $table .= "</table></div>";

   if($myemail != ''){

/* visualizza lo Storico degli acquisti dell'utente */

    $table .= "<br/><br/>".$this->getemail_prodotto($myemail);

    }
  }
 return $table;
 }


/* less_disponibilita Calcolo Disponibilita' di un prodotto detraendone uno per acquisto */

 function less_disponibilita($idord){
   $sql="SELECT disponibilita FROM prodotti WHERE id = ?";
   $cond[] = $idord;
   $this->stmt = $this->pdo->prepare($sql);
   $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
   $this->stmt->execute($cond);
    while($row= $this->stmt->fetch()){
     if($row['disponibilita'] > 0){
      $mydisp = $row['disponibilita']-1;
     }else{ $mydisp = 0; }
    }

   $sql2="UPDATE prodotti SET disponibilita = ?";
   $cond = [$mydisp];
   $sql2 .= " WHERE id=?";
   $cond[] = $idord;
   $this->stmt = $this->pdo->prepare($sql2);
   $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
   $this->stmt->execute($cond);

  return false;
 }

/* more_disponibilita Calcolo Disponibilita' di un prodotto aggiungendone uno */
/* dopo cancellazione acquisto */

function more_disponibilita($idord){
   $sql="SELECT disponibilita FROM prodotti WHERE id = ?";
   $cond[] = $idord;
   $this->stmt = $this->pdo->prepare($sql);
   $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
   $this->stmt->execute($cond);
   $mydisp = 0;
    while($row= $this->stmt->fetch()){
      $mydisp = $row['disponibilita']+1;
    }

   $sql2="UPDATE prodotti SET disponibilita = ?";
   $cond = [$mydisp];
   $sql2 .= " WHERE id=?";
   $cond[] = $idord;
   $this->stmt = $this->pdo->prepare($sql2);
   $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
   $this->stmt->execute($cond);

  return false;
 }

/* getcheckout è il CHECKOUT Finale Acquisti Utente */

 function getcheckout($tot,$id){

/* aggiorna costo totale ordine */

  $data = ['costo' => $tot, 'id' => $id ];
  $sql = "UPDATE orders SET costo=:costo WHERE id=:id";
  $this->stmt = $this->pdo->prepare($sql);
  $this->stmt->execute($data);

  $form ="<h3><B>CHECKOUT ACQUISTI</B></h3>";
  $form .= "<form action=\"request.php\" method=\"post\">";
  $form .= "<input type=\"text\" name=\"nome\" placeholder=\"Nome\" required /><br/>";
  $form .= "<input type=\"text\" name=\"snome\" placeholder=\"Cognome\" required /><br/>";
  $form .= "<input type=\"email\" name=\"email\" placeholder=\"Email\" required /><br/>";
  $form .= "<input type=\"text\" name=\"address\" placeholder=\"Indirizzo\" required /><br/>";
  $form .= "<input type=\"text\" name=\"city\" placeholder=\"Citta'\" required /><br/>";
  $form .= "<input type=\"text\" name=\"prov\" placeholder=\"Provincia\" required /><br/>";
  $form .= "<input type=\"text\" name=\"tel\" placeholder=\"Telefono\" required /><br/>";
  $form .= "<input type=\"text\" name=\"tcard\" placeholder=\"Tipo Carta\" required /><br/>";
  $form .= "<input type=\"text\" name=\"card\" placeholder=\"Codice Carta\" required /><br/>";
  $form .= "<br/><label name=\"tot\"><b>Totale :".$tot."&nbsp;Euro</b></label><br/><br/>";
  $form .= "<input type=\"hidden\" name=\"mytot\" value=\"".$tot."\" />";  
  $form .= "<input type=\"hidden\" name=\"mynum\" value=\"".$id."\" />";
  $form .= "<input type=\"hidden\" name=\"req\" value=\"docheckout\" />";
  $form .= "<input type=\"submit\" value=\"ACQUISTA\" class=\"mybutton\" /></form></div>";
  echo $form;
 }


/* del_order_bydate cancella ordine per data acquisto */

 function del_order_bydate($date){
   $mytime = $date.' 00:00:00';
   //echo ('Time:'.$mytime);
   $this->stmt = $this->pdo->prepare("DELETE FROM `orders` WHERE `order_date`=?");
   $cond = [$mytime];
   $this->stmt->execute($cond);
  }

/* [LOGIN] */

  function login($email,$password,$liv){
    $logged=false;
    switch ($liv){
      //cliente
      case 1: { $logged = $this->getemail_cliente($email,$password); break; }
      //venditore
      case 2: { $logged = $this->getemail_venditore($email,$password); break; }
    }
   return $logged;
 }

}
?>