<!-- TEST ALERT BOX -->
<script type="text/javascript">
  bootstrap_alert = function() {}
  bootstrap_alert.success = function(message) {
    $('#alert_placeholder').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><h4><i class="icon fa fa-check"></i> Success!</h4><span>'+message+'</span></div>')

  }
  bootstrap_alert.error = function(message) {
    $('#alert_placeholder').html('<div class="alert alert-error alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><h4><i class="icon fa fa-check"></i> Error!</h4><span>'+message+'</span></div>')
  }
</script>
<!-- /.TEST ALERT BOX -->

<?php require_once('../mysql_connect.php');
session_start();
if ($_SESSION['username']!= 'carl' || $_SESSION['password']!= '1234') 
 header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/Login.php");

if (isset($_POST['submit'])&&isset($_POST['Universities'])){
  $_SESSION["All"] = false;
  $messageScreen = null;
  if(!isset($messageScreen)){

    /*INSTANTIATE*/
    $Universities=Array();

    /*GET ALL THE VALUES IN ALL CHECKBOXES*/
    for($count=0; $count<sizeof($_POST['Universities']); $count++){
      $Universities[$count]= $_POST['Universities'][$count];
      $messageScreen.="<br>";
      $messageScreen.=$Universities[$count];
    }
    $_SESSION["Universities"] = $Universities;
    $error=0;
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/TableView.php");
  }

}else if(isset($_POST['all'])){
  $_SESSION["All"] = true;
  $error=0;
  header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/TableView.php");
}else if(isset($_POST['submit'])&&!isset($_POST['Universities'])){
  $messageScreen="Please check at least one university.";
  $error=1;
    } //  end of sumbit

    ?>

    <html>
    <head>
      <title>University Data</title>
      <!-- Dependencies -->
      <!-- Bootstrap 3.3.6 -->
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
      <!-- DataTables -->
      <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
      <!-- jQuery 2.2.3 -->
      <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
      <!-- Bootstrap 3.3.6 -->
      <script src="../bootstrap/js/bootstrap.min.js"></script>
      <!-- DataTables -->
      <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
      <!-- /.Dependencies -->
    </head>
    <body>
      <!-- Content Header (Page header) -->
      <section class="content-header">


        <h1>
          Universities
          <small>
            <?php
            date_default_timezone_set("Asia/Manila");
            echo date("l").', '.date("Y/m/d"). ' ';
            echo date("h:i:sa").'<p>';
            ?>
          </small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-xs-5">
            <div class="box">
              <div class="box-header">
              </div>

              <!-- ALERT CONTAINER-->
              <div id = "alert_placeholder"></div>
              <!-- /.box-header -->
              <div class="box-body">
                <!-- MAIN CONTENT START -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <table id='universities' class='table table-bordered table-striped'>
                    <thead>

                      <tr>
                        <th width="10%" class="hidden"><input type="checkbox" onClick="toggle(this)"></th>
                        <th>University</th>
                        <th>Count</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query="SELECT students.University, count(students.University) AS 'Count' FROM universitydata.students GROUP BY students.University";
                      $result=mysqli_query($dbc,$query)or die("Error: ".mysqli_error($dbc));

                      $ctr=0;
                      while($row=mysqli_fetch_array($result,MYSQL_ASSOC)){
                        /*CHECKBOX*/
                        echo"
                        <tr id='row{$ctr}'>
                          <td class='hidden'>
                            <input type='checkbox' class='minimal' name='Universities[]' value='{$row['University']}' id='check{$ctr}'>
                          </td>
                          ";
                          /*END OF CHECKBOX*/
                          echo "
                          <td>".$row['University']."</td>
                          <td>".$row['Count']."</td>
                        </tr>";
                        $ctr++;
                      } 
                      ?>
                    </tbody>
                  </table>
                  <!-- END OF MAIN CONTENT -->
                  <div class="btn-group pull-right">
                    <button type="submit" name="all" class="btn btn-warning" value="All">View All</button>
                    <button type="submit" name="submit" class="btn btn-primary" value="Submit">View Selected</button>
                  </div>
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <!-- END OF FORM -->  
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </section>
          <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- page script -->
        <script>
          $(document).ready(function() {
            $('#universities').DataTable({
              "paging": false,
              "lengthChange": false,
              "searching": false,
              "ordering": false,
              "info": false,
              "autoWidth": false
            });
          } );

          /*CLICKABLE ROWS*/
          $('#universities tbody').on( 'click', 'tr', function () {
            if (event.target.type !== 'checkbox') {
              $(':checkbox', this).trigger('click');
            }
          });

          $("input[type='checkbox']").change(function (e) {
            if ($(this).is(":checked")) {
              $(this).closest('tr').addClass("active");
            } else {
              $(this).closest('tr').removeClass("active");
            }
          });

          /*TOGGLE ALL CHECKBOXES*/
          function toggle(source) {
            checkboxes = document.getElementsByName('Universities[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
            }
          }
        </script>
        <script>
          <?php
          if(isset($messageScreen) && $error==0){
            echo"
            bootstrap_alert.success('{$messageScreen}');
            ";
          }else if($error==1){
            echo"
            bootstrap_alert.error('{$messageScreen}');
            ";
            $error=0;
          }
          ?>
        </script>
        <!-- /.page script -->
        <!-- style -->
        <style type="text/css">
          #universities tbody tr:hover{
            background-color: #EAEDED;
          }
        </style>
        <!-- /.style -->
      </body>
      </html>