<?php

/*
* This class is for generating barcodes in diffrenct encoding symbologies.
* It supports EAN-13,EAN-8,UPC-A,UPC-E,ISBN ,2 of 5 Symbologies(std,ind,interleaved),postnet,
* codabar,code128,code39,code93 symbologies.
* 
* Author Kevin Waterson <kevin@phpro.org>
* 
* Requirements : PHP with GD library support. 
* 
* Reference : http://www.barcodeisland.com/symbolgy.phtml
*
* EXAMPLE
*        $bar= new barcode();
*        $barnumber="abcdef98765";
*        $bar->_encode   = 'CODE128';
*        $bar->_color    = '#ccc000';
*        $bar->_bgcolor  = '#FFFFFF';
*        $bar->_height   = 50;
*        $bar->_font     = '/usr/share/fonts/arial.ttf';
*        $bar->_scale    = 2;
*        $bar->_format   = 'gif';
*        try
*        {
*                // if filename is omitted, the image is returned as a string
*                // eg: $image_string = $bar->genBarCode( $barnumber, 'gif' );
*                $bar->genBarCode( $barnumber, 'gif', 'my_barcode.gif');
*        }
*        catch( Exception $e )
*        {
*                echo $e->getMessage()."\n";
*                echo $e->getLine()."\n";
*        }
*/
    
class barcode 
{
    /**
    *
    * Settor
    *
    * @access    public
    * @param    string    $name
    * @param    mixed    $value
    *
    */
    public function __set( $name, $value )
    {
        switch( $name )
        {
            case '_encode':
                $this->_encode=strtoupper( $value );
                break;
            case '_color':
            $this->_color = array( 
                    hexdec( substr( $value, 1, 2 ) ), 
                    hexdec( substr( $value, 3, 2 ) ), 
                    hexdec( substr( $value, 5, 2 ) ) 
                    );
                break;

            case '_bgcolor':
                $this->_bgcolor = array (
                    hexdec( substr( $value, 1, 2 ) ),
                    hexdec( substr( $value, 3, 2 ) ),
                    hexdec( substr( $value, 5, 2 ) )
                    );
                break;

            case '_width':
            case '_height':
            case '_scale':
            case '_font':
            case '_format':
            case '_n2w':
            case '_file':
            $this->$name = $value;
            break;

            default: throw new Exception( "Unable to set $name" );
        }
    }

    /**
    *
    * Settor
    *
    * @access       public
    * @param    string  $name
    * @param    mixed   $value
    *
    */
    public function get( $name )
    {
        switch( $name )
        {
            case '_encode':
            case '_width':
            case '_height':
            case '_scale':
            case '_color':
            case '_font':
            case '_bgcolor':
            case '_format':
            case '_n2w':
            return $this->$name;
            break;

            default: throw new Exception( "Unable to get $name" );
        }
    }

    /**
    *
    * constructor, duh!
    *
    * @access    public
    *
    */
    public function __construct( )
    {
    }

    /**
    *
    * Set the font
    *
    * @access    public
    * @param    string    $font
    *
    */
    public function setFont( $font, $autolocate=false )
    {
        $this->_font=$font;
        if($autolocate)
        {  
            $this->_font = dirname( $_SERVER['PATH_TRANSLATED'] ). '/' .$font.'.ttf';
        }
    }


    /**
    *
    * Set the narrow to wide format need by some codes
    *
    * @access    public
    * @param    int    $n2w
    *
    */
    public function setNarrow2Wide($n2w)
    {
        if( $n2w < 2 )
        {
            $n2w=3;
        }
        $this->_n2w = $n2w;
    }
        

    /**
    *
    * Generate the barcode from $barnumber
    * Selects the appropreate encoding and relevant method
    *
    * @access    public
    * @param    string    $barnumber
    * @param    string    $format
    * @param    string    $file
    *
    */
    public function genBarCode( $barnumber, $format="gif", $file="" )
    {
        switch( $this->_encode )
        {
            case 'EAN-13':
                if( strlen( $barnumber ) > 13 )
                {
                    throw new Exception( 'Barcode number must be less then 13 characters' );
                    return false;
                }
                return $this->_eanBarcode( $barnumber, $this->_scale, $file);
                break;

            case 'UPC-A':
                if(strlen($barnumber)>12)
                {
                    throw new Exception( 'Barcode number must be less then 13 characters.' );
                    return false;
                }
                return $this->_eanBarcode( $barnumber, $this->_scale, $file);
                break;

            case 'ISBN':
                if(strlen($barnumber)>13 || strlen($barnumber)<12)
                {
                    throw new Exception( 'Barcode number must be less then 13 characters.' );
                    return false;
                }
                elseif(substr($barnumber,0,3)!="978")
                {
                    throw new Exception( 'Not an ISBN barcode number. Must be start with 978' );
                    return false;
                }
                return $this->_eanBarcode( $barnumber, $this->_scale, $file );
                break;

            case 'EAN-8':
                if( strlen( $barnumber ) > 8 )
                {
                    throw new Exception( 'Barcode number must be less then 8 characters.' );
                    return false;
                }
                return $this->_ean8Barcode($barnumber,$this->_scale,$file);
                break;

            case 'UPC-E':
                if(strlen($barnumber)>12)
                {
                    throw new Exception( 'Barcode number must be less then 12 characters.' );
                    return false;
                }
                return $this->_upceBarcode($barnumber,$this->_scale,$file);
                break;

            case 'S205':
            case 'I2O5':
                 //STANDARD 2 OF 5 SYMBOLOGY OR INDUSTRIAL 2 OF 5
                return $this->_so25Barcode( $barnumber, $this->_scale, $file);
                break;

            case 'I25':
            case 'INTERLEAVED':
                //INTERLEAVED 2 OF 5
                return $this->_i25Barcode( $barnumber, $this->_scale, $file );
                break;

            case 'POSTNET':
                return $this->_postBarcode( $barnumber, $this->_scale, $file );
                break;

            case 'CODABAR':
                return $this->_codaBarcode( $barnumber, $this->_scale, $file );
                break;

            case 'CODE128':
                return $this->_c128Barcode( $barnumber, $this->_scale, $file );
                break;

            case 'CODE39':
                return $this->_c39Barcode( $barnumber, $this->_scale, $file, false );
                break;

            case 'CODE93':
                return $this->_c93Barcode( $barnumber, $this->_scale, $file );
                break;

            default:
                throw new Exception( $this->_encode.' not supported!' );
        }
    }
    
