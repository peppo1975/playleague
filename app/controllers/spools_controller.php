<?







function getNumber($value)
{

    $tmp = explode('@', $value);

    if ($tmp[1] == 'smsviaemail.it')
        return $tmp[0];

    return $value;

}







class SpoolsController extends AppController
{

    var $name = "Spools";
    var $login_required = false;
    var $helpers = array('Backend');
    var $uses = array('User', 'Group', 'EmailModel', 'Spool', 'Newsletter', 'NewsletterConfig');
    var $components = array('Password', 'RequestHandler', 'Email', 'ControllerList');







    function admin_index()
    {
        
    }







    function admin_count()
    {

        return $this->Spool->find('count', array(
                    'conditions' => array(
                        'sent' => 0,
                        'Spool.created <= NOW()',
                        'Spool.modified BETWEEN ? AND ?' => array(date("Y-m-d H:i:s", strtotime("-4 days")), date("Y-m-d H:i:s")),
                        'EmailModel.disabled' => 0,
                        'EmailModel.created <= NOW()'
                    )
        ));

    }







    function admin_counter()
    {
        
    }







    function beforeFilter()
    {

        parent::beforeFilter();

        switch ($this->action)
        {

            case 'sendMails':


                if (!$this->Auth->user())
                    $this->Auth->allow('*');

                break;
        }

    }







