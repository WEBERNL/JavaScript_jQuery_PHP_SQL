// global variables
var currentUser = null;  // initializing as "null" since this variable is intended to be an object variable
var token = "";  // initializing as "" since this variable is intended to be a single-valued variable

// once the document is ready, the toggleLoginLogoffItems() method is called with a "false" parameter
// if a "remember me" cookie exists, then the autologin() method is called
$(document).ready(function() {
    toggleLoginLogoffItems(false);

    if(retrieveCookie("name")) {
        automaticLogin();
    }
});

// method used to "toggle login and logoff items"
function toggleLoginLogoffItems(loggedin) {
    if(loggedin === true){
        $('.loggedOn').show();
        $('.loggedOff').hide();
    } else {
        $('.loggedOn').hide();
        $('.loggedOff').show();
    }
}


// method used to generate a random number to be used as a token if the user chooses the "remember me" option upon login
function generateRandomToken() {
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	var dayte = new Date();
	var dateInMilliseconds = dayte.getTime();

    for (var i=0; i < 25; i++) {
        text += possible.charAt(parseInt(Math.random() * possible.length));
    }

    return dateInMilliseconds + text;
}

// method to streamline code for creating cookies by specifying only name/value and number of days until expiration
function writeCookiePlusExpirationOnly(cookieNameVariable, cookieValueVariable, daysUntilExpirationVariable){
	if (cookieNameVariable && cookieValueVariable != "") {
		var timing = new Date();
		timing.setTime(timing.getTime()+ (daysUntilExpirationVariable*24*60*60*1000));
		
		document.cookie = cookieNameVariable + "=" + encodeURIComponent(cookieValueVariable) + "; expires=" + timing.toUTCString();
	}
}

// method to streamline code for deleting cookies by specifying only name and a negative number of days until expiration
function deleteCookie(cookieNameVariable, daysUntilExpirationVariable){
	if (cookieNameVariable) {
		var timing = new Date();
		timing.setTime(timing.getTime()+ (daysUntilExpirationVariable*24*60*60*1000));
		
		document.cookie = cookieNameVariable + "=" + "" + "; expires=" + timing.toUTCString();
	}
}

// method to streamline code for accessing cookies
function retrieveCookie(cookieNameVariable) {
	if(document.cookie) {
		var cookieArray = document.cookie.split("; "); //note that this accesses only the name/value components of the cookies...any optional components (such as path, etc) aren't returned
		for (var i = 0; i < cookieArray.length; i++) {
			if(cookieArray[i].split("=")[0] == cookieNameVariable) {
				return decodeURIComponent(cookieArray[i].split("=")[1]);
			}
		}
		
	}
}

// method called at "remember me" selection/deselection during login
$('#rememberMe').on('click', function() {
    if  ($("#rememberMe").prop("checked") === true) {
        token = generateRandomToken();       
    }else{
        token = "";
    } 
});

// method called at automatic login...to automatically login using the "remember me" cookie as authorization
function automaticLogin(){
    $.ajax({
            url: 'automaticLogin.php',
            type: 'POST',
            data:	{
                        rememberToken: retrieveCookie("name")   
                    }, 
            
            success:    function (result){
                            try {
                                data = JSON.parse(result);  // this method called to parse the data successfully returned from autoLogin.php
                                currentUser = data; // if the user credentials are in the database, the result parameter format is... {"id":"idValue","name":"nameValue","username":"usernameValue","email":"emailValue"} 
                                
                                $("#homeNavItem").click(); // this code simulates a click of the element that has an ID attribute of "homeNavItem"

                                toggleLoginLogoffItems(true);

                                $("#userLoginCredential").text("Welcome " + currentUser.name); // text() method used to modify the element's value                     
                               
                            } catch (exception) {
                                alert(exception);
                            }
                    },
            error:      function (xhr, ajaxOptions, thrownError) {     
                        alert("-ERROR:" + xhr.responseText + " - " + thrownError + " -OPTIONS" + ajaxOptions);
                    }
    }); 
        
}

