<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>แบบฟอร์มอัพโหลด doctor order</title>
<?php include('java_css_file.php'); ?>
<style>
.file {
  visibility: hidden;
  position: absolute;
}	
</style>
<script>
$(document).on("click", ".browse", function() {
  var file = $(this)
    .parent()
    .parent()
    .parent()
    .find(".file");
  file.trigger("click");
});
$('input[type="file"]').change(function(e) {
  var fileName = e.target.files[0].name;
  $("#file").val(fileName);
 
  var reader = new FileReader();
  reader.onload = function(e) {
    // get loaded data and render thumbnail.
    document.getElementById("preview").src = e.target.result;
  };
  // read the image file as a data URL.
  reader.readAsDataURL(this.files[0]);
});
</script>
</head>

<body>
<div class="container">
        <div class="ml-2 col-sm-4">
            <div id="msg"></div>
            <form method="post" id="image-form" enctype="multipart/form-data" onSubmit="return false;">
                <div class="form-group">
                    <input type="file" name="file" class="file">
                    <div class="input-group my-3">
                        <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                        <div class="input-group-append">
                            <button type="button" class="browse btn btn-primary">Browse...</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <img src="<a class="vglnk" href="https://placehold.it/80x80" rel="nofollow"><span>https</span><span>://</span><span>placehold</span><span>.</span><span>it</span><span>/</span><span>80x80</span></a>" id="preview" class="img-thumbnail">
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Upload" class="btn btn-danger">
                </div>
            </form>
        </div>
    </div></body>
</html>