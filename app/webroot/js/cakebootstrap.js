/*
* Twitter Bootstrappifier for CakePHP 
*
* Author: Mutlu Tevfik Kocak
*
* CakePHP Twitter Bootstrappifier
*
* Selects all con twitter Bootstrap incompatible form and buttons,
* and converts them into pretty Twitter Bootstrap style.
*
*/

function cakebootstrap()
{
    //All submit forms converted to primary button
    $('.submit > input[type="submit"]').addClass('btn btn-primary');
    $('.form-horizontal .submit').addClass('form-group');
    $('.form-horizontal .submit > input[type="submit"]').wrap('<div class="col-sm-10 col-sm-offset-2" />');
    //All index actions converted into pretty buttons
    $('td[class="actions"] > a[class!="btn"]').addClass('btn');
    
    //All (div.inputs) with default FormHelper style (div.input > label ~ input)
    //converted into Twitter Bootstrap Style (div.clearfix > label ~ div.input)
    
    $('div.input').wrap('<fieldset class="form-group" />');
    $('.form-horizontal div.input > label').addClass('control-label col-sm-2');
    $('div.input > input, div.input > select, div.input > textarea').addClass('form-control');
    $('.form-horizontal div.input').children(':not(label)').wrap('<div class="col-sm-10" />');
    $('div.input').replaceWith(function() {
      return $(this).contents();
    });
}

//Default CakePHP Error inputs are converted to twitter bootstrap style
function errorstrap()
{
	$('.message').addClass('alert alert-info');
	$('.flash_success').addClass('alert alert-success');
	$('#flashMessage.success').addClass('alert alert-success');
	$('.flash_warning').addClass('alert alert-warning');
	$('.error-message').addClass('alert alert-error');
	//$('div.error-message').append($('div.error-message').replaceWith('<span class="help-inline">'+$('div.error-message').text()+'</span'));
	$('.form-error').addClass('error');
	$('.form-error').closest('.clearfix').addClass('error');
}

//Styling start when document loads













