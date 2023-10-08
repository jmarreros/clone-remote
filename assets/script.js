(function( $ ) {
    'use strict';

    $('#clone-remote-content').click(function(e){
        e.preventDefault();

        $.ajax({
            url : dcms_clone_remote.ajaxurl,
            type: 'post',
            data: {
                action : 'dcms_ajax_remote_content',
                id_post: 123
            },
            beforeSend: function(){
                $('#clone-remote-message').html('Enviando ...');
            },
            success: function(res){
                $('#clone-remote-message').html('Finalizado');
                console.log(res)
            }

        });
    })

})( jQuery );