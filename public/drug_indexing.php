<?php require_once('Connections/hos.php'); ?>
<?php 
mysql_select_db($database_hos, $hos);
$query_rs_drug = "select i.*,concat(d.name,' ',d.strength) as drugname from drug_index i left outer join drugitems d on d.icode=i.icode order by d.name";
$rs_drug = mysql_query($query_rs_drug, $hos) or die(mysql_error());
$row_rs_drug = mysql_fetch_assoc($rs_drug);
$totalRows_rs_drug = mysql_num_rows($rs_drug);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Maha Drug Index</title>
<?php include('java_css_file.php'); ?>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://kit.fontawesome.com/1ed6ef1358.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.colVis.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap4.min.css"/>
<script>
$(document).ready(function() {
		
    $('#table_data').append('<caption style="caption-side: bottom"></caption>');

	$('#table_data').DataTable( {
		"order": [[ 1, "asc" ]],
		//"searching": false,
		//"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
		"paging": false,
		"info":     false,
        dom: 'Bfrtip',

		columnDefs: [
            {
                targets: 1,
                className: 'noVis',
	
            }
        ],
        buttons: [  
				{
				extend: 'colvis',
				text: '<i class="fas fa-table"></i>&nbsp;Column',
				className: 'btn btn-default',
				titleAttr: 'COLOUMN',	
				columnText: function ( dt, idx, title ) {
					return (idx+1)+': '+title;
					}
				}
			,
            
				{
				extend: 'excel',
				text: '<i class="fas fa-file-excel"></i>&nbsp;Excel',
				className: 'btn btn-default',
				titleAttr: 'EXCEL',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}	
			, 

                       {
                       extend: 'print',
					   text: '<i class="fas fa-print"></i> Print',
					   titleAttr: 'PRINT',
					   title: '',	   
                       exportOptions: {
                          // stripHtml : false,
						   //messageBottom: null,
						   columns: ':not(.notexport)',
						   //columns: ':visible',
                           //columns: [ 0, 1, 2,5 ] //Your Colume value those you want
                           },
						   message: '<div class="text-center "><h5 class="thfont font-weight-bold">Drug Index : กลุ่มงานเภสัชกรรมและคุ้มครองผู้บริโภค โรงพยาบาลมหาชนะชัย</div>',  
                         }
        ],
		language: {
        search: "_INPUT_",
        searchPlaceholder: "ค้นหา..."
    	}
    } );
});
</script>
<style>
.dataTables_length,.dataTables_filter {
    margin-left: 10px;
	margin-right: 15px;
    float: right;
}
@media print {
    table,table thead, table tr, table td {
        border-top: #000 solid 1px;
        border-bottom: #000 solid 1px;
        border-left: #000 solid 1px;
        border-right: #000 solid 1px;
        font-size: 16px;
    }
    table {
    border:solid #000 !important;
    border-width:1px 0 0 1px !important;
}
th, td {
    border:solid #000 !important;
    border-width:0 1px 1px 0 !important;
}
} 
</style>	
	

</head>

<body>
<nav class="navbar navbar-info bg-info text-white p-2"  >
  <span ><h3>สารบัญชั้นวางยา</h3></span>		
</nav>	
<div class="p-2">
<table width="100%" border="0" cellspacing="0" cellpadding="3" id="table_data" class="table table-hover thfont table-sm table-striped font14">
		<thead>
			<tr>
				<th>ลำดับ</th>
				<th>ชื่อยา</th>
				<th>Index</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0; do{ $i++; ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $row_rs_drug['drugname']; ?></td>
				<td><?php echo $row_rs_drug['drug_index']; ?></td>
			</tr>
			<?php }while($row_rs_drug = mysql_fetch_assoc($rs_drug)); ?>
		</tbody>
	</table>
</div>	
</body>
</html>
<?php mysql_free_result($rs_drug); ?>