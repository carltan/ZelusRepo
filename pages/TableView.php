<?php require_once('../mysql_connect.php'); 
session_start();
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
			Students
			<small>
				<?php
				date_default_timezone_set("Asia/Manila");
				echo date("l").', '.date("Y/m/d"). ' ';
				echo date("h:i:sa").'<p>';
				?>
			</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><a href="#">Students</a></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"></h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!-- MAIN CONTENT START -->
						<table border="0" cellspacing="5" cellpadding="5">
							<tbody><tr>
								<td>Minimum age:</td>
								<td><input type="text" id="min" name="min"></td>
							</tr>
							<tr>
								<td>Maximum age:</td>
								<td><input type="text" id="max" name="max"></td>
							</tr>
						</tbody></table>
						<table id='students' class='table table-bordered table-striped'>
							<thead>
								<tr>
									<th>Name</th>
									<th>Surname</th>
									<th>Birthday</th>
									<th>University</th>
									<th>Age</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$whereClause="";
								$Universities = $_SESSION['Universities'];
								for($count=0; $count<sizeof($Universities); $count++){
									$whereClause.=" University = '".$Universities[$count]."' ";
									if($count!=sizeof($Universities)-1){
										$whereClause.="OR";
									}
								}
								$query="SELECT *, 
								TIMESTAMPDIFF(YEAR,students.Birthday,CURDATE())
								AS 'Age' 
								FROM universitydata.students 
								WHERE".$whereClause;
								if($_SESSION['All']==true){
									$query="SELECT *,
									TIMESTAMPDIFF(YEAR,students.Birthday,CURDATE())
									AS 'Age' 
									FROM universitydata.students";
									$_SESSION['All']=false;
								}
								$result=mysqli_query($dbc,$query)or die("Error: ".mysqli_error($dbc));
								while($row=mysqli_fetch_array($result,MYSQL_ASSOC)){
									echo "<tr>
									<td>".$row['Name']."</td>
									<td>".$row['Surname']."</td>
									<td>".substr($row['Birthday'], 0, 10)."</td>
									<td>".$row['University']."</td>
									<td>".$row['Age']."</td>
								</tr>";
							} 
							?>
						</tbody>
						<!-- /TABLE FOOTER -->
						<tfoot>
							<tr>
								<th colspan="4">END OF REPORT</th>
							</tr>
						</tfoot>
					</table>
					<!-- END OF MAIN CONTENT -->
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
<footer class="main-footer">
	<div class="pull-right hidden-xs">
		<b>Version</b> 1.0.0
	</div>
	<strong>Copyright &copy; 2017 <a href="">ZelusRepo</a>.</strong> All rights
	reserved.
</footer>

<!-- page script -->
<script>
	/* Custom filtering function which will search data in column four between two values */
	$.fn.dataTable.ext.search.push(
		function( settings, data, dataIndex ) {
			var min = parseInt( $('#min').val(), 10 );
			var max = parseInt( $('#max').val(), 10 );
        var age = parseFloat( data[4] ) || 0; // use data for the age column

        if ( ( isNaN( min ) && isNaN( max ) ) ||
        	( isNaN( min ) && age <= max ) ||
        	( min <= age   && isNaN( max ) ) ||
        	( min <= age   && age <= max ) )
        {
        	return true;
        }
        return false;
    }
    );

    $(document).ready(function() {
    var table = $('#students').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": true,
			"autoWidth": false
		});
     
    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').keyup( function() {
        table.draw();
    } );
} );

</script>
<style type="text/css">
	#students tbody tr:hover{
		background-color: #EAEDED;
	}
</style>
</body>
</html>