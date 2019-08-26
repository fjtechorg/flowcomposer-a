 var oldFunc = window.send_to_editor;
                    window.send_to_editor = function(html) {

                    imgurl = jQuery('img', html).attr('src');
                    jQuery("#"+formfieldID).val(imgurl);
                     tb_remove();
                    window.send_to_editor = oldFunc;
                    }