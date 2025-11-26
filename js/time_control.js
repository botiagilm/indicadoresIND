
function exec_getdata(){
	var ID = document.getElementById("indicador_id").value;
	get_data("TIME_CONTROL",ID);
}

function reset_time_control(){
    $("#ln_wc").html("");  
    $("#tbl_body").empty();
}


function set_timecontrol(data){
    var body="";
    $("#ln_wc").html("DADOS " + data[0].WORK_CENTER);
    if(data == undefined){
        $("#tbl_body").append(body);
        return;
    }
    data.forEach(function(key,value){
        body += '<tr class="table_tr '+ key.ESTATUS_PRECALENTADO.toUpperCase()+'">';
       // body += '<td class="all_label extra2" style="text-align:center;">'+key.HER_MATERIAL_DESC+'</td>';
        body += '<td class="all_label extra2">'+key.HER_SERIAL+'</td>';
        body += '<td class="all_label extra2">'+key.MIN_PRECALENTADO +'-'+ key.MAX_PRECALENTADO +'</td>';
        body += '<td class="all_label extra2">'+key.F_IN_PRECALENTADO+'</td>';
        body += '<td class="all_label extra2">'+key.TIEMPO_PRECALENTADO+'</td>';
        //body += '<td class="all_label extra2">'+key.ESTATUS_PRECALENTADO+'</td>';
        body += "</tr>";
    });
    $("#tbl_body").append(body);
}