    /**
    *
    *  Function for code93
    *
    * A Code 39 barcode has the following structure:
    * A start character , represented below by the asterisk (*) character. 
    * Any number of characters encoded from the table below. 
    * The "C" and "K" checksum digits calculated as described above and encoded using the table below. 
    * A stop character, which is a second asterisk character. 
    *
    * @access    private
    * @param    string    $barnumber
    * @return    string
    *
    */
    private function _c93Encode( $barnumber )
    {
        $encTable=array("0" => "100010100",
                "1" => "101001000",
                "2" => "101000100",
                "3" => "101000010",
                "4" => "100101000",
                "5" => "100100100",
                "6" => "100100010",
                "7" => "101010000",
                "8" => "100010010",
                "9" => "100001010",
                "A" => "110101000",
                "B" => "110100100",
                "C" => "110100010",
                "D" => "110010100",
                "E" => "110010010",
                "F" => "110001010",
                "G" => "101101000",
                "H" => "101100100",
                "I" => "101100010",
                "J" => "100110100",
                "K" => "100011010",
                "L" => "101011000",
                "M" => "101001100",
                "N" => "101000110",
                "O" => "100101100",
                "P" => "100010110",
                "Q" => "110110100",
                "R" => "110110010",
                "S" => "110101100",
                "T" => "110100110",
                "U" => "110010110",
                "V" => "110011010",
                "W" => "101101100",
                "X" => "101100110",
                "Y" => "100110110",
                "Z" => "100111010",
                "-" => "100101110",
                "." => "111010100",
                " " => "111010010",
                "$" => "111001010",
                "/" => "101101110",
                "+" => "101110110",
                "%" => "110101110",
                "$" => "100100110",
                "%" => "111011010",
                "/" => "111010110",
                "+" => "100110010",
                "*" => "101011110"
                );

        $mfcStr="";
        $widebar=str_pad("",$this->_n2w,"1",STR_PAD_LEFT);
        $widespc=str_pad("",$this->_n2w,"0",STR_PAD_LEFT);
        
        $arr_key=array_keys($encTable);
        /// calculating C And K

        for($j=0;$j<2;$j++)
        {
            $sum=0;
            for($i=strlen($barnumber);$i>0;$i--)
            {
                $num=$barnumber[strlen($barnumber)-$i];
                if(preg_match("/[A-Z]+/",$num))
                    $num=ord($num)-55;
                elseif($num=='-')
                    $num=36;
                elseif($num=='.')
                    $num=37;
                elseif($num==' ')
                    $num=38;
                elseif($num=='$')
                    $num=39;
                elseif($num=='/')
                    $num=40;
                elseif($num=='+')
                    $num=41;
                elseif($num=='%')
                    $num=42;
                elseif($num=='*')
                    $num=43;
                
                $sum+=$i*$num;    
            }    
            $barnumber.=trim($arr_key[(int)($sum % 47)]);
        }

        $barnumber="*".$barnumber."*";
        
        for($i=0;$i<strlen($barnumber);$i++)
        {
            $mfcStr.=$encTable[$barnumber[$i]];
        }
        $mfcStr.='1';
        
        return $mfcStr;
    }

    /**
    * 93 Barcodes
    *
    * @access    private
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    $string    $file
    * @param    string    $checkdigit
    * @return    string
    *
    */
    private function _c93Barcode( $barnumber, $scale=1, $file="", $checkdigit=false )
    {
        $bars=$this->_c93Encode($barnumber);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height+10*$scale;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars)+2*$scale*10; 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left']+$scale*10;

        $height=floor($total_y-($scale*20));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=$bars[$i];

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        
        $font_arr=imagettfbbox ( $scale*10, 0, $this->_font, $barnumber);
        $x= floor($total_x-(int)$font_arr[0]-(int)$font_arr[2]+$scale*10)/2;    
        imagettftext($im,$scale*10,0,$x, $height2, $bar_color,$this->_font , $barnumber);

