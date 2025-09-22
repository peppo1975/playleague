					<li>tipo utente: <em><?=$currentUser['Group']['nome'];?></em></li>
					
					<? if($layout == "desktop"): ?>
					
					<li>ultimo accesso: <em><?=$this->Time->format("d/m/Y | \o\\r\e H:i:s",$this->Time->fromString($currentUser['User']['modified']));?></em></li>
					
					<? endif; ?>
			
				</ul>
				
				<ul class="right">
	
					
					<li><b><?=$currentUser['User']['username'];?></b></li>
					
					<? if($this->Session->read('User.group_id') == 1): ?>
					
					<?
					
						$count_spools = $this->requestAction('/admin/spools/count');
					
					?>
					
					<li><a href="/admin/spools/counter" title="<?=$count_spools;?> messaggi non inviati" <? if($count_spools > 0): ?>style="color: red;"<? endif; ?>>Messaggi NON inviati (<?=$count_spools;?>)</a></li>
					
					<? endif; ?>
					
					<li><a href="/admin/users/logout" title="Esci">Esci/Logout</a></li>

