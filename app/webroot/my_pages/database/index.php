<style>
    .elementoRestore:hover {
        cursor: pointer;
        background-color: yellowgreen;
    }
</style>
<a href="backup.php" target="_blank">BACKUP</a>
<br>
<br>
<br>
<br>
<a id="restore">DOWNLOAD</a>
<div id="list">

</div>

<script src="../../js/script_my.js" type="text/javascript"></script>
<script>
    class leggiBackup {
        listDiv;
        elementoRestore;
        constructor() {
            this.listDiv = document.querySelector("#list");
        }

        async jsonElenco() {
            var json = await httpPost('./db/listFilesJson.php', {});
            this.listDiv.innerHTML = "";

            var ul = document.createElement('ul');

            json.forEach((item, i) => {
                var li = document.createElement('li');
                li.innerHTML = item;

                li.classList.add('elementoRestore');
                li.setAttribute('value', item);
                ul.appendChild(li);
            });

            this.listDiv.appendChild(ul);

            this.elementoRestore = document.getElementsByClassName('elementoRestore');

            this.clickElementoRestore();
        }

        clickElementoRestore() {
            Object.keys(this.elementoRestore).forEach((index) => {
                this.elementoRestore[index].addEventListener('click', (val) => {
                    console.log(val);
                    //alert('test');
                    //window.open('restore.php?file=' + val.srcElement.innerText, '_blank'); //restore ma solo in sviluppo
                    window.open('db/' + val.srcElement.innerText, '_blank'); //download

                });
            })
        }

    }
</script>
<script>

    var lb = new leggiBackup();
    var restore = document.getElementById("restore");

    restore.addEventListener('click', () => {
        lb.jsonElenco();
    });

    document.addEventListener("DOMContentLoaded", function () {
        lb.jsonElenco();
    });

    setInterval(() => {
        lb.jsonElenco();
    }, 1000);

</script>