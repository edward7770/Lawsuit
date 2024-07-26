
$('.datatable').DataTable({
    "bFilter": true,
	"destroy": true,
	////"dom": '<"datatable-header"lfB>rtip', // Use 'B' at the end
    "sDom": 'fBtlpi',
	///dom: 'Bfrtip',
	////dom: 'frtpB', //This changes the position to Bottom
    "ordering": true,
    "order": [],

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
      $('.dt-buttons').css('display', 'none');

      var type=$('#lawsuitsType').val();
      var state=$('#state').val();
      var stage=$('#stage').val();
      var reportType=$('#reportType').val();

      // $('div.dataTables_filter').css('position', 'absolute');
      // $('div.dataTables_filter').css('right', '0px');
      // var copyButton = '<a href="#" class="table-btn-action-icon buttons-copy" onclick="printLawsuitReport();"><span><i class="fa fa-copy"></i></span></a>';
      var csvButton = '<a href="LawsuitExcelPrint.php?type=' + type +'&stage=' + stage +'&reportType=' + reportType + '&state=' + state + '" class="table-btn-action-icon buttons-csv"><span><i class="fa fa-file-csv"></i></span></a>';
      var printButton = '<a href="#" class="table-btn-action-icon buttons-print" onclick="printLawsuitReport();"><span><i class="fa fa-print"></i></span></a>';
      $(printButton).insertAfter(".dataTables_filter");
      $(csvButton).insertAfter(".dataTables_filter");
      // $(copyButton).insertAfter(".dataTables_filter");
    },
	
});