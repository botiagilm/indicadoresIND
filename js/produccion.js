
function exec_getdata(){
	var ID = document.getElementById("indicador_id").value;
	get_data("PRODUCCION",ID);
}

function reset_produccion(){
    $("#ln_wc").html("");
    $("#ln_kg_dia").html("");
    //$("#ln_kg_noche").html("");    
    $("#ln_kg_hora").html("");
}

function set_produccion(data){
    console.log(data[0].WORK_CENTER);
    $("#ln_wc").html(data[0].WORK_CENTER);
    //$("#ln_kg_noche").html(data.NOCHE_KG.toFixed(0));
    $("#ln_kg_dia").html(toCommas(Math.trunc(data[0].ACUM_DIA)));
    $("#ln_kg_hora").html(toCommas(Math.trunc(data[0].KG_HRA)));
    var color_kghora = valida_meta(data[0].KG_HRA,data[0].META_KGHRA);
    var color_acum = valida_meta(data[0].ACUM_DIA,data[0].META_DAY);
    $("#ln_kg_dia").css("color",color_acum);
	$("#ln_kg_hora").css("color",color_kghora);
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