<?php
include("include/loginChek.php");
include('include/Connect.php');
if (isset($_GET['Offerprice_id'])) {
  $Offerprice_id = $_GET['Offerprice_id'];
} else {
  $Offerprice_id = "NULL";
}
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
  <link rel="stylesheet" href="css/adminlte.min.css">
  <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="css/customer.css">
  <!--===============================================================================================-->
  <script src="helper/jquery/jquery-3.5.1.min.js"></script>
  <script src="helper/sweetalert/sweetalert2@10.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  <script src="helper/sweetalert/sweetalert2@10.js"></script>
  <script>
    $(document).ready(function() {
      $('#cusomeradd').submit(function(event) {
        // console.log("Dawfwa");
        var Offerprice_id = <?php echo $Offerprice_id; ?>;
        event.preventDefault();
        var key = "add";
        var data = new FormData(this);
        data.append("key", key);
        data.append("Offerprice_id", Offerprice_id);
        $.ajax({
          url: "Process/Cusomeradd.php",
          cache: false,
          contentType: false,
          processData: false,
          data: data,
          type: "post",
          success: function(require) {
            // console.log(require);
            var require = JSON.parse(require);
            if (require.status == "success") {
              $('#cusomeradd').trigger("reset");
              Notifytoprigth("บันทึกข้อมูลลูกค้าสำเหร็ยจ", "success");
              // console.log(require);
            } else {
              alert("ERROR");
              console.log(require.Message);
            }
          },
          error: function(e) {
            console.log(e);
          }
        });
      });
      $('#uplond').click(function() {
        $('#profil').trigger('click');
      });
    });

    function uplondpdf(fileist) {
      var files = fileist.files[0];
      var url = URL.createObjectURL(files);
      // alert(url);
      $('#profileimg').attr('src', url);
    }
    var Offerprice_id = <?php echo $Offerprice_id; ?>;
    if (!(Offerprice_id == "NULL")) {
      var key = "all";
      $.ajax({
        url: "Process/customer_offerprice_detail.php",
        data: {
          key,
          Offerprice_id
        },
        dataType: "json",
        type: "post",
        success: function(require) {
          // console.log(require);
          // var obj = JSON.parse(require);
          $('#Company').val(require.Company);
          $('#Domain').val(require.Domain);
          $('#email').val(require.Email);
          $('#Co_number').val(require.Co_number);
          if (!(require.Address == "ไม่มีข้อมูล")) {
            $('#address').val(require.Address);
          }
          if (!(require.Details == "ไม่มีข้อมูล")) {
            $('#details').val(require.Details);
          }
        },
        error: function(e) {
          console.log(e);
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
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="Choicecustomer.php">เลือกรูปแบบลูกค้า</a></li>
                <li class="breadcrumb-item active">ลูกค้าปัจจุบัน</li>
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
                  <h1 class="text-dark">เพิ่มรายชื่อลูกค้าปัจจุบัน</h1>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
      <div class="container">
        <div class="main-body">
          <form method="POST" action="Process/Cusomeradd.php" id="cusomeradd">
            <div class="row gutters-sm">
              <div class="col-md-4 mb-3">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                      <div class="avatar-wrapper">
                        <img src="images/profile.png" alt="Admin" width="180" id="profileimg">
                        <div class="upload-button" id="uplond">
                          <svg class="fa fa-arrow-circle-up" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="100%" height="100%" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1536 1536">
                            <path d="M1284 767q0-27-18-45L904 360l-91-91q-18-18-45-18t-45 18l-91 91l-362 362q-18 18-18 45t18 45l91 91q18 18 45 18t45-18l189-189v502q0 26 19 45t45 19h128q26 0 45-19t19-45V714l189 189q19 19 45 19t45-19l91-91q18-18 18-45zm252 1q0 209-103 385.5T1153.5 1433T768 1536t-385.5-103T103 1153.5T0 768t103-385.5T382.5 103T768 0t385.5 103T1433 382.5T1536 768z" fill="#626262" />
                          </svg>
                        </div>
                        <input class="file-upload" name="files" accept=".png, .jpg, .jpeg" onchange="uplondpdf(this)" type="file" id="profil" style="display: none;">
                      </div>
                    </div>
                    <div class="row edittext">
                      <div class="col-sm-10">
                        <div class="inputbox">
                          <input class="effect-1" type="text" required name="Company" id="Company" placeholder="ชื่อบริษัท :" style="width: 20vw;">
                          <span class="focus-border" style="max-width: 20vw;"></span>
                        </div>
                        <!-- <h6 class="mb-0">ชื่อบริษัท : <span></span> <input class="form-control" type="text" name=""> </h6> -->
                      </div>
                      <div class="col">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                          <path d="M13 13h-2V7h2m0 10h-2v-2h2M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2z" fill="#626262" />
                          <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)" />
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card mt-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap edittext">
                      <div class="inputbox col-sm-10">
                        <input class="effect-1" type="text" required name="Domain" id="Domain" placeholder="Website :">
                        <span class="focus-border"></span>
                      </div>
                      <div class="col">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                          <path d="M13 13h-2V7h2m0 10h-2v-2h2M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2z" fill="#626262" />
                          <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)" />
                        </svg>
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
                          <input class="effect-1" type="text" required name="Email" id="email" placeholder="Email :">
                          <span class="focus-border"></span>
                        </div>
                      </div>
                      <div class="col">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                          <path d="M13 13h-2V7h2m0 10h-2v-2h2M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2z" fill="#626262" />
                          <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)" />
                        </svg>
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
                          <input class="effect-1" type="text" required name="Co_number" id="Co_number" placeholder="เบอร์ติดต่อ :">
                          <span class="focus-border"></span>
                        </div>
                      </div>
                      <div class="col">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                          <path d="M13 13h-2V7h2m0 10h-2v-2h2M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2z" fill="#626262" />
                          <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)" />
                        </svg>
                      </div>
                    </div>
                    <br>
                    <div class="row edittext">
                      <div class="col-sm-10">
                        <div class="inputbox">
                          <input class="effect-1" type="number" required name="Rates" id="Rates" placeholder="ราคา :">
                          <span class="focus-border"></span>
                        </div>
                      </div>
                      <div class="col">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                          <path d="M13 13h-2V7h2m0 10h-2v-2h2M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2z" fill="#626262" />
                          <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)" />
                        </svg>
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
                        <div class="panel-body no-padding">
                          <div class="form-group">
                            <label>วันครบกำหนดชำระ :</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path d="M19 19H5V8h14m-3-7v2H8V1H6v2H5c-1.11 0-2 .89-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-1V1" fill="#626262" />
                                    <rect x="0" y="0" width="24" height="24" fill="rgba(0, 0, 0, 0)" />
                                  </svg></span>
                              </div>
                              <input type="date" required name="Duedate" class="form-control">
                            </div>
                            <!-- /.input group -->
                          </div>
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
                          <?php  ?>
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
  </div>
</body>

</html>