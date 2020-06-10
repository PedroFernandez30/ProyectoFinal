/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
require('../css/app.scss');
import DOMPurify from 'dompurify';
/// app.js

//const $ = require('jquery');
//import $ from 'jquery';
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
const $ = require('jquery');
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

/*$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});*/

$(document).ready(function(){

  

 //Guarda el último input radio pulsado en el filtrado del buscador por tipo
  var lastTipoPulsado = null;

  //Guarda el último input radio pulsado en el filtrado del buscador por fecha
  var lastFechaPulsada = null;


  //permitirEditarForm();
  permitirUsoBuscador();

  window.permitirEditarForm = permitirEditarForm;
  window.DOMPurify = require('dompurify');
  window.mgOrDislike = mgOrDislike;
  window.borrarUserIdentfied = borrarUserIdentfied;
  window.filtrarPorTipo = filtrarPorTipo;
  window.limpiarForm = limpiarForm;
  window.cargarRutaActual = cargarRutaActual;
  window.filtrarPorFecha = filtrarPorFecha;
  window.borrarVideo = borrarVideo;
  window.detectarDivVideos = detectarDivVideos;
  window.irAConfigPorPost = irAConfigPorPost;
  window.escribirOMostrarComentario = escribirOMostrarComentario;
  window.activarBotonComentar = activarBotonComentar;
  window.cambiarTabActiva = cambiarTabActiva;
  window.mostrarVideos = mostrarVideos;
  window.toggleSuscripcion = toggleSuscripcion;
  window.borrarComentario = borrarComentario;
  window.borrarMensajeFlash = borrarMensajeFlash;

    $('.dropdown-toggle').dropdown();

    function borrarUserIdentfied(event, path) {
      event.preventDefault();
      console.log("PATH "+path);
    }

    function limpiarForm(id) {
      var form = document.getElementById(id);
      var labels = form.querySelectorAll("label[class=custom-file-label]");
      for(var i = 0; i< labels.length; i++) {
         labels[i].innerHTML = '';
      }
      form.reset();
      //$('#'+id).trigger('reset');
    }

    function cargarRutaActual(url) {
      localStorage.setItem("url2", url);
      var ruta1 = localStorage.getItem("url1");
      var ruta2 = localStorage.getItem("url2");
      if(ruta1 == ruta2) {
        var divContenidoBuscador = document.getElementById("contenidoBuscador");
        var contenidoBuscador = localStorage.getItem("contenidoBuscador");
        divContenidoBuscador.innerHTML = '';
        divContenidoBuscador.innerHTML = contenidoBuscador;
      }
      console.log(url);
    }

    /*
            "1,0" => addMg
            "0,1" => addDislike
            "-1,0" => removeMg
            "0,-1" => removeDislike
            "1,-1" => addMg, removeDislike
            "-1,1" => removeMg, addDislike
        */
    function mgOrDislike(url, situacion, idVideo) {
      console.log(url);
      console.log(situacion);
      var divMgDislike = document.getElementById("divMgDislike");
      var buttonsMgDislike = divMgDislike.querySelectorAll("button");
      for (let index = 0; index < buttonsMgDislike.length; index++) {
        buttonsMgDislike[index].disabled = true;
        
      }
      
      $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: {
          "situacion": situacion,
          "idVideo": idVideo, 
        },
        async: true,
        success: function (data)
        {
          console.log(data);
          var divMgDislike = document.getElementById("divMgDislike");
          divMgDislike.innerHTML = '';
          divMgDislike.innerHTML = data.contenido;
        }
         
    });
    return false;
      
    }

    //impedir que se manden los datos de edición de un canal si no se han modificado
  function permitirUsoBuscador() {
    var form = $('#formBusqueda');
    var inputBusqueda = document.getElementById("textoBusqueda");
    var valorOriginal = inputBusqueda.value;
    
    console.log(valorOriginal);
    
    console.log(form);
    console.log("Entro en permitir usar buscador");
    if(form.length) {
      var submit = document.getElementById("submitBusqueda");
      submit.setAttribute('disabled','disabled');

      console.log("Hay form length");
      inputBusqueda.addEventListener('keyup', function() {
        console.log(inputBusqueda.value);
        console.log(valorOriginal);
        if(inputBusqueda.value != valorOriginal) {
          submit.removeAttribute('disabled');
        } else {
          submit.setAttribute('disabled', 'disabled');
        }
      })
    }
  }

  //Ver los vídeos o canales del buscador 
  
    function filtrarPorFecha(radio, url) {
      var inputRadio = radio;
      console.log(videos);
      console.log(url);
      if(inputRadio != lastFechaPulsada) {
        lastFechaPulsada = inputRadio;
        console.log(inputRadio.value);
        switch(lastFechaPulsada.value) {
          case 'hoy':
            var fechaABuscar = 1;
            $('#filtroTipo').hide();
            $('#canalesEncontrados').hide();
            buscarPorFecha(fechaABuscar, url);
            break;
          case 'semana':
            var fechaABuscar = 7;
            $('#filtroTipo').hide();
            $('#canalesEncontrados').hide();
            buscarPorFecha(fechaABuscar, url);
            break;
          case 'mes':
            var fechaABuscar = 30;
            $('#filtroTipo').hide();
            $('#canalesEncontrados').hide();
            buscarPorFecha(fechaABuscar, url);
            break;
          case 'anyo':
            $('#filtroTipo').hide();
            $('#canalesEncontrados').hide();
            var fechaABuscar = 365;
            buscarPorFecha(fechaABuscar, url);
            break;
          case 'limpiar':
            $('#filtroTipo').show();
            $('#videosEncontrados').show();
            $('#canalesEncontrados').show();
            //$('#videosEncontradosFiltrados').hide();
            var divVideosEncontrados = document.getElementById("videosEncontradosFiltrados");
            if(divVideosEncontrados != null) {
              divVideosEncontrados.classList.remove("d-block");
              divVideosEncontrados.classList.remove("d-none");
              divVideosEncontrados.classList.add("d-none");
            }
            
            
            break;
        }
      }
    }

    //Llama al controlador pasándole los días atrás que se tiene remontar
    function buscarPorFecha(dias, url) {
      $("#filtroFecha :input").attr("disabled", true);
      var spinner = document.getElementById("modalSpinner");
      var palabraBuscada = document.getElementById("valorBusqueda");
      console.log(spinner);
      $('#modalSpinner').show();
      $.ajax({
        url: url,
        type: "POST",
        dataType: "text",
        data: {
          "diasARestar": dias,
          "valor": palabraBuscada.value
        },
        async: true,
        success: function (data)
        {
          $('#modalSpinner').hide();
          $("#filtroFecha :input").attr("disabled", false);
            console.log("SUCCESS");
            //console.log(data.videosFiltrados);
            var dataObject = JSON.parse(data);
            $('#videosEncontrados').hide();
            var divVideosEncontrados = document.getElementById("videosEncontradosFiltrados");
            console.log(dataObject);
            console.log(dataObject.videosFiltradosAVer);
            console.log(dataObject.contenido);
            divVideosEncontrados.classList.remove("d-none");
            divVideosEncontrados.classList.add("d-block");
            divVideosEncontrados.innerHTML = '';
            divVideosEncontrados.innerHTML = dataObject.contenido.content;
        }
    });
    return false;
    }

    //Ver los vídeos o canales del buscador 
  
    function filtrarPorTipo(radio) {
      var inputRadio = radio;
      if(inputRadio != lastTipoPulsado) {
        lastTipoPulsado = inputRadio;
        console.log(inputRadio.value);
        switch(lastTipoPulsado.value) {
          case 'canales':
            $('#videosEncontrados').hide();
            $('#canalesEncontrados').show();
            break;
          case 'videos':
            $('#canalesEncontrados').hide();
            $('#videosEncontrados').show();
            break;
          case 'todos':
            $('#videosEncontrados').show();
            $('#canalesEncontrados').show();
            break;
        }
      }
    }
  
    $('#formBusqueda').submit(function(e) {
      console.log("ESTOY EN FORM BÚSQUEDA");
        e.preventDefault();
        var inputBusqueda = document.getElementById("textoBusqueda");
        console.log(inputBusqueda.value);
        var submitBuscar = document.getElementById("submitBusqueda");
        submitBuscar.setAttribute('disabled','disabled');
    
        var url = e.target.action;
        rellenarSpinner();
        $('#modalSpinner').modal('show');

        $.ajax({
          url: url,
          type: "POST",
          dataType: "text",
          data: {
            "value": inputBusqueda.value
          },
          async: true,
          success: function (data)
          {
              $('#modalSpinner').modal('hide');
              document.getElementById("cuerpoModalSpinner").innerHTML = '';
              console.log("SUCCESS");
              console.log(data);
              localStorage.setItem("contenido",data);
              var divContenidoBuscador = document.getElementById("contenidoBuscador");
              var dataObject = JSON.parse(data);
              console.log(dataObject.canalesSerialize);
              divContenidoBuscador.innerHTML = '';
              divContenidoBuscador.innerHTML = dataObject.contenido;
              localStorage.setItem("contenido",dataObject.contenido);
              var palabraBuscada = document.getElementById("valorBusqueda");
              palabraBuscada.value = inputBusqueda.value;
              //console.log(data);
              
              //console.log(dataObject);
              inputBusqueda.value = '';
              permitirUsoBuscador();
              
          }
      });
      return false;
      });
  
    
    function irAConfigPorPost(event, rutaBoton = '') {
      //Creo un formulario con los datos del enlace
      console.log(event);
      console.log(rutaBoton);
      
      var form = document.createElement('form');
      
      form.method = 'POST';
      form.setAttribute('id', 'anular');
      if (rutaBoton != '') {
          form.action = rutaBoton;
      }
      else {
          form.action = event.target.href;
      }
      form.action = event.target.href;
      //form.action = "/prueba/";
      var input = document.createElement("input");
      input.type = "hidden";
      input.name = "previo";
      input.value = rutaBoton;

      form.appendChild(input);
      document.body.appendChild(form);

      console.log(form.action);
      console.log(form);
      form.submit();
      return false;
  }

  //suscribirse o borrar la suscripción mediante AJAX
  function toggleSuscripcion(modo, idVideo, url, canalSuscritoId, canalAlQueSuscribeId, token = null ) {
    var link = event.target;
    link.onclick = function() {
      return false;
    }
    link.classList.add('disabled');
    console.log(link);
    console.log(token);
    var that = $(this);
    if(modo == 'borrar') {
      var tipo = "DELETE"; 
      var datos = {
        "canalSuscritoId": canalSuscritoId,
        "canalAlQueSuscribeId": canalAlQueSuscribeId,
        "idVideo": idVideo,
        "_token": token
      };
    }else {
      var tipo = "POST";
      var datos = {
        "canalAlQueSuscribeId": canalAlQueSuscribeId,
        "idVideo": idVideo
      };
    }
    $.ajax({
        
        url: url,
        type: tipo,
        dataType: "json",
        data: datos,
        async: true,
        success: function (data)
        {
            console.log(data);
            console.log(data.numeroSuscriptores);
            var divToggleSuscripcion = document.getElementById("toggleSuscripcion");
            divToggleSuscripcion.innerHTML = '';
            divToggleSuscripcion.innerHTML = data.contenido;
            var numSuscripciones = document.getElementById("numSuscriptores");
            numSuscripciones.textContent = data.numeroSuscriptores + " suscriptor(es)";

            borrarMensajeFlash("suscripcionFlash");

            

        }
    });
    return false;
  }

  function borrarMensajeFlash(id) {
    console.log("borrarSuscripcionFlash");
    var suscripcionFlash = $("#"+id);
      if(suscripcionFlash.innerHTML != '') {
        console.log(suscripcionFlash);
        console.log("suscripcionFlashDesaparece");
        suscripcionFlash.delay(5000).hide(300);
      }
  }
    
  //USAR ONKEYUP
  function activarBotonComentar(textArea) {
    var value = textArea.value;
    var botonComentar = $('#botonComentar');
    console.log(value);
    console.log(botonComentar);

    if (value != '') {
      botonComentar.prop('disabled', false);
    } else {
      botonComentar.prop('disabled', true);
    }
  }

  //mostrar los vídeos de un canal mediante ajax
  function mostrarVideos(url, canalId) {
    var that = $(this);
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    "idCanal": canalId
                },
                async: true,
                success: function (data)
                {
                    console.log(data)
                    var divComunidadOVideos = document.getElementById("comunidadOVideos");
                    divComunidadOVideos.innerHTML = '';
                    divComunidadOVideos.innerHTML = data;

                }
            });
            return false;


  }

  //Borrar un comentario mediante AJAX

  function borrarComentario(url, idComentario, idVideo, idCanal, token) {
    var button = event.target;
    button.setAttribute('disabled', 'disabled');
    //console.log(token);
    var that = $(this);
            $.ajax({
                url: url,
                type: "DELETE",
                dataType: "json",
                data: {
                    "idComentario": idComentario,
                    "idVideo": idVideo,
                    "idCanal": idCanal,
                    "_token": token
                },
                async: true,
                success: function (data)
                {
                    //console.log(data)
                    var divComentarios = document.getElementById("divComentarios");
                    var divComunidadOVideos = document.getElementById("comunidadOVideos");
                    if(divComentarios != null) {
                      divComentarios.innerHTML = '';
                      divComentarios.innerHTML = data;
                    }else if (divComunidadOVideos != null) {
                      divComunidadOVideos.innerHTML = '';
                      divComunidadOVideos.innerHTML = data;
                    }

                    borrarMensajeFlash("comentarioFlash");
                }
            });
            return false;
  }



  //Crear un comentario mediante ajax
  function escribirOMostrarComentario(url, videoComentado = null, canalComentado = null, crearOMostrar, nivel){
    var button = event.target;
    button.setAttribute('disabled', 'disabled');
    //console.log("EESTOY EN ESCRIBIR O MOSTRAR COMENTARIO");
    //console.log(url +","+ videoComentado+","+ canalComentado+","+ crearOMostrar +","+nivel);
    var that = $(this);
    var contenido = $("#comentario").prop('value');
    console.log(contenido);

    $.ajax({
        //url:"{{(path('comentario_new'))}}",
        url: url,
        type: "POST",
        dataType: "json",
        data: {
          "videoComentado": videoComentado,
          "canalComentado": canalComentado,
          "contenido": contenido,
          "crearOMostrar": crearOMostrar,
          "nivel": nivel
        },
        async: true,
        success: function (data)
        {
            //console.log(data);
            if(crearOMostrar == 'crear') {
                  var divComentarios = document.getElementById("divComentarios");
                  var divComunidadOVideos = document.getElementById("comunidadOVideos");
                  if(divComentarios != null) {
                    divComentarios.innerHTML = '';
                    divComentarios.innerHTML = data;
                  }else if (divComunidadOVideos != null) {
                    divComunidadOVideos.innerHTML = '';
                    divComunidadOVideos.innerHTML = data;
                  }

                  borrarMensajeFlash("comentarioFlash");
                  

                
                console.log(soloComentarios);
            } else {
              console.log("ELSE");
              console.log(data);
              var divComunidadOVideos = document.getElementById("comunidadOVideos");
              divComunidadOVideos.innerHTML = '';
              divComunidadOVideos.innerHTML = data;
              //divComunidadOVideos.appendChild(data);
              
            }
            
            

        }
    });
    return false;

  }


