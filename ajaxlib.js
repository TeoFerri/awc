// LIBRERIA AJAX GESTISCE FUNZIONI TRA LA PAGINA E REQUEST.PHP AL DB // 

function getitems(str1,str2,str3) {
  var xhttp;
  var tabprodotti = document.getElementById("tabprodotti");

  if (str1 == "") {
    tabprodotti.innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      tabprodotti.innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "request.php?q="+str1+"&p="+str2+"&v="+str3, true);
  xhttp.send();
}

function sendnote(str1,str2) {
  var xhttp;
  var tabprodotti = document.getElementById("tabprodotti");
  var note=document.getElementById("wrecens").value;
  
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     tabprodotti.innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "request.php?q="+str1+"&p="+str2+"&v="+note, true);
  xhttp.send();
}


function getpage(str1,str2,str3) {
  var xhttp;
  var alldoc = document.getElementById("alldoc");

  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     alldoc.innerHTML = this.responseText;
     window.location.reload();
    }
  };
  xhttp.open("GET", "request.php?q="+str1+"&p="+str2+"&v="+str3, true);
  xhttp.send();
}

function getaddcart(str,str2) {
  var xhttp;
  var ccart = document.getElementById("ccart");

  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     ccart.innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "request.php?q=addcart&p="+str+"&v="+str2, true);
  xhttp.send();
}