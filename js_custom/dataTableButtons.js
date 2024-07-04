
$('.datatable').DataTable({
    "bFilter": true,
	"destroy": true,
	////"dom": '<"datatable-header"lfB>rtip', // Use 'B' at the end
    "sDom": 'fBtlpi',
	///dom: 'Bfrtip',
	////dom: 'frtpB', //This changes the position to Bottom
    "ordering": true,
    "order": [],
    "buttons": [
                {
			   extend: 'csv',
			   charset: 'UTF-8',
			   ///fieldSeparator: ';',
			   bom: true,
			   ///filename: 'CsvTest',
			   ////title: 'CsvTest'
			  },
			  'copy', 'excel', 'pdf', 'print'
		  ],
    "language": {
      search: '<i class="fas fa-search"></i>',
      searchPlaceholder: "Search",
      sLengthMenu: '_MENU_',
      paginate: {
        next: 'Next <i class=" fa fa-angle-double-right ms-2"></i>',
        previous: '<i class="fa fa-angle-double-left me-2"></i> Previous'
      },
    },
    initComplete: (settings, json) => {
      $('.dataTables_filter').appendTo('#tableSearch');
      $('.dataTables_filter').appendTo('.search-input');
    },
	
});
$(document).ready(function() {
  $('.dt-buttons').css('float', 'right');
});