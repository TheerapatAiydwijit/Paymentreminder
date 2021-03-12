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
<!DOCTYPE html>
<html lang="en">


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
                $('#customerDetail').html("");
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
                    // console.log(require);
                    require.forEach(showdata);
                }
            });
        }

        function showdata(item, index) {
            var customerdetail = document.getElementById("customerDetail");
            var temp = document.getElementsByTagName("template")[0];
            var clon = temp.content.cloneNode(true);
            clon.getElementById("Domain").innerHTML = item.Domain;
            clon.getElementById("Domain_2").innerHTML = item.Domain;
            clon.getElementById("uplondbill").setAttribute("name", item.Co_id);
            clon.getElementById("Company").innerHTML = item.Company;
            clon.getElementById("Rates").innerHTML = item.Rates;
            clon.getElementById("Co_id").innerHTML = item.Co_id;
            clon.getElementById("Duedate").innerHTML = item.Duedate;
            if(item.stbill == "รอการอัปโหลด"){
                clon.getElementById("status").setAttribute("class","text-danger");
                clon.getElementById("fileupdiv").innerHTML = item.stbill;
            }else{
                var atag =document.createElement('a');
                atag.href ="customerdetail/"+item.Company+item.Co_id+"/bill/"+item.stbill.Bill_file;
                atag.setAttribute("target","_blank");
                atag.innerHTML = item.stbill.Bill_file;
                clon.getElementById("status").setAttribute("class","text-success");
                clon.getElementById("fileupdiv").appendChild(atag);
            }
            // console.log(item);
            // console.log(clon);
            customerdetail.appendChild(clon);
        }
        function flipper(data, event) {
            var target = $(event.target);
            // console.log(target);
            if (target.is("button")) {
                //follow that link
                uplondbill(data);
                return true;
            } else {
                $(data).toggleClass("flip");
            }
            return false;
        }

        function uplondbill(data) {
            var Co_id =$(data).find("button").attr("name");
            // console.log(Co_id);
            (async () => {
                const {
                    value: file
                } = await Swal.fire({
                    title: 'Select PDF',
                    input: 'file',
                    inputAttributes: {
                        'accept': 'application/pdf',
                        'aria-label': 'Upload your bill file'
                    }
                })
                if (file) {
                    // console.log("dawfawg");
                    var files = file;
                    // var Co_id = $(data).find("button").attr("name");
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
                        dataType: "json",
                        success: function(require) {
                            // console.log(require);
                            var icon = require.status;
                            var title = require.Message;
                            Notifytoprigth(title, icon);
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    });
                }
            })()
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
            <br><br>
            <template id="template">
                <div class="col-sm-3 flipper col-sm-offset-1" onclick="flipper(this,event)">
                    <div class="card ">
                        <div class="front">
                            <p style="font-size:5rem;" id="status"><i class="far fa-file-pdf"></i></p>
                            <h1 id="Domain"></h1>
                            <span id="Duedate"></span><br>
                            <span><u>รายละเอียดเพิ่มเติม</u></span>
                        </div>
                        <div class="back">
                            <p>รหัสบริษัท: <span id="Co_id"></span></p>
                            <div class="text-left ml-2">
                                <p>โดเมน: <span id="Domain_2"></span></p>
                                <p>ชื่อ: <span id="Company"></span></p>
                                <p>จำนวณเงิน: <span id="Rates"></span></p>
                                <p>ใบเรียกเก็บเงิน: <span id="fileupdiv"></span></p>
                                <button type="button" name="00" class="btn btn-info" id="uplondbill">อัปโหลดใบวางบิล</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <div class="row" id="customerDetail">
              
            </div>
            <br><br><br>
        </div>
    </div>
    </div>

</body>

</html>