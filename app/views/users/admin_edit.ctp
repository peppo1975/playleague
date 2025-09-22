<script type="text/javascript">
if (typeof $ != "undefined") {
$("#genera").click(function() {
$.get("/admin/users/generatepwd",function(ret) {
	$("#UserPassword").val(ret.pwd);
	$("#UserPasswordConfirm").val(ret.pwd);
	},'json');
 });
 }
</script>
<?=$this->element("/backend/add_edit_scripts");?>
	<?=$this->element("/backend/edit_scripts");?>

	<?=$this->Form->create('User', array('action' => 'edit','prefix' => 'admin','class' => 'formAdd','type' => 'file'));?>

	<div class="form_header">

								<h2>Modifica utente: <span><?=$this->data['User']['username'];?></span></h2>
								<ul>
	
									<li><?=$this->Form->submit('reset campi',array('type'=>'reset','div' =>false));?></li>
									<li><?=$this->Form->submit('annulla',array('type' => 'button','div' => false,'id' => 'formReset'));?></li>
									<li><?=$this->Form->submit('modifica',array('type' => 'submit','div' => false));?></li>
								</ul>
								<div class="clear"></div>

	</div><!-- close form_header -->
	
	<?=$this->Form->input('nome', array('label' => 'Nome'));?>
	<?=$this->Form->input('cognome', array('label' => 'Cognome'));?>
	<? // print_r($_SESSION) ?>
	<div class="clear"></div>
	
	<?=$this->Form->input('username', array('label' => 'Email'));?>
	<?=$this->Form->input('old_username', array('type' => 'hidden'));?>
	
	<?=$this->Form->input('NomeAtleta',array('label' => 'Atleta', 'class' => 'searchAthlete', 'data-url' => '/admin/athletes/searchAthlete','data-dest' => 'UserAthleteId'));?>
	<?=$this->Form->input('athlete_id',array('type' => 'hidden'));?>	
	
	<div class="clear"></div>
	
	<?=$this->Form->input('password', array('label' => 'Password'));?>
	<?=$this->Form->input('old_password', array('type' => 'hidden'));?>
	
	<?=$this->Form->input('password_confirm', array('label' => 'Conferma password', 'type' => 'password'));?>

	<div class="input">
	<label>&nbsp;</label>
	<?=$this->Form->submit('Genera password',array('type' => 'button','div' => false,'id' => 'genera'));?>
	</div>
	
	<div class="clear"></div>
		
	<?
	$options = array();
	foreach($groups as $group) {
	  $options[$group['Group']['id']] = $group['Group']['nome'];
	 }
	?>
	
	<?=$this->Form->input('group_id', array('label' => 'Gruppo', 'type'=>'select', 'options' => $options));?>
	<?=$this->Form->input('campo_id', array('label' => 'Campo', 'type'=>'select', 'options' => $campi, 'empty' => '-'));?>
	

        <!-- //GIUSEPPE 2023-07-28 -------------------------------- -->
        <label>&nbsp;</label>
        <!--<button id="addCampo" style="display: none">AGGIUNGI ALLA LISTA DEI CAMPI</button>-->
        <a id="addCampo" style="display: none; cursor: pointer; float:left; padding: 5px 10px; border: 1px solid #ccc; border-radius: 5px;">Associa campo</a>
        
        <div class="clear"></div>  
        
        
        <label id="listaCampiLabel" >LISTA CAMPI</label>
        <select  id="listaCampi" style="width: 200px; height: 200px; display: none" multiple>

        </select>
        <?//=$idUtente?>
        <label>&nbsp;</label>
        <a id="saveList" style="display: none; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius: 5px; float:left; margin-right: 20px; background: #6fb406; color:#fff;">Salva lista</a>
        <a id="deleteToList" style="display: none; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius: 5px; float:left; background: #ff3e39; color:#fff;">Cancella associazione</a>
        <div class="clear"></div>
        <div id="messageOK" style="color: green; font-weight: bold; display: none">
            <hr>
            INSERIMENTO OK
        </div>
        <!-- --------------------- ------------------------------- -->
        
	<?=$this->Form->end();?>

        <!-- //GIUSEPPE 2023-07-28 ------------------------------- -->
        
        <script>
