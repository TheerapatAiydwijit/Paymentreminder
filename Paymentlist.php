<?php
include('include/Connect.php');
include("include/loginChek.php");
$month = date("m") + 1;
$year = date("Y");
if ($month > 12) {
  $month = "1";
}

?>
<!DOCTYPE html>
<html lang="en">

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
  <link rel="stylesheet" type="text/css" href="helper/DataTables/datatables.min.css" />

  <!--===============================================================================================-->
  <script src="helper/jquery/jquery-3.5.1.min.js"></script>
  <script src="helper/sweetalert/sweetalert2@10.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  <script type="text/javascript" src="helper/DataTables/datatables.min.js"></script>
</head>
<script>
  function Deletepay(pay_id) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: 'ต้องการลบรายการชำระเงิน',
      text: "ข้อมูลรายการจะหายไปต้องการลบหรือไม่",
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: 'No, cancel!',
      confirmButtonText: 'Yes, delete it!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        var key = "1";
        $.ajax({
          url: "Process/paymentlist.php",
          data: {
            pay_id,
            key
          },
          type: "POST",
          success: function(require) {
            if (require == "1") {
              tablecreate();
            }
          }
        });
      } else if (
        result.dismiss === Swal.DismissReason.cancel
      ) {

      }
    })
  }
</script>
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
                <li class="breadcrumb-item active">รายการที่ต้องชำระเงิน</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <div id="layoutSidenav_content">
        <main>
          <div class="container-fluid" style="margin-top: 1vh">
            <div class="row">
              <div class="col-md-11 text-center">
                <h1 class="text-dark">รายการที่ต้องชำระเงิน</h1>
              </div>
            </div>
            <br>
            <div class="row mb-2">
              <div class="col-md-2 text-right">
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
              </div>
              <div class="col-md-1">
                <select name="year" id="year">
                  <?php
                  $sqlyear = "SELECT date FROM payment";
                  $yearquary = mysqli_query($conn, $sqlyear);
                  $yeararray = array();
                  while ($allyear = mysqli_fetch_array($yearquary, MYSQLI_ASSOC)) {
                    $strYear = date("Y", strtotime($allyear['date']));
                    if (!in_array($strYear, $yeararray)) {
                      array_push($yeararray, $strYear);
                    }
                  }
                  // print_r($yeararray);
                  $num = count($yeararray);
                  for ($i = 0; $i < $num; $i++) { ?>
                    <option value="<?php echo $yeararray[$i]; ?>" <?php if ($year == $yeararray[$i]) echo "selected"; ?>><?php echo $yeararray[$i]; ?></option>
                  <?php } ?>
                </select>

              </div>
              <div class="col-md-3 text-left">
                <button type="button" id="searchpayment">ค้นหา</button>
              </div>
            </div>
            <div class="col-md-12 text-center">
              <div class="panel-body no-padding">
                <div class="table-responsive">
                  <style>
                    #table_id thead {
                      background-color: #000000;
                    }

                    #table_id th {
                      color: #ffffff;
                    }
                  </style>
                  <table class="table table-hover table-email display" id="table_id">
                    <thead>
                      <tr>
                        <th>วันที่</th>
                        <th>ชื่อเว็บไซต์</th>
                        <th>ชื่อบริษัท</th>
                        <th>จำนวนเงินทั้งหมด</th>
                        <th>ชำระแล้ว</th>
                        <th>สถานะการชำระ</th>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                      </tr>
                    </thead>
                  </table>
                </div><!-- /.table-responsive -->
              </div><!-- /.panel-body -->
              <script>
                $(document).ready(function() {
                  tablecreate();
                  $('#searchpayment').click(function() {
                    tablecreate();
                  });
                });

                function tablecreate() {
                  var month = $('#selectmonth').val();
                  var year = $('#year').val();
                  // console.log(selectedmonth);
                  try {
                    table.destroy();
                  } catch (e) {

                  }
                  table = $('#table_id').DataTable({
                    ajax: {
                      "url": 'Process/paymentlist.php',
                      "type": "POST",
                      "data": function(d) {
                        return $.extend({}, d, {
                          "key": "0",
                          "month": month,
                          "year": year
                        });
                      }
                    },
                    columns: [{
                        data: 'payment_date',
                        render: function(data, type) {
                          return data;
                        }
                      },
                      {
                        data: 'Domain',
                        render: function(data, type) {
                          return data;
                        }
                      },
                      {
                        data: 'Company',
                        render: function(data, type) {
                          return data;
                        }
                      },
                      {
                        data: 'Rates',
                        render: function(data, type) {
                          return data;
                        }
                      },
                      {
                        data: 'remain',
                        render: function(data, type) {
                          return data;
                        }
                      },
                      {
                        data: 'paymentstatus',
                        render: function(data, type) {
                          if (data == "1") {
                            return '<span class="badge badge-secondary badge-success">ชำระเสร็ยจสิ้น</span>';
                          } else {
                            return '<span class="badge badge-secondary badge-warning">กำลังอยู่ระหว่างดำเนินการ</span>';
                          }
                        }
                      },
                      {
                        data: 'payment_id',
                        render: function(data, type) {
                          return '<a href="Paymentadd.php?Edit=' + data + '" class="Edit btn btn-warning" name="' + data + '">แก้ไข</a>';
                        }
                      },
                      {
                        data: 'payment_id',
                        render: function(data, type) {
                          return '<button type="button" class="Delete btn btn-danger" onclick="Deletepay(' + data + ')">ลบ</button>';
                        }
                      },
                    ]
                  });
                }
              </script>
            </div>
          </div>
        </main>
      </div>
      <br><br><br>
    </div>
  </div>
  </div>
</body>

</html>