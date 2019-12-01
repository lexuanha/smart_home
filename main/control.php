<!DOCTYPE html>
<html>
  <head>
    <title> VTA </title>
    <link rel="Shortcut Icon" href="image/WSN.ico">
    <meta http-equiv="content-type" content="tex/html">
    <meta charset="UTF-8">
    <meta rel="stylesheet" href="font/Roboto.woff2">
    <meta rel="stylesheet" href="font/Lato.woff2">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"> </script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <script>
      function addZero(i) {
          if (i < 10) {
            i = "0" + i;
          }
          return i;
        }

        function converttime(needtime){
            datemaxtemp = new Date(needtime);
            //console.log(datemaxtemp);
            return addZero(datemaxtemp.getHours()) +":"+ addZero(datemaxtemp.getMinutes()) + ":" + addZero(datemaxtemp.getSeconds()) + " - " + addZero(datemaxtemp.getDate())+'/' + addZero((datemaxtemp.getMonth()+1)) + '/'+ addZero(datemaxtemp.getFullYear());
        }
      function printData(fl){
        var st = 0;
        var sh = 0;
        var sd = 0;
        var sl1 = 0;
        setInterval(function(){      
          var date = new Date();
          var y = addZero(date.getFullYear());
          var m = addZero(date.getMonth() + 1);
          var day = addZero(date.getDate());
          var hour = addZero(date.getHours());
          var mi = addZero(date.getMinutes());
          var s = addZero(date.getSeconds());
          document.getElementById('date1').innerHTML = day + "/" + m + "/" + y;
          document.getElementById('time1').innerHTML = hour + ":" + mi + ":" + s;
        }, 1000);
      }
     
    </script>
    
  </head>
  <body style="position:relative; margin: 0px;">
    <!-Taskbar-->
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
                    "icon": "fas fa-building"
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
          createCookie("select", kq , "10");
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
    <div id="sidebar" style="position:fixed;width: 200px;height:100%; background-color: #1E282C; padding: 0px; margin: 70px 0px; color: #ECF0F5; float:left;">
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
                echo "<li id=\"$save_id\" data-jstree = '{\"icon\": \"" .$icon ."\"}'>$save_area";
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
              echo "<li id=\"$save_id\" data-jstree = '{\"icon\": \"" .$icon ."\"}'>$save_area";
              trytry($save_id,$conn);
              echo"</li>";
            }
            echo "</ul>";
          }
        ?>
        
      </div>
      <form action="#" method="post" style = "margin: 20px auto auto 20px">
        <input type="submit" class="square_btn" style = " margin-top: 20px " name="make" value="Select">
      </form>
    </div>                  
    <script>
      openTab(3);
    </script>
    
    <!-Page-content-->
    <div id="page-content" style="float:left;color: #18699F;margin: 70px 0px 0px 200px;">
      <!-Graph-page-->      
      <div id="tab3"> 
        <div id="general-title" class="page-title"> 
          <div> Control </div>  
        </div>

        <div  id="all" style="margin:20px 0px 0px 60px;border: 1px solid gray;float:left ;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
          <div id="alltext" class="sub-title"><span style="margin-left: 20px; margin-right: 150px; color:#FF8C00;"> All Device </span></div>
          <div id= id="allbutton" style="float:left;margin: 20px 150px 20px 150px;">
            
            <?php
            require "phpMQTT-master/phpMQTT.php";
            ?>
            <html>
            <body>

              <form action="control.php" method="POST" >
                <!-- <input class="square_btn" id="allon" type="submit" name="all_on" value="All On">
                <input class="square_btn" id="alloff" type="submit" name="all_off" value="All Off"><br> -->
                <a id="allon"  name="all_on" class="square_btn">All On</a>
                <a id="alloff"  name="all_off" class="square_btn" >All Off</a><br>
                <!-- Topic: <input type="text" name=topic><br>
                Message: <input type="text" name=message><br>
                <br><input class="square_btn" type="submit" name="submit" value="Submit"> -->
              </form>
            </body>
            </html>
             <?php   
                if (isset($_POST['submit'])) {
                    $mqtt = new phpMQTT("localhost", 1883, "PHP MQTT Publisher");
                    if ($mqtt->connect()) {
                      echo "Published TOPIC:" . " " . $_POST['topic'] . "<br>";
                      echo "Published MESSAGE:" . " " . $_POST['message'] . "<br>";
                      $mqtt->publish($_POST['topic'], $_POST['message'], 0);
                      $mqtt->close();
                    }
                }
            ?>
          </div>
          
        </div>
        
        <script >
          /* if the page has been fully loaded we add two click handlers to the button */
          $(document).ready(function () {
            /* Get the checkboxes values based on the class attached to each check box */
            $("#make").click(function() {
                getValueUsingClass();
            });
          });

          function getValueUsingClass(){
            /* declare an checkbox array */
            var chkArray = [];
            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $(".chk:checked").each(function() {
              chkArray.push($(this).val());
            });
            var output = {};
            for(i=0 ;i<chkArray.length ;i++){
               output[i] = chkArray[i];
            }
            var kq = JSON.stringify(output);
            //console.log(output);
            console.log(kq);
            createCookie("control", kq , "/");
            /* we join the array separated by the comma */
            var selected;
            selected = chkArray.join(',') ;
            
            /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
            if(selected.length > 0){
              // alert("You have selected " + selected); 
            }else{
              // alert("Please at least check one of the checkbox"); 
            }
          }
        </script>

        <div  id="light" style="margin:20px 0px 0px 60px;border: 1px solid gray;float:left ;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
          <div id="lighttext" class="sub-title"><span style="margin-left: 20px; margin-right: 200px; color:#FF8C00;"> Control  </span></div>
          <div id= id="lightbutton" style="float:left;margin: 20px 20px 20px 20px; width: 500px">
            <div id="checkboxlist"  style="margin-left:310px" >
              <?php
                if(isset($_COOKIE["select"])){
                  $obj = json_decode($_COOKIE["select"],true);
                  $id_area = $obj[0];
                  $res_id = $conn->query("SELECT * FROM location WHERE parentid = $id_area ");//Xem id nay co phai la node la khong.
                  if ($res_id->num_rows == 0){
                    echo " <script>
                          $('#allon').click(function() {";
                    $res_device = $conn->query("SELECT * FROM device WHERE areaid = $id_area");//Tat ca cac thiet bi co trong phong.
                    if ($res_device->num_rows > 0){
                      while($row = $res_device->fetch_assoc()){
                        $device_id = $row["id"];
                        echo "$('#".$device_id."').prop(\"checked\", true);";
                      }
                    }
                    echo "});
                        </script>";
                         echo " <script>
                          $('#alloff').click(function() {";
                    $res_device = $conn->query("SELECT * FROM device WHERE areaid = $id_area");//Tat ca cac thiet bi co trong phong.
                    if ($res_device->num_rows > 0){
                      while($row = $res_device->fetch_assoc()){
                        $device_id = $row["id"];
                        echo "$('#".$device_id."').prop(\"checked\", false);";
                      }
                    }
                    echo "});
                        </script>";
                    $res_device = $conn->query("SELECT * FROM device WHERE areaid = $id_area");//Tat ca cac thiet bi co trong phong.
                    if ($res_device->num_rows > 0){
                      while($row = $res_device->fetch_assoc()){

                        $device_id = $row["id"];
                        $device_name = $row["name"];
                        $device_code = $row["code"];
                        $device_status = $row["status"];
                        echo "<label class='form-switch'>
                                <input id='$device_id' type='checkbox' value='$device_id' class='chk' $device_status > $device_name
                                <i></i>
                              </label>";
                      }
                    }
                  }else echo "<script>alert(\"Please select specifically location! :(\");</script>";
                }
              ?>
              <div>
                <form action="control.php" method="POST" >
                   <br><input class="square_btn" type="submit" id ="make" name="turn" value="Submit"> 
                </form>
              </div>
            </div>

            <?php   
              if (isset($_POST['turn'])) {
                $obj_area = json_decode($_COOKIE["select"],true);
                $obj_device = json_decode($_COOKIE["control"],true);
                $id_area = $obj_area[0];
                
                $res_device = $conn->query("SELECT * FROM device WHERE areaid = $id_area");//Tat ca cac thiet bi co trong phong.
                if ($res_device->num_rows > 0){
                  while($row = $res_device->fetch_assoc()){
                    $id_device = $row["id"];
                    echo "<script>console.log(".$id_device.");</script>";

                    $kt=0;
                    for( $i = 0; $i < sizeof($obj_device) ; $i++){
                      if($obj_device[$i] == $id_device)$kt=1;
                    }
                    $mqtt = new phpMQTT("localhost", 1883, "PHP MQTT Publisher");
                    if ($mqtt->connect()) {
                      if($kt){
                        $conn->query("UPDATE device SET status = 'checked' WHERE id = $id_device ");
                        // echo "Published TOPIC: " . $id_device. "<br>";
                        // echo "Published MESSAGE: 0<br>";
                        $mqtt->publish($id_device,0, 0);
                        $mqtt->close();
                        echo '<script type="text/javascript">window.location.href ="control.php";</script>';
                      }else{
                        $conn->query("UPDATE device SET status = NULL WHERE id = $id_device ");
                        // echo "Published TOPIC: " . $id_device. "<br>";
                        // echo "Published MESSAGE: 1<br>";
                        $mqtt->publish($id_device,1, 0);
                        $mqtt->close();
                        echo '<script type="text/javascript">window.location.href ="control.php";</script>';
                      }
                    }
                  }
                }
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
