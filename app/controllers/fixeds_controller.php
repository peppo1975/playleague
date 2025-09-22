<?php

class FixedsController extends AppController
{

    var $name = "Fixeds";
    var $login_required = true;
    var $helpers = array('Backend', 'Javascript', 'Cksource');
    var $uses = array('Upload', 'Page', 'Fixed', 'Order');
    var $firstModel = 'Fixed';
    var $components = array('RequestHandler');





    function admin_index()
    {
        
    }





    function admin_add()
    {
        // print_r($_POST);

        $this->layout = "ajax";

        if (!empty($this->data))
        {
            $this->insert_fixed();

            // print_r($_POST);
            $this->set('result', 'ADD_OK');
            $this->render('/backend/ajaxResult');
        }
    }





    function admin_edit($id)
    {
        //print_r($id);

        $this->layout = "ajax";

        if (empty($this->data))
        {

            $this->data = $this->Fixed->find('first', array('conditions' => array($this->Fixed->primaryKey => $id)));

            //print_r($this->data);

            $this->set('id_contenuto', $this->data['Fixed']['id_contenuto']);

            $this->Fixed->set($this->data);
        }
        else
        {

            $this->data['Fixed'][$this->Fixed->primaryKey] = $id;

            $this->Fixed->set($this->data);

            $ADD_OK = true;


            if ($this->Fixed->save())
            {
                if ($ADD_OK)
                {
                    $this->set('result', 'ADD_OK');
                    $this->render('/backend/ajaxResult');
                }
            }
        }
    }





    private function insert_fixed()//ok
    {


        $descrizione = $_POST['data']['Fixed']['descrizione'];
//        $valore = $_POST['data']['Fixed']['valore'];
        $valore = $_POST['data']['Fixed']['valore'];
        $note = $_POST['data']['Fixed']['note'];

        $query = "INSERT INTO ContenutiFissi (descrizione, valore, note)
                    VALUES ('$descrizione', '$valore', '$note')";

        mysql_query($query);
    }





    public function read_all_fixed()//ok
    {

        $res = array();

        $sql = "SELECT * FROM `ContenutiFissi`";

        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result))
        {

            $res[$row['descrizione']] = $row['valore'];
        };

        return $res;
    }





    public function read_fixed($descrizione)//ok
    {

        $res = array();

        $sql = "SELECT * FROM `ContenutiFissi` WHERE descrizione = '$descrizione'";

        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result))
        {

            $res[$row['descrizione']] = $row['valore'];
        };

        return $res;
    }





}
