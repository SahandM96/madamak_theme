function openFilter(e,t){$(`.${t}-filter`).slideToggle(),$(e).toggleClass("opened")}function openAllFilter(e){$(".filters").slideToggle(),$(e).toggleClass("opened"),$(".filtersButton").toggleClass("opened"),$("#applyFilters").toggleClass("show")}$(document).ready(function(){if($(".more-post-owl").owlCarousel({autoplay:!0,autoplaySpeed:300,loop:!0,navSpeed:300,items:4,margin:10,center:!0,autoWidth:!0}),$(".owl-comments").owlCarousel({autoplay:!1,loop:!1,navSpeed:300,items:4,margin:10,center:!0,autoWidth:!1}),$(".owl-carousel-four").owlCarousel({autoplay:!0,autoplaySpeed:300,loop:!0,navSpeed:300,items:4,margin:20,center:!0}),$(".center-owl-carousel").owlCarousel({autoplay:!0,autoplaySpeed:300,loop:!0,navSpeed:300,items:3,margin:2,center:!0}),$(".owl-carousel").owlCarousel({loop:!0,nav:!1,dots:!1,autoWidth:!0,rtl:!0,center:!1,margin:10,smartSpeed:2e3,items:4}),setTimeout(function(){$("#loading-screen").fadeOut()},2e3),$(window).scroll(function(e){$(window).scrollTop()>1e3?$(".goToUp").addClass("show"):$(".goToUp").removeClass("show")}),"posted"!=document.getElementById("min-price").textContent){var e=Number(document.getElementById("min-price").textContent);localStorage.minPrice="",localStorage.minPrice=e}else e=Number(localStorage.minPrice);if("posted"!=document.getElementById("max-price").textContent){var t=Number(document.getElementById("max-price").textContent);localStorage.maxPrice="",localStorage.maxPrice=t}else t=Number(localStorage.maxPrice);let o=Number(document.getElementById("selected-max-price").textContent),l=Number(document.getElementById("selected-min-price").textContent);$(function(){$("#slider-range").slider({isRTL:!0,animate:!0,range:!0,min:e,max:t,values:[l,o],slide:function(e,t){$("#minInput").attr("value",t.values[0]),$("#maxInput").attr("value",t.values[1]),$("#price-log").html(`قیمت: از <span>${t.values[0]}</span> تا <span>${t.values[1]}</span>`)}});let a=$("#slider-range").slider("values",0),n=$("#slider-range").slider("values",1);$("#minInput").attr("value",a),$("#maxInput").attr("value",n),$("#price-log").html(`قیمت: از <span>${a}</span> تا <span>${n}</span>`)});var a=!1;$("#category-ul").find("li input").each(function(){1==$(this).prop("checked")&&(a=!0)}),a||$("#category-ul li:nth-child(3) input").prop("checked",!0)}),$("label[for='wpas_department']").text("دپارتمان"),$(".wpas-ticket-details-header thead tr th:last-child").text("دپارتمان"),$(".woocommerce-info").has(".showcoupon").hide();