        return $this->write( $im, $file );    
    }

    /**
    * A Code 39 barcode has the following structure:
    * A start character - the asterisk (*) character. 
    * Any number of characters encoded from the table below. 
    * An optional checksum digit calculated as described above and encoded from the table below. 
    * A stop character, which is a second asterisk character.
    *
    * @access private
    * @param    string    $barnumber
    * @param    bool    $checkdigit
    * @return    string
    *
    */
    public function _c39Encode($barnumber,$checkdigit=false)
    {
        $encTable=array("0" => "NNNWWNWNN",
                "1" => "WNNWNNNNW",
                "2" => "NNWWNNNNW",
                "3" => "WNWWNNNNN",
                "4" => "NNNWWNNNW",
                "5" => "WNNWWNNNN",
                "6" => "NNWWWNNNN",
                "7" => "NNNWNNWNW",
                "8" => "WNNWNNWNN",
                "9" => "NNWWNNWNN",
                "A" => "NNWWNNWNN",
                "B" => "NNWNNWNNW",
                "C" => "WNWNNWNNN",
                "D" => "NNNNWWNNW",
                "E" => "WNNNWWNNN",
                "F" => "NNWNWWNNN",
                "G" => "NNNNNWWNW",
                "H" => "WNNNNWWNN",
                "I" => "NNWNNWWNN",
                "J" => "NNNNWWWNN",
                "K" => "WNNNNNNWW",
                "L" => "NNWNNNNWW",
                "M" => "WNWNNNNWN",
                "N" => "NNNNWNNWW",
                "O" => "WNNNWNNWN",
                "P" => "NNWNWNNWN",
                "Q" => "NNNNNNWWW",
                "R" => "WNNNNNWWN",
                "S" => "NNWNNNWWN",
                "T" => "NNNNWNWWN",
                "U" => "WWNNNNNNW",
                "V" => "NWWNNNNNW",
                "W" => "WWWNNNNNN",
                "X" => "NWNNWNNNW",
                "Y" => "WWNNWNNNN",
                "Z" => "NWWNWNNNN",
                "-" => "NWNNNNWNW",
                "." => "WWNNNNWNN",
                " " => "NWWNNNWNN",
                "$" => "NWNWNWNNN",
                "/" => "NWNWNNNWN",
                "+" => "NWNNNWNWN",
                "%" => "NNNWNWNWN",
                "*" => "NWNNWNWNN"
            );

        $mfcStr="";
        $widebar=str_pad("",$this->_n2w,"1",STR_PAD_LEFT);
        $widespc=str_pad("",$this->_n2w,"0",STR_PAD_LEFT);
        
        if($checkdigit==true)
        {
            $arr_key=array_keys($encTable);
            for($i=0;$i<strlen($barnumber);$i++)
            {
                $num=$barnumber[$i];
                if(preg_match("/[A-Z]+/",$num))
                    $num=ord($num)-55;
                elseif($num=='-')
                    $num=36;
                elseif($num=='.')
                    $num=37;
                elseif($num==' ')
                    $num=38;
                elseif($num=='$')
                    $num=39;
                elseif($num=='/')
                    $num=40;
                elseif($num=='+')
                    $num=41;
                elseif($num=='%')
                    $num=42;
                elseif($num=='*')
                    $num=43;
                $sum+=$num;    
            }    
            $barnumber.=trim($arr_key[(int)($sum % 43)]);
        }

        $barnumber="*".$barnumber."*";
        
        for($i=0;$i<strlen($barnumber);$i++)
        {
            $tmp=$encTable[$barnumber[$i]];

            $bar =true;
            
            for($j=0;$j<strlen($tmp);$j++)
            {
                if($tmp[$j]=='N' && $bar)
                    $mfcStr.='1';
                else if($tmp[$j]=='N' && !$bar)
                    $mfcStr.='0';
                else if($tmp[$j]=='W' && $bar)
                    $mfcStr.=$widebar;
                else if($tmp[$j]=='W' && !$bar)
                    $mfcStr.=$widespc;
                $bar = !$bar;
            }
            $mfcStr.='0';
        }
        return $mfcStr;
    }

    /**
    *
    * 39Barcode
    *
    * @access     private
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $file
    * @param    bool    $checkdigit
    * @return    string
    *
    */
    private function _c39Barcode($barnumber,$scale=1,$file="",$checkdigit=false)
    {
        $bars=$this->_c39Encode($barnumber,$checkdigit);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height+10*$scale;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars)+2*$scale*10; 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left']+$scale*10;

        $height=floor($total_y-($scale*20));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=$bars[$i];

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        
        $font_arr=imagettfbbox ( $scale*10, 0, $this->_font, $barnumber);
        $x= floor($total_x-(int)$font_arr[0]-(int)$font_arr[2]+$scale*10)/2;    
        imagettftext($im,$scale*10,0,$x, $height2, $bar_color,$this->_font , $barnumber);

        return $this->write( $im, $file );
    }
    
    /**
    *
    * Start function for code128
    *
    * @access     private
    * @param    string    $barnumber
    * @param    string    $useKeys
    * @return    string
    *
    */
    public function _c128Encode($barnumber,$useKeys)
    {
        $encTable=array("11011001100","11001101100","11001100110","10010011000","10010001100","10001001100","10011001000","10011000100","10001100100","11001001000","11001000100","11000100100","10110011100","10011011100","10011001110","10111001100","10011101100","10011100110","11001110010","11001011100","11001001110","11011100100","11001110100","11101101110","11101001100","11100101100","11100100110","11101100100","11100110100","11100110010","11011011000","11011000110","11000110110","10100011000","10001011000","10001000110","10110001000","10001101000","10001100010","11010001000","11000101000","11000100010","10110111000","10110001110","10001101110","10111011000","10111000110","10001110110","11101110110","11010001110","11000101110","11011101000","11011100010","11011101110","11101011000","11101000110","11100010110","11101101000","11101100010","11100011010","11101111010","11001000010","11110001010","10100110000","10100001100","10010110000","10010000110","10000101100","10000100110","10110010000","10110000100","10011010000","10011000010","10000110100","10000110010","11000010010","11001010000","11110111010","11000010100","10001111010","10100111100","10010111100","10010011110","10111100100","10011110100","10011110010","11110100100","11110010100","11110010010","11011011110","11011110110","11110110110","10101111000","10100011110","10001011110","10111101000","10111100010","11110101000","11110100010","10111011110","10111101110","11101011110","11110101110","11010000100","11010010000","11010011100","11000111010");

        $start=array("A"=>"11010000100","B"=>"11010010000","C"=>"11010011100");
        $stop="11000111010";

        $sum=0;
        $mfcStr="";
        if($useKeys=='C')
        {
            for($i=0;$i<strlen($barnumber);$i+=2)
            { 
                $val=substr($barnumber,$i,2);
                if(is_int($val))
                    $sum+=($i+1)*(int)($val);
                elseif($barnumber==chr(129))
                    $sum+=($i+1)*100;
                elseif($barnumber==chr(130))
                    $sum+=($i+1)*101;
                $mfcStr.=$encTable[$val];
            }
        }
        else
        {
            for($i=0;$i<strlen($barnumber);$i++)
            { 
                $num=ord($barnumber[$i]);
                if($num>=32 && $num<=126)
                    $num=ord($barnumber[$i])-32;
                elseif($num==128)
                $num=99;
                elseif($num==129)
                    $num=100;
                elseif($num==130)
                    $num=101;
                elseif($num<32 && $useKeys=='A')
                    $num=$num+64;
                $sum+=($i+1)*$num;
                $mfcStr.=$encTable[$num];
            }
        }

        if($useKeys=='A')
            $check=($sum+103)%103;
        if($useKeys=='B')
            $check=($sum+104)%103;
        if($useKeys=='C')
            $check=($sum+105)%103;

        return $start[$useKeys].$mfcStr.$encTable[$check].$stop."11";
    }


    /**
    *
    * 128 Barcode
    *
    * @access    public
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $file    The output filename
    * @see        _c128Encode()
    *
    */
    public function _c128Barcode( $barnumber, $scale=1, $file="" )
    {
        $useKeys="B";
        if( preg_match( "/^[0-9".chr(128).chr(129).chr(130)."]+$/", $barnumber ) )
        {
            $useKeys='C';
            if( strlen( $barnumber ) %2 != 0 )
            {
                $barnumber='0'.$barnumber;
            }
        }
        
        for($i=0;$i<32;$i++)
        {
            $chr=chr($i);
        }
        if(preg_match("/[".$chr."]+/",$barnumber))
        {
            $useKeys='A';
        }

        $bars=$this->_c128Encode($barnumber,$useKeys);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height+10*$scale;
        // if (!$space)
        $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars)+2*$scale*10; 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left']+$scale*10;

        $height=floor($total_y-($scale*20));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);

        // imagecolorallocate ( resource $image , int $red , int $green , int $blue )
        $bg_color = imagecolorallocate( $im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2] );
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate( $im, $this->_color[0], $this->_color[1], $this->_color[2] );

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=strtoupper( $bars[$i] );

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        if( !function_exists( 'imagettfbbox' ) )
        {
            throw new exception( "FreeType extension must be installed\n" );
        }
        else
        {
            $font_arr = imagettfbbox ( $scale*10, 0, $this->_font, $barnumber);
        }

        $x= floor($total_x-(int)$font_arr[0]-(int)$font_arr[2]+$scale*10)/2;    
        imagettftext($im,$scale*10,0,$x, $height2, $bar_color,$this->_font , $barnumber);

        return $this->write( $im, $file );
    }
    

    /**
    * A Code 11 Barcode has the following structure:
    * One of four possible start characters (A, B, C, or D), encoded from the table below. 
    * A narrow, inter-character space. 
    * The data of the message, encoded from the table below, with a narrow inter-character space between each character. 
    * One of four possible stop characters (A, B, C, or D), encoded from the table below
    * 
    * @access     private
    * @para        string    $barnumber
    * @return    string
    */
    private function _codaEncode($barnumber)
    {
        $encTable=array("0000011","0000110","0001001","1100000","0010010","1000010","0100001","0100100","0110000","1001000");
        $chrTable=array("-" => "0001100","$" => "0011000",":" => "1000101","/" => "1010001","." => "1010100", "+" => "0011111","A" => "0011010","B" => "0001011","C" => "0101001","D" => "0001110");

        $mfcStr="";
            
        $widebar=str_pad("",$this->_n2w,"1",STR_PAD_LEFT);
        $widespc=str_pad("",$this->_n2w,"0",STR_PAD_LEFT);
            
        for($i=0;$i<strlen($barnumber);$i++)
        {
            if(preg_match("/[0-9]+/",$barnumber[$i]))
                $tmp=$encTable[(int)$barnumber[$i]];
            else
                $tmp=$chrTable[strtoupper(trim($barnumber[$i]))];

            $bar =true;
            
            for($j=0;$j<strlen($tmp);$j++)
            {
                if($tmp[$j]=='0' && $bar)
                    $mfcStr.='1';
                else if($tmp[$j]=='0' && !$bar)
                    $mfcStr.='0';
                else if($tmp[$j]=='1' && $bar)
                    $mfcStr.=$widebar;
                else if($tmp[$j]=='1' && !$bar)
                    $mfcStr.=$widespc;

                $bar = !$bar;
            }
            $mfcStr.='0';
        }
        
        return $mfcStr;
    }

    /**
    *
    * Coda Barcode
    *
    * @access    private
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $file
    * @return    string
    *
    */
    public function _codaBarcode($barnumber,$scale=1,$file="")
    {
        $bars=$this->_codaEncode($barnumber);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars); 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left'];

        $height=floor($total_y-($scale*10));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=strtoupper($bars[$i]);

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        

        $x= ($total_x-strlen($bars))/2;    
        imagettftext($im,$scale*6,0,$x, $height2, $bar_color,$this->_font , $barnumber);

        return $this->write( $im, $file );    
    }


    /**
    *
    * A PostNet barcode has the following structure:
    * Frame bar, encoded as a single 1. 
    * 5, 9, or 11 data characters properly encoded (see encoding table below). 
    * Check digit, encoded using encoding table below. 
    * Final frame bar, encoded as a single 1. 
    * 0         11000 
    * 1         00011 
    * 2         00101 
    * 3         00110 
    * 4         01001 
    * 5         01010 
    * 6         01100 
    * 7         10001 
    * 8         10010 
    * 9         10100 
    *
    * @access    private
    * @param    string    $barnumber
    * @return    string
    *
    */
    private function _postEncode( $barnumber )
    {
        $encTable=array("11000","00011","00101","00110","01001","01010","01100","10001","10010","10100");

        $sum=0;
        $encstr="";
        for($i=0;$i<strlen($barnumber);$i++)
        {
            $sum+=(int)$barnumber[$i];
            $encstr.=$encTable[(int)$barnumber[$i]];
        }
        if($sum%10!=0)
            $check=(int)(10-($sum%10));
        
        $encstr.=$encTable[$check];
        $encstr="1".$encstr."1";
        return $encstr;
    }

    public function _postBarcode($barnumber,$scale=1,$file="")
    {
        if(strlen($barnumber)==5 || strlen($barnumber)==9 || strlen($barnumber)==11)
            ;
        else
        {
            throw new Exception( "Not a valid postnet number." );
            return false;
        }

        $bars=$this->_postEncode($barnumber);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars)*2; 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left'];

        $height=floor($total_y-($scale*10));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $val=strtoupper($bars[$i]);
            $h=$total_y-$space['bottom'];

            if($val==1)
                imagefilledrectangle($im,$xpos,$space['top'],$xpos+$scale-1,$height2 , $bar_color);
            else
                imagefilledrectangle($im,$xpos,floor($height2/1.5) ,$xpos+$scale-1, $height2,$bar_color);
            $xpos+=2*$scale;
        }
        
        return $this->write( $im, $file );
    }

        
    /**
    *
    * A Standard 2 of 5 barcode has the following physical structure:
    * Start character, encoded as 11011010. 
    * Data characters properly encoded (see encoding table below). 
    * Stop character, encoded as 11010110. 
    *
    * ASCII    BARCODE
    * 0         NNWWN 
    * 1         WNNNW 
    * 2         NWNNW 
    * 3         WWNNN 
    * 4         NNWNW 
    * 5         WNWNN 
    * 6         NWWNN 
    * 7         NNNWW 
    * 8         WNNWN 
    * 9         NWNWN 
    *
    * @access    private
    * @param    string    $barnumber
    * @return    string
    *
    */
    private function _i25Encode( $barnumber )
    {
        $encTable=array("NNWWN","WNNNW","NWNNW","WWNNN","NNWNW","WNWNN","NWWNN","NNNWW","WNNWN","NWNWN");
        $guards=array("1010","1101");

        $len=strlen($barnumber);
        if($len % 2!=0)
        {
            $barnumber=$this->_checkDigit($barnumber,$len);
            if($len==strlen($barnumber) && substr($barnumber,-1)!='0')
                $barnumber.='0';
        }
        
        $mfcStr="";
        
        $widebar=str_pad("",$this->_n2w,"1",STR_PAD_LEFT);
        $widespc=str_pad("",$this->_n2w,"0",STR_PAD_LEFT);
        
        for($i=0;$i<strlen($barnumber);$i+=2)
        {
            $tmp=$encTable[(int)$barnumber[$i]];
            $tmp1=$encTable[(int)$barnumber[$i+1]];
            for($j=0;$j<strlen($tmp);$j++)
            {
                if($tmp[$j]=='N')
                    $mfcStr.='1';
                else
                    $mfcStr.=$widebar;

                if($tmp1[$j]=='N')
                    $mfcStr.='0';
                else
                    $mfcStr.=$widespc;
            }
        }
        
        return $guards[0].$mfcStr.$guards[1];
    }

    /**
    *
    * i25 Barcodes
    *
    * @access    private
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $file
    * @return    string
    *
    */
    private function _i25Barcode( $barnumber, $scale=1, $file="" )
    {
        $bars=$this->_i25Encode($barnumber);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars); 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left'];

        $height=floor($total_y-($scale*10));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=strtoupper($bars[$i]);

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        
        

        $x= ($total_x-strlen($bars))/2;    
        imagettftext($im,$scale*6,0,$x, $height2, $bar_color,$this->_font , $barnumber);

        return $this->write( $im, $file );
    }
    
    /**
    *
    * Function for S2O5
    * A Standard 2 of 5 barcode has the following physical structure:
    * Start character, encoded as 11011010. 
    * Data characters properly encoded (see encoding table below). 
    * Stop character, encoded as 11010110. 
    *
    * ASCII    BARCODE
    * 0         NNWWN 
    * 1         WNNNW 
    * 2         NWNNW 
    * 3         WWNNN 
    * 4         NNWNW 
    * 5         WNWNN 
    * 6         NWWNN 
    * 7         NNNWW 
    * 8         WNNWN 
    * 9         NWNWN 
    *
    * @access    private
    * @param    string    $barnumber
    * @return    string
    *
    */

    public function _so25Encode($barnumber)
    {
        $encTable=array("NNWWN","WNNNW","NWNNW","WWNNN","NNWNW","WNWNN","NWWNN","NNNWW","WNNWN","NWNWN");
        $guards=array("11011010","1101011");

        $len=strlen($barnumber);
        $barnumber=$this->_checkDigit($barnumber,$len);
        if($len==strlen($barnumber) && substr($barnumber,-1)!='0')
            $barnumber.='0';
        
        $mfcStr="";
        
        $widebar=str_pad("",$this->_n2w,"1",STR_PAD_LEFT);
        $widebar.="0";
        
        for($i=0;$i<strlen($barnumber);$i++)
        {
            $num=(int)$barnumber{$i};
            $str="";
            $str=str_replace("N","10",$encTable[$num]);
            $str=str_replace("W",$widebar,$str);
            $mfcStr.=$str;
        }
        
        return $guards[0].$mfcStr.$guards[1];
    }

    /**
    *
    * so25 Barcodes
    *
    * @access private
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $file
    * @return    string
    *
    */
    private function _so25Barcode($barnumber,$scale=1,$file="")
    {
        $bars=$this->_so25Encode($barnumber);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars); 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left'];

        $height=floor($total_y-($scale*10));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=strtoupper($bars[$i]);

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        
        

        $x= ($total_x-strlen($bars))/2;    
        imagettftext($im,$scale*6,0,$x, $height2, $bar_color,$this->_font , $barnumber);

        return $this->write( $im, $file );
    }
        
        
    /**
    *
    * Functions to conver UCPA to UPCE Encoding
    *
    * @access    private
    * @param    string    $upca
    * @return    string
    * @see         _upceEncode()
    *
    */
    private function ConvertUPCAtoUPCE( $upca )
    {
         $csumTotal = 0; // The checksum working variable starts at zero
         $upce ="";
         // If the source message string is less than 12 characters long, we make it 12 characters

         if( strlen($upca) < 12 )
         {
            $barnumber = str_pad($barnumber, 12, "0", STR_PAD_LEFT);  
         }

         if( substr($upca,0,1) != '0' && substr($upca,0,1) != '1')
         {
             throw new Exception( 'Invalid Number System (only 0 & 1 are valid)' );
             return false;
         }
         else
         {
              if( substr($upca,3,3) == '000' || substr($upca,3,3) == '100' ||  substr($upca,3,3) == '200' )
                $upce = substr($upca,1,2) . substr($upca,8,3) . substr($upca,3,1);
              else if( substr($upca,4,2) == '00' )
                $upce = substr($upca,1,2) . substr($upca,9,2) . '3';
              else if( substr($upca,5,1) == '0' )
                $upce = substr($upca,1,4) . substr($upca,10,1) . '4';
              else if( substr($upca,10,1) >= '5' )
                $upce = substr($upca,1,5) . substr($upca,10,1);
              else
              {
                   throw new Exception( 'Invalid product code (00005 to 00009 are valid)' );
                   return false;
              }
         }
         return $upce;
    }

    /**
    *
    * Encode UPCE
    *
    * @access    private
    * @param    string    $barnumber
    * @param    int    encbit
    * @param    string    $checkdigit
    * @return    string
    *
    */    
    private function _upceEncode( $barnumber, $encbit, $checkdigit)
    {
        $leftOdd=array("0001101","0011001","0010011","0111101","0100011","0110001","0101111","0111011","0110111","0001011");
        $leftEven=array("0100111","0110011","0011011","0100001","0011101","0111001","0000101","0010001","0001001","0010111");

        $encTable0=array("EEEOOO","EEOEOO","EEOOEO","EEOOOE","EOEEOO","EOOEEO","EOOOEE","EOEOEO","EOEOOE","EOOEOE");
        $encTable1=array("OOOEEE","OOEOEE","OOEEOE","OOEEEO","OEOOEE","OEEOOE","OEEEOO","OEOEOE","OEOEEO","OEEOEO");
            
        $guards=array("bab","ababa","b");


        if($encbit==0)
            $encTable=$encTable0;
        elseif($encbit==1)
            $encTable=$encTable1;
        else
        {
            throw new Exception( "Not an UPC-E barcode number" );
            return false;
        }

        $mfcStr="";
        $prodStr="";
        $checkdigit;
        $encTable[$checkdigit];

        for($i=0;$i<strlen($barnumber);$i++)
        {
            $num=(int)$barnumber{$i};
            $even=(substr($encTable[$checkdigit],$i,1)=='E');
            if(!$even)
                $mfcStr.=$leftOdd[$num];
            else
                $mfcStr.=$leftEven[$num];
        }
        return $guards[0].$mfcStr.$guards[1].$guards[2];
    }

    /**
    *
    * UPCE barcodes
    *
    * @access    private
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $vile
    * @return    string
    *
    */
    public function _upceBarcode($barnumber,$scale=1,$file="")
    {
        if(strlen($barnumber)>6)
        {    
            $this->_ean13CheckDigit($barnumber);
            $barnumber=substr($this->_ean13CheckDigit($barnumber),1);
            $encbit=$barnumber[0];
            $checkdigit=$barnumber[11];
            $barnumber=$this->ConvertUPCAtoUPCE($barnumber);
        }
        else
        {
            $barnumber=$this->_checkDigit($barnumber,7);
            $encbit=$barnumber[0];
            $checkdigit=$barnumber[7];
            $barnumber=substr($barnumber,1,6);
        }

        $bars=$this->_upceEncode($barnumber,$encbit,$checkdigit);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars)+$scale*12; 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left']+($scale*6);

        $height=floor($total_y-($scale*10));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=strtoupper($bars[$i]);
            if(preg_match("/[a-z]/i",$val))
            {
                $val=ord($val)-65;
                $h=$height2;
            }

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        
        

        imagettftext($im,$scale*6,0, $space['left'], $height, $bar_color,$this->_font , $encbit);

        
        $x= $space['left']+$scale*strlen($barnumber)+$scale*6;    
        imagettftext($im,$scale*6,0,$x, $height2, $bar_color,$this->_font , $barnumber);

        $x=$total_x-$space['left']-$scale*6;
        imagettftext($im,$scale*6,0, $x, $height, $bar_color,$this->_font , $checkdigit);

        return $this->write( $im, $file );    
    }
    
    /**
    *
    * Function for EAN-8 Encoding
    * 
    * @access    private
    * @param    string    $barnumber
    * @param    int    $number
    * @return    int
    *
    */
    private function _checkDigit($barnumber,$number)
    {
         $csumTotal = 0; // The checksum working variable starts at zero

         // If the source message string is less than 12 characters long, we make it 12 characters
        if(strlen($barnumber) < $number)
        {
            $barnumber = str_pad( $barnumber, $number, "0", STR_PAD_LEFT );  
        }
          
        // Calculate the checksum value for the message
        
        for($i=0;$i<strlen($barnumber);$i++) 
        {
            if($i % 2 == 0 )
            {
                $csumTotal = $csumTotal + (3 * intval($barnumber{$i}));
            }
            else
            {
                $csumTotal = $csumTotal + intval($barnumber{$i});
            }
        }

        // Calculate the checksum digit
        //echo $csumTotal;
         if( $csumTotal % 10 == 0 )
        {
            $checksumDigit = '';
        }
        else
        {
            $checksumDigit = 10 - ($csumTotal % 10);
        }
         return $barnumber.$checksumDigit;
    }

    /**
    *
    * An EAN-8 barcode has the following physical structure:
    * Left-hand guard bars, or start sentinel, encoded as 101. 
    * Two number system characters, encoded as left-hand odd-parity characters. 
    * First two message characters, encoded as left-hand odd-parity characters. 
    * Center guard bars, encoded as 01010. 
    * Last three message characters, encoded as right-hand characters. 
    * Check digit, encoded as right-hand character. 
    * Right-hand guar bars, or end sentinel, encoded as 101.
    *
    * @access    private
    * @param    string    $barnumber
    * @return    string
    *
    */
    private function _ean8Encode( $barnumber )
    {
        $leftOdd=array("0001101","0011001","0010011","0111101","0100011","0110001","0101111","0111011","0110111","0001011");
        $leftEven=array("0100111","0110011","0011011","0100001","0011101","0111001","0000101","0010001","0001001","0010111");
        $rightAll=array("1110010","1100110","1101100","1000010","1011100","1001110","1010000","1000100","1001000","1110100");

        $encTable=array("000000","001011","001101","001110","010011","011001","011100","010101","010110","011010");
        
        $guards=array("bab","ababa","bab");

        $mfcStr="";
        $prodStr="";
        
        for($i=0;$i<strlen($barnumber);$i++)
        {
            $num=(int)$barnumber{$i};
            if($i<4) 
            {
                $mfcStr.=$leftOdd[$num];
            }
            elseif($i>=4)
            {
                $prodStr.=$rightAll[$num];
            }

        }

        return $guards[0].$mfcStr.$guards[1].$prodStr.$guards[2];
    }

    /**
    *
    * EAN8 Barcodes
    * 
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $file
    * @return    string
    *
    */
    public function _ean8Barcode($barnumber,$scale=1,$file="")
    {
        $barnumber=$this->_checkDigit($barnumber,7);
        $bars=$this->_ean8Encode($barnumber);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*strlen($bars); 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left'];

        $height=floor($total_y-($scale*10));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=strtoupper($bars[$i]);
            if(preg_match("/[a-z]/i",$val))
            {
                $val=ord($val)-65;
                $h=$height2;
            }

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }

        $str=substr($barnumber,0,4);
        $x= $space['left']+$scale*strlen($barnumber);    
        imagettftext($im,$scale*6,0,$x, $height2, $bar_color,$this->_font , $str);

        $str=substr($barnumber,4,4);
        $x=$space['left']+$scale*strlen($bars)/1.65;
        imagettftext($im,$scale*6,0, $x, $height2, $bar_color,$this->_font ,$str);

        return $this->write( $im, $file );
    }
        
    /**
    *
    * Functions for EAN-13 Encoding
    *
    * @access    private
    * @param    string    $barnumber
    * @return    string
    *
    */
    private function _ean13CheckDigit( $barnumber )
    {
         $csumTotal = 0; // The checksum working variable starts at zero

         // If the source message string is less than 12 characters long, we make it 12 characters
         if(strlen($barnumber) <= 12 )
         {
            $barnumber = str_pad($barnumber, 13, "0", STR_PAD_LEFT);  
         }
          
         /*if(strlen($barnumber) == 13)
            $barnumber = substr($barnumber,0,12);*/

        // Calculate the checksum value for the message
        for($i=0;$i<strlen($barnumber);$i++) 
        {
            if($i % 2 == 0 )
            {        
                $csumTotal = $csumTotal + intval($barnumber{$i});
            }
            else
            {
                $csumTotal = $csumTotal + (3 * intval($barnumber{$i}));
            }
        }

         // Calculate the checksum digit
        if( $csumTotal % 10 == 0 )
        {
            $checksumDigit = '';
        }
        else
        {
            $checksumDigit = 10 - ($csumTotal % 10);
        }
         return $barnumber.$checksumDigit;
    }

    /**
    *
    * An EAN-13 barcode has the following physical structure:
    * Left-hand guard bars, or start sentinel, encoded as 101. 
    * The second character of the number system code, encoded as described below. 
    * The five characters of the manufacturer code, encoded as described below. 
    * Center guard pattern, encoded as 01010. 
    * The five characters of the product code, encoded as right-hand characters, described below. 
    * Check digit, encoded as a right-hand character, described below. 
    * Right-hand guard bars, or end sentinel, encoded as 101. 
    * FIRST NUMBER
    *
    * SYSTEM DIGIT PARITY TO ENCODE WITH 
    *        SECOND NUMBER
    *    SYSTEM DIGIT MANUFACTURER CODE CHARACTERS 
    *    1    2    3     4    5 
    * 0 (UPC-A)    Odd    Odd    Odd    Odd    Odd    Odd 
    * 1            Odd Odd Even Odd Even Even 
    * 2            Odd Odd Even Even Odd Even 
    * 3            Odd Odd Even Even Even Odd 
    * 4            Odd Even Odd Odd Even Even 
    * 5            Odd Even Even Odd Odd Even 
    * 6            Odd Even Even Even Odd Odd 
    * 7            Odd Even Odd Even Odd Even 
    * 8            Odd Even Odd Even Even Odd 
    * 9            Odd Even Even Odd Even Odd 
    *
    * @access    private
    * @param    string    $barnumber
    * @return    string
    *
    */
    private function _eanEncode( $barnumber )
    {
        $leftOdd=array("0001101","0011001","0010011","0111101","0100011","0110001","0101111","0111011","0110111","0001011");
        $leftEven=array("0100111","0110011","0011011","0100001","0011101","0111001","0000101","0010001","0001001","0010111");
        $rightAll=array("1110010","1100110","1101100","1000010","1011100","1001110","1010000","1000100","1001000","1110100");

        $encTable=array("000000","001011","001101","001110","010011","011001","011100","010101","010110","011010");
        
        $guards=array("bab","ababa","bab");

        $mfcStr="";
        $prodStr="";
        
        $encbit=$barnumber[0];

        for($i=1;$i<strlen($barnumber);$i++)
        {
            $num=(int)$barnumber{$i};
            if($i<7) 
            {
                $even=(substr($encTable[$encbit],$i-1,1)==1);
                if(!$even)
                    $mfcStr.=$leftOdd[$num];
                else
                    $mfcStr.=$leftEven[$num];
            }
            elseif($i>=7)
            {
                $prodStr.=$rightAll[$num];
            }
        }
        return $guards[0].$mfcStr.$guards[1].$prodStr.$guards[2];
    }
    
    /**
    *
    * EAN Barcodes
    *
    * @access    private
    * @param    string    $barnumber
    * @param    int    $scale
    * @param    string    $file
    * @return    string
    *
    */
    public function _eanBarcode($barnumber,$scale=1,$file="")
    {
        $barnumber=$this->_ean13CheckDigit($barnumber);

        $bars=$this->_eanEncode($barnumber);

        if ($scale<1) $scale=2;
        $total_y=(double)$scale * $this->_height;
        if (!$space)
          $space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
        
        /* count total width */
        $xpos=0;
        
        $xpos=$scale*(114); 

        /* allocate the image */
        $total_x= $xpos +$space['left']+$space['right'];
        $xpos=$space['left']+($scale*6);

        $height=floor($total_y-($scale*10));
        $height2=floor($total_y-$space['bottom']);
    
        $im=imagecreatetruecolor($total_x, $total_y);
        $bg_color = imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
        imagefilledrectangle($im,0,0,$total_x,$total_y,$bg_color); 
        $bar_color = imagecolorallocate($im, $this->_color[0], $this->_color[1],$this->_color[2]);

        for($i=0;$i<strlen($bars);$i++)
        {
            $h=$height;
            $val=strtoupper($bars[$i]);
            if(preg_match("/[a-z]/i",$val))
            {
                $val=ord($val)-65;
                $h=$height2;
            }
            if($this->_encode=="UPC-A" && ($i<10 || $i>strlen($bars)-13))
                $h=$height2;

            if($val==1)
                imagefilledrectangle($im,$xpos, $space['top'],$xpos+$scale-1, $h,$bar_color);
            $xpos+=$scale;
        }
        
        
        if($this->_encode=="UPC-A")
            $str=substr($barnumber,1,1);
        else
            $str=substr($barnumber,0,1);

        imagettftext($im,$scale*6,0, $space['left'], $height, $bar_color,$this->_font , $str);

        if($this->_encode=="UPC-A")
            $str=substr($barnumber,2,5);
        else
            $str=substr($barnumber,1,6);
        
        $x= $space['left']+$scale*strlen($barnumber)+$scale*6;    
        imagettftext($im,$scale*6,0,$x, $height2, $bar_color,$this->_font , $str);
        
        if($this->_encode=="UPC-A")
            $str=substr($barnumber,7,5);
        else
            $str=substr($barnumber,7,6);
        $x=$space['left']+$scale*strlen($bars)/1.65+$scale*6;
        imagettftext($im,$scale*6,0, $x, $height2, $bar_color,$this->_font ,$str);

        if($this->_encode=="UPC-A")
        {
            $str=substr($barnumber,12,1);
            $x=$total_x-$space['left']-$scale*6;
            imagettftext($im,$scale*6,0, $x, $height, $bar_color,$this->_font , $str);
        }

        return $this->write( $im, $file );
    }


    /**
    *
    * Write image to file
    *
    * @access    private
    * @param    resource    $im
    * @param    string        $file
    * @return    bool or string if $file is empty
    *
    */
    private function write( $im, $file )
    {
        if( empty( $file ) )
        {
            return $this->getImageAsString( $im );
        }
        else
        {
            switch( $this->_format )
            {
                case 'jpg':
                case 'jpeg':
                    return imagejpeg( $im, $file );
                break;
    
                case 'png':
                    return imagepng( $im, $file );
                break;

                case 'gif':
                    return imagegif( $im, $file );
                break;

                default: throw new Exception( "Image type ($image_type) not supported" );
            }
            imagedestroy( $im );
        }
    }

    /**
    *
    * Get image as a string
    *
    * @access    public
    * @param    resource    $im
    * return    string
    *
    */
    public function getImageAsString( $im )
    {
        ob_start();
        switch( $this->_format )
        {
            case 'jpg':
            case 'jpeg':
                imagejpeg( $im );
            break;

            case 'png':
                imagepng( $im );
            break;

            case 'gif':
                imagegif( $im );
            break;

            default: throw new Exception( "Image type ($image_type) not supported" );
        }
        $image = ob_get_clean();
        imagedestroy( $im );
        return $image;
    }

} // end of class

?>