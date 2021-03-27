<?php
include("include/loginChek.php");
include("Process/authorize.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" type="image/png" href="images/icons/favicon.ico" /> -->
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <title>AdminControl</title>
    <script>
        function LineLoginC() {
            console.log("dwadwad");
            var userid = <?php echo $_SESSION['USERID']; ?>;
            var Unit = <?php echo $random; ?>;
            console.log("dwdawwafgwge");
            $.ajax({
                url: "Process/createDatabseAu.php",
                type: "POST",
                data: {
                    userid,
                    Unit
                },
                success: function(require) {
                    if (require != 1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: require,
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                    } else {
                        console.log(require);
                    }
                }
            });
        }

        function tokenconC() {
            var userid = <?php echo $_SESSION['USERID']; ?>;
            var Unit = <?php echo $random; ?>;
            $.ajax({
                url: "Process/gettoken.php",
                type: "POST",
                data: {
                    userid,
                    Unit
                },
                success: function(require) {
                    // console.log(require);
                    var text = "ข้อความทดสอบ";
                    testmassage(text);
                }
            });
        }

        function testmassage(message) {
            $.ajax({
                url: "Process/PLine.php",
                type: "POST",
                data: {
                    message
                }
            });
        }
        $(document).ready(function() {
            settext();
            $('#Notify').change(function() {
                var checked = $('#Notify');
                if (checked.prop('checked')) {
                    Swal.mixin({
                        // input: 'text',
                        confirmButtonText: 'Next &rarr;',
                        showCancelButton: true,
                        progressSteps: ['1', '2', '3']
                    }).queue([{
                            title: 'ขั้นตอนที่ 1',
                            html: 'เพื่มเพื่อน' + '<a href="https://line.me/R/ti/p/%40linenotify" target="_blank" rel="noopener">LINE Notify</a>',
                        },
                        {
                            title: 'ขั้นตอนที่ 2',
                            html: 'เข้าสู่ระบบบัญชีไลย์ของคุณเพื่อเชื่อมต่อกับบริการ Paymentreminder' + "<br>" + "<a href='<?php echo $queryString; ?>' onclick='LineLoginC()' target='_blank' rel='noopener' id='LineLogin'>ไปที่LineLogin</a>",
                        },
                        {
                            title: 'ขั้นตอนที่ 3',
                            html: 'คลิกลิ้งด้านล่างเพื่อเชื่อมต่อLineของคุณกับบริการPaymentreminder' + '<br>' + '<a href="#" onclick="tokenconC()" id="tokencon">เชื่อต่อบริการ</a>',
                        }
                    ]).then((result) => {
                        if (result.value) {
                            Swal.fire({
                                title: 'All done!',
                                html: "เสร็ยจสิ้นการเชื่อมต่อ",
                                confirmButtonText: 'สำเหร็ยจ!'
                            })
                        } else {
                            $('#Notify').prop('checked', false);
                        }
                    })
                } else {
                    // alert("false");
                    var userid = <?php echo $_SESSION['USERID']; ?>;
                    var key = "2";
                    $.ajax({
                        url: "Process/home.php",
                        data: {
                            userid,
                            key
                        },
                        type: "POST",
                        dataType: "json",
                        success: function(require) {
                            if (require == "1") {
                                var icon = 'info';
                                var title = " ปิดการเชื่อมต่อกับLINE Notify เสร็ยจสิ้น";
                                Notifytoprigth(title, icon);
                            }
                        }
                    });
                }
            });
            $('#Statusnotify').change(function() {
                var Statusnotify = $('#Statusnotify');
                var key = "3";
                var userid = <?php echo $_SESSION['USERID']; ?>;
                if (Statusnotify.prop('checked')) {
                    var status = "1";
                    $.ajax({
                        url: "Process/home.php",
                        data: {
                            status,
                            userid,
                            key
                        },
                        type: "POST",
                        dataType: "json",
                        success: function(require) {
                            if (require == "1") {
                                var icon = 'info';
                                var title = " เปิดการแจ้งเตือนสำหรับLINE Notify เสร็ยจสิ้น";
                                Notifytoprigth(title, icon);
                            }
                        }
                    });
                } else {
                    var status = "0";
                    $.ajax({
                        url: "Process/home.php",
                        data: {
                            status,
                            userid,
                            key
                        },
                        type: "POST",
                        dataType: "json",
                        success: function(require) {
                            if (require == "1") {
                                var icon = 'info';
                                var title = " ปิดการแจ้งเตือนสำหรับLINE Notify เสร็ยจสิ้น";
                                Notifytoprigth(title, icon);
                            }
                        }
                    });
                }
            });
            $('#autoemail').change(function() {
                var key = "4";
                if ($(this).prop('checked')) {
                    var status = "1";
                    $.ajax({
                        url: "Process/home.php",
                        data: {
                            status,
                            key
                        },
                        type: "POST",
                        dataType: "json",
                        success: function(require) {
                            if (require == "1") {
                                var icon = 'info';
                                var title = " เปิดการส่งEmailอัตโนมัตย์ เสร็ยจสิ้น";
                                Notifytoprigth(title, icon);
                            }
                        }
                    });
                } else {
                    var status = "0";
                    $.ajax({
                        url: "Process/home.php",
                        data: {
                            status,
                            key
                        },
                        type: "POST",
                        dataType: "json",
                        success: function(require) {
                            if (require == "1") {
                                var icon = 'info';
                                var title = " ปิดการส่งEmailอัตโนมัตย์ เสร็ยจสิ้น";
                                Notifytoprigth(title, icon);
                            }
                        }
                    });
                }
            });
        });

        function settext() {
            var key = 0;
            $.ajax({
                url: "Process/home.php",
                data: {
                    key
                },
                type: "POST",
                dataType: "json",
                success: function(require) {
                    $('#numcustomer').html(require.numcustomer);
                    $('#paymen').html(require.paymen);
                    $('#paymensuccess').html(require.paymensuccess);
                    $('#paynosuccess').html(require.paynosuccess);

                    $('#alldomain').html(require.numcustomer + " โดเมน");
                    $('#domainpermonth').html(require.paymen + " โดเมน");
                    // console.log(require);
                }
            });
        }
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
                            <li class="breadcrumb-item active">หน้าหลัก</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- <a href="#" id="Notify">ConnectLineNotify</a> -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-11 text-center">
                    <h1 class="text-black">ระบบเเจ้งเตือนการชำระเงิน</h1>
                </div>
            </div>
            <br>
            <div class="row mb-3" id="cardbrief">
                <div class="col">
                    <a href="Customerlist.php">
                        <div class="card conclude">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h3 class="success" id="numcustomer">0</h3>
                                            <span>ลูกค้าทั้งหมด</span>
                                        </div>
                                        <div class="align-self-center">
                                            <img src="images/icons/customer.png" width="33vw" align="right">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="Paymentlist.php">
                        <div class="card conclude">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h3 class="success" id="paymen">0</h3>
                                            <span>ที่ต้องชำระในเดือนนี้</span>
                                        </div>
                                        <div class="align-self-center">
                                            <img src="images/icons/bell.png" align="right" width="33vw">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="Paymentlist.php">
                        <div class="card conclude">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h3 class="success" id="paymensuccess">0</h3>
                                            <span>ชำระแล้ว</span>
                                        </div>
                                        <div class="align-self-center">
                                            <img src="images/icons/credit-card.png" width="33vw" align="right">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="Paymentlist.php">
                        <div class="card conclude">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left">
                                            <h3 class="success" id="paynosuccess">0</h3>
                                            <span>ค้างชำระ</span>
                                        </div>
                                        <div class="align-self-center">
                                            <img src="images/icons/credit-cardnopayment.png" width="33vw" align="right">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="card conclude">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">สรุปยอดในเเต่ละปี</h3>
                                <a href="#">ออกรายงาน</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg" id="alldomain">....โดเมน</span>
                                    <span>จำนวณลูกค้าทั้งหมด</span>
                                </p>
                                <p class="ml-auto d-flex flex-column text-right">
                                    <span class="text-success" id="domainpermonth">
                                        33 โดเมน
                                    </span>
                                    <span class="text-muted">ที่ต้องจัดการในเดือนนี้</span>
                                </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="position-relative mb-4">
                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                <div id="chart_div"></div>
                                <script>
                                    google.charts.load('current', {
                                        'packages': ['bar']
                                    });
                                    google.charts.setOnLoadCallback(drawChart);

                                    function drawChart() {
                                        var key = "1";
                                        var jsonData = $.ajax({
                                            type: "POST",
                                            data: {
                                                key
                                            },
                                            dataType: "json",
                                            url: "Process/home.php",
                                            async: false
                                        }).responseText;
                                        // console.log(jsonData);
                                        var data = new google.visualization.DataTable(jsonData);
                                        // console.log(data);
                                        // var dataa = google.visualization.arrayToDataTable([
                                        //     ['เดือน', 'จำนวณลูกค้า'],
                                        //     ["ม.ค", 10],
                                        //     ["ก.พ", 30],
                                        //     ["มี.ค", 35],
                                        //     ["เม.ย", 20],
                                        //     ["พ.ค", 5],
                                        //     ["มิ.ย", 15],
                                        //     ["ก.ค", 40],
                                        //     ["ส.ค", 42],
                                        //     ["ก.ย", 55],
                                        //     ["ต.ค", 12],
                                        //     ["พ.ย", 9],
                                        //     ["ธ.ค", 8]
                                        // ]);
                                        // console.log(dataa);

                                        var options = {
                                            bars: 'vertical',
                                            legend: {
                                                position: 'none'
                                            },
                                            vAxis: {
                                                format: 'decimal'
                                            },
                                            height: 300,
                                            colors: ['#1b9e77']
                                        };
                                        var chart = new google.charts.Bar(document.getElementById('chart_div'));
                                        chart.draw(data, google.charts.Bar.convertOptions(options));
                                    }
                                </script>
                            </div>
                        </div><!-- card-body -->
                    </div>
                </div><!-- col -->
                <div class="col-md-3">
                    <div class="card card-outline conclude">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-7 mb-0">
                                    <h5 class="card-title">ควบคุม</h5>
                                </div>
                            </div>
                        </div>
                        <?php
                        $sqltoken = "SELECT TokenLine,Statusnotify FROM user WHERE userid='$userid'";
                        $quarytoken = mysqli_query($conn, $sqltoken);
                        $alldata = mysqli_fetch_array($quarytoken, MYSQLI_ASSOC);
                        $emailcek = "SELECT status FROM account WHERE Typeacc='1'";
                        $quaryaccon = mysqli_query($conn, $emailcek);
                        $alltataaccon = mysqli_fetch_array($quaryaccon, MYSQLI_ASSOC);
                        ?>
                        <li class="list-group-item">
                            ผูกบันชีLine
                            <label class="switch ">
                                <input type="checkbox" id="Notify" <?php if (!$alldata['TokenLine'] == "0") echo "checked"; ?> class="success">
                                <span class="slider round"></span>
                            </label>
                        </li>
                        <li class="list-group-item">
                            การแจ้งเตือนในไลน์
                            <label class="switch ">
                                <input type="checkbox" id="Statusnotify" <?php if (!$alldata['Statusnotify'] == "0") echo "checked"; ?> class="success">
                                <span class="slider round"></span>
                            </label>
                        </li>
                        <li class="list-group-item">
                            ส่งอีเมลอัตโนมัติ
                            <label class="switch ">
                                <input type="checkbox" id="autoemail" <?php if (!$alltataaccon['status'] == "0") echo "checked"; ?> class="success">
                                <span class="slider round"></span>
                            </label>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>