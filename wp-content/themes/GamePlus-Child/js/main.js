let app = new Vue({
    el: '#app',

    data: {
        /////////// FOR INDEX ///////////
        sliderStatus: [null, 'show', 'hide', 'hide', 'hide', 'hide', 'hide', 'hide'],
        archiveCategoriesStatus: [true, false, false, false],
        /////////// FOR INDEX ///////////

        galleryPhotosStatus: [],


        customerStatus: [null, 'show', 'hide', 'hide'],

    },

    mounted() {

        /////////// FOR INDEX ///////////
        /////////// AUTO CHANGE SLIDER ///////////

        ref = this;
        let slideChangeDelay = document.getElementById('slide-change-delay');
        if (slideChangeDelay == undefined || slideChangeDelay == null || slideChangeDelay == '') {
            slideChangeDelay = 5000;
        } else {
            slideChangeDelay = Number(slideChangeDelay.textContent);
            slideChangeDelay = slideChangeDelay * 1000;
        }

        let sliderChanger = setInterval(function () {
            let currentSlide = ref.sliderStatus.indexOf('show');
            let nextSlideIndex = currentSlide + 1;
            let firstSlideIndex = 1;

            let nextSlide = document.getElementById(`slide0${nextSlideIndex}`);

            let newSliderStatus = [null, 'hide', 'hide', 'hide', 'hide', 'hide', 'hide', 'hide'];

            if (nextSlide != null && nextSlide != undefined && nextSlide != '') {
                newSliderStatus[nextSlideIndex] = 'show';
            } else {
                newSliderStatus[firstSlideIndex] = 'show';
            }

            app.$set(app.$data, 'sliderStatus', newSliderStatus);
        }, slideChangeDelay);

        /////////// AUTO CHANGE SLIDER ///////////
        /////////// FOR INDEX ///////////





        /////////// FOR PRODUCT PAGE ////////////
        let countOfPhotos = $('.gallery-photo').length;
        let changer = null;
        if (countOfPhotos > 0) {
            let arr = [];
            for (i = 0; i < countOfPhotos; i++) {
                arr[i] = false;
            }
            arr[0] = true;
            changer = arr;
        } else {
            changer = false;
        }

        this.galleryPhotosStatus = changer;
        /////////// FOR PRODUCT PAGE ////////////


    },


    methods: {


        /////////// FOR INDEX ///////////
        changeSlide(slideIndex) {
            let newSliderStatus = [null, 'hide', 'hide', 'hide', 'hide', 'hide', 'hide', 'hide'];
            newSliderStatus[slideIndex] = 'show';

            app.$set(app.$data, 'sliderStatus', newSliderStatus);
        },


        changeArchiveCategory(categoryIndex) {
            let newArchiveCategoriesStatus = [false, false, false, false];
            newArchiveCategoriesStatus[categoryIndex] = true;

            app.$set(app.$data, 'archiveCategoriesStatus', newArchiveCategoriesStatus);
        },
        /////////// FOR INDEX ///////////




        /////////// FOR PRODUCT PAGE ////////////
        changeGalleryPhoto(photoIndex) {
            let countOfPhotos = $('.gallery-photo').length;
            let arr = [];
            for (i = 0; i < countOfPhotos; i++) {
                arr[i] = false;
            }
            arr[photoIndex] = true;
            this.galleryPhotosStatus = arr;
        },

        galleryNav(method) {
            if (method == 'next') {
                var nextPhotoId = this.galleryPhotosStatus.indexOf(true) + 1;
            } else {
                var nextPhotoId = this.galleryPhotosStatus.indexOf(true) - 1;
            }

            nextPhoto = this.galleryPhotosStatus[nextPhotoId];
            if (nextPhoto == false) {
                this.changeGalleryPhoto(nextPhotoId);
            }
        },
        /////////// FOR PRODUCT PAGE ////////////





        changeCustomer(slideIndex) {
            let newCustomerStatus = [null, 'hide', 'hide', 'hide'];
            newCustomerStatus[slideIndex] = 'show';

            app.$set(app.$data, 'customerStatus', newCustomerStatus);
        },


        customerNav(method) {
            if (method == 'next') {
                var nextCustomerId = this.customerStatus.indexOf('show') + 1;
            } else {
                var nextCustomerId = this.customerStatus.indexOf('show') - 1;
            }

            nextCustomer = this.customerStatus[nextCustomerId];
            if (nextCustomer == 'hide') {
                this.changeCustomer(nextCustomerId);
            }
        },






        openNav() {
            $('#sidenav').toggleClass('show');
            $('#black-screen').fadeToggle();
        },


        showSearchPanel: function () {
            $('#fixed-search').css('display', 'flex');
        },

        closeSearchPanel: function () {
            $('#fixed-search').hide();
		},
		


		goToUp() {
			document.querySelector("#app").scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
    }
});