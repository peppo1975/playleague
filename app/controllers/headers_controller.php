<?

//GIUSEPPE 2023-09-10 --------------------------------

if (!function_exists("getThumb"))
{


    function getThumb($value)
    {
        App::Import('Helper', 'Thumbnail');

        $thumbnail = new ThumbnailHelper();

        return "<img src=\"" . $thumbnail->link(array('path' => $value, 'w' => 50, 'f' => 'png')) . "\" alt=\"\" />";
    }


    function getDefaultValue($value)
    {

        if ($value == 1)
            $checked = 'checked="checked"';
        else
            $checked = '';

        $checkbox = '<input type="checkbox" class="checkbox_default" value="' . $value . '" name="checkbox_default" id="checkbox_default" ' . $checked . ' />';

        return $checkbox;
    }

}


//----------------------------------------------------

/* function getThumb($value)
  {
  file_put_contents("_THUM", print_r($value, true));
  App::Import('Helper', 'Thumbnail');

  $thumbnail = new ThumbnailHelper();

  return "<img src=\"" . $thumbnail->link(array('path' => $value, 'w' => 50, 'f' => 'png')) . "\" alt=\"\" />";
  }


  function getDefaultValue($value)
  {

  if ($value == 1)
  $checked = 'checked="checked"';
  else
  $checked = '';

  $checkbox = '<input type="checkbox" class="checkbox_default" value="' . $value . '" name="checkbox_default" id="checkbox_default" ' . $checked . ' />';

  return $checkbox;
  } */



class HeadersController extends AppController
{

    var $name = "Headers";
    var $login_required = true;
    var $helpers = array('Backend');
    var $uses = array('Upload');


    function admin_index()
    {
        
    }


    function admin_edit($id)
    {

        $this->layout = "ajax";

        if (!empty($this->data))
        {

            $this->data['Upload']['id'] = $id;

            $this->dmy2ymd($this->data['Upload']['published']);

            $this->Upload->set($this->data);

            //print_r($this->data);

            if ($this->Upload->save())
            {

                $this->set('result', 'RELOAD_OK');
                $this->render('/backend/ajaxResult');
            }
        }
        else
        {

            $this->data = $this->Upload->findById($id);
            $this->data['Upload']['published'] = date("d/m/Y", strtotime($this->data['Upload']['published']));

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

                $upload['tag'] = 'HEADER';

                $upload['default'] = $this->data['Header']['default'];

                $upload['color'] = $this->data['Upload']['color'];

                if ($upload['default'] == 1)
                {

                    $this->Upload->updateAll(
                            array('Upload.default' => 0)
                    );
                }

                $ext = array('jpeg', 'gif', 'png', 'bmp', 'jpg');

                if (in_array($upload['ext'], $ext) === false)
                {
                    $this->Upload->invalidate("percorso", $this->getError('INVALID_FILEFORMAT'));
                    $ADD_OK = false;
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

                $this->Upload->invalidate("percorso", $this->getError('INVALID_FILEFORMAT'));

                $ADD_OK = false;
            }

            if ($ADD_OK)
            {

                $this->set('result', 'RELOAD_OK');
                $this->render('/backend/ajaxResult');
            }
        }
    }


    function admin_setDefault($value, $id)
    {

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

