<?php
echo '**********';
?>
<form method="post" action="/email/upload-attachments" enctype="multipart/form-data">
	<input type="file" name="attachments[]" multiple="multiple"/>
	<input type="submit" value="上传"/>
</form>