// method called at login
$('#loginButton').on("click", function() {

    if ($("#username").val() === ""){
        alert ("Must enter username");
        return;
       
    }

    if ($("#pwd").val() === ""){
        alert ("Must enter password");
        return;
    }
         
        $.ajax({
            url: 'login.php',
            type: 'POST',
            data:	{
                        username:   $("#username").val(), 
                        password:   $("#pwd").val(),
                        rememberToken:  token         
                    }, 
            
            success:    function (result){
                            try {
                                data = JSON.parse(result);  // this method called to parse the data successfully returned from login.php
                                currentUser = data; // if the user credentials are in the database, the result parameter format is... {"id":"idValue","name":"nameValue","username":"usernameValue","email":"emailValue"} 
                                                                                 
                                $("#username").val("");                    // val() method used to modify the input element's value
                                $("#pwd").val("");                         // val() method used to modify the input element's value
                                $("#rememberMe").prop("checked", false);   // prop() method used to modify the input element's checked property/attribute

                                $("#homeNavItem").click();  // this code simulates a click of the element that has an ID attribute of "homeNavItem"
                               
                                toggleLoginLogoffItems(true);

                                $("#userLoginCredential").text("Welcome " + currentUser.name); // text() method used to modify the element's value                               
                                
                                if (token != ""){
                                writeCookiePlusExpirationOnly("name", token, 7);
                                } 
                                token = ""; 
                              
                                
                            } catch (exception) {
                                alert(exception);
                            }
                        },
            error:      function (xhr, ajaxOptions, thrownError) {     
                        alert("-ERROR:" + xhr.responseText + " - " + thrownError + " -OPTIONS" + ajaxOptions);
            }
        });
}); 
    
                    

// method called at logout
$('#logoutNavItem').on("click", function() {
    currentUser = null;
    deleteCookie("name", -3);  // this code removes "remember me" cookie upon logout (used -3 as arbitrary negative to delete cookie)
    toggleLoginLogoffItems(false);
    $("#homeNavItem").click();  // this code simulates a click of the element that has an ID attribute of "homeNavItem"        
});


// method called at signup
$('#signUpButton').on('click', function() {
    if ($("#signUpUsername").val() === ""){
        alert ("Must enter username");
        return;
       
    }

    if ($("#signUpName").val() === ""){
        alert ("Must enter name");
        return;
    }

    if ($("#signUpEmail").val() === ""){
        alert ("Must enter email");
        return;
       
    }

    if ($("#signUpPassword").val() === ""){
        alert ("Must enter password");
        return;
       
    }

    if($('#signUpPassword').val() != $('#signUpConfirmPassword').val()) {
        alert("Passwords must match");
        return ;
    }

    $.ajax({
        url: 'signup.php',
        type: 'POST',
        data:	{
                    username:   $("#signUpUsername").val(), 
                    name:       $("#signUpName").val(),
                    email:      $("#signUpEmail").val(),
                    password:   $("#signUpPassword").val()
                },
        success:	function(outData){

                        try {
                            data = JSON.parse(outData);  // this method called to parse the data successfully returned from signup.php
                            currentUser = data;  // outData parameter format is... {"id":"idValue","name":"nameValue","username":"usernameValue","email":"emailValue"} 

                            $("#signUpUsername").val("");
                            $("#signUpName").val("");
                            $("#signUpEmail").val("");
                            $("#signUpPassword").val("");
                            $("#signUpConfirmPassword").val("");

                            $("#homeNavItem").click();  // this code simulates a click of the element that has an ID attribute of "homeNavItem"

                            toggleLoginLogoffItems(true);

                            $("#userLoginCredential").text("Welcome " + currentUser.name); // text() method used to modify the element's value
                            
                        } catch (exception) {
                            alert(exception);
                        }
                    },
        error: 	    function (xhr, ajaxOptions, thrownError) {
                        alert("-ERROR:" + xhr.responseText + " - " + thrownError + " -OPTIONS" + ajaxOptions);
                    }
    });    		
});

