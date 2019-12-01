<!DOCTYPE html>
<html>
  <head>
    <title> IOT_SVMC </title>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>
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
      createCookie("select_manage",'', "10");
      $(function () {
          $("#tree").jstree({
              "checkbox": {
                  "keep_selected_style": false,
                  "three_state":false,
                  "real_checkboxes":false
              },
              "plugins": ["types", "checkbox"],
              "core": {
                  "multiple": false
              },
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
            console.log(output);
            console.log(kq);
            createCookie("select_manage", kq , "10");
          });
      });
    </script>
  </head>
  <body  style="position:relative; margin: 0px;">
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
    <div id="sidebar" style="position:fixed;width: 200px;height:1000px; background-color: #1E282C; padding: 0px; margin: 70px 0px; color: #ECF0F5; float:left;">
      <div id="menu_text" style="height: 60px; text-align: center; vertical-align: middle; line-height: 60px; border-bottom: 1px solid #ECF0F5">
        <p style="font-size: 30px;margin: 0;px border-bottom: 1px solid #ECF0F5; border-top: 1px solid #ECF0F5"><strong> MENU </strong></p>
      </div>
    </div>
    <script>
      function openTab(id) {
        $('.sidebar-nav li').removeClass('active');
        $('#li-' + id).addClass('active');
      }
      openTab(5);
    </script>
    <div id="page-content" style="float:left;height:1000px;color: #18699F;margin: 70px 0px 0px 200px;">
      <div id="general-page" class="page">
        <h1>MANAGE</h1>
          <div id="tree" style="width: auto; background-color: #ECF0F5 ;color: blue; padding: 0px; margin: 10px 10px 10px 10px">
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
        </div>
      <div>
        <button onclick="document.getElementById('addlocation').style.display='block'" style="width:auto;"><span class="far fa-plus-square"></span>&nbsp; Add Location</button>
        <button onclick="document.getElementById('addsensor').style.display='block'" style="width:auto;"><span class="far fa-plus-square"></span>&nbsp; Add Sensor</button>
        <button onclick="document.getElementById('adddevice').style.display='block'" style="width:auto;"><span class="far fa-plus-square"></span>&nbsp; Add Device</button>
        <button onclick="document.getElementById('rename').style.display='block'" style="width:auto;"><span class="fas fa-edit"></span>&nbsp; Rename</button>
        <button onclick="document.getElementById('relocation').style.display='block'" style="width:auto;"><span class="fas fa-people-carry"></span>&nbsp; Relocation</button>
        <button onclick="document.getElementById('delete').style.display='block'" style="width:auto;"><span class="fas fa-trash-alt"></span>&nbsp; Delete</button>
        
      </div>
    </div>

    <body>
      <div id="relocation" class="modal" >
        <script>
          createCookie("select_manage_t",'', "10");
          $(function () {
              $("#tree_t").jstree({
                  "checkbox": {
                      "keep_selected_style": false,
                      "three_state":false,
                      "real_checkboxes":false
                  },
                  "plugins": ["types", "checkbox"],
                  "core": {
                      "multiple": false
                  },
                  "types": {
                      "default": {
                          "icon": "fas fa-map-marker-alt"
                      }
                  }
              });
              $("#tree_t").bind("changed.jstree",
              function (e, data) {
                var output = {};
                for(i=0 ;i<data.selected.length ;i++){
                   output[i] = data.selected[i];
                }
                var kq = JSON.stringify(output);
                console.log(output);
                console.log(kq);
                createCookie("select_manage_t", kq , "10");
              });
          });

        </script>
        <span onclick="document.getElementById('relocation').style.display='none'" class="close" title="Close Modal">&times;</span>
        <form class="modal-content" action="manage.php" method = "post" role = "form">
          <div class="container">
            <center>
              <h1>Relocation</h1>
              <p>Please select the location you want move to.</p>
            </center>
            <div id="tree_t" style="width: auto; background-color: #ECF0F5 ;color: blue; padding: 0px; margin: 10px 10px 10px 10px">
              <?php
                function trytry_t($id_parent,$conn){
                  $res_id_t = $conn->query("SELECT * FROM location WHERE parentid = $id_parent ");
                  if ($res_id_t->num_rows > 0){
                    echo "<ul>";
                    while($row = $res_id_t->fetch_assoc()){
                      $save_id = $row["id"];
                      $save_area = $row["area"];
                      $icon = $row["ext"];
                      $res_id = $conn->query("SELECT * FROM location WHERE parentid = $save_id ");
                      if ($res_id->num_rows > 0){
                        echo "<li id=\"$save_id\" data-jstree = '{\"icon\": \"" .$icon ."\"}' >$save_area";
                        trytry_t($save_id,$conn);
                        echo "</li>";
                      }
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
                    trytry_t($save_id,$conn);
                    echo"</li>";
                  }
                  echo "</ul>";
                }
              ?>
            </div>
            <hr>
            <div class="clearfix">
              <button type="button" onclick="document.getElementById('relocation').style.display='none'" class="cancelbtn" style="float:left;">Cancel</button>
              <button type="submit" class="makebtn" name="do_relocation">Update</button>
            </div>
          </div>
        </form>
      </div>
    </body>
    <?php
      if(isset($_POST["do_relocation"])){
        $old = json_decode($_COOKIE['select_manage'],true);
        $new = json_decode($_COOKIE['select_manage_t'],true);

        if($old==NULL OR $new==NULL){
          echo "<script type=\"text/javascript\">alert(\"You must select enough location !\");</script>";
        }
        else{
          $location_old = $old[0];
          $location_new = $new[0];

          $res_name = $conn->query("SELECT * FROM location WHERE area = ( SELECT area FROM location WHERE id = $location_old ) AND parentid <> ( SELECT parentid FROM location WHERE id = $location_old ) AND parentid = $location_new");//Xem ten nay da ton tai trong thu muc chua.
          if ($res_name->num_rows == 0){
             $conn->query("UPDATE location SET parentid = $location_new WHERE id = $location_old ");//Di chuyen old vao new.
            echo "<script type=\"text/javascript\">alert(\"Change location successfully !\");</script>";
            echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
          }
          else echo "<script type=\"text/javascript\">alert(\"Location name already exists ! Please rename before relocation !\");</script>";
        }
      }
    ?>

    <body>
      <div id="delete" class="modal">
        <span onclick="document.getElementById('delete').style.display='none'" class="close" title="Close Modal">&times;</span>
        <form class="modal-content" action="manage.php" method = "post" role = "form">
          <div class="container">
            <center>
              <h1>Delete</h1>
              <p>Are you sure you want to delete this location?.</p>
            </center>
            
            <div class="clearfix">
              <button type="button" onclick="document.getElementById('delete').style.display='none'" class="cancelbtn">Cancel</button>
              <button type="submit" class="makebtn" name="do_delete">YES</button>
            </div>
          </div>
        </form>
      </div>
    </body>
     <?php
      if(isset($_POST["do_delete"])){
        $obj = json_decode($_COOKIE['select_manage'],true);
        function del($id_parent,$conn){
          $res_id_son = $conn->query("SELECT id FROM location WHERE parentid = $id_parent ");
          $conn->query("DELETE FROM location WHERE id = $id_parent ");//Xoa trong bang location.
          $conn->query("DELETE FROM device WHERE areaid = $id_parent ");
          $res_id_router = $conn->query("SELECT id FROM router WHERE areaid = $id_parent ");
          if ($res_id_router->num_rows > 0){
            while($row = $res_id_router->fetch_assoc()){
              $id_sensor = $row["id"];
              $conn->query("DELETE FROM router WHERE id = $id_sensor ");//Xoa trong bang router.
              $conn->query("DELETE FROM sensordata WHERE sensor = $id_sensor ");//Xoa trong bang sensordata.
              $conn->query("DELETE FROM device WHERE areaid = $id_sensor ");
            }
          }
          if ($res_id_son->num_rows > 0){
            while($row = $res_id_son->fetch_assoc()){
              $save_id = $row["id"];
              del($save_id,$conn);
            }
          }
        }
        if($obj==NULL){
          echo "<script type=\"text/javascript\">alert(\"You must select a location :((\");</script>";
        }
        else{
          $id_area = $obj[0];
          del($id_area,$conn);
          echo "<script type=\"text/javascript\">alert(\"Delete complete !\");</script>";
          echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
        }
      }
    ?>

    <body>
      <div id="rename" class="modal" >
        <span onclick="document.getElementById('rename').style.display='none'" class="close" title="Close Modal">&times;</span>
        <form class="modal-content" action="manage.php" method = "post" role = "form">
          <div class="container">
            <center>
              <h1>Rename</h1>
              <p>Please fill in this form to rename.</p>
            </center>
            <hr>
            <label for="rename"><b>New name</b></label>
            <input type="text" placeholder="Enter New Name" name="newname" required>
            <div class="clearfix">
              <button type="button" onclick="document.getElementById('rename').style.display='none'" class="cancelbtn" style="float:left;">Cancel</button>
              <button type="submit" class="makebtn" name="do_rename">Update</button>
            </div>
          </div>
        </form>
      </div>
    </body>
    <?php
      if(isset($_POST["do_rename"])){
        $obj = json_decode($_COOKIE['select_manage'],true);
        if($obj==NULL){
          echo "<script type=\"text/javascript\">alert(\"You must select a location :((\");</script>";
        }
        else{
          $id_area = $obj[0];
          $newname = (string)$_POST["newname"];
          // echo "<script type=\"text/javascript\">alert(\"".$kt ." " .$id_area ."  " .$newname ."\");</script>";
          $res_id_parent = $conn->query("SELECT parentid FROM location WHERE id = $id_area ");//Lay parent cua node.
          if ($res_id_parent->num_rows > 0){
            while($row = $res_id_parent->fetch_assoc()){
              $id_parent = $row["parentid"];
              // echo "<script type=\"text/javascript\">alert(\"".$id_parent."\");</script>";
              $res_name = $conn->query("SELECT * FROM location WHERE area = '$newname' AND id <> $id_area AND parentid = $id_parent");//Xem ten nay da ton tai trong thu muc chua.
              if ($res_name->num_rows == 0){
                $conn->query("UPDATE location SET area = '$newname' WHERE id = $id_area ");//Doi ten cho area.
                echo "<script type=\"text/javascript\">alert(\"Done :)\");</script>";
                echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
              }
              else echo "<script type=\"text/javascript\">alert(\"Location name already exists ! Please try again !\");</script>";
            }
          }
        }
      }
    ?>

    <body>
      <div id="adddevice" class="modal">
        <span onclick="document.getElementById('adddevice').style.display='none'" class="close" title="Close Modal">&times;</span>
        <form class="modal-content" action="manage.php" method = "post" role = "form">
          <div class="container">
            <center>
              <h1>Add device</h1>
              <p>Please fill in this form to add device.</p>
            </center>
            <hr>
            <label for="namedevice"><b>Device's name </b></label>
            <input type="text" placeholder="Enter device's name" name="device_name" required>

            <label for="codedevice"><b>Control code </b></label>
            <input type="text" placeholder="Enter device's code" name="device_code" required>
            <div class="clearfix">
              <button type="button" onclick="document.getElementById('adddevice').style.display='none'" class="cancelbtn">Cancel</button>
              <button type="submit"  class="makebtn" name="do_adddevice" >ADD</button>
            </div>
          </div>
        </form>
      </div>
    </body>
     <?php
      if(isset($_POST["do_adddevice"])){
        $obj = json_decode($_COOKIE['select_manage'],true);
        $name_device = (string)$_POST["device_name"];
        $code_device = (string)$_POST["device_code"];
        
        if($obj==NULL){
          echo "<script type=\"text/javascript\">alert('You need choose a location !');</script>";
        }
        else{
          $id_area = $obj[0];
          $res_leaf = $conn->query("SELECT * FROM location WHERE ext = 'fas fa-building' AND id = $id_area ");//Chi duoc phep chon location ko duoc chon device.
          if ($res_leaf->num_rows == 0){
            $res_name = $conn->query("SELECT * FROM device WHERE name = '$name_device' AND  areaid = $id_area ");//Xem ten nay da ton tai trong thu muc chua.
            if ($res_name->num_rows == 0){
              $conn->query("INSERT INTO device (areaid,name,code) VALUES (\"" .$id_area ."\",\"" .$name_device ."\",\"" .$code_device ."\")");
              echo "<script type=\"text/javascript\">alert('Create new device successfully !');</script>";
              echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
            }
            else echo "<script type=\"text/javascript\">alert('Device name already exists ! Please try again !');</script>";
          }
          else echo "<script type=\"text/javascript\">alert(\"You can't add into a location ! Please choose a esp device!\");</script>";
        }
      }
    ?>

    <body>
      <div id="addsensor" class="modal">
        <span onclick="document.getElementById('addsensor').style.display='none'" class="close" title="Close Modal">&times;</span>
        <form class="modal-content" action="manage.php" method = "post" role = "form">
          <div class="container">
            <center>
              <h1>Add sensor</h1>
              <p>Please fill in this form to add sensor.</p>
            </center>
            <hr>
            <label for="macadd"><b>MAC Address</b></label>
            <input type="text" placeholder="Enter MAC Address" name="macadd" required>
            <label for="sensor"><b>Sensor's location </b></label>
            <input type="text" placeholder="Enter Sensor's location" name="sensor" required>
            <div class="clearfix">
              <button type="button" onclick="document.getElementById('addsensor').style.display='none'" class="cancelbtn">Cancel</button>
              <button type="submit"  class="makebtn" name="do_addsensor" >ADD</button>
            </div>
          </div>
        </form>
      </div>
    </body>
     <?php
      if(isset($_POST["do_addsensor"])){
        $obj = json_decode($_COOKIE['select_manage'],true);
        $macadd = (string)$_POST["macadd"];
        $loc_sensor = (string)$_POST["sensor"];
        
        $res_mac = $conn->query("SELECT * FROM location WHERE mac = '$macadd'");//Xem dia chi mac nay da ton tai chua.
        if ($res_mac->num_rows == 0){
          if($obj==NULL){
            $res_name = $conn->query("SELECT * FROM location WHERE area = '$loc_sensor' AND  parentid IS NULL");//Xem ten nay da ton tai trong thu muc chua.
            if ($res_name->num_rows == 0){
              $conn->query("INSERT INTO location (mac, area, ext) VALUES (\"" .$macadd ."\",\"" .$loc_sensor ."\",'fas fa-map-marker-alt')");
              echo "<script type=\"text/javascript\">alert('Create new sensor successfully !');</script>";
              echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
            }
            else echo "<script type=\"text/javascript\">alert('Sensor name already exists ! Please try again !');</script>";
          }
          else{
            $id_area = $obj[0];
            $res_leaf = $conn->query("SELECT * FROM location WHERE ext = 'fas fa-building' AND id = $id_area ");//Chi duoc phep chon location ko duoc chon sensor.
            if ($res_leaf->num_rows > 0){
              $res_name = $conn->query("SELECT * FROM location WHERE area = '$loc_sensor' AND  parentid = $id_area ");//Xem ten nay da ton tai trong thu muc chua.
              if ($res_name->num_rows == 0){
                $conn->query("INSERT INTO location (mac, area, parentid, ext) VALUES (\"" .$macadd ."\",\"" .$loc_sensor ."\",\"" .$id_area ."\",'fas fa-map-marker-alt')");
                echo "<script type=\"text/javascript\">alert('Create new sensor successfully !');</script>";
                echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
              }
              else echo "<script type=\"text/javascript\">alert('Sensor name already exists ! Please try again !');</script>";
            }
            else echo "<script type=\"text/javascript\">alert(\"You can't add into a sensor ! Please choose a location !\");</script>";
          }
        }
        else echo "<script type=\"text/javascript\">alert('MAC address already exists ! Please try again !');</script>";
      }
    ?>


    <body>
      <div id="addlocation" class="modal">
        <span onclick="document.getElementById('addlocation').style.display='none'" class="close" title="Close Modal">&times;</span>
        <form class="modal-content" action="manage.php" method = "post" role = "form">
          <div class="container">
            <center>
              <h1>Add location</h1>
              <p>Please fill in this form to add location.</p>
            </center>
            <hr>
            <label for="location"><b>Location's name </b></label>
            <input type="text" placeholder="Location's name" name="location" required>
            <label for="describe"><b>Describe </b></label>
            <input type="text" placeholder="Enter Describe" name="describe" required>
            <div class="clearfix">
              <button type="button" onclick="document.getElementById('addlocation').style.display='none'" class="cancelbtn">Cancel</button>
              <button type="submit" class="makebtn" name="do_addlocation" >ADD</button>
            </div>
          </div>
        </form>
      </div>
    </body>
    <?php
      if(isset($_POST["do_addlocation"])){
        $obj = json_decode($_COOKIE['select_manage'],true);
        if($obj==NULL){
          $loc_name = (string)$_POST["location"];
          $loc_des = (string)$_POST["describe"];
          $res_name = $conn->query("SELECT * FROM location WHERE area = '$loc_name' AND  parentid IS NULL");//Xem ten nay da ton tai trong thu muc chua.
          if ($res_name->num_rows == 0){
            $conn->query("INSERT INTO location (area, des, ext) VALUES (\"" .$loc_name ."\",\"" .$loc_des ."\",'fas fa-building')");
            echo "<script type=\"text/javascript\">alert('Create new location successfully !');</script>";
            echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
          }
          else echo "<script type=\"text/javascript\">alert('Location name already exists ! Please try again !');</script>";
        }
        else{
          $id_area = $obj[0];
          $res_leaf = $conn->query("SELECT id FROM location WHERE ext = 'fas fa-building' AND id = $id_area ");//Chi duoc phep chon location ko duoc chon sensor.
          if ($res_leaf->num_rows > 0){
            $loc_name = (string)$_POST["location"];
            $loc_des = (string)$_POST["describe"];
            $res_name = $conn->query("SELECT * FROM location WHERE area = '$loc_name' AND  parentid = $id_area ");//Xem ten nay da ton tai trong thu muc chua.
            if ($res_name->num_rows == 0){
              $conn->query("INSERT INTO location (area, des, parentid, ext) VALUES (\"" .$loc_name ."\",\"" .$loc_des ."\",\"" .$id_area ."\",'fas fa-building')");
              echo "<script type=\"text/javascript\">alert('Create new location successfully !');</script>";
              echo '<script type="text/javascript">window.location.href ="manage.php";</script>';
            }
            else echo "<script type=\"text/javascript\">alert('Location name already exists ! Please try again !');</script>";
          }
          else echo "<script type=\"text/javascript\">alert(\"You can't add into a sensor ! Please choose a location  !\");</script>";
        }
      }
    ?>

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

  </body>
</html>
