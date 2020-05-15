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
  window.irAConfigPorPost = irAConfigPorPost;
  window.escribirOMostrarComentario = escribirOMostrarComentario;
  window.activarBotonComentar = activarBotonComentar;
  window.cambiarTabActiva = cambiarTabActiva;
  window.mostrarVideos = mostrarVideos;


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


  //Crear un comentario mediante ajax
  function escribirOMostrarComentario(url, videoComentado = null, canalComentado = null, crearOMostrar, nivel){
    console.log("EESTOY EN ESCRIBIR O MOSTRAR COMENTARIO");
    console.log(url +","+ videoComentado+","+ canalComentado+","+ crearOMostrar +","+nivel);
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
            console.log(data);
            if(crearOMostrar == 'crear') {
              var numComentarios = document.getElementById("numComentarios");
              var comentarios = document.getElementById("comentarios");
              var soloComentarios = document.getElementById("soloComentarios");
              var textAreaComentarios = document.getElementById("comentario");
              var buttonComentar = document.getElementById("botonComentar");
              textAreaComentarios.value = '';
              soloComentarios.innerHTML = '';
              buttonComentar.disabled = true;
              
              /*<div  class="col-8" id='{{ comentario.id }}'>
                      <span class="text-white"> {{ comentario.canalQueComenta.nombreCanal }}</span>
                      <span> {{ comentario. fechaComentario | date('d/m/y') }} </span>
                      <p>{{ comentario.contenido }}</p>
                  </div>*/

                  if(data.length > 0) {
                    numComentarios.textContent = data.length + " comentario(s)";
                  } else {
                    numComentarios.textContent = "Sé el primero en comentar";
                  }

                data.reverse().forEach(comentario => {
                  console.log(comentario);
                  console.log(comentario.canalQueComenta.nombre);
                  console.log(comentario.fechaPublicacion);
                  console.log(comentario.contenido);
                  var divComentario = document.createElement("div");
                  divComentario.classList.add('col-8');
                  divComentario.id = comentario.id;
                  

                  var spanNombreCanal = document.createElement("span");
                  spanNombreCanal.classList.add('text-white');
                  spanNombreCanal.textContent = comentario.canalQueComenta.nombre;
                  divComentario.appendChild(spanNombreCanal);
                  console.log(spanNombreCanal);

                  var spanFechaComentario = document.createElement("span");
                  spanFechaComentario.textContent =  " "+comentario.fechaPublicacion;
                  divComentario.appendChild(spanFechaComentario);
                  console.log(spanFechaComentario);

                  var p = document.createElement("p");
                  p.textContent = comentario.contenido;
                  divComentario.appendChild(p);
                  console.log(divComentario)
                  soloComentarios.appendChild(divComentario);
                });
                console.log(soloComentarios);
                comentarios.appendChild(soloComentarios);
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



console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

