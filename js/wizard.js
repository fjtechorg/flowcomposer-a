$("#wizard").steps();
            $("#wizard_form").steps({
                bodyTag: "fieldset",
				enableCancelButton: false,
				enableFinishButton:false,
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Always allow going backward even if the current step contains invalid fields!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }

                    var form = $(this);

                    // Clean up if user went backward before
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $(".body:eq(" + newIndex + ") label.error", form).remove();
                        $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                    }

                    // Disable validation on fields that are disabled or hidden.
                    form.validate().settings.ignore = ":disabled,:hidden";

                    // Start validation; Prevent going forward if false
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Suppress (skip) "Warning" step if the user is old enough.
                    if (currentIndex < 1 )
                    {
                        $("a[href='#next']").show();
                        $("a[href='#next']").html("Next");
                        $("a[href='#next']").attr('href',"#next");
                        $("a[href='#next']").removeAttr('onclick');

                    }

                    if (currentIndex === 1 )
                    {
                        $("a[href='#next']").hide();
                        $(this).steps("next");
                        $("a[href='#next']").html("Finish");
                        $("a[href='#next']").closest('li').show();
                        $("a[href='#next']").attr('onclick',"location.href='index.php';");

                    }


                    // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 1 && priorIndex === 2)
                    {
                        $(this).steps("previous");

                    }
                },
                onFinishing: function (event, currentIndex)
                {
                },
                onFinished: function (event, currentIndex)
                {
                    $("#wizard_form").steps({enableFinishButton:true});
                }
            }).validate({
                        errorPlacement: function (error, element)
                        {
                            element.before(error);
                        },
                        rules: {
                            confirm: {
                                equalTo: "#password"
                            }
                        }
                    });
/*
$(document).on('click', '.delete_bot', function () {
     var ThisBotId = $('#bot_id').val();
	 jQuery('#SelectedBot').val(ThisBotId);
	 jQuery('#delete_bot').modal();
});

$(document).on('click', '.delete_selected_bot', function () {
     var ThisBotId = jQuery('#SelectedBot').val();
	 var ajax_url='includes/admin-ajax.php';
	 var data = {'action': 'delete_bot',
				'bot_id': ThisBotId  		   	 
							  };					  
	jQuery.post(ajax_url, data, function(response) {	
			window.location.href = "index.php";
		});							  				  
	 jQuery('#delete_bot').modal('toggle');
});
*/
$(document).ready(function(){
jQuery('a[href="#cancel"]').addClass('delete_bot');
});