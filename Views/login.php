<html>
<head>
<link rel="stylesheet" href="../css/login.css">
</head>
<body> <!--Vormgeving form om in te loggen en bijbehorende input velden en button.-->  
            <!--Titel boven het formulier.-->
    <div class="page-title">
        Ledenadministratie biljartvereniging 'De Kromme Keu'
    </div>

    <div class="form-container">                <!--wrapper om flexbox toe te passen.-->  
        <form class="form-signin" method="post" action="/index.php"> <!--action="/login" gebruikt voor het juist doorverwijzen via .htaccess naar index.php.-->
            <div class="form-label-group">
                <input type="text" class="form-control" placeholder="Username" required autofocus name="uname">
            </div>

            <div class="form-label-group">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="psw">
            </div>

            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>