<script>
$(document).ready(function() {
    $('#example').append('<caption style="caption-side: bottom"></caption>');

	$('#example').DataTable( {
		
		"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
		
        dom: 'Bfrtip',
		columnDefs: [
            {
                targets: 1,
                className: 'noVis'
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
				extend: 'copy',
				text: '<i class="fas fa-copy"></i>&nbsp;Copy',
				className: 'btn btn-default',
				titleAttr: 'COPY',
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
					}
				}
			,
				{
				extend: 'csv',
				text: '<i class="fas fa-file-csv"></i>&nbsp;CSV',
				className: 'btn btn-default',
				titleAttr: 'CSV',	
				exportOptions: {
					columns: ':not(.notexport)',
					columns: ':visible'
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
				extend: 'pdf',
				text: '<i class="fas fa-file-pdf"></i>&nbsp;PDF',
				className: 'btn btn-default',
				titleAttr: 'PDF',
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
                       exportOptions: {
                          // stripHtml : false,
                           messageBottom: null,
						   columns: ':not(.notexport)',
						   columns: ':visible'

                           //columns: [ 0, 1, 2, 3, 4 ] //Your Colume value those you want
                           }
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

</style>