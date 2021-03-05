<!DOCTYPE html>
<html lang="en">
<?php
include("include/loginChek.php");
include_once("include/Connect.php");
$mailid = "0";
$Co_id = "0";
if (isset($_GET['Co_id'])) {
    $Co_id = $_GET['Co_id'];
    $sqlsele = "SELECT Mailfrom_id FROM customer WHERE Co_id='$Co_id'";
    $result = mysqli_query($conn, $sqlsele) or die("Error : " . mysqli_error($conn));
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $mailid = $data['Mailfrom_id'];
}
?>

<head>
    <!-- <base href="http://localhost/Paymentreminder/"> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" type="image/png" href="images/icons/favicon.ico" /> -->
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <link href="helper/summernote0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <script src="helper/summernote0.8.18/summernote-bs4.min.js"></script>
    <script src="helper/summernote0.8.18/lang/summernote-th-TH.js"></script>
    <title>AdminControl</title>
    <script>
        $(document).ready(function() {
            $('#submit').click(function() {
                var detail = $('#summernote').summernote('code');
                var Co_id = <?php echo $Co_id; ?>;
                var Name_email = "0";
                try {
                    Name_email = $('#Name_email').val();
                } catch (err) {

                }
                console.log(Co_id);
                console.log(Name_email);
                $.ajax({
                    url: "Process/alertmailedit.php",
                    type: "POST",
                    data: {
                        detail,
                        Co_id,
                        Name_email
                    },
                    success: function(require) {
                        Notifytoprigth(require, "success");
                        console.log(require);
                    }
                });
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
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                                <li class="breadcrumb-item active">แก้ไขแบบอีเมลย์</li>
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
                                    <h1 class="text-dark">แก้ไขแบบอีเมลย์</h1>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <br>
            <div class="content">
                <div class="row">
                    <div class="col-md-3">
                        <div class="accordion" id="accordionExample">
                            <div class="card" style="text-decoration: none;">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <i class="fas fa-mail-bulk"></i>  Emailboxs
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <ul class="list-unstyled nav nav-pills flex-column">
                                            <li class="nav-item">
                                                <a href="Alertmailbox.php" class="nav-link">
                                                    ที่กำลังจะส่ง
                                                    <!-- <span class="badge bg-warning float-right">65</span> -->
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="Alertallmailbox.php" class="nav-link">ทั้งหมด</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="Alertmailedit.php" class="nav-link">แก้ไขแบบอีเมล์เริ่มต้น</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-warning mr-2"></i>การจัดการรูปแบบเมลย์เริ่มต้น</h6>
                                <div>
                                    <li class="list-group-item">
                                        <button type="button" id="submit" class="btn btn-success">ยืนยันการแก้ไข</button>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-7 my-3">
                                        <?php
                                        $sql = "SELECT * FROM email WHERE Mailfrom_id='$mailid'";
                                        $quray = mysqli_query($conn, $sql);
                                        $resut = mysqli_fetch_array($quray, MYSQLI_ASSOC);
                                        $detailmail = $resut['Mail_detail'];
                                        if (isset($_GET['Co_id'])) { ?>
                                            <input type="text" placeholder="ใส่ชื่อของรูปแบบอีเมย์" id="Name_email" style="width:100%;" value="<?php echo $resut['Name_email']; ?>">
                                        <?php  } else { ?><h3>แก้ไขแบบอีเมล์เริ่มต้น</h3><?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div id="summernote" class="card-body"><?php echo $resut['Mail_detail']; ?></div>
                            <script>
                                var year = function(context) {
                                    var ui = $.summernote.ui;

                                    // create button
                                    var button = ui.button({
                                        contents: 'ปี',
                                        tooltip: 'ปีของรอบจ่ายเงิน',
                                        click: function() {
                                            // invoke insertText method with 'hello' on editor module.
                                            context.invoke('editor.insertText', '<div>ปีนี้</div>');
                                        }
                                    });

                                    return button.render(); // return button as jquery object
                                }
                                var Co_name = function(context) {
                                    var ui = $.summernote.ui;

                                    // create button
                                    var button = ui.button({
                                        contents: 'ชื่อ',
                                        tooltip: 'ชื่อของบริษัท',
                                        click: function() {
                                            // invoke insertText method with 'hello' on editor module.
                                            context.invoke('editor.insertText', '<div>ชื่อของบริษัท</div>');
                                        }
                                    });

                                    return button.render(); // return button as jquery object
                                }
                                var Domain = function(context) {
                                    var ui = $.summernote.ui;

                                    // create button
                                    var button = ui.button({
                                        contents: 'โดเมน',
                                        tooltip: 'ชื่อโดเมนของเว็ป',
                                        click: function() {
                                            // invoke insertText method with 'hello' on editor module.
                                            context.invoke('editor.insertText', '<div>โดเมนเนม</div>');
                                        }
                                    });

                                    return button.render(); // return button as jquery object
                                }
                                var Duedate = function(context) {
                                    var ui = $.summernote.ui;

                                    // create button
                                    var button = ui.button({
                                        contents: 'รอบชำระ',
                                        tooltip: 'รอบการชำระเงินประจำปี',
                                        click: function() {
                                            // invoke insertText method with 'hello' on editor module.
                                            context.invoke('editor.insertText', '<div>รอบการชำระเงิน</div>');
                                        }
                                    });

                                    return button.render(); // return button as jquery object
                                }
                                var Rates = function(context) {
                                    var ui = $.summernote.ui;

                                    // create button
                                    var button = ui.button({
                                        contents: 'ราคา',
                                        tooltip: 'จำนวณเงินที่บริษัทต้องชำระ',
                                        click: function() {
                                            // invoke insertText method with 'hello' on editor module.
                                            context.invoke('editor.insertText', '<div>ราคา</div>');
                                        }
                                    });

                                    return button.render(); // return button as jquery object
                                }
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
                                    ],
                                    buttons: {
                                        Co_name: Co_name,
                                        Duedate: Duedate,
                                        Domain: Domain,
                                        Rates: Rates,
                                        year: year
                                    }
                                });
                            </script>
                        </div>
                        <br><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</body>

</html>