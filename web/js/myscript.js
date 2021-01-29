$(document).ready(function() {
        
    $("#input-44").fileinput({
    	uploadUrl:  'loadimg/uploadimg',
        //uploadUrl: '/uploads',  
        showPreview: true,
        maxFileSize: 10000,    
        maxFilePreviewSize: 10240,
        allowedFileExtensions: ["jpg", "jpeg", "gif", "png"],
        deleteUrl: "/site/file-delete",
    });
});