function cambiarTabActiva(tab, url, canalId, nivel) {
  console.log(tab+","+url+","+canalId + ","+nivel);
  var tabVideos = document.getElementById("tabVideos");
  var tabComunidad = document.getElementById("tabComunidad");

  if(tab == tabVideos) {
    console.log("TABVIDEOS");
    if(tab.classList.contains("active")) {

    }else {
      tab.classList.add("active");
      tabComunidad.classList.remove("active");
      console.log(tab);
      console.log(tabComunidad);
      mostrarVideos(url, canalId);

      
    }
  } 
  if(tab == tabComunidad) {
    console.log("TABCOMUNIDAD");
    //console.log(tab.href);
    if(tab.classList.contains("active")) {

    }else {
      tab.classList.add("active");
      tabVideos.classList.remove("active");
      console.log(tab);
      console.log(tabVideos);
      escribirOMostrarComentario(url, null, canalId, 'mostrar', nivel);
    }
  }
}

function showFormData(formData) {
  var formDataArray = [];
  for(var value of formData.values()) {
    
    if(value instanceof Object && value != '_token') {
      formDataArray.push(value['name']); 
    } else {
      formDataArray.push(value); 
    }
  }
    
 return formDataArray;
}

function arraysMatch(arr1, arr2) {

	// Check if the arrays are the same length
	if (arr1.length !== arr2.length) return false;

	// Check if all items exist and are in the same order
	for (var i = 0; i < arr1.length; i++) {
		if (arr1[i] !== arr2[i]) return false;
	}

	// Otherwise, return true
	return true;

};

  //impedir que se manden los datos de edición de un formulario si no se han modificado
  function permitirEditarForm(idDiv) {
    console.log(idDiv);
    
    var form = $('#'+idDiv);
    console.log("Entro en permitir editar form");
    console.log(form);
    if(form.length) {
      //var submit = document.getElementById("submitEditarCanal");
      var submit = form.find(':submit');
      console.log(submit);
      submit.prop('disabled','disabled');
      var origForm = new FormData(form[0]);
      var origFormArray = showFormData(origForm);

      var formSelector = document.getElementById(idDiv);
      var inputFilesOfForm = formSelector.querySelectorAll("input[type=file]");

      var esInputFile = false;

      $('#'+ idDiv+' :input').on('change input', function(event) {

        for(var i = 0; i< inputFilesOfForm.length && !esInputFile; i++) {
          if(event.target == inputFilesOfForm[i]) {
            esInputFile = true;
          }
        }
        //console.log(event.target);
        var fileName = event.target.value;
        //console.log(fileName);
        var fileNameOnly = fileName.replace("C:\\fakepath\\", "");
        //console.log(fileNameOnly);
        var inputFileId = event.target.id;
        //console.log(event.target.value);
        
        //console.log(form.find(':input[type=file]'));

        if(esInputFile == true) {
          var label = $("label[for='" +inputFileId+ "']");
          //label.siblings(".custom-file").html(event.target.value);
          label.last().html(fileNameOnly);
          esInputFile = false;
        }
        
        
        console.log("Input on change");
          var newForm = new FormData(form[0]);
          var newFormArray = showFormData(newForm);
          if(arraysMatch(origFormArray, newFormArray) == false) {
            submit.removeAttr('disabled');
          } else {
            submit.prop('disabled','disabled');
          }
      });
    }
  }

  //Crea un array en el que la clave es el nombre del campo y el valor el error
  //asociado a ese campo
  function mergeArrayErrores(errores){
    var arrayErroresValue = Object.values(errores);
    var arrayErroresKeys = Object.keys(errores);
    var arrayErroresCombinado = [];
    
    for (let index = 0; index < arrayErroresKeys.length; index++) {
      var element = arrayErroresKeys[index];
      //console.log(element);
      var arrayError = [
        element, arrayErroresValue[index]
      ];
      arrayErroresCombinado.push(arrayError);
    }

    //Llamar a función que coge el div cuyo id le paso, y es igual al nombre del campo del formulario,
    // y si ese campo tiene un error se lo muestro

    for (let index = 0; index < arrayErroresCombinado.length; index++) {
      rellenarErrorByField(arrayErroresCombinado[index]);
      
    }
  }

  //Muestra los errores existentes a nivel de campo
  function rellenarErrorByField(valor) {
    //valor = array con 'nombreCanal' => 'Error:Ya existe'
    //valor[0] = 'nombreCanal' (nombre del campo)
    //valor [1] = 'Error: Ya existe' => error perteneciente al campo
    console.log("RELLENAR VALOR BY FIELD");
    console.log(valor[0]);
    if(valor[1] != '') {
      var divError = document.getElementById(''+valor[0]);
      divError.classList.remove("d-none");
      divError.classList.add("d-block","alert", "alert-danger");
      divError.innerHTML = valor[1];
      console.log(divError);
      setTimeout(function() {
        divError.classList.add("d-none");
        divError.classList.remove("d-block", "alert", "alert-danger");
      },5000);
    }
  }

  function rellenarSpinner() {
    var spinner = document.createElement("div");
    spinner.classList.add("spinner-border", "text-light");
    spinner.setAttribute('id', 'loadingVideo');
    var spanSpinner = document.createElement("span");
    spanSpinner.textContent = "Loading...";
    spanSpinner.classList.add("sr-only");
    spinner.appendChild(spanSpinner);
    var cuerpoSpinner = document.getElementById("cuerpoModalSpinner");
    cuerpoSpinner.appendChild(spinner);
  }
  
  //Función que manda los datos cuando se sube un nuevo vídeo
  $('#formSubirVideo').submit(function(e) {
    console.log("ESTOY EN SUBIR FORM VÍDEO");
      e.preventDefault();
      //permitirEditarForm('formSubirVideo');
      var submit = document.getElementById("submitSubirVideo");
      //submit.setAttribute('disabled','disabled');
  
      var url = e.target.action;
      
      var formData = new FormData($('#formSubirVideo')[0]);
      
      rellenarSpinner();

      $('#modalSubidaVideo').modal('hide');
      
      $('#modalSpinner').modal('show');
      
      $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        async: true,
        success: function (data)
        {
            console.log("SUCCESS");
            console.log(data);
            $('#modalSpinner').modal('hide');
            document.getElementById("cuerpoModalSpinner").innerHTML = '';
            //var dataObject = JSON.parse(data);
            
            if(data.code == 'success') {
              //console.log(dataObject);
              //console.log(data.contenido);
              var divComunidadOVideos = document.getElementById("comunidadOVideos");
              divComunidadOVideos.innerHTML = '';
              divComunidadOVideos.innerHTML = data.contenido;

              
              var divMensajeSubidaVideo = document.getElementById("mensajeSubidaVideo");
              divMensajeSubidaVideo.classList.remove("d-none");
              divMensajeSubidaVideo.classList.add("d-block","alert", "alert-success");
              divMensajeSubidaVideo.innerHTML = data.mensaje;
              
              setTimeout(function() {
                divMensajeSubidaVideo.classList.add("d-none");
                divMensajeSubidaVideo.classList.remove("d-block", "alert", "alert-success");
              },5000);
              $('#formSubirVideo').trigger("reset");
              limpiarForm("formSubirVideo");
              //permitirEditarForm();

              
            } else if(data.code == 'error') {

              $('#modalSubidaVideo').modal('show');

              mergeArrayErrores(data.errores);
              
              permitirEditarForm('formSubirVideo');
            }
        }
    });
    return false;
    });

  //Función que manda los datos del canal cuando se edita

  $('#editFormCanal').submit(function(e) {
  console.log("ESTOY EN EDIT FORM CANAL");
    e.preventDefault();
    rellenarSpinner();
    var formData = new FormData($('#editFormCanal')[0]);
    for (var value of formData.values()) {
      console.log(value); 
    }
    $('#modalSpinner').modal('show');
    $('#modalEditCanal').modal('hide');

    var submit = document.getElementById("submitEditarCanal");
    submit.setAttribute('disabled','disabled');

    var url = e.target.action;
    
    
    console.log(url);
    
    $.ajax({
      url: url,
      type: "POST",
      dataType: "json",
      data: formData,
      processData: false,
      contentType: false,
      async: true,
      success: function (data)
      {
          console.log("SUCCESS");
          $('#modalSpinner').modal('hide');
          document.getElementById("cuerpoModalSpinner").innerHTML = '';
          console.log(data);
          //var dataObject = JSON.parse(data);
          
          //console.log(dataObject.contenido);
          if(data.code == 'success') {

            var divExitoEdicion = document.getElementById("exitoEdicion");
            divExitoEdicion.classList.remove("d-none");
            divExitoEdicion.classList.add("d-block");
            divExitoEdicion.textContent = data.mensaje;
            document.getElementById("nombreCanalH1").innerHTML = data.nombreCanal;

            if(data.rutaImgPerfil != '') {
              console.log(data.rutaImgPerfil);
              document.getElementById("iconoPerfil").src = data.rutaImgPerfil+"?" + new Date().getTime();
              console.log(document.getElementById("iconoPerfil").src);
            }

            $('#editFormCanal').trigger("reset");
            permitirEditarForm('editFormCanal');

            setTimeout(function() {
              divExitoEdicion.classList.add("d-none");
              divExitoEdicion.classList.remove("d-block");
            },5000);
            

            
          } else if(data.code == 'error') {

            $('#modalEditCanal').modal('show');
            mergeArrayErrores(data.errores);
            
            permitirEditarForm('editFormCanal');
          }
      }
  });
  return false;
  });


  function detectarDivVideos(ruta, canalId) {
    console.log(ruta);
    console.log(canalId);
    var divComVideos = $("#comunidadOVideos");
    var divVideoIndex = $("#videosIndex");
    if (divComVideos.length) {
      $(window).scroll(function() {
        console.log("DetectarDivComVideo");
      });
    } else if (divVideoIndex.length) {
      $(window).scroll(function() {
        console.log("DetectarDivVideoIndex");
      });
    }
  }
});

