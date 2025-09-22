<?

function checkEmbed($value) {

	$tmp = str_replace('<iframe', '', $value);
	
	if($tmp != $value) {
	
		$value = 'Embed code';
	
	}
	
	return $value;

}

	class StreamsController extends AppController {
	
			var $name = "Streams";
			var $helpers = array('Backend','Javascript','Cksource');
			var $uses = array('Stream','Post');
						
			function admin_index() {
				
					
				
			}
			
			
			function admin_filters() {
				
				$this->layout = "ajax";
				
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchFilters",$this->data['searchFilters']);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
			}
			
			function admin_search() {
				
				$this->layout = "ajax";	
						
				if (!empty($this->data)) {
					
					$this->Session->write($this->name . ".searchData",$this->data);
					$this->set('result','RELOAD_OK');
					$this->render('/backend/ajaxResult');
					
				}
				
				if ($this->Session->check($this->name . ".searchData",$this->data)) {
					
					$this->data = $this->Session->read($this->name . ".searchData");
					
				} 
			
			}

 			function admin_add() {
			
				$this->layout = "ajax";	
				
				if (!empty($this->data)) {
				
					$this->Stream->set($this->data);
					
					if ($this->Stream->save()) {
						
						$ADD_OK = true;
							
						if ($ADD_OK) {
									
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						
						}
						
					}
					
				}
				
			}
						
			function admin_edit($id) {
			
				$this->layout = "ajax";

				if (empty($this->data)) {
								
					$this->data = $this->Stream->find('first',array('conditions' => array('Stream.id' => $id)));
					$this->Stream->set($this->data);
				
				} else {
										
				$this->Stream->set($this->data);
				
				$ADD_OK = true;

					if ($this->Stream->save()) {
													
						if ($ADD_OK) {
							$this->set('result','ADD_OK');
							$this->render('/backend/ajaxResult');
						}	
					}
					
				}
			
			}	
			
			function admin_setStream($id) {
			
				$this->layout = "ajax";
				
				if($this->Stream->updateAll(array('Stream.disabled' => 1), array('Stream.id !=' => $id))) {
				
					$set = 1;
				
				} else {
				
					$set = 0;
				
				}
				
				$this->set('result', json_encode(array('set' => $set)));
				$this->render('/backend/ajaxResult');
			
			}
			
			function getStream() {
			
				$stream = $this->Stream->find('first', array(
				
						'conditions' => array(
						
							'Stream.disabled' => 0
						
						)
				
					)
				
				);
				
				if(!empty($this->params['requested'])) {
				
					return $stream;
				
				}
			
			}
			
			function embed($id) {
			
				$this->layout = "ajax";
							
				$post = $this->Post->findById($id);								
				$path = $post['Upload'][0]['path'];
				
				$this->set('path', $path);
			
			}
			
			function embedStreaming($id) {
			
				$this->layout = "ajax";
				
				$stream = $this->Stream->findById($id);
				
				$this->set('stream', $stream);
			
			}
	
	}
