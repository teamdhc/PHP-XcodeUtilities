<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
<h2>give '@synthesize' get [release] and .nil</h2>
<?php
$out = '';   $out2 = '';
if(isset($_POST['yes'])) {
  $in = trim($_POST['a']);
  $in = str_replace('@synthesize','',$in);
  $lns = explode("\n",$in);
  foreach($lns as $line) {
    $line = trim($line);
    if(strlen($line)>0) {
      $keys = explode(',', $line);

      foreach($keys as $key) {
        $key = trim(str_replace(';','',$key));
        $l2 = explode('=', $key,2);
        if(count($l2)>1) {
          $key = $l2[0];
        }
        $out .= '[self.';
        $out .= $key;
        $out .= ' release];';
        $out .= "\n";
      }
    }
  }

  $in = trim($_POST['a']);
  $in = str_replace('@synthesize','',$in);
  $lns = explode("\n",$in);
  foreach($lns as $line) {
    $line = trim($line);
    if(strlen($line)>0) {
      $keys = explode(',', $line);

      foreach($keys as $key) {
        $key = trim(str_replace(';','',$key));
        $l2 = explode('=', $key,2);
        if(count($l2)>1) {
          $key = $l2[0];
        }
        $out2 .= 'self.';
        $out2 .= $key;
        $out2 .= '=nil;';
        $out2 .= "\n";
      }
    }
  }
}
?>
<form action="" method="post">
  <input type="hidden" name="yes" value="please">
  <textarea style="width:32%; height:90%;" name="a"><?php
    echo(@$_POST['a']);
    ?></textarea>
  <textarea style="width:32%; height:90%;" name="b"><?php
    echo(@$out);
    ?></textarea>
  <textarea style="width:32%; height:90%;" name="c"><?php
    echo(@$out2);
    ?></textarea>
  <input type="submit">
</form>
</body>
</html>