//Borrar el vídeo dado
function borrarVideo(url, idVideo, idCanal, token, mensajeConfirmacion) {
  var borrar = confirm(mensajeConfirmacion);
  if(borrar) {
    $.ajax({
      url: url,
      type: "DELETE",
      dataType: "json",
      data: {
        "idVideo" : idVideo,
        "idCanal": idCanal,
        "_token": token,
      },
      async: true,
      success: function (data)
      {
          console.log("SUCCESS");
          console.log(data);
          //var dataObject = JSON.parse(data);
          
          console.log(data.contenido);
          if(data.code == 'success') {
            var div = document.getElementById("comunidadOVideos");
            if(div == null) {
              var div = document.getElementById("videoIndex");
            }
            div.innerHTML = '';
            div.innerHTML = data.contenido;
  
            var divMensajeExitoBorrado = document.getElementById("mensajeSubidaVideo");
            divMensajeExitoBorrado.classList.remove("d-none");
            divMensajeExitoBorrado.classList.add("d-block","alert", "alert-success");
            divMensajeExitoBorrado.innerHTML = data.mensaje;
                
                setTimeout(function() {
                  divMensajeExitoBorrado.classList.add("d-none");
                  divMensajeExitoBorrado.classList.remove("d-block", "alert", "alert-success");
                },5000);
  
          } else if(data.code == 'error') {
            console.log(error);
            var divMensajeErrorBorrado = document.getElementById("mensajeSubidaVideo");
            divMensajeErrorBorrado.classList.remove("d-none");
            divMensajeErrorBorrado.classList.add("d-block","alert", "alert-danger");
            divMensajeErrorBorrado.innerHTML = data.mensaje;
                
                setTimeout(function() {
                  divMensajeErrorBorrado.classList.add("d-none");
                  divMensajeErrorBorrado.classList.remove("d-block", "alert", "alert-danger");
                },5000);
  
          }
      }
    });
    return false;
  }
  
}

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

