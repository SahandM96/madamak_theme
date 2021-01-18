$(document).ready(function () {

    $('.more-post-owl').owlCarousel({
        autoplay: true,
        autoplaySpeed: 300,
        loop: true,
        navSpeed: 300,
        items: 4,
        margin: 10,
        center: true,
        autoWidth: true,
    });

    $(".owl-comments").owlCarousel({
        autoplay: false,
        loop: false,
        navSpeed: 300,
        items: 4,
        margin: 10,
        center: true,
        autoWidth: false,
    });

    $('.owl-carousel-four').owlCarousel({
        autoplay: true,
        autoplaySpeed: 300,
        loop: true,
        navSpeed: 300,
        items: 4,
        margin: 20,
        center: true,
    });

    $('.center-owl-carousel').owlCarousel({
        autoplay: true,
        autoplaySpeed: 300,
        loop: true,
        navSpeed: 300,
        items: 3,
        margin: 2,
        center: true,
    });

    $(".owl-carousel").owlCarousel({
        loop: true,
        nav: false,
        dots: false,
        autoWidth: true,
        rtl: true,
        center: false,
        margin: 10,
        smartSpeed: 2000,
        items: 4
    });




    setTimeout(function () {
        $("#loading-screen").fadeOut();
    }, 2000);



	$(window).scroll(function (event) {
		var scroll = $(window).scrollTop();
		if (scroll > 1000) {
			$('.goToUp').addClass('show');
		} else {
			$('.goToUp').removeClass('show');
		}
	});



    if (document.getElementById('min-price').textContent != 'posted') {
        var minPrice = Number(document.getElementById('min-price').textContent);
        localStorage['minPrice'] = '';
        localStorage['minPrice'] = minPrice;
    } else {
        var minPrice = Number(localStorage['minPrice']);
    }

    if (document.getElementById('max-price').textContent != 'posted') {
        var maxPrice = Number(document.getElementById('max-price').textContent);
        localStorage['maxPrice'] = '';
        localStorage['maxPrice'] = maxPrice;
    } else {
        var maxPrice = Number(localStorage['maxPrice']);
    }

    let selectedMaxPrice = Number(document.getElementById('selected-max-price').textContent);
    let selectedMinPrice = Number(document.getElementById('selected-min-price').textContent);

    $(function () {
        $("#slider-range").slider({
            isRTL: true,
            animate: true,
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [selectedMinPrice, selectedMaxPrice],
            slide: function (event, ui) {
                $("#minInput").attr('value', ui.values[0]);
                $("#maxInput").attr('value', ui.values[1]);
                $('#price-log').html(`قیمت: از <span>${ui.values[0]}</span> تا <span>${ui.values[1]}</span>`);
            }
        });
        let sliderMinVal = $("#slider-range").slider("values", 0);
        let sliderMaxVal = $("#slider-range").slider("values", 1);
        $("#minInput").attr('value', sliderMinVal);
        $("#maxInput").attr('value', sliderMaxVal);
        $('#price-log').html(`قیمت: از <span>${sliderMinVal}</span> تا <span>${sliderMaxVal}</span>`);
    });

    var someThingChecked = false;

    $("#category-ul").find("li input").each(function () {
        if ($(this).prop('checked') == true) {
            someThingChecked = true;
        }
    });
    if (!someThingChecked) {
        $("#category-ul li:nth-child(3) input").prop("checked", true);
	}

});


function openFilter(item, filterName) {
    let filterClass = `.${filterName}-filter`;
    $(filterClass).slideToggle();
    $(item).toggleClass('opened');
}

function openAllFilter(item) {
    $('.filters').slideToggle();
    $(item).toggleClass('opened');
    $('.filtersButton').toggleClass('opened');
    $('#applyFilters').toggleClass('show');
}


$("label[for='wpas_department']").text('دپارتمان');
$(".wpas-ticket-details-header thead tr th:last-child").text('دپارتمان');

$('.woocommerce-info').has('.showcoupon').hide();