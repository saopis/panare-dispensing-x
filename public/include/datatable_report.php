<script>
$(document).ready(function() {
    $('#example').DataTable( {
		"lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
		
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
        ]
    } );
});
</script>