    function sendMails($test = 0)
    {

        $this->layout = "ajax";
        $this->autoRender = "false";

        // error_reporting(E_ALL);

        $spool = array(); //GIUSEPPE 2020-06-05

        if ($test == 1)
        {

            $spool = $this->Spool->find('all', array(
                'conditions' => array(
                    'sent' => 0,
                    'Spool.created <= NOW()',
                    'EmailModel.disabled' => 0,
                    'EmailModel.created <= NOW()',
                    'Spool.error' => 0
                ),
                'limit' => 70,
                'order' => array('Spool.created' => 'DESC')
            ));
        }

        if (empty($spool))
        {

            //debug('Nessuna email in coda');
            exit;
        }


        $validation = & Validation::getInstance();

        App::import('Sanitize');
        $sanitize = new Sanitize;

        $arr_email_sent = array(); //GIUSEPPE 2017-07-18

        foreach ($spool as $mail)
        {

            if ($mail['Spool']['email'] == 'scandicci@midlandeuropa.com' ||
                    $mail['Spool']['email'] == 'sesto@midlandeuropa.com' ||
                    $mail['Spool']['email'] == 'info@midlandeuropa.com' ||
                    $mail['Spool']['email'] == 'filippomaria.rossidivernio@gmail.com' ||
                    $mail['Spool']['email'] == 'lorenzopirami@hotmail.com' ||
                    $mail['Spool']['email'] == 'SPRAMAGGIORE@bancafideuram.it' ||
                    $mail['Spool']['email'] == 'andrea.greppi@gmail.com' ||
                    $mail['Spool']['email'] == 'arexius@gmail.com'
            )
            {


                continue;
            }
            else
            {



                /* NEWSLETTER */
                $mail_data = $this->EmailModel->findById($mail['EmailModel']['id']);

                if ($mail['EmailModel']['newsletter_id'] != 0)
                {

                    $newsletter = $this->Newsletter->findById($mail['EmailModel']['newsletter_id']);

                    $ora = strtotime(date("Y-m-d H:i:s"));

                    $published = strtotime($newsletter['Newsletter']['published']);

                    if ($published > $ora)
                        break;

                    $config = $this->NewsletterConfig->find('first', array(
                        'conditions' => array('NewsletterConfig.is_default' => 1),
                    ));



                    if (substr_count($mail['EmailModel']['layout'], "yourgame"))
                    {
                        $config = $this->NewsletterConfig->findById(4);
                        $config['NewsletterAccount']['sender_name'] = 'Yourgame';
                        $config['NewsletterAccount']['sender_mail'] = 'info@yourgame.it';
                    }

                    if (substr_count($mail['EmailModel']['layout'], "forum"))
                    {
                        $config = $this->NewsletterConfig->findById(3);
                        $config['NewsletterAccount']['sender_name'] = '578Toscana';
                        $config['NewsletterAccount']['sender_mail'] = 'info@578toscana.it';
                    }

                    if (!empty($config))
                    {
                        $this->Email->smtpOptions = array(
                            'port' => $config['NewsletterAccount']['port'],
                            'timeout' => '30',
                            'host' => $config['NewsletterAccount']['host'],
                            'username' => $config['NewsletterAccount']['username'],
                            'password' => $config['NewsletterAccount']['password'],
                            'client' => 'CAKE'
                        );

                        $this->Email->delivery = 'smtp';
                        $this->Email->sendAs = 'both';
                        $this->Email->replyTo = $config['NewsletterAccount']['sender_mail'];
                        $this->Email->from = $config['NewsletterAccount']['sender_name'] . '<' . $config['NewsletterAccount']['sender_mail'] . '>';

                        $this->set('disclaimer', str_replace('#unsubscribe', '/' . md5($mail['Spool']['email']), $config['NewsletterConfig']['disclaimer']));
                    }

                    $this->set('data', $newsletter);
                    $this->set('uploads', $newsletter['Upload']); //email
                }
                /* ---------- */

                $this->Spool->create();

                $this->data['Spool']['id'] = $mail['Spool']['id'];
                $this->data['Spool']['error'] = 0;
                $this->data['Spool']['mail_id'] = $mail['EmailModel']['id'];
                //print $mail['Spool']['email'];
                if (substr_count($mail['Spool']['email'], '@smsviaemail.it') == 0)
                {//Se  un email.
                    $oldEmail = $mail['Spool']['email'];
                    $mail['Spool']['email'] = filter_var($mail['Spool']['email'], FILTER_SANITIZE_EMAIL);

                    if (!$validation->email($mail['Spool']['email']))
                    {

                        print('Indirizzo email non valido.');

                        $this->data['Spool']['email'] = $mail['Spool']['email'];
                        $this->data['Spool']['sent'] = 0;
                        $this->data['Spool']['error'] = 1;

                        //GIUSEPPE 2017-07-18 --------------------------
                        $this->Spool->query("UPDATE `timmy_spools` SET `error` = '1' WHERE `timmy_spools`.`id` = " . $mail['Spool']['id'] . ";");
                        //----------------------------------------------
//                        $this->Spool->set($this->data);
//                        $this->Spool->save();

                        continue;
                    }

                    $this->Email->to = $mail['Spool']['email'];
                    if ($mail['EmailModel']['from'] != '')
                        $this->Email->from = $mail['EmailModel']['from'];


                    if (substr_count($mail['EmailModel']['layout'], "forum"))
                    {
                        $this->Email->from = 'info@578toscana.it';
                    }
                    if (substr_count($mail['EmailModel']['layout'], "yourgame"))
                    {
                        $this->Email->from = 'info@yourgame.it';
                    }

                    $this->Email->subject = $mail['EmailModel']['subject'];
                    $this->Email->template = $mail['EmailModel']['layout'];

                    /* Invio email con allegati (se ci sono) */
                    if (isset($mail_data['Upload']) && count($mail_data['Upload']))
                    {
                        $uploads = array();

                        foreach ($mail_data['Upload'] as $upload)
                        {
                            $uploads[$upload['name']] = APP . 'webroot/' . $upload['path'];
                        }
                        $this->Email->attachments = $uploads;
                        $this->set('uploads', $mail_data['Upload']);
                    }
                    /* ______________________________________ */

                    $this->set('text', $mail['EmailModel']['message']);
                    $this->set('subject', $mail['EmailModel']['subject']);

                    if ($this->Email->send())
                    {
                        //print "qua";
                        $this->data['Spool']['sent'] = 1;
                        $this->data['Spool']['email'] = $mail['Spool']['email'];
                        $arr_email_sent[] = $mail['Spool']['email'];
                        // debug(date("d/m/Y H:i:s") . ": " . $mail['Spool']['email'] . " sent");
                        //$this->Spool->query("DELETE FROM timmy_spools WHERE email = '" . $oldEmail . "' AND sent = 0"); echo $oldEmail; exit;
                        
                        //GIUSEPPE 2017-07-18 --------------------------
                        $this->Spool->query("UPDATE `timmy_spools` SET `sent` = '1' WHERE `timmy_spools`.`id` = " . $mail['Spool']['id'] . ";");
                        //----------------------------------------------
                    }
                    else
                    {
                        //print "no qua";

                        $this->data['Spool']['sent'] = 0;
                        $this->data['Spool']['error'] = 1;
                        
                        //GIUSEPPE 2017-07-18 --------------------------
                        $this->Spool->query("UPDATE `timmy_spools` SET `error` = '1' WHERE `timmy_spools`.`id` = " . $mail['Spool']['id'] . ";");
                        //----------------------------------------------
                    }
                }
                else
                {//Se  un sms da aimon.
                    $to_number = str_replace('@smsviaemail.it', '', $mail['Spool']['email']);

                    if ($this->sendSms(array('dest' => $to_number, 'text' => $mail['EmailModel']['subject'])))
                    {
                        $this->data['Spool']['email'] = $mail['Spool']['email'];
                        $this->data['Spool']['sent'] = 1;
                        
                        //GIUSEPPE 2017-07-18 --------------------------
                        $this->Spool->query("UPDATE `timmy_spools` SET `sent` = '1' WHERE `timmy_spools`.`id` = " . $mail['Spool']['id'] . ";");
                        //----------------------------------------------
                    }
                    else
                    {

                        $this->data['Spool']['email'] = $mail['Spool']['email'];
                        $this->data['Spool']['sent'] = 0;
                        $this->data['Spool']['error'] = 1;
                        
                        //GIUSEPPE 2017-07-18 --------------------------
                        $this->Spool->query("UPDATE `timmy_spools` SET `error` = '1' WHERE `timmy_spools`.`id` = " . $mail['Spool']['id'] . ";");
                        //----------------------------------------------
                        
                    }
                }

//                $this->Spool->set($this->data);
//                $this->Spool->save();
            }
        }
        
        //GIUSEPPE 2017-07-18 --------------------------
        print_r($arr_email_sent);
        //----------------------------------------------

    }







