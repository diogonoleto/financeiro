$(document).on('keyup', '#fornecedor_input', function() {
  var v = $('#fornecedor_input').val();
  var rex = new RegExp($(this).val(), 'i');
  $("#fornecedor_id").val();
  if(v.length == 0){
    $('.ul-fornecedor li, .ul-fornecedor li a').show();
  } else {
    $('.ul-fornecedor li').hide();
    $('.ul-fornecedor li').filter(function () {
      return rex.test($(this).text());
    }).show();
    if($('.ul-fornecedor li:visible').length == 0){
      $('#btn-fornecedor-novo').removeClass('hidden');
    } else {
      $('#btn-fornecedor-novo').addClass('hidden');
    }
  }
});
$(document).on('focusout', '#fornecedor_input', function(e) {
  e.preventDefault();
  if($('.ul-fornecedor li a:visible').length == 1){
    $('.ul-fornecedor li a:visible').trigger("click");
  } else if($('.ul-fornecedor li a:visible').length == 0){
    $('.ul-fornecedor li a').show();
  }
});
$(document).on('focusin', '#fornecedor_input', function(e) {
  e.preventDefault();
  $('.ul-fornecedor li').show();
  $("#fornecedor_input").select();
});
$(document).on('click', '.btn-fornecedor', function(e) {
  e.preventDefault();
  var te = $(this).text();
  var re = $(this).attr('rel');
  $("#fornecedor_id").val(re);
  $("#fornecedor_input").val(te);
  $('.ul-fornecedor li').hide();
});
$(document).on('click', '.btn-fornecedor-create', function(e) {
  e.preventDefault();
  $("#fornecedor-nova").removeClass("hidden");
  $("#form-fornecedor input[name='razao_social']").val($("#fornecedor_input").val()).select();
  $('.ul-fornecedor li').hide();
});
$(document).on('click', '.btn-fornecedor-cancelar', function(e) {
  e.preventDefault();
  $("#fornecedor-nova, #btn-fornecedor-novo").addClass("hidden");
  $("#fornecedor_input").val("").focus();
});
$(document).on("change", "#form-fornecedor select[name='fornecedor_id']", function(e) {
  e.preventDefault();
  $( "select option:selected" ).each(function() {
    $("#form-fornecedor input[name='descricao']").val($(this).attr('desc'));
  });
});
$(document).on("submit", "#form-fornecedor", function(e) {
  e.preventDefault();
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  $(".se-pre-con").fadeIn();
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
    $("#form-fornecedor #cnpj").parent().addClass("has-error").children(".help-block").html('<strong>'+error+'</strong>');
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
          $("#form-fornecedor #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        $("#fornecedor_id").val(data.id);
        $(".ul-fornecedor").append('<li rel="'+data.id+'"><a href="#" class="btn-fornecedor" rel="'+data.id+'">'+data.nome_fantasia+'</a></li>')
        $(".ul-fornecedor li:last-child .btn-fornecedor").trigger("click");
        $("#fornecedor_input").val(data.nome_fantasia);
        $("#fornecedor-nova, #btn-fornecedor-novo").addClass("hidden");
        $("#form-fornecedor #cnpj").val("");
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
        $("#form-fornecedor #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      $(".se-pre-con").fadeOut();
    }
  });
});

