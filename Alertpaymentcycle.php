<!DOCTYPE html>
<html lang="en">
<?php
include("include/loginChek.php");
$date = date('Y-m-01');
$current = date("m", strtotime($date));
$newyear = "false";
if ($current == "12") {
    $newyear = "true";
}
$month = date("m", strtotime($date . "+1 month"));
// $date = "-".$date."-";
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" type="image/png" href="images/icons/favicon.ico" /> -->
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="css/Alertpaymentcycle.css">
    <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <title>AdminControl</title>
    <script>
        $(document).ready(function() {
            var monthD = "0" + <?php echo $month;  ?>;
            var newyear = <?php echo $newyear;  ?>;
            getdate(monthD, newyear);
            // getdate("0123");
            $('#selectmonth').change(function() {
                var month = $(this).children("option:selected").val();
                $('#detailmoney').attr("name", 0);
                $('#detailmoney').css('margin-right', '-100vw');
                // console.log(month);
                getdate(month, newyear);
            })
        });

        function getdate(month, newyear) {
            var key = "1";
            $.ajax({
                url: "Process/alertpaymentcycle.php",
                data: {
                    key,
                    newyear,
                    month
                },
                type: "POST",
                dataType: "json",
                success: function(require) {
                    console.log(require);
                    $('#data').html(require);
                }
            });
        }

        function showdata(Co_id) {
            var namedetail = $('#detailmoney').attr("name");
            $('#detailmoney').css('margin-right', '0');
            $('#detailmoney').attr("name", Co_id);
            var newyear = <?php echo $newyear;  ?>;
            if (Co_id == namedetail) {
                $('#detailmoney').css('margin-right', '-100vw');
                $('#detailmoney').attr("name", 0);
                $('#fileup').attr("name", 0);
            } else {
                var key = "2";
                $.ajax({
                    url: "Process/alertpaymentcycle.php",
                    type: "POST",
                    data: {
                        key,
                        newyear,
                        Co_id
                    },
                    dataType: "json",
                    success: function(require) {
                        // var month = $(this).children("option:selected").val();
                        // getdate(month);
                        $('#Co_id').html(require.Co.Co_id);
                        $('#Domain').html(require.Co.Domain);
                        $('#Company').html(require.Co.Company);
                        $('#Rates').html(require.Co.Rates);
                        $('#Email').html(require.Co.Email);
                        $('#Line').html(require.Co.Line);
                        $('#Address').html(require.Co.Address);
                        $('#fileupdiv').attr('name', require.Co.Co_id);
                        if (require.Bill.Bill_file != "ไม่มีข้อมูล") {
                            var bileurl ="customerdetail/"+require.Co.Company+require.Co.Co_id+"/bill/"+require.Bill.Bill_file;
                            var atag = "<h4><a href=" + bileurl + "  target='_blank' >" + require.Bill.Bill_file + "</a></h4>";
                            $('#fileupdiv').html(atag);
                        } else {
                            $('#fileupdiv').html(' ');
                            // $('#fileupdiv').html('<input type="file" id="fileup" onchange="uplode(this)">');
                        }
                        console.log(require);
                    },
                    error:function(e){
                        console.log(e);
                    }
                })
            }
        }
        $(document).ready(function() {
            $('#uplondbill').click(function() {
                (async () => {
                    const {
                        value: file
                    } = await Swal.fire({
                        title: 'Select PDF',
                        input: 'file',
                        inputAttributes: {
                            'accept': 'application/pdf',
                            'aria-label': 'Upload your profile picture'
                        }
                    })
                    if (file) {
                        var files = file;
                        var Co_id = $('#fileupdiv').attr('name');
                        var key = "3";
                        var newyear = <?php echo $newyear; ?>;
                        var data = new FormData();
                        data.append("files", files);
                        data.append("Co_id", Co_id);
                        data.append("key", key);
                        data.append("newyear", newyear);
                        $.ajax({
                            url: "Process/alertpaymentcycle.php",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: data,
                            success: function(require) {
                                var icon = '';
                                var title = '';
                                switch (require) {
                                    case "0":
                                        icon = 'warning';
                                        title = 'ไม่สามารถจัดเตรียมพื้นที่ในการอัปโหลดได้';
                                        break;
                                    case "1":
                                        icon = 'warning';
                                        title = 'ขออภัยอนุญาตเฉพาะไฟล์ pdf เท่านั้น';
                                        break;
                                    case "2":
                                        icon = 'success';
                                        title = 'อัปโหลดไฟล์ ' + file.name + " เสร็ยจสมบูรณ์";
                                        var month = $('#selectmonth').children("option:selected").val();
                                        getdate(month, newyear);
                                        showdata(Co_id);
                                        break;
                                    case "3":
                                        icon = 'error';
                                        title = 'มีปัญหาในการนำข้อมูลจัดเก็บในดาต้าเบส';
                                        break;
                                    case "4":
                                        icon = 'question';
                                        title = 'ไม่สามารถอัปโหลดไฟล์ได้ในขณะนี้';
                                        break;
                                }
                                Notifytoprigth(title,icon);
                            }
                        });
                    }
                })()
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
        <div class="content-wrapper px-2" style="overflow-y: hidden;">
        <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                                <li class="breadcrumb-item active">รายการใกล้ชำระ</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
                <select name="#" id="selectmonth">
                    <option value="01" <?php if ($month == "01") echo "selected"; ?>>มกราคม</option>
                    <option value="02" <?php if ($month == "02") echo "selected"; ?>>กุมภาพันธ์</option>
                    <option value="03" <?php if ($month == "03") echo "selected"; ?>>มีนาคม</option>
                    <option value="04" <?php if ($month == "04") echo "selected"; ?>>เมษายน</option>
                    <option value="05" <?php if ($month == "05") echo "selected"; ?>>พฤษภาคม</option>
                    <option value="06" <?php if ($month == "06") echo "selected"; ?>>มิถุนายน</option>
                    <option value="07" <?php if ($month == "07") echo "selected"; ?>>กรกฎาคม</option>
                    <option value="08" <?php if ($month == "08") echo "selected"; ?>>สิงหาคม</option>
                    <option value="09" <?php if ($month == "09") echo "selected"; ?>>กันยายน</option>
                    <option value="10" <?php if ($month == "10") echo "selected"; ?>>ตุลาคม</option>
                    <option value="11" <?php if ($month == "11") echo "selected"; ?>>พฤศจิกายน</option>
                    <option value="12" <?php if ($month == "12") echo "selected"; ?>>ธันวาคม</option>
                </select>
                <div class="pt-3 row">
                    <div class="col content" id="data" style="transition: all 0.3s;">

                    </div>
                    <div class="col-md-4" id="detailmoney" style="transition: all 0.3s;margin-right:-100vw;">
                        <h2>รหัสบริษัท: <span id="Co_id"></span> </h2>
                        <h3>โดเมน:<span id="Domain"></span></h3>
                        <h3>ชื่อ:<span id="Company"></span></h3>
                        <h3>จำนวณเงิน:<span id="Rates"></span></h3>
                        <h3>ช่องทางการติดต่อ</h3>
                        <h3>Email:<span id="Email"></span></h3>
                        <h3>Line:<span id="Line"></span></h3>
                        <h3>ที่อยู่:<span id="Address"></span></h3>
                        <h3>ใบเรียกเก็บเงิน: </h3>
                        <div id="fileupdiv">
                            <!-- <input type="file" id="fileup" onchange="uplode(this)"> -->
                        </div>
                        <button type="button" id="uplondbill">อัปโหลดใบวางบิล</button>
                    </div>
                </div>
                <br><br><br>
            </div>
        </div>
    </div>

</body>

</html>