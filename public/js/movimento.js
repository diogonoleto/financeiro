moment.locale('pt-br');
$(document).ready(function() {
  liCalendar(0);
  $(window).resize(function() {
    resizediv();
  });
});
$(function(){
  var $box = $("#form-pesquisa");
  $(window).on("click.Bst", function(e){
    if( $box.has(e.target).length == 0 && !$box.is(e.target) ){
      $(".search-options").addClass("hidden");
    }
  });
});
$(document).on("submit", "#form-pesquisa", function(e) {
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  var url = $(this).attr("action");
  var get = "GET";
  var data = $(this).serializeArray();
  if(!$("#div-crud").hasClass("hidden")){
    $(".tools-user").removeClass("hidden");
    $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
    $("#div-crud").addClass("hidden");
    $(".ativo").removeClass("ativo");
  }
  if($('#movimento-filtro:visible').length > 0 && $('#mf-data:visible').length == 0){
    $("#data_inicio, #data").val("");
  }
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      $("#grid-table-body").html(data);
      if(!$("#movimento-filtro").hasClass('hidden')){
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
      if( $("#data").val() == "fdho"){
        $(".hb-data").addClass("hidden");
      } else {
        $(".hb-data").removeClass("hidden");
      }
      if( $("#regime").val() == "cadastro"){
        $(".hb-datc").removeClass("hidden");
      } else {
        $(".hb-datc").addClass("hidden");
      }
      var tipo = $("#tipo").val();
      $(".tipo").removeClass("active");
      $("#"+tipo).addClass('active');
      if(tipo == 'Receita'){
        $('a[order="nome_fantasia"]').text('RECEBIDO DE');
        if($("#ul-a-tipo input").is(':checked')){
          $("#ul-a-tipo").trigger("click");
        }
        if($("#ul-a-saldo input").is(':checked')){
          $("#ul-a-saldo").trigger("click");
        }
        $("#ul-a-saldo, #ul-a-tipo").parent().addClass("hidden");
      } else if (tipo == 'Despesa'){
        $('a[order="nome_fantasia"]').text('PAGO A');
        if($("#ul-a-tipo input").is(':checked')){
          $("#ul-a-tipo").trigger("click");
        }
        if($("#ul-a-saldo input").is(':checked')){
          $("#ul-a-saldo").trigger("click");
        }
        $("#ul-a-saldo, #ul-a-tipo").parent().addClass("hidden");
      } else {
        $('a[order="nome_fantasia"]').text('RECEBIDO DE / PAGO A');
        if(!$("#ul-a-tipo input").is(':checked')){
          $("#ul-a-tipo").trigger("click");
        }
        if(!$("#ul-a-saldo input").is(':checked')){
          $("#ul-a-saldo").trigger("click");
        }
        $("#ul-a-saldo, #ul-a-tipo").parent().removeClass("hidden");
      }
      $("#ulcol li").each(function(i, item){
        if(!$(this).children('a').children('input').is(':checked')){
          $(".cl-"+$(this).children('a').attr('rel')).addClass("hidden");
        } else {
          $(".cl-"+$(this).children('a').attr('rel')).removeClass("hidden");
        }
      });

      if(agenda){
        $("#btn-agenda-movimento-edit").attr({ "route": agenda.route, "tipo": agenda.tipo });
        $("#btn-agenda-movimento-edit").trigger("click");
        agenda = null;
      }

      resizediv();
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
$(document).on('click', '.mparent', function(e){
  $(".grid-table").toggleClass("hidden");
  $(this).parent().toggleClass("hidden");
  $(".mchild-"+$(this).attr('rel')).toggleClass("hidden");
});
$(document).on('click', '.checkbox-uni', function(e){
  if($(this).children('input').is(':checked')){
    $(this).children('input').prop("checked", false);
    $(this).children('span.mdi').removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
  } else {
    $(this).children('input').prop("checked", true);
    $(this).children('span.mdi').removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
  }
  var qtdli = $(this).closest('ul').children("li").children("a.checkbox-uni").children("i").length;
  var qtdch = 0;
  $(this).closest('ul').children("li").children("a.checkbox-uni").each(function(i, item){
    if($(item).children("span.mdi").hasClass('mdi-checkbox-marked-outline')){
      qtdch++;
    }
  });
  var ifirst = $(this).closest('ul').children("li").first().children("a");
  if( qtdli == qtdch){
    ifirst.children('input').prop("checked", true);
    ifirst.children('span.mdi').removeClass("mdi-checkbox-blank-outline").removeClass("mdi-checkbox-blank").addClass("mdi-checkbox-marked-outline");
  } else if ( qtdch == 0) {
    ifirst.children('input').prop("checked", false);
    ifirst.children('span.mdi').removeClass("mdi-checkbox-marked-outline").removeClass("mdi-checkbox-blank").addClass("mdi-checkbox-blank-outline");
  } else {
    ifirst.children('input').prop("checked", false);
    ifirst.children('span.mdi').removeClass("mdi-checkbox-marked-outline").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-blank");
  }
});
$(document).on('click', '.checkbox-all', function(e){
  var qtdli = $(this).closest('ul').children("li").children("a.checkbox-uni").children("span.mdi").length;
  var qtdch = 0;
  $(this).closest('ul').children("li").children("a.checkbox-uni").each(function(i, item){
    if($(item).children("span.mdi").hasClass('mdi-checkbox-marked-outline')){
      qtdch++;
    }
  });
  if( qtdli == qtdch){
    $(this).closest('ul').children("li").children("a").each(function(i, item){
      $(item).children('input').prop("checked", false);
      $(item).children('span.mdi').removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
    });
  } else {
    $(this).closest('ul').children("li").children("a").each(function(i, item){
      $(item).children('input').prop("checked", true);
      $(item).children('span.mdi').removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
    });
  }
});
$(document).on('click', '#ulcol li a', function(e){
  e.preventDefault();
  if($(this).children("input").is(':checked')){
    $(this).children("input").prop("checked", false);
    $(this).children("i").removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
    $(".cl-"+$(this).attr('rel')).addClass("hidden");
  } else {
    $(this).children("input").prop("checked", true);
    $(this).children("i").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
    $(".cl-"+$(this).attr('rel')).removeClass("hidden");
  }
});
$(document).on('click', "#btn-movimento-filtro", function(e){
  e.preventDefault();
  $('.input-group-btn').removeClass("open");
  $("#btn-movimento-filtro").toggleClass("active");
  $("#movimento-header, #movimento-filtro").toggleClass("hidden");
  if(!$("#movimento-filtro").hasClass("hidden")){
    $("#input-search").val("").focus();
  } else {
    $("#form-pesquisa").submit();
  }
  $('.grid-table').show();
  $("#movimento-filtro").toggleClass("active");



  $(".checkbox-uni input, .checkbox-all input").prop("checked", true);
  $(".checkbox-uni .checkbox, .checkbox-all .checkbox").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");


  $(".fconta.all input").prop("checked", true);
  $(".fconta.all .checkbox").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");


  $('#data_inicio, #data_fim').datetimepicker({
    locale: 'pt-BR',
    format: 'DD/MM/YYYY',
    useCurrent: false,
    widgetPositioning: {
      horizontal: 'right',
      vertical: 'bottom'
    }
  });
  // $("#data_inicio").on("dp.change", function (e) {
  //   $('#data_fim').data("DateTimePicker").minDate(e.date);
  // });
  $("#data_fim").on("dp.change", function (e) {
    $("#form-pesquisa").submit();
  });

  resizediv();
  resizeind();
});
$(document).on('click', '.flancamento', function(e){
  e.preventDefault();
  e.stopPropagation();
  if(!$("#flancamento").hasClass("open")){
    $(".flancamento").removeClass("hidden");
    $("#flancamento").addClass("open");
    $(".search-options").addClass("hidden");
    return false;
  }
  $("#flancamento").removeClass("open");
  $(".flancamento").addClass("hidden").removeClass("active");
  $(this).addClass("active").removeClass("hidden");
  $("#lancamento").val($(this).attr("rel"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '.ftipo', function(e){
  e.preventDefault();
  e.stopPropagation();
  if(!$('#ftipo').hasClass("open")){
    $('.ftipo').removeClass("hidden");
    $('#ftipo').addClass("open");
    $(".search-options").addClass("hidden");
    return false;
  }
  $('#ftipo').removeClass("open");
  $('.ftipo').addClass("hidden").removeClass("active");
  $(this).addClass('active').removeClass("hidden");
  $('#tipo').val($(this).attr("rel"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '.fregime', function(e){
  e.preventDefault();
  e.stopPropagation();
  if(!$('#fregime').hasClass("open")){
    $('.fregime').removeClass("hidden");
    $('#fregime').addClass("open");
    $(".search-options").addClass("hidden");
    return false;
  }
  $('#fregime').removeClass("open");
  $('.fregime').addClass("hidden").removeClass("active");
  $(this).addClass('active').removeClass("hidden");
  $('#regime').val($(this).attr("rel"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '.fpontual', function(e){
  e.preventDefault();
  e.stopPropagation();
  if(!$('#fpontual').hasClass("open")){
    $('.fpontual').removeClass("hidden");
    $('#fpontual').addClass("open");
    $(".search-options").addClass("hidden");
    return false;
  }
  $('#fpontual').removeClass("open");
  $('.fpontual').addClass("hidden").removeClass("active");
  $(this).addClass('active').removeClass("hidden");
  $('#input_pontual').val($(this).attr("rel"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '.fdata', function(e){
  e.preventDefault();
  e.stopPropagation();
  if(!$('#fdata').hasClass("open")){
    $('.fdata').removeClass("hidden");
    $('#fdata').addClass("open");
    $('#pees').addClass("hidden");
    $(".search-options").addClass("hidden");
    return false;
  }
  if($(this).attr("rel") == "pees"){
    $('#pees').removeClass("hidden");
  } else {
    $('#pees').addClass("hidden");
  }
  $('#fdata').removeClass("open");
  $('.fdata').addClass("hidden").removeClass("active");
  $('#data').val($(this).attr("rel"));
  $(this).addClass('active').removeClass("hidden");
  $("#form-pesquisa").submit();
});
$(document).on('click', '.fconta', function(e){
  e.preventDefault();
  e.stopPropagation();
  if(!$('#fconta').parent().hasClass("open")){
    $('.fconta').removeClass("hidden");
    $('#fconta').parent().addClass("open");
    $(".search-options").addClass("hidden");
    return false;
  }

  if($(this).hasClass("all")){
    var qtdli = $(this).closest('ul').children("li").children("a.fconta").children("span.mdi").length;
    var qtdch = 0;
    $(this).closest('ul').children("li").children("a.fconta").each(function(i, item){
      if($(item).children("span.mdi").hasClass('mdi-checkbox-marked-outline')){
        qtdch++;
      }
    });
    if( qtdli == qtdch){
      $(this).closest('ul').children("li").children("a").each(function(i, item){
        $(item).children('input').prop("checked", false);
        $(item).children('span.mdi').removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
      });
    } else {
      $(this).closest('ul').children("li").children("a").each(function(i, item){
        $(item).children('input').prop("checked", true);
        $(item).children('span.mdi').removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
      });
    }
  } else {
    if($(this).children('input').is(':checked')){
      $(this).children('input').prop("checked", false);
      $(this).children('span.mdi').removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
    } else {
      $(this).children('input').prop("checked", true);
      $(this).children('span.mdi').removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
    }
    var qtdli = $(this).closest('ul').children("li").children("a.fconta").children("span.mdi").length;
    var qtdch = 0;
    $(this).closest('ul').children("li").children("a.fconta").each(function(i, item){
      if($(item).children("span.mdi").hasClass('mdi-checkbox-marked-outline')){
        qtdch++;
      }
    });
    var ifirst = $(this).closest('ul').children("li").first().children("a");
    if( qtdli == qtdch){
      ifirst.children('input').prop("checked", true);
      ifirst.children('span.mdi').removeClass("mdi-checkbox-blank-outline").removeClass("mdi-checkbox-blank").addClass("mdi-checkbox-marked-outline");
    } else if ( qtdch == 0) {
      ifirst.children('input').prop("checked", false);
      ifirst.children('span.mdi').removeClass("mdi-checkbox-marked-outline").removeClass("mdi-checkbox-blank").addClass("mdi-checkbox-blank-outline");
    } else {
      ifirst.children('input').prop("checked", false);
      ifirst.children('span.mdi').removeClass("mdi-checkbox-marked-outline").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-blank");
    }
  }

  $("#form-pesquisa").submit();
});
$(document).on('click', '#fconta', function(e){
  e.preventDefault();
  e.stopPropagation();
  if(!$('#fconta').parent().hasClass("open")){
    $('.fconta').removeClass("hidden");
    $('#fconta').parent().addClass("open");
    $(".search-options").addClass("hidden");
    return false;
  }
});
$(document).on('click', '.ffornecedor, .fcliente', function(e){
  e.preventDefault();
  e.stopPropagation();
  $("#input-fornecedor").val($(this).text());
  $('ul.ul-ffornecedor li').hide();
  $("#div-fornecedor, .ul-ffornecedor").removeClass("open");
  $('#ffornecedor').parent().removeClass("open");
  $("#ffornecedor_id").val($(this).attr("rel"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '.fcategoria', function(e){
  e.preventDefault();
  e.stopPropagation();
  $("#input-categoria").val($(this).attr("title"));
  $('ul.ul-fcategoria li').hide();
  $("#div-categoria").toggleClass("open");
  $('#fcategoria').parent().toggleClass("open");

  $("#fcategoria_id").val($(this).attr("rel"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '.fcentrocusto', function(e){
  e.preventDefault();
  e.stopPropagation();
  $("#input-centrocusto").val($(this).attr("title"));
  $('ul.ul-fcentrocusto li').hide();
  $("#div-centrocusto").toggleClass("open");
  $('#fcentrocusto').parent().toggleClass("open");

  $("#fcentrocusto_id").val($(this).attr("rel"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '#input-fornecedor, #input-categoria, #input-centrocusto, #data_inicio, #data_fim, #pesquisa, .ul-conta', function(e){
  e.preventDefault();
  e.stopPropagation();
  $(".search-options").addClass("hidden");
});
$(document).click(function(){
  $('.flancamento').addClass("hidden");
  $('.flancamento.active').removeClass("hidden");

  $('.ftipo').addClass("hidden");
  $('.ftipo.active').removeClass("hidden");

  $('.fregime').addClass("hidden");
  $('.fregime.active').removeClass("hidden");

  $('.fpontual').addClass("hidden");
  $('.fpontual.active').removeClass("hidden");

  $('.fdata').addClass("hidden");
  $('.fdata.active').removeClass("hidden");

  $('#fconta').parent().removeClass("open");
  $('.fconta input').not(":checked").parent().addClass("hidden");
  $('.fconta input:checked').parent().addClass("active");

  if($('ul.ul-ffornecedor li:visible').length > 1){
    $('ul.ul-ffornecedor li:first-child a').trigger("click");
  }

  if($('ul.ul-fcategoria li:visible').length > 1){
    $('ul.ul-fcategoria li:first-child a').trigger("click");
  }

  if($('ul.ul-fcentrocusto li:visible').length > 1){
    $('ul.ul-fcentrocusto li:first-child a').trigger("click");
  }

  $(".open").removeClass("open");
});
$(document).on('click', '#btn-filtro-remove', function(e){
  e.preventDefault();
  e.stopPropagation();
  var div = $(this).parent().parent().parent();
  var id = div.attr("id");
  if(id == "mf-fornecedor"){
    $("li[rel='#mf-cliente']").removeClass("disabled");
  }
  div.addClass("hidden");
  div.children("div:nth-child(2)").children("input").val("");
  $("li[rel='#"+id+"']").removeClass("disabled");
  if($('.mf:visible').length == 0){
    $("#data").val("fdem");
    $("#btn-movimento-filtro").trigger('click');
  }

  $("#form-pesquisa").submit();
});
$(document).on('click', '#form-pesquisa', function(e){
  e.preventDefault();
  $("#input-search").focus();
  $(".open").removeClass("open");
});
$(document).on('click', '.search-item', function(e){
  e.preventDefault();
  e.stopPropagation();
  $(this).addClass("disabled");
  $(".search-options").addClass("hidden");

  var rel = $(this).attr("rel") == "#mf-cliente" ? "#mf-fornecedor" : $(this).attr("rel");
  var div = $(rel).clone();

  $(rel).remove();
  $("#mf-search").before(div);

  $(rel).removeClass("hidden").find("a.active").trigger("click").focus();
  $('#input-search').val("");

  if($(this).attr("rel") == "#mf-cliente"){

    $("li[rel='#mf-fornecedor']").addClass("disabled");
    $("#input-fornecedor").attr("filtro", "cliente").focus();
    $("#labforn").html("Cliente:");
    $("#ffornecedor").scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": "250px", "position": "absolute!important", "margin-top": "-1px", "border-top": 0 })
    $("#div-fornecedor").addClass("open");

  } else if($(this).attr("rel") == "#mf-fornecedor"){

    $("li[rel='#mf-cliente']").addClass("disabled");
    $("#input-fornecedor").attr("filtro", "fornecedor").focus();
    $("#labforn").html("Fornecedores:");

    $("#ffornecedor").scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": "250px", "position": "absolute!important", "margin-top": "-1px", "border-top": 0 })
    $("#div-fornecedor").addClass("open");

  } else if($(this).attr("rel") == "#mf-conta"){
    $('.ul-conta').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": "250px", "position": "absolute!important"});
    $(".search-options").addClass("hidden");
    $("#div-conta").addClass("open");
  } else if($(this).attr("rel") == "#mf-categoria"){
    $('.ul-fcategoria').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": "250px", "position": "absolute!important", "margin-top": "-1px", "border-top": 0 }).addClass("open");
    $(".search-options").addClass("hidden");
    $("#div-categoria").addClass("open");
  } else if($(this).attr("rel") == "#mf-centrocusto"){
    $('.ul-fcentrocusto').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": "250px", "position": "absolute!important", "margin-top": "-1px", "border-top": 0 }).addClass("open");
    $(".search-options").addClass("hidden");
    $("#div-centrocusto").addClass("open");
  } else if($(this).attr("rel") == "#mf-pesquisa"){
    $('#mf-pesquisa').removeClass("hidden");
    $("#pesquisa").select();
  } else if($(this).attr("rel") == "#mf-data"){
    $('#data_inicio, #data_fim').datetimepicker({
      locale: 'pt-BR',
      format: 'DD/MM/YYYY',
      widgetPositioning: {
        horizontal: 'right',
        vertical: 'bottom'
      }
    });
    // $("#data_inicio").on("dp.change", function (e) {
    //   $('#data_fim').data("DateTimePicker").minDate(e.date);
    // });

    $("#data_fim").on("dp.change", function (e) {
      $("#form-pesquisa").submit();
    });
  }
});

$(document).on('focusin', '#input-search', function(e){
  e.preventDefault();
  $(".search-options").removeClass("hidden");
  $('.search-options ul li').show();
  $("#form-pesquisa").addClass("focused");
});
$(document).on('keyup', '#input-search', function(e) {
  var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
  var v = new RegExp($(this).val());
  if(v.length == 0){
    $('.search-options ul li').show();
  } else {
    $('.search-options ul li').hide();
    $('.search-options ul li').filter(function () {
      return v.test($(this).text());
    }).show();

    if($('.search-options ul li:visible').length == 0){
      if($('#mf-pesquisa:visible').length == 0){
        $("#pesquisa").val($(this).val());
      }
      $('.search-options ul li:first').show();
      if(key == 13) {
        $('.search-options ul li:visible').trigger("click");
      }
    }
  }
});
$(document).on('focusout', '#input-search', function(e) {
  e.preventDefault();
  if($('.search-options ul li:visible').length == 1){
    $('.search-options ul li:visible').trigger("click");
  } else if($('.search-options ul li:visible').length == 0){
    $('.search-options ul li:first').show();
  }
});

$(document).on('focusin', '#pesquisa', function(e) {
  $("#form-pesquisa").submit();
});
$(document).on('keyup', '#pesquisa', function(e) {
  e.preventDefault();
  var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
  if(key == 13) {
    $("#form-pesquisa").submit();
  }
});
$(document).on('focusout', '#pesquisa', function(e) {
  e.preventDefault();
  $("#form-pesquisa").submit();
});
$(document).on('focusin', '#input-fornecedor', function(e){
  e.preventDefault();
  e.stopPropagation();
  $(this).select();
  $('ul.ul-ffornecedor li').show();
  if($(this).attr("filtro") == "fornecedor")
    $('ul.ul-ffornecedor .fcliente').parent().hide();
  else
    $('ul.ul-ffornecedor .ffornecedor').parent().hide();
  $(".search-options").addClass("hidden");
  $("#div-fornecedor").addClass("open");
  $(".ul-ffornecedor").addClass("open");
});
$(document).on('keyup', '#input-fornecedor', function() {
  var v = new RegExp($(this).val(), "i");
  if(v.length == 0){
    $('ul.ul-ffornecedor li').show();
  } else {
    $('ul.ul-ffornecedor li').hide();
    $('ul.ul-ffornecedor li').filter(function () {
      return v.test($(this).text());
    }).show();

    if($('ul.ul-ffornecedor li:visible').length == 0){
      $('ul.ul-ffornecedor li:first').show();
    }
  }
});
$(document).on('focusout', '#input-fornecedor', function(e) {
  e.preventDefault();
  if($('ul.ul-ffornecedor li:visible').length == 1){
    $('ul.ul-ffornecedor li:visible a').trigger("click");
  } else if($('ul.ul-ffornecedor li:visible').length == 0){
    $('ul.ul-ffornecedor li').show();
  }
});

$(document).on('focusin', '#input-categoria', function(e){
  e.preventDefault();
  e.stopPropagation();
  $(this).select();
  $('ul.ul-fcategoria li').show();
  $(".search-options").addClass("hidden");
  $("#div-categoria").toggleClass("open");
  $(".ul-fcategoria").toggleClass("open");
});
$(document).on('keyup', '#input-categoria', function() {
  var v = new RegExp($(this).val(), "ig");
  if(v.length == 0){
    $('ul.ul-fcategoria li').show();
  } else {
    $('ul.ul-fcategoria li').hide();
    $('ul.ul-fcategoria li').filter(function () {
      return v.test($(this).text());
    }).show();
    if($('ul.ul-fcategoria li:visible').length == 0){
      $('ul.ul-fcategoria li:first').show();
    }
  }
});
$(document).on('focusout', '#input-categoria', function(e) {
  e.preventDefault();
  if($('ul.ul-fcategoria li:visible').length == 1){
    $('ul.ul-fcategoria li:visible a').trigger("click");
  } else if($('ul.ul-fcategoria li:visible').length == 0){
    $('ul.ul-fcategoria li').show();
  }
});

$(document).on('focusin', '#input-centrocusto', function(e){
  e.preventDefault();
  e.stopPropagation();
  $(this).select();
  $('ul.ul-fcentrocusto li').show();
  $(".search-options").addClass("hidden");
  $("#div-centrocusto").toggleClass("open");
  $(".ul-fcentrocusto").toggleClass("open");
});
$(document).on('keyup', '#input-centrocusto', function() {
  var v = new RegExp($(this).val(), "ig");
  if(v.length == 0){
    $('ul.ul-fcentrocusto li').show();
  } else {
    $('ul.ul-fcentrocusto li').hide();
    $('ul.ul-fcentrocusto li').filter(function () {
      return v.test($(this).text());
    }).show();
    if($('ul.ul-fcentrocusto li:visible').length == 0){
      $('ul.ul-fcentrocusto li:first').show();
    }
  }
});
$(document).on('focusout', '#input-centrocusto', function(e) {
  e.preventDefault();
  if($('ul.ul-fcentrocusto li:visible').length == 1){
    $('ul.ul-fcentrocusto li:visible a').trigger("click");
  } else if($('ul.ul-fcentrocusto li:visible').length == 0){
    $('ul.ul-fcentrocusto li').show();
  }
});

$(document).on('focusin', '#cliente_input', function(e) {
  e.preventDefault();
  $('.ul-cliente li').show();
  $("#cliente_input").select();
});
$(document).on('keyup', '#cliente_input', function() {
  var v = $('#cliente_input').val();
  var rex = new RegExp($(this).val(), 'i');
  $("#cliente_id").val();
  if(v.length == 0){
    $('.ul-cliente li, .ul-cliente li a').show();
  } else {
    $('.ul-cliente li').hide();
    $('.ul-cliente li').filter(function () {
      return rex.test($(this).text());
    }).show();
    if($('.ul-cliente li:visible').length == 0){
      $('#btn-cliente-novo').removeClass("hidden");
    } else {
      $('#btn-cliente-novo').addClass("hidden");
    }
  }
});
$(document).on('focusout', '#cliente_input', function(e) {
  e.preventDefault();
  if($('.ul-cliente li a:visible').length == 1){
    $('.ul-cliente li a:visible').trigger("click");
  } else if($('.ul-cliente li a:visible').length == 0){
    $('.ul-cliente li a').show();
  }
});

$(document).on('focusin', '#forma_pagamento_input', function(e) {
  e.preventDefault();
  $('.ul-forma-pagamento, .ul-forma-pagamento li').show();
  $("#forma_pagamento_input").select();
});
$(document).on('keyup', '#forma_pagamento_input', function() {
  var v = $('#forma_pagamento_input').val();
  var rex = new RegExp($(this).val(), 'i');
  $("#forma_pagamento_id").val();
  if(v.length == 0){
    $('.ul-forma-pagamento li, .ul-forma-pagamento li a').show();
  } else {
    $('.ul-forma-pagamento li').hide();
    $('.ul-forma-pagamento li').filter(function () {
      return rex.test($(this).text());
    }).show();
    $('.ul-forma-pagamento li a').hide();
    $('.ul-forma-pagamento li a').filter(function () {
      return rex.test($(this).text());
    }).show();
  }
});
$(document).on('focusout', '#forma_pagamento_input', function(e) {
  e.preventDefault();
  if($('.ul-forma-pagamento li a:visible').length == 1){
    $('.ul-forma-pagamento li a:visible').trigger("click");
  } else if($('.ul-forma-pagamento li a:visible').length == 0){
    $('.ul-forma-pagamento li a').show();
  }
});

$(document).on('change', '#flag_pontual', function(e){
  if( !$('#flag_pontual').is(':checked') ){
    $(this).val(0);
  } else {
    $(this).val(1);
  }
});

$(document).on('click', '#export', function(e){
  e.preventDefault();
  $(".se-pre-con").fadeIn();

  var url = $(this).attr("data-route");
  var get = "GET";
  var data = $("#form-pesquisa").serializeArray();
  $.ajax({
    url: url,
    type: get,
    data: data,
    cache: false,
    success: function (response, textStatus, request) {
      var a = document.createElement("a");
      a.href = response.file;
      a.download = response.name;
      document.body.appendChild(a);
      a.click();
      a.remove();
      $(".se-pre-con").fadeOut();
    },
    error: function (data) {
      $("#grid-table-body").html("Algo deu errado!!");
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
    }
  });
});
$(document).on('change', '#pago', function(e){
  if( !$('#pago').is(':checked') ){
    $(".pago-div").addClass("hidden");
    $("#valor_recebido, #data_baixa").removeAttr("required");
    $(this).val(0);
  } else {
    $(".pago-div").removeClass("hidden");
    $("#valor_recebido, #data_baixa").prop('required',true);
    $(this).val(1);
    if($("#repetir").val() == 1)
      $(this).closest("form").animate({ scrollTop: $(this).offset().top+510 }, 1000);
    else
      $(this).closest("form").animate({scrollTop: $(this).offset().top+400 }, 1000);
    var v = parseFloat($("#valor").maskMoney('unmasked')[0]);
    var d = parseFloat($("#desconto").maskMoney('unmasked')[0]);
    var j = parseFloat($("#juro").maskMoney('unmasked')[0]);
    var s = (v - d + j).toFixed(2);
    s = s.replace('.',',');
    $('#valor_recebido').val(s);
    $('#data_baixa').focus();
  }
});
$(document).on('change', '#conta_saida_id', function(e){
  var v = $(this).val();
  $("#conta_entrada_id option").removeClass("hidden").each(function() {
    $(this).val() == v ? $(this).addClass("hidden") : null;
  });
});
$(document).on('change', '#conta_entrada_id', function(e){
  var v = $(this).val();
  $("#conta_saida_id option").removeClass("hidden").each(function() {
    $(this).val() == v ? $(this).addClass("hidden") : null;
  });
});
$(document).on("click", "#data_vencimento, #data_baixa, #data_emissao, #data_transferencia", function(e) {
  e.preventDefault();
  $(this).select();
  $("#movimentoForm").animate({ scrollTop: 0 }, 0);
  $("#movimentoForm").animate({ scrollTop: $(this).parent().parent().position().top }, 0);
});
$(document).on('keyup', '#valor, #juro, #desconto', function() {
  var v = parseFloat($("#valor").maskMoney('unmasked')[0]);
  var d = parseFloat($("#desconto").maskMoney('unmasked')[0]);
  var j = parseFloat($("#juro").maskMoney('unmasked')[0]);
  var s = (v - d + j).toFixed(2);
  s = s.replace('.',',');
  $('#valor_recebido').val(s);
  var j = $('#juro').val().replace('R$ ','');
  var d = $('#desconto').val().replace('R$ ','');
  if( j != '0,00' || d != '0,00' ){
    $('#valor_recebido').prop("disabled", true);
  } else {
    $('#valor_recebido').removeAttr("disabled");
  }
});
$(document).on('click', '.mes', function(e){
  var m = parseInt($(this).attr("rel"));
  liCalendar(m);
});
$(document).on('click', '.tipo', function(e){
  $("#tipo").val($(this).attr("id"));
  $("#form-pesquisa").submit();
});
$(document).on('click', '.order-mov', function(e){

  var sort = $(this).attr("sort") == "asc" ? "desc" : "asc";
  $(this).attr("sort", sort);
  $("#sort").val(sort);

  $(".order-mov").removeClass("active");
  $(this).addClass("active");
  var order = $(this).attr("order");
  $("#order").val(order);

  $("#form-pesquisa").submit();

});
// $(document).on('click', '.order-mov2', function(e){
//   e.preventDefault();
//   var c = 0;
//   $("input[name='contas[]']:checked").each(function (i, item){
//     c++;
//   });
//   if(c==0){
//     $(".checkbox-uni input, .checkbox-all input").prop("checked", true);
//     $(".checkbox-uni .checkbox, .checkbox-all .checkbox").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
//   }


//   $(".se-pre-con").fadeIn();

//   var sort = "";
//   var order = $('a.order-mov.active').attr("order");


//   var conta = [];

//   if(!$(this).hasClass('addMov')){
//     $("#movimento-filtro").addClass("movimento-filtro-ativo");
//     $('#movimento-filtro').removeClass("active");
//     $("#input-search").parent().parent().addClass("hidden");
//     $("#input-search").val("");
//   }

//   $("input[name='contas[]']:checked").each(function (i, item){
//     conta[i] = ($(this).val()).toString();
//   });





//   if($(this).hasClass('fdho')){
//     $(".mes-3").parent().removeClass("active");
//     data = "fdh";
//     $(this).addClass("active");
//     $("#filtro-mes, #intervalo").addClass("hidden");
//   }
//   if($(this).hasClass('fdatas')){
//     $(".mes-3").parent().removeClass("active");
//     data = "fds";
//     $(this).addClass("active");
//     $("#filtro-mes, #intervalo").addClass("hidden");
//   }
//   if($(this).hasClass('fdatat')){
//     $(".mes-3").parent().removeClass("active");
//     data = "fdt";
//     $(this).addClass("active");
//     $("#filtro-mes, #intervalo").addClass("hidden");
//   }
//   if($(this).hasClass('fdatai')){
//     $(this).addClass("active");
//     $(".mes-3").parent().removeClass("active");
//     data = "fdp";
//     data_inicio = $("#data_inicio").val();
//     data_fim = $("#data_fim").val();
//     if(!data_inicio || !data_fim){
//       $(".se-pre-con").fadeOut();

//       $("#data_inicio").focus();
//       return false;
//     }
//     $("#filtro-mes, #mf-data, .fdatai").addClass("hidden");
//     $("#intervalo div.col-sm-5").removeClass("col-sm-5").addClass("col-sm-6");
//   }



//   if($(this).hasClass('order-mov')){
//     sort = $(this).attr("sort");
//     sort = sort == "asc" ? "desc" : "asc";
//     $(this).attr("sort", sort);
//     $(".order-mov").removeClass("active");
//     $(this).addClass("active");
//     order = $(this).attr("order");
//     tipo = $("a.tipo.active").attr("id");
//     if(!$('#btn-movimento-filtro').hasClass("active")){
//       $("#movimento-filtro").removeClass("movimento-filtro-ativo");
//     }
//   }

//   if($(this).hasClass('mes') && !$(this).hasClass('addMov')){
//     $(".mes-3").parent().addClass("active");


//     $("#filtro-mes, #intervalo, #mf-data").addClass("hidden");


//     if(!$('.mes-3').attr("filtro")){
//       $("#movimento-filtro").removeClass("movimento-filtro-ativo");
//       $("#btn-movimento-filtro").removeClass("active");

//       $(".lancamento, .ftipo, .regime").removeClass("active").addClass("hidden");
//       $("#lancamento li:first-child a, #ftipo li:first-child a, #regime li:first-child a").addClass("active").removeClass("hidden");

//       $(".checkbox-all").trigger("click");
//       tipo = $("a.tipo.active").attr("id");
//       data = $(this).attr("date");
//       data = "fdem";
//       lancamento = $("a.lancamento.active").attr("rel");
//       conta = [];
//       $("input[name='contas[]']:checked").each(function (i, item){
//         conta[i] = ($(this).val()).toString();
//       });
//     } else {
//       $('.mes').removeAttr("filtro");
//     }
//     var m = parseInt($(this).attr("rel"));
//     liCalendar(m);
//     if(!$("#div-crud").hasClass("hidden")){
//       $(".btn-movimento-create").trigger("click");
//     }
//   }


//   if($(this).hasClass('tipo')){
//     $("#movimento-filtro").removeClass("movimento-filtro-ativo");
//     $("#btn-movimento-filtro").removeClass("active");
//     tipo = $(this).attr("id");
//     $(".mes-3").parent().addClass('active')
//   }
//   if(!$("#div-crud").hasClass("hidden")){
//     $(".tools-user").removeClass("hidden");
//     $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
//     $("#div-crud").addClass("hidden");
//     $(".ativo").removeClass("ativo");
//   }

//   $('.addMov').removeClass('addMov');

//   $.ajax({
//     url: url+"?tipo="+tipo+"&lancamento="+lancamento+"&order="+order+"&sort="+sort+"&conta="+conta+"&data="+data+"&data_inicio="+data_inicio+"&data_fim="+data_fim+"&regime="+regime,
//     type: "GET",
//     success: function(data){
//       $("#title").text("Movimentações - " + tipo);
//       $("#btn-movimento-tools a.tipo").removeClass('active');

//       $("#"+tipo).addClass('active');
//       $("#grid-table-body").html(data);
//       // $("#filtro-info").html($(".fdate").text()).removeClass("hidden");


//       $("#"+tipo).addClass('active');
//       if(data == "fdh"){
//         $(".hb-data").addClass("hidden");
//       } else {
//         $(".hb-data").removeClass("hidden");
//       }
//       if(tipo == 'Receita'){
//         $('a[order="nome_fantasia"]').text('RECEBIDO DE');
//         if($("#ul-a-tipo input").is(':checked')){
//           $("#ul-a-tipo").trigger("click");
//         }
//         if($("#ul-a-saldo input").is(':checked')){
//           $("#ul-a-saldo").trigger("click");
//         }
//         $("#ul-a-saldo, #ul-a-tipo").parent().addClass("hidden");
//       } else if (tipo == 'Despesa'){
//         $('a[order="nome_fantasia"]').text('PAGO A');

//         if($("#ul-a-tipo input").is(':checked')){
//           $("#ul-a-tipo").trigger("click");
//         }
//         if($("#ul-a-saldo input").is(':checked')){
//           $("#ul-a-saldo").trigger("click");
//         }
//         $("#ul-a-saldo, #ul-a-tipo").parent().addClass("hidden");
//       } else {
//         $('a[order="nome_fantasia"]').text('RECEBIDO DE / PAGO A');

//         if(!$("#ul-a-tipo input").is(':checked')){
//           $("#ul-a-tipo").trigger("click");
//         }
//         if(!$("#ul-a-saldo input").is(':checked')){
//           $("#ul-a-saldo").trigger("click");
//         }
//         $("#ul-a-saldo, #ul-a-tipo").parent().removeClass("hidden");
//       }
//       $("#ulcol li").each(function(i, item){
//         if(!$(this).children('a').children('input').is(':checked')){
//           $(".cl-"+$(this).children('a').attr('rel')).addClass("hidden");
//         } else {
//           $(".cl-"+$(this).children('a').attr('rel')).removeClass("hidden");
//         }
//       });
//       resizediv();
//       resizeind();
//       $(".se-pre-con").fadeOut();
//     },
//     error: function(data){
//       $("#grid-table-body").html("Algo deu errado!!");
//       if(data.status==404 || data.status==401) {
//         location.reload();
//       } else if (data.status==403){
//         $("#grid-table-body").html("Você não tem acesso a essa informações!!");
//       }
//       $(".se-pre-con").fadeOut();
//     }
//   });
// });


$(document).on('click', '.btn-drop-movimento-create, .btn-movimento-create, .btn-movimento-edit, .btn-transferencia-create,.btn-conta-edit, #btn-agenda-movimento-edit', function(e){
  e.preventDefault();
  $('#movimento-filtro').removeClass("active").removeClass("movimento-filtro-ativo");

  $("#div-crud").html("");
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  $("#movimento-filtro").addClass("hidden");
  $("#input-search").val("");
  if( $(this).hasClass('ativo')){
    $(".se-pre-con").fadeIn();
    $(".tools-user").removeClass("hidden");
    $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
    $("#div-crud").addClass("hidden");
    $(".ativo").removeClass("ativo");
    $(".se-pre-con").fadeOut();
    resizediv();
    resizeind();
    return false;
  }
  var ati = 0;
  $(".ativo").each(function(i, item){
    ati = 1;
  });
  if(ati == 1){
    $(".ativo").removeClass("ativo");
  } else {
    var tipo = $("#tipo").val();
    if($(this).hasClass('btn-movimento-create')){
      if(tipo == "Extrato"){
        $(".input-group-btn").toggleClass("open");
        resizediv();
        resizeind();
        return false;
      }
    } else {
      $(".input-group-btn").removeClass("open");
    }
    $(".se-pre-con").fadeIn();
    $(".tools-user").toggleClass("hidden");
    $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
    $("#div-crud").toggleClass("hidden");
  }
  if($(this).hasClass('btn-drop-movimento-create')){
    $('#btnmc').addClass('ativo');
  } else {
    $(this).addClass('ativo');
  }
  if($('.btn-conta-edit').hasClass('ativo')){
    $("#form-conta-create").attr("action", $(this).attr("route"));
    $("#form-conta-create").submit();
    return false;
  }
  $("#form-movimento-create").attr("action", $(this).attr("route"));
  $("#form-movimento-create").attr("tipo", $(this).attr("tipo"));
  $("#form-movimento-create").submit();
});
$(document).on("submit", "#movimentoForm", function(e) {
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  var url = $(this).attr("action");
  var get = $(this).attr("method");

  $("#valor_recebido").removeAttr('disabled');

  var data = $(this).serializeArray();
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      if(data.error){
        $.each(data.error , function( key, value ) {
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        if($("#movimentoForm #data_baixa").val()){
          var data1 = (moment($("#movimentoForm #data_baixa").val(), 'DD/MM/YYYY').format("YYYY-MM")).split("-");
        } else {
          var data1 = (moment($("#movimentoForm #data_vencimento").val(), 'DD/MM/YYYY').format("YYYY-MM")).split("-");
        }
        var data2 = (moment().format('YYYY-MM')).split("-");
        var total = (data1[0] - data2[0])*12 + (data1[1] - data2[1]);
        liCalendar(total);
        $('.mes-3').trigger("click");
        $(".ativo").removeClass("ativo");
      }
      $(".se-pre-con").fadeOut();
    },
    error: function(data){
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $.each( data.responseJSON , function( key, value ) {
        $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on("submit", "#form-movimento-create", function(e) {
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  var url = $(this).attr("action");
  var get = $(this).attr("method");
  var tipo = $("#tipo").val();
  if(tipo == "Extrato"){
    tipo = $("#form-movimento-create").attr("tipo");
  }

  $.ajax({
    url: url+"?tipo="+tipo,
    type: get,
    success: function(data){
      $("#div-crud").html(data);

      $('.form-crud, .ul-categoria').scrollbar({ "scrollx": "none", disableBodyScroll: true });

      if(tipo == "Receita"){
        $('.ul-cliente').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "width": "100%"});
      } else if(tipo == "Despesa"){
        $('.ul-fornecedor').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "width": "100%"});
      }

      $("#data_emissao").datetimepicker({
        locale: 'pt-BR',
        format: 'DD/MM/YYYY',
        useCurrent: false,
        widgetPositioning: {
          horizontal: 'right',
          vertical: 'bottom'
        }
      });
      $("#data_vencimento").datetimepicker({
        locale: 'pt-BR',
        format: 'DD/MM/YYYY',
        useCurrent: false,
        widgetPositioning: {
          horizontal: 'right',
          vertical: 'bottom'
        }
      });
      $("#data_baixa").datetimepicker({
        locale: 'pt-BR',
        format: 'DD/MM/YYYY',
        useCurrent: false,
        widgetPositioning: {
          horizontal: 'right',
          vertical: 'bottom'
        }
      });
      $('#data_transferencia').datetimepicker({
        locale: 'pt-BR',
        format: 'DD/MM/YYYY',
        useCurrent: false,
        widgetPositioning: {
          horizontal: 'right',
          vertical: 'bottom'
        }
      });
      // $("#data_emissao").on("dp.change", function (e) {
      //   $("#data_baixa").data("DateTimePicker").minDate(e.date);
      //   $("#data_vencimento").data("DateTimePicker").minDate(e.date);
      // });
      // $("#data_baixa, #data_vencimento").on("dp.change", function (e) {
      //   $('#data_emissao').data("DateTimePicker").maxDate(e.date);
      // });
      $('[data-toggle="popover"]').popover();
      $('#valor, #valor_recebido, #valor_transferencia').maskMoney({ prefix:'R$ ', allowZero: false, defaultZero: false, allowNegative: false, thousands:'.', decimal:',', affixesStay: false });
      $('#desconto, #juro').maskMoney({ prefix:'R$ ', allowZero:true, defaultZero: true, allowNegative: false, thousands:'.', decimal:',', affixesStay: false });
      $('#repeticoes').mask('####0');
      var maskcnpj = '00.000.000/0000-00';
      if( ($("#cnpj").val()).length == 11 ){
        maskcnpj = '000.000.000-00####';
      }
      $('input[name=cnpj]').mask(maskcnpj, {
        onKeyPress: function(cn, e, field, options){
          var masks = ['00.000.000/0000-00', '000.000.000-00####'];
          mask = (cn.length>14) ? masks[0] : masks[1];
          field.mask(mask, options);
        },
        placeholder: "000.000.000-00"
      });
      $(".ativo").each(function(i, item){
        if($(this).hasClass("btn-transferencia-create")){
          $("#movimento-novo").addClass("hidden");
          $("#transferencia-nova").removeClass("hidden");
          $("#conta_saida_id").focus();
        } else {
          $("#movimento-novo").removeClass("hidden");
          $("#transferencia-nova").addClass("hidden");
          $("#descricao").focus();
        }
      });
      resizediv();
      resizeind();
      $("#movimentoForm").animate({ scrollTop: 0 }, 0);
      $(".se-pre-con").fadeOut();
    },
    error: function(data){
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $("#div-list").toggleClass("col-sm-8").toggleClass("col-sm-12");
      $("#div-crud").toggleClass("hidden");
      $(".se-pre-con").fadeOut();
    }
  });
});

$(document).on('click', '.btn-cliente', function(e) {
  e.preventDefault();
  var te = $(this).text();
  var re = $(this).attr('rel');
  $("#cliente_id").val(re);
  $("#cliente_input").val(te);
  $('.ul-cliente li').hide();
});
$(document).on('click', '.btn-cliente-create', function(e) {
  e.preventDefault();
  $("#cliente-nova").removeClass("hidden");
  $("#form-cliente input[name='razao_social']").val($("#cliente_input").val()).select();
  $('.ul-cliente li').hide();
});
$(document).on('click', '.btn-cliente-cancelar', function(e) {
  e.preventDefault();
  $("#cliente-nova, #btn-cliente-novo").addClass("hidden");
  $("#cliente_input").val("").focus();
});
$(document).on("submit", "#form-cliente", function(e) {
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  var url = $(this).attr("action");
  var get = $(this).attr("method");
  var data = $(this).serializeArray();
  ob = {};
  $(data).each(function(i, field){
    ob[field.name] = field.value;
  });
  if(!valida_cpf_cnpj(ob['cnpj'])){
    var error = "CPF ou CNPJ inválido";
    if(ob['cnpj'].length == 14)
      error = "CPF é inválido";
    else if(ob['cnpj'].length > 14)
      error = "CNPJ é inválido";
    $("#form-cliente #cnpj").parent().addClass("has-error").children(".help-block").html('<strong>'+error+'</strong>');
    $(".se-pre-con").fadeOut();
    return false;
  }
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      if(data.error){
        $.each(data.error , function( key, value ) {
          $("#form-cliente #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        $("#cliente_id").val(data.id);
        $(".ul-cliente").append('<li rel="'+data.id+'"><a href="#" class="btn-cliente" rel="'+data.id+'">'+data.nome_fantasia+'</a></li>');
        $(".ul-cliente li:last-child .btn-cliente").trigger("click");
        $("#cliente_input").val(data.nome_fantasia);
        $("#cliente-nova, #btn-cliente-novo").addClass("hidden");
        $("#form-cliente #cnpj").val("");
        $("#centro_custo_input").focus();
      }
      $(".se-pre-con").fadeOut();
    },
    error: function(data){
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $.each( data.responseJSON , function( key, value ) {
        $("#form-cliente #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on("change", "#form-cliente select[name='cliente_id']", function(e) {
  e.preventDefault();
  $( "select option:selected" ).each(function() {
    $("#form-cliente input[name='descricao']").val($(this).attr('desc'));
  });
});
$(document).on("submit", "#form-conta-create", function(e) {
  e.preventDefault();
  var url = $(this).attr("action");
  var get = $(this).attr("method");
  var data = $(this).serializeArray();
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      $("#div-crud").html(data);

      var h =  $("body").innerHeight();
      h -= $(".content-header").outerHeight(true);
      h -= $("#btn-movimento-tools").length ? $("#btn-movimento-tools").outerHeight(true) : 0;
      h -= $("#div-search").length ? $("#div-search").outerHeight(true) : 0;
      h -= $("#movimento-filtro").hasClass('active') || $("#movimento-filtro").hasClass('movimento-filtro-ativo') ? $("#movimento-filtro").outerHeight(true) : 0;
      h -= $("#grid-table-header").length ? $("#grid-table-header").outerHeight(true) : 0;
      h -= $("#movimento-footer").length ? $("#movimento-footer").outerHeight(true) : 0;
      h -= $(".pagination-bottom").length ? $(".pagination-bottom").outerHeight(true) : 0;

      $('.form-crud, .ul-banco').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({ "max-height": h, "width": "100%"});

      $(".tab-pane.fade.in.active .banco_input").focus().select();
      $('#saldo_data_conta_corrente, #saldo_data_poupanca, #saldo_data_aplicacao, #saldo_data_meio, #saldo_data_investimento, #saldo_data_caixinha, #saldo_data_outro').datetimepicker({
        locale: 'pt-BR'
      });
      $('#saldo_conta_corrente, #saldo_poupanca, #saldo_aplicacao, #saldo_meio, #saldo_investimento, #saldo_caixinha, #saldo_outro').maskMoney({ prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false, allowZero: true });
      $('#agencia').mask("#######0000", { reverse: true, placeholder:'N° da Agência: ex. 0000' });
      $('#conta').mask("#######0000-A", { reverse: true, placeholder:'N° da Conta: ex. 000000000000-0' });
      resizediv();
      resizeind();
      $(".se-pre-con").fadeOut();
      $(".form-crud").animate({scrollTop: 0 }, 1000);
    },
    error: function(data, ajaxOptions, thrownError){
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $(".btn-conta-create").trigger("click");
    }
  });
});
$(document).on("submit", ".ContaForm", function(e) {
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  var url = $(this).attr("action");
  var get = $(this).attr("method");
  var data = $(this).serializeArray();
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      if(data.error){
        $.each(data.error , function( key, value ) {
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        $("a.tipo.active").trigger("click");
      }
      $(".se-pre-con").fadeOut();
    },
    error: function(data){
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $.each( data.responseJSON , function( key, value ) {
        $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on('click', '.checkbox-padrao', function(e){
  e.preventDefault();
  if($(this).children("#padrao").is(':checked')){
    $(this).children("#padrao").prop("checked", false);
    $(this).children("i").removeClass("mdi-checkbox-marked-outline").addClass("mdi-checkbox-blank-outline");
  } else {
    $(this).children("#padrao").prop("checked", true);
    $(this).children("i").removeClass("mdi-checkbox-blank-outline").addClass("mdi-checkbox-marked-outline");
  }
});
$(document).on('click', '.fisica-juridica', function(e){
  e.preventDefault();
  $("input[name='tipo_pessoa']").prop("checked", false);
  $(".radio").removeClass("mdi-checkbox-marked-circle-outline").addClass("mdi-checkbox-blank-circle-outline");
  $(this).children('input').prop("checked", true);
  $(this).children('.radio').removeClass("mdi-checkbox-blank-circle-outline").addClass("mdi-checkbox-marked-circle-outline");
});

$(document).on('click', '.btn-forma-pagamento', function(e) {
  e.preventDefault();
  var te = $(this).text();
  var re = $(this).attr('rel');
  $("#forma_pagamento_id").val(re);
  $("#forma_pagamento_input").val(te);
  $('.ul-forma-pagamento').hide();
});