//        alert("test");
        var UserGroupId = document.getElementById("UserGroupId");
        var UserCampoId = document.getElementById("UserCampoId");
        var listaCampi = document.getElementById("listaCampi");
        var addCampo = document.getElementById("addCampo");
        var saveList = document.getElementById("saveList");
        var deleteToList = document.getElementById("deleteToList");
        var idCampiScelti = [];
        var edited = false;



        readCampi();
        
        var event = new Event('change');
       
        
        UserGroupId.addEventListener('change', (e)=>{
            console.log(e);           
           var valueSelect = parseInt(e.target.value);
           
           if(valueSelect === 15)
           {
                listaCampiLabel.style.display = 'block';
                listaCampi.style.display = 'block';
                addCampo.style.display = 'block';
           }
           else
           {
                listaCampiLabel.style.display = 'none';
                listaCampi.style.display = 'none';
                addCampo.style.display = 'none';
           }
           
        });
        
        
        addCampo.addEventListener('click',()=>{
            var value = UserCampoId.value;
            var selectedIndex = UserCampoId.selectedIndex;
            var name = UserCampoId[selectedIndex].innerText;
            
            var isFound = idCampiScelti.includes(value);
            if(isFound)
                return 0;
            
            
            idCampiScelti.push(value);
            edited = true;
            saveList.style.display = 'block';
            const option = document.createElement("option");
            option.value = value;
            option.innerHTML = name;
            listaCampi.appendChild(option);  
            
        });
        
        
        listaCampi.addEventListener('change',(e)=>{
            //alert('test');
            console.log(e);
            const values = Array.from(listaCampi.selectedOptions).map(el => el.value);
            
            if(values.length > 0)
            {
                deleteToList.style.display = 'block';
            }
            else
            {
                 deleteToList.style.display = 'none';
            }
        });
        
        deleteToList.addEventListener('click',()=>{
            const values = Array.from(listaCampi.selectedOptions).map(el => el.value);
            deleteToList.style.display = 'none';
                values.map((val)=>{
                var remove ;
                Object.keys(listaCampi.options).forEach((i)=>{
                          console.log(i);
                             if (listaCampi.options[i].value === val)
                             {
                                remove = i;
                                
                                function canVote(age) {
                                    return age != val;
                                }

                                idCampiScelti = idCampiScelti.filter(canVote);
                                edited = true;
                                 saveList.style.display = 'block';
                             }
                });      

                listaCampi.remove(remove);
            });
        });
        
        
        saveList.addEventListener('click',()=>{
            console.log(idCampiScelti);
            var utente =   <?=$idUtente?>
            
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/admin/users/insertCampi/");
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
            
            const body = JSON.stringify({
                Utente: utente,
                Campi: idCampiScelti,
              });
            xhr.onload = () => {
                if (xhr.readyState == 4 && xhr.status == 200) {
                   saveList.style.display = 'none';
                   document.getElementById("messageOK").style.display = 'block';
                   setTimeout(()=>{
                       document.getElementById("messageOK").style.display = 'none';
                   },1500);
                } else {
                  console.log(`Error: ${xhr.status}`);
                }
              };
            xhr.send(body);
        });
        
        UserGroupId.dispatchEvent(event);
        
        // -------------------------------------------------------------------------------
        
        function readCampi()
        {
            var utente =   <?=$idUtente?>
            
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "/admin/users/readCampi/" + utente);
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

          xhr.onload = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                
                var res = JSON.parse(xhr.responseText);
                console.log(res);
              
                listaCampi.innerHTML = "";
              
              res.map((val)=>{
                const option = document.createElement("option");
                option.value = val.Campo;
                option.innerHTML = val.Descrizione;
                listaCampi.appendChild(option);  
                
                idCampiScelti.push(val.Campo);
                
              });
              
            } else {
              console.log(`Error: ${xhr.status}`);
            }
          };
          xhr.send();
        }

        </script>