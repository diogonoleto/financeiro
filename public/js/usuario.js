$(document).ready(function() {
  $(window).resize(function() {
    resizediv();
  });
});

$(document).on("click", "#btn-img-editar, #btn-image-cancelar", function(e){
  e.preventDefault();
  $("#div-image").toggleClass("hidden");
  $("#btn-img-editar").toggleClass("hidden");
});
$(document).on("click", ".btn-endereco-edit, .btn-endereco-novo", function(e){
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  $("#usuarioEnderecoForm").trigger("reset");
  $("#usuarioEnderecoForm #id").val("");
  $("#endereco").trigger("click");
  if($(this).hasClass("btn-endereco-novo")){
    $(".se-pre-con").fadeOut();
    return false;
  }
  var url = $(this).attr("route");
  var get = "GET";
  $.ajax({
    url: url,
    type: get,
    success: function(data){
      $.each(data, function(k,v) {
        $("#usuarioEnderecoForm #"+k).val(v);
        $(".se-pre-con").fadeOut();
      });
    },
    error: function(){
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
    }
  });
});
$(document).on("click", ".btn-contato-edit, .btn-contato-novo", function(e){
  e.preventDefault();
  $(".help-block").html('');
  $(".has-error").removeClass("has-error");
  $(".se-pre-con").fadeIn();
  $("#usuarioContatoForm").trigger("reset");
  $("#usuarioContatoForm #id").val("");
  $("#contato").trigger("click");
  if($(this).hasClass("btn-contato-novo")){
    $(".se-pre-con").fadeOut();
    return false;
  }
  var url = $(this).attr("route");
  var get = "GET";
  $.ajax({
    url: url,
    type: get,
    success: function(data){
      $.each(data, function(k,v) {
        $("#usuarioContatoForm #"+k).val(v);
        $(".se-pre-con").fadeOut();
        if(k == "tipo_contato"){
          if( v == 1 ){
            $("#descricao-lab").text("E-Mail");
            $("#descricao").unmask();
            $("#descricao").prop({"type": "email", "placeholder": "Digite o E-Mail!"}).removeClass("text-right");
          } else {
            $("#descricao-lab").text("Telefone");
            $("#descricao").prop({ "type": "text", "maxlength":"15" }).addClass("text-right");
            $('#descricao').mask('(00) 00000-0000', {
              onKeyPress: function(tel, e, field, options){
                var masks = ['(00) 00000-0000', '(00) 0000-0000#'];
                mask = (tel.length>14) ? masks[0] : masks[1];
                $('#descricao').mask(mask, options);
              },
              placeholder: "(00) 00000-0000"
            });
          }
          if(v == 3){
            $("#descricao-lab").text("Fax");
          }
        }
      });
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
$(document).on("click", ".btn-conta-nova", function(e){
  e.preventDefault();
  $("#usuarioContaForm").trigger("reset");
  $("#conta_id").val("");
  $("#daba").trigger("click");
});
$(document).on("click", ".btn-conta-edit", function(e){
  e.preventDefault();
  $(".div-daba #conta_id").val($(this).attr("conta_id"));
  $(".div-daba #tipo_conta").val($(this).attr("tipo_conta"));
  $(".div-daba #banco_id").val($(this).attr("banco_id"));
  var ba = $(this).attr("banco_id");
  $(".div-daba .bb-"+ba).trigger("click");
  $(".div-daba #agencia").val($(this).attr("agencia"));
  $(".div-daba #conta").val($(this).attr("conta"));
  $("#daba").trigger("click");
});
$(document).on("click", ".btn-dase-edit", function(e){
  e.preventDefault();
  $("#dase").trigger("click");
});
$(document).on("click", ".btn-dapri-edit", function(e){
  e.preventDefault();
  $(this).addClass("hidden").parent().children("h4, .cpfs, .reg").addClass("hidden");
  $(this).parent().children("form").removeClass("hidden");
});
$(document).on("click", "#btn-dapri-cancelar", function(e){
  e.preventDefault();
  $(this).parent().parent().addClass("hidden").parent().children("h4, .cpfs, .reg, a").removeClass("hidden");
});
$(document).on("change", "#img", function(e){
  e.preventDefault();
  var tmppath = URL.createObjectURL(e.target.files[0]);
  $("#perfil-img").fadeIn("fast").attr('src',tmppath);
  $("#avatar").val("");
});
$(document).on("click", "#btn-avatar", function(e){
  e.preventDefault();
  $("#perfil-img").fadeIn("fast").attr('src',$(this).attr('src'));
  $("#avatar").val($(this).attr('rel'));
  $('#img').val('');
});
$(document).on('click', '.btn-usuario-create, .btn-usuario-edit', function(e){
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  $("#input-search").val("").parent().parent().addClass("hidden");
  if( $(this).hasClass('ativo')){
    $("#div-list").removeClass("col-sm-8").removeClass("col-sm-4").addClass("col-sm-12");
    $(".pagination-bottom").removeClass("pag-right");
    $("#div-crud").addClass("hidden").removeClass("col-sm-8").html("");
    $(".ativo").removeClass("ativo");
    $(".se-pre-con").fadeOut();
    return false;
  }
  var ati = 0;
  $(".ativo").each(function(i, item){
    ati = 1;
  });
  if(ati == 1){
    $(".ativo").removeClass("ativo");
    $("#div-list").removeClass("col-sm-4");
    $("#div-crud").removeClass("col-sm-8").addClass("col-sm-4").html("");
  } else {
    $("#div-list").addClass("col-sm-8").removeClass("col-sm-12");
    $("#div-crud").toggleClass("hidden").addClass("col-sm-4");
    $(".pagination-bottom").addClass("pag-right");
  }
  if($(this).hasClass('btn-usuario-edit')){
    $("#div-crud").addClass("edit");
  } else {
    $("#div-crud").removeClass("edit");
  }
  $(this).addClass('ativo');
  $("#form-usuario-create").attr("action", $(this).attr("route"));
  $("#form-usuario-create").submit();
});
$(document).on("submit", "#form-usuario-create", function(e){
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  var url = $(this).attr("action");
  var get = $(this).attr("method");
  var data = $(this).serializeArray();
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      $("#div-crud").html(data);
      var h = $("body").innerHeight();
      h -= $(".content-header").innerHeight();
      h -= 70;
      $("#usuario-novo").css("height", h);
      atualiquery();
    },
    error: function(data){
      console.log(data);
      $("#movimento-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
      $("#movimento-create").toggleClass("hidden");
      $(".se-pre-con").fadeOut();
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
    }
  });
});
$(document).on("submit", "#usuarioForm, #usuarioEditForm", function(e) {
  e.preventDefault();
  var id = $(this).attr('id');
  $(".se-pre-con").fadeIn();
  $(".has-error").removeClass("has-error").children(".help-block").last().html('');
  var url = $(this).attr("action");
  var get = $(this).attr("method");
  var data = $(this).serializeArray();
  var id = $(this).attr("id");
  ob = {};
  $(data).each(function(i, field){
    ob[field.name] = field.value;
  });
  if(!valida_cpf(ob['cpf'])){
    var error = "CPF é inválido";
    $("#"+id+" #cpf").parent().addClass("has-error").children(".help-block").html('<strong>'+error+'</strong>');
    $(".se-pre-con").fadeOut();
    return false;
  }
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      $(".help-block").html('');
      $(".has-error").removeClass("has-error");
      if(data.error){
        $.each(data.error , function( key, value ) {
          $("#"+id+" #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
      } else {
        var qtde = $("#btn-usuario-create").attr("qtde");
        qtde -= qtde ? 1 : qtde;
        if(qtde){
          if(qtde == 0){
            $("#btn-usuario-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>'+qtde).addClass("hidden");
          } else {
            $("#btn-usuario-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>'+qtde);
          }
        } else {
          $("#btn-usuario-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>');
        }
        $("#btn-usuario-create").attr("qtde", qtde);
        $("#div-crud").html(data).addClass("edit");
        $(".div-right").removeClass("hidden");
        $("#div-list").removeClass("col-sm-4");
        $(".div-impr").removeClass("col-sm-6");
        $("#div-crud").addClass("col-sm-4");
        $("#form-search").submit();
        atualiquery();
      }
    },
    error: function(data){
      $(".help-block").html('');
      $(".has-error").removeClass("has-error");
      $.each( data.responseJSON , function( key, value ) {
        $("#"+id+" #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
      });
      $(".se-pre-con").fadeOut()
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
    }
  });
});
$(document).on("submit", "#usuarioMaisInfoForm, #usuarioEnderecoForm, #usuarioContatoForm, #usuarioContaForm", function(e) {
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
      $(".help-block").html('');
      $(".has-error").removeClass("has-error");
      if(data.error){
        $.each(data.error , function( key, value ) {
          if(key == "descricao")
            value = $("#tipo_contato").val() == 1 ? "O E-Mail já existe." : $("#tipo_contato").val() == 2 ? "O Telefone já existe." : "O Fax já existe."
          $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
        $(".se-pre-con").fadeOut();
      } else {
        $("#div-crud").html(data).addClass("edit");
        $(".div-right").removeClass("hidden");
        $("#div-list").removeClass("col-sm-4");
        $(".div-impr").removeClass("col-sm-6");
        $("#div-crud").addClass("col-sm-4");
        atualiquery();
      }
    },
    error: function(data){
      console.log(data.responseJSON);
      $(".help-block").html('');
      $(".has-error").removeClass("has-error");
      $.each( data.responseJSON , function( key, value ) {
        if(key == "descricao")
          value[0] = $("#tipo_contato").val() == 1 ? "O E-Mail já existe." : $("#tipo_contato").val() == 2 ? "O Telefone já existe." : "O Fax já existe."
        $("#"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value[0]+'</strong>');
      });
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on('change', '#rtradios', function(e){
  $('input[name="radio"]').prop('checked', false);
});
$(document).on('change', '#dase', function(e){
  if( !$('#dase').is(':checked') ){
    $(".div-right").removeClass("hidden");
    $("#div-list").removeClass("col-sm-4");
    $(".div-impr").removeClass("col-sm-6");
    $("#div-crud").addClass("col-sm-4");
    $(".div-dase").addClass("hidden");
  } else {
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4").addClass("col-sm-8");
    $(".div-dase").removeClass("hidden");
    $("#rg").focus();
  }
});
$(document).on('change', "#endereco", function(e){
  if( !$('.div-impr #endereco').is(':checked') ){
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4");
    $(".div-ende").addClass("hidden");
  } else {
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4").addClass("col-sm-8");
    $(".div-ende").removeClass("hidden");
    $(".div-impr #cep").focus();
  }
});
$(document).on('change', "#end", function(e){
  if( !$('#usuarioForm #end').is(':checked') ){
    $("#usuarioForm .end-div").addClass("hidden");
    $(this).val(0);
    $("#usuarioForm #end").parent().css("margin-bottom", "40px");
  } else {
    $("#usuarioForm .end-div").removeClass("hidden");
    $("#usuarioForm #cep").focus();
    $(this).val(1);
    $("#usuario-novo").animate({scrollTop: $('#usuario-novo').prop("scrollHeight") }, 1000);
    $("#usuarioForm #end").parent().css("margin-bottom", "0");
  }
});
$(document).on('change', "#con", function(e){
  if( !$('#usuarioForm #con').is(':checked') ){
    $("#usuarioForm .con-div").addClass("hidden");
    $(this).val(0);
  } else {
    $("#usuarioForm .con-div").removeClass("hidden");
    $("#usuarioForm #tipo_conta").focus();
    $(this).val(1);
    $("#usuario-novo").animate({scrollTop: $('#usuario-novo').prop("scrollHeight") }, 1000);
  }
});
$(document).on('change', '#contato', function(e){
  if( !$('#contato').is(':checked') ){
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4");
    $(".div-contato").addClass("hidden");
  } else {
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4").addClass("col-sm-8");
    $(".div-contato").removeClass("hidden");
    $("#tipo_contato").focus();
  }
});
$(document).on('change', '#daba', function(e){
  if( !$('#daba').is(':checked') ){
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4");
    $(".div-daba").addClass("hidden");
  } else {
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4").addClass("col-sm-8");
    $(".div-daba").removeClass("hidden");
    $("#tipo_conta").focus();
  }
});
$(document).on('change', '#desativar', function(e){
  if(!$('#desativar').is(':checked')){
  } else {
  }
});
$(document).on('change', '#iie', function(e){
  if($('#iie').is(':checked')){
    $(".div-iie").addClass("hidden");
    $(this).val(1);
  } else {
    $(".div-iie").removeClass("hidden");
    $("#cep").focus();
  }
});
$(document).on('change', '#tipo_contato', function(e){
  $("#descricao-lab").text($(this).find('option:selected').text());
  if($(this).val() == 1){
    $("#descricao").prop({ "type": "email", "placeholder":"Digite o e-mail!" }).removeClass("text-right");
    $("#descricao").unmask();
  } else {
    $("#descricao").prop({ "type": "text", "maxlength":"15" }).addClass("text-right");
    $('#descricao').mask('(00) 00000-0000', {
      onKeyPress: function(tel, e, field, options){
        var masks = ['(00) 00000-0000', '(00) 0000-0000#'];
        mask = (tel.length>14) ? masks[0] : masks[1];
        $('#descricao').mask(mask, options);
      },
      placeholder: "(00) 00000-0000"
    });
  }
});
$(document).on('click', '.btn-usuario-cancelar', function(e) {
  e.preventDefault();
  $("#div-list").addClass("col-sm-8").removeClass("col-sm-4");
  $("#div-crud").addClass("col-sm-4").removeClass("col-sm-8");
  $(".div-impr").removeClass("col-sm-6");
  $("input[name='radio']").prop('checked', false);
  $(this).parent().parent().parent().addClass("hidden");
});
$(document).on('keyup', '.banco_input', function() {
  var v = $(this).val();
  var rex = new RegExp($(this).val(), 'i');
  if(v.length == 0){
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children("li").show();
  } else {
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li').hide();
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li').filter(function () {
      return rex.test($(this).text());
    }).show();
  }
});
$(document).on('focusout', '.banco_input', function(e) {
  e.preventDefault();
  if($(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').length == 1){
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').children('a').trigger("click");
  }
});
$(document).on('focusin', '.banco_input', function(e) {
  e.preventDefault();
  $(this).select();

  var v = $(this).val();
  var rex = new RegExp($(this).val(), 'i');
  if(v.length == 0){
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children("li").show();
  } else {
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li').hide();
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li').filter(function () {
      return rex.test($(this).text());
    }).show();
  }
  if($(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').length == 1){
    $(this).parent().children('.scroll-wrapper').children('.ul-banco').children('li:visible').children('a').trigger("click");
  }
});
$(document).on('click', '.btn-banco', function(e) {
  e.preventDefault();
  var te = $(this).text();
  var re = $(this).attr('rel');
  $(this).parent().parent().parent().parent().children("#banco_id").val(re);
  $(this).parent().parent().parent().parent().children(".banco_input").val(te);
  $(this).parent().parent().children('li').hide();
});
$(document).on("click", ".item-delete", function(){
  $(".se-pre-con").fadeIn();
  var acao = $(this).attr("rel");
  $("#acao").val(acao);
  var url = $(this).attr("route");
  var data = $("#delete-form").serializeArray();
  $.ajax({
    url: url,
    type: "post",
    data: data,
    success: function(data){
      $("#div-crud").html(data);
      $(".div-right").removeClass("hidden");
      $("#div-list").removeClass("col-sm-4");
      $(".div-impr").removeClass("col-sm-6");
      $("#div-crud").addClass("col-sm-4");
      $("#acao").val("");
      atualiquery();
    },
    error: function(data){
      $(".se-pre-con").fadeOut();
      $("#acao").val("");
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
    }
  });
});
$(document).on('submit', '.dropzone', function(e) {
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  var formData = new FormData(this);
  $.ajax({
    type:'POST',
    url: $(this).attr('action'),
    data:formData,
    cache:false,
    contentType: false,
    processData: false
  }).done(function(data){
    $("#div-crud").html(data);
    atualiquery();
  });
});
$(document).on("change", "#tipo_identificacao", function(e){
  e.preventDefault();
  $(this).parent().parent().children("input").first().prop({"id": $(this).val(), "name": $(this).val()});
  $(this).val() == 'rg' ? $(this).parent().parent().children("input, label").addClass("oprg").focus() : $(this).parent().parent().children("input, label").removeClass("oprg");
});
$(document).on("click", "#btn-mais-identificacao", function(e){
  e.preventDefault();
  var opt = '';
  var id = '';
  var oerg = '';
  var coerg = '';
  var qtd = 0;
  $("#usuarioMaisInfoForm #tipo_identificacao").prop("disabled", true);
  if($("#usuarioMaisInfoForm #rg").length == 0){
    opt += '<option value="rg">RG</option>';
    oerg = '<label for="oerg" class="idop oprg">Orgão Exp.</label><input type="text" id="oerg" name="oerg" class="form-control idop oprg" size="20" maxlength="20" value="" autocomplete="off">';
    coerg = 'oprg';
    qtd++;
  }
  if($("#usuarioMaisInfoForm #cnh").length == 0){
    opt += '<option value="cnh">CNH</option>';
    qtd++;
  }
  if($("#usuarioMaisInfoForm #crea").length == 0){
    opt += '<option value="crea">CREA</option>';
    qtd++;
  }
  if($("#usuarioMaisInfoForm #crm").length == 0){
    opt += '<option value="crm">CRM</option>';
    qtd++;
  }
  if($("#usuarioMaisInfoForm #cro").length == 0){
    opt += '<option value="cro">CRO</option>';
    qtd++;
  }
  if($("#usuarioMaisInfoForm #oab").length == 0){
    opt += '<option value="oab">OAB</option>';
    qtd++;
  }
  id = $("#usuarioMaisInfoForm #oab").length == 0 ? 'oab' : id;
  id = $("#usuarioMaisInfoForm #cro").length == 0 ? 'cro' : id;
  id = $("#usuarioMaisInfoForm #crm").length == 0 ? 'crm' : id;
  id = $("#usuarioMaisInfoForm #crea").length == 0 ? 'crea': id;
  id = $("#usuarioMaisInfoForm #cnh").length == 0 ? 'cnh' : id;
  id = $("#usuarioMaisInfoForm #rg").length == 0 ? 'rg' : id;
  $(this).parent().before('<div class="col-md-12">'+
    '<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">'+
    '<label for="tipo_identificacao">Tipo</label>'+
    '<span class="input-group-btn"><select class="form-control" name="tipo_identificacao" id="tipo_identificacao" required>'+opt+'</select></span>'+
    '<label for="regra_id" class="idop '+coerg+'">N° de Identificação</label>'+
    '<input type="text" id="'+id+'" name="'+id+'" class="form-control text-right idop '+coerg+'" size="20" maxlength="20" value="" autocomplete="off">'
    +oerg+
    '<span class="help-block"></span>'+
    '</div>'+
    '</div>');
  $("#"+id).focus();
  if(qtd==1){
    $(this).addClass("hidden");
    $("#usuarioMaisInfoForm #tipo_identificacao").prop("disabled", true);
  } else {
    $(this).removeClass("hidden");
  }
});
var atualiquery = function(){
  $('[data-toggle=confirmation]').confirmation({ rootSelector: '[data-toggle=confirmation]', container: 'body' });
  $('[data-toggle="tooltip"]').tooltip();

  $('.div-dase .form-crud, .div-end-ps, #usuarioForm .form-crud, .div-impr, .ul-banco').scrollbar({ "scrollx": "none", disableBodyScroll: true });
  resizediv();
  $("#data_nascimento").datetimepicker({
    locale: 'pt-BR',
    format: 'DD/MM/YYYY',
    viewMode: 'years',
    widgetPositioning: {
      horizontal: 'right',
      vertical: 'bottom'
    }
  });
  $("#usuarioEditForm #cpf, #usuarioForm #cpf").mask('000.000.000-00', { placeholder: "000.000.000-00" });
  $('#telefone').mask('(00) 00000-0000', {
    onKeyPress: function(tel, e, field, options){
      var masks = ['(00) 00000-0000', '(00) 0000-0000#'];
      mask = (tel.length>14) ? masks[0] : masks[1];
      $('#telefone').mask(mask, options);
    },
    placeholder: "(00) 00000-0000"
  });
  $('#usuarioEnderecoForm #cep').mask('00.000-000', {
    onComplete: function(cep) {
      $(".se-pre-con").fadeIn();
      var v = $('#empresaEnderecoForm #cep').val();
      $.ajax({
        url: urlCEP,
        type: 'GET',
        data: { cep:v },
        success: function(data){
          $("#empresaEnderecoForm #logradouro").val(data.logradouro);
          $("#empresaEnderecoForm #cidade").val(data.cidade);
          $("#empresaEnderecoForm #bairro").val(data.bairro);
          $("#empresaEnderecoForm #numero").focus();
          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          console.log(data);
          $(".se-pre-con").fadeOut();
        }
      });
    }
  });

  $('#usuarioForm #cep').mask('00.000-000', {
    onComplete: function(cep) {
      $(".se-pre-con").fadeIn();
      var v = $('#empresaForm #cep').val();
      $.ajax({
        url: urlCEP,
        type: 'GET',
        data: { cep:v },
        success: function(data){
          $("#empresaForm #logradouro").val(data.logradouro);
          $("#empresaForm #cidade").val(data.cidade);
          $("#empresaForm #bairro").val(data.bairro);
          $("#empresaForm #numero").focus();
          $(".se-pre-con").fadeOut();
        },
        error: function(data){
          console.log(data);
          $(".se-pre-con").fadeOut();
        }
      });
    }
  });
  $(".se-pre-con").fadeOut();
}
var resizediv = function (){
  var h = $("body").innerHeight();
  h -= $(".content-header").innerHeight();
  h -= 70;
  $("#empresa-novo").css("height", h);
  $(".div-dase .form-crud, .div-end-ps, #usuarioForm .form-crud").css("height", h-40);
}