    function sendSms($options = array())
    {

        if (!isset($options['mit']))
            $options['mit'] = '';


        $text = trim($options['text']);
        $text = utf8_decode($options['text']);
        $text = substr($text, 0, 160);
        $text = base64_encode($text);

        $buffer = array(
            "authlogin" => Configure::read('options_sms_username'),
            "authpasswd" => Configure::read('options_sms_password'),
            "sender" => base64_encode($options['mit']),
            "body" => $text,
            "destination" => $options['dest'],
            "id_api" => Configure::read('options_sms_api')
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.apisms.it/http/send_sms");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $buffer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);

        debug($ret);

        curl_close($ch); # ritorno dalle api print_r($ret);	

        $retrn = substr_count($ret, '+01 SMS Queued');

        if ($retrn > 0)
            return true;
        else
            return false;

    }







    function isValidEmail($email)
    {
        return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);

    }







    function testProva()
    {

        $this->layout = null;
        $this->autoRender = false;

        Configure::Write('debug', 2);

        debug('prova');

        $validation = & Validation::getInstance();

        // Validate the e-mail address.
        if (!$validation->email('e.lamacchiatimmytag.it'))
        {

            debug('Indirizzo non valido');
        }
        else
        {

            debug('indirizzo go');
        }

    }







    function admin_sms()
    {
        
    }







    function admin_mail()
    {
        
    }







    function admin_newsletter()
    {
        
    }







    function admin_bullettin()
    {
        
    }







}
