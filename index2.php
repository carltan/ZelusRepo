<head>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">

<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js">
$(document).ready(function(){
    	$('#userData').DataTable();
	});
  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    tr:nth-child(even) {
      background-color: #dddddd;
    }
  </style>
</head>
<body>
	
  <table id="userData">
    <tr>
      <th>Name</th>
      <th>Surname</th>
      <th>Birthday</th>
      <th>University</th>
    </tr>
    <?php
    $query="SELECT * FROM universitydata.students";
    $result=mysqli_query($dbc,$query)or die("Error: ".mysqli_error($dbc));
    while($row=mysqli_fetch_array($result,MYSQL_ASSOC)){
      echo "<tr>
      <td>".$row['Name']."</td>
      <td>".$row['Surname']."</td>
      <td>".substr($row['Birthday'], 0, 10)."</td>
      <td>".$row['University']."</td>
    </tr>";
  } 
  ?>
</table>
</body>
</html>