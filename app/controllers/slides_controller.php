<?


if (!function_exists("getThumb")) { 
	function getThumb($value) {
		
		App::Import('Helper','Thumbnail');
					
		$thumbnail = new ThumbnailHelper();
	
		return "<img src=\"" . $thumbnail->link(array('path' =>  $value, 'w' => 50, 'f' => 'png')) . "\" alt=\"\" />";
		
	}
	
	function getDefaultValue($value) {
	
		if($value == 1) $checked = 'checked="checked"';
		else $checked = '';
		
		$checkbox = '<input type="checkbox" class="checkbox_default" value="' . $value . '" name="checkbox_default" id="checkbox_default" '. $checked .' />';
	
		return $checkbox;
	
	}
 }

	class SlidesController extends AppController {
	
			var $name = "Slides";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array('Upload');
						
			function admin_index() {
				
					
				
			}
			
			function admin_edit($id) {
				
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->data['Upload']['id'] = $id;
					
					$this->dmy2ymd($this->data['Upload']['published']);

					// Funzione data fine slider home page 04-05-2018 --------------------------
           				
           				$this->dmy2ymd($this->data['Upload']['over']);
            			$this->data['Upload']['over'] = $this->data['Upload']['over']." 23:59:59";
            		
            		// ----------------------------------------------
					

					$this->Upload->set($this->data);
					
					//print_r($this->data);
		
					if ($this->Upload->save()) {
							
							$this->set('result','RELOAD_OK');
							$this->render('/backend/ajaxResult');
						
					}
					
				} else {
					
					$this->data = $this->Upload->findById($id);
					$this->data['Upload']['published'] = date("d/m/Y",strtotime($this->data['Upload']['published']));

					// Funzione data fine slider home page 04-05-2018 --------------------------

           		 		$this->data['Upload']['over'] = date("d/m/Y", strtotime($this->data['Upload']['over']));
            		
            		// ---------------------------------------------- 


					if ($this->data['Upload']['published'] == "30/11/-0001") 
						$this->data['Upload']['published'] = date("d/m/Y");

				}
				
			}




    function admin_add()
    {

        $this->layout = "ajax";

        if (!empty($this->data))
        {


            $ADD_OK = true;

            $saveData = $this->data['Upload'];

            $upload = $this->Uploader->upload('Upload.percorso');

            if ($upload != false)
            {

                $upload['tag'] = 'SLIDE';

                $upload['default'] = 1;

                $upload['color'] = '';

                $ext = array('jpeg', 'gif', 'png', 'bmp', 'jpg', 'mp4');

                if (in_array($upload['ext'], $ext) === false)
                {

                    /* //GIUSEPPE 2018-07-25 analizzo il caso in cui non ci sia un' immagine ------------------------------------------- */
                    /* $this->Upload->invalidate("percorso", $this->getError('INVALID_FILEFORMAT'));
                      $ADD_OK = false; */
                }
                else
                {

                    $saveData = array_merge($saveData, $upload);

                    //Tmp mod
                    $saveData['path'] = '/files/uploads/' . $saveData['path'];

                    $this->Upload->set($saveData);

                    if (!$this->Upload->save())
                        $ADD_OK = false;
                }
            }
            else
            {


                /* //GIUSEPPE 2018-07-25 controlliamo che ci sia un link video */
                if ($this->data['Media']['percorso'] != "")
                {
                    //type: link
                    /* controllo se è vimeo o youtube e lo memorizzo di conseguenza */
//                    $this->parse_link($this->data['Media']['percorso'], $this->data['Media']['description']);
                    $this->parse_link($this->data['Media']['percorso'], $this->data['Media']['description']);
                }
                else
                {
                    $this->Upload->invalidate("percorso", $this->getError('INVALID_FILEFORMAT'));
                    $ADD_OK = false;
                }

//                $this->Upload->invalidate("percorso", $this->getError('INVALID_FILEFORMAT'));
//
//                $ADD_OK = false;
            }

            if ($ADD_OK)
            {

                $this->set('result', 'RELOAD_OK');
                $this->render('/backend/ajaxResult');
            }
        }

    }



    /* //GIUSEPPE 2018-07-16 ------------------------------------ */





 private function parse_link($link, $description)
    {
        $link_video = "";
        $type = "";

        if (strstr($link, "youtube"))
        {
            $expl = explode("?v=", $link);

            $video = $expl[1];

            $link_video = $video;

            $type = "video/youtube";
        }
        elseif (strstr($link, "vimeo"))
        {

            $expl = explode("vimeo.com/", $link);

            $video = $expl[1];

            $link_video = $video;

            $type = "video/vimeo";
        }
        else
        {
            $this->Upload->invalidate("percorso", $this->getError('INVALID_FILEFORMAT'));
            $ADD_OK = false;
        }

        $data = date("Y-m-d H:i:s");

        $query = "INSERT INTO `files` (`id`, `name`, `type`, `size`, `filesize`, `ext`, `group`, `width`, `height`, `path`, `uploaded`, `created`, `modified`, `default`, `title`, `description`, `tag`, `athlete_id`, `disabled`, `color`, `order`, `post_id`, `page_id`, `news_id`, `newsletter_id`, `block_id`, `campi_id`, `squadra_id`, `brreport_id`, `lda_wall_id`, `isEvidenza`, `banner_id`, `slider_id`, `user_id`, `yearTrofeo`, `section_id`, `published`, `over`, `nlayout_id`, `category`, `effect`, `cat_id`, `link`, `event_id`) "
                . "VALUES "
                . "(NULL, '', '$type', '', '', '', 'video', '', '', '$link_video', '$data', '$data', '', '', '', '$description', 'SLIDE', '', '0', '#FFF', '0', '0', '0', '0', '0', '', '', '', '', '0', '0', '', '0', '', NULL, '0', '', '', '0', '0', '0', '0', NULL, '0');";

        mysql_query($query);

    }


			
			function admin_setDefault($value,$id) {
			
				$this->layout = "ajax";
			
				$this->Upload->updateAll(
				
					array('Upload.default' => 0)
				
				);
					
				$this->data = $this->Upload->findById($id);
				$this->data['Upload']['default'] = $value;
				$this->Upload->set($this->data);
				$this->Upload->save();
			
				$this->set('result', json_encode(array('update' => 1)));
				$this->render('/backend/ajaxResult');
			
			}
	
	}


