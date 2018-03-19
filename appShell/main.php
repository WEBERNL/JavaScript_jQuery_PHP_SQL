<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Main Page</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />

</head>

<body>
    
    <!-- documents are included by first referencing the $_SERVER autoglobal--> 
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/header.html"); ?>

    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/home.html"); ?>  
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/about.html"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/contact.html"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/signup.html"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/login.html"); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/manageAccount.html"); ?>

    <?php include($_SERVER['DOCUMENT_ROOT'] . "/appShell/footer.html"); ?>	
        
    <!-- code sources-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js\appShell.js"></script>
    
    <!--once the document is ready, the first section is displayed and links are enabled such that once a section is shown, sibling sections are hidden-->
    <script>
		$(document).ready(function() {
		    $('section').eq(0).show(); 
		    $('.navbar-nav').on('click', 'a', function() {
                $($(this).attr('href')).show().siblings('section').hide();
            });

            $('#loginSignUpLink').on("click", function(){
                $('#signupNavItem').click();
            });
		});
    </script>
    


</body>

</html>