<?php
	
	// GIUSEPPE
	// controller creato ad hoc per gestire il database
	
	class TestController extends AppController {
		
		
		var $name = "Subscriptions";
		var $helpers = array('Backend');
		var $uses = array('Athlete','User','Subscription','Match','Campionati','Campicampionati','Ranking','Yearbook','Disciplinari','Discipline','Half','Squadre','Campi','Causalresult','AnniSportivi','SquadreCampionati','Athlete','Lda','Matchgoal','Notgame','EmailModel','Spool','NewsletterConfig','TipiAssicurazione');
		
		var $components = array('Password', 'RequestHandler', 'Email','ControllerList'); 
		
		function vedi($test)
		{
			echo $test;
			exit;
		}
		
		
		//da testare prima
		function deletecookie($namecookie)
		{
			//session_start(); 
			//setcookie(($namecookie, "", time()-10800,"/");
			//echo $namecookie." ".count($_COOKIE)."<br>";
			//echo "cookie ".$_COOKIE[(string)$namecookie];
			
			//exit;
			
			echo "fengul";
			
			exit;
		}
		
		//GIUSEPPE TEST ----------------------------------------------
		// funzioni di test
		
		function cerca()
		{
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			mysql_select_db("MidlandDev2016");
			
			$q = mysql_query("SELECT * FROM Atleti ORDER BY Atleta DESC");
			
			print_r(mysql_fetch_array($q)) ;
			
			exit;
			
		}
		
		
		function cancella($id)
		{
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			mysql_select_db("MidlandDev2016");
			
			$q = mysql_query("DELETE FROM Atleti WHERE Atleta = ".$id);
			
			//print_r(mysql_fetch_array($q)) ;
			
			exit;
			
		}
		
		
		function annuario()
		{
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			mysql_select_db("MidlandDev2016");
			
			$q = mysql_query("SELECT * FROM Annuario ORDER BY Annuario DESC");
			
			print_r(mysql_fetch_array($q)) ;
			
			exit;
		}
		
		
		
		function backup_table($table)
		{
			//mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			//mysql_select_db("MidlandDev2016");
			
			$result = mysql_query('SELECT * FROM '.$table);
			
			$num_fields = mysql_num_fields($result);
			
			$return.= 'DROP TABLE IF EXISTS '.$table.';';
			
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			
			$return.= "\n\n".$row2[1].";\n\n";
			
			//for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysql_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					
					for($j=0; $j < $num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						
						if ($j < ($num_fields-1)) { $return.= ','; }
						
					}
					
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
			
			//save file		
			
			//$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
			
			$position = APP. 'webroot/files/sql/db-backup-'.time().'.sql';
			
			echo $position;
			
			$this->autoRender = false;
			
			//$handle = fopen($position, "w+"); 
			$handle = fopen(APP. '/webroot/files/json/ZZZS_' . time() . '.json', "a+"); 
			
			fwrite($handle,$return);
			
			fclose($handle);
			
			//echo $return;
			
			exit;
		}
		
		
		
		function backup_database()
		{
			//$link = mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			//mysql_select_db("MidlandDev2016",$link);
			
			$tables = '*';
			
			if($tables == '*')
			{
				$tables = array();
				$result = mysql_query('SHOW TABLES');
				while($row = mysql_fetch_row($result))
				{
					$tables[] = $row[0];
				}
			}
			else
			{
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			}
			$return="";
			//cycle through
			foreach($tables as $table)
			{
				$result = mysql_query('SELECT * FROM '.$table);
				$num_fields = mysql_num_fields($result);
				
				$return.= 'DROP TABLE IF EXISTS '.$table.';';
				$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
				$return.= "\n\n".$row2[1].";\n\n";
				
				for ($i = 0; $i < $num_fields; $i++) 
				{
					
					$index_to_insert = 0;
					
					while($row = mysql_fetch_row($result))
					{
						
						if($index_to_insert==0)
						{
							$return.= 'INSERT INTO '.$table.' VALUES'.chr(0x0A).'(';
							for($j=0; $j < $num_fields; $j++) 
							{
								$row[$j] = addslashes($row[$j]);
								$row[$j] = ereg_replace("\n","\\n",$row[$j]);
								if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
								if ($j < ($num_fields-1)) { $return.= ','; }
							}
							$return.= "),\n";
							$index_to_insert++;
						}
						else
						{
							$return.= '(';
							for($j=0; $j < $num_fields; $j++) 
							{
								$row[$j] = addslashes($row[$j]);
								$row[$j] = ereg_replace("\n","\\n",$row[$j]);
								if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
								if ($j < ($num_fields-1)) { $return.= ','; }
							}
							$return.= "),\n";
							$index_to_insert++;
						}
						
					}
					
					$return[strlen($return)-2]= ";";
				}
				$return.="\n\n\n";
			};
			
			
			//$position = APP. 'webroot/files/sql/db-backup-'.time().'.sql'; 
			
			$position_json = APP. 'webroot/files/json/ZZZS_'.time().'.sql';
			
			
			
			$this->autoRender = false;
			
			$handle = fopen($position_json,"a+");
			//$handle = fopen($position, "a+");
			//$handle = fopen(APP. '/webroot/files/json/ZZZS_' . time() . '.json', "a+"); 
			
			fwrite($handle,$return);
			
			fclose($handle);
			
			echo $position_json;
			//echo $return;
			
			exit;
		}
		
		function cancella_annuario($id)
		{
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			mysql_select_db("MidlandDev2016");
			
			$q = mysql_query("DELETE FROM Annuario WHERE Atleta = ".$id);
			
			//print_r(mysql_fetch_array($q)) ;
			
			exit;
			
		}
		
		function cancella_annuario_anno($anno)
		{
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			mysql_select_db("MidlandDev2016");
			
			$q = mysql_query("DELETE FROM Annuario WHERE AnnoSportivo = ".$anno);
			
			//print_r(mysql_fetch_array($q)) ;
			
			exit;
			
		}
		
		
		function update_atleta($idAtleta,$set_voce,$valore)
		{
			
			// UPDATE `atleti` SET `Email` = '' WHERE `atleti`.`Atleta` = 16044;
			
			
			
			
			mysql_connect("localhost","MidlandDev2016","MdlndDv2016Db");
			
			mysql_select_db("MidlandDev2016");
			
			$q = mysql_query("UPDATE `Atleti` SET `".$set_voce."` = ".$valore." WHERE `Atleta` = ".$idAtleta);
			
			
			exit;
			
		}
		
		function readmail()
		{
			
			if(isset($_SESSION['email_payor']))
			{
				echo $_SESSION['email_payor'];
			}
			else
			{
				echo "NO MAIL";
			}
			
			exit;
			
		}
		
		
		function find($valTest) {
			
			$this->autoRender = false;
			
			$term = @mysql_real_escape_string($valTest);
			
			$athletes = $this->Athlete->find('all',
			array('conditions' =>"CONCAT(Cognome,' ',Nome) LIKE '$term%'",
			'limit' => 30,
			'order' => 'Athlete.Cognome ASC'
			)
			);
			
			
			
			$ret = array();
			foreach ($athletes as $athlete) {
				
				$ret[]=array(
				
				'id'=>$athlete['Athlete']['Atleta'],
				'label'=>($athlete['Athlete']['Cognome'] . " " . $athlete['Athlete']['Nome'] . " - " . date("d/m/Y",strtotime($athlete['Athlete']['DataNascita'])))
				
				);
				
			}
			
			print json_encode($ret);
			
			//print_r($athletes);
			exit;
		}
		
		
		function sendMail()
		{
			$mails = ["primo","secondo","terzo","quarto","quinto"];
			
			//$tesserato = $mails;
			
			foreach($mails as $tesserato)
			{
				
				//echo $tesserato.'@associazioneiag.it'; 
				
				//echo $tesserato;
				
				$this->set('tesserato',$tesserato);
				
				$this->Email->to = array($tesserato.'@associazioneiag.it');
				
				$this->Email->from = 'Play League SSDARL <noreply@playleaguesport.it>';
				
				$this->Email->subject = 'Notifica nuovi tesseramenti';
				
				$this->Email->template = 'tesseramento_singolo'; 
				
				$this->Email->send();
			}
			
			
			
			exit;
		}
		
		
		// GIUSEPPE test ............................................... */
		function getMenuTest()
		{
			$this->layout = null;
			
			/* Get order from database */
			
			$order = $this->Order->find('first', array(
			
			'conditions' => array(
			
			'Order.model' => 'Page', 
			
			)
			
			));
			
			//debug($order);
			
			/**/
			
			if($this->Page->hasField($order['Order']['argument']) == false) $order['Order']['argument'] = 'title';
			
			$data = $this->Page->find('all', array('conditions' => array('Page.parent_id' => 0, 'Page.disabled' => 0)));	
			
			
			$menus = [];
			
			foreach( $data as $parent )
			{
				$menus[] = array('name' => $parent['Page']['title'],'children' => $this->getMenuByParent($parent["Page"]["id"], $order['Order']['argument'], $order['Order']['order_type']));
			}
			
			//debug($menus);
			
			echo json_encode($menus);
			
			//print_r($menus);
			exit;
			//return $menus;
			
		}
		/* ............................................................. */
		
	}
	
?>														