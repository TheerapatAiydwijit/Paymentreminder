<?php
include("include/loginChek.php");
include('include/Connect.php');
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มรายชื่อ</title>
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script>
        $(document).ready(function() {
            $('#Cus_offerprice').submit(function(event) {
                // console.log("Dawfwa");
                event.preventDefault();
                var key = "Add";
                var data = new FormData(this);
                data.append("Add", key);
                $.ajax({
                    url: "Process/Cus_offerprice.php",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    type: "post",
                    success: function(require) {
                        console.log(require);
                        if (require == "1") {
                            $('#Cus_offerprice').trigger("reset");
                            Notifytoprigth("บันทึกข้อมูลสำเหร็ยจ", "success");
                            // console.log(require);
                        } else {
                            if (require == "2") {
                                // $('#cusomeradd').trigger("reset");
                                Notifytoprigth("แก้ไขข้อมูลสำเหร็ยจ", "success");
                                setTimeout(() => {
                                    window.location.href = "Customerlist.php";
                                }, 1300);
                            } else {
                                console.log(require);
                            }
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            });
        });
    </script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <aside class="main-sidebar sidebar-dark-primary elevation-4 pt-2">
            <?php include("include/sidebar.php"); ?>
        </aside>
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <?php
            include("include/navbar.php");
            ?>
        </nav>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                                <li class="breadcrumb-item"><a href="Choicecustomer.php">เลือกรูปแบบลูกค้า</a></li>
                                <li class="breadcrumb-item active">การเสนอราคา</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <div class="col-md-15 text-center">
                <div id="layoutSidenav_content">
                    <main>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-11 text-center">
                                    <h1 class="text-dark">เพิ่มการเสนอราคา</h1>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <br>
            <div class="container">
                <div class="main-body">
                    <form method="POST" action="Process/Cus_offerprice.php" id="Cus_offerprice">
                        <div class="row gutters-sm">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <div class="avatar-wrapper">
                                                <img src="images/profile.png" alt="Admin" width="180" id="profileimg">
                                                <!-- <div class="upload-button" id="uplond">
                                                    <svg class="fa fa-arrow-circle-up" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="100%" height="100%" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1536 1536">
                                                        <path d="M1284 767q0-27-18-45L904 360l-91-91q-18-18-45-18t-45 18l-91 91l-362 362q-18 18-18 45t18 45l91 91q18 18 45 18t45-18l189-189v502q0 26 19 45t45 19h128q26 0 45-19t19-45V714l189 189q19 19 45 19t45-19l91-91q18-18 18-45zm252 1q0 209-103 385.5T1153.5 1433T768 1536t-385.5-103T103 1153.5T0 768t103-385.5T382.5 103T768 0t385.5 103T1433 382.5T1536 768z" fill="#626262" />
                                                    </svg>
                                                </div> -->
                                                <!-- <input class="file-upload" name="files" accept=".png, .jpg, .jpeg" onchange="uplondpdf(this)" type="file" id="profil" style="display: none;"> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-3">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap edittext">
                                            <div class="inputbox col-sm-10">
                                                <input class="effect-1" type="text"  name="Domain" id="Domain" placeholder="Website :">
                                                <span class="focus-border"></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap edittext">
                                            <div class="inputbox col-sm-10">
                                                <input class="effect-1" type="text" name="Line" id="line" placeholder="LINE :">
                                                <span class="focus-border"></span>
                                            </div>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap edittext">
                                            <div class="inputbox col-sm-10">
                                                <div class="">
                                                    <input class="effect-1" type="text"  name="Email" id="email" placeholder="Email :">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row edittext">
                                            <div class="col-sm-10">
                                                <div class="inputbox">
                                                    <input class="effect-1" type="text" required name="Company" id="Company" placeholder="ชื่อบริษัท :">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row edittext">
                                            <div class="col-sm-10">
                                                <div class="inputbox">
                                                    <input class="effect-1" type="text"  name="Co_number" id="Co_number" placeholder="เบอร์ติดต่อ :">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row edittext">
                                            <div class="col-sm-10">
                                                <div class="inputbox">
                                                    <input class="effect-1" type="text" name="Address" id="address" placeholder="ที่อยู่ :">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row edittext">
                                            <div class="col-sm-10">
                                                <div class="inputbox">
                                                    <input class="effect-1" type="text" name="Details" id="details" placeholder="รายละเอียด :">
                                                    <span class="focus-border"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gutters-sm">
                                    <div class="col-sm-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Billdetail</i>รายละเอียดบิลเรียกเก็บเงิน</h6>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-warning mr-2">OfferpriceManagement</i>แถบเครื่องมือ</h6>
                                                <div>
                                                    <li class="list-group-item">
                                                        <center>
                                                            <input type="submit" id="submit" class="btn btn-success" value="บันทึก">
                                                        </center>
                                                    </li>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>
</body>

</html>