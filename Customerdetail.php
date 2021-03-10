<?php
include("include/loginChek.php");
include 'include/Connect.php';
$Co_id = $_GET['Edit'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายละเอียด</title>
  <!--===============================================================================================-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <!--===============================================================================================-->
  <!-- <link rel="stylesheet" href="css/admin.css"> -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="css/customer.css">
  <link rel="stylesheet" href="css/customerdetail.css">
  <link rel="stylesheet" href="helper/lightbox2-2.11.3/css/lightbox.css">
  <!--===============================================================================================-->
  <script src="helper/jquery/jquery-3.5.1.min.js"></script>
  <script src="helper/sweetalert/sweetalert2@10.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  <script src="helper/sweetalert/sweetalert2@10.js"></script>
  <script src="helper/lightbox2-2.11.3/js/lightbox.js"></script>

  <script>
    $(document).ready(function() {
      lodedata(<?php echo $Co_id; ?>);
      lodeQuotation(<?php echo $Co_id; ?>);
      londpayment(<?php echo $Co_id; ?>);
      $('#editcustomer').on('click', function() {
        var danger = $('#editcustomer').toggleClass('btn-danger');
        var success = $('#editcustomer').toggleClass('btn-success');
        if (danger.hasClass('btn-danger')) {
          $(this).html("ปิด");
        } else {
          $(this).html("เปิด");
          Notifytoprigth("ดับเบิ้ลคลิกเพื่อแก้ไข","info");
        }
      });
      $('.edittext').dblclick(function() {
        if ($('#editcustomer').hasClass('btn-success')) {
          $(this).css("background-color", "#EAEDED ");
          // $(this).children("span").css("color","#FFFFFF");
          var refer = $(this).children("span").attr('id');
          var inhtml = $(this).children("span").html();
          $(this).children("span").attr("contenteditable", "TRUE");
          $(this).children("span").trigger("focus");
        }
      });
      $('.edittext').focusout(function() {
        var key = "UPDATE";
        $(this).children("span").attr("contenteditable", "false");
        $(this).css("background-color", "#FFFFFF");
        var Co_id = <?php echo $Co_id; ?>;
        var column = $(this).children("span").attr("id");
        var value = $(this).children("span").html();
        $.ajax({
          url: "Process/customerdetail.php",
          data: {
            key,
            column,
            value,
            Co_id
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
    });

    function lodedata(Co_id) {
      var key = "loaddata";
      $.ajax({
        url: "Process/customerdetail.php",
        data: {
          key,
          Co_id
        },
        type: "POST",
        datatype: "json",
        success: function(require) {
          // console.log(require);
          var obj = JSON.parse(require);

          $('#Domain').html(obj.Domain);
          $('#Line').html(obj.Line);
          $('#Email').html(obj.Email);
          $('#Rates').html(obj.Rates);
          $('#Company').html(obj.Company);
          $('#Co_number').html(obj.Co_number);
          $('#Address').html(obj.Address);
          $('#Details').html(obj.Details);
          if (!(obj.Profile == "ไม่มีข้อมูล")) {
            var urlimg = "customerdetail/" + obj.Company + obj.Co_id + "/profile/" + obj.Profile;
            //  console.log(urlimg);
            $('#profileimg').attr("src", urlimg);
          }
        },
        error: function(e) {
          // console.log(e);
        }
      });
    }

    function lodeQuotation(Co_id) {
      var key = "Quotation";
      $.ajax({
        url: "Process/customerdetail.php",
        data: {
          key,
          Co_id
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
      datesend.innerHTML = "ของปี " + data.Sendyear;
      datesend.setAttribute("href", "#");
      datesend.setAttribute('onclick', "showemail(" + data.send_id + ")");
      datesend.setAttribute("send_id", data.send_id);
      // datesend.setAttribute('data-toggle', 'modal');
      // datesend.setAttribute('data-target', '#email_detail');

      var status = document.createElement('button');
      status.setAttribute("data-toggle", "tooltip");
      status.setAttribute("type", "button");
      if (data.status == "0") {
        status.setAttribute("Class", "btn btn-danger tooltipset");
        status.setAttribute("data-placement", "top");
        status.setAttribute("title", "ไม่มีข้อมูล");
        status.innerHTML = "ส่งไม่สำเหร็ยจ";
      } else {
        status.setAttribute("Class", "btn btn-success tooltipset");
        status.setAttribute("data-placement", "top");
        status.setAttribute("title", "หมายเลขใบวางบิลที่ " + data.bill_id);
        status.innerHTML = "ส่งสำเหร็ยจ";

      }
      cell1.append(order);
      cell2.append(datesend);
      cell3.append(status);
    }

    function showemail(send_id) {
      var key = "showemail";
      $.ajax({
        url: "Process/customerdetail.php",
        data: {
          key,
          send_id
        },
        type: "POST",
        success: function(require) {
          $('#filebill').html(" ");
          var require = JSON.parse(require);
          $('#modal-body').html(require.Textemail);
          var atag = document.createElement('a');
          atag.setAttribute('href', require.Bill_locations);
          atag.innerHTML = require.Bill_name;
          $('#filebill').append(atag);
        },
        error: function(e) {
          console.log(e);
        }
      });
      $('#email_detail').modal('show');
    }

    function londpayment(Co_id) {
      var key = "londpayment";
      $.ajax({
        url: "Process/customerdetail.php",
        data: {
          key,
          Co_id
        },
        type: "POST",
        success: function(require) {
          // console.log(require);
          $("#listpayment").empty();
          var require = JSON.parse(require);
          for (var t = 0; t < require.length; t++) {
            createpaymentimg(require[t]);
            // $("#listpayment").append('<li class="list-group-item"><div class="row"><a href="#" class="paylist" id="' + require[t].payment_id + '"><div class="col">' + require[t].date + '</div></a><div class="col-md-5 ml-auto text-right">' + require[t].status + '</div></div></li>');
          }
          // createpaymentlist(require);
        },
        error: function(e) {
          console.log(e);
        }
      });
    }

    function createpaymentimg(data) {
      var li = document.createElement('li');
      li.setAttribute('class', 'list-group-item');
      var divrow = document.createElement('div');
      divrow.setAttribute('class', 'row');
      var atag = document.createElement('a');
      atag.setAttribute('href', '#slippayment');
      atag.setAttribute('onclick', "londimg(" + data.payment_id + ")");
      atag.setAttribute('id', data.payment_id);
      var divcold = document.createElement('div');
      divcold.setAttribute('class', 'col');
      divcold.innerHTML = data.date;
      var divcols = document.createElement('div');
      divcols.setAttribute('class', 'col ml-auto text-right');
      divcols.innerHTML = data.status;
      atag.append(divcold);
      divrow.append(atag);
      divrow.append(divcols);
      li.append(divrow);
      const ul = document.getElementById('listpayment');
      ul.append(li);
    }

    function londimg(payment_id) {
      var key = "londimg";
      $.ajax({
        url: "Process/customerdetail.php",
        data: {
          key,
          payment_id
        },
        type: "POST",
        success: function(require) {
          // $("#listpayment").empty();
          $('#slippayment').html(" ");
          const slippayment = document.getElementById('slippayment');
          var require = JSON.parse(require);
          for (var t = 0; t < require.length; t++) {
            var div_item = document.createElement("div");
            div_item.setAttribute("class", "col-sm-6 col-md-4 col-lg-3 item");
            var atag = document.createElement("a");
            atag.setAttribute("href", require[t].Slipname);
            atag.setAttribute("data-lightbox", "photos")
            var img = document.createElement("img");
            img.setAttribute("src", require[t].Slipname);
            img.setAttribute("class", "img-fluid");
            img.setAttribute("width", "200");
            img.setAttribute("height", "300");
            img.setAttribute("data-toggle", "tooltip");
            img.setAttribute("data-placement", "top");
            img.setAttribute("title", require[t].dateuplond)
            atag.append(img);
            div_item.append(atag);
            slippayment.append(div_item);
          }
          $('#num_rows').html(require[0].num_rows);
          $('#statuspay').html(require[0].status);
          console.log(require);
        },
        error: function(e) {
          console.log(e);
        }
      });
      //  alert(payment_id);
    }
  </script>
  <!-- <div class="col-sm-6 col-md-4 col-lg-3 item">
    <a href="https://picsum.photos/500/600" data-lightbox="photos">
      <img class="img-fluid" src="https://picsum.photos/500/600" data-toggle="tooltip" data-placement="top" title="20 ก.พ 2564 18:20:13">
    </a>
  </div> -->
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
                  <h1 class="text-dark">รายชื่อลูกค้าปัจจุบัน</h1>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
      <br>
      <div class="container">
        <div class="main-body">
          <!-- <form method="POST" action="Process/Cusomeradd.php" id="cusomeradd"> -->
            <div class="row gutters-sm">
              <div class="col-md-4 mb-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                      <div class="avatar-wrapper">
                        <img src="images/profile.png" alt="Admin" width="180" id="profileimg">
                        <input class="file-upload" name="files" accept=".png, .jpg, .jpeg" onchange="uplondpdf(this)" type="file" id="profil" style="display: none;">
                      </div>
                    </div>
                    <div class="row edittext">
                      <h4 class="ml-2">
                        บริษัท :
                      </h4>
                      <span class="text-secondary ml-2" id="Company"></span>
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
                        <h6 class="mb-0">เบอร์ติดต่อ :</h6>
                      </div>
                      <span class="col-sm-9 text-secondary" id="Co_number">

                      </span>
                    </div>
                    <hr>
                    <div class="row edittext">
                      <div class="col-sm-3">
                        <h6 class="mb-0">ราคา :</h6>
                      </div>
                      <span class="col-sm-9 text-secondary" id="Rates">

                      </span>
                    </div>
                    <hr>
                    <div class="row edittext">
                      <div class="col-sm-3">
                        <h6 class="mb-0">ที่อยู่ :</h6>
                      </div>
                      <span class="col-sm-9 text-secondary" id="Address">

                      </span>
                    </div>
                    <hr>
                    <div class="row edittext">
                      <div class="col-sm-3">
                        <h6 class="mb-0">รายละเอียด :</h6>
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
                        <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Billdetail</i>รายละเอียดบิลเรียกเก็บเงิน</h6>
                        <div class="panel-body no-padding">
                          <div class="table-responsive">
                            <table class="table table-hover table-email display" id="table_id">
                              <thead>
                                <tr>
                                  <th style="width:61px;">ลำดับ</th>
                                  <th>วันที่</th>
                                  <th>สถานะ</th>
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
                            <button type="button" id="editcustomer" class="btn btn-danger ml-3">ปิด</button>
                          </li>
                          <li class="list-group-item">
                            แก้ไขเนื้อหาอีเมล
                           <a href="Alertmailedit.php?Co_id=<?php echo $Co_id; ?>"><button type="button" id="editcustomer" class="btn btn-warning ml-3">แก้ไข</button></a>
                          </li>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <!-- </form> -->
        </div>
      </div>
      <!-- popup Email Detail -->
      <div class="modal fade" id="email_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">รายละเอียดอีเมล</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="modal-body">

            </div>
            <div style="background-color: #D0D3D4;">
              <div class="row">
                <div class="col ml-5 my-3" id="filebill">
                  <!-- <a href="#" >31_2564.pfd</a> -->
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!--  -->
      <div class="container pb-5">
        <div class="row" id="paymentlist">
          <div class="col-md-3 listpaymente boxscroll">
            <div>
              <center>
                <h3>รายการชำระเงิน</h3>
              </center>
            </div>
            <ul class="list-group list-group-flush" id="listpayment">
              <li class="list-group-item">
                <div class="row">
                  <div class="col">ชำระเงินปี 2564</div>
                  <div class="col-md-5 text-right">สถานะ</div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="row">
                  <a href="#" class="paylist" id="1">
                    <div class="col">ชำระเงินปี 2564</div>
                  </a>
                  <div class="col-md-5 text-right">สถานะ</div>
                </div>
              </li>
            </ul>
          </div>
          <div class="col photo-gallery">
            <div>
              <center>
                <h3>หลักฐานสลิปเรียกเก็บเงิน</h3>
              </center>
            </div>
            <div class="row detailpaymente boxscroll photos" id="slippayment">
              <!-- <div class="col-sm-6 col-md-4 col-lg-3 item">
                  <a href="https://picsum.photos/500/600" data-lightbox="photos">
                    <img class="img-fluid" src="https://picsum.photos/500/600" data-toggle="tooltip" data-placement="top" title="20 ก.พ 2564 18:20:13">
                  </a>
                </div> -->
            </div>
            <div>
              <center>
                <h3>รายละเอียด</h3>
              </center>
            </div>
            <div class="row concludepaymente" style="height: 19.5vh;">
              <ul class="list-group">
                <li class="list-group">
                  <span> แบ่งจ่าย : <b id="num_rows">3</b> งวด</span>
                </li>
                <li class="list-group">
                  <span> สถานะการชำระเงิน : <b id="statuspay">เสร็ยจสิ้น</b></span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>