<?php /*
$mysqli = mysqli_connect("localhost", "root", "", "ne1_data");
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$res = mysqli_query($mysqli, "SELECT * FROM directory_venues LEFT JOIN directory_venue_translations ON directory_venues.id = directory_venue_translations.venue_id");
while($row = mysqli_fetch_assoc($res)) {
	
	$str = strtolower( strip_tags($row['opening_hours']) );

		
	$str = preg_replace('/\s+/', '', $str);
	$str = preg_replace( "/\r|\n/", "", $str );
	$str = str_replace('.', ":", $str);
	
	$str = str_replace('dinner', "", $str);
	
	$str = str_replace('until', "-", $str);
	$str = str_replace('till', "-", $str);
	
	$str = str_replace(' - ', "-", $str);
	$str = str_replace('midnight', "00:00", $str);
	$str = str_replace('late', "00:00", $str);
	$str = str_replace('12 noon', "12:00", $str);
	$str = str_replace('noon', "12:00", $str);
	$str = str_replace('midday', "12:00", $str);
	$str = str_replace('lunchtime', "12:00", $str);
	$ampm = array(":00am", ":00pm",":30am",":30pm",":15am", ":15pm");
	$str = str_replace($ampm, ":00", $str);

	$str = str_replace(' 10am', "10:00", $str);
	$str = str_replace(' 11am', "11:00", $str);
	$str = str_replace(' 12am', "12:00", $str);	
	$str = str_replace(' 1am', "01:00", $str);
	$str = str_replace(' 2am', "02:00", $str);
	$str = str_replace(' 3am', "03:00", $str);
	$str = str_replace(' 4am', "04:00", $str);
	$str = str_replace(' 5am', "05:00", $str);
	$str = str_replace(' 6am', "06:00", $str);
	$str = str_replace(' 7am', "07:00", $str);
	$str = str_replace(' 8am', "08:00", $str);
	$str = str_replace(' 9am', "09:00", $str);

	$str = str_replace(' 10pm', "22:00", $str);
	$str = str_replace(' 11pm', "23:00", $str);
	$str = str_replace(' 12pm', "24:00", $str);	
	$str = str_replace(' 1pm', "13:00", $str);
	$str = str_replace(' 2pm', "14:00", $str);
	$str = str_replace(' 3pm', "15:00", $str);
	$str = str_replace(' 4pm', "16:00", $str);
	$str = str_replace(' 5pm', "17:00", $str);
	$str = str_replace(' 6pm', "18:00", $str);
	$str = str_replace(' 7pm', "19:00", $str);
	$str = str_replace(' 8pm', "20:00", $str);
	$str = str_replace(' 9pm', "21:00", $str);
	
	$str = preg_replace('/\([^)]*\)+/' , '', $str);
	
	/*
	$str = str_replace(' 1:00', " 01:00", $str);
	$str = str_replace('-1:00', "-01:00", $str);
	$str = str_replace(' 2:00', " 02:00", $str);
	$str = str_replace('-2:00', "-02:00", $str);
	$str = str_replace(' 3:00', " 03:00", $str);
	$str = str_replace('-3:00', "-03:00", $str);
	$str = str_replace(' 4:00', " 04:00", $str);
	$str = str_replace('-4:00', "-04:00", $str);
	$str = str_replace(' 5:00', " 05:00", $str);
	$str = str_replace('-5:00', "-05:00", $str);
	$str = str_replace(' 6:00', " 06:00", $str);
	$str = str_replace('-6:00', "-06:00", $str);
	$str = str_replace(' 7:00', " 07:00", $str);
	$str = str_replace('-7:00', "-07:00", $str);
	$str = str_replace(' 8:00', " 08:00", $str);
	$str = str_replace('-8:00', "-08:00", $str);
	$str = str_replace(' 9:00', " 09:00", $str);
	$str = str_replace('-9:00', "-09:00", $str);
	*/
	
	//$str = str_replace('nbsp', "", $str);
	//$str = str_replace('ndash', "", $str);

	//$str = str_replace('close', "closed", $str);
	//$str = str_replace('closedd', "closed", $str);
	
	//$str = str_replace('day :', "day ", $str);
	
	/*
	$str = str_replace('mon', "monday", $str);
	$str = str_replace('tues', "tuesday", $str);
	$str = str_replace('wed', "wednesday", $str);
	$str = str_replace('thurs', "thursday", $str);
	$str = str_replace('fri', "friday", $str);
	$str = str_replace('sat', "saturday", $str);
	$str = str_replace('sun', "sunday", $str);
	
	$str = str_replace('dayday', "day", $str);
	$str = str_replace('wednesdaynesday', "wednesday", $str);
	$str = str_replace('saturdayurday', "saturday", $str);
	
	$str = preg_replace('/\s/', '', $str);
	
	$str = str_replace('monday', "#monday", $str);
	$str = str_replace('tuesday', "#tuesday", $str);
	$str = str_replace('wednesday', "#wednesday", $str);
	$str = str_replace('thursday', "#thursday", $str);
	$str = str_replace('friday', "#friday", $str);
	$str = str_replace('saturday', "#saturday", $str);
	$str = str_replace('sunday', "#sunday", $str);

	$str = preg_split("/(\#)/", $str);
	
	$str = array_filter($str);
	
	$str = str_replace('monday', "#monday#", $str);
	$str = str_replace('tuesday', "#tuesday#", $str);
	$str = str_replace('wednesday', "#wednesday#", $str);
	$str = str_replace('thursday', "#thursday#", $str);
	$str = str_replace('friday', "#friday", $str);
	$str = str_replace('saturday', "#saturday#", $str);
	$str = str_replace('sunday', "#sunday#", $str);
		
	$y = [];
	foreach ($str as $v) {
		$x = preg_split("/(\#)/", $v);
		array_push($y, $x);
	}
	
	$y = array_filter($y);
	
	//echo $str;
	echo '<pre>';
	//var_dump($str);
	var_dump($y);	
	echo '</pre>';
	
	echo "<br />-------------------------------------------------------------<br />";
}

mysqli_close($mysqli);
*/


