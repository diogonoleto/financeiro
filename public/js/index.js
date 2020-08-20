$(document).ready(function() {
  $(window).resize(function() {
    resizeind();
  });
  $(".se-pre-con").fadeIn();

  if($("#form-search").length > 0){ 
    $("#form-search").submit();
  }
  $('[data-toggle="popover"]').popover();
  $('[data-toggle="tooltip"]').tooltip();
});
$(document).on('click', '#btn-apresentacao-next, #btn-apresentacao-prev, #btn-apresentacao-fina', function(e) {
  e.preventDefault();
  var indice;
  var ultima = vdata.length;
  if(e.target.id == "btn-apresentacao-next"){
    indice = parseInt($(".spotlight-lens").attr("data-next"));
  } else if(e.target.id == "btn-apresentacao-prev"){
    indice = parseInt($(".spotlight-lens").attr("data-prev"));
  } else {
    $(".spotlight-lens").addClass("hidden");
    if( $("input#apresentacao").is(':checked') ){
      $.ajax({
        url: "{{ route('usuario.apresentacao') }}",
        type: "GET",
        success: function(data){
          console.log(data);
        }, 
        error: function(data){
          console.log(data);
        }
      });
    }
    return false;
  }
  if (indice > 0 ){
    $('.spotlight-lens').attr("data-prev",indice-1);
    $('#btn-apresentacao-prev').removeAttr("disabled");
  } else {
    $('.spotlight-lens').attr("data-prev", indice);
    $('#btn-apresentacao-prev').attr("disabled","disabled");
  }
  if (indice < ultima-1 ){
    $('.spotlight-lens').attr("data-next", indice+1);
    $('#btn-apresentacao-next').removeAttr("disabled");
  } else {
    $('.spotlight-lens').attr("data-next", indice);
    $('#btn-apresentacao-next').attr("disabled","disabled");
  }
  $('#apresentacao').html(vdata[indice].style);
  $('.spotlight-teaser').fadeOut(100).addClass('spotlight-teaser-mov').delay(1000).fadeIn();
  $('.spotlight-header').html(vdata[indice].titulo);
  $('.spotlight-text').html(vdata[indice].descricao);
  $('.spotlight-footer').removeClass('hidden');
});
$(document).on('click', '#btn-next, #btn-prev, #btn-fina', function(e) {
  e.preventDefault();
  console.log();
  var indice;
  var ultima = vdata.length;
  if(e.target.id == "btn-next"){
    indice = parseInt($(".spotlight-lens").attr("data-next"));
  } else if(e.target.id == "btn-prev"){
    indice = parseInt($(".spotlight-lens").attr("data-prev"));
  } else {
    $(".spotlight-lens").addClass("hidden");
    return false;
  }
  if (indice > 0 ){
    $('.spotlight-lens').attr("data-prev",indice-1);
    $('#btn-prev').removeAttr("disabled");
  } else {
    $('.spotlight-lens').attr("data-prev", indice);
    $('#btn-prev').attr("disabled","disabled");
  }
  if (indice < ultima-1 ){
    $('.spotlight-lens').attr("data-next", indice+1);
    $('#btn-next').removeAttr("disabled");
  } else {
    $('.spotlight-lens').attr("data-next", indice);
    $('#btn-next').attr("disabled","disabled");
  }
  $('#apresentacao').html(vdata[indice].style);
  $('.spotlight-teaser').fadeOut(100).addClass('spotlight-teaser-mov').delay(1000).fadeIn();
  $('.spotlight-header').html(vdata[indice].titulo);
  $('.spotlight-text').html(vdata[indice].descricao);
});
$(document).on('click', '#btn-grid-table', function(e){
  e.preventDefault();
  $("#grid-table-body").toggleClass("gtbl");
  $(".grid-table").toggleClass("grid list");
  $("#grid-table-header").toggleClass("hidden");
  $("#btn-grid-table i").toggleClass("mdi-view-list").toggleClass("mdi-view-module");
});
$(document).on('click', '.btn-tools-delete, .btn-categoria-delete, .btn-tipo-delete, .btn-orgao-delete, .btn-subcategoria-delete', function(e){
  e.preventDefault();
  var a = "div-del-cat";
  if($(this).hasClass("btn-subcategoria-delete")){
    a = "div-del-sub";
  }
  $(this).parent().parent().append('<div class="div-del '+a+'" style="display:none;"><span class="del-obs">Deseja realmente excluir?</span><a href="#" class="btn-delete">SIM</a><a href="#" class="btn-delete-nao">NÃO</a></div').addClass("delete-border");
  $(".tools-user, .tools-subcategoria, .tools-categoria").addClass("hidden");
  $(".btn-delete").parent().fadeIn();
  $('#delete-form').attr("action", $(this).attr("route"));
});
$(document).on('mouseleave', '.div-del', function(e){
  e.preventDefault();
  $(".div-del").fadeOut().remove();
  $(".delete-border").removeClass("delete-border");
  $(".delete-border-top").removeClass("delete-border-top");
  $(".delete-border-bottom").removeClass("delete-border-bottom");
  $("#delete_confirmar").val(0);
  $(".grid-table, .categorias, .tools-user, .tools-subcategoria, .tools-categoria").removeClass("hidden");
});
$(document).on('click', '.btn-delete', function(e){
  e.preventDefault();
  $("#delete-form").submit();
  $(".se-pre-con").fadeIn();
});
$(document).on('click', '.btn-delete-nao', function(e){
  e.preventDefault();
  $(".div-del").fadeOut().remove();
  $(".delete-border").removeClass("delete-border");
  $(".delete-border-top").removeClass("delete-border-top");
  $(".delete-border-bottom").removeClass("delete-border-bottom");
  $("#delete_confirmar").val(0);
  $(".grid-table, .categorias, .tools-user, .tools-subcategoria, .tools-categoria").removeClass("hidden");
});
$(document).on('click', '#btn-search', function(e){
  e.preventDefault();
  $('#movimento-filtro').removeClass("active");
  $("#div-search").toggleClass("hidden");
  $("#input-search").val("").focus();
  $('.grid-table').show();
  if($("#div-search").hasClass('hidden')){
    $("#form-search").submit();
  }
  resizeind();
});
$(document).on("submit", "#form-search", function(e) {
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  var url = $(this).attr("action");
  var get = "GET";
  var data = $(this).serializeArray();
  // alert(JSON.stringify(data));
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      $("#grid-table-body").html(data);
      if(!$("#div-search").hasClass('hidden')){
        $("#ul-a-saldo").parent().addClass('hidden');
        $("#saldo > div, #saldoAnt > div").addClass("hidden");
      } else {
        $("#saldo > div, #saldoAnt > div").removeClass("hidden");
      }
      $("#ulcol li").each(function(i, item){
        if(!$(this).children('a').children('input').is(':checked')){
          $(".cl-"+$(this).children('a').attr('rel')).addClass("hidden");
        } else {
          $(".cl-"+$(this).children('a').attr('rel')).removeClass("hidden");
        }
      });
      $('#grid-table-body').scrollbar({ "scrollx": "none", disableBodyScroll: true });

      if($(".search-options").length > 0){ 
        $(".search-options").addClass("hidden");
      }
      resizeind();
    },
    error:function (data){
      $("#grid-table-body").html("Algo deu errado!!");
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on('click', '.pagination a', function(e){
  e.preventDefault();
  var url = $("#form-search").attr("action");
  var get = "GET";
  var page = $(this).attr("href").split('page=')[1];
  $.ajax({
    url: url+"?page="+page,
    type: get,
    success: function(data){
      $("#grid-table-body").html(data);
    },
  });
});
$(document).on('click', '.order', function(e){
  e.preventDefault();
  var url = $("#form-search").attr("action");
  var get = "GET";
  var sort = $(this).attr("sort");
  sort = sort == "ASC" ? "DESC" : "ASC";
  $(this).attr("sort", sort);
  var order = $(this).attr("order");
  $.ajax({
    url: url+"?order="+order+"&sort="+sort,
    type: get,
    success: function(data){
      $("#grid-table-body").html(data);
    },
  });
});
var resizeind = function (){
  var h =  $("body").outerHeight();
  h -= $(".content-header").length ? $(".content-header").outerHeight(true) : 0;
  h -= $("#btn-movimento-tools").length ? $("#btn-movimento-tools").outerHeight(true) : 0;
  h -= $("#div-search").length ? $("#div-search").outerHeight(true) : 0;
  h -= $("#movimento-filtro").hasClass('active') || $("#movimento-filtro").hasClass('movimento-filtro-ativo') ? $("#movimento-filtro").outerHeight(true) : 0;
  h -= $("#grid-table-header").length ? $("#grid-table-header").outerHeight(true) : 0;
  h -= $("#movimento-footer").length ? $("#movimento-footer").outerHeight(true) : 0;
  h -= $(".pagination-bottom").length ? $(".pagination-bottom").outerHeight(true) : 0;
  $('#grid-table-body').css("max-height", h).parent().css({ "max-height": h, "width": "100%" });
  $(".se-pre-con").fadeOut();
}
var req;
function buscarNoticias(valor) {
  document.getElementById('grid-table-body').innerHTML = '<div class="col-xs-12 grid-table text-center" style="margin-bottom:5px;"><div class="col-xs-12">Buscando Informações</div></div>';
  if(window.XMLHttpRequest) {
    req = new XMLHttpRequest();
  }
  else if(window.ActiveXObject) {
    req = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var tipo = $("a.tipo.active").attr("id");

  if(!tipo)
    tipo=null;
  var url = $("#form-search").attr("action");
  url = url+"?input-search="+valor+"&tipo="+tipo;
  req.open("Get", url, true); 
  req.onreadystatechange = function() {
    if(req.readyState == 1) {
      document.getElementById('grid-table-body').innerHTML = '<div class="col-xs-12 grid-table text-center" style="margin-bottom:5px;"><div class="col-xs-12">Buscando Informações</div></div>';
    }
    if(req.readyState == 4 && req.status == 200) {
      var resposta = req.responseText;
      document.getElementById('grid-table-body').innerHTML = resposta;
      if(!$("#div-search").hasClass('hidden')){
        $("#saldo, #saldoAnt, #ftr, #ftp, #fres, #farp, #fvdr").addClass("hidden");
        $(".mes").parent().removeClass("active");
        $("#frs, #fgs, #fvs, #fap, #far, #frv, #fdv").removeClass("hidden").removeClass("col-lg-offset-2").removeClass("col-lg-offset-1");
        $("#ulcol li").each(function(i, item){
          if(!$(this).children('a').children('input').is(':checked')){
            $(".cl-"+$(this).children('a').attr('rel')).addClass("hidden");
          } else {
            $(".cl-"+$(this).children('a').attr('rel')).removeClass("hidden");
          }
        });
        $("#ul-a-saldo").parent().addClass('hidden');
        $(".cl-sald").addClass('hidden');
      }


      // $('.scrollbar-inner').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": h, "width": "100%"});


    }
  }
  req.send(null);
}
