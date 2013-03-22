window.onload = initAll; 
var xhr = false;

function initAll() {
     document.getElementById("get").onclick = questrst;
}

function questrst() {
     makeRequest();
     return false;
}

function makeRequest() {
     if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
     }
     else {
        if (window.ActiveXObject) {
           try {
              xhr = new ActiveXObject("Microsoft.XMLHTTP");
           }
           catch (e) { }
        }
     }
    
     if (xhr) {
             
        document.getElementById("rst").innerHTML = "wait.";
        
        var a = document.getElementById('sectiona').value;
        var b = document.getElementById('sectionb').value;
        var c = document.getElementById('captcha').value;
        var poststr = "email=" + a + "&pwd=" + b + "&captcha=" + c;
        xhr.onreadystatechange = showContents;
        xhr.open("POST", 'dologin.php', true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send(poststr);
     }
     else {
        document.getElementById("rst").innerHTML = "yameidie";
     }
}

function showContents() {
    var outMsg = "";
    if (xhr.readyState == 4) {
        if (xhr.status == 200) {
                var outMsg = xhr.responseText;

            }
        }
        else {
            var outMsg = "yameidie " + xhr.status;
        }
        document.getElementById("rst").innerHTML = outMsg;
}   