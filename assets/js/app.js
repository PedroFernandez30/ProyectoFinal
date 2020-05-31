/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
require('../css/app.scss');
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

  permitirEditarForm();
  permitirUsoBuscador();

  window.filtrarPorTipo = filtrarPorTipo;
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
              console.log("SUCCESS");
              console.log(data);
              var divContenidoBuscador = document.getElementById("contenidoBuscador");
              var dataObject = JSON.parse(data);
              divContenidoBuscador.innerHTML = '';
              divContenidoBuscador.innerHTML = dataObject.contenido
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

      /*<input type="hidden" name="_csrf_token"
           value="{{ csrf_token('authenticate') }}"
    ></input>*/
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

  //impedir que se manden los datos de edición de un formulario si no se han modificado
  function permitirEditarForm() {
    var idDiv = 'editFormCanal';
    var form = $('#editFormCanal');
    if(!form.length) {
      var form = $('formSubirVideo');
      var idDiv = 'formSubirVideo';
    }
    console.log("Entro en permitir editar canal");
    if(form.length) {
      //var submit = document.getElementById("submitEditarCanal");
      var submit = form.find(':submit');
      submit.prop('disabled','disabled');
      var origForm = form.serialize();

      $('#'+ idDiv+' :input').on('change input', function() {
        console.log("Input on change");
          if(form.serialize() !== origForm) {
            //var submit = form.querySelector('button[type="submit"]');
            console.log(submit);
            submit.removeAttr('disabled');
          } else {
            submit.prop('disabled','disabled');
          }
      });
    }
  }

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
      console.log(divError)
      setTimeout(function() {
        divError.classList.add("d-none");
        divError.classList.remove("d-block", "alert", "alert-danger");
      },5000);
    }
  }

  
  //Función que manda los datos cuando se sube un nuevo vídeo
  $('#formSubirVideo').submit(function(e) {
    console.log("ESTOY EN SUBIR FORM VÍDEO");
      e.preventDefault();
  
      var submit = document.getElementById("submitSubirVideo");
      //submit.setAttribute('disabled','disabled');
  
      var url = e.target.action;
      
      var formSerialize = $(this).serialize();
      var formData = new FormData($('#formSubirVideo')[0]);
      
      console.log(submit);
      console.log(url);
      console.log(formSerialize);
    
      $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        //dataType: "text",
        data: formData,
        processData: false,
        contentType: false,
        async: true,
        success: function (data)
        {
            console.log("SUCCESS");
            console.log(data);
            //var dataObject = JSON.parse(data);
            
            if(data.code == 'success') {
              //console.log(dataObject);
              //console.log(data.contenido);
              $('#modalSubidaVideo').modal('hide');

              var divComunidadOVideos = document.getElementById("comunidadOVideos");
              divComunidadOVideos.innerHTML = '';
              divComunidadOVideos.innerHTML = data.contenido

              var divMensajeSubidaVideo = document.getElementById("mensajeSubidaVideo");
              divMensajeSubidaVideo.classList.remove("d-none");
              divMensajeSubidaVideo.classList.add("d-block","alert", "alert-success");
              divMensajeSubidaVideo.innerHTML = data.mensaje;
              
              setTimeout(function() {
                divMensajeSubidaVideo.classList.add("d-none");
                divMensajeSubidaVideo.classList.remove("d-block", "alert", "alert-success");
              },5000);
              //permitirEditarForm();

              
            } else if(dataObject.code == 'error') {
              var arrayErroresValue = Object.values(dataObject.errores);
              var arrayErroresKeys = Object.keys(dataObject.errores);
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
              permitirEditarForm();
            }
        }
    });
    return false;
    });

  //Función que manda los datos del canal cuando se edita

  $('#editFormCanal').submit(function(e) {
  console.log("ESTOY EN EDIT FORM CANAL");
    e.preventDefault();

    var submit = document.getElementById("submitEditarCanal");
    submit.setAttribute('disabled','disabled');

    var url = e.target.action;
    
    var formSerialize = $(this).serialize();
  
    $.ajax({
      url: url,
      type: "POST",
      dataType: "text",
      data: formSerialize,
      async: true,
      success: function (data)
      {
          console.log("SUCCESS");
          //console.log(data);
          var dataObject = JSON.parse(data);
          
          console.log(dataObject.contenido);
          if(dataObject.code == 'success') {

            var divExitoEdicion = document.getElementById("exitoEdicion");
            divExitoEdicion.classList.remove("d-none");
            divExitoEdicion.classList.add("d-block");
            divExitoEdicion.textContent = dataObject.mensaje;

            permitirEditarForm();

            setTimeout(function() {
              divExitoEdicion.classList.add("d-none");
              divExitoEdicion.classList.remove("d-block");
            },5000);
            

            
          } else if(dataObject.code == 'error') {
            var arrayErroresValue = Object.values(dataObject.errores);
            var arrayErroresKeys = Object.keys(dataObject.errores);
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
            permitirEditarForm();
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


console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

