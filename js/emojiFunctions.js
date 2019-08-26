$(document).ready(function(){

    $(document).on('mouseover', '.emoji-picker-icon', function () {
        elem = $(this).parent().find('.emoji-wysiwyg-editor');
        if (!elem.is(":focus")) {

            elem.focus();
            setEndOfContenteditable(elem.get(0));
        }

    });


        window.emojiPicker = new EmojiPicker({

            emojiable_selector: '[data-emojiable=true]',

            assetsPath: 'img/',

            popupButtonClasses: 'icon-smile'

        });

        window.emojiPicker.discover();
});

function convertEmojiUtfToImage(input) {

   let tags = extractPersonalizationTags(input);
   let toReplace = [];
   let replaced = [];
console.log(tags);
   if (typeof tags !== "undefined" && tags) {
       for (let i = 0; i < tags.length; i++) {
           toReplace.push(tags[i]);
           replaced.push(convertPersonalizationTagToSpan(tags[i]));
       }

       input = replaceBulk(input, toReplace, replaced);
   }

    if(!input)

        return "";



    if(!Config.rx_codes)

        Config.init_unified();



    return input.replace(Config.rx_codes, function(m)

    {

        var val = Config.reversemap[m];


        if (val) {

            val = ":" + val + ":";



            var $img = $.emojiarea.createIcon($.emojiarea.icons[val]);


            return $img;

        }

        else

            return "";

    });



}


