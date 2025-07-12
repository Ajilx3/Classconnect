function emaivalidation(){
    var email=document.getElementById('email').ariaValueMax;
    var emailpattern=/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/;
    if(emailpattern.test(email)){
        return true;

    }else{
        alert ("enter a valid email");
        return false;

    }
}