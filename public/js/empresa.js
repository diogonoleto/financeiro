$(document).ready(function() {
  $(window).resize(function() {
    resizediv();
  });
});

$(document).on('click', '.mparent', function(e){
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  $(".grid-table").toggleClass("hidden");
  $(this).parent().toggleClass("hidden");
  $(".mchild-"+$(this).attr('rel')).toggleClass("hidden");

  if( $(".btn-empresa-create").hasClass('ativo') || $(".btn-empresa-edit").hasClass('ativo')){
    $("#div-list").removeClass("col-sm-8").removeClass("col-sm-4").addClass("col-sm-12");
    $(".pagination-bottom").removeClass("pag-right");
    $("#div-crud").addClass("hidden").removeClass("col-sm-8").html("");
    $(".ativo").removeClass("ativo");
  }
  $(".se-pre-con").fadeOut();
});


$(document).on("click", "#btn-img-editar, #btn-image-cancelar", function(e){
  e.preventDefault();
  $("#div-image").toggleClass("hidden");
  $("#btn-img-editar").toggleClass("hidden");
});
$(document).on("click", ".btn-endereco-edit, .btn-endereco-novo", function(e){
  e.preventDefault();
  $(".se-pre-con").fadeIn();
  $("#empresaEnderecoForm").trigger("reset");
  $("#empresaEnderecoForm #id").val("");
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
        $("#empresaEnderecoForm #"+k).val(v);
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
  $("#empresaContatoForm").trigger("reset");
  $("#empresaContatoForm #id").val("");
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
        $("#empresaContatoForm #"+k).val(v);
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
  $("#empresaContaForm").trigger("reset");
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
  if($("#cnpj").val().length > 14){
    $("#razao_social").attr("placeholder", "Razão Social").parent().children("label").html("Razão Social<span>*</span>");
    $(".div-dase #data_fundacao").attr("placeholder", "Data da Fundação").parent().children("label").text("Data da Fundação");
    $(".div-cnpj").removeClass("hidden");
    $(".div-cpf").addClass("hidden");
  } else {
    $(".div-cnpj").addClass("hidden");
    $(".div-cpf").removeClass("hidden");
    $("#razao_social").attr("placeholder", "Nome").parent().children("label").html("Nome<span>*</span");
    $(".div-dase #data_fundacao").attr("placeholder", "Data de Nascimento").parent().children("label").text("Data de Nascimento");
  }
  $("#dase").trigger("click");
});
$(document).on("click", ".btn-dapri-edit", function(e){
  e.preventDefault();
  $(this).addClass("hidden").parent().children("h4, .cnpjs, .h-cnpj").addClass("hidden");
  $(this).parent().children("form").removeClass("hidden");
});
$(document).on("mouseleave", ".btn-conta-nova, .btn-conta-edit, .btn-dase-edit, .btn-endereco-novo", function(){
  $(".tooltip").remove();
});
$(document).on("click", "#btn-dapri-cancelar", function(e){
  e.preventDefault();
  $(this).parent().parent().addClass("hidden").parent().children("h4, .cnpjs, .h-cnpj, a").removeClass("hidden");
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
$(document).on('click', '.btn-empresa-create, .btn-empresa-edit', function(e){
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
  if($(this).hasClass('btn-empresa-edit')){
    $("#div-crud").addClass("edit");
  } else {
    $("#div-crud").removeClass("edit");
  }
  $(this).addClass('ativo');
  $("#form-empresa-create").attr("action", $(this).attr("route"));
  $("#form-empresa-create").submit();
});
$(document).on("submit", "#form-empresa-create", function(e){
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
      $("#empresa-novo").css("height", h);
      atualiquery();
    },
    error: function(data){
      console.log(data);
      $("#movimento-list").toggleClass("col-sm-8").toggleClass("col-xs-8").toggleClass("col-sm-12").toggleClass("col-xs-12");
      $("#movimento-create").toggleClass("hidden");
      if(data.status==404 || data.status==401) {
        location.reload();
      } else if (data.status==403){
        $("#grid-table-body").html("Você não tem acesso a essa informações!!");
      }
      $(".se-pre-con").fadeOut();
    }
  });
});
$(document).on("submit", "#empresaForm, #empresaEditForm", function(e) {
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
  if(!valida_cpf_cnpj(ob['cnpj'])){
    var error = "CPF ou CNPJ inválido";
    if(ob['cnpj'].length == 14)
      error = "CPF é inválido";
    else if(ob['cnpj'].length > 14)
      error = "CNPJ é inválido";
    $("#"+id+" #cnpj").parent().addClass("has-error").children(".help-block").html('<strong>'+error+'</strong>');
    $(".se-pre-con").fadeOut();
    return false;
  }
  $.ajax({
    url: url,
    type: get,
    data: data,
    success: function(data){
      console.log(data);
      $(".help-block").html('');
      $(".has-error").removeClass("has-error");
      if(data.error){
        $.each(data.error , function( key, value ) {
          if(key == "razao_social" && ob['cnpj'].length == 14){
            value = "O Nome já existe.";
          }
          if(key == "cnpj" && ob['cnpj'].length == 14){
            value = "O CPF já existe.";
          }
          $("#"+id+" #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
        });
        $(".se-pre-con").fadeOut();
      } else {
        var qtde = $("#btn-empresa-create").attr("qtde");
        qtde -= qtde ? 1 : qtde;
        if(qtde){
          if(qtde == 0){
            $("#btn-empresa-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>'+qtde).addClass("hidden");
          } else {
            $("#btn-empresa-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>'+qtde);
          }
        } else {
          $("#btn-empresa-create").html('<i class="mdi mdi-plus mdi-20px" aria-hidden="true"></i>');
        }
        $("#btn-empresa-create").attr("qtde", qtde);
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
        if(key == "razao_social" && ob['cnpj'].length == 14){
          value = "O Nome já existe.";
        }
        if(key == "cnpj" && ob['cnpj'].length == 14){
          value = "O CPF já existe.";
        }
        $("#"+id+" #"+key).parent().addClass("has-error").children(".help-block").html('<strong>'+value+'</strong>');
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
$(document).on("submit", "#empresaMaisInfoForm, #empresaEnderecoForm, #empresaContatoForm, #empresaContaForm", function(e) {
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
    if($("#cnpj").val().length > 14){
      $("#razao_social").attr("placeholder", "Razão Social").parent().children("label").html("Razão Social<span>*</span>");
      $(".div-dase #data_fundacao").attr("placeholder", "Data da Fundação").parent().children("label").text("Data da Fundação");
      $(".div-cnpj").removeClass("hidden");
      $(".div-cpf").addClass("hidden");
    } else {
      $(".div-cnpj").addClass("hidden");
      $(".div-cpf").removeClass("hidden");
      $("#razao_social").attr("placeholder", "Nome").parent().children("label").html("Nome<span>*</span");
      $(".div-dase #data_fundacao").attr("placeholder", "Data de Nascimento").parent().children("label").text("Data de Nascimento");
    }
    $(".div-right").addClass("hidden");
    $("#div-list").addClass("col-sm-4");
    $(".div-impr").addClass("col-sm-6");
    $("#div-crud").removeClass("col-sm-4").addClass("col-sm-8");
    $(".div-dase").removeClass("hidden");
    $("#rg").focus();
  }
});
$(document).on('change', '#endereco', function(e){
  if( !$('#endereco').is(':checked') ){
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
    $("#cep").focus();
  }
});
$(document).on('change', "#end", function(e){
  if( !$('#empresaForm #end').is(':checked') ){
    $("#empresaForm .end-div").addClass("hidden");
    $(this).val(0);
  } else {
    $("#empresaForm .end-div").removeClass("hidden");
    $("#empresaForm #cep").focus();
    $(this).val(1);
    $("#empresa-novo").animate({scrollTop: $('#empresa-novo').prop("scrollHeight") }, 1000);
  }
});
$(document).on('change', "#con", function(e){
  if( !$('#empresaForm #con').is(':checked') ){
    $("#empresaForm .con-div").addClass("hidden");
    $(this).val(0);
  } else {
    $("#empresaForm .con-div").removeClass("hidden");
    $("#empresaForm #tipo_conta").focus();
    $(this).val(1);
    $("#empresa-novo").animate({scrollTop: $('#empresa-novo').prop("scrollHeight") }, 1000);
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
$(document).on('click', '.btn-empresa-cancelar', function(e) {
  e.preventDefault();
  $("#div-list").addClass("col-sm-8").removeClass("col-sm-4");
  $("#div-crud").addClass("col-sm-4").removeClass("col-sm-8");
  $(".div-impr").removeClass("col-sm-6");

  $("input[name='radio']").prop('checked', false);
  $(this).parent().parent().parent().addClass("hidden");
});
$(document).on('click', '#empresaForm .btn-empresa-cancelar', function(e) {
  e.preventDefault();
  $(".btn-empresa-create").trigger("click");
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
      console.log(data);
      $(".se-pre-con").fadeOut();
      $("#acao").val("");
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
  $("#empresaMaisInfoForm #tipo_identificacao").prop("disabled", true);
  if($("#empresaMaisInfoForm #rg").length == 0){
    opt += '<option value="rg">RG</option>';
    oerg = '<label for="oerg" class="idop oprg">Orgão Exp.</label><input type="text" id="oerg" name="oerg" class="form-control idop oprg" size="20" maxlength="20" autocomplete="off">';
    coerg = 'oprg';
    qtd++;
  }
  if($("#empresaMaisInfoForm #cnh").length == 0){
    opt += '<option value="cnh">CNH</option>';
    qtd++;
  }
  if($("#empresaMaisInfoForm #crea").length == 0){
    opt += '<option value="crea">CREA</option>';
    qtd++;
  }
  if($("#empresaMaisInfoForm #crm").length == 0){
    opt += '<option value="crm">CRM</option>';
    qtd++;
  }
  if($("#empresaMaisInfoForm #cro").length == 0){
    opt += '<option value="cro">CRO</option>';
    qtd++;
  }
  if($("#empresaMaisInfoForm #oab").length == 0){
    opt += '<option value="oab">OAB</option>';
    qtd++;
  }
  id = $("#empresaMaisInfoForm #oab").length == 0 ? 'oab' : id;
  id = $("#empresaMaisInfoForm #cro").length == 0 ? 'cro' : id;
  id = $("#empresaMaisInfoForm #crm").length == 0 ? 'crm' : id;
  id = $("#empresaMaisInfoForm #crea").length == 0 ? 'crea': id;
  id = $("#empresaMaisInfoForm #cnh").length == 0 ? 'cnh' : id;
  id = $("#empresaMaisInfoForm #rg").length == 0 ? 'rg' : id;
  $(this).parent().before('<div class="col-md-12">'+
    '<div class="input-group" style="margin-top: 10px; margin-bottom: 10px;">'+
    '<label for="tipo_identificacao">Tipo</label>'+
    '<span class="input-group-btn"><select class="form-control" name="tipo_identificacao" id="tipo_identificacao" required>'+opt+'</select></span>'+
    '<label for="regra_id" class="idop '+coerg+'">N° de Identificação</label>'+
    '<input type="text" id="'+id+'" name="'+id+'" class="form-control text-right idop '+coerg+'" size="20" maxlength="20" autocomplete="off">'
    +oerg+
    '<span class="help-block"></span>'+
    '</div>'+
    '</div>');
  $("#"+id).focus();
  if(qtd==1){
    $(this).addClass("hidden");
    $("#empresaMaisInfoForm #tipo_identificacao").prop("disabled", true);
  } else {
    $(this).removeClass("hidden");
  }
});
var atualiquery = function(){
  $('[data-toggle=confirmation]').confirmation({ rootSelector: '[data-toggle=confirmation]', container: 'body' });
  $('[data-toggle="tooltip"]').tooltip();
  $('.div-dase .form-crud, .div-end-ps, #empresaForm .form-crud, .div-impr').scrollbar({ "scrollx": "none", disableBodyScroll: true });

  $('.ul-banco').scrollbar({ "scrollx": "none", disableBodyScroll: true }).parent().css({"max-height" : "200px"});
  resizediv();
  $("#data_fundacao").datetimepicker({
    locale: 'pt-BR',
    format: 'DD/MM/YYYY',
    viewMode: 'years',
    widgetPositioning: {
      horizontal: 'right',
      vertical: 'bottom'
    }
  });
  var maskcnpj = "00.000.000/0000-00";
  if($("#cnpj").val().length <= 11){
    maskcnpj = '000.000.000-00####';
    $(".div-cnpj, .h-cnpj").addClass("hidden");
    $(".div-cpf").removeClass("hidden");
    $("#razao_social").attr("placeholder", "Nome").parent().children("label").html("Nome<span>*</span");
    $("#nome_fantasia").val("");
    $(".div-dase #data_fundacao").attr("placeholder", "Data de Nascimento").parent().children("label").text("Data de Nascimento");
  } else {
    $("#razao_social").attr("placeholder", "Razão Social").parent().children("label").html("Razão Social<span>*</span>");
    $(".div-dase #data_fundacao").attr("placeholder", "Data da Fundação").parent().children("label").text("Data da Fundação");
    $(".div-cnpj, .h-cnpj").removeClass("hidden");
    $(".div-cpf").addClass("hidden"); 
  }
  $("#empresaEditForm #cnpj, #empresaForm #cnpj").mask(maskcnpj, { placeholder: "000.000.000-00",
    onKeyPress: function(cn, e, field, options){
      var masks = ['00.000.000/0000-00', '000.000.000-00####']; 
      if(cn.length>14){
        mask = masks[0];
        $("#empresaEditForm #razao_social, #empresaForm #razao_social").attr("placeholder", "Razão Social").parent().children("label").html("Razão Social<span>*</span>");
        $(".div-dase #data_fundacao").attr("placeholder", "Data da Fundação").parent().children("label").text("Data da Fundação");
        $(".div-cnpj").removeClass("hidden");
        $(".div-cpf").addClass("hidden");
      } else {
        mask = masks[1];
        $("#empresaEditForm #razao_social, #empresaForm #razao_social").attr("placeholder", "Nome").parent().children("label").html("Nome<span>*</span");
        $(".div-dase #data_fundacao").attr("placeholder", "Data de Nascimento").parent().children("label").text("Data de Nascimento");
        $(".div-cnpj").addClass("hidden");
        $(".div-cpf").removeClass("hidden");
      }
      $('#empresaEditForm #cnpj, #empresaForm #cnpj').mask(mask, options);
    },
    onComplete: function(cnpj) {
      $("#empresaEditForm #razao_social, #empresaForm #razao_social").attr("placeholder", "Razão Social").parent().children("label").html("Razão Social<span>*</span>");
      $(".div-dase #data_fundacao").attr("placeholder", "Data da Fundação").parent().children("label").text("Data da Fundação");
      $(".div-cnpj").removeClass("hidden");
      $(".div-cpf").addClass("hidden");
      cnpj = cnpj.replace(/[^\w\s]/gi, '');
      $.ajax({
        url: "https://www.receitaws.com.br/v1/cnpj/"+cnpj,
        type: "get",
        dataType: "JSONP",
        success: function(data){
          console.log(data);
          if( !$('#end').is(':checked') ){
            $("#end").trigger("click");
          }
          $("#empresaEditForm #razao_social, #empresaForm #razao_social").val(data.nome);
          $("#empresaEditForm #nome_fantasia, #empresaForm #nome_fantasia").val(data.fantasia);
          $("#empresaForm #data_fundacao").val(data.abertura);
          $("#cep").val(data.cep);
          $("#cidade").val(data.municipio);
          $("#logradouro").val(data.logradouro);
          $("#numero").val(data.numero);
          $("#bairro").val(data.bairro);
          $("#complemento").val(data.complemento);
          $("#telefone").val(data.telefone);
          $("#email").val(data.email);

        },
        error: function(data){
          console.log(data);
        }
      });
    }
  });
  $('#telefone').mask('(00) 00000-0000', {
    onKeyPress: function(tel, e, field, options){
      var masks = ['(00) 00000-0000', '(00) 0000-0000#'];
      mask = (tel.length>14) ? masks[0] : masks[1];
      $('#telefone').mask(mask, options);
    },
    placeholder: "(00) 00000-0000"
  });
  $('#empresaEnderecoForm #cep').mask('00.000-000', {
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

  $('#empresaForm #cep').mask('00.000-000', {
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
  $(".div-dase .form-crud, .div-end-ps, #empresaForm .form-crud").css("height", h-40);
}
