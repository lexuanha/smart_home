<?php
    $servername = "localhost";
    $username = "root";
    $password = "110297@";
    $dbname = "iot";
    // tao connect
    $conn = new mysqli($servername, $username, $password, $dbname);
    // kiem tra connect
    if ($conn->connect_error) {
        die("Kết nối thất bại :( <br>" . $conn->connect_error);
    }
?>

<script>
function createCookie(name, value, days) {
    var expires;
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toGMTString();
    }
    else {
      expires = "";
    }
    document.cookie = name+ "=" + value + expires + "; path=/";
    //console.log(document.cookie);
}
createCookie("select",'', "10");
$(function () {
    $("#tree").jstree({
        "checkbox": {
            "keep_selected_style": false
        },
        "plugins": [ "types", "checkbox"],
        "types": {
            "default": {
                "icon": "fas fa-map-marker-alt"
            }
        }
    });
    $("#tree").bind("changed.jstree",
    function (e, data) {
      var output = {};
      for(i=0 ;i<data.selected.length ;i++){
         output[i] = data.selected[i];
      }
      var kq = JSON.stringify(output);
      //console.log(output);
      console.log(kq);
      createCookie("select", kq , "/");
    });
});
</script>

<div id ="taskbar" style = "position:fixed;top:0px;left:0px;height: 70px;width:100%; background-color: #1E282C;">
  <div style="position:relative;left:30px;width:300px;vertical-align:middle;color: #ECF0F5; float: left; margin: 10px;">
    <span style="font-size:40px;font-weight:bold"> IoT SVMC </span>     
  </div>
  <div>
    <ul class="sidebar-nav" style = "float:left">
      <li id="li-1" class = "active">
          <a href="index.php"><span class="fas fa-home"></span>&nbsp; Home</a>
      </li>
      <li id="li-2">
          <a href="general.php"><span class="fas fa-info-circle"></span>&nbsp; Detail</a>
      </li>
      <li id="li-3">
          <a href="control.php"><span class="fas fa-toggle-on"></span>&nbsp; Control</a>
      </li>
      <li id="li-4">
          <a href="chart.php"><span class="fas fa-chart-line"></span>&nbsp; Graphic</a>
      </li>
      <li id="li-5">
          <a href="manage.php"><span class="fas fa-user-alt"></span>&nbsp; Manage</a>
      </li>
      <li id="li-6">
          <a onclick="document.getElementById('signin').style.display='block'" style="width:auto;"><span class="fas fa-sign-in-alt"></span>&nbsp; Sign In</a>
      </li>
      <li id="li-7">
          <a onclick="document.getElementById('signup').style.display='block'" style="width:auto;"><span class="fas fa-user-plus"></span>&nbsp; Sign Up</a>
      </li>
    </ul>
  </div>
</div>
<script>
  function openTab(id) {
    $('.sidebar-nav li').removeClass('active');
    $('#li-' + id).addClass('active');
  }
</script>
<div id="sidebar" style="position:fixed;width: 200px;height:1000px; background-color: #1E282C; padding: 0px; margin: 70px 0px; color: #ECF0F5; float:left;">
  <div id="menu_text" style="height: 60px; text-align: center; vertical-align: middle; line-height: 60px; border-bottom: 1px solid #ECF0F5">
    <p style="font-size: 30px;margin: 0;px border-bottom: 1px solid #ECF0F5; border-top: 1px solid #ECF0F5"><strong> MENU </strong></p>
  </div>
  <div id="tree" style="width: auto; background-color: #ECF0F5 ;color: black; padding: 0px; margin: 5px 0px 5px 5px">
    <?php
      function trytry($id_parent,$conn){
        $res_id_t = $conn->query("SELECT * FROM location WHERE parentid = $id_parent ");
        if ($res_id_t->num_rows > 0){
          echo "<ul>";
          while($row = $res_id_t->fetch_assoc()){
            $save_id = $row["id"];
            $save_area = $row["area"];
            $icon = $row["ext"];
            echo "<li id=\"$save_id\" data-jstree = '{\"icon\": \"" .$icon ."\"}' >$save_area";
            trytry($save_id,$conn);
            echo "</li>";
          }
          echo "</ul>";
        }
      }
      $res_id = $conn->query("SELECT * FROM location where parentid IS NULL ");
      if ($res_id->num_rows > 0){ 
        echo "<ul>";
        while($row = $res_id->fetch_assoc()){
          $save_id = $row["id"];
          $save_area = $row["area"];
          $icon = $row["ext"];
          echo "<li id=\"$save_id\" data-jstree = '{\"icon\": \"" .$icon ."\"}' >$save_area";
          trytry($save_id,$conn);
          echo"</li>";
        }
        echo "</ul>";
      }
    ?>
  </div>
  <form action="#" method="post">
    <input type="submit" style = " margin-left: 20px " name="make" value="Done"></form>
  </form>
</div>

<body>
  <div id="signin" class="modal">
    <span onclick="document.getElementById('signin').style.display='none'" class="close" title="Close Modal">&times;</span>
    <form class="modal-content" action="signin.php" method = "post" role = "form">
      <div class="container">
        <center>
          <h1>Sign In</h1>
          <p>Please fill in this form to sign in.</p>
        </center>
        <hr>
        <label for="email"><b>Email Or Username</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" required>
        <label>
          <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
        </label>
        <div class="clearfix">
          <button type="button" onclick="document.getElementById('signin').style.display='none'" class="cancelbtn">Cancel</button>
          <button type="submit" class="makebtn">Sign In</button>
        </div>
      </div>
    </form>
  </div>
  <script>
    // Get the modal
    var modal = document.getElementById('signin');
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>

<body>
  <div id="signup" class="modal">
    <span onclick="document.getElementById('signup').style.display='none'" class="close" title="Close Modal">&times;</span>
    <form class="modal-content" action="signup.php" method = "post" role = "form">
      <div class="container">
        <center>
          <h1>Sign Up</h1>
          <p>Please fill in this form to create an account.</p>
        </center>
        
        <hr>
        <label for="email"><b>Email</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" required>

        <label for="psw-repeat"><b>Repeat Password</b></label>
        <input type="password" placeholder="Repeat Password" name="psw-repeat" required>
        
        <label>
          <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
        </label>

        <p>By creating an account you agree to our <a href="privacy.php" style="color:dodgerblue">Terms & Privacy</a>.</p>

        <div class="clearfix">
          <button type="button" onclick="document.getElementById('signup').style.display='none'" class="cancelbtn">Cancel</button>
          <button type="submit" class="makebtn">Sign Up</button>
        </div>
      </div>
    </form>
  </div>
  <script>
    // Get the modal
    var modal = document.getElementById('signup');
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>