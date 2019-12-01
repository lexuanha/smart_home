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
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script></head>
    <style>
    #chartdiv$type {
      width: 100%;
      height: 500px;
    }
    </style>
    <!-- Resources -->
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <!-- Chart code -->
    
    
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
        <input type="date" name="date" style = "margin-top: 20px; border: 1px solid black; background-color: #ECF0F5;font-family: Roboto;">
        <input type="submit" class="square_btn" style = " margin-top: 20px " name="make" value="Select">
      </form>
    </div>                  
    <script>
      openTab(4);
    </script>
    <div id="page-content" style="float:left; height: auto;width: auto;color: #18699F;margin: 70px 0px 0px 200px;">
      <div id="general-page" class="page" style="height: auto; width: auto">
        <?php
          if(isset($_POST['make'])){
            $obj = json_decode($_COOKIE["select"],true);

            $date = $_POST['date'];
            $time_bg = "\"$date 00:00:00\"";
            // echo $time_bg;
            $time_end = "\"$date 23:59:59\"";
            $time ="time";
            for( $i = 0; $i < sizeof($obj) ; $i++){
              $type = "0";
              $id_area = $obj[$i];
              
              $res_id = $conn->query("SELECT * FROM location WHERE parentid = $id_area ");//Xem id nay co phai la node la khong.
              if ($res_id->num_rows == 0){
                
                $res_type = $conn->query("SELECT * FROM sensor"); //truy van cac kieu du lieu da co.
                if ($res_type->num_rows > 0){
                  while($row = $res_type->fetch_assoc()){
                    $phpvalue = array();
                    $phptimeline = array();
                    $len;
                    $id_sensor = $row["id"];
                    $type = $row["code"];
                    $sensor = $row["type"];
                    $color = $row["des"];

                    $res_2id = $conn->query("SELECT id FROM router WHERE areaid = $id_area and sensorid = $id_sensor");
                    if ($res_2id->num_rows > 0){
                      while($row = $res_2id->fetch_assoc()){
                        $cur_id = $row["id"];
                        $res_his = NULL;
                        if($date==NULL){
                          $date = date("Y-m-d");
                          $time_bg = "\"$date 00:00:00\"";
                          $time_end = "\"$date 23:59:59\"";
                          //echo $time_bg;
                        }
                        $res_his = $conn->query("SELECT * FROM sensordata WHERE sensor = $cur_id AND $time >= $time_bg AND $time <= $time_end ");
                        if ($res_his->num_rows > 0){
                          $len = $res_his->num_rows;
                          while($row = $res_his->fetch_assoc()){
                            $phpvalue[] = $row["value"];
                            $phptimeline[] =$row["time"];
                          }
                        }
                      }
                    
                    $test1 = json_encode($phpvalue);
                    $test2 = json_encode($phptimeline);
                    echo "
                     <script>
                      am4core.ready(function() {
                        // Themes begin
                        am4core.useTheme(am4themes_animated);
                        // Themes end
                        // Create chart instance
                        var chart = am4core.create(\"chartdiv$type\", am4charts.XYChart);
                        // Add data
                        chart.data = generateChartData$type();
                        // Create axes
                        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                        dateAxis.renderer.minGridDistance = 50;
                        dateAxis.baseInterval = {
                          \"timeUnit\": \"second\",
                          \"count\": 1
                        };
                        dateAxis.tooltipDateFormat = \"HH:mm:ss, d MMMM\";

                        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                        // Create series
                        var series = chart.series.push(new am4charts.LineSeries());
                        series.dataFields.valueY = \"visits\";
                        series.dataFields.dateX = \"time\";
                        series.strokeWidth = 2;
                        series.stroke = \"" .$color . "\";
                        //series.connect = true;
                        // series.tensionX = 0.8;
                        series.minBulletDistance = 10;
                        series.tooltipText = \"{valueY}\";
                        series.tooltip.pointerOrientation = \"vertical\";
                        series.tooltip.background.cornerRadius = 20;
                        series.tooltip.background.fillOpacity = 0.5;
                        series.tooltip.label.padding(12,12,12,12)
                        // Add scrollbar
                        chart.scrollbarX = new am4core.Scrollbar();
                        //chart.scrollbarX.series.push(series);
                        // Add cursor
                        chart.cursor = new am4charts.XYCursor();
                        chart.cursor.xAxis = dateAxis;
                        chart.cursor.snapToSeries = series;
                        function generateChartData$type() {
                          
                          var chartData$type = [];
                        
                          var data$type = " .$test1 .";
                          var timeline = " .$test2 .";

                          for (var i = 0; i < " .$len ."; i++) {

                              chartData$type.push({
                                  time: new Date(timeline[i]),
                                  visits: data".$type."[i]
                              });
                          }
                          return chartData$type;
                        }
                      }); // end am4core.ready()
                    </script>
                    <div style=\"height: auto; width: 1500px; margin: 50px 50px 50px 20px;\">
                      <center><h1 style= \"color: $color;\">$sensor</h1></center>
                      <div id=\"chartdiv$type\" style=\"height: 350px;\"></div>
                    </div>
                    ";
                    }
                  }
                }
              }else echo "<script>alert(\"Please select specifically location! :(\");</script>";
            }
          }
        ?>
      </div>
    </div>
  </body>
</html>
