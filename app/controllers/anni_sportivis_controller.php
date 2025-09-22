<?



class AnniSportivisController extends AppController
{



    var $name = "AnniSportivis";
    var $login_required = true;
    var $helpers = array('Backend');
    var $uses = array('AnniSportivi');



    function admin_index()
    {
        
    }





    function admin_filters()
    {

        $this->layout = "ajax";

        if (!empty($this->data))
        {

            $this->Session->write(ucfirst(Inflector::underscore($this->name)) . ".searchFilters", $this->data['searchFilters']);
            $this->set('result', 'RELOAD_OK');
            $this->render('/backend/ajaxResult');
        }

    }





    function admin_search()
    {

        $this->layout = "ajax";

        if (!empty($this->data))
        {

            $this->Session->write(ucfirst(Inflector::underscore($this->name)) . ".searchData", $this->data);
            $this->set('result', 'RELOAD_OK');
            $this->render('/backend/ajaxResult');
        }

        if ($this->Session->check(ucfirst(Inflector::underscore($this->name)) . ".searchData", $this->data))
            {

                $this->data = $this->Session->read(ucfirst(Inflector::underscore($this->name)) . ".searchData");
            }

        }





        function admin_add()
        {

            $this->layout = "ajax";



            if (!empty($this->data))
            {


                $this->AnniSportivi->set($this->data);

                if ($this->AnniSportivi->save())
                {

                    $ADD_OK = true;

                    if ($ADD_OK)
                    {

                        /* GIUSEPPE 2017-08-29 */
                        $this->data_max($this->data['AnniSportivi']['DataInizio']);
                        $this->insert_date_init($this->data['AnniSportivi']['AnnoSportivo'], $this->data['AnniSportivi']['DataInizio']);
                        /* - - - - -  - - */


                    //$this->insert_date_init()

                        $this->set('result', 'ADD_OK');
                        $this->render('/backend/ajaxResult');
                    }
                }
            }

        }





        function admin_edit()
        {

            $this->layout = "ajax";

        }





        /* GIUSEPPE 2018-08-29 */



        function data_max($data_max)
        {

            $data_for_read = explode("-", $data_max);

            $data_max_write = $data_for_read[2] . "-" . $data_for_read['1'] . "-" . $data_for_read[0];

            $dirname = APP . '/webroot/files/data_max';
            mkdir($dirname, 0777, true);
            $content = "";

            $filename = APP . '/webroot/files/data_max/data_max.json';
            $handle = fopen($filename, "w+");
            fwrite($handle, $data_max_write);
            fclose($handle);

        }





        function insert_date_init($anno, $inizio)
        {
            $sql = "UPDATE AnniSportivi SET DataInizio = '$inizio' WHERE AnnoSportivo = '$anno'";

            mysql_query($sql);

        }





        /* ------------------- */
    }
