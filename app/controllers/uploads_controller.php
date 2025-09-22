<?

	class UploadsController extends AppController {
	
			var $name = "Uploads";
			var $login_required = true;
			var $helpers = array('Backend','Javascript','Cksource');
			var $uses = 'Upload';
			
	
			function admin_delete($id) {
				
				$this->layout = "ajax";
				
				$data = $this->Upload->findById($id);
				
				if (!empty($data)) {
					
	
				@unlink(APP . '/webroot/' . $data['Upload']['path']);
				
				$ret = $this->Upload->delete($id,true);
						
				$this->set('ret',json_encode( array( 'ret' => $ret ) ));
						
				}
						
				return $this->render('/backend/ajax');
					
			}
			
			function delete($id) {
				
				$this->layout = "ajax";
				
				$data = $this->Upload->findById($id);
				
				if (!empty($data)) {
					
	
				@unlink(APP . '/webroot/' . $data['Upload']['path']);
				
				$ret = $this->Upload->delete($id,true);
						
				$this->set('ret',json_encode( array( 'ret' => $ret ) ));
						
				}
						
				return $this->render('/backend/ajax');
					
			}			
			
			function admin_edit($id) {
				
				$this->layout = "timmybox";
				
				if (empty($this->data)) {
					
					$this->data = $this->Upload->findById($id);
					
					$this->Upload->set($this->data);
					
				} else {
					
					$this->layout = "timmybox_ajax";
					$testdata = $this->Upload->findById($id);

					if ($testdata['Upload']['group']== 'youtube') {
		
					
		
								$data = json_decode(file_get_contents("http://www.youtube.com/oembed?url=" . $this->data['Upload']['name'] . "&amp;format=json"),1);
								$filename = Inflector::Slug($this->data['Upload']['name']) . ".jpg";
								@unlink(APP . '/webroot/files/uploads/' . $filename);
								file_put_contents(APP . '/webroot/files/uploads/' . $filename,file_get_contents($data['thumbnail_url']));
		
								@chmod(APP . '/webroot/files/uploads/' . $filename,0644);
		
								$this->data['Media']['name'] = $this->data['Upload']['name'];
								$this->data['Media']['path'] = '/files/uploads/' . $filename;
		
								$upload['name'] = $this->data['Media']['name'];
								$upload['path'] = $this->data['Media']['path'];
								$upload['type'] = "youtube";
								$upload['group'] = "youtube";
								$upload['ext'] = "jpg";
								$upload['tag'] = $this->data['Media']['tag'];								
								$upload['description'] = $this->data['Media']['description'];

								$saveData = array_merge($this->data['Upload'],$upload);
								
								$this->Upload->set($saveData);
								
								if (!$this->Upload->save()) return false;
								
		
								return true;
		
							} else {
					$this->Upload->set($this->data);
					
					$this->Upload->save();
					
					}
					
				}
				
				
			}
			
			function edit($id = null) {
				
				if (empty($this->data)) {
					
					$this->layout = "timmybox";
					
					$this->data = $this->Upload->findById($id);
					
					$this->Upload->set($this->data);
					
				} else {
					
					$this->layout = "ajax";
					
					

					
					$this->Upload->set($this->data);
					
					$this->Upload->save();
					
					$this->set('result', json_encode(array('ok' => 1, 'id' => $this->data['Upload']['id'],'title' => $this->data['Upload']['title'], 'description' => $this->data['Upload']['description'])));
					$this->render('/backend/ajaxResult');
					
				}
				
				
			}			
			
			function evidenza($element_id, $file_id, $model) {
			
				$this->layout = "ajax";
				
				App::Import('Model', $model);
				$Element = new $model;
				
				$Element->read(null, $element_id);
				$this->Upload->read(null, $file_id);
				
				if($Element->field('file_id') == $file_id) {
				
					$evidenza = 0;
					$Element->set('file_id', 0);
					$this->Upload->set('isEvidenza', 0);
					
					$this->Upload->save();
					$Element->save();					
					
				} else {
				
					$evidenza = 1;
					$Element->set('file_id', $file_id);
					$this->Upload->set('isEvidenza', 1);
					
					$this->Upload->save();
					$Element->save();					
				
				}
				
	
				$this->Upload->updateAll(array('Upload.isEvidenza' => 0), array('Upload.id !=' => $file_id,strtolower($model).'_id' => $element_id));

				$this->set('result', json_encode(array('check' => $evidenza)));
				$this->render('/backend/ajaxResult');
			
			}
			
			public function ajax_upload() {
 
				$this->layout = "upload";
 
			}
			
			function admin_sortableOrder($model = 'Upload') {
				
				$this->layout = "ajax";

				foreach($_POST['Data'] as $order => $id) {
					
					$this->data = $this->Upload->findById($id);
					$this->data['Upload']['order'] = $order;
					$this->Upload->set($this->data);
					$this->Upload->save();
				
				}
				
				$this->set('result', json_encode(array('order' => 1)));
				$this->render('/backend/ajaxResult');			
				
			}					
	
	}
	
?>
