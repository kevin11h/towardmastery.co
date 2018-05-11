$('body').on('submit', '#mc-embedded-subscribe-form', function(){
    $('#modal-email-subscription').modal('hide');

    var header_text, content_text;
    if(language == 'fr'){
        header_text = 'Succès';
        content_text = 'Votre inscription est confirmée.';
    } else {
        header_text = 'Success';
        content_text = 'Your subscription is confirmed.';
    }

    $('body').append('<div class="alert alert-success" role="alert"><h4 class="alert-heading">'+ header_text +' <i class="fas fa-check"></i></h4><hr><p class="mb-0">'+ content_text +'</p></div>');
    setTimeout(function(){
        $('.alert').fadeOut('slow', function(){
            $('.alert').remove();
        });
    }, 5000);
});
