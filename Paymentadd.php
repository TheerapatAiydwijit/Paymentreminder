<!DOCTYPE html>
<html lang="en">
<?php
include("include/loginChek.php");
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
  <link rel="stylesheet" href="helper/select2/css/select2.min.css">
  <!--===============================================================================================-->
  <script src="helper/jquery/jquery-3.5.1.min.js"></script>
  <script src="helper/sweetalert/sweetalert2@10.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  <script src="helper/select2/js/select2.min.js"></script>
  <script src="helper/select2/js/i18n/th.js"></script>
  <script>
    $(document).ready(function() {
      $('#addPayment').submit(function(even) {
        event.preventDefault();
        var data = new FormData(this);
        $.ajax({
          url: "Process/Paymentpang2.php",
          cache: false,
          contentType: false,
          processData: false,
          data: data,
          type: "post",
          success: function(require) {
            var table = document.getElementById("periodtable").innerHTML = " ";
            $('#addPayment').trigger("reset");
            Notifytoprigth("บันทึกข้อมูลการชำระเงินสำเหร็ยจ", "success");
            console.log(require);
          }
        });
      });
      $('#period').on('keyup change', function() {
        // console.log("Dawgfaw");
        var table = document.getElementById("periodtable").innerHTML = " ";
        var numperiod = $(this).val();
        creatperiod(numperiod);
      });
    });

    function uplondslip(alltag) {
      var orderid = alltag.getAttribute("id");
      var file = alltag.files[0];
      var Co_id = $('#Co_idfile').val();
      var date = $('#Duedate').val();
      var data = new FormData();
      data.append('slip', file);
      data.append('Edit', orderid);
      data.append('Co_id', Co_id);
      data.append('date', date);
      // console.log(Co_id);
      $.ajax({
        url: "Process/Paymentpang2.php",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: "post",
        success: function(require) {
          console.log(require);
          var icon = "success";
          var tital = "ทำการบันทึกรายละเอียดการจ่ายเงินเสร็ยจสิ้น";
          Notifytoprigth(tital, icon);
        },
        error: function(e) {
          console.log(e);
        }
      });
    }

    function creatperiod(num) {
      for (var x = 1; x <= num; x++) {
        var table = document.getElementById("periodtable");
        var number = table.rows.length;
        var row = table.insertRow(number);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        // var cell3 = row.insertCell(2);
        var topic = document.createElement('p');
        topic.innerHTML = "งวดที่ " + x;
        var file = document.createElement('input')
        file.type = 'file';
        file.setAttribute('accept', '.png, .jpg, .jpeg');
        file.setAttribute("name", "slip[]");
        cell1.appendChild(topic);
        cell2.appendChild(file);
      }
    }
    // $(document).ready(function(){
    //   $('#addPayment input').dblclick(function(){
    //       $(this).attr("readonly", false);
    //   });
    //   $('#addPayment input').focusout(function(){
    //       $(this).attr("readonly", "true");
    //   });
    // })
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
    <div class="content-wrapper px-2">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <?php if(isset($_GET['Edit'])){ ?>
                  <li class="breadcrumb-item"><a href="Paymentlist.php">รายการที่ต้องชำระเงิน</a></li>
                <?php } ?>
                <li class="breadcrumb-item active">รายละเอียดการชำระเงิน</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <div class="col-md-15 text-center">
        <div id="layoutSidenav_content">
          <main>
            <div class="container-fluid" style="margin-top: 3vh">
              <div class="row">
                <div class="col-md-11 text-center">
                  <h1 class="text-dark">เพิ่มรายการชำระเงิน</h1>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
      <br>
      <?php
      include 'include/Connect.php';
      if (isset($_GET['Edit'])) {
        $edit = $_GET['Edit'];
        $swl = "SELECT * FROM payment WHERE payment_id='$edit'";
        $result = mysqli_query($conn, $swl);
        $value = mysqli_fetch_array($result);
        $Co_id = $value['Co_id'];
        //ข้อมูลบริษัท
        $sqlcustomer = "SELECT Company,Domain,Rates FROM customer WHERE Co_id='$Co_id'";
        $quary = mysqli_query($conn, $sqlcustomer);
        $cusdata = mysqli_fetch_array($quary, MYSQLI_ASSOC);
        //ข้อมมูลรายละเอียดการชำระเงิน
        $sqlpaydetail = "SELECT * FROM payment_detail WHERE payment_id='$edit'";
        $quarydetail = mysqli_query($conn, $sqlpaydetail);
        $numrowdetail = mysqli_num_rows($quarydetail);
      }
      ?>
      <form method="POST" action="Process/Paymentpang2.php" id="addPayment" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-7">
            <label for="inputtext">ชื่อเว็บไซต์หรือชื่อบริษัท:</label> <br>
            <select class="js-data-example-ajax" name="Websitename" style="width: 100%;">
              <?php
              if (isset($edit)) { ?>
                <script>
                  $(".js-data-example-ajax").prop("disabled", true);
                </script>
                <option value="<?php echo $cusdata['Domain']; ?>" selected="selected"><?php echo $cusdata['Domain']; ?></option>
              <?php } ?>
            </select>
            <script>
              $('.js-data-example-ajax').select2({
                language: "th",
                delay: 250,
                placeholder: 'กรุณาพิมโดเมนเนมของเว็ปไซ้ย์',
                templateResult: formatState,
                templateSelection: formatselect,
                ajax: {
                  url: 'Process/Paymentaddsearch.php',
                  dataType: 'json',
                },
              });

              function formatselect(state) {
                if (!state.id) {
                  // alert(state.id);
                  return state.text;
                }
                $co_id = state.id;
                $('#Co_idfile').val($co_id)
                $('#Co_idfile').attr("readonly", "true");
                $('#Company').val(state.comname);
                $('#Company').attr("readonly", "true");
                $('#Rates').val(state.Rates);
                $('#Rates').attr("readonly", "true");
                $('#Duedate').val(state.Duedate);
                $('#Duedate').attr("readonly", "true");
                var Lyear = state.Lyear;
                if (Lyear > 0) {
                  // creatperiod(Lyear);
                  $('#period').val(Lyear);
                  $('#period').trigger("keyup");
                }
                return state.text;
              };

              function formatState(state) {
                if (!state.id) {
                  return state.text;
                }
                var $state = $(
                  '<span> - ' + 'โดเมนเนม ' + state.text + ' จากบริษัท ' + state.comname + '</span>'
                );
                return $state;
              };
            </script>
            <label for="inputtext">ชื่อบริษัท:</label>
            <input type="text" class="form-control" id="Company" readonly name="Company" value="<?php if (isset($edit)) {
                                                                                                  echo $cusdata['Company'];
                                                                                                } ?>" placeholder="ชื่อบริษัท">
            <label for="inputtext">วันที่:</label>
            <input type="date" class="form-control" id="Duedate" readonly name="date" value="<?php if (isset($edit)) {
                                                                                                echo $value['date'];
                                                                                              } ?>" placeholder="วันที่">
            <label for="inputtext">ราคา:</label>
            <input type="text" class="form-control" id="Rates" readonly name="Amount" value="<?php if (isset($edit)) {
                                                                                                echo $cusdata['Rates'];
                                                                                              } ?>" placeholder="ราคา">
            <label for="inputtext">จำนวนงวดชำระ :</label>
            <input type="number" class="form-control" id="period" name="Pamentperiod" <?php if (isset($edit)) echo "readonly";  ?> placeholder="จำนวณการแบ่งงวดชำระ" value="<?php if (isset($edit)) {
                                                                                                                                                                              echo $numrowdetail;
                                                                                                                                                                            } ?>">
            <?php if (isset($_GET['Edit'])) { ?>
              <a href="Paymentlist.php">
                <--ย้อนกลับ </a>
                  <!-- <button type="button" id="forcesuccess">บันทึกว่าเสร็ยจสิ้นแล้ว</button> -->
                <?php } else { ?>
                  <input type="submit" class="btn btn-warning" value="บันทึก">
                <?php } ?>

                <?php if (isset($_GET['Edit'])) { ?>
                  <input type="hidden" name="Edit" value="<?php echo $value['payment_id']; ?>">
                  <input type="hidden" name="ed" value="edit">
                  <input type="hidden" name="Co_id" id="Co_idfile" value="<?php if (isset($edit)) {
                                                                            echo $Co_id;
                                                                          } ?>">
                <?php } else { ?>
                  <input type="hidden" name="Add" value="Add">
                  <input type="hidden" name="Co_id" value="" id="Co_idfile">
                <?php } ?>
          </div>
          <div class="col-md-5">
            <table id="periodtable">
              <?php
              if (isset($edit)) {
                $i = 1;
                while ($datapaymrnted = mysqli_fetch_array($quarydetail, MYSQLI_ASSOC)) { ?>
                  <tr>
                    <td>
                      งวดที่ <?php echo $i; ?>
                    </td>
                    <td>
                      <input type="File" id="<?php echo $datapaymrnted['order_detail']; ?>" name="slip[]" onchange="uplondslip(this)">
                    </td>
                    <td>
                      <?php
                      if (!$datapaymrnted['Slipname'] == '0') { ?>
                        <a href="customerdetail/<?php echo $cusdata['Company'] . $Co_id . "/slip" . "/" . $datapaymrnted['Slipname']; ?>" target="_blank">สลิปการจ่ายเงิน</a>
                      <?php } ?>
                    </td>
                  </tr>

              <?php $i++;
                }
              }
              ?>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
  </div>
</body>

</html>