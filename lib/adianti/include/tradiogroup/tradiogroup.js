function tradiogroup_enable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').removeAttr('disabled') },1);    
}

function tradiogroup_disable_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').attr('disabled', '') },1);    
}

function tradiogroup_clear_field(form_name, field) {
    setTimeout(function(){ $('form[name='+form_name+'] [name='+field+']').attr('checked', false) },1);    
}