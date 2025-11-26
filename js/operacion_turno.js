function exec_getdata(){
	var ID = document.getElementById("indicador_id").value;
	get_data("OPERACION_TURNO",ID);
}

function reset_operacion_turno(){
    $("#toneladas").html("");
    $("#ln_wc").html("");
    $("#vel_real").html("");
    $("#vel_meta").html("");    
    $("#tbl_body").empty();
    $("#actual").html("");    
}

function set_orden_activa(data){
    var tonin= parseFloat(data.QTY_DONE.replace(/,/g, ''));
    var tontot= parseFloat(data.QTY_TO_BUILD.replace(/,/g, ''));
    let toneladas = ((tonin/1000) + " / " + (tontot/1000)) + " TON";
    $("#toneladas").html(toneladas);
    $("#of").html("OF "+ data.SHOP_ORDER);
}

function set_operacion_turno(data){
    var body="";
    var prd_mes=0;
    var prd_diaant=0;
    var prd_diaacum=0;
    data.forEach(function(key,value){
        var color_acum = valida_meta(key.ACUM_DIA,key.META_DAY);
		var color_kghora = valida_meta(key.KG_HRA,key.META_KGHRA);
        body += '<tr class="table_tr">';
        body += '<td class="all_label extra3" style="text-align:center;">'+key.WORK_CENTER+'</td>';        
        body += '<td class="all_label extra3">'+toCommas(parseFloat(key.DIA).toFixed(0))+'</td>';
        body += '<td class="all_label extra3">'+toCommas(parseFloat(key.NOCHE).toFixed(0))+'</td>';
        body += '<td class="all_label extra3" style="color:'+color_acum+' !important" >'+toCommas(parseFloat(key.ACUM_DIA).toFixed(0))+'</td>';
        body += '<td class="all_label extra3" style="color:'+color_kghora+' !important">'+toCommas(parseFloat(key.KG_HRA).toFixed(0))+'</td>';
        body += '<td class="all_label extra3">'+toCommas(parseFloat(key.ACUM_MES).toFixed(0))+'</td>';
        body += "</tr>";
        prd_mes += parseFloat(key.ACUM_MES);
        //prd_diaant += parseFloat(key.DIA_ANT);
        prd_diaacum += parseFloat(key.ACUM_DIA);
    });
        body += '<tr class="table_tr">';
        body += '<td class="all_label extra3" style="text-align:center;">Total</td>';        
        body += '<td class="all_label extra3 "></td>';
        body += '<td class="all_label extra3 "></td>';
        body += '<td class="all_label extra3 ">'+toCommas(parseFloat(prd_diaacum).toFixed(0))+'</td>';
        body += '<td class="all_label extra3 "></td>';
        //body += '<td class="all_label extra3 ">'+toCommas(parseFloat(prd_diaant).toFixed(0))+'</td>';
        body += '<td class="all_label extra3 ">'+toCommas(parseFloat(prd_mes).toFixed(0))+'</td>';
        body += "</tr>";
    $("#tbl_body").append(body);
    $("#meta_real").html(toCommas(parseFloat(prd_mes).toFixed(2)));
}

function get_color(estatus){
    var color="";
    switch(estatus){
        case "1":
            color="td_green";
            break;
        case "2":
            color="td_yellow";
            break;
        case "3":
            color="td_red";
            break;
        default:
            color="td_neutro"
            break;
    }
    return color;
}