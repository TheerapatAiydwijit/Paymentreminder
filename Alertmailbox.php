<!DOCTYPE html>
<html lang="en">
<?php
include("include/loginChek.php");
include("include/Connect.php");

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Alertmailbox.css">
    <link rel="stylesheet" type="text/css" href="helper/DataTables/datatables.min.css" />
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script type="text/javascript" src="helper/DataTables/datatables.min.js"></script>
    <script src="include/mailbox.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="modal fade" id="model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <center>
                <div class="windows8" id="loding">
                    <div class="wBall" id="wBall_1">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_2">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_3">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_4">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_5">
                        <div class="wInnerBall"></div>
                    </div>
                </div>
            </center>
        </div>
    </div>
    <div class="wrapper">
        <aside class="main-sidebar sidebar-dark-primary elevation-4 pt-2">
            <?php include("include/sidebar.php"); ?>
        </aside>
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <?php
            include("include/navbar.php");
            ?>
        </nav>
        <div class="content-wrapper px-3">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                                <li class="breadcrumb-item active">กล่องอีเมลย์</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <div class="col-md-15 text-center">
                <div id="layoutSidenav_content">
                    <main>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-11 text-center">
                                    <h1 class="text-dark">กล่องอีเมลย์สำหรับเดือนหน้า</h1>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <div class="accordion" id="accordionExample">
                        <div class="card" style="text-decoration: none;">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-mail-bulk"></i>  Emailboxs
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <ul class="list-unstyled nav nav-pills flex-column">
                                        <li class="nav-item">
                                            <a href="Alertmailbox.php" class="nav-link">
                                                ที่กำลังจะส่ง
                                                <!-- <span class="badge bg-warning float-right">65</span> -->
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="Alertallmailbox.php" class="nav-link">ทั้งหมด</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="Alertmailedit.php" class="nav-link">แก้ไขแบบอีเมล์เริ่มต้น</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-outline">
                        <!-- <div class="card-header">
                            <div class="row">
                                <div class="col-md-7">
                                    <h5 class="card-title">สำหรับเดือนหน้า</h5>
                                </div>
                            </div>
                        </div> -->
                        
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <button type="button" class="btn btn-warning btn-sm" id="selectall">
                                    <i class="far fa-plus-square"></i>  เลือกทั้งหมด
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" id="notselectall">
                                    <i class="far fa-minus-square"></i>  ไม่เลือกทั้งหมด
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" id="closesele">
                                    <i class="far fa-bell-slash"></i>  ปิดการส่งที่เลือก
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" id="sendselect">
                                    <i class="far fa-share-square"></i>  บังคับส่งที่เลือก
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div class="panel-body no-padding">
                                <div class="table-responsive">
                                    <table class="table table-hover table-email display" id="table_id">
                                        <thead>
                                            <tr>
                                                <!-- <th>Co_id</th>
                                                    <th>sedemailst</th>
                                                    <th>Domain</th>
                                                    <th>Company</th>
                                                    <th>mailfrom</th>
                                                    <th>Duedate</th>
                                                    <th>numbill</th> -->
                                            </tr>
                                        </thead>
                                    </table>
                                </div><!-- /.table-responsive -->
                            </div><!-- /.panel-body -->
                            <script>
                                $(document).ready(function() {
                                    $('#table_id').DataTable({
                                        ajax: {
                                            "url": 'Process/alertallmailbox.php',
                                            "type": "POST",
                                            "data": function(d) {
                                                return $.extend({}, d, {
                                                    "key": 0
                                                });
                                            }
                                        },
                                        columns: [{
                                                data: 'Co_id',
                                                render: function(data, type) {
                                                    return '<input id="' + data + '" type="checkbox" class="mail-checkbox" value="' + data + '">';
                                                }
                                            },
                                            {
                                                data: 'sedemailst',
                                                render: function(data, type) {
                                                    if (data == "0") {
                                                        return '<a href="#" class="star star-nochecked" data-toggle="tooltip" data-placement="top" title="ส่งไม่สำเหร็ยจ/ยังไม่ถึงวันส่ง"><i class="fa fa-star"></i></a>'
                                                    } else {
                                                        return '<a href="#" class="star star-checked" data-toggle="tooltip" data-placement="top" title="ส่งสำเหร็ยจแล้ว"><i class="fa fa-star"></i></a>'
                                                    }
                                                }
                                            },
                                            {
                                                data: 'detail',
                                                // Co_id:'detail.Co_id',
                                                // Company:'detail.Company',
                                                // mailfrom:'detail.mailfrom',
                                                render: function(data, type) {
                                                    var Co_id = data.Co_id;
                                                    var Domain = data.Domain;
                                                    var Company = data.Company;
                                                    var mailfrom = data.mailfrom;
                                                    return '<a href="Alertmailedit.php?Co_id=' + Co_id + '">' +
                                                        '<h4 class = "text-primary">' + Domain + '</h4></a>' +
                                                        '<p class = "email-summary">' + Company + '<strong></strong>: จะทำการส่งโดยใช้' + mailfrom + '</p>'
                                                }
                                            },
                                            {
                                                data: 'Readiness',
                                                render: function(data, type) {
                                                    var date = data.Duedate;
                                                    var numbill = data.numbill;
                                                    if (numbill > 0) {
                                                        return '<span>' + date + '</span>' + '<br>' + '<span><i class="fa fa-paperclip"></i><i class="fa fa-share"></i></span>'
                                                    } else {
                                                        return '<span>' + date + '</span>'
                                                    }

                                                }
                                            }
                                        ]
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <br><br><br>
                </div>

            </div>
        </div>
    </div>
    </div>
</body>

</html>