namespace ForceUTF8;
class Encoding {
  const ICONV_TRANSLIT = "TRANSLIT";
  const ICONV_IGNORE = "IGNORE";
  const WITHOUT_ICONV = "";
  protected static $win1252ToUtf8 = array(
        128 => "\xe2\x82\xac",
        130 => "\xe2\x80\x9a",
        131 => "\xc6\x92",
        132 => "\xe2\x80\x9e",
        133 => "\xe2\x80\xa6",
        134 => "\xe2\x80\xa0",
        135 => "\xe2\x80\xa1",
        136 => "\xcb\x86",
        137 => "\xe2\x80\xb0",
        138 => "\xc5\xa0",
        139 => "\xe2\x80\xb9",
        140 => "\xc5\x92",
        142 => "\xc5\xbd",
        145 => "\xe2\x80\x98",
        146 => "\xe2\x80\x99",
        147 => "\xe2\x80\x9c",
        148 => "\xe2\x80\x9d",
        149 => "\xe2\x80\xa2",
        150 => "\xe2\x80\x93",
        151 => "\xe2\x80\x94",
        152 => "\xcb\x9c",
        153 => "\xe2\x84\xa2",
        154 => "\xc5\xa1",
        155 => "\xe2\x80\xba",
        156 => "\xc5\x93",
        158 => "\xc5\xbe",
        159 => "\xc5\xb8"
  );
    protected static $brokenUtf8ToUtf8 = array(
        "\xc2\x80" => "\xe2\x82\xac",
        "\xc2\x82" => "\xe2\x80\x9a",
        "\xc2\x83" => "\xc6\x92",
        "\xc2\x84" => "\xe2\x80\x9e",
        "\xc2\x85" => "\xe2\x80\xa6",
        "\xc2\x86" => "\xe2\x80\xa0",
        "\xc2\x87" => "\xe2\x80\xa1",
        "\xc2\x88" => "\xcb\x86",
        "\xc2\x89" => "\xe2\x80\xb0",
        "\xc2\x8a" => "\xc5\xa0",
        "\xc2\x8b" => "\xe2\x80\xb9",
        "\xc2\x8c" => "\xc5\x92",
        "\xc2\x8e" => "\xc5\xbd",
        "\xc2\x91" => "\xe2\x80\x98",
        "\xc2\x92" => "\xe2\x80\x99",
        "\xc2\x93" => "\xe2\x80\x9c",
        "\xc2\x94" => "\xe2\x80\x9d",
        "\xc2\x95" => "\xe2\x80\xa2",
        "\xc2\x96" => "\xe2\x80\x93",
        "\xc2\x97" => "\xe2\x80\x94",
        "\xc2\x98" => "\xcb\x9c",
        "\xc2\x99" => "\xe2\x84\xa2",
        "\xc2\x9a" => "\xc5\xa1",
        "\xc2\x9b" => "\xe2\x80\xba",
        "\xc2\x9c" => "\xc5\x93",
        "\xc2\x9e" => "\xc5\xbe",
        "\xc2\x9f" => "\xc5\xb8"
  );
  protected static $utf8ToWin1252 = array(
       "\xe2\x82\xac" => "\x80",
       "\xe2\x80\x9a" => "\x82",
       "\xc6\x92"     => "\x83",
       "\xe2\x80\x9e" => "\x84",
       "\xe2\x80\xa6" => "\x85",
       "\xe2\x80\xa0" => "\x86",
       "\xe2\x80\xa1" => "\x87",
       "\xcb\x86"     => "\x88",
       "\xe2\x80\xb0" => "\x89",
       "\xc5\xa0"     => "\x8a",
       "\xe2\x80\xb9" => "\x8b",
       "\xc5\x92"     => "\x8c",
       "\xc5\xbd"     => "\x8e",
       "\xe2\x80\x98" => "\x91",
       "\xe2\x80\x99" => "\x92",
       "\xe2\x80\x9c" => "\x93",
       "\xe2\x80\x9d" => "\x94",
       "\xe2\x80\xa2" => "\x95",
       "\xe2\x80\x93" => "\x96",
       "\xe2\x80\x94" => "\x97",
       "\xcb\x9c"     => "\x98",
       "\xe2\x84\xa2" => "\x99",
       "\xc5\xa1"     => "\x9a",
       "\xe2\x80\xba" => "\x9b",
       "\xc5\x93"     => "\x9c",
       "\xc5\xbe"     => "\x9e",
       "\xc5\xb8"     => "\x9f"
    );
  static function toUTF8($text){
  /**
   * Function \ForceUTF8\Encoding::toUTF8
   *
   * This function leaves UTF8 characters alone, while converting almost all non-UTF8 to UTF8.
   *
   * It assumes that the encoding of the original string is either Windows-1252 or ISO 8859-1.
   *
   * It may fail to convert characters to UTF-8 if they fall into one of these scenarios:
   *
   * 1) when any of these characters:   ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞß
   *    are followed by any of these:  ("group B")
   *                                    ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶•¸¹º»¼½¾¿
   * For example:   %ABREPRESENT%C9%BB. «REPRESENTÉ»
   * The "«" (%AB) character will be converted, but the "É" followed by "»" (%C9%BB)
   * is also a valid unicode character, and will be left unchanged.
   *
   * 2) when any of these: àáâãäåæçèéêëìíîï  are followed by TWO chars from group B,
   * 3) when any of these: ðñòó  are followed by THREE chars from group B.
   *
   * @name toUTF8
   * @param string $text  Any string.
   * @return string  The same string, UTF8 encoded
   *
   */
    if(is_array($text))
    {
      foreach($text as $k => $v)
      {
        $text[$k] = self::toUTF8($v);
      }
      return $text;
    } 
    
    if(!is_string($text)) {
      return $text;
    }
       
    $max = self::strlen($text);
  
    $buf = "";
    for($i = 0; $i < $max; $i++){
        $c1 = $text{$i};
        if($c1>="\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
          $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
          $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
          $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
            if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
                if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                    $buf .= $c1 . $c2;
                    $i++;
                } else { //not valid UTF8.  Convert it.
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = ($c1 & "\x3f") | "\x80";
                    $buf .= $cc1 . $cc2;
                }
            } elseif($c1 >= "\xe0" & $c1 <= "\xef"){ //looks like 3 bytes UTF8
                if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                    $buf .= $c1 . $c2 . $c3;
                    $i = $i + 2;
                } else { //not valid UTF8.  Convert it.
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = ($c1 & "\x3f") | "\x80";
                    $buf .= $cc1 . $cc2;
                }
            } elseif($c1 >= "\xf0" & $c1 <= "\xf7"){ //looks like 4 bytes UTF8
                if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
                    $buf .= $c1 . $c2 . $c3 . $c4;
                    $i = $i + 3;
                } else { //not valid UTF8.  Convert it.
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = ($c1 & "\x3f") | "\x80";
                    $buf .= $cc1 . $cc2;
                }
            } else { //doesn't look like UTF8, but should be converted
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = (($c1 & "\x3f") | "\x80");
                    $buf .= $cc1 . $cc2;
            }
        } elseif(($c1 & "\xc0") == "\x80"){ // needs conversion
              if(isset(self::$win1252ToUtf8[ord($c1)])) { //found in Windows-1252 special cases
                  $buf .= self::$win1252ToUtf8[ord($c1)];
              } else {
                $cc1 = (chr(ord($c1) / 64) | "\xc0");
                $cc2 = (($c1 & "\x3f") | "\x80");
                $buf .= $cc1 . $cc2;
              }
        } else { // it doesn't need conversion
            $buf .= $c1;
        }
    }
    return $buf;
  }
  static function toWin1252($text, $option = self::WITHOUT_ICONV) {
    if(is_array($text)) {
      foreach($text as $k => $v) {
        $text[$k] = self::toWin1252($v, $option);
      }
      return $text;
    } elseif(is_string($text)) {
      return static::utf8_decode($text, $option);
    } else {
      return $text;
    }
  }
  static function toISO8859($text) {
    return self::toWin1252($text);
  }
  static function toLatin1($text) {
    return self::toWin1252($text);
  }
  static function fixUTF8($text, $option = self::WITHOUT_ICONV){
    if(is_array($text)) {
      foreach($text as $k => $v) {
        $text[$k] = self::fixUTF8($v, $option);
      }
      return $text;
    }
    $last = "";
    while($last <> $text){
      $last = $text;
      $text = self::toUTF8(static::utf8_decode($text, $option));
    }
    $text = self::toUTF8(static::utf8_decode($text, $option));
    return $text;
  }
  static function UTF8FixWin1252Chars($text){
    // If you received an UTF-8 string that was converted from Windows-1252 as it was ISO8859-1
    // (ignoring Windows-1252 chars from 80 to 9F) use this function to fix it.
    // See: http://en.wikipedia.org/wiki/Windows-1252
    return str_replace(array_keys(self::$brokenUtf8ToUtf8), array_values(self::$brokenUtf8ToUtf8), $text);
  }
  static function removeBOM($str=""){
    if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
      $str=substr($str, 3);
    }
    return $str;
  }
  protected static function strlen($text){
    return (function_exists('mb_strlen') && ((int) ini_get('mbstring.func_overload')) & 2) ?
           mb_strlen($text,'8bit') : strlen($text);
  }
  public static function normalizeEncoding($encodingLabel)
  {
    $encoding = strtoupper($encodingLabel);
    $encoding = preg_replace('/[^a-zA-Z0-9\s]/', '', $encoding);
    $equivalences = array(
        'ISO88591' => 'ISO-8859-1',
        'ISO8859'  => 'ISO-8859-1',
        'ISO'      => 'ISO-8859-1',
        'LATIN1'   => 'ISO-8859-1',
        'LATIN'    => 'ISO-8859-1',
        'UTF8'     => 'UTF-8',
        'UTF'      => 'UTF-8',
        'WIN1252'  => 'ISO-8859-1',
        'WINDOWS1252' => 'ISO-8859-1'
    );
    if(empty($equivalences[$encoding])){
      return 'UTF-8';
    }
    return $equivalences[$encoding];
  }
  public static function encode($encodingLabel, $text)
  {
    $encodingLabel = self::normalizeEncoding($encodingLabel);
    if($encodingLabel == 'ISO-8859-1') return self::toLatin1($text);
    return self::toUTF8($text);
  }
  protected static function utf8_decode($text, $option)
  {
    if ($option == self::WITHOUT_ICONV || !function_exists('iconv')) {
       $o = utf8_decode(
         str_replace(array_keys(self::$utf8ToWin1252), array_values(self::$utf8ToWin1252), self::toUTF8($text))
       );
    } else {
       $o = iconv("UTF-8", "Windows-1252" . ($option == self::ICONV_TRANSLIT ? '//TRANSLIT' : ($option == self::ICONV_IGNORE ? '//IGNORE' : '')), $text);
    }
    return $o;
  }
}

$mysqli = mysqli_connect("localhost", "root", "", "ootdatabase");
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$res = mysqli_query($mysqli, "SELECT * FROM wp_postmeta WHERE meta_key = 'post_code'");
while($row = mysqli_fetch_assoc($res)) {
	
	$str = strip_tags( $row['meta_value'] );
	$str = strtoupper($str);
	//$str = Encoding::fixUTF8($str);
	echo $str;
	mysqli_query($mysqli, "UPDATE wp_postmeta SET meta_value =" . $str . "WHERE meta_key = 'post_code'");
	
	echo "<br />-------------------------------------------------------------<br />";
}

mysqli_close($mysqli);
?>