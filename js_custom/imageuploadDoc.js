////$(".image_preview").change(function(){ //// not working for dynamically added selects	
var MAX_FILE_SIZE 			 	= (1024*1024); // 1MB
var MAX_FILE_SIZE 			 	= (MAX_FILE_SIZE*14); // 14MB

$('body').on('change', '.image_check', function() {
		fileSize = this.files[0].size;
		if (fileSize > MAX_FILE_SIZE) {
			var $image = $('#img-upload-'+this.id);
			$image.removeAttr('src').replaceWith($image.clone());
			/////alert('File size must be less than 5 Mb');
			showMessageFull('File size must be less than 15 Mb');
			////document.getElementById(this.id).value = "";
			jQuery('#'+this.id).remove();
			var addnew ='<input type="file" class="form-control form-control-sm image_check" id="'+this.id+'">';
			var idd=this.id+'Fieldset';
			$('#'+idd).append(addnew);
			return;
		} 
		///else 
		////readURL(this);
});
function check_upload_file(id)
{
	var fileName = document.getElementById(''+id).value.toLowerCase();
	
	var path = window.location.pathname;
	var page = path.split("/").pop();
	
	if(!fileName.endsWith('.jpg') && !fileName.endsWith('.png') && !fileName.endsWith('.jpeg') && !fileName.endsWith('.pdf') && !fileName.endsWith('.docx') && !fileName.endsWith('.xlsx') ){
		////alert("Please upload jpg, png, jpeg, gif, pdf, docx or xlsx extension files only");
		showMessageFull('Please upload jpg, png, jpeg, gif, pdf, docx or xlsx extension files only');
		jQuery('#'+id).remove();
		var addnew ='<input type="file" class="form-control form-control-sm image_check" id="'+id+'">';
		var idd=id+'Fieldset';
		$('#'+idd).append(addnew);
		////document.getElementById(id).value = null;
		return;
	}
}