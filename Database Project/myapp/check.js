function check_name(uname)
{
  if (uname!="")
  {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      var message;
      var re  = /^[a-zA-Z0-9]\w*$/;
      if (this.readyState == 4 && this.status == 200) {
        switch(this.responseText) {
          case 'YES':
            if(uname.match(re)){
              message='OK!';
            }
            else{
              message='只能用大小寫英文和數字!';
            }
            break;
          case 'NO':
            message=' The username has been registered! :O';
            break;
          default:
            message= 'I give up';
            break;
        }
          document.getElementById("msg").innerHTML = message;
      }
    };
      xhttp.open("POST", "check_name.php", true);
      xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhttp.send("uname="+uname);
  }
  else
    document.getElementById("msg").innerHTML = "";
};

function check_pwd(pwd)
{
  var message;
  var re  = /^[a-zA-Z0-9]\w*$/;
    if(pwd.match(re)){
      message='OK!';
    }
    else if(!pwd.match(re)){
      message='只能用大小寫英文和數字!';
    }
  document.getElementById("msg2").innerHTML = message;
};

function com_pwd()
{
  pwd = document.form1.pwd.value;
  compwd = document.form1.compwd.value;
  var message;
  if(pwd==compwd){
    message='OK!';
  }
  else{
    message='密碼不相符';
  }
  document.getElementById("msg3").innerHTML = message;
};

function check_phone(phone)
{
  var message;
  var re  = /^[0-9]\w*$/;
  if(phone.match(re)){
    message='OK!';
  }
  else{
    message='只能用數字!';
  }
  document.getElementById("msg4").innerHTML = message;
};
/*
function check_shopname(sname)
{
  if (sname!="")
  {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      var message;
      var re  = /^[a-zA-Z0-9]\w*$/;
      if (this.readyState == 4 && this.status == 200) {
        switch(this.responseText) {
          case 'YES':
            if(sname.match(re)){
              message='OK!';
            }
            else{
              message='只能用大小寫英文和數字!';
            }
            break;
          case 'NO':
            message=' The username has been registered! :O';
            break;
          default:
            message=' Oops! There is something wrong! >:O';
            break;
        }
          document.getElementById("msg").innerHTML = message;
      }
    };
      xhttp.open("POST", "check_shopname.php", true);
      xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xhttp.send("sname="+sname);
  }
  else
    document.getElementById("msg").innerHTML = "";
};*/

function check_price(price)
{
  var message;
  if(price>=0){
    message='OK!';
  }
  else{
    message='不能是負數!!';
  }
  document.getElementById("msg2").innerHTML = message;
};
function check_amount(amount)
{
  var message;
  if(amount>=0){
    message='OK!';
  }
  else{
    message='不能是負數!!';
  }
  document.getElementById("msg3").innerHTML = message;
};