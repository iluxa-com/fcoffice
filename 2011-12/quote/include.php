<?php
function utf8_unicode_str($str) {

     $y='';
     for($i=0;$i<mb_strlen($str,'utf-8');$i++){
        $tmp= mb_substr($str,$i,1,'utf-8');
        $y .= utf8_unicode($tmp);
     }
     return $y ;
 }

// utf8 -> unicode
function utf8_unicode($c) {
 switch(strlen($c)) {
 case 1:
 $n = ord($c);
 break;
 case 2:
 $n = (ord($c[0]) & 0x3f) << 6;
 $n += ord($c[1]) & 0x3f;
 break;
 case 3:
 $n = (ord($c[0]) & 0x1f) << 12;
 $n += (ord($c[1]) & 0x3f) << 6;
 $n += ord($c[2]) & 0x3f;
 break;
 case 4:
 $n = (ord($c[0]) & 0x0f) << 18;
 $n += (ord($c[1]) & 0x3f) << 12;
 $n += (ord($c[2]) & 0x3f) << 6;
 $n += ord($c[3]) & 0x3f;
 break;
 }
 return "&#$n;";
}