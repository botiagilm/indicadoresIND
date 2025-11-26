var path = window.location.pathname;
var page = path.split("/").pop();
var intervals = new Array();
gethora();
intervals["hora"] = setInterval(gethora, 10000);

  
  function get_data(indicador,ind_id) {
    var data = {
        METODO:"get_ind",
        INDICADOR: indicador,
	IND_ID : ind_id
      };
      $.ajax({
        type: "POST",
        data: data,
        dataType: 'json',
        url: "../api/functions.php",
        success: function (res) {  
          //console.log(data);      
          init_transaccion(res.data,res.indicador);
        },
        error: function (err) {
          console.log(err);
          //location.reload();
        },
      });
  }



function gethora() {
  let date = new Date();
  let hour = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
  let minute =
    date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
  let second =
    date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
  let hora = hour + ":" + minute + ":" + second;
  let fecha =
    date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
  $("#fecha").html(fecha);
  $("#hora").html(hora);
}

function init_transaccion(data,indicador) {
  //console.log(data);
  data.forEach(function (key, value) {
    //console.log(key.process);
    //console.log(key.url);
    //console.log(key.duracion);
    if (key.process!="SQL"){
      var data = {
        transaction: key.T_URL,
        params: key.params,
        indicador: indicador,
      };
      ejecutar(data);
      intervals[value] = setInterval(ejecutar, key.duracion * 1000, data);
    }else{
      var data ={
        params: key.params
      };
      ejecutar_sql(data);
      intervals[value] = setInterval(ejecutar_sql, key.duracion * 1000, data);
    }
  });
}

function ejecutar_sql(data) {
  var aData={
    METODO:"get_data_sql",
    PARAMS:data.params
  }
  $.ajax({
    type: "POST",
    data: aData,
    dataType: 'json',
    url: "../api/functions.php",
    success: function (res) {
      var ind=res.data;
      console.log(ind.length);
      if(ind.length>0){
        generate(res.indicador, ind);
      }
      //generate(res.indicador, res.data);
    },
    error: function (err) {
      console.log(err);
      //location.reload();
    },
  });
}

function ejecutar(data) {
  console.log(data);
  var aData={
    METODO:"get_mii_query",
    PARAMS:data.params,
    TRANSACTION:data.transaction,
    INDICADOR: data.indicador,
  }
  $.ajax({
    type: "POST",
    data: aData,
    dataType: 'json',
    url: "../api/functions.php",
    success: function (res) {
      var ind=res.data;
      if(ind.length>0){
        generate(res.indicador, ind);
      }
    },
    error: function (err) {
      console.log(err);
      //location.reload();
    },
  });
}

function ejecutar_dinamico(oData,url,indicador) {  
  $.ajax({
    type: "POST",
    data: oData,
    url: url,
    success: function (data) {
      generate(indicador, data);
    },
    error: function (err) {
      console.log(err);
      location.reload();
    },
  });
}



function generate(url, data) {
  var fnc = ( url.indexOf("/") !== -1 ? url.split("/").pop():url);
  console.log(fnc);
  switch (fnc.toUpperCase()) {
    case "OPERACION_TURNO":
      //console.log(data);
      reset_operacion_turno();            
      set_wc(url);
      set_operacion_turno(data);
      break; 
    case "PRODUCCION_PRENSAS":
      //console.log(data);
      reset_produccion_prensas();
      set_produccion_prensas(data);
      break;   
    case "PRODUCCION":
      //console.log(data);
      //reset_produccion();
      //updateDashboard(data);
      break;   
    case "PRODUCCION_GRAPH":      
      reset_produccion_graph();
      set_produccion_graph(data);
      break;
    case "TIME_CONTROL":
      reset_time_control();
      set_timecontrol(data);
      break;
  }
}

function set_wc(wc) {
  $("#ln_wc").html(wc);
}

function set_velocidad(data, est_linea) {
  if (est_linea != "PNP" && est_linea != "PP") {
    let vel = "/ " + data.META + " -" + data.UM;
    $("#vel_real").removeClass();
    $("#vel_real").html(data.VELOCIDAD);
    $("#vel_meta").html(vel);
    switch (data.COLOR) {
      case "V":
        $("#vel_real").addClass("velocidadVerde");
        break;
      case "A":
        $("#vel_real").addClass("velocidadAmarillo");
        break;
      case "R":
        $("#vel_real").addClass("velocidadRojo");
        break;
      default:
        break;
    }
  } else {
    $("#vel_real").html("");
    $("#vel_meta").html("");
  }
}

function toCommas(value) {
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}



function valida_meta(valor1,valor2){
  var color = "";
  if(valor1 ==0 ){
      color="BLACK";
  }else if(parseFloat(valor1) < parseFloat(valor2)){
      color="RED";
  }else{
      color="GREEN";
  }
  return color;
}
