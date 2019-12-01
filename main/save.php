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
      include 'top_left.php';
    ?>
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
                <input class="square_btn" id="allon" type="submit" name="all_on" value="All On">
                <input class="square_btn" id="alloff" type="submit" name="all_off" value="All Off">
               <!--  <input type="checkbox" id="allon"  name="all_on" class="square_btn">All On
                <input type="checkbox" id="alloff"  name="all_off" class="square_btn" >All Off<br> -->
               <!--  Topic: <input type="text" name=topic><br>
                Message: <input type="text" name=message><br> -->
                <br><input class="square_btn" type="submit" name="submit" value="Submit">
              </form>

            </body>
            </html>
            <?php   
                if (isset($_POST['submit'])) {
                    $mqtt = new phpMQTT("localhost", 1883, "PHP MQTT Publisher");
                    if ($mqtt->connect()) {
                      if (isset($_POST['all_on'])){
                        echo "Published TOPIC: all<br>";
                        echo "Published MESSAGE: 0 <br>";
                        $mqtt->publish("all",0, 0);
                        $mqtt->close();
                      }
                      if (isset($_POST['all_off'])){
                        echo "Published TOPIC: all<br>";
                        echo "Published MESSAGE: 1 <br>";
                        $mqtt->publish("all",1, 0);
                        $mqtt->close();
                      }
                        // echo "Published TOPIC:" . " " . $_POST['topic'] . "<br>";
                        // echo "Published MESSAGE:" . " " . $_POST['message'] . "<br>";
                        // $mqtt->publish($_POST['topic'], $_POST['message'], 0);
                        // $mqtt->close();
                    }
                }
            ?>
          </div>
          <script>
            $('#allon').click(function() {
                console.log(1);
                $('#lv').prop("checked", true);
                $('#kc').prop("checked", true);
                $('#bedr').prop("checked", true);
                $('#bathr').prop("checked", true);
                $('#tv').prop("checked", true);
                $('#fr').prop("checked", true);
                $('#cf').prop("checked", true);
                $('#fan').prop("checked", true);
            });
          </script>
          <script>
            $('#alloff').click(function() {
                console.log(0);
                $('#lv').prop("checked", false);
                $('#kc').prop("checked", false);
                $('#bedr').prop("checked", false);
                $('#bathr').prop("checked", false);
                $('#tv').prop("checked", false);
                $('#fr').prop("checked", false);
                $('#cf').prop("checked", false);
                $('#fan').prop("checked", false);
            });
          </script>
        </div>

        <div  id="light" style="margin:20px 0px 0px 60px;border: 1px solid gray;float:left ;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
          <div id="lighttext" class="sub-title"><span style="margin-left: 20px; margin-right: 200px; color:#FF8C00;"> Light </span></div>
          <div id= id="lightbutton" style="float:left;margin: 20px 20px 20px 20px; width: 500px">
            <b>Led LivingRoom</b>
            <label class="form-switch" style="margin-left:310px">
              <input id="lv" name="lv" type="checkbox" >
              <i></i>
            </label>
          </div>
          <script>
            $('#lv').click(function() {
                var sttlv = $(this).prop("checked");
                //alert(sttlv);

            });
          </script>
          <div style="margin: 20px 20px 20px 20px;">
            <b>Led Kitchen</b>
            <label class="form-switch" style="margin-left:342px;">
              <input id="kc" type="checkbox">
              <i></i>
            </label>
          </div>
          <div style="margin: 20px 20px 20px 20px;">
            <b>Led BedRoom</b>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="form-switch" style="margin-left:307px">
              <input id = "bedr" type="checkbox">
              <i></i>
            </label>
          </div>
          <div style="margin: 20px 20px 20px 20px;">
            <b>Led BathRoom</b>
            <label class="form-switch" style="margin-left:318px">
              <input id = "bathr" type="checkbox">
              <i></i>
            </label>
          </div>
        </div>

        <div  id="device" style="margin:20px 0px 0px 60px;border: 1px solid gray;float:left ;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
          <div id="devicetext" class="sub-title"><span style="margin-left: 20px; margin-right: 200px; color:#FF8C00;"> Device </span></div>
          <div id= id="devicebutton" style="float:left;margin: 20px 20px 20px 20px; width: 400px">
            <b>Television</b>
            <label class="form-switch" style="margin-left:259px">
              <input id="tv" type="checkbox">
              <i></i>
            </label>
          </div>
          <div style="margin: 20px 20px 20px 20px;">
            <b>Frigde</b>
            <label class="form-switch" style="margin-left:285px">
              <input id="fr" type="checkbox">
              <i></i>
            </label>
          </div>
          <div style="margin: 20px 20px 20px 20px;">
            <b>Comfortable</b>
            <label class="form-switch" style="margin-left:240px">
              <input id="cf" type="checkbox">
              <i></i>
            </label>
          </div>
          <div style="margin: 20px 20px 20px 20px;">
            <b>Fan</b>
            <label class="form-switch" style="margin-left:305px">
              <input id="fan" type="checkbox">
              <i></i>
            </label>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
