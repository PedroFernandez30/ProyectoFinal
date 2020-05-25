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

  permitirEditarCanal();

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
});

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

//impedir que se manden los datos de edición de un canal si no se han modificado
function permitirEditarCanal() {
  var form = $('#editFormCanal');
  var form2 = document.getElementById("editFormCanal");
  
  console.log(form);
  console.log(form2);
  console.log("Entro en permitir editar canal");
  if(form.length) {
    var submit = document.getElementById("submitEditarCanal");
    submit.setAttribute('disabled','disabled');
    var origForm = form.serialize();

    $('#editFormCanal :input').on('change input', function() {
        if(form.serialize() !== origForm) {
          //var submit = form.querySelector('button[type="submit"]');
          console.log(submit);
          submit.removeAttribute('disabled');
        } else {
          submit.setAttribute('disabled','disabled');
        }
    });
  }
  
}

$('#editFormCanal').submit(function(e) {
console.log("ESTOY EN EDIT FORM CANAL");
  e.preventDefault();

  var submit = document.getElementById("submitEditarCanal");
  submit.setAttribute('disabled','disabled');

  var url = e.target.action;
  
  var formSerialize = $(this).serialize();

  console.log($(this));
  console.log($(this).data());
  
  console.log(JSON.stringify(formSerialize));
 
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
          console.log("borro el flash");
         
          
          var divExitoEdicion = document.getElementById("exitoEdicion");
          divExitoEdicion.classList.remove("d-none");
          divExitoEdicion.classList.add("d-block");
          divExitoEdicion.textContent = dataObject.mensaje;

          permitirEditarCanal();

          setTimeout(function() {
            divExitoEdicion.classList.add("d-none");
            divExitoEdicion.classList.remove("d-block");
          },5000);
          

          
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



console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

