</div>
<script type="text/javascript">
	var pageType = '<?php echo (isset($_GET['type'])) ? $_GET['type']: ''; ?>';
</script>
<script src="<?php echo $origin ?>/js/script.js"></script>
<script src="<?php echo $origin ?>/js/CodeMirror/lib/codemirror.js"></script>
<script src="<?php echo $origin ?>/js/CodeMirror/mode/xml/xml.js"></script>
<script src="<?php echo $origin ?>/js/CodeMirror/mode/javascript/javascript.js"></script>
<script src="<?php echo $origin ?>/js/CodeMirror/mode/css/css.js"></script>
<script src="<?php echo $origin ?>/js/CodeMirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="<?php echo $origin ?>/js/CodeMirror/addon/edit/matchbrackets.js"></script>
<script src="<?php echo $origin ?>/js/CodeMirror/doc/activebookmark.js"></script>
<?php 
if(isset($_GET['url'])){
$pathinfo = pathinfo($_GET['url']);

if(isset($_GET['type']) && $_GET['type'] == 'template' && $pathinfo['extension'] == 'js'){
?>
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("editor-area"), {
      lineNumbers: true,
      mode: "text/javascript",
      matchBrackets: true,
	  onBlur: function(){editor.save()}
    });
	
  </script>
<?php
}
if(isset($_GET['type']) && $_GET['type'] == 'template' && $pathinfo['extension'] == 'html'){
?>
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("editor-area"), {
      lineNumbers: true,
      mode: "text/html",
      matchBrackets: true,
	  onBlur: function(){editor.save()}
    });
	
  </script>
<?php
}
if(isset($_GET['type']) && $_GET['type'] == 'template' && $pathinfo['extension'] == 'css'){

?>
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("editor-area"), {
      lineNumbers: true,
      mode: "text/css",
      matchBrackets: true,
	  onBlur: function(){editor.save()}
    });
	
  </script>
  <?php } }?>
  
  
 <!-- <?php 
//if(isset($_GET['type']) && $_GET['type'] == 'page'){
?>
<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("editor-area"), {
      lineNumbers: true,
      mode: "text/x-markdown",
      matchBrackets: true,
	  onBlur: function(){editor.save()}
    });
	
  </script>
  <?php// } ?>-->
</body>
</html>
