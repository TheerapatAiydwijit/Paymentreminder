<!DOCTYPE html>
<html lang="en">
<?php
include("include/loginChek.php");
include("include/calendarEvents.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--===============================================================================================-->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="helper/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="helper/evocalendar/css/evo-calendar.css">
    <link rel="stylesheet" href="helper/evocalendar/css/evo-calendar.midnight-blue.css">
    <link rel="stylesheet" href="helper/evocalendar/css/evo-calendar.orange-coral.css">
    <link rel="stylesheet" href="helper/evocalendar/css/evo-calendar.royal-navy.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!--===============================================================================================-->
    <script src="helper/jquery/jquery-3.5.1.min.js"></script>
    <script src="helper/evocalendar/js/evo-calendar.js"></script>
    <script src="helper/sweetalert/sweetalert2@10.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <title>Document</title>
    <script>
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
                                <li class="breadcrumb-item active">ปฏิทินลูกค้า</li>
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
                                    <h1 class="text-dark">ปฏิทินลูกค้า</h1>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <br>
            <div id="calendar"></div>
            <script>
                $(document).ready(function() {
                    $('#sidebar').toggleClass('active');
                    $('#calendar').evoCalendar({
                        language: 'es',
                        'todayHighlight': true,
                        'sidebarDisplayDefault': false,
                        'eventDisplayDefault': false,
                        'calendarEvents': <?php echo $jsonreturn; ?>
                    });
                    $('#calendar').on('selectDate', function(event, newDate, oldDate) {
                        var active_events = $('#calendar').evoCalendar('getActiveEvents').length;
                        if (active_events > 0) {
                            console.log("เปิด");
                            $('#calendar').evoCalendar('toggleEventList', true);
                            $('#calendar').evoCalendar('toggleSidebar', false);
                        } else {
                            console.log("ปิด");
                            $('#calendar').evoCalendar('toggleEventList', false);
                        }
                        // console.log(active_events);
                    });
                    $('#calendar').on('selectEvent', function(event, activeEvent) {
                        console.log(activeEvent);
                        var name = activeEvent['name'];
                        var id = activeEvent['id'];
                        var description = activeEvent['description'];
                        var date = activeEvent['date'];
                        Swal.fire({
                            icon: 'info',
                            title: name,
                            html: description + '<br>' + 'ต้องชำระทั้งหมดก่อนวันที่ ' + date,
                            footer: '<a href="Alertmailedit.php?Co_id=' + id + '">แก้ไขอีเมลก่อนทำการส่งมั้ย?</a>'
                        })
                    });
                });
            </script>
        </div>
    </div>
    </div>
</body>

</html>