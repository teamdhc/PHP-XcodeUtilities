<?php
/**
 * Created by PhpStorm.
 * User: Carl
 * Date: 17/03/14
 * Time: 22:02
 */

//This started out as a quick script, and suprisingly did well for the latest project!
//TODO: Redo parser, using an XML Parser instead
//TODO: Parser the data with more intelligence, including obeying auto-size masks and font sizes.

function tokener($c) {
  if (ord($c) >= 48 && ord($c) <= 57) return 'number';
  return 'whitespace';
}

function parseQuigs($a) {
  $tokens = array();
  $tokenValue = '';
  $currentToken = FALSE;
  for ($i = 0; $i < strlen($a); $i++) {
    $b = tokener($a{$i});
    if ($b != $currentToken) {
      if ($currentToken !== FALSE) $tokens[] = array(0 => $currentToken, 1 => $tokenValue);
      $tokenValue = '';
      $currentToken = $b;
    }
    $tokenValue .= $a{$i};
  }
  if ($currentToken !== FALSE) $tokens[] = array(0 => $currentToken, 1 => $tokenValue);

  return $tokens;
}

if (isset($_FILES['fle'])) {
  $fle = $_FILES['fle'];
  if ($fle['error'] == UPLOAD_ERR_OK) {
    if (is_uploaded_file($fle['tmp_name'])) {

      $fromSrcX = 1;
      $fromSrcY = 1;
      $toSrcX = 1;
      $toSrcY = 1;

      $d1 = '';
      $d2 = '';
      $e1 = '';
      $e2 = '';
      switch ($_POST['from']) {
      case('ipad'):
        $d1 = 'com.apple.InterfaceBuilder3.CocoaTouch.iPad.XIB';
        $e1 = 'iOS.CocoaTouch.iPad';
        $fromSrcY = 1024;
        $fromSrcX = 768;
        break;
      case('iphone'):
        $d1 = 'com.apple.InterfaceBuilder3.CocoaTouch.XIB';
        $e1 = 'iOS.CocoaTouch';
        $fromSrcY = 480;
        $fromSrcX = 320;
        break;
      case('iphonetall'):
        $d1 = 'com.apple.InterfaceBuilder3.CocoaTouch.XIB';
        $e1 = 'iOS.CocoaTouch';
        $fromSrcY = 568;
        $fromSrcX = 320;
        break;
      }


      $rpr = '';
      switch ($_POST['to']) {
      case('ipad'):
        $rpr = '-iPad';
        $d2 = 'com.apple.InterfaceBuilder3.CocoaTouch.iPad.XIB';
        $e2 = 'iOS.CocoaTouch.iPad';
        $toSrcY = 1024;
        $toSrcX = 768;
        break;
      case('iphone'):
        $rpr = '-iPhone';
        $d2 = 'com.apple.InterfaceBuilder3.CocoaTouch.XIB';
        $e2 = 'iOS.CocoaTouch';
        $toSrcY = 480;
        $toSrcX = 320;
        break;
      case('iphonetall'):
        $rpr = '-iPhoneTall';
        $d2 = 'com.apple.InterfaceBuilder3.CocoaTouch.XIB';
        $e2 = 'iOS.CocoaTouch';
        $toSrcY = 568;
        $toSrcX = 320;
        break;
      }

      $targetSX = $fromSrcX / $toSrcX;
      $targetSY = $fromSrcY / $toSrcY;
      $a = array();
      $a[$d1] = $d2;
      $a[$e1] = $e2;

      if (@$_POST['landscape'] > 0) {
        list($targetSX, $targetSY) = array($targetSY, $targetSX);
      }

      $newFilename = str_replace('.xib', $rpr . '.xib', $fle['name']);
      $g = file_get_contents($fle['tmp_name']);

      //Replace
      foreach ($a as $o1 => $o2) {
        $g = str_replace($o1, $o2, $g);
      }

      $t1 = explode('>{', $g);
      for ($i = 1; $i < count($t1); $i++) {
        $t2 = explode('}<', $t1[$i], 2);
        $t2[0] = '{' . $t2[0] . '}';
        $k1 = parseQuigs($t2[0]);

        $t2[0] = '';
        $xistrue = true;
        foreach ($k1 as $w) {
          if ($w[0] == 'whitespace') {
            $t2[0] .= $w[1];
          } else {
            if ($xistrue) {
              $t2[0] .= ceil($w[1] / $targetSX);
            } else {
              $t2[0] .= ceil($w[1] / $targetSY);
            }
            $xistrue = !$xistrue;
          }
        }
        $t1[$i] = implode('<', $t2);
      }

      $g = implode('>', $t1);

      Header('Content-Description: File Transfer');
      Header('Content-Type: application/force-download');
      Header('Content-Disposition: attachment; filename=' . $newFilename);
      echo $g;
      return;
    }
  }

}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XIB Convert</title>
</head>
<body>
<h3>XIB Conversion</h3>

<form action="?a=1" method="post" enctype="multipart/form-data">
  <select name="from">
    <option value="ipad">iPad</option>
    <option value="iphone">iPhone 3.5"</option>
    <option value="iphonetall">iPhone 4"</option>
  </select>
  <select name="to">
    <option value="ipad">iPad</option>
    <option value="iphone">iPhone 3.5"</option>
    <option value="iphonetall">iPhone 4"</option>
  </select>
  <input type="checkbox" name="landscape" value="123"> is Landscape</br>
  <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
  <input type="file" name="fle"></br>
  <input type="submit" name="asda" value="Submit"></br>
</form>
</body>
</html>