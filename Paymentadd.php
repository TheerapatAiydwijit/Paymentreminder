<!DOCTYPE html>
<html lang="en">
<?php
include("include/loginChek.php");
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
        var li = document.getElementById("periodtable").innerHTML = " ";
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
          var tital = "ทำการบันทึกรายละเอียดการจ่ายเงินเสร็จสิ้น";
          Notifytoprigth(tital, icon);
          setTimeout(function() {
            location.reload();
          }, 1500)
        },
        error: function(e) {
          console.log(e);
        }
      });
    }

    function fileupdate(file) {
      var files = file.files;
      var filename = files[0].name;
      var url = URL.createObjectURL(files[0]);
      const atage = document.createElement("a");
      atage.href = url;
      atage.target = "_blank";
      atage.innerHTML = filename;
      $(file).closest('li').children('.textshow').find('small').html(atage);
    }

    function tarclik(location) {
      $(location).closest('div').children('input').trigger("click");
    }

    function creatperiod(num) {
      for (var x = 1; x <= num; x++) {
        var ul = document.getElementById("periodtable");

        // ul.appendChild(file);
        // ----- //
        var li = document.createElement('li');
        li.setAttribute("class", "list-group-item d-flex justify-content-between lh-condensed");
        var divempty = document.createElement('div');
        divempty.setAttribute('class', 'textshow')
        var h6cmy_0 = document.createElement('h6');
        h6cmy_0.setAttribute("class", "my-0");
        h6cmy_0.innerHTML = "งวดที่ " + x;
        divempty.appendChild(h6cmy_0);
        var smalltext = document.createElement('small');
        smalltext.setAttribute("class", "text-muted");
        smalltext.innerHTML = "ไม่มีไฟล์";
        divempty.appendChild(smalltext);
        li.appendChild(divempty);
        var divinput = document.createElement('div');
        divinput.setAttribute('class', 'input-group-prepend');
        // fileuplond
        var file = document.createElement('input')
        file.type = 'file';
        file.setAttribute('accept', '.png, .jpg, .jpeg');
        file.setAttribute("name", "slip[]");
        file.setAttribute("onchange", "fileupdate(this)");
        file.style.display = 'none';
        divinput.appendChild(file);
        // 
        var button = document.createElement('button');
        button.setAttribute("type", "button");
        button.setAttribute("class", "btn btn-light");
        button.setAttribute("onclick", "tarclik(this)");
        var spanicon = document.createElement('span');
        spanicon.setAttribute("class", "input-group-text h1");
        var itag = document.createElement('i');
        itag.setAttribute("class", "fas fa-upload");
        spanicon.appendChild(itag);
        button.appendChild(spanicon);
        divinput.appendChild(button);
        li.appendChild(divinput)
        ul.appendChild(li);

      }
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
    <div class="content-wrapper px-2">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <?php if (isset($_GET['Edit'])) { ?>
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
      <form method="POST" action="Process/Paymentpang2.php" id="addPayment" enctype="multipart/form-data">
        <div class="row ml-5">
          <div class="col-md-7">
            <div class="card">
              <div class="card-header">
                <h4 class="d-flex justify-content-between align-items-center">
                  <span class="text-muted">รายละเอียด</span>
                  <!-- <span class="badge badge-secondary badge-pill">3</span> -->
                </h4>
              </div>
              <div class="card-body">
                <select class="js-data-example-ajax" name="Websitename" style="width: 100%;">
                  <?php
                  if (isset($_GET['Edit'])) { ?>
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
                    placeholder: 'กรุณาพิมโดเมนเนมของเว็ปไซต์',
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
                    $('#period').prop("readonly", false);
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
                    // if (state.order % 2 == 0) {
                    //   var $state = $(
                    //     '<div class="row" style="background-color:#D7DBDD;"><div class="col-md-8">' + 'โดเมนเนม ' + state.text + ' จากบริษัท ' + state.comname + '</div> <div class="col ml-auto">' + state.Datethai + '</div></div>'
                    //   );
                    // } else {
                    //   var $state = $(
                    //     '<div class="row"><div class="col-md-8">' + 'โดเมนเนม ' + state.text + ' จากบริษัท ' + state.comname + '</div> <div class="col ml-auto">' + state.Datethai + '</div></div>'
                    //   );
                    // }
                    var $state = $(
                        '<div class="row"><div class="col-md-8">' + 'โดเมนเนม ' + state.text + ' จากบริษัท ' + state.comname + '</div> <div class="col ml-auto">' + state.Datethai + '</div></div>'
                      );
                    return $state;
                  };
                </script>
                <br><br>
                <div class="mb-3">
                  <label for="username">ชื่อบริษัท</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-list-alt"></i></span>
                    </div>
                    <input type="text" class="form-control" id="Company" readonly name="Company" placeholder="ชื่อบริษัท" value="<?php if (isset($edit)) {
                                                                                                                                    echo $cusdata['Company'];
                                                                                                                                  } ?>">
                  </div>
                </div>
                <div class="row">
                  <div class="mb-3 col-md-6">
                    <label for="username">วันที่</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-list-alt"></i></span>
                      </div>
                      <input type="date" class="form-control" id="Duedate" readonly name="date" value="<?php if (isset($edit)) {
                                                                                                          echo $value['date'];
                                                                                                        } ?>" placeholder="วันที่">
                    </div>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="username">ราคา</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-list-alt"></i></span>
                      </div>
                      <input type="text" class="form-control" id="Rates" readonly name="Amount" value="<?php if (isset($edit)) {
                                                                                                          echo $cusdata['Rates'];
                                                                                                        } ?>" placeholder="ราคา">
                    </div>
                  </div>
                </div>

              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-8 text-right">
                    <h4>จำนวนงวดชำระ</h4>
                  </div>
                  <div class="col-md-4 ml-auto">
                    <input type="number" class="form-control" id="period" readonly required name="Pamentperiod" <?php if (isset($edit)) echo "readonly";  ?> placeholder="จำนวณการแบ่งงวดชำระ" value="<?php if (isset($edit)) {
                                                                                                                                                                                                        echo $numrowdetail;
                                                                                                                                                                                                      } ?>">
                  </div>
                </div>
              </div>
            </div>
            <a href="Paymentlist.php"><button type="button" class="btn btn-info text-white"><i class="fas fa-arrow-left"></i>ย้อนกลับ</button></a>
            <button type="submit" class="btn btn-success text-white"><i class="far fa-save"></i> บันทึก</button>
          </div>
          <div class="col-md-3 order-md-2 mb-4">
            <ul class="list-unstyled">
              <li>
                <ul class="list-group mb-3" id="periodtable">
                  <?php if (isset($_GET['Edit'])) {
                    $i = 1;
                    while ($datapaymrnted = mysqli_fetch_array($quarydetail, MYSQLI_ASSOC)) { ?>
                      <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div class="textshow">
                          <h6 class="my-0">งวดที่ 1</h6>
                          <small class="text-muted">
                            <?php if ($datapaymrnted['Slipname'] != '0') { ?>
                              <a href="customerdetail/<?php echo $cusdata['Company'] . $Co_id . "/slip" . "/" . $datapaymrnted['Slipname']; ?>" target="_blank"><?php echo $datapaymrnted['Slipname']; ?></a>
                            <?php } else {
                              echo "ไม่มีไฟล์";
                            } ?>
                          </small>
                        </div>
                        <div class="input-group-prepend">
                          <input type="File" id="<?php echo $datapaymrnted['order_detail']; ?>" name="slip[]" onchange="uplondslip(this)" accept='.png, .jpg, .jpeg' style="display: none;">
                          <button type="button" class="btn btn-light" onclick="tarclik(this)">
                            <span class="input-group-text h1">
                              <i class="fas fa-upload"></i>
                            </span>
                          </button>
                        </div>
                      </li>
                  <?php $i++;
                    }
                  } ?>
                </ul>
              </li>
              <!-- <li>
                <ul class="list-group mb-3" id="periodtable">
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <h6 class="my-0">งวดที่ 1</h6>
                      <small class="text-muted">filename</small>
                    </div>
                    <div class="input-group-prepend">
                      <button type="button" class="btn btn-light">
                        <span class="input-group-text h1"><i class="far fa-eye"></i></span>
                      </button>
                    </div>
                    <span class="text-muted"><i class="far fa-eye"></i></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                      <h6 class="my-0">งวดที่ 2</h6>
                      <small class="text-muted">No file</small>
                    </div>
                    <div class="input-group-prepend">
                      <button type="button" class="btn btn-light">
                        <span class="input-group-text h1"><i class="fas fa-upload"></i></span>
                      </button>
                    </div>
                  </li>
                </ul>
              </li> -->
              <li class="list-group-item d-flex justify-content-between">
                <span>สถาณะการชำระเงิน</span>
                <strong><?php
                        if (isset($_GET['Edit'])) {
                          if ($value['status'] == "0") {
                            echo '<span class="badge badge-secondary badge-warning">กำลังดำเนินการ</span>';
                          } else {
                            echo '<span class="badge badge-secondary badge-success">เสร็จสิ้น</span>';
                          }
                        } else {
                          echo '<span class="badge badge-secondary badge-info">ไม่ระบุ</span>';
                        }
                        ?></strong>
              </li>
            </ul>
          </div>
        </div>
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
      </form>
      <br>
    </div>
  </div>
  </div>
</body>

</html>