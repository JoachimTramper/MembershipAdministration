<html>
<head>
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title> 
</head>
<body>   
            <!--Titel boven het formulier.-->
    <div class="page-title">
        Member Administration of Billiard Club 'The Crooked Cue'
    </div>

    <div class="form-container">                <!--Wrapper to apply flexbox-->  
        <form class="form-signin" method="post" action="/index.php"> <!-- Action submits to index.php for processing -->
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