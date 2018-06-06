<!DOCTYPE html>
<html lang="en">
<head>
<title>Task Management</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
    href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script
    src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script
    src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css">
</head>
<body>
    <ul class="w3-navbar w3-light-grey w3-border">
        <li><a href="#">Task Management</a></li>
        @if (session()->get('name'))
        <li class="w3-right">
                Welcome {{session()->get('name') }} , <a class="w3-right" href="{{asset('logout') }}">Logout</a> </li> @else
        <li class="w3-right"><a class="w3-green" href="#" id="auth"
            onclick="document.getElementById('authentication').style.display='block'">SignIn/SignUp</a></li>@endif
    </ul>
    <div id="container"></div>
    <div id="authentication" class="w3-modal">
        <span
            onclick="document.getElementById('authentication').style.display='none'"
            class="w3-closebtn w3-grey w3-hover-red w3-container w3-padding-16 w3-display-topright">X</span>

        <div class="w3-modal-content w3-card-8 w3-animate-zoom"
            style="max-width: 600px">

            <div class="col-md-6 w3-card-8 w3-teal" onclick="openForm('Login')">
                <h3>Sign In</h3>
            </div>
            <div class="col-md-6 w3-card-8 w3-teal"
                onclick="openForm('Register')">
                <h3>Sign Up</h3>
            </div>
            <div style="margin-top: 25px !important;">
                <div id="Login" class="w3-container form">
                    <div class="w3-container ">
                        <div class="w3-section">
                           <div class="alert alert-danger fade in" id="loginerrmsg" hidden>
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    <strong>Error!</strong> A problem has been occurred while submitting your data.
                            </div>
                           
                            <form action="{{ route('login') }}" method="POST" id="loginform">
                                {{ csrf_field() }} <input type="hidden" name="redirurl"
                                    value="{{ $_SERVER['REQUEST_URI'] }}"> <label><b>Username</b></label>
                                <input name="username"
                                    class="w3-input w3-border w3-margin-bottom" type="text"
                                    placeholder="Enter Username" required> <label><b>Password</b></label>
                                <input class="w3-input w3-border w3-margin-bottom"
                                    name="password" type="password" placeholder="Enter Password"
                                    required> <input type="submit"
                                    class="w3-btn w3-btn-block w3-green" id="loginbutton" value="Login"> 
                            </form>
                        </div>
                    </div>
                    <div class="w3-container w3-border-top w3-padding-16 ">
                        <button
                            onclick="document.getElementById('authentication').style.display='none'"
                            type="button" class="w3-btn w3-red">Cancel</button>
                        
                    </div>
                </div>
            </div>
            <div id="Register" class="w3-container form ">
                <div class="w3-container">
                    <div class="w3-section">

                        <br> <br> 
                        @if (count($errors->register) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->register->all() as $error)
                                <P>{{ $error }}</p>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ asset('register') }}" method="POST" id="regForm">
                            {{ csrf_field() }}
                             <input type="hidden" name="redirurl"
                                value="{{ $_SERVER['REQUEST_URI'] }}"> 
                            <label><b>Email</b></label>
                            <input class="w3-input w3-border w3-margin-bottom" type="text"
                                name="email" placeholder="Enter Email"
                                value="{{ old('email') }}" required>
                            <label><b>User Type</b></label>
                            <select class="w3-input w3-border w3-margin-bottom" name="user_role_id" >
                            @foreach($userrole as $user_role)
                            <OPTION value = '{{$user_role->id}}'>{{$user_role->user_role}}</OPTION>
                            @endforeach
                            </select>   
                            <label><b>Name</b></label>
                            <input class="w3-input w3-border w3-margin-bottom" type="text"
                                name="name" placeholder="Enter Name" required
                                value="{{ old('name') }}"> 
                            <label><b>Password</b></label> 
                            <input
                                class="w3-input w3-border w3-margin-bottom" type="password"
                                name="password" required placeholder="Enter Password"> 
                            <label><b>Confirm Password</b> </label> 
                            <input
                                class="w3-input w3-border w3-margin-bottom" required
                                type="password" name="password_confirmation"
                                placeholder="Enter Password">

                            <button type="submit" class="w3-btn w3-btn-block w3-green">SignUp</button>
                        </form>
                    </div>
                </div>
                <div class="w3-container w3-border-top w3-padding-16 ">
                    <button
                        onclick="document.getElementById('authentication').style.display='none'"
                        type="button" class="w3-btn w3-red">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="fluid-container"></div>
    <script>    
openForm("Login");
function openForm(formName) {
    
    var x = document.getElementsByClassName("form");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
    }
    document.getElementById(formName).style.display = "block";  
}
</script>
<script src="{{ asset('js/oauth.js') }}"></script> 
@if (Session::has('message'))
    <script>  $('#auth').click(); </script>
    @endif @if($errors->login->any())
    <script>  $('#auth').click();</script>
    @endif @if($errors->register->any())
    <script>  $('#auth').click(); openForm('Register');</script>
    @endif
</body>
</html>