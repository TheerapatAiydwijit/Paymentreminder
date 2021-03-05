$(document).ready(function () {
    $('#selectall').click(function () {
        var ch = document.getElementsByClassName("mail-checkbox");
        for (var i = 0; i < ch.length; i++)
            ch[i].checked = "true";
    });
    $('#notselectall').click(function () {
        var ch = document.getElementsByClassName("mail-checkbox");
        for (var i = 0; i < ch.length; i++)
            ch[i].checked = "";
    });
});

function inform(status, text) {
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
    if (status == "0") {
        Toast.fire({
            icon: 'info',
            title: text
        })
    } else if (status == "1") {
        Toast.fire({
            icon: 'success',
            title: text
        })
    } else if (status == "2") {
        Toast.fire({
            icon: 'error',
            title: text
        })
    }

}
$(document).ready(function () {
    $('#closesele').click(function () {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'แน่ใจ?',
            text: "การปิดการส่งจะเทียบเท่ากับการที่ระบบบันทึกว่าส่งอีเมย์สำเหร็ยจแล้ว",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยันการปิดการส่ง',
            cancelButtonText: 'ไม่ปิดการส่ง',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                var Co_id = [];
                var key = "1";
                for (var m = 0; ; m++) {
                    try {
                        var Evenc = document.getElementsByClassName('mail-checkbox')[m];
                        if (Evenc.checked) {
                            Co_id.push(Evenc.value);
                        }
                    } catch (e) {
                        // exit the loop
                        break;
                    }
                }
                // console.log(Co_id);
                var jsonString = JSON.stringify(Co_id);
                $.ajax({
                    url: "Process/compel.php",
                    type: "post",
                    data: {
                        jsonString,
                        key
                    },
                    dataType: "json",
                    success: function (require) {
                        var arrat = [];
                        for (var i = 0; i < require.length; i++) {
                            console.log(require);
                            if (require[i].status == "2") {
                                arrat.push(require[i].Co_id);
                            }
                        }
                        if (arrat.length > 0) {
                            var text = "ไม่สามารถปิดการส่งอีเมยล์สำหรับบางรายการได้";
                            inform(2, text);
                        } else {
                            var text = "ปิดการส่งอีเมลย์สำหรับบริษัทที่เลือกสำเหร็ยจ";
                            inform(1, text);
                        }
                    }
                });
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                var text = "ยกเลิกการบังคับส่ง"
                inform(0, text);
            }
        })

    });
    $('#sendselect').click(function () {
        $('#model').modal('show');
        $("#loding").css("display", "block");
        // var event = $(document).click(function (e) {
        //     e.stopPropagation();
        //     e.preventDefault();
        //     e.stopImmediatePropagation();
        //     return false;
        // });

        // // disable right click
        // $(document).bind('contextmenu', function (e) {
        //     e.stopPropagation();
        //     e.preventDefault();
        //     e.stopImmediatePropagation();
        //     return false;
        // });
        var Co_id = [];
        var key = "2";
        for (var m = 0; ; m++) {
            try {
                var Evenc = document.getElementsByClassName('mail-checkbox')[m];
                if (Evenc.checked) {
                    Co_id.push(Evenc.value);
                }
            } catch (e) {
                // exit the loop
                break;
            }
        }
        var jsonString = JSON.stringify(Co_id);
        $.ajax({
            url: "Process/compel.php",
            type: "post",
            data: {
                jsonString,
                key
            },
            dataType: "json",
            success: function (require) {
                console.log(require);
                $('#model').modal('toggle');
                $("#loding").css("display", "none");
            },
            error: function (error) {
                console.log(error);
                $('#model').modal('toggle');
                $("#loding").css("display", "none");
            }
        });

    });
});
