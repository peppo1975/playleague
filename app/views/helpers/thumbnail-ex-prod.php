<?php 
App::import('Vendor', 'phpThumb', array('file' => 'phpThumb'.DS.'phpthumb.class.php'));

class ThumbnailHelper extends Helper    {
    
    private $php_thumb;
    private $options;
    private $tag_options;
    private $file_extension;
    private $cache_filename;
    private $error;
    
    private function init($options = array(), $tag_options = array())    {
        $this->options = $options;
        $this->tag_options = $tag_options;
        $this->set_file_extension();
        $this->set_cache_filename();
        $this->error = '';
    }
    
    private function set_file_extension()    {
        $this->file_extension = substr($this->options['src'], strrpos($this->options['src'], '.'), strlen($this->options['src']));
    }
    
    private function set_cache_filename()    {
        ksort($this->options);
        $filename_parts = array();
        $cacheable_properties = array('src', 'new', 'w', 'h', 'wp', 'hp', 'wl', 'hl', 'ws', 'hs', 'f', 'q', 'sx', 'sy', 'sw', 'sh', 'zc', 'bc', 'bg', 'fltr','aoe');
        foreach($this->options as $key => $value)    {
            if(in_array($key, $cacheable_properties))    {
                $filename_parts[$key] = $value;
            }
        }
        
        $this->cache_filename = '';
        foreach($filename_parts as $key => $value)    {
            $this->cache_filename .= $key . $value;
        }
        $this->cache_filename = $this->options['save_path'] . DS . md5($this->cache_filename) . $this->file_extension;
    }
    
 	function frame_link($options = array())
	{
		
		$video_preview = null;
		
		App::Import('Model','Upload');
		
		$preview = new Upload;
		
		$video = $preview->findByPath($options['path']);
		
		
		if (!empty($video)) {
			
			
			//if (!empty($video['Upload']['video_preview']) && is_file('/var/www/vhosts/midlandsport.it/cake.midlandsport.it/midland2015cake2/app/webroot' . '/' . $video['Upload']['video_preview'])) {
			//GIUSEPPE 2017-02-13 ... inserito percoro assoluto che si "riconosce automaticamente"
			if (!empty($video['Upload']['video_preview']) && is_file( $_SERVER['DOCUMENT_ROOT'] . '/' . $video['Upload']['video_preview'])) {
				
				$video_preview = $video['Upload']['video_preview'];
				
			}
			
		}
		
		if ($video_preview == null) { 
		
		if (isset($options['path'])) {
			
			$options['src'] = APP . '/webroot/' . $options['path'];
			
		}

		
		if ( !extension_loaded('ffmpeg') ) return ("skip ffmpeg extension not loaded"); 
		if ( !extension_loaded('gd') ) return("skip gd extension not avaliable.");
		if ( !is_file($options['src']) ) return ("Video nn trovato");


		$frame = 50;
		$mov = new ffmpeg_movie($options['src']);
		
		
		$options['path'] = $options['path'] . "_preview.jpg";
		
		$img = $options['src'] . "_preview.jpg";
		
		#echo "<li>$video: ".$mov->getVideoCodec()."</li>";
		
		$ff_frame = $mov->getFrame($frame);
		if ($ff_frame)
		{
			$gd_image = $ff_frame->toGDImage();
			if ($gd_image)
			{
				imagejpeg($gd_image, $img);
				imagedestroy($gd_image);

				return $this->link($options);
	
			}
		}
		
		} else {
			
			
			$options['path'] = $video_preview;
			$options['src'] = APP . '/webroot/' . $options['path'];
			
			return $this->link($options);
			
		}
	}
	
 	function frame_show($options = array())
	{
		

		$video_preview = null;
		
		App::Import('Model','Upload');
		
		$preview = new Upload;
		
		$video = $preview->findByPath($options['path']);
		
		
		if (!empty($video)) {
			
			//if (!empty($video['Upload']['video_preview']) && is_file('/var/www/vhosts/midlandsport.it/cake.midlandsport.it/midland2015cake2/app/webroot' . '/' . $video['Upload']['video_preview'])) {
			//GIUSEPPE 2017-02-13 ... inserito percoro assoluto che si "riconosce automaticamente"
			if (!empty($video['Upload']['video_preview']) && is_file( $_SERVER['DOCUMENT_ROOT']  . '/' . $video['Upload']['video_preview'])) {
				
				$video_preview = $video['Upload']['video_preview'];
				
			}
			
		}
		
		if ($video_preview == null) { 
		
		if (isset($options['path'])) {
			
			$options['src'] = APP . '/webroot/' . $options['path'];
			
		}

		
		if ( !extension_loaded('ffmpeg') ) return ("skip ffmpeg extension not loaded"); 
		if ( !extension_loaded('gd') ) return("skip gd extension not avaliable.");
		if ( !is_file($options['src']) ) return ("Video nn trovato");


		$frame = 50;
		$mov = new ffmpeg_movie($options['src']);
		
		$options['path'] = $options['path'] . "_preview.jpg";
		
		$img = $options['src'] . "_preview.jpg";
		
 		
		#echo "<li>$video: ".$mov->getVideoCodec()."</li>";
		
		$ff_frame = $mov->getFrame($frame);
		if ($ff_frame)
		{
			$gd_image = $ff_frame->toGDImage();
			if ($gd_image)
			{
				imagejpeg($gd_image, $img);
				imagedestroy($gd_image);

				return $this->show($options);
	
			}
		}
		
		} else {
			
			$options['path'] = $video_preview;
			$options['src'] = APP . '/webroot/' . $options['path'];
			
			return $this->show($options);
			
		}
	}
    
