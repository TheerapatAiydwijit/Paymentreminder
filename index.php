<?php
include("include/Connect.php");
session_start();
if(isset($_COOKIE['USERID'])) {
    header("Location: admin.php");
}elseif(isset($_SESSION['USERID'])) {
    header("Location: admin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <!--===============================================================================================-->
    <title>Login</title>
    <script>
        $(document).ready(function() {
            $(document).keypress(function(e) {
                var key = e.which;
                if (key == 13) // the enter key code
                {
                    e.preventDefault();
                    $("#login").trigger("click");
                }
            });
            $('#login').on('click', function() {
                var user = $('#Username').val();
                var pass = $('#Password').val();
                var checked = $('#savecookie');
                var cookie = "0";
                if (checked.prop('checked')) {
                    cookie = "1";
                }
                $.ajax({
                    url: "Process/login.php",
                    type: "post",
                    data: {
                        user,
                        pass,
                        cookie
                    },
                    success: function(require) {
                        console.log(require);
                        if (require == "1") {
                            Swal.fire(
                                'Good job!',
                                'You clicked the button!',
                                'success'
                            )
                            setTimeout(function() {
                                location.href = "admin.php"
                            }, 1000);
                            // setTimeout(window.location.href = "admin.php", 1000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
                                footer: '<a href>Why do I have this issue?</a>'
                            })
                        }
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="images/logo.jpg" alt="IMG" width="100%">
                </div>

                <form class="login100-form validate-form">
                    <span class="login100-form-title">
                        Payment Login
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Username" id="Username">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <img src="images/icons/user.png" alt="">
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="pass" placeholder="Password" id="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <img src="images/icons/key.png" alt="">
                        </span>
                    </div>
                    <input type="checkbox" id="savecookie" name="savelogin">
                    <label for="savecookie">คงอยู่ในระบบต่อไป</label>
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="button" id="login">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>