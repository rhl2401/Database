<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Location tracking</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <script type="text/JavaScript" src="js/sha512.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>

    <link rel="stylesheet" href="style.css">
</head>
<body class="background">
    <div class="contianer-fluid">
        <div class="row justify-content-center no-gutters">
            <div class="col-10 col-sm-8 col-md-8 col-lg-6 col-xl-4">
                <div class="box">
                    <h1>Tracking at it's best!</h1>
                    <hr>
                    <p>Ever wondered where you have traveled? With this service, you can finally get the answer. Register below for an incredible tracking experience. <br>Start for free!</p>

                    <a href="#collapse-register" class="btn btn-primary" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapse-register">Sign up</a>
                    <a href="#collapse-login" class="btn btn-info" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapse-login">Login</a>

                    <div class="collapse" id="collapse-register">
                        <h2>Sign up</h2>
                        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" name="registration_form">
                            <div class="form-group">
                                <label for="fullname">Full name</label>
                                <input type="text" class="form-control" name="fullname" id="fullname">
                            </div>
                            <div class="row">
                                <div class="form-group col-12 col-md-6">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" aria-describedby="username-help">
                                    <small id="username-help" class="form-text text-muted">This will be used for login.</small>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password">
                                </div>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">I accept the terms and conditions</label>
                            </div>
                            <button type="button" onclick="formhash(this.form, this.form.password)" class="btn btn-primary">Register!</button>
                        </form>
                    </div>

                    <div class="collapse" id="collapse-login">
                        <h2>Login</h2>
                        <form action="includes/process_login.php" method="post" name="login_form">
                            <div class="row">
                                <div class="form-group col-12 col-md-6">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" aria-describedby="username-help">
                                    <small id="username-help" class="form-text text-muted">This was chosen when you registered.</small>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <button type="button" onclick="formhash(this.form, this.form.password)" class="btn btn-primary">Login</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>