(function ($) {
    $(document).ready(function () {
        function irg_show_products(url, perpage, page) {
            if (page === 0) {
                page = 1;
            }
            $("#ProductList").empty();
            if (page === 1) {
                $("#product-prev").hide();
            } else {
                $("#product-prev").show();
            }

            $.ajax({
                url: url + '?per_page=' + perpage + '&page=' + page,
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    data.forEach(function (entry) {
                        var html = "";
                        html += '<div class="product" itemscope itemtype="http://schema.org/Product">';
                        html += '<div class="product-image"><a href="' + entry['link'] + '"><img itemprop="image" src="' + entry['better_featured_image'] + '" /></a></div>';
                        html += '<h2 class="product-title"><a href="' + entry['link'] + '" itemprop="name">' + entry['title']['rendered'] + '</a></h2>';
                        html += '<p class="product-price"><span itemprop="priceCurrency">' + entry['currency'][0] + '</span> <span\n' +
                            '          itemprop="price" content="' + entry['price'][0] + '">' + entry['price'][0] + '</span></p>';
                        html += '</div>';
                        $("#ProductList").append(html);

                    });
                }
            });
        }

        irg_show_products(urltoparse, 12, pagenr);

        $("#product-prev").click(function () {
            pagenr = pagenr - 1;
            if (pagenr === 0) {
                pagenr = 1;
            }
            irg_show_products(urltoparse, 12, pagenr);
            $("html, body").animate({scrollTop: 0}, "slow");
        });

        $("#product-next").click(function () {
            pagenr = pagenr + 1;
            irg_show_products(urltoparse, 12, pagenr);
            $("html, body").animate({scrollTop: 0}, "slow");
        });

    });

})(jQuery);