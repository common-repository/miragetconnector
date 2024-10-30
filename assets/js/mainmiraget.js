( function($){ 

    $(document).ready(() => {
        
        $("body").on("click", "#tucson-field-submit", function (event) {  
            
             let emailInput = $( "body" ).find('#tucson-field-email') ;
            //alert('Yes we found this email : ' + emailInput[0].value )
            $.ajax({
                method: "POST",
                url: 'wp-admin/admin-ajax.php',
                data: {
                    'action': 'mboim_ajax_add_text',
                    'text': emailInput[0].value 
                }
            })
            .done( function( data ) {
                
            }); 
        })  
    })

})(jQuery);
 
 