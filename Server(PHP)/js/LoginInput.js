
function loginValidate(loginForm){

var validationVerified=true;
var errorMessage="";


if (loginForm.myusername.value=="")
{
errorMessage+="Please Enter your E-mail!\n";
validationVerified=false;
}
if(loginForm.mypassword.value=="")
{
errorMessage+="Please Enter your password!\n";
validationVerified=false;
}
if (!isValidEmail(loginForm.myusername.value)) {
errorMessage+="Invalid email address provided!\n";
validationVerified=false;
}
if(!validationVerified)
{
alert(errorMessage);
}
if(validationVerified)
{

}
return validationVerified;
}


//validate email function
function isValidEmail(val) {
	var re = /^[\w\+\'\.-]+@[\w\'\.-]+\.[a-zA-Z]{2,}$/;
	if (!re.test(val)) {
		return false;
	}
    return true;
}





