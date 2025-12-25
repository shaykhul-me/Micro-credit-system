<?php
if(isset($_GET['reset_password']) && $_GET['reset_password'] == "success"){
    $loginmsg = '<div class="alert alert-success">Password Change Successfully</div>';
}
include('loginprosad.php');
include('header.php');
?>
<?php
?>

<body class="bg-silver-300">
    <div class="content">
        <div class="brand">
            <a class="link" href="index.html">Next Barisal</a>
        </div>
        <form id="login-form" action="" method="post">
        <?php 
        if(isset($loginmsg)){
            echo $loginmsg;
        }
        ?>
            <h2 class="login-title">Log in</h2>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-envelope"></i></div>
                    <input class="form-control" type="text" name="email" placeholder="Username" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group-icon right">
                    <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group d-flex justify-content-between">
                <label class="ui-checkbox ui-checkbox-info">
                    <input type="checkbox">
                    <span class="input-span"></span>Remember me</label>
                <a href="forgot_password.php?step1=1">Forgot password?</a>
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block" type="submit" name="submit">Login</button>
            </div>
        </form>
    </div>
<?php include('footer.php') ?>