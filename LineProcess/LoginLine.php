<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rich Menu Demo</title>
  <style>
    body {
      margin: 0;
      padding: 20px
    }

    input {
      width: 90%;
      height: 32px;
      display: block;
      margin: 0 auto
    }

    input[type=text],
    input[type=password] {
      padding: 16px;
      font-size: 24px
    }

    input[type=button] {
      width: 100%;
      height: 48px
    }
  </style>
</head>

<body>
  <table width="100%" cellspacing="8">
    <tr>
      <td><input type="text" name="username" placeholder="Username" id="usernamet" required></td>
    </tr>
    <tr>
      <td><input type="password" placeholder="Password" id="password" required></td>
    </tr>
    <tr>
      <td><input type="button" value="Submit" id="post"></td>
    </tr>
    <tr>
      <td>
        <p id="alerterror" style="color:#E74C3C;">
      </td>
    </tr>
  </table>
  <script src="../helper/jquery/jquery-3.5.1.min.js"> </script>
  <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
  <script>
    window.onload = function(e) {
      liff.init(function(data) {
        initializeApp(data);
      });
    }

    function initializeApp(data) {
      jQuery("#post").click(function() {
          liff.closeWindow();
        jQuery.post(
          "https://reminderthaiweb.000webhostapp.com/LineProcess/Lineaddrichmenu.php", {
            uid: data.context.userId,
            username: $('#usernamet').val(),
            password: $('#password').val()
          },
          function(responseText) {
            if (responseText.status == "success") {

            } else {
             
            }
          },
          "json"
        );
      });
    }
  </script>
</body>

</html>