    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="images/logo.jpg" alt="THAIWEBEASY" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><b>thaiwebeasy.com</b></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="images/avatar.png" class="img-circle elevation-2" alt="User">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?php
                    include_once("include/Connect.php");
                    $userid = $_SESSION['USERID'];
                    $sqluser = "SELECT name FROM user WHERE userid='$userid'";
                    $quaryuser = mysqli_query($conn, $sqluser) or die("Error : " . mysqli_error($conn));
                    $alldata = mysqli_fetch_array($quaryuser);
                    echo $alldata['name'];
                    ?>
                </a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="admin.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            หน้าหลัก
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            ลูกค้า
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="Customerlist.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>รายชื่อลูกค้า</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Choicecustomer.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>เพิ่มรายชื่อ</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            แจ้งเตือน
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="Alertpaymentcycle.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ใกล้ถึงรอบชำระ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Alertmailbox.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Email</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Alertcalendar.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ปฏิทินรอบ</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tree"></i>
                        <p>
                            ชำระเงิน <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="Paymentlist.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>รายการที่ต้องชำระ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Paymentadd.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>เพิ่มรายการชำระเงิน</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            ออกรายงาน
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    <script src="helper/js/adminlte.js"></script>