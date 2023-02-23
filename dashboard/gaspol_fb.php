<div class="icon-bar-wrap ui-draggable ui-draggable-handle" id="wrap-all">
    <div class="icon-bar" id="feature-buttons">
        <a href="https://qmera.io/chatcore/pages/login_page.php?env=2" target="_blank">
		<span data-translate="palioButton-1"><img id="qbutton-1" data-html="true" data-toggle="tooltip" style="width: 50px; height: 50px; margin-top: 10px; margin-left: 6px"
                    data-placement="right" src="assets/img/floating_button/button_cc.png?v=6"
                    alt="cc" class="paliobutton"
                    title="Broadcast messages to notify your customers about your new products, special discounts, and promotions. You can also embed a feature that allows your customers to chat with each other. All the more reason why they should spend more time in your app..."></span>
        <span data-translate="palioButton-2"><img id="qbutton-2" data-html="true" data-toggle="tooltip" style="width: 50px; height: 50px; margin-top: 10px; margin-left: 6px" data-placement="right"
                src="assets/img/floating_button/button_chat.png?v=6" alt="chat"
                class="paliobutton" title="Provide your customers with an advanced Contact Center directly from your app. Engage with your customers through Chat..."
                data-original-title="Provide your customers with an advanced Contact Center directly from your app. Engage with your customers through Chat..."></span>
        <span data-translate="palioButton-3"><img id="qbutton-3" data-html="true" data-toggle="tooltip" style="width: 50px; height: 50px; margin-top: 10px; margin-left: 6px" data-placement="right"
                src="assets/img/floating_button/button_call.png" alt="call"
                class="paliobutton" title="Provide your customers with an advanced Contact Center directly from your app. Engage with your customers through Voice Call..."
                data-original-title="Provide your customers with an advanced Contact Center directly from your app. Engage with your customers through Voice Call..."></span>
        <span data-translate="palioButton-4"><img id="qbutton-4" data-html="true" data-toggle="tooltip" style="width: 50px; height: 50px; margin-top: 10px; margin-left: 6px" data-placement="right"
                src="assets/img/floating_button/button_stream.png?v=6" alt="stream"
                class="paliobutton" title="Provide your customers with an advanced Contact Center directly from your app. Engage with your customers through Video Call..."
                data-original-title="Provide your customers with an advanced Contact Center directly from your app. Engage with your customers through Video Call..."></span>
		</a>
    </div>
    <div class="palio-button" id="palio-button-1">
        <img style="margin-top: 10px; margin-right: 15px" src="assets/img/floating_button/fb_icon_g.png?v=4" alt="palio">
    </div>
</div>

<script>
    let open = true; // floating button initial pos: open
    // var $ = window.jQuery;    

    $(function () {
        // $("#wrap-all").draggable({
        //     containment: "window",
        //     appendTo: "body"
        // });
        var $elements = [$('#feature-buttons'), $('#palio-button-1')];
        // $("#feature-buttons, #palio-button-1").draggable({
        //     containment: "window",
        //     start: function (ev, ui) {
        //         var $elem
        //         for (var i in $elements) {
        //             $elem = $elements[i];
        //             if ($elem[0] != this) {
        //                 $elem.data('dragStart', $elem.offset());
        //             }
        //         }
        //     },
        //     drag: function (ev, ui) {
        //         var xPos, $elem,
        //         deltaX = ui.position.left - ui.originalPosition.left;
        //         deltaY = ui.position.top - ui.originalPosition.top;
        //         for (var i in $elements) {
        //             $elem = $elements[i];
        //             if ($elem[0] != this) {
        //                 $elem.offset({
        //                     top: $elem.data('dragStart').top + deltaY,
        //                     left: $elem.data('dragStart').left + deltaX
        //                 });
        
        //             }
        //         }
        //     }
        // })

        $("body").tooltip({
            selector: '[data-toggle="tooltip"]',
        });

        $("#palio-button-1").click(function () {
            if (open) {
                // $("#feature-buttons").css('visibility', 'hidden');
                $("#qbutton-1").animate({opacity: 0}, 150);
                $("#qbutton-2").delay(150).animate({opacity: 0}, 150);
                $("#qbutton-3").delay(300).animate({opacity: 0}, 150);
                // $("#qbutton-4").delay(450).animate({opacity: 0}, 150);
                $("#qbutton-4").delay(450).animate({
                    opacity: 0}, 
                    {
                        duration: 150,
                        complete: function() {
                            $("#feature-buttons").css('visibility', 'hidden');
                        }
                    });
                
                    open = false;
            } else {
                
                // $("#feature-buttons").css('visibility', 'unset');
                $("#qbutton-4").animate({opacity: 1}, 150);
                $("#qbutton-3").delay(150).animate({opacity: 1}, 150);
                $("#qbutton-2").delay(300).animate({opacity: 1}, 150);
                // $("#qbutton-1").delay(450).animate({opacity: 1}, 150);
                $("#qbutton-1").delay(450).animate({
                    opacity: 1}, 
                    {
                        duration: 150,
                        complete: function() {
                            $("#feature-buttons").css('visibility', 'visible');
                        }
                    });
                open = true;
            }
        });

        document.getElementById("palio-button-1").addEventListener("touchstart", tapHandler);
        var tapedTwice = false;

        function tapHandler(event) {
            if (!tapedTwice) {
                tapedTwice = true;
                setTimeout(function () {
                    tapedTwice = false;
                }, 500);
                return false;
            }
            event.preventDefault();
            if (open) {
                // $("#feature-buttons").css('visibility', 'hidden');
                $("#qbutton-1").animate({opacity: 0}, 150);
                $("#qbutton-2").delay(150).animate({opacity: 0}, 150);
                $("#qbutton-3").delay(300).animate({opacity: 0}, 150);
                $("#qbutton-4").delay(450).animate({opacity: 0}, 150);
                open = false;
            } else {
                // $("#feature-buttons").css('visibility', 'unset');
                $("#qbutton-4").animate({opacity: 1}, 150);
                $("#qbutton-3").delay(150).animate({opacity: 1}, 150);
                $("#qbutton-2").delay(300).animate({opacity: 1}, 150);
                $("#qbutton-1").delay(450).animate({opacity: 1}, 150);
                open = true;
            }
        }

        // prevent from being hidden when resizing
        var dragDiv = $("#wrap-all");

        $(window).resize(function () {
            if ($(window).innerWidth() > $(dragDiv).width()) {
                var oLeft = parseInt($(window).innerWidth() - $(dragDiv).width());
                var posLeft = parseInt($(dragDiv).css("left"));
                if (posLeft > oLeft) {
                    $(dragDiv).css("left", oLeft);
                    toPercent();
                }
            }

            if ($(window).innerHeight() > $(dragDiv).height()) {
                var oTop = parseInt($(window).innerHeight() - $(dragDiv).height());
                var posTop = parseInt($(dragDiv).css("top"));
                if (posTop > oTop) {
                    $(dragDiv).css("top", oTop);
                    toPercent();
                }
            }
        });

        function toPercent() {
            $(dragDiv).css("left", parseInt($(dragDiv).css("left")) / (wrapper.innerWidth() / 100) + "%");
            $(dragDiv).css("top", parseInt($(dragDiv).css("top")) / (wrapper.innerHeight() / 100) + "%");
        }
    });
</script>