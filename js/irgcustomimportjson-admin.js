(function ($) {
    $(document).ready(function () {
		/* Checking valid URL for the importer */
        function ValidURL(str) {
            var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
            if(!regex .test(str)) {
                $("#jsonimporterr").show().empty().append("Please enter valid URL.");
                return false;
            } else {
                return true;
            }
        }

        $("#parsejsonbutton").click(function () {
            $("#jsonimportsucc").hide();
            $("#jsonimporterr").hide();
            $("#jsonimportload").hide();
            var jsonurl = $("#jsonurl").val();

            if (jsonurl === "") {
                $("#jsonimporterr").show().empty().append("Please fill up the URL!");
            } else {

                console.log(ValidURL(jsonurl));

                if (ValidURL(jsonurl)) {
                    //Activate loader gif
                    $("#jsonimportload").show();
                    // This does the ajax request
                    $.ajax({
                        url: ajaxurl,
                        data: {
                            'action': 'irgCustomImportJson_parse_json_action',
                            'jsonurl': jsonurl
                        },
                        success: function (data) {
                            // This outputs the result of the ajax request
                            console.log(data);
                            $("#jsonimportload").hide();
                            $("#jsonimportsucc").show();


                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                            $("#jsonimporterr").show().empty().append(errorThrown);
                            ;
                        }
                    });
                }

            }

        });

    });
})(jQuery);