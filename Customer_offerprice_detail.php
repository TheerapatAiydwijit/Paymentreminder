<!DOCTYPE html>
<html lang="en">
<?php
include("include/loginChek.php");
include('include/Connect.php');
$off_id = $_GET['Edit'];
$sqloff = "SELECT * FROM customer_offerprice WHERE Offerprice_id='$off_id'";
$sulet = mysqli_query($conn, $sqloff);
$alloff = mysqli_fetch_array($sulet, MYSQLI_ASSOC);
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มรายชื่อ</title>
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="css/Customer_offerprice_detail.css">
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <link href="helper/summernote0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <script src="helper/summernote0.8.18/summernote-bs4.min.js"></script>
    <script src="helper/summernote0.8.18/lang/summernote-th-TH.js"></script>
    <script>
        $(document).ready(function() {
            lodedata(<?php echo $off_id; ?>);
            lodeQuotation(<?php echo $off_id; ?>);
            $('#lisendemail').hide();
            $('#lieditmail').hide();
            $('#editofferprice').on('click', function() {
                var danger = $('#editofferprice').toggleClass('btn-danger');
                var success = $('#editofferprice').toggleClass('btn-success');
                if (danger.hasClass('btn-danger')) {
                    $(this).html("ปิด");
                } else {
                    $(this).html("เปิด");
                    Notifytoprigth("ดับเบิ้ลคลิกเพื่อแก้ไข","info");
                }
            });
            // การแก้ไขข้ความ
            $('.edittext').dblclick(function() {
                if ($('#editofferprice').hasClass('btn-success')) {
                    $(this).css("background-color", "#EAEDED ");
                    // $(this).children("span").css("color","#FFFFFF");
                    var refer = $(this).children("span").attr('id');
                    var inhtml = $(this).children("span").html();
                    $(this).children("span").attr("contenteditable", "TRUE");
                    $(this).children("span").trigger("focus");
                }
                // alert(inhtml);
            });
            $('.edittext').focusout(function() {
                var key = "UPDATE";
                $(this).children("span").attr("contenteditable", "false");
                $(this).css("background-color", "#FFFFFF");
                var Offerprice_id = <?php echo $off_id; ?>;
                var column = $(this).children("span").attr("id");
                var value = $(this).children("span").html();
                $.ajax({
                    url: "Process/customer_offerprice_detail.php",
                    data: {
                        key,
                        column,
                        value,
                        Offerprice_id
                    },
                    type: "POST",
                    success: function(require) {
                        if (require == "1") {
                            var icon = "success";
                            var tital = "ทำการแก้ไขข้อมูลสำเหร็ยจ";
                        } else {
                            var icon = "error";
                            var tital = "พบข้อผิดพลาดในการแก้ไขข้อมูล";
                        }
                        Notifytoprigth(tital, icon);
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
                // alert(value);
                // alert("dwaafw");
            });
            $('.edittext').keypress(function(e) {
                var key = e.which;
                if (key == 13) // the enter key code
                {
                    e.preventDefault();
                    $(this).children("span").attr("contenteditable", "false");
                    $(".edittext [contenteditable=TRUE]").trigger("focusout");
                }
            });
            // การอัพโหลด
            $('#offerpricepdf').click(function() {
                $("#fileofferpricepdf").trigger("click");
            });
            // ส่งเอกสาร
            $('#recordemail').click(function() {
                var idname = $(this).attr('id');
                // console.log(idname);
                tosavedata(idname);
            });
            $('#sendemail').click(function() {
                var idname = $(this).attr('id');
                var files = $("#fileofferpricepdf")[0].files.length;
                if (files == 0) {
                    alert("ไม่สามารส่งอีเมลย์ได้หากไม่อัพโหลดไฟล์");
                } else {
                    tosavedata(idname);
                }
            });
        });

        function tosavedata(events) {
            $('#model').modal('show');
            $("#loding").css("display", "block");
            var key = events;
            var keychek = $('#Order_id').val();
            var Offerprice_id = "<?php echo $off_id; ?>";
            var files = $("#fileofferpricepdf").prop('files')[0];
            var data = new FormData();
            var message = $('#summernote').val();
            var sendto = $('#sendto').val();
            var toppic = $('#toppic').val();
            data.append('files', files);
            data.append('message', message);
            data.append('Offerprice_id', Offerprice_id);
            data.append('key', key);
            data.append('Sendto', sendto);
            data.append('Toppic', toppic);
            $.ajax({
                url: "Process/customer_offerprice_detail.php",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "post",
                success: function(require) {
                    $('#model').modal('toggle');
                    $("#loding").css("display", "none");
                    var obj = JSON.parse(require);
                    if (obj.status == "success") {
                        var title = obj.Message;
                        var icon = 'success';
                        Notifytoprigth(title, icon);
                    } else {
                        var title = obj.Message;
                        var icon = 'error';
                        Notifytoprigth(title, icon);
                    }
                    console.log(require);
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }

        function uplondpdf(fileist) {
            var files = fileist.files[0];
            var url = URL.createObjectURL(files);
            const atage = document.createElement("a");
            atage.href = url;
            atage.target = "_blank";
            atage.innerHTML = "ดูไฟล์";
            $('#linkpdf').html(atage);
        }

        function lodedata(Offerprice_id) {
            var key = "all";
            $.ajax({
                url: "Process/customer_offerprice_detail.php",
                data: {
                    key,
                    Offerprice_id
                },
                type: "POST",
                datatype: "json",
                success: function(require) {
                    var obj = JSON.parse(require);
                    // console.log(obj.Company);
                    $('#Domain').html(obj.Domain);
                    $('#Line').html(obj.Line);
                    $('#Email').html(obj.Email);
                    $('#Company').html(obj.Company);
                    $('#Co_number').html(obj.Co_number);
                    $('#Address').html(obj.Address);
                    $('#Details').html(obj.Details);
                },
                error: function(e) {
                    // console.log(e);
                }
            });
        }

        function lodeQuotation(Offerprice_id) {
            var key = "Quotation";
            $.ajax({
                url: "Process/customer_offerprice_detail.php",
                data: {
                    key,
                    Offerprice_id
                },
                type: "POST",
                success: function(require) {
                    // console.log(require);
                    var require = JSON.parse(require);
                    $('#bodydetail').children().remove();
                    // $('tr.body_caption_top').remove();
                    for (var t = 0; t < require.length; t++) {
                        // console.log(require[t]);
                        createtable(require[t]);
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }

        function createtable(data) {
            var table = document.getElementById("bodydetail");
            var number = table.rows.length;
            var row = table.insertRow(number);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);

            var order = document.createElement('p');
            order.innerHTML = data.Order;
            cell1.style.width = "61px";

            var datesend = document.createElement("a");
            datesend.innerHTML = data.Date;
            datesend.setAttribute("href", "#summernote");
            datesend.onclick = function() {
                $('#sendto').val("''");
                $('#toppic').val("''");
                $('#summernote').summernote('code', "''");
                // alert(data.Message);
                $('#sendto').val(data.Sendto);
                $('#toppic').val(data.Toppic);
                $('#summernote').summernote('code', data.Message);
            };
            var status = document.createElement('button');
            status.setAttribute("data-toggle", "tooltip");
            if (data.Senddate == "error") {
                status.setAttribute("Class", "btn btn-danger tooltipset");
                status.setAttribute("data-placement", "top");
                status.setAttribute("title", "ไม่มีข้อมูล");
                status.innerHTML = "ส่งไม่สำเหร็ยจ";
            } else {
                if (data.Senddate == "Draft") {
                    status.setAttribute("Class", "btn btn-warning tooltipset");
                    status.setAttribute("data-placement", "top");
                    status.setAttribute("title", data.Senddate);
                    status.innerHTML = "แบบร่าง";
                } else {
                    status.setAttribute("Class", "btn btn-success tooltipset");
                    status.setAttribute("data-placement", "top");
                    status.setAttribute("title", data.Senddate);
                    status.innerHTML = "ส่งสำเหร็ยจ";
                }

            }
            cell1.appendChild(order);
            cell2.appendChild(datesend);
            cell3.appendChild(status);
        }
    </script>
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
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                                <li class="breadcrumb-item"><a href="Customerlist.php">รายการลูกค้า</a></li>
                                <li class="breadcrumb-item active">รายละเอียดลูกค้า</li>
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
                                    <h1 class="text-dark">รายละเอียดลูกค้า</h1>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <br>
            <div class="container">
                <div class="main-body">
                    <div class="row gutters-sm">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                                        <div class="mt-3">
                                            <!-- <h4 id="Company"></h4> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap edittext">
                                        <h6 class="mb-0">
                                            Website
                                        </h6>
                                        <span class="text-secondary" id="Domain"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap edittext">
                                        <h6 class="mb-0">LINE</h6>
                                        <span class="text-secondary" id="Line"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap edittext">
                                        <h6 class="mb-0">Email</h6>
                                        <span class="text-secondary" id="Email"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row edittext">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">ชื่อบริษัท</h6>
                                        </div>
                                        <span class="col-sm-9 text-secondary" id="Company">

                                        </span>
                                    </div>
                                    <hr>
                                    <div class="row edittext">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">เบอร์ติดต่อ</h6>
                                        </div>
                                        <span class="col-sm-9 text-secondary" id="Co_number">

                                        </span>
                                    </div>
                                    <hr>
                                    <div class="row edittext">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">ที่อยู่</h6>
                                        </div>
                                        <span class="col-sm-9 text-secondary" id="Address">

                                        </span>
                                    </div>
                                    <hr>
                                    <div class="row edittext">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">รายละเอียด</h6>
                                        </div>
                                        <span class="col-sm-9 text-secondary" id="Details">

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters-sm">
                                <div class="col-sm-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Quotation</i>รายละเอียดใบเสนอราคา</h6>
                                            <div class="panel-body no-padding">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-email display" id="table_id">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:61px;">ลำดับ</th>
                                                                <th>วันที่</th>
                                                                <th>สถาณะ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="tboyscoll" id="bodydetail">
                                                            <tr class="body_caption_top">
                                                                <td style="width:61px;">
                                                                    1
                                                                </td>
                                                                <td>
                                                                    17 ก.พ 2564
                                                                </td>
                                                                <td>
                                                                    ส่งแล้ว
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- /.table-responsive -->
                                            </div><!-- /.panel-body -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-warning mr-2">OfferpriceManagement</i>แถบเครื่องมือ</h6>
                                            <div>
                                                <li class="list-group-item">
                                                    แก้ไขรายละเอียดลูกค้า
                                                    <button type="button" id="editofferprice" class="btn btn-danger ml-3">ปิด</button>
                                                </li>
                                                <li class="list-group-item">
                                                    ย้ายไปยังลูกค้าปัจุบัน
                                                    <a href="Customeradd.php?Offerprice_id=<?php echo $off_id; ?>"> <button type="button" id="editofferprice" class="btn btn-success ml-3">ย้าย</button></a>
                                                </li>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <input type="email" name="sendtoemail" id="sendto" placeholder="อีเมลย์ปลายทาง" style="width: 100%;height:6vh;">
                        <input type="text" name="topic" id="toppic" placeholder="หัวเรื่อง" style="width: 100%;height:6vh;">
                        <textarea id="summernote" name="editordata"></textarea>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-success mr-2">EmailManagement</i>แถบเครื่องมือ</h6>
                                <div>
                                    <li class="list-group-item">
                                        ส่งใบเสนอราคา
                                        <!-- <input type="file" name="offerpricepdf" id="offerprice"> -->
                                        <button type="button" id="offerpricepdf" class="btn btn-primary ml-3">อัพโหลด</button>
                                        <input type="file" id="fileofferpricepdf" onchange="uplondpdf(this)" style="display: none;">
                                        <input type="text" id="Order_id" style="display: none;">
                                    </li>
                                    <li class="list-group-item" id="linkpdf">

                                    </li>
                                    <li class="list-group-item">
                                        บันทึกไว้เป็นแบบร่าง
                                        <button type="button" class="btn btn-warning ml-3" id="recordemail">บันทึก</button>
                                    </li>
                                    <li class="list-group-item">
                                        ส่งใบเสนอราคาให้ลูกค้า
                                        <button type="button" class="btn btn-success ml-3" id="sendemail">ส่ง</button>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $("#summernote").summernote({
                        lang: "th-TH",
                        dialogsInBody: true,
                        height: 500,
                        minHeight: null,
                        maxHeight: null,
                        shortCuts: false,
                        fontSize: 14,
                        disableDragAndDrop: false,
                        toolbar: [
                            ["style", ["bold", "italic", "underline", "clear"]],
                            ["font", ["strikethrough", "superscript", "subscript"]],
                            ["fontsize", ["fontsize", "fontname"]],
                            ["color", ["color"]],
                            ["para", ["ul", "ol", "paragraph"]],
                            ["height", ["height"]],
                            ["Other", ["fullscreen", "codeview", "help"]],
                            ["mybutton", ["year", "Co_name", "Domain", "Duedate", "Rates"]]
                        ]
                    });
                </script>
            </div>
            <br>
            <br>
            <br>
        </div>
    </div>
    </div>
    </main>
    </div>
    </div>
    </div>
    </div>
</body>

</html>