<!--// Load the required libraries  
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html-docx-js/dist/html-docx.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js"></script>

-->
<script src="js_custom/FileSaver.min.js"></script>
<script src="js_custom/html-docx.js"></script>
<script src="js_custom/jszip.min.js"></script>

<script>
	
/////var htmlText = '<p><span style="color: rgb(255, 0, 0); font-family: Inter, sans-serif; font-size: 16px;">Contract Terms UN :</span><br></p>';

function generateHTML_docZip(htmlText1,htmlText2,fileName)	
{
// Define the HTML text
	
	// Convert the HTML text to a Word document
	var docx1 = htmlDocx.asBlob(htmlText1);
	var docx2 = htmlDocx.asBlob(htmlText2);

	// Create multiple Word documents
	var docx1 = docx1;
	var docx2 = docx2;

	// Compress all the Word documents into a single ZIP file
	var zip = new JSZip();
	zip.file("contractEn.docx", docx1);
	zip.file("contractAr.docx", docx2);
	zip.generateAsync({type:"blob"}).then(function(content) {
		// Download the ZIP file
		saveAs(content, fileName+".zip");
	});
}

////generateHTML_docZip(htmlText,'fileName');	
////generateHTML_docZip('tttt','t222t','fileName');
</script>