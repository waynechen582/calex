

/* ===================
Table Of Content
======================
01 PRELOADER
02 MEGA MENU
03 STICKY HEADER
04 SWIPER SLIDER
05 PARALLAX BACKGROUND
06 AOS ANIMATION
07 STICKY BAR
08 FORM VALIDATION
09 TOOLTIP
10 POPOVER
11 BACK TO TOP
12 GLIGHTBOX
13 TYPING TEXT ANIMATION
14 ISOTOPE
15 WAVE
====================== */

"use strict";
!function () {

    window.Element.prototype.removeClass = function () {
        let className = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
            selectors = this;
        if (!(selectors instanceof HTMLElement) && selectors !== null) {
            selectors = document.querySelector(selectors);
        }
        if (this.isVariableDefined(selectors) && className) {
            selectors.classList.remove(className);
        }
        return this;
    }, window.Element.prototype.addClass = function () {
        let className = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
            selectors = this;
        if (!(selectors instanceof HTMLElement) && selectors !== null) {
            selectors = document.querySelector(selectors);
        }
        if (this.isVariableDefined(selectors) && className) {
            selectors.classList.add(className);
        }
        return this;
    }, window.Element.prototype.toggleClass = function () {
        let className = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
            selectors = this;
        if (!(selectors instanceof HTMLElement) && selectors !== null) {
            selectors = document.querySelector(selectors);
        }
        if (this.isVariableDefined(selectors) && className) {
            selectors.classList.toggle(className);
        }
        return this;
    }, window.Element.prototype.isVariableDefined = function () {
        return !!this && typeof (this) != 'undefined' && this != null;
    }
}();


var e = {
    init: function () {
        e.preLoader(),
        e.megaMenu(),
        e.stickyHeader(),
        e.swiperSlider(),
        e.parallaxBG(),
        e.aosFunc(),
        e.stickyBar(),
        e.formValidation(),
        e.toolTipFunc(),
        e.popOverFunc(),
        e.backTotop(),
        e.lightBox(),
        e.typeText(),
        e.enableIsotope(),
        e.waveCanvas(),
        e.owlCarousel()
    },
    isVariableDefined: function (el) {
        return typeof !!el && (el) != 'undefined' && el != null;
    },
    getParents: function (el, selector, filter) {
        const result = [];
        const matchesSelector = el.matches || el.webkitMatchesSelector || el.mozMatchesSelector || el.msMatchesSelector;

        // match start from parent
        el = el.parentElement;
        while (el && !matchesSelector.call(el, selector)) {
            if (!filter) {
                if (selector) {
                    if (matchesSelector.call(el, selector)) {
                        return result.push(el);
                    }
                } else {
                    result.push(el);
                }
            } else {
                if (matchesSelector.call(el, filter)) {
                    result.push(el);
                }
            }
            el = el.parentElement;
            if (e.isVariableDefined(el)) {
                if (matchesSelector.call(el, selector)) {
                    return el;
                }
            }

        }
        return result;
    },
    getNextSiblings: function (el, selector, filter) {
        let sibs = [];
        let nextElem = el.parentNode.firstChild;
        const matchesSelector = el.matches || el.webkitMatchesSelector || el.mozMatchesSelector || el.msMatchesSelector;
        do {
            if (nextElem.nodeType === 3) continue; // ignore text nodes
            if (nextElem === el) continue; // ignore elem of target
            if (nextElem === el.nextElementSibling) {
                if ((!filter || filter(el))) {
                    if (selector) {
                        if (matchesSelector.call(nextElem, selector)) {
                            return nextElem;
                        }
                    } else {
                        sibs.push(nextElem);
                    }
                    el = nextElem;

                }
            }
        } while (nextElem = nextElem.nextSibling)
        return sibs;
    },
    on: function (selectors, type, listener) {
        document.addEventListener("DOMContentLoaded", () => {
            if (!(selectors instanceof HTMLElement) && selectors !== null) {
                selectors = document.querySelector(selectors);
            }
            selectors.addEventListener(type, listener);
        });
    },
    onAll: function (selectors, type, listener) {
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(selectors).forEach((element) => {
                if (type.indexOf(',') > -1) {
                    let types = type.split(',');
                    types.forEach((type) => {
                        element.addEventListener(type, listener);
                    });
                } else {
                    element.addEventListener(type, listener);
                }


            });
        });
    },
    removeClass: function (selectors, className) {
        if (!(selectors instanceof HTMLElement) && selectors !== null) {
            selectors = document.querySelector(selectors);
        }
        if (e.isVariableDefined(selectors)) {
            selectors.removeClass(className);
        }
    },
    removeAllClass: function (selectors, className) {
        if (e.isVariableDefined(selectors) && (selectors instanceof HTMLElement)) {
            document.querySelectorAll(selectors).forEach((element) => {
                element.removeClass(className);
            });
        }

    },
    toggleClass: function (selectors, className) {
        if (!(selectors instanceof HTMLElement) && selectors !== null) {
            selectors = document.querySelector(selectors);
        }
        if (e.isVariableDefined(selectors)) {
            selectors.toggleClass(className);
        }
    },
    toggleAllClass: function (selectors, className) {
        if (e.isVariableDefined(selectors)  && (selectors instanceof HTMLElement)) {
            document.querySelectorAll(selectors).forEach((element) => {
                element.toggleClass(className);
            });
        }
    },
    addClass: function (selectors, className) {
        if (!(selectors instanceof HTMLElement) && selectors !== null) {
            selectors = document.querySelector(selectors);
        }
        if (e.isVariableDefined(selectors)) {
            selectors.addClass(className);
        }
    },
    select: function (selectors) {
        return document.querySelector(selectors);
    },
    selectAll: function (selectors) {
        return document.querySelectorAll(selectors);
    },

    // START: 01 Preloader
    preLoader: function () {
        window.onload = function () {
            var preloader = e.select('.preloader');
            if (e.isVariableDefined(preloader)) {
                preloader.className += ' animate__animated animate__fadeOut';
                setTimeout(function(){
                    preloader.style.display = 'none';
                }, 200);
            }
        };
    },
    // END: Preloader

    // START: 02 Mega Menu
    megaMenu: function () {
        e.onAll('.dropdown-menu a.dropdown-item.dropdown-toggle', 'click', function (event) {
            var element = this;
            event.preventDefault();
            event.stopImmediatePropagation();
            if (e.isVariableDefined(element.nextElementSibling) && !element.nextElementSibling.classList.contains("show")) {
                const parents = e.getParents(element, '.dropdown-menu');
                e.removeClass(parents.querySelector('.show'), "show");
                if(e.isVariableDefined(parents.querySelector('.dropdown-opened'))){
                    e.removeClass(parents.querySelector('.dropdown-opened'), "dropdown-opened");
                }
            }
            var $subMenu = e.getNextSiblings(element, ".dropdown-menu");
            e.toggleClass($subMenu, "show");
            $subMenu.previousElementSibling.toggleClass('dropdown-opened');
            var parents = e.getParents(element, 'li.nav-item.dropdown.show');
            if (e.isVariableDefined(parents) && parents.length > 0) {
                e.on(parents, 'hidden.bs.dropdown', function (event) {
                    e.removeAllClass('.dropdown-submenu .show');
                });
            }
        });
    },
    // END: Mega Menu

    // START: 03 Sticky Header
    stickyHeader: function () {
        var stickyNav = e.select('.navbar-sticky');
        if (e.isVariableDefined(stickyNav)) {
            var stickyHeight = stickyNav.offsetHeight;
            document.addEventListener('scroll', function (event) {
                var scTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scTop >= 400) {
                    stickyNav.addClass('navbar-sticky-on');
                } else {
                    stickyNav.removeClass("navbar-sticky-on");
                }
            });
        }
    },
    // END: Sticky Header

    // START: 04 Swiper Slider
    swiperSlider: function () {
        if ($(".swiper-slider-fade").length !== 0) {
            var swiper = new Swiper(".swiper-container", {
                effect: "fade", //other supported effects: coverflow, flip, cube, slide
                pagination: null,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                nextButton: ".swiper-button-next",
                prevButton: ".swiper-button-prev",
                autoplay: 5000,
                speed: 1000,
                spaceBetween: 0,
                loop: true,
                simulateTouch: true,
                onSlideChangeEnd: function (swiper) {
                    $(".swiper-slide").each(function () {
                        if ($(this).index() === swiper.activeIndex) {
                            // Fadein in active slide
                            $(this).find(".slider-content").fadeIn(25);
                        } else {
                            // Fadeout in inactive slides
                            $(this).find(".slider-content").fadeOut(25);
                        }
                    });
                },
            });
        }
    },
    // END: Swiper Slider

    // START: 05 Parallax Background
    parallaxBG: function () {
        var parBG = e.select('.bg-parallax');
        if (e.isVariableDefined(parBG)) {
            jarallax(e.selectAll('.bg-parallax'), {
                speed: 0.6
            });
        }
    },
    // END: Parallax Background

    // START: 06 AOS Animation
    aosFunc: function () {
        var aos = e.select('.aos');
        if (e.isVariableDefined(aos)) {
            AOS.init({
                duration: 500,
                easing: 'ease-out-quart',
                once: true
            });
        }
    },
    // END: AOS Animation

    // START: 07 Sticky Bar
    stickyBar: function () {
        var stickyBar = e.select('[data-sticky]');
        if (e.isVariableDefined(stickyBar)) {
            var sticky = new Sticky('[data-sticky]');
        }
    },
    // END: Sticky Bar

    // START: 08 Form Validation
    formValidation: function () {
        var formV = e.select('.needs-validation');
        if (e.isVariableDefined(formV)) {
            window.addEventListener('load', function() {
              // Fetch all the forms we want to apply custom Bootstrap validation styles to
              var forms = document.querySelectorAll('.needs-validation')

              // Loop over them and prevent submission
              Array.prototype.slice.call(forms)
                .forEach(function (form) {
                  form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                      event.preventDefault()
                      event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                  }, false)
                })
            }, false);
        }
    },
    // END: Form Validation

    // START: 09 Tooltip
    // Enable tooltips everywhere via data-toggle attribute
    toolTipFunc: function () {
        var tooltipTriggerList = [].slice.call(e.selectAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    },
    // END: Tooltip

    // START: 10 Popover
    // Enable popover everywhere via data-toggle attribute
    popOverFunc: function () {
        var popoverTriggerList = [].slice.call(e.selectAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
          return new bootstrap.Popover(popoverTriggerEl)
        })
    },
    // END: Popover

    // START: 11 Back to Top
    backTotop: function () {
        var scrollpos = window.scrollY;
        var backBtn = e.select('.back-top');
        if (e.isVariableDefined(backBtn)) {
            var add_class_on_scroll = () => backBtn.addClass("btn-show");
            var remove_class_on_scroll = () => backBtn.removeClass("btn-show");

            window.addEventListener('scroll', function () {
                scrollpos = window.scrollY;
                if (scrollpos >= 500) {
                    add_class_on_scroll()
                } else {
                    remove_class_on_scroll()
                }
            });

            backBtn.addEventListener('click', () => window.scrollTo({
                top: 0,
                behavior: 'smooth',
            }));
        }
    },
    // END: Back to Top

    // START: 12 GLightbox
    lightBox: function () {
        var light = e.select('[data-glightbox]');
        if (e.isVariableDefined(light)) {
            var lb = GLightbox({
                selector: '*[data-glightbox]',
                openEffect: 'fade',
                closeEffect: 'fade'
            });
        }
    },
    // END: GLightbox

    // START: 13 Typing Text Animation
    typeText: function () {
        var t = e.select('.typed');
        if (e.isVariableDefined(t)) {
            var type = e.selectAll('.typed');
            type.forEach(el => {
                var strings = el.getAttribute('data-type-text');
                var split_strings = strings.split("&&");
                var typespeed = el.getAttribute('data-speed') ? el.getAttribute('data-speed') : 200;
                var typeBackSpeed = el.getAttribute('data-back-speed') ? el.getAttribute('data-back-speed') : 50;

                ityped.init(el, {
                    strings: split_strings,
                    showCursor: true,
                    typeSpeed: typespeed,
                    backSpeed: typeBackSpeed
                });
            });
        }
    },
    // END: Typing Text Animation

    // START: 14 Isotope
    enableIsotope: function () {
        var isGridItem = e.select('.grid-item');
        if (e.isVariableDefined(isGridItem)) {

            // Code only for normal Grid
            var onlyGrid = e.select('[data-isotope]');
            if (e.isVariableDefined(onlyGrid)) {
                var allGrid = e.selectAll("[data-isotope]");
                allGrid.forEach(gridItem => {
                    var gridItemData = gridItem.getAttribute('data-isotope');
                    var gridItemDataObj = JSON.parse(gridItemData);
                    var iso = new Isotope(gridItem, {
                        itemSelector: '.grid-item',
                        layoutMode: gridItemDataObj.layoutMode
                    });

                    imagesLoaded(gridItem).on('progress', function () {
                        // layout Isotope after each image loads
                        iso.layout();
                    });
                });
            }

            // Code only for normal Grid
            var onlyGridFilter = e.select('.grid-menu');
            if (e.isVariableDefined(onlyGridFilter)) {
                var filterMenu = e.selectAll('.grid-menu');
                filterMenu.forEach(menu => {
                    var filterContainer = menu.getAttribute('data-target');
                    var a = menu.dataset.target;
                    var b = e.select(a);
                    var filterContainerItemData = b.getAttribute('data-isotope');
                    var filterContainerItemDataObj = JSON.parse(filterContainerItemData);
                    var filter = new Isotope(filterContainer, {
                        itemSelector: '.grid-item',
                        transitionDuration: '0.7s',
                        layoutMode: filterContainerItemDataObj.layoutMode
                    });

                    var menuItems = menu.querySelectorAll('li a');
                    menuItems.forEach(menuItem => {
                        menuItem.addEventListener('click', function (event) {
                            var filterValue = menuItem.getAttribute('data-filter');
                            filter.arrange({filter: filterValue});
                            menuItems.forEach((control) => control.removeClass('active'));
                            menuItem.addClass('active');
                        });
                    });

                    imagesLoaded(filterContainer).on('progress', function () {
                        filter.layout();
                    });
                });
            }
        }
    },
    // END: Isotope

    // START: 15 wave
    waveCanvas: function () {
    	var canvas = document.getElementById('waveCanvas')
        if (e.isVariableDefined(canvas)) {
            var ctx = canvas.getContext('2d')
            canvas.width = canvas.parentNode.offsetWidth
            canvas.height = canvas.parentNode.offsetHeight
            
            let step = 0
            const lines = 4
        
            function loop() {
                ctx.clearRect(0, 0, canvas.width, canvas.height)
                step++
                for (let i = 0; i < lines; i++) {
                    ctx.fillStyle = 'rgba(255,255,255,.8)'
                    var angle = (step + i * 180 / lines) * Math.PI / 180
                    var deltaHeight = Math.sin(angle) * 90
                    var deltaHeightRight = Math.cos(angle) * 50
                    ctx.beginPath()
                    ctx.moveTo(0, canvas.height / 2 + deltaHeight)
                    ctx.bezierCurveTo(canvas.width / 2, canvas.height / 2 + deltaHeight - 50, canvas.width / 2, canvas.height / 2 + deltaHeightRight - 50, canvas.width, canvas.height / 2 + deltaHeightRight)
                    ctx.lineTo(canvas.width, canvas.height)
                    ctx.lineTo(0, canvas.height)
                    ctx.lineTo(0, canvas.height / 2 + deltaHeight)
                    ctx.closePath()
                    ctx.fill()
                }
        
                requestAnimationFrame(loop)
            }
		    loop()
        }
    },
    // END: wave

    // BEGIN: 05 Owl Carousel
    owlCarousel: function () {
        var carousel = $(".owl-carousel");
        carousel.owlCarousel({
            loop:true,
            margin: 50,
            autoplay: true,
            autoplayTimeout: 3500,
            nav: false,
            navText: [
                '<i class="ti-angle-left"></i>',
                '<i class="ti-angle-right"></i>',
            ],
            dots: true, // 顯示點點
            responsive: {
                0: {
                    items: 1, // 螢幕大小為 0~480 顯示 1 個項目
                },
                480: {
                    items: 2, // 螢幕大小為 480~768 顯示 1 個項目
                },
                768: {
                    items: 4, // 螢幕大小為 768~ 顯示 1 個項目
                },
            },
        });
    },
    // END: Owl Carousel

};
e.init();