var active = -1;
$(document).on('keyup', '#categoria_input', function(e) {
  var v = $('#categoria_input').val();
  var rex = new RegExp($(this).val(), 'i');
  $("#categoria_id").val();

  if(v.length == 0){
    $('.ul-categoria li, .ul-categoria li a').show();
  } else {
    $('.ul-categoria li').hide();
    $('.ul-categoria li').filter(function () {
      $(this).children('span').first().children('a').show();
      return rex.test($(this).text());
    }).show();
    $('.ul-categoria li a').hide();
    $('.ul-categoria li a').filter(function () {
      $(this).parent().children('span').first().children('a').show();
      return rex.test($(this).text());
    }).show();
  }
});
$(document).on('focusout', '#categoria_input', function(e) {
  e.preventDefault();
  if($('.ul-categoria li a:visible').length == 1){
    $('.ul-categoria li a:visible').trigger("click");
  } else if($('.ul-categoria li a:visible').length == 0){
    $('#categoria_input').val("");
    $('.ul-categoria li a').show();
  }
});
$(document).on('focusin', '#categoria_input', function(e) {
  e.preventDefault();
  $('.ul-categoria').show();
  $('.ul-categoria li').show();
  $("#categoria_input").select();
});
$(document).on('click', '.btn-categoria', function(e) {
  e.preventDefault();
  var te = $(this).text();
  var re = $(this).attr('rel');
  $("#categoria_id").val(re);
  $("#categoria_input").val(te);
  $('.ul-categoria').hide();
});
$(document).on('click', '.btn-categoria-create', function(e) {
  e.preventDefault();
  var re = $(this).attr('rel');
  $("#form-categoria #categoria_id").val(re);
  $("#categoria-nova").removeClass("hidden");
  $("#form-categoria input[name='nome']").focus();
  $('.ul-categoria li').hide(); 
});
$(document).on('click', '.btn-categoria-cancelar', function(e) {
  e.preventDefault();
  $("#categoria-nova").addClass("hidden");
  $("#categoria_input").focus();
});
$(document).on('keyup', "#form-categoria input[name='nome']", function(e){
  e.preventDefault();
  $("#form-categoria input[name='descricao']").val($(this).val());
});
$(document).on("submit", "#form-categoria", function(e) {
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
          $("#form-categoria #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        $("#categoria_id").val(data.id);
        $(".ul-categoria li[rel='"+data.categoria_id+"']").append('<a href="#" class="btn-categoria uppercase" rel="'+data.id+'">'+data.nome+'</a>');
        $("#categoria_input").val(data.nome);
        $("#categoria-nova").addClass("hidden");
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
        $("#form-categoria #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on('keyup', '#centro_custo_input', function() {
  var v = $('#centro_custo_input').val();
  var rex = new RegExp($(this).val(), 'i');
  $("#centro_custo_id").val();
  if(v.length == 0){
    $('.ul-centro-custo li, .ul-centro-custo li a').show();
  } else {
    $('.ul-centro-custo li').hide();
    $('.ul-centro-custo li').filter(function () {
      return rex.test($(this).text());
    }).show();
    if($('.ul-centro-custo li:visible').length == 0){
      $('#btn-centro-custo-novo').removeClass('hidden');
    } else {
      $('#btn-centro-custo-novo').addClass('hidden');
    }
  }
});
$(document).on('focusout', '#centro_custo_input', function(e) {
  e.preventDefault();
  if($('.ul-centro-custo li a:visible').length == 1){
    $('.ul-centro-custo li a:visible').trigger("click");
  } else if($('.ul-centro-custo li a:visible').length == 0){
    $('.ul-centro-custo li a').show();
  }
});
$(document).on('focusin', '#centro_custo_input', function(e) {
  e.preventDefault();
  $('.ul-centro-custo li').show();
  $("#centro_custo_input").select();
});
$(document).on('click', '.btn-centro-custo', function(e) {
  e.preventDefault();
  var te = $(this).text();
  var re = $(this).attr('rel');
  $("#centro_custo_id").val(re);
  $("#centro_custo_input").val(te);
  $('.ul-centro-custo li').hide();
});
$(document).on('click', '.btn-centro-custo-create', function(e) {
  e.preventDefault();
  $("#centro-custo-nova").removeClass("hidden");
  $("#form-centro-custo input[name='nome']").val($("#centro_custo_input").val()).select();
  $('.ul-centro-custo li').hide();
});
$(document).on('click', '.btn-centro-custo-cancelar', function(e) {
  e.preventDefault();
  $("#centro-custo-nova, #btn-centro-custo-novo").addClass("hidden");
  $("#centro_custo_input").val("").focus();
});
$(document).on("change", "#form-centro-custo select[name='centro_custo_id']", function(e) {
  e.preventDefault();
  $( "select option:selected" ).each(function() {
    $("#form-centro-custo input[name='descricao']").val($(this).attr('desc'));
  });
});
$(document).on("submit", "#form-centro-custo", function(e) {
  e.preventDefault();
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  $(".se-pre-con").fadeIn();
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
          $("#form-centro-custo #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        $("#centro_custo_id").val(data.id);
        $(".ul-centro-custo").append('<li class="uppercase" rel="'+data.id+'"><a href="#" class="btn-centro-custo uppercase" rel="'+data.id+'">'+data.nome+'</a></li>');
        $("#centro_custo_input").val(data.nome);
        $("#centro-custo-nova, #btn-centro-custo-novo").addClass("hidden");
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
        $("#form-centro-custo #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on("click", "#btn-transferencia-salvar", function(e) {
  e.preventDefault();
  $("#btn-transferencia-form").trigger('click');
});
$(document).on("submit", "#transferenciaForm", function(e) {
  e.preventDefault();
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  $(".se-pre-con").fadeIn();
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
          $("#transferenciaForm #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        $('.mes-3').trigger("click");
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
        $("#transferenciaForm #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on("click", "#btn-movimento-salvar", function(e) {
  e.preventDefault();
  $("#btn-movimento-form").trigger("click");
});
$(document).on('click', '.btn-movimento-cancelar, .btn-conta-cancelar', function(e) {
  e.preventDefault();
  $(".tools-user").removeClass("hidden");
  $(".delete-border").removeClass("delete-border");
  $(".delete-border-top").removeClass("delete-border-top");
  $(".delete-border-bottom").removeClass("delete-border-bottom");
  $("#div-list").removeClass("col-sm-8").addClass("col-sm-12");
  $("#div-crud").addClass("hidden");
  $(".ativo").removeClass("ativo");
});
$(document).on('click', '#print', function(e){
  e.preventDefault();
  $("#grid-table-body").parent().css({ "height": "100%", "max-height": "100%" }).animate({ scrollTop: 0 }, 0);
  $("#grid-table-body").css({ "height": "100%", "max-height": "100%" }).animate({ scrollTop: 0 }, 0);
  window.print();
});
$(document).on('change', '#repetir', function(e){
  if( !$('#repetir').is(':checked') ){
    $(".repetir-div").addClass("hidden");
    $(this).val(0);
    psfc.update();
  } else {
    $(".repetir-div").removeClass("hidden");
    $("#ciclo").focus();
    $(this).val(1);
    $(this).val(1).closest("form").animate({ scrollTop: $(this).offset().top+360 }, 1000);
  }
});
$(document).on('change', '#outro', function(e){
  if( !$('#outro').is(':checked') ){
    $(".outro-div").addClass("hidden");
    $("#outro").parent().css("margin-bottom", "40px");
    psfc.update();
  } else {
    $(".outro-div").removeClass("hidden");

    $("#num_doc").focus();

    $(this).val(1).closest("form").animate({ scrollTop: $(this).closest("form").prop("scrollHeight") }, 1000);
    $("#outro").parent().css("margin-bottom", "0");
  }
});
$(document).on('click', '.prne', function(e){
  e.preventDefault();
  var n = parseInt($(this).attr("rel"));
  var a = parseInt($(".mes-3").attr("rel"));
  liCalendar(n+a);
  $('.mes-3').trigger("click");
});
$(document).on("click", "#valor, #desconto, #juro", function(e) {
  e.preventDefault();
  $(this).select();
});
$(document).on('focusout', '#valor, #juro, #desconto', function(e) {
  if($(this).val() == null){
    $(this).val(0);
  }
});
var liCalendar = function(M) {
  $(".mes-1").attr("rel", M-2).attr("date", moment().add(M-2, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M-2, 'months').format('MMM') + '</div><div>' + moment().add(M-2, 'months').format('YYYY') + '</div>');
  $(".mes-2").attr("rel", M-1).attr("date", moment().add(M-1, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M-1, 'months').format('MMM') + '</div><div>' + moment().add(M-1, 'months').format('YYYY') + '</div>');
  $(".mes-3").attr("rel", M).attr("date", moment().add(M, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M, 'months').format('MMM') + '</div><div>' + moment().add(M, 'months').format('YYYY') + '</div>');
  $(".mes-4").attr("rel", M+1).attr("date", moment().add(M+1, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M+1, 'months').format('MMM') + '</div><div>' + moment().add(M+1, 'months').format('YYYY') + '</div>');
  $(".mes-5").attr("rel", M+2).attr("date", moment().add(M+2, 'months').format('YYYY-MM')).html('<div class="nav-calendar-top"></div><div class="uppercase">' + moment().add(M+2, 'months').format('MMM') + '</div><div>' + moment().add(M+2, 'months').format('YYYY') + '</div>');

  $("#data_inicio").val(moment().add(M, 'months').format('YYYY-MM'));
  $("#data").val("fdem");
  $("#form-pesquisa").submit();
}
var resizediv = function (){
  var h =  $("body").innerHeight();
      h -= $(".content-header").outerHeight(true);
      h -= $("#btn-movimento-tools").length ? $("#btn-movimento-tools").outerHeight(true) : 0;
      h -= $("#div-search").length ? $("#div-search").outerHeight(true) : 0;
      h -= $("#movimento-filtro").hasClass('active') || $("#movimento-filtro").hasClass('movimento-filtro-ativo') ? $("#movimento-filtro").outerHeight(true) : 0;
      h -= $("#grid-table-header").length ? $("#grid-table-header").outerHeight(true) : 0;
      h -= $("#movimento-footer").length ? $("#movimento-footer").outerHeight(true) : 0;
      h -= $(".pagination-bottom").length ? $(".pagination-bottom").outerHeight(true) : 0;

  $(".form-crud").css("height", h-70);
  h -= 2;
  $("#NovaConta, #ContaCorrente").css("height", h);
}