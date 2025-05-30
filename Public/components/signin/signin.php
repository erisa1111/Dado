<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./signin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="cards">
        <div >
            <a class="card-toggle active" data-target="#nanny"><i class="fa-solid fa-hands-holding-child"></i></a>
            <a class="card-toggle" data-target="#parent" ><i class="fa-solid fa-user"></i></a>
        </div>
       
        <div class="card active" id="nanny">
            <div class="card-content">
                <div class="text">
                    <p>Are you looking for a job?</p>
                    <h1>Start your ChildCare <br> career today</h1>
                    <div class="buttons">
    <button class="btn btn_login" onclick="location.href='/views/login.php';"><strong>Log in</strong></button>
    <button class="btn btn_signup" onclick="location.href='/views/signup_n.php?role=nanny';"><strong>Sign up</strong></button>
</div>

                </div>
            </div>
        </div>
    
 
        <div class="card" id="parent">

            <div class="card-content">
                <div class="text">
                    <p>Are you looking for reliable childcare?</p>
                    <h1>Find the Perfect Nanny <br> for Your Kids</h1>
                    <div class="buttons">
                    <div class="buttons">
    <button class="btn btn_login" onclick="location.href='/views/login.php';"><strong>Log in</strong></button>
    <button class="btn btn_signup" onclick="location.href='/views/signup_p.php?role=parent';"><strong>Sign up</strong></button>
</div>

                </div>
            </div>
        </div>
     </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./signin.js"></script>
</body>
</html>
