function validations(){
    return (emaivalidation()|| passvalidation());
}
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
function passvalidation(){
    var password=document.getElementById('password').ariaValueMax;
    var passpattern=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    if(passpattern.test(password)){
        return true;
    }else{
        alert("Must contain at least 6 characters, including 1 uppercase, 1 lowercase, and 1 number");
        return false;

    }
}