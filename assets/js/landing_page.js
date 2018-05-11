var $ = require('jquery');
import 'bootstrap';
require("../css/landing_page.scss");

$(document).ready(function(){

    var language = document.documentElement.lang;

    require('./utils.js');

    // <i class="fas fa-times"></i>

  // $("input[type='checkbox'], select").on("change", function(){
  //     $('input[name="current_page"]').val(1); //reset page number
  //     var form = $(this).parents('form');
  //     $.ajax({
  //         url: form.attr("action"),
  //         data: form.serialize(),
  //         method: 'GET',
  //         success: function(data){
  //             var article_list = $("#articles ul");
  //             article_list.empty();
  //             if(data != 'EOF'){
  //                 article_list.append( data );
  //                 setTimeout(function(){
  //                   $(window).on('scroll', loadNextArticles);
  //                 }, 2000);
  //             } else {
  //               article_list.append( "<p class='text-center'>No article found</p>" );
  //             }
  //         },
  //         error: function(){
  //             alert('error error');
  //         }
  //     });
  // });

  // $(window).on('scroll', loadNextArticles);

  // $(window).on('beforeunload', function(){
  //     location.reload(true);
  //     window.history.forward(1);
  // });
  //
  // $(window).on('load', function(){
  //     location.reload(true);
  //     window.history.forward(1);
  // });

  // function loadNextArticles(event){
  //     if($(window).scrollTop() + $(window).height() >  $(document).height() - 200) {
  //          $( this ).off( event );
  //
  //          var current_page = $('input[name="current_page"]');
  //          var next_page = parseInt(current_page.val()) + 1;
  //          current_page.val(next_page);
  //
  //          var form = $('#datagrid');
  //          $.ajax({
  //              url: form.attr("action"),
  //              data: form.serialize(),
  //              method: 'GET',
  //              success: function(data){
  //                  if(data != 'EOF'){
  //                      var article_list = $("#articles");
  //                      article_list.append( data );
  //                      setTimeout(function(){
  //                        $(window).on('scroll', loadNextArticles);
  //                      }, 2000);
  //                  } else {
  //                    article_list.append( "<p class='text-center'>No article found</p>" );
  //                  }
  //              },
  //              error: function(){
  //                  alert('error error');
  //              }
  //          });
  //
  //      }
  // }

});
