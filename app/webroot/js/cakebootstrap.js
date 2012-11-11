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
    $('input[type="submit"]').addClass('btn btn-primary');
    //All index actions converted into pretty buttons
    $('td[class="actions"] > a[class!="btn"]').addClass('btn');
    
    //All (div.inputs) with default FormHelper style (div.input > label ~ input)
    //converted into Twitter Bootstrap Style (div.clearfix > label ~ div.input)
    
    $('div.input').wrap('<fieldset class="control-group" />');
    //$('div.input.required').parent().addClass('required');
    $('div.input > label').addClass('control-label');
    $('div.input').children(':not(label)').wrap('<div class="controls" />');
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
	$('.flash_warning').addClass('alert');
	$('.error-message').addClass('alert alert-error');
	//$('div.error-message').append($('div.error-message').replaceWith('<span class="help-inline">'+$('div.error-message').text()+'</span'));
	$('.form-error').addClass('error');
	$('.form-error').closest('.clearfix').addClass('error');
}

//Styling start when document loads













