<?

	class DashboardsController extends AppController {
	
			var $name = "Dashboards";
			var $login_required = true;
			var $helpers = array('Backend');
			var $uses = array();
			var $components = array('Session','Cookie');
						
			function admin_index() {
				
					
				
			}
				
			function admin_setType() {
				
				$type = $_POST['type'];
				
				$this->Session->write('admin_type',$type);
				
				$this->layout = "ajax";
				
				exit;
				
			}
			
			function admin_setDataType() {
				
				$type = $_POST['type'];
				
				//$this->Session->write('admin_data_type',$type);
				
				$this->Cookie->write('admin_data_type',$type,false, 3600);
				
				$this->layout = "ajax";
				
				exit;
				
			}			
				
	}
