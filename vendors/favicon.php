<?php

class favicon extends object {
    
      var $url = null;
      var $id = null;
      var $has_icon = null;
      var $data = null;
      var $cache = null;
      
    
      function favicon($url, $id) {
          $this->id = $id;
          $this->url = $url;
          $this->cache = WWW_ROOT.'img'.DS.'favicons'.DS;
          $this->size = 16;
          
          $this->has_icon = $this->load();
      }
      
      
      function load() {
          /* http://www.peej.co.uk/projects/favatars.html */
          $timeout = 15; // timeout in sec
          $HTTPRequest = @fopen($this->url, 'r'); 
          echo $this->url;
          if ($HTTPRequest) { 
              stream_set_timeout($HTTPRequest, $timeout); 
              $html = fread($HTTPRequest, 4096); 
              $HTTPRequestData = stream_get_meta_data($HTTPRequest); 
              fclose($HTTPRequest); 
              if (!$HTTPRequestData['timed_out']) { 
                  if (preg_match('/<link[^>]+rel="(?:shortcut )?icon"[^>]+?href="([^"]+?)"/si', $html, $matches)) { 
                      $linkUrl = html_entity_decode($matches[1]); 
                      if (substr($linkUrl, 0, 1) == '/') { 
                          $urlParts = parse_url($this->url); 
                          $faviconURL = $urlParts['scheme'].'://'.$urlParts['host'].$linkUrl; 
                      } elseif (substr($linkUrl, 0, 7) == 'http://') { 
                          $faviconURL = $linkUrl; 
                      } elseif (substr($this->url, -1, 1) == '/') { 
                          $faviconURL = $this->url.$linkUrl; 
                      } else { 
                          $faviconURL = $this->url.'/'.$linkUrl; 
                      } 
                  } else { 
                      $urlParts = parse_url($this->url); 
                      $faviconURL = $urlParts['scheme'].'://'.$urlParts['host'].'/favicon.ico'; 
                  } 
                  $HTTPRequest = @fopen($faviconURL, 'r'); 
                  if ($HTTPRequest) { 
                      stream_set_timeout($HTTPRequest, $timeout);
                      $this->data = fread($HTTPRequest, 8192); 
                      $HTTPRequestData = stream_get_meta_data($HTTPRequest); 
                      fclose($HTTPRequest); 
                      
                      if (!$HTTPRequestData['timed_out'] && strlen($this->data) < 8192) { 
                          return true; 
                      } 
                  } 
              } 
          } 
          $this->data = null;
          return false;
      }
      
      
      function save() {
          
          if ($this->has_icon) {
                App::import('Vendor', 'phpThumb', array('file'=>'phpthumb'.DS.'phpthumb.class.php'));
                
                $thumb = new phpThumb();
                $thumb->setSourceData($this->ico2png($this->data));
                $thumb->setParameter('w', $this->size);
                $thumb->setParameter('config_output_format', 'png');

                if ($thumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
                    if ($thumb->RenderToFile($this->cache.$this->id.'.png')) {
                        // do something on success
                    } else {
                        // do something with debug/error messages
                    }
                } else {
                    // do something with debug/error messages
                }              
          } else {
                return false;
          }
      }



    # Convert ico to png function,
    # information about ico format is accessible on a site http://kainsk.tomsk.ru/g2003/sys26/oswin.htm,
    function ico2png($ico)
    {
        $res = '';

        while(!isset($tmp))
        {
            $tmp = '';

            # get ICONDIR struct & check that it is correct ico format
            $icondir = unpack('sidReserved/sidType/sidCount', substr($ico, 0, 6));
            if ($icondir['idReserved']!=0 || $icondir['idType']!=1 || $icondir['idCount']<1) break;
            $icondir['idEntries'] = array();
            $entry = array();
            for($i=0; $i<$icondir['idCount']; $i++)
            {
                $entry = unpack('CbWidth/CbHeight/CbColorCount/CbReserved/swPlanes/swBitCount/LdwBytesInRes/LdwImageOffset', substr($ico, 6 + $i*16, 16));
                $icondir['idEntries'][] = $entry;
            }
           
            # select need icon & get it raw data
            $iconres = '';
            $bpx = 1; # bits per pixel
            $idx = 0; # index of need icon
            foreach($icondir['idEntries'] as $k=>$entry)
            {
                if ($entry['bWidth']==16 && isset($entry['swBitCount']) && $entry['swBitCount']>$bpx && $entry['swBitCount']<33)
                {
                    $idx = $k;
                    $bpx = $entry['swBitCount'];
                }
            }           
            $iconres = substr($ico, $icondir['idEntries'][$idx]['dwImageOffset'], $icondir['idEntries'][$idx]['dwBytesInRes']);
            unset($ico);
            unset($icondir);

            # getting bitmap info
            $bitmap_info = array();
            $bitmap_info['header'] = unpack('LbiSize/LbiWidth/LbiHeight/SbiPlanes/SbiBitCount/LbiCompression/LbiSizeImage/LbiXPelsPerMeter/LbiYPelsPerMeter/LbiClrUsed/LbiClrImportant', substr($iconres, 0, 40));

            $bitmap_info['header']['biHeight'] = $bitmap_info['header']['biHeight'] / 2;           
            $number_color = 0;

            if ($bitmap_info['header']['biBitCount'] > 16)
            {
                $number_color = 0;
                $sizecolor = $bitmap_info['header']['biWidth']*$bitmap_info['header']['biBitCount'] * $bitmap_info['header']['biHeight'] / 8;  
            }
            elseif ( $bitmap_info['header']['biBitCount'] < 16)
            {
                $number_color = (int) pow(2, $bitmap_info['header']['biBitCount']);
                $sizecolor = $bitmap_info['header']['biWidth']*$bitmap_info['header']['biBitCount'] * $bitmap_info['header']['biHeight'] / 8;  
                if ($bitmap_info['header']['biBitCount']=='1') $sizecolor = $sizecolor * 2;
            }
            else return $res;

            $rgb_table_size =  4 * $number_color;       
            for($i=0; $i<$number_color; $i++)
            {
                $bitmap_info['colors'][] = unpack('CrgbBlue/CrgbGreen/CrgbRed/CrgbReserved', substr($iconres, 40 + $i*4, 4));
            }
            $current_offset = 40 + $number_color * 4;

            $arraycolor = array();

            for($i=0; $i<$sizecolor; $i++)
            {
                $value = unpack('Cvalue', substr($iconres, $current_offset, 1));
                $arraycolor[] = $value['value'];
                $current_offset++;
            }

            # background alpha is disabled because IE 5.5 + have bug with alpha-channels
            # by default background color is white
            # imagealphablending($im, false);
            # imagefilledrectangle($im, 0, 0, 16, 16, $color);
            # imagealphablending($im, true);
            $im = imagecreatetruecolor(16, 16);
            $color = imagecolorallocate($im, 255, 255, 255);
            imagefill($im, 1, 1, $color);

            # getting mask
            $alpha = '';
            for($i=0; $i<16; $i++)
            {
                $z = unpack('Cx/Cy', substr($iconres, $current_offset, 2));
                $z = str_pad(decbin($z['x']), 8, '0', STR_PAD_RIGHT)  . str_pad(decbin($z['y']), 8, '0', STR_PAD_LEFT);
                $alpha .= $z;
                $current_offset = $current_offset + 4;
            }

            # drawing image
            $ico_size = 16;   
            $off = 0; # range (0-255)

            # cases for different color depth
            switch ($bitmap_info['header']['biBitCount'])   
            {       

                ###################### for 32 bit icons ######################
                case 32:
                    for($y=0; $y<$ico_size; $y++)
                    {
                        for($x=0; $x<$ico_size; $x++)
                        {
                            $a = round((255-$arraycolor[$off*4+3])/2);
                            $a = ($a<0) ? 0 : $a;
                            $a = ($a>127) ? 127 : $a;
                            $color = imagecolorallocatealpha($im, $arraycolor[$off*4+2], $arraycolor[$off*4+1], $arraycolor[$off*4], $a);
                            imagesetpixel($im, $x, $ico_size-1-$y, $color);
                            $off++;
                        }
                    }
                break;

                ###################### for 24 bit icons ######################
                case 24:
                    for($y=0; $y<$ico_size; $y++)
                    {
                        for($x=0; $x<$ico_size; $x++)
                        {
                            $valpha = ($alpha[$off]=='1') ? 127 : 0;
                            $color = imagecolorallocatealpha($im, $arraycolor[$off*3+2], $arraycolor[$off*3+1], $arraycolor[$off*3], $valpha);
                            imagesetpixel ($im, $x, $ico_size-1-$y, $color);
                            $off++;
                        }
                    }
                break;

                ###################### for 08 bit icons ######################
                case 8:
                    for($y=0; $y<$ico_size; $y++)
                    {
                        for($x=0; $x<$ico_size; $x++)
                        {
                            $valpha = ($alpha[$off]=='1') ? 127 : 0;
                            $c = $arraycolor[$off];
                            $c = $bitmap_info['colors'][$c];
                            $color = imagecolorallocatealpha($im, $c['rgbRed'], $c['rgbGreen'], $c['rgbBlue'], $valpha);
                            imagesetpixel ($im, $x, $ico_size-1-$y, $color);
                            $off++;
                        }
                    }
                break;

                ###################### for 04 bit icons ######################
                # 318 = 22 (header) + 40 (bitmap_info) + 16 * 4 (colors) + 128 (pixels) + 64 (mask)
                case 4:
                    for($y=0; $y<$ico_size; $y++)
                    {
                        for($x=0; $x<$ico_size; $x++)
                        {
                            $valpha = ($alpha[$off]=='1') ? 127 : 0;
                            $c = ($arraycolor[floor($off/2)]);
                            $c = str_pad(decbin($c), 8, '0', STR_PAD_LEFT);
                            $m =  (fmod($off+1, 2)==0) ? 1 : 0;
                            $c = bindec(substr($c, $m*4, 4));
                            $c = $bitmap_info['colors'][$c];
                            $color = imagecolorallocatealpha($im, $c['rgbRed'], $c['rgbGreen'], $c['rgbBlue'], $valpha);
                            imagesetpixel ($im, $x, $ico_size-1-$y, $color);
                            $off++;
                        }
                    }
                break;

                ###################### for 01 bit icons ######################
                # 198 = 22 (header) + 40 (bitmap_info) + 2 * 4 (colors) + 64 (pixels, but real 32 needed?) + 64 (mask)
                case 1:
                    for($y=0; $y<$ico_size; $y++)
                    {
                        for($x=0; $x<$ico_size; $x++)
                        {
                            $valpha = ($alpha[$off]=='1') ? 127 : 0;
                            $c = ($arraycolor[floor($off/8)]); # меняем байт каждые 8 пикселей
                            $c = str_pad(decbin($c), 8, '0', STR_PAD_LEFT);
                            $m = fmod($off+8, 8) + 1; # bit number
                            $c = (int) substr($c, $m-1, 1);
                            $c = $bitmap_info['colors'][$c];
                            $color = imagecolorallocatealpha($im, $c['rgbRed'], $c['rgbGreen'], $c['rgbBlue'], $valpha);
                            imagesetpixel ($im, $x, $ico_size-1-$y, $color);
                            $off++;
                        }
                        $off = $off + 16;
                    }           
                break;

                ##############################################################

                default:
                return '';
            }

            # output png
            ob_start();
            # imagesavealpha($im, true);
            imagepng($im);
            imagedestroy($im);
            $res = ob_get_clean();
        }
        return $res;
    }      
      
    
}
    
    
?>
