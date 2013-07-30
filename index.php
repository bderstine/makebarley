<?php
$subs = array("<h1","<h2","<h3","<h4","<h5","<p","<li>");
if(isset($_POST) && count($_POST)>0){
  $prefix = $_POST["prefix"];
  $menutype = $_POST["menutype"];
  $uniqid = uniqid();
  $uploaddir = 'temp/';
  $uploadfile = $uploaddir.$uniqid."_".basename($_FILES['userfile']['name']);
  if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {} else { echo "There was a problem uploading the file!\n"; exit; }
} else {
  $prefix = "mb_";
  $menutype = "advanced";
}
?>
<html>
<head>
<title>MakeBarley.com - Convert static html to a GetBarley.com template!</title>
<style type="text/css">
body{
  font-face: arial;
  color: #666666;
}
h1,h2,h3,h4,h5{
  margin: 0;
  padding: 0;
}
a{
  text-decoration: none;
  color: #15689F;
}
a:hover{
  text-decoration: underline;
}
#header{
  margin-bottom: 15px;
}
</style>
</head>

<body>
<div id="wrapper" style="padding:20px;">

<div id="header">
  <h1><a href="http://www.makebarley.com">MakeBarley.com</a></h1>
  <h3>Convert static html templates into <a href="http://getbarley.com" target="_blank">GetBarley</a> templates!</h3>
  <h4>Works on <?php foreach($subs as $s){ echo htmlspecialchars($s)." "; } ?></h4>
</div>

<p>Download the test file: <a href="test.html">right-click here and save link/target as</a></p>

<div style="float:left;width:20%;">
  <form enctype="multipart/form-data" action="index.php" method="POST">
    Update this html file: <input name="userfile" type="file" /><br/><br/>

    Prefix: <input type="text" name="prefix" size="20" value="<?php echo $prefix; ?>"/><br/><br/>

    Menu Type:<br/>
    <input type="radio" name="menutype" value="blog" <?php if($menutype=="blog"){ echo "checked"; } ?> /> Blog<br>
    <input type="radio" name="menutype" value="advanced" <?php if($menutype=="advanced"){ echo "checked"; } ?> /> Advanced<br>
    <input type="radio" name="menutype" value="plus" <?php if($menutype=="plus"){ echo "checked"; } ?> /> Plus<br>
    <input type="radio" name="menutype" value="simple" <?php if($menutype=="simple"){ echo "checked"; } ?> /> Simple<br>
    <input type="radio" name="menutype" value="mini" <?php if($menutype=="mini"){ echo "checked"; } ?> /> Mini<br><br>

    <input type="submit" value="Update File" />

  </form>
</div>

<div style="float:right;width:80%;">
<?php
if(isset($_POST) && count($_POST)>0){
  echo "Use the following as your GetBarley template file:<br/>";
  echo "<textarea cols='120' rows='20'>";

  $lines = file($uploadfile); unlink($uploadfile);
  foreach ($lines as $line_num => $line) {
    $hassub = 0; $subtype = "none";
    foreach($subs as $s){
      if(strpos($line,$s)!==false){
        $hassub = 1; $subtype = $s;
      }
    }

    $subreplace = array(
	"<h1 data-barley=\"".$prefix.$line_num."_h1\" data-barley-editor=\"$menutype\"",
	"<h2 data-barley=\"".$prefix.$line_num."_h2\" data-barley-editor=\"$menutype\"",
	"<h3 data-barley=\"".$prefix.$line_num."_h3\" data-barley-editor=\"$menutype\"",
	"<h4 data-barley=\"".$prefix.$line_num."_h4\" data-barley-editor=\"$menutype\"",
	"<h5 data-barley=\"".$prefix.$line_num."_h5\" data-barley-editor=\"$menutype\"",
	"<p data-barley=\"".$prefix.$line_num."_p\" data-barley-editor=\"$menutype\"",
	"<li data-barley=\"".$prefix.$line_num."_li\" data-barley-editor=\"$menutype\">"
    );

    $line = str_replace($subs,$subreplace,$line);
    echo htmlspecialchars($line);
  }  
  echo "</textarea>";
}
?>
</div>
<div style="clear:both;"></div>
</div>

</body>
</html>