    private function image_is_cached()    {
        if(is_file($this->cache_filename))    {
            return true;
        } else    {
            return false;
        }
    }
    
    private function create_thumb()    {
        $this->php_thumb = new phpThumb();
        foreach($this->php_thumb as $var => $value) {
            if(isset($this->options[$var]))    {
                $this->php_thumb->setParameter($var, $this->options[$var]);
            }
        }
        if($this->php_thumb->GenerateThumbnail()) {
            $this->php_thumb->RenderToFile($this->cache_filename);
        } else {
            $this->error = ereg_replace("[^A-Za-z0-9\/: .]", "", $this->php_thumb->fatalerror);
            $this->error = str_replace('phpThumb v1.7.8200709161750', '', $this->error);
        }
    }
    
    private function show_image_tag()    {
        if($this->error != '')    {
            $src = $this->options['error_image_path'];
            $this->tag_options['alt'] = $this->error;
        } else    {
            $src = $this->options['display_path'] . '/' . substr($this->cache_filename, strrpos($this->cache_filename, DS) + 1, strlen($this->cache_filename));
        }
        $img_tag = '<img src="' . $src . '"';
        if(isset($this->options['w']))    {
            $img_tag .= ' width="' . $this->options['w'] . '"';
        }
        if(isset($this->options['h']))    {
            $img_tag .= ' height="' .  $this->options['h'] . '"';
        }
        foreach($this->tag_options as $key => $value)    {
            $img_tag .= ' ' . $key . '="' . $value . '"';
        }
        $img_tag .=  ' />';
        
        echo $img_tag;
    }
    
    public function link($options = array()) {
		
		if (!isset($options['save_path'])) $options['save_path'] = '/var/www/vhosts/midlandsport.it/cake.midlandsport.it/midland2015cake2' . '/app/webroot/assets/images/thumbs';
		//GIUSEPPE 2017-02-13 ... inserito percoro assoluto che si "riconosce automaticamente"
		//if (!isset($options['save_path'])) $options['save_path'] =  $_SERVER['DOCUMENT_ROOT'] . '/assets/images/thumbs';
		if (!isset($options['display_path'])) $options['display_path'] = '/var/www/vhosts/midlandsport.it/cake.midlandsport.it/midland2015cake2' . '/app/webroot/assets/images/thumbs';
		if (!isset($options['error_image_path'])) $options['error_image_path'] ='/var/www/vhosts/midlandsport.it/cake.midlandsport.it/midland2015cake2' . '/app/webroot/assets/images/error.jpg';
    
		if (!isset($tag_options['alt'])) $tag_options['alt'] = "";
    
		if (isset($options['path'])) {
			
			$options['src'] = APP . '/webroot/' . $options['path'];
			
		}
    
        $this->init($options, $tag_options);
        if($this->image_is_cached()) {

			if($this->error != '') {
				$src = $this->options['error_image_path'];

			} else    {
				$src = $this->options['display_path'] . '/' . substr($this->cache_filename, strrpos($this->cache_filename, DS) + 1, strlen($this->cache_filename));
			}
			
			return $src;


        } else {
            $this->create_thumb();

			if($this->error != '')    {
				$src = $this->options['error_image_path'];

			} else    {
				$src = $this->options['display_path'] . '/' . substr($this->cache_filename, strrpos($this->cache_filename, DS) + 1, strlen($this->cache_filename));
			}
			
			return $src;

        }
		
	}
    
    public function show($options = array(), $tag_options = array())    {
		

		if (!isset($options['save_path'])) $options['save_path'] = '/var/www/vhosts/midlandsport.it/cake.midlandsport.it/midland2015cake2' . '/app/webroot/assets/images/thumbs';
		//GIUSEPPE 2017-02-13 ... inserito percoro assoluto che si "riconosce automaticamente"
		//if (!isset($options['save_path'])) $options['save_path'] = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/thumbs';
		if (!isset($options['display_path'])) $options['display_path'] = '/assets/images/thumbs';
		if (!isset($options['error_image_path'])) $options['error_image_path'] = '/assets/images/error.jpg';
    
		if (!isset($tag_options['alt'])) $tag_options['alt'] = "";
    
		if (isset($options['path'])) {
			
			$options['src'] = APP . '/webroot/' . $options['path'];
			//GIUSEPPE 2017-02-13 ... inserito percoro assoluto che si "riconosce automaticamente"
			//$options['src'] = $_SERVER['DOCUMENT_ROOT'].'/'. $options['path'];
			
		}
    
        $this->init($options, $tag_options);
        if($this->image_is_cached())    {
            $this->show_image_tag();
        } else    {
            $this->create_thumb();
            $this->show_image_tag();
        }
    }
    
}
