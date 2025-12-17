<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
?>    
<html lang="en" >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta http-equiv="Last-Modified" content="0">
	    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	    <meta http-equiv="Pragma" content="no-cache">
        <link rel="stylesheet" href="adminlte/dist/css/adminlte.css">
        <link rel="stylesheet" href="css/all_kpi.css">
        <style>
            .fullpage{
                position:absolute !important;
                top:0 !important;
                left:0 !important;
                width:100% !important;
                height:100% !important;
                z-index : 10000 !important;
            }
        </style>
    </head>
    <body class="general_ligth h-100 d-flex align-items-center justify-content-center">
        <h1 id="ln_wc" class="all_label2 extra_all2">Indicadores</h1>
    </body>
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/bootstrap/js/bootstrap.min.js"></script>
    <script>
        
        crear_frames();
        init();
        function crear_frames(){
            var data = {
            METODO:"get_ind_config",
            };
            $.ajax({
                type: "POST",
                data: data,
                dataType: 'json',
                async:false,
                url: "api/functions.php",
                success: function (data) {
                    console.log(data);
                    var div = document.body;
                    var frames =data.data;
                    frames.forEach(function(i,key){
                        var ifrm = document.createElement("iframe");
                        ifrm.setAttribute("src", "web/"+i.IND_NAME+".php");
                        ifrm.setAttribute("data-seconds", i.IND_DURATION);
                        ifrm.setAttribute("class", "iframe_indicador");
                        ifrm.setAttribute("frameborder", "0");
                        ifrm.setAttribute("allow", "fullscreen");
                        ifrm.setAttribute("id", i.SQ_ID);
                        ifrm.style.width = "100px";
                        ifrm.style.height = "100px";
                        div.appendChild(ifrm);
			ifrm.addEventListener("load", function() {
				console.log(ifrm.contentDocument);
			    ifrm.contentDocument.getElementById("indicador_id").value=i.SQ_ID;
				ifrm.contentWindow.exec_getdata();
			});
                    });
                    //init();
                },
                error: function (err) {
                    console.log(err);
                    //location.reload();
                },
            });
        }


        function init(){
            var iframes = document.getElementsByClassName("iframe_indicador");
            console.log(iframes.length);
            if (iframes.length>0){
                init_pages(0);
            }
        }

        function init_pages(i){
            var iframes = document.getElementsByClassName("iframe_indicador");
            if(i>=iframes.length){
                console.log("REset");
                i=0;
                clearTimeout(timeout);
                init_pages(i);
            }else{
                var seconds = iframes[i].getAttribute("data-seconds"),
                        id = iframes[i].getAttribute("id");
                remove_class();
                add_class(id);
                timeout = setTimeout(() => {
                    i++;
                    //console.log("Siguiente : " + data[i].IND_NAME);
                    init_pages(i);
                }, seconds*1000);
            }
        }
        
        function remove_class(){   
            console.log("REmover class");
            const iframes = document.querySelectorAll('.iframe_indicador');
            iframes.forEach(elm => {
                elm.classList.remove('fullpage');
            });
        }

        function add_class(id){
            console.log("add class: " + id);
            var element = document.getElementById(id);
            element.classList.add("fullpage");
        }
        
        // Validar si  el device necesita actualizarse
        function ValidaActualizacion() {
            let formData = new FormData();
            formData.append("METODO", "get_reload");
            fetch('../api/functions.php', {
                    method: "POST",
                    body: formData
                })
                .then((response) => response.json())
                .then((response) => {
                    let reload = response.data[0].DEV_RELOAD;
                    let diferencia = response.data[0].DIFERENCIA;
                    if (reload == 1 || diferencia == 1) {
                        location.reload();
                        updateReloadStatus();
                    } else {
                        setTimeout(() => {
                            ValidaActualizacion();
                        }, 15000);
                    }
                })
                .catch(err => {
                    setTimeout(() => {
                        ValidaActualizacion();
                    }, 15000);
                });
        }

        function updateReloadStatus() {
            let formData = new FormData();
            formData.append("METODO", "update_reload");
            fetch('../api/functions.php', {
                    method: "POST",
                    body: formData
                })
                .then((response) => response.json())
                .then((response) => {
                    console.log("Reload status updated.");
                })
                .catch(err => {
                    console.log("Error updating reload status:", err);
                });
        }
    </script>
</html>