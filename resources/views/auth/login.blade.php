<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: rgb(60, 120, 216);
            background-image: url('/images/retirement_home.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: flex-end; 
            align-items: center;     
            padding-right: 150px;      
        }

        #parent-div {
            display: flex;
            flex-direction: column;
            background-color: rgb(182, 215, 168);
            height: 600px;
            width: 350px;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        h1 {
            font-weight: 400;
            margin-bottom: 80px;
            font-size: 50px;
        }

        input {
            display: flex;
            font-size: 25px;
            font-weight: 300;
            width: 94.5%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid black;
            border-radius: 3px;
            background-color: rgb(201, 218, 248);
        }

        #login_body_div {
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        #btn_div {
            display: flex;
            align-items: center;
        }

        #login_btn {
            width: 100%;
            padding: 10px;
            background-color: rgb(111, 168, 220);
            border: none;
            color: black;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 3px;
            margin-top: 10px;
        }

        #login_btn:hover {
            background-color: rgb(90, 150, 200);
            color: white;
        }
    </style>
</head>
<body>
    <div id="parent-div">
        <h1>Login</h1>
        <div id="login_body_div">
            <input type="text" name="Email_Login" id="email_login" placeholder="Email">
            <input type="password" name="Password_Login" id="password_login" placeholder="Password">
        </div>

        <div id="btn_div">
            <button id="login_btn">Login</button>
        </div>
        
        <p>
            Not a user yet? Sign Up <a href="{{ route('welcome') }}">Here</a>
        </p>
    </div>
</body>
</html>
