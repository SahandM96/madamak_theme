jQuery(document).ready(function ($) {
    if ($("#live_search_result2").length) {
        var timeout2;
        var setouttime = 500;
        $("#keyword2").on("keyup", function (e) {
            if (e.keyCode !== 32 && e.keyCode !== 13) {
                clearTimeout(timeout2);
                timeout2 = setTimeout(callTimeout, setouttime);
            }
            $("#keyword2").on("keydown", function (e) {
                if (e.keyCode !== 32 && e.keyCode !== 13) {
                    clearTimeout(timeout2);
                }
            });
        });

        function callTimeout() {
            var value = $("#keyword2").val();
            var count = value.length;
            if (count < 3) {
                $("#live_search_result2").removeClass("show");
            }
            if (count >= 3) {
                $.ajax({
                    url: srApi.url,
                    type: "get",
                    data: {
                        action: "live_func",
                        nonce: srApi.nonce,
                        keyword: value
                    },
                    beforeSend: function () {
                        $("#live_search_result2").removeClass("show");
                    },
                    success: function (data) {
                        $("#live_search_result2").addClass("show");
                        $("#result_box2").html(data);
                    },
                });
            }
        }
    }
});