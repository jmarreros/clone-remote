(function( $ ) {
    'use strict';

    $('#clone-remote-content').click(function(e){
        e.preventDefault();

        $.ajax({
            url : dcms_clone_remote.ajaxurl,
            type: 'post',
            data: {
                action : 'dcms_ajax_remote_content',
                nonce: dcms_clone_remote.nonce,
                post_id: $('#current-id').val()
            },
            beforeSend: function(){
                $('#clone-remote-message').html('Enviando ...');
            },
            success: function(res){
                $('#clone-remote-message').html(res.message);
            }

        });
    })

})( jQuery );