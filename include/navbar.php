<?php
include("Connect.php");
include("Frequently.php");
date_default_timezone_set("Asia/Bangkok");
$date = date("Y-m-d");
$select = "SELECT * FROM notification WHERE status=2 AND Level!='1' ORDER BY Not_date DESC";
$result = $conn->query($select);
$numrow = $result->num_rows;
?>
<script>
    $(document).ready(function() {
        $('.clik').click(function() {
            var detil = $(this).html();
            var level = $(this).attr("Level");
            var Not_id = $(this).attr("id");
            // console.log(level);
            $.ajax({
                url: "Process/navbar.php",
                data: {
                    Not_id
                },
                type: "POST",
                success: function(require) {
                    var setbgc = "#noti" + Not_id;
                    $(setbgc).css("background-color", "#F0F0F0");
                    if (level == "2") {
                        Swal.fire({
                            icon: 'question',
                            title: 'ข้อผิดพลาดจากการแจ้งเตือน',
                            text: detil
                        })
                    } else {
                        if (level == "3") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'ข้อผิดพลาดจากการส่ง Email',
                                text: detil
                            })
                        }
                    }
                }
            });

        });
    });
</script>
<!-- Left navbar links -->
<ul class="navbar-nav">
  <li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
  </li>
</ul>
<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
  <li class="nav-item">
    <a class="nav-link" href="#">
      <h4>
      <span class="badge badge-secondary badge-info">
        <?php
        echo DateThai($date);
        ?>
        </span>
      </h4>
    </a>
  </li>
  <!-- Notifications Dropdown Menu -->
  <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <h2><i class="far fa-bell"></i>
      <span class="badge badge-warning navbar-badge"><?php echo $numrow; ?></span></h2>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <span class="dropdown-item dropdown-header"><?php echo $numrow; ?> Notifications</span>
      <?php
      if ($numrow > 0) {
        while ($row = $result->fetch_assoc()) { ?>
          <div class="dropdown-divider" id="noti<?php echo $row['Not_id']; ?>"></div>
          <a href="#" class="dropdown-item clik" id="<?php echo $row['Not_id']; ?>" Level="<?php echo $row['Level']; ?>">
            <?php echo $row['Detail']; ?>
            <span class="float-right text-muted text-sm">
              <?php
              echo difference($row['Not_date']);
              ?></span>
          </a>
      <?php  }
      } ?>
      <!-- <div class="dropdown-divider"></div>
      <a href="#" class="dropdown-item">
        <i class="fas fa-envelope mr-2"></i> 4 new messages
        <span class="float-right text-muted text-sm">3 mins</span>
      </a> -->
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
      <i class="fas fa-expand-arrows-alt"></i>
    </a>
  </li>
  <li class="nav-item">
    <a href="Process/logout.php" class="nav-link" role="button">
      <i class="fas fa-sign-out-alt"></i>
    </a>
  </li>
</ul>
<script>
  $(document).ready(function() {
    var Notification = <?php echo $numrow; ?>;
    if (Notification > 0) {
      var title = Notification + " การแจ้งเตือนที่ยังไม่ได้อ่าน";
      var icon = 'info';
      Notifytoprigth(title, icon);
    }
  });

  function Notifytoprigth(mass, icon) {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 1500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })
    Toast.fire({
      icon: icon,
      title: mass
    })
  }
</script>