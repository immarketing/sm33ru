<?php
include ('sm33/myandexexp.php');
$fName='./tmp/yandex/testyml.xml';
$YMLWriter = new sm33_myandexexp($fName);
$YMLWriter->writeHDR();
$YMLWriter->writeRootStart();

$YMLWriter->writeRootFinish();
$YMLWriter->closeFile();

// Mastera.Uzhasov
<?php
include ('pleer.txt.php');
$tmpFName="./tmp/pleer.ru/eletr-knigi.html";
$loaded = loadFile ( $tmpFName );
$loaded = trimUnusedChars ( $loaded );
$rws = getProdRows ( $loaded );
echo count($rws );
$v=$rws[0];
$res = preg_match ( "/<h1><a[^>]*?>([^<]*?)</ims", $v, $nInfo );
echo $res;
$res = preg_match ( "/22px[^<]*?<a[^>]*?>([^<]*?)</ims", $v, $nInfo );
echo $res;
$res = preg_match ( "/<p[^i]*?id=\"s_desc[^>]*?>([^<]*?)</ims", $v, $nInfo );
echo $res;
$res = preg_match ( "/<p>[^:]*?: ([^<]*?)</ims", $v, $nInfo );
echo $res;
$res = preg_match ( "/<span[^c]*?class=\"price[^>]*?>([^<]*?)</ims", $v, $nInfo );
echo $res;
$res = preg_match ( "/<h1><a[^h]*?href=\"([^\"]*?)\"/ims", $v, $nInfo );
echo $res;
$res = preg_match ( "/22px[^<]*?<a[^h]*?href=\"([^\"]*?)\"/ims", $v, $nInfo );
echo $res;

