// method called at "manage account" option selection
$('#manageAccountNavItem').on('click', function() {
    $('#manageAccountUsername').val(currentUser.username);
    $('#manageAccountName').val(currentUser.name);
    $('#manageAccountEmail').val(currentUser.email);
});

// method called at "manage account" form submission
$('#manageAccountButton').on('click', function() {

    if ($("#manageAccountUsername").val() === ""){
        alert ("Must enter username");
        return;
       
    }

    if ($("#manageAccountName").val() === ""){
        alert ("Must enter name");
        return;
    }

    if ($("#manageAccountEmail").val() === ""){
        alert ("Must enter email");
        return;
    }

    $.ajax({
        url: 'manageAccount.php',
        type: 'POST',
        data:	{
                    id:   currentUser.id,
                    username: $('#manageAccountUsername').val(),
                    name:  $('#manageAccountName').val(),
                    email: $('#manageAccountEmail').val()
                },
        success: function(result){

            try {
                data = JSON.parse(result);  // this method called to parse the data successfully returned from manageAccount.php
                currentUser = data; // result parameter format is... {"id":"idValue","name":"nameValue","username":"usernameValue","email":"emailValue"}
                                                           
                $('#manageAccountUsername').val("");            // val() method used to modify the input element's value
                $('#manageAccountName').val("");                // val() method used to modify the input element's value
                $('#manageAccountEmail').val("");               // val() method used to modify the input element's value

                $("#homeNavItem").click();   // this code simulates a click of the element that has an ID attribute of "homeNavItem"
               
                toggleLoginLogoffItems(true);

                $("#userLoginCredential").text("Welcome " + currentUser.name); // text() method used to modify the element's value
               
                
            } catch (exception) {
                alert(exception);
            }
        },
        error:  function (xhr, ajaxOptions, thrownError) {     
                alert("-ERROR:" + xhr.responseText + " - " + thrownError + " -OPTIONS" + ajaxOptions);

        }
    });
});

// method called to display modal that allows password updates...this method is called at "update password" selection on "manage account" form 
$('#manageAccountPasswordButton').on('click', function() {
    $('#manageAccountPasswordModal').css('display', 'block'); 
});
 
// method called to hide modal that allows password updates
$('#manageAccountPasswordModalExit').on('click', function() {
    $('#manageAccountPasswordModal').css('display', 'none'); 
});

// method called at "manage password" form submission
$('#manageAccountPasswordModalButton').on('click', function(){
    
    if ($("#manageAccountCurrentPassword").val() === ""){
        alert ("Must enter current password");
        return;
    }

    if ($("#manageAccountNewPassword").val() === ""){
        alert ("Must enter new password");
        return;
    }

    if ($("#manageAccountConfirmPassword").val() === ""){
        alert ("Must confirm new password");
        return;
    }  

    if($('#manageAccountNewPassword').val() != $('#manageAccountConfirmPassword').val()) {
        alert("Passwords must match");
        return ;
    }

    $.ajax({
        url: 'managePassword.php',
        type: 'POST',
        data:	{
                    id:   currentUser.id,
                    currentPassword: $('#manageAccountCurrentPassword').val(),
                    updatedPassword: $('#manageAccountConfirmPassword').val()
                },

        success: function(){
                $('#manageAccountCurrentPassword').val("");    // val() method used to modify the input element's value
                $('#manageAccountNewPassword').val("");        // val() method used to modify the input element's value
                $('#manageAccountConfirmPassword').val("");    // val() method used to modify the input element's value

                $('#manageAccountPasswordModalExit').click();  // this code simulates a click of the element that has an ID attribute of "manageAccountPasswordModalExit"
               
        },
        error:  function (xhr, ajaxOptions, thrownError) {     
                alert("-ERROR:" + xhr.responseText + " - " + thrownError + " -OPTIONS" + ajaxOptions);

        }
    }); 
});

