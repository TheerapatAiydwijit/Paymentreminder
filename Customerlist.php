<?php
include("include/loginChek.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายชื่อลูกค้า</title>
  <!--===============================================================================================-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <!--===============================================================================================-->
  <!-- <link rel="stylesheet" href="css/admin.css"> -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="css/customerlist.css">
  <link rel="stylesheet" type="text/css" href="helper/DataTables/datatables.min.css" />
  <!--===============================================================================================-->
  <script src="helper/jquery/jquery-3.5.1.min.js"></script>
  <script src="helper/sweetalert/sweetalert2@10.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css
" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <script type="text/javascript" src="helper/DataTables/datatables.min.js"></script>
</head>
<script type="text/javascript">
  function closecus(argument) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: 'No, cancel!',
      confirmButtonText: 'Yes, delete it!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "Process/Customerdelete.php",
          type: "POST",
          data: {
            argument
          },
          success: function(require) {
            if (require == 1) {
              var icon = "success";
              var mass = "เปลี่ยนสถานะเสร็ยจสิ้น";
              Notifytoprigth(mass, icon);
              var typecu = $('#typecustomer').val();
              createtable(typecu);
              // createtable();
            } else {
              alert(require);
            }
          },
          error: function(e) {
            console.log(e);
          }
        })
      } else if (
        result.dismiss === Swal.DismissReason.cancel
      ) {
        var lo = "Customerlist.php";
        window.location.href = lo;
      }
    })
  }

  function renew(renewcus) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: 'No, cancel!',
      confirmButtonText: 'Yes, delete it!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "Process/Customerdelete.php",
          type: "POST",
          data: {
            renewcus
          },
          success: function(require) {
            if (require == 1) {
              var icon = "success";
              var mass = "เปลี่ยนสถานะเสร็ยจสิ้น";
              Notifytoprigth(mass, icon);
              var typecu = $('#typecustomer').val();
              createtable(typecu);
              // createtable();
            } else {
              alert(require);
            }
          },
          error: function(e) {
            console.log(e);
          }
        })
      } else if (
        result.dismiss === Swal.DismissReason.cancel
      ) {
        var lo = "Customerlist.php";
        window.location.href = lo;
      }
    })
  }

  function deleteOffer(Co_id) {

  }
  $(document).ready(function() {
    $('#typecustomer').change(function() {
      var type = $(this).val();
      if (type == "Customer") {
        var html = "รายชื่อลูกค้าลูกค้าปัจจุบัน";
      } else {
        var html = "รายชื่อลูกค้าอยู่ขั้นตอนเสนอราคา";
      }
      $('#topic').html(html);
      console.log(html);
      // console.log(type);
      createtable(type);
    });
  });
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
                <li class="breadcrumb-item active">รายการลูกค้า</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <div id="layoutSidenav_content">
        <main><br><br>
          <div class="container-fluid" style="margin-top: 1vh">
            <div class="row">
              <div class="col-md-11 text-center">
                <h1 class="text-dark" id="topic">รายชื่อลูกค้าลูกค้าปัจจุบัน</h1>
              </div>
              <div class="col-md-11 text-center">
                <select name="customerT" id="typecustomer">
                  <option value="Customer" id="oddcust">ลูกค้าปัจจุบัน</option>
                  <option value="offerprice" id="offer">อยู่ขั้นตอนเสนอราคา</option>
                </select>
              </div>
            </div>
            <div class="col-md-12 text-center">
              <div class="container">
                <?php include 'include/Connect.php';
                ?>
                <div class="panel-body">
                  <table id="table_id" class="display table">
                    <thead>
                      <tr bgcolor="black">
                        <th>
                          ลำดับ
                        </th>
                        <th>
                          ชื่อบริษัท
                        </th>
                        <th>
                          ชื่อเว็บไซต์
                        </th>
                        <th>
                          ครบกำหนด
                        </th>
                        <th>
                          รายละเอียด
                        </th>
                        <th>
                          ลบ
                        </th>
                      </tr>
                    </thead>
                  </table>
                  <script>
                    createtable("Customer");

                    function createtable(key) {
                      try {
                        table.destroy();
                      } catch (e) {

                      }
                      if (key == "offerprice") {
                        // $('#typecustomer').find('option[value="offerprice"]').attr('selected','selected')
                        table = $('#table_id').DataTable({
                          ajax: {
                            "url": 'Process/customerlist.php',
                            "type": "POST",
                            "data": function(d) {
                              return $.extend({}, d, {
                                "key": key
                              });
                            }
                          },
                          columns: [{
                            data: 'Offerprice_id',
                            render: function(data, type) {
                              return data;
                            }
                          }, {
                            data: 'Company',
                            render: function(data, type) {
                              return data;
                            }
                          }, {
                            data: 'Domain',
                            render: function(data, type) {
                              return data;
                            }
                          }, {
                            data: 'Duedate',
                            render: function(data, type) {
                              return "อยู่ในขั้นตอนเสนอราคา";
                            }
                          }, {
                            data: 'Offerprice_id',
                            render: function(data, type) {
                              return '<a href="Customer_offerprice_detail.php?Edit=' + data + '"><button class="btn btn-warning btn-sm">ดูรายละเอียด</button></a>';
                            }
                          }, {
                            data: 'Sd',
                            render: function(data, type) {
                              return '<a id="test" onclick="deleteOffer(' + data.Offerprice_id + ')"><button class="btn btn-danger btn-sm">ลบ</button></a>';
                            }
                          }]
                        });
                      } else {
                        // $('#typecustomer').find('option[value="Customer"]').attr('selected','selected')
                        table = $('#table_id').DataTable({
                          ajax: {
                            "url": 'Process/customerlist.php',
                            "type": "POST",
                            "data": function(d) {
                              return $.extend({}, d, {
                                "key": key
                              });
                            }
                          },
                          columns: [{
                            data: 'Co_id',
                            render: function(data, type) {
                              return data;
                            }
                          }, {
                            data: 'Company',
                            render: function(data, type) {
                              return data;
                            }
                          }, {
                            data: 'Domain',
                            render: function(data, type) {
                              return data;
                            }
                          }, {
                            data: 'Duedate',
                            render: function(data, type) {
                              return data;
                            }
                          }, {
                            data: 'Co_id',
                            render: function(data, type) {
                              return '<a href="Customerdetail.php?Edit=' + data + '"><button class="btn btn-warning btn-sm">ดูรายละเอียด</button></a>';
                            }
                          }, {
                            data: 'Sd',
                            render: function(data, type) {
                              if (data.Sdelete == "1") {
                                return '<a id="test" onclick="renew(' + data.Co_id + ')"><button class="btn btn-secondary btn-sm">ต่ออายุ</button></a>';
                              } else {
                                return '<a id="test" onclick="closecus(' + data.Co_id + ')"><button class="btn btn-danger btn-sm">ลบ</button></a>';
                              }
                            }
                          }]
                        });
                      }


                    }
                  </script>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
      <br>
      <br>
      <br>
    </div>
  </div>
  </div>
</body>

</html>