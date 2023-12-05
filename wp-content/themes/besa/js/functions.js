'use strict';

class StickyHeader {
  constructor() {
    let _this = this;

    this.$tbayHeader = $('.tbay_header-template');
    this.$tbayHeaderMain = $('.tbay_header-template .header-main');

    if (this.$tbayHeader.hasClass('main-sticky-header') && this.$tbayHeaderMain.length > 0) {
      this._initStickyHeader();
    }

    $('.search-min-wrapper .btn-search-min').click(this._onClickSeachMin);
    $('.tbay-search-form .overlay-box').click(this._onClickOverLayBox);
    this._intSearchOffcanvas;
    let sticky_header = $('.element-sticky-header');

    if (sticky_header.length > 0) {
      _this._initELementStickyheader(sticky_header);
    }
  }

  _initStickyHeader() {
    var _this = this;

    var tbay_width = $(window).width();

    var header_height = _this.$tbayHeader.outerHeight();

    var headerMain_height = _this.$tbayHeaderMain.outerHeight();

    var admin_height = $('#wpadminbar').length > 0 ? $('#wpadminbar').outerHeight() : 0;

    var sticky = _this.$tbayHeaderMain.offset().top;

    if (tbay_width >= 1024) {
      if (sticky == 0 || sticky == admin_height) {
        if (_this.$tbayHeader.hasClass('sticky-header')) return;

        _this._stickyHeaderOnDesktop(headerMain_height, sticky, admin_height);

        _this.$tbayHeaderMain.addClass('sticky-1');

        $(window).scroll(function () {
          if ($(this).scrollTop() > header_height) {
            _this.$tbayHeaderMain.addClass('sticky-box');
          } else {
            _this.$tbayHeaderMain.removeClass('sticky-box');
          }
        });
      } else {
        $(window).scroll(function () {
          if (!_this.$tbayHeader.hasClass('main-sticky-header')) return;

          if ($(this).scrollTop() > sticky - admin_height) {
            if (_this.$tbayHeader.hasClass('sticky-header')) return;

            _this._stickyHeaderOnDesktop(headerMain_height, sticky, admin_height);
          } else {
            _this.$tbayHeaderMain.css("top", 0).css("position", "relative").removeClass('sticky-header').parent().css('padding-top', 0);

            _this.$tbayHeaderMain.prev().css('margin-bottom', 0);
          }
        });
      }
    }
  }

  _stickyHeaderOnDesktop(headerMain_height, sticky, admin_height) {
    this.$tbayHeaderMain.addClass('sticky-header').css("top", admin_height).css("position", "fixed");

    if (sticky == 0 || sticky == admin_height) {
      this.$tbayHeaderMain.parent().css('padding-top', headerMain_height);
    } else {
      this.$tbayHeaderMain.prev().css('margin-bottom', headerMain_height);
    }
  }

  _onClickSeachMin() {
    $('.tbay-search-form.tbay-search-min form').toggleClass('show');
    $(this).toggleClass('active');
  }

  _onClickOverLayBox() {
    $('.search-min-wrapper .btn-search-min').removeClass('active');
    $('.tbay-search-form.tbay-search-min form').removeClass('show');
  }

  _intSearchOffcanvas() {
    if ($('#tbay-offcanvas-main').length === 0) return;
    $('[data-toggle="offcanvas-main-search"]').on('click', function () {
      $('#wrapper-container').toggleClass('show');
      $('#tbay-offcanvas-main').toggleClass('show');
    });
    var $box_totop = $('#tbay-offcanvas-main, .search');
    $(window).on("click.Bst", function (event) {
      if ($box_totop.has(event.target).length == 0 && !$box_totop.is(event.target)) {
        $('#wrapper-container').removeClass('show');
        $('#tbay-offcanvas-main').removeClass('show');
      }
    });
  }

  _initELementStickyheader(elements) {
    var el = elements.first();

    let _this = this;

    var scroll = false,
        sum = 0,
        prev_sum = 0;
    if (el.parents('.tbay_header-template').length === 0) return;
    var adminbar = $('#wpadminbar').length > 0 ? $('#wpadminbar').outerHeight() : 0,
        sticky_load = el.offset().top - $(window).scrollTop() - adminbar,
        sticky = sticky_load;
    el.prevAll().each(function () {
      prev_sum += $(this).outerHeight();
    });
    elements.each(function () {
      if ($(this).parents('.element-sticky-header').length > 0) return;
      sum += $(this).outerHeight();
    });

    _this._initELementStickyheaderContent(sticky_load, sticky, sum, prev_sum, elements, el, adminbar, scroll);

    $(window).scroll(function () {
      scroll = true;
      if ($(window).scrollTop() === 0) sticky = 0;

      _this._initELementStickyheaderContent(sticky_load, sticky, sum, prev_sum, elements, el, adminbar, scroll);
    });
  }

  _initELementStickyheaderContent(sticky_load, sticky, sum, prev_sum, elements, el, adminbar, scroll) {
    if ($(window).scrollTop() < prev_sum && scroll || $(window).scrollTop() === 0 && scroll) {
      if (el.parent().children().first().hasClass('element-sticky-header')) return;
      el.css('top', '');

      if (sticky === sticky_load || sticky === 0) {
        elements.last().next().css('padding-top', '');
      } else {
        el.prev().css('margin-bottom', '');
      }

      el.parent().css('padding-top', '');
      elements.each(function () {
        $(this).removeClass("sticky");

        if ($(this).prev('.element-sticky-header').length > 0) {
          $(this).css('top', '');
        }
      });
    } else {
      if ($(window).scrollTop() < prev_sum && !scroll) return;
      elements.each(function () {
        if ($(this).parents('.element-sticky-header').length > 0) return;
        $(this).addClass("sticky");

        if ($(this).prevAll('.element-sticky-header').length > 0) {
          let total = 0;
          $(this).prevAll('.element-sticky-header').each(function () {
            total += $(this).outerHeight();
          });
          $(this).css('top', total + adminbar);
        }
      });
      el.css('top', adminbar);

      if (sticky === sticky_load || sticky === 0) {
        el.addClass("sticky");
        el.parent().css('padding-top', sum);
      } else {
        el.prev().css('margin-bottom', sum);
      }
    }
  }

}

const TREE_VIEW_OPTION_MEGA_MENU = {
  animated: 300,
  collapsed: true,
  unique: true,
  persist: "location"
};
const TREE_VIEW_OPTION_MOBILE_MENU = {
  animated: 300,
  collapsed: true,
  unique: true,
  hover: false
};

class Mobile {
  constructor() {
    this._topBarDevice();

    this._mobileMenu();

    this._SearchFocusActive();

    this._SearchOnClickSearchHeader();

    this._PopupLoginMobile();

    this._Select_change_form();

    this._FastClicker();

    this._FooterMobileAccordion();

    $(window).scroll(() => {
      this._topBarDevice();
    });
  }

  _topBarDevice() {
    var scroll = $(window).scrollTop();
    var objectSelect = $(".topbar-device-mobile").height();
    var scrollmobile = $(window).scrollTop();
    $(".topbar-device-mobile").toggleClass("active", scroll <= objectSelect);
    $("#tbay-mobile-menu").toggleClass("offsetop", scrollmobile == 0);
  }

  _mobileMenu() {
    $('[data-toggle="offcanvas"], .btn-offcanvas').click(function () {
      $('#wrapper-container').toggleClass('active');
      $('#tbay-mobile-menu').toggleClass('active');
    });
    $("#main-mobile-menu .caret").click(function () {
      $("#main-mobile-menu .dropdown").removeClass('open');
      $(event.target).parent().addClass('open');
    });
  }

  _SearchFocusActive() {
    let search_mobile = $('.tbay-search-mobile .tbay-search');
    let search_cancel = $('.tbay-search-mobile .button-search-cancel');
    search_mobile.focusin(function () {
      $(search_mobile.parents('#tbay-mobile-menu-navbar')).addClass('search-mobile-focus');
      search_mobile.parent().find('.button-search-cancel').addClass('cancel-active');
    });
    search_cancel.on("click", function () {
      $(search_cancel.parents('#tbay-mobile-menu-navbar')).removeClass('search-mobile-focus');
      search_cancel.removeClass('cancel-active');
    });
  }

  _SearchOnClickSearchHeader() {
    let search_mobile = $('.search-device .search-icon');
    let search_cancel = $('.search-device .button-search-cancel');
    search_mobile.on("click", function () {
      $(search_mobile.parent()).addClass('active-search-mobile');
      $(search_mobile.parents('.topbar-device-mobile')).addClass('active-search');
    });
    search_cancel.on("click", function () {
      $(search_cancel.parents('.search-device')).removeClass('active-search-mobile');
      $(search_mobile.parents('.topbar-device-mobile')).removeClass('active-search');
      search_cancel.removeClass('cancel-active');
    });
  }

  _PopupLoginMobile() {
    let popup_login_mobile = $('.mmenu-account .popup-login a, .footer-device-mobile > .device-account > a.popup-login');
    popup_login_mobile.on("click", function () {
      let api = $("#tbay-mobile-menu-navbar").data("mmenu");
      $('#custom-login-wrapper').modal('show');
      $(popup_login_mobile.parents('#tbay-mobile-menu-navbar')).removeClass('mm-menu_opened');
      api.close();
    });
  }

  _Select_change_form() {
    $('.topbar-device-mobile > form select').on('change', function () {
      this.form.submit();
    });
  }

  _FastClicker() {
    if ('addEventListener' in document) {
      document.addEventListener('DOMContentLoaded', function () {
        FastClick.attach(document.body);
      }, false);
    }
  }

  _FooterMobileAccordion() {
    if ($(window).width() >= 768) return;
    $('.footer-mobile-collapse .heading-tbay-title').off().on('click', function () {
      var $title = $(this);
      var $widget = $title.parent();
      var $content = $widget.find('> *:not(.heading-tbay-title)');

      if ($widget.hasClass('opened-collapse')) {
        $widget.removeClass('opened-collapse');
        $content.stop().slideUp(200);
      } else {
        $widget.addClass('opened-collapse');
        $content.stop().slideDown(200);
      }
    });
  }

}

class AccountMenu {
  constructor() {
    this._slideToggleAccountMenu(".tbay-login");

    this._slideToggleAccountMenu(".topbar-mobile");

    this._tbayClickNotMyAccountMenu();
  }

  _tbayClickNotMyAccountMenu() {
    var $win_my_account = $(window);
    var $box_my_account = $('.tbay-login .dropdown .account-menu,.topbar-mobile .dropdown .account-menu,.tbay-login .dropdown .account-button,.topbar-mobile .dropdown .account-button');
    $win_my_account.on("click.Bst", function (event) {
      if ($box_my_account.has(event.target).length == 0 && !$box_my_account.is(event.target)) {
        $(".tbay-login .dropdown .account-menu").slideUp(500);
        $(".topbar-mobile .dropdown .account-menu").slideUp(500);
      }
    });
  }

  _slideToggleAccountMenu(parentSelector) {
    $(parentSelector).find(".dropdown .account-button").click(function () {
      $(parentSelector).find(".dropdown .account-menu").slideToggle(500);
    });
  }

}

class BackToTop {
  constructor() {
    this._init();
  }

  _init() {
    $(window).scroll(function () {
      var isActive = $(this).scrollTop() > 400;
      $('.tbay-to-top').toggleClass('active', isActive);
      $('.tbay-category-fixed').toggleClass('active', isActive);
    });
    $('#back-to-top-mobile, #back-to-top').click(this._onClickBackToTop);
  }

  _onClickBackToTop() {
    $('html, body').animate({
      scrollTop: '0px'
    }, 800);
  }

}

class CanvasMenu {
  constructor() {
    this._init();

    this._remove_click_Outside();

    this._initCanvasMenuSidebar();

    this._initCanvasMenu();
  }

  _init() {
    $("#tbay-offcanvas-main .btn-toggle-canvas").on("click", function () {
      $('#wrapper-container').removeClass('active');
    });
    $("#main-menu-offcanvas .caret").click(function () {
      $("#main-menu-offcanvas .dropdown").removeClass('open');
      $(this).parent().addClass('open');
      return false;
    });
    $('[data-toggle="offcanvas-main"]').on('click', function () {
      $('#wrapper-container').toggleClass('active');
      $('#tbay-offcanvas-main').toggleClass('active');
    });
  }

  _remove_click_Outside() {
    let win = $(window);
    win.on("click.Bst,click touchstart tap", function (event) {
      let box_popup = $('#tbay-offcanvas-main, .btn-toggle-canvas');

      if (box_popup.has(event.target).length == 0 && !box_popup.is(event.target)) {
        $('#wrapper-container').removeClass('active');
        return;
      }
    });
  }

  _initCanvasMenuSidebar() {
    jQuery(document).on('click', '.canvas-menu-sidebar .btn-canvas-menu', function () {
      $('body').toggleClass('canvas-menu-active');
    });
    jQuery(document).on('click', '.close-canvas-menu, .bg-close-canvas-menu', function () {
      $('body').removeClass('canvas-menu-active');
    });
  }

  _initCanvasMenu() {
    $(".element-menu-canvas").each(function () {
      jQuery(this).find('.canvas-menu-btn-wrapper > a').on('click', function (event) {
        $(this).parent().parent().addClass('open');
        event.stopPropagation();
      });
    });
    jQuery(document).on('click', '.canvas-overlay-wrapper', function (event) {
      $(this).parent().removeClass('open');
      event.stopPropagation();
    });
  }

}

class FuncCommon {
  constructor() {
    var _this = this;

    _this._progressAnimation();

    _this._createWrapStart();

    $('.mod-heading .widget-title > span').wrapStart();

    _this._tbayActiveAdminBar();

    _this._tbayResizeMegamenu();

    _this._initHeaderCoverBG();

    _this._initCanvasSearch();

    _this._initTreeviewMenu();

    _this._categoryMenu();

    _this._initContentMinHeight();

    $(window).scroll(() => {
      _this._tbayActiveAdminBar();
    });
    $(window).on("resize", () => {
      _this._tbayResizeMegamenu();
    });
    setTimeout(function () {
      jQuery(document.body).on('tbay_load_html_click', () => {
        _this._tbayResizeMegamenu();
      });
    }, 2000);

    _this._addAccordionLoginandCoupon();
  }

  _tbayActiveAdminBar() {
    var objectSelect = $("#wpadminbar");

    if (objectSelect.length > 0) {
      $("body").addClass("active-admin-bar");
    }
  }

  _createWrapStart() {
    $.fn.wrapStart = function () {
      return this.each(function () {
        var $this = $(this);
        var node = $this.contents().filter(function () {
          return this.nodeType == 3;
        }).first(),
            text = node.text().trim(),
            first = text.split(' ', 1).join(" ");
        if (!node.length) return;
        node[0].nodeValue = text.slice(first.length);
        node.before('<b>' + first + '</b>');
      });
    };
  }

  _progressAnimation() {
    $("[data-progress-animation]").each(function () {
      var $this = $(this);
      $this.appear(function () {
        var delay = $this.attr("data-appear-animation-delay") ? $this.attr("data-appear-animation-delay") : 1;
        if (delay > 1) $this.css("animation-delay", delay + "ms");
        setTimeout(function () {
          $this.animate({
            width: $this.attr("data-progress-animation")
          }, 800);
        }, delay);
      }, {
        accX: 0,
        accY: -50
      });
    });
  }

  _tbayResizeMegamenu() {
    var window_size = $('body').innerWidth();

    if ($('.tbay_custom_menu').length > 0 && $('.tbay_custom_menu').hasClass('tbay-vertical-menu')) {
      if (window_size > 767) {
        this._resizeMegaMenuOnDesktop();
      } else {
        this._initTreeViewForMegaMenuOnMobile();
      }
    }

    if ($('.tbay-megamenu').length > 0 && $('.tbay-megamenu,.tbay-offcanvas-main').hasClass('verticle-menu') && window_size > 767) {
      this._resizeMegaMenuVertical();
    }
  }

  _resizeMegaMenuVertical() {
    var full_width = parseInt($('#main-container.container').innerWidth());
    var menu_width = parseInt($('.verticle-menu').innerWidth());
    var w = full_width - menu_width;
    $('.verticle-menu').find('.aligned-fullwidth').children('.dropdown-menu').css({
      "max-width": w,
      "width": full_width - 30
    });
  }

  _resizeMegaMenuOnDesktop() {
    let maxWidth = $('#main-container.container').innerWidth() - $('.tbay-vertical-menu').innerWidth();
    let width = $('#main-container.container').innerWidth() - 30;
    $('.tbay-vertical-menu').find('.aligned-fullwidth').children('.dropdown-menu').css({
      'max-width': maxWidth,
      "width": width
    });
  }

  _initTreeViewForMegaMenuOnMobile() {
    $(".tbay-vertical-menu > .widget_nav_menu >.nav > ul").each(function () {
      if ($(this).hasClass('treeview')) return;
      $(this).treeview(TREE_VIEW_OPTION_MEGA_MENU);
    });
  }

  _addAccordionLoginandCoupon() {
    $('.showlogin, .showcoupon').click(function (event) {
      $(event.currentTarget).toggleClass('active');
    });
  }

  _initHeaderCoverBG() {
    let menu = $('.tbay-horizontal .navbar-nav > li,.tbay-horizontal-default .navbar-nav > li, .tbay_header-template .product-recently-viewed-header'),
        search = $('.tbay-search-form .tbay-search'),
        btn_category = $('.category-inside .category-inside-title'),
        cart_click = $('.cart-popup');
    menu.mouseenter(function () {
      if ($(this).parents('#tbay-header').length === 0) return;
      if ($(this).children('.dropdown-menu, ul, .content-view').length == 0) return;
      $('.tbay_header-template').addClass('nav-cover-active-1');
    }).mouseleave(function () {
      if ($(this).closest('.dropdown-menu').length) return;
      $('.tbay_header-template').removeClass('nav-cover-active-1');
    });
    search.focusin(function () {
      if ($(this).closest('.dropdown-menu').length) return;
      if (search.parents('.sidebar-canvas-search').length > 0 || $(this).closest('.tbay_header-template').length === 0) return;
      $('.tbay_header-template').addClass('nav-cover-active-2');
    }).focusout(function () {
      $('.tbay_header-template').removeClass('nav-cover-active-2');
    });
    cart_click.on('shown.bs.dropdown', function (event) {
      $(event.target).closest('.tbay_header-template').addClass('nav-cover-active-3');
    }).on('hidden.bs.dropdown', function (event) {
      $(event.target).closest('.tbay_header-template').removeClass('nav-cover-active-3');
    });

    if (btn_category.parents('.tbay_header-template')) {
      $(document.body).on('tbay_category_inside_open', () => {
        $('.tbay_header-template').addClass('nav-cover-active-4');
      });
      $(document.body).on('tbay_category_inside_close', () => {
        $('.tbay_header-template').removeClass('nav-cover-active-4');
      });
    }
  }

  _initCanvasSearch() {
    let input_search = $('#tbay-search-form-canvas .sidebar-canvas-search .sidebar-content .tbay-search');
    input_search.focusin(function () {
      input_search.parent().addClass('search_cv_active');
    }).focusout(function () {
      input_search.parent().removeClass('search_cv_active');
    });
  }

  _initTreeviewMenu() {
    if (typeof jQuery.fn.treeview === "undefined") return;
    $("#category-menu").addClass('treeview');
    jQuery(".treeview-menu .menu, #category-menu").treeview(TREE_VIEW_OPTION_MEGA_MENU);
    jQuery("#main-mobile-menu, #main-mobile-menu-xlg").treeview(TREE_VIEW_OPTION_MOBILE_MENU);
  }

  _categoryMenu() {
    $(".category-inside .category-inside-title").click(function () {
      $(event.target).parents('.category-inside').toggleClass("open");
      if ($(event.target).parents('.category-inside').hasClass('setting-open')) return;

      if ($(event.target).parents('.category-inside').hasClass('open')) {
        $(document.body).trigger('tbay_category_inside_open');
      } else {
        $(document.body).trigger('tbay_category_inside_close');
      }
    });
    let $win = $(window);
    $win.on("click.Bst,click touchstart tap", function (event) {
      let $box = $('.category-inside .category-inside-title, .category-inside-content');
      if (!$('.category-inside').hasClass('open') && !$('.tbay_header-template').hasClass('nav-cover-active-4')) return;

      if ($box.has(event.target).length == 0 && !$box.is(event.target)) {
        let insides = $('.category-inside');
        $.each(insides, function (key, inside) {
          if (!$(inside).hasClass('setting-open')) {
            $(inside).removeClass('open');
            $('.tbay_header-template').removeClass('nav-cover-active-4');
          }
        });
      }
    });
  }

  _initContentMinHeight() {
    let window_size = $('body').innerWidth(),
        $screen = $(window).height(),
        $header = $('.tbay_header-template').outerHeight(),
        $content = $('#tbay-main-content').outerHeight();

    if ($content < $screen && window_size > 1200) {
      $('#tbay-main-content').css('min-height', $screen - $header);
    }
  }

}

class NewsLetter {
  constructor() {
    this._init();
  }

  _init() {
    var popup = $('#popupNewsletterModal');
    if (popup.length === 0) return;
    popup.on('hidden.bs.modal', function () {
      Cookies.set('hiddenmodal', 1, {
        expires: 0.1,
        path: '/'
      });
    });
    setTimeout(function () {
      if (typeof Cookies.get('hiddenmodal') === "undefined" || Cookies.get('hiddenmodal') == "") {
        popup.modal('show');
      }
    }, 3000);
  }

}

class Banner {
  constructor() {
    this._init();
  }

  _init() {
    let btnRemove = $('.banner-remove');

    if (btnRemove.length === 0) {
      $('.elementor-widget-besa-banner-close').each(function () {
        $(this).closest('section').addClass('section-banner-close');
      });
    } else {
      btnRemove.on('click', function (event) {
        let id = $(this).data('id');
        $(this).parents('.elementor-widget-besa-banner-close').slideUp("slow");
        Cookies.set('banner_remove_' + id, 'hidden', {
          expires: 0.1,
          path: '/'
        });
        event.preventDefault();
      });
    }
  }

}

class Search {
  constructor() {
    this._init();
  }

  _init() {
    this._tbaySearchMobile();

    this._searchToTop();

    this._searchCanvasForm();

    this._searchCanvasFormV3();

    $('.button-show-search').click(() => $('.tbay-search-form').addClass('active'));
    $('.button-hidden-search').click(() => $('.tbay-search-form').removeClass('active'));
  }

  _tbaySearchMobile() {
    $(".topbar-mobile .search-popup, .search-device-mobile").each(function () {
      $(this).find(".show-search").click(event => {
        $(this).find(".tbay-search-form").slideToggle(500);
        $(this).find(".tbay-search-form .input-group .tbay-search").focus();
        $(event.currentTarget).toggleClass('active');
      });
    });
    $(window).on("click.Bst,click touchstart tap", function (event) {
      var $box = $('.footer-device-mobile > div i, .topbar-device-mobile .search-device-mobile i ,.search-device-mobile .tbay-search-form form');
      if (!$(".search-device-mobile .show-search").hasClass('active')) return;

      if ($box.has(event.target).length == 0 && !$box.is(event.target)) {
        $(".search-device-mobile .tbay-search-form").slideUp(500);
        $(".search-device-mobile .show-search").removeClass('active');
        $("body").removeClass('mobile-search-active');
      }
    });
    $('.topbar-mobile .dropdown-menu').click(function (e) {
      e.stopPropagation();
    });
  }

  _searchToTop() {
    $('.search-totop-wrapper .btn-search-totop').click(function () {
      $('.search-totop-content').toggleClass('active');
      $(this).toggleClass('active');
    });
    var $box_totop = $('.search-totop-wrapper .btn-search-totop, .search-totop-content');
    $(window).on("click.Bst", function (event) {
      if ($box_totop.has(event.target).length == 0 && !$box_totop.is(event.target)) {
        $('.search-totop-wrapper .btn-search-totop').removeClass('active');
        $('.search-totop-content').removeClass('active');
      }
    });
  }

  _searchCanvasForm() {
    let searchform = $('#tbay-search-form-canvas');
    if (searchform.length === 0) return;
    searchform.find('button.search-open').click(function () {
      $(event.target).parents('#tbay-search-form-canvas').toggleClass("open");
      $('body').toggleClass("active-search-canvas");
    });
    let window_searchcanvas = $(window);
    let forcussidebar = $('#tbay-search-form-canvas .search-open, #tbay-search-form-canvas .sidebar-content');
    window_searchcanvas.on("click.Bst", function (event) {
      if (!searchform.hasClass('open')) return;

      if (forcussidebar.has(event.target).length == 0 && !forcussidebar.is(event.target)) {
        searchform.removeClass("open");
        $('body').removeClass("active-search-canvas");
      }
    });
    searchform.find('button.btn-search-close').click(function () {
      if (!searchform.hasClass('open')) return;
      searchform.removeClass("open");
      $('body').removeClass("active-search-canvas");
    });
  }

  _searchCanvasFormV3() {
    let searchform = $('#tbay-search-form-canvas-v3');
    if (searchform.length === 0) return;
    searchform.find('button.search-open').click(function () {
      $(event.target).parents('#tbay-search-form-canvas-v3').toggleClass("open");
      $('body').toggleClass("active-search-canvas");
    });
    let window_searchcanvas = $(window);
    let forcussidebar = $('#tbay-search-form-canvas-v3 .search-open, #tbay-search-form-canvas-v3 .sidebar-content');
    window_searchcanvas.on("click.Bst", function (event) {
      if (!searchform.hasClass('open')) return;

      if (forcussidebar.has(event.target).length == 0 && !forcussidebar.is(event.target)) {
        searchform.removeClass("open");
        $('body').removeClass("active-search-canvas");
      }
    });
    searchform.find('button.btn-search-close').click(function () {
      if (!searchform.hasClass('open')) return;
      searchform.removeClass("open");
      $('body').removeClass("active-search-canvas");
    });
  }

}

class TreeView {
  constructor() {
    this._tbayTreeViewMenu();
  }

  _tbayTreeViewMenu() {
    if (typeof $.fn.treeview === "undefined" || typeof $('.tbay-treeview') === "undefined") return;
    $(".tbay-treeview").each(function () {
      if ($(this).find('> ul').hasClass('treeview')) return;
      $(this).find('> ul').treeview({
        animated: 400,
        collapsed: true,
        unique: true,
        persist: "location"
      });
    });
  }

}

class Section {
  constructor() {
    this._tbayMegaMenu();

    this._tbayRecentlyView();
  }

  _tbayMegaMenu() {
    let menu = $('.elementor-widget-besa-nav-menu');
    if (menu.length === 0) return;
    menu.find('.tbay-element-nav-menu').each(function () {
      if ($(this).data('wrapper').layout !== "horizontal") return;

      if (!$(this).closest('.elementor-top-column').hasClass('tbay-column-static')) {
        $(this).closest('.elementor-top-column').addClass('tbay-column-static');
      }

      if (!$(this).closest('section').hasClass('tbay-section-static')) {
        $(this).closest('section').addClass('tbay-section-static');
      }
    });
  }

  _tbayRecentlyView() {
    let recently = $('.tbay-element-product-recently-viewed');
    if (recently.length === 0) return;
    recently.each(function () {
      if ($(this).data('wrapper').layout !== "header") return;

      if (!$(this).closest('.elementor-top-column').hasClass('tbay-column-static')) {
        $(this).closest('.elementor-top-column').addClass('tbay-column-static');
      }

      if (!$(this).closest('.elementor-top-column').hasClass('tbay-column-recentlyviewed')) {
        $(this).closest('.elementor-top-column').addClass('tbay-column-recentlyviewed');
      }

      if (!$(this).closest('section').hasClass('tbay-section-recentlyviewed')) {
        $(this).closest('section').addClass('tbay-section-recentlyviewed');
      }

      if (!$(this).closest('section').hasClass('tbay-section-static')) {
        $(this).closest('section').addClass('tbay-section-static');
      }
    });
  }

}

class Preload {
  constructor() {
    this._init();
  }

  _init() {
    if ($.fn.jpreLoader) {
      var $preloader = $('.js-preloader');
      $preloader.jpreLoader({}, function () {
        $preloader.addClass('preloader-done');
        $('body').trigger('preloader-done');
        $(window).trigger('resize');
      });
    }

    $('.tbay-page-loader').delay(100).fadeOut(400, function () {
      $('body').removeClass('tbay-body-loading');
      $(this).remove();
    });

    if ($(document.body).hasClass('tbay-body-loader')) {
      setTimeout(function () {
        $(document.body).removeClass('tbay-body-loader');
        $('.tbay-page-loader').fadeOut(250);
      }, 300);
    }
  }

}

class Accordion {
  constructor() {
    this._init();
  }

  _init() {
    if ($('.single-product').length === 0) return;
    $('#accordion').on('shown.bs.collapse', function (e) {
      var offset = $(this).find('.collapse.show').prev('.tabs-title');

      if (offset) {
        $('html,body').animate({
          scrollTop: $(offset).offset().top - 150
        }, 500);
      }
    });
  }

}

class MenuDropdownsAJAX {
  constructor() {
    this._initmenuDropdownsAJAX();
  }

  _initmenuDropdownsAJAX() {
    var _this = this;

    $('body').on('mousemove', function () {
      $('.menu').has('.dropdown-load-ajax').each(function () {
        var $menu = $(this);

        if ($menu.hasClass('dropdowns-loading') || $menu.hasClass('dropdowns-loaded')) {
          return;
        }

        if (!_this.isNear($menu, 50, event)) {
          return;
        }

        _this.loadDropdowns($menu);
      });
    });
  }

  loadDropdowns($menu) {
    var _this = this;

    $menu.addClass('dropdowns-loading');
    var storageKey = '',
        unparsedData = '',
        menu_mobile_id = '';

    if ($menu.closest('nav').attr('id') === 'tbay-mobile-menu-navbar') {
      if ($('#main-mobile-menu-mmenu-wrapper').length > 0) {
        menu_mobile_id += '_' + $('#main-mobile-menu-mmenu-wrapper').data('id');
      }

      if ($('#main-mobile-second-mmenu-wrapper').length > 0) {
        menu_mobile_id += '_' + $('#main-mobile-second-mmenu-wrapper').data('id');
      }

      storageKey = besa_settings.storage_key + '_megamenu_mobile' + menu_mobile_id;
    } else {
      storageKey = besa_settings.storage_key + '_megamenu_' + $menu.closest('nav').find('ul').data('id');
    }

    unparsedData = localStorage.getItem(storageKey);
    var storedData = false;
    var $items = $menu.find('.dropdown-load-ajax'),
        ids = [];
    $items.each(function () {
      ids.push($(this).find('.dropdown-html-placeholder').data('id'));
    });

    try {
      storedData = JSON.parse(unparsedData);
    } catch (e) {
      console.log('cant parse Json', e);
    }

    if (storedData) {
      _this.renderResults(storedData, $menu);

      if ($menu.attr('id') !== 'tbay-mobile-menu-navbar') {
        $menu.removeClass('dropdowns-loading').addClass('dropdowns-loaded');
      }
    } else {
      $.ajax({
        url: besa_settings.ajaxurl,
        data: {
          action: 'besa_load_html_dropdowns',
          ids: ids
        },
        dataType: 'json',
        method: 'POST',
        success: function (response) {
          if (response.status === 'success') {
            _this.renderResults(response.data, $menu);

            localStorage.setItem(storageKey, JSON.stringify(response.data));
          } else {
            console.log('loading html dropdowns returns wrong data - ', response.message);
          }
        },
        error: function () {
          console.log('loading html dropdowns ajax error');
        }
      });
    }
  }

  renderResults(data, $menu) {
    var _this = this;

    Object.keys(data).forEach(function (id) {
      _this.removeDuplicatedStylesFromHTML(data[id], function (html) {
        let html2 = html;
        const regex1 = '<li[^>]*><a[^>]*href=["]' + window.location.href + '["]>.*?<\/a><\/li>';
        let content = html.match(regex1);

        if (content !== null) {
          let $url = content[0];
          let $class = $url.match(/(?:class)=(?:["']\W+\s*(?:\w+)\()?["']([^'"]+)['"]/g)[0].split('"')[1];
          let $class_new = $class + ' active';
          let $url_new = $url.replace($class, $class_new);
          html2 = html2.replace($url, $url_new);
        }

        $menu.find('[data-id="' + id + '"]').replaceWith(html2);

        if ($menu.attr('id') !== 'tbay-mobile-menu-navbar') {
          $menu.addClass('dropdowns-loaded');
          setTimeout(function () {
            $menu.removeClass('dropdowns-loading');
          }, 1000);
        }
      });
    });
  }

  isNear($element, distance, event) {
    var left = $element.offset().left - distance,
        top = $element.offset().top - distance,
        right = left + $element.width() + 2 * distance,
        bottom = top + $element.height() + 2 * distance,
        x = event.pageX,
        y = event.pageY;
    return x > left && x < right && y > top && y < bottom;
  }

  removeDuplicatedStylesFromHTML(html, callback) {
    if (besa_settings.combined_css) {
      callback(html);
      return;
    } else {
      const regex = /<style>.*?<\/style>/mg;
      let output = html.replace(regex, "");
      callback(output);
      return;
    }
  }

}

class MenuClickAJAX {
  constructor() {
    if (typeof besa_settings === "undefined") return;

    this._initmenuClickAJAX();
  }

  _initmenuClickAJAX() {
    $('.element-menu-ajax.ajax-active').each(function () {
      var $menu = $(this);
      $menu.find('.menu-click').off('click').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        if (!$this.closest('.element-menu-ajax').hasClass('ajax-active')) return;
        var element = $this.closest('.tbay-element'),
            type_menu = element.data('wrapper')['type_menu'],
            layout = element.data('wrapper')['layout'];

        if (type_menu === 'toggle') {
          var nav = element.find('.category-inside-content > nav');
        } else {
          var nav = element.find('.menu-canvas-content > nav');
        }

        var slug = nav.data('id');
        var storageKey = besa_settings.storage_key + '_' + slug + '_' + layout;
        var storedData = false;
        var unparsedData = localStorage.getItem(storageKey);

        try {
          storedData = JSON.parse(unparsedData);
        } catch (e) {
          console.log('cant parse Json', e);
        }

        if (storedData) {
          nav.html(storedData);
          element.removeClass('load-ajax');
          $this.closest('.element-menu-ajax').removeClass('ajax-active');

          if (layout === 'treeview') {
            $(document.body).trigger('tbay_load_html_click_treeview');
          } else {
            $(document.body).trigger('tbay_load_html_click');
          }
        } else {
          $.ajax({
            url: besa_settings.ajaxurl,
            data: {
              action: 'besa_load_html_click',
              slug: slug,
              type_menu: type_menu,
              layout: layout
            },
            dataType: 'json',
            method: 'POST',
            beforeSend: function (xhr) {
              element.addClass('load-ajax');
            },
            success: function (response) {
              if (response.status === 'success') {
                nav.html(response.data);
                localStorage.setItem(storageKey, JSON.stringify(response.data));

                if (layout === 'treeview') {
                  $(document.body).trigger('tbay_load_html_click_treeview');
                } else {
                  $(document.body).trigger('tbay_load_html_click');
                }
              } else {
                console.log('loading html dropdowns returns wrong data - ', response.message);
              }

              element.removeClass('load-ajax');
              $this.closest('.element-menu-ajax').removeClass('ajax-active');
            },
            error: function () {
              console.log('loading html dropdowns ajax error');
            }
          });
        }
      });
    });
  }

}

class SumoSelect {
  constructor() {
    if (typeof jQuery.fn.SumoSelect === "undefined") return;
    if (typeof besa_settings === "undefined") return;

    this._init();
  }

  _init() {
    jQuery(document).ready(function () {
      jQuery('.woocommerce-currency-switcher,.woocommerce-fillter >.select, .woocommerce-ordering > .orderby, .tbay-filter select').SumoSelect({
        csvDispCount: 3,
        captionFormatAllSelected: "Yeah, OK, so everything."
      });
      let search_form = jQuery('.tbay-search-form');
      search_form.each(function () {
        if ($(this).hasClass('tbay-search-mobile')) return;
        $(this).find('select').SumoSelect({
          forceCustomRendering: true
        });
      });
    });
  }

}

class AutoComplete {
  constructor() {
    if (typeof jQuery.Autocomplete === "undefined") return;
    if (typeof besa_settings === "undefined") return;

    this._callAjaxSearch();
  }

  _callAjaxSearch() {
    var _this = this,
        url = besa_settings.ajaxurl + '?action=besa_autocomplete_search',
        form = $('form.searchform.besa-ajax-search'),
        RegEx = function (value) {
      return value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    };

    form.each(function () {
      var _this2 = $(this),
          autosearch = _this2.find('input[name=s]'),
          image = Boolean(_this2.data('thumbnail')),
          price = Boolean(_this2.data('price'));

      autosearch.devbridgeAutocomplete({
        serviceUrl: _this._AutoServiceUrl(autosearch, url),
        minChars: _this._AutoMinChars(autosearch),
        appendTo: _this._AutoAppendTo(autosearch),
        width: '100%',
        maxHeight: 'initial',
        onSelect: function (suggestion) {
          if (suggestion.link.length > 0) window.location.href = suggestion.link;
        },
        onSearchStart: function (query) {
          let form = autosearch.parents('form');
          form.addClass('tbay-loading');
        },
        beforeRender: function (container, suggestion) {
          if (typeof suggestion[0].result != 'undefined') {
            $(container).prepend('<div class="list-header"><span>' + suggestion[0].result + '</span></div>');
          }

          if (suggestion[0].view_all) {
            $(container).append('<div class="view-all-products"><span>' + besa_settings.show_all_text + '</span><i class="tb-icon tb-icon-chevron-right"></i></div>');
          }
        },
        onSearchComplete: function (query, suggestions) {
          form.removeClass('tbay-loading');
          $(this).parents('form').addClass('open');
          $(document.body).trigger('tbay_searchcomplete');
        },
        formatResult: (suggestion, currentValue) => {
          let returnValue = _this._initformatResult(suggestion, currentValue, RegEx, image, price);

          return returnValue;
        },
        onHide: function (container) {
          if ($(this).parents('form').hasClass('open')) $(this).parents('form').removeClass('open');
        }
      });
      $('body').click(function () {
        if (autosearch.is(":focus")) {
          return;
        }

        autosearch.each(function () {
          $(this).devbridgeAutocomplete('hide');
        });
      });
    });
    var cat_change = form.find('[name="product_cat"], [name="category"]');

    if (cat_change.length) {
      cat_change.change(function (e) {
        let se_input = $(e.target).parents('form').find('input[name=s]'),
            ac = se_input.devbridgeAutocomplete();
        ac.hide();
        ac.setOptions({
          serviceUrl: _this._AutoServiceUrl(se_input, url)
        });
        ac.onValueChange();
      });
    }

    $(document.body).on('tbay_searchcomplete', function () {
      $(".view-all-products").on("click", function () {
        $(this).parents('form').submit();
        e.stopPropagation();
      });
    });
  }

  _AutoServiceUrl(autosearch, url) {
    let form = autosearch.parents('form'),
        number = parseInt(form.data('count')),
        postType = form.data('post-type'),
        product_cat = form.find('[name="product_cat"], [name="category"]').val();

    if (number > 0) {
      url += '&number=' + number;
    }

    url += '&post_type=' + postType;

    if (product_cat) {
      url += '&product_cat=' + product_cat;
    }

    return url;
  }

  _AutoAppendTo(autosearch) {
    let form = autosearch.parents('form'),
        appendTo = typeof form.data('appendto') !== 'undefined' ? form.data('appendto') : form.find('.besa-search-results');
    return appendTo;
  }

  _AutoMinChars(autosearch) {
    let form = autosearch.parents('form'),
        minChars = parseInt(form.data('minchars'));
    return minChars;
  }

  _initformatResult(suggestion, currentValue, RegEx, image, price) {
    if (currentValue == '&') currentValue = "&#038;";
    var pattern = '(' + RegEx(currentValue) + ')',
        returnValue = '';
    if (suggestion.no_found) return '<div class="suggestion-title no-found-msg">' + suggestion.value + '</div>';

    if (image && suggestion.image && suggestion.image.length > 0) {
      returnValue += ' <div class="suggestion-thumb">' + suggestion.image + '</div>';
    }

    returnValue += '<div class="suggestion-group">';
    returnValue += '<div class="suggestion-title product-title">' + suggestion.value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/&lt;(\/?strong)&gt;/g, '<$1>') + '</div>';

    if (price && suggestion.price && suggestion.price.length > 0) {
      returnValue += ' <div class="suggestion-price price">' + suggestion.price + '</div>';
    }

    if (suggestion.sku && suggestion.sku.length > 0) {
      returnValue += '<div class="suggestion-sku product-sku"><span>' + suggestion.sku.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/&lt;(\/?strong)&gt;/g, '<$1>') + '</span></div>';
    }

    returnValue += '</div>';
    return returnValue;
  }

}

class CountDownTimer {
  constructor() {
    if (typeof jQuery.fn.tbayCountDown === "undefined") return;
    if (typeof besa_settings === "undefined") return;
    this.CountDownTimer();
    this.CountDownTimer2();
  }

  CountDownTimer() {
    var _this = this;

    if (jQuery('[data-time="timmer"]').length === 0) return;
    jQuery('[data-time="timmer"]:not(.scroll-init)').each(function () {
      _this._initCountDownTimer(jQuery(this));
    });
    jQuery('[data-time="timmer"].scroll-init').waypoint(function () {
      var $this = $($(this)[0].element);

      _this._initCountDownTimer($this);
    }, {
      offset: '100%'
    });
  }

  CountDownTimer2() {
    var _this = this;

    if (jQuery('[data-countdown="countdown"]').length === 0) return;
    jQuery('[data-countdown="countdown"]:not(.scroll-init)').each(function () {
      _this._initCountDownTimer2(jQuery(this));
    });
    jQuery('[data-countdown="countdown"].scroll-init').waypoint(function () {
      var $this = $($(this)[0].element);

      _this._initCountDownTimer2($this);
    }, {
      offset: '100%'
    });
  }

  _initCountDownTimer(el) {
    let date = jQuery(el).data('date').split("-"),
        days = jQuery(el).data('days') ? jQuery(el).data('days') : '',
        hours = jQuery(el).data('hours') ? jQuery(el).data('hours') : '',
        mins = jQuery(el).data('mins') ? jQuery(el).data('mins') : '',
        secs = jQuery(el).data('secs') ? jQuery(el).data('secs') : '';
    jQuery(el).tbayCountDown({
      TargetDate: date[0] + "/" + date[1] + "/" + date[2] + " " + date[3] + ":" + date[4] + ":" + date[5],
      DisplayFormat: "<div class=\"times\"><div class=\"day\">%%D%%" + days + "</div><span>:</span><div class=\"hours\">%%H%%" + hours + "</div><span>:</span><div class=\"minutes\">%%M%%" + mins + "</div><span>:</span><div class=\"seconds\">%%S%%" + secs + "</div></div>",
      FinishMessage: ""
    });
  }

  _initCountDownTimer2(el) {
    let date = jQuery(el).data('date').split("-"),
        days = jQuery(el).data('days') ? jQuery(el).data('days') : '',
        hours = jQuery(el).data('hours') ? jQuery(el).data('hours') : '',
        mins = jQuery(el).data('mins') ? jQuery(el).data('mins') : '',
        secs = jQuery(el).data('secs') ? jQuery(el).data('secs') : '';
    jQuery(el).tbayCountDown({
      TargetDate: date[0] + "/" + date[1] + "/" + date[2] + " " + date[3] + ":" + date[4] + ":" + date[5],
      DisplayFormat: "<div class=\"times\"><div class=\"day\">%%D%%" + days + " </div><span>:</span><div class=\"hours\">%%H%%" + hours + " </div><span>:</span><div class=\"minutes\">%%M%%" + mins + " </div><span>:</span><div class=\"seconds\">%%S%%" + secs + " </div></div>",
      FinishMessage: ""
    });
  }

}

class MMenu {
  constructor() {
    if (typeof jQuery.fn.mmenu === "undefined") return;
    if (typeof besa_settings === "undefined") return;

    this._initMmenu();
  }

  _initMmenu() {
    if ($('body').hasClass('admin-bar')) {
      $('html').addClass('html-mmenu');
    }

    var text_cancel = typeof besa_settings !== "undefined" ? besa_settings.cancel : '';
    var _PLUGIN_ = 'mmenu';

    $[_PLUGIN_].i18n({
      'cancel': text_cancel
    });

    var mmenu = $("#tbay-mobile-smartmenu");
    if ($(mmenu).length === 0) return;
    var themes = mmenu.data('themes');
    var enablesearch = Boolean(mmenu.data("enablesearch"));
    var menu_title = mmenu.data('title');
    var searchcounters = Boolean(mmenu.data('counters'));
    var enabletabs = Boolean(mmenu.data("enabletabs"));
    var tabone = enabletabs ? mmenu.data('tabone') : '';
    var taboneicon = enabletabs ? mmenu.data('taboneicon') : '';
    var tabsecond = enabletabs ? mmenu.data('tabsecond') : '';
    var tabsecondicon = enabletabs ? mmenu.data('tabsecondicon') : '';
    var enablesocial = Boolean(mmenu.data("enablesocial"));
    var socialjsons = '';
    var enablebottom = Boolean(mmenu.data("enablebottom"));
    var enableeffects = Boolean(mmenu.data("enableeffects"));
    var effectspanels = enableeffects ? mmenu.data('effectspanels') : '';
    var effectslistitems = enableeffects ? mmenu.data('effectslistitems') : '';
    var mmenuOptions = {
      offCanvas: true,
      navbar: {
        title: menu_title
      },
      counters: searchcounters,
      extensions: [themes, effectspanels, effectslistitems]
    };
    var mmenuOptionsAddition = {
      navbars: [],
      searchfield: {}
    };

    if (enablesearch) {
      mmenuOptionsAddition.navbars.push({
        position: ['top'],
        content: ['searchfield']
      });
      mmenuOptionsAddition.searchfield = {
        panel: {
          add: true
        }
      };
    }

    if (enabletabs) {
      mmenuOptionsAddition.navbars.push({
        type: 'tabs',
        content: ['<a href="#main-mobile-menu-mmenu"><i class="' + taboneicon + '"></i> <span>' + tabone + '</span></a>', '<a href="#mobile-menu-second-mmenu"><i class="' + tabsecondicon + '"></i> <span>' + tabsecond + '</span></a>']
      });
    }

    var content = '';

    if (enablesocial) {
      if (typeof mmenu.data("socialjsons") !== "undefined") {
        socialjsons = JSON.parse(mmenu.data("socialjsons").replace(/'/g, '"'));
        content = $.map(socialjsons, function (value) {
          return `<a class="mmenu-icon" href="${value.url}" target="_blank"><i class="${value.icon}"></i></a>`;
        });
      }
    }

    if (enablebottom) {
      mmenuOptionsAddition.navbars.push({
        position: 'bottom',
        content: content
      });
    }

    mmenuOptions = _.extend(mmenuOptionsAddition, mmenuOptions);
    var mmenuConfigurations = {
      offCanvas: {
        pageSelector: "#tbay-main-content"
      },
      searchfield: {
        clear: true
      }
    };
    $("#tbay-mobile-menu-navbar").mmenu(mmenuOptions, mmenuConfigurations);
    let search = $('#mm-searchfield');

    if (search.length > 0) {
      search.prependTo($("#tbay-mobile-menu-navbar .mm-navbars_bottom"));
    }

    if (!enablesocial) {
      $('.mm-navbars_bottom .mm-navbar').hide();
    }

    $('.mm-panels').css('top', $('.mm-navbars_top').outerHeight());
  }

}

class OnePageNav {
  constructor() {
    if (typeof jQuery.fn.onePageNav === "undefined") return;
    if (typeof besa_settings === "undefined") return;

    this._productSingleOnepagenav();
  }

  _productSingleOnepagenav() {
    if ($('#sticky-menu-bar').length > 0) {
      let offset_adminbar = 0;

      if ($('#wpadminbar').length > 0) {
        offset_adminbar = $('#wpadminbar').outerHeight();
      }

      let offset = $('#sticky-menu-bar').outerHeight() + offset_adminbar;
      $('#sticky-menu-bar').onePageNav({
        currentClass: 'current',
        changeHash: false,
        scrollSpeed: 750,
        scrollThreshold: 0.5,
        scrollOffset: offset,
        filter: '',
        easing: 'swing',
        begin: function () {},
        end: function () {},
        scrollChange: function () {}
      });
    }

    var onepage = $('#sticky-menu-bar');

    if (onepage.length > 0) {
      var tbay_width = $(window).width();
      $('.tbay_header-template').removeClass('main-sticky-header');
      var btn_cart_offset = $('.single_add_to_cart_button').length > 0 ? $('.single_add_to_cart_button').offset().top : 0;
      var sum_height_default = $('div.product .out-of-stock').length > 0 ? $('div.product .out-of-stock').offset().top : 0;
      var meta_default = 0;

      if ($('.by-vendor-name-link').length > 0) {
        sum_height_default = $('.by-vendor-name-link').offset().top;
      } else if ($('.single_add_to_cart_button').length === 0) {
        meta_default = onepage.parent().find('.product_meta').length > 0 ? onepage.parent().find('.product_meta').offset().top - onepage.parent().find('.product_meta').outerHeight() : 0;

        if (onepage.hasClass('woo-simple-auction')) {
          sum_height_default = $('.auction_form').length > 0 ? $('.auction_form').offset().top : meta_default;
        } else if (onepage.hasClass('yith-auctions')) {
          sum_height_default = $('#yith-wcact-form-bid').length > 0 ? $('#yith-wcact-form-bid').offset().top : meta_default;
        }
      }

      var sum_height = $('.single_add_to_cart_button').length > 0 ? btn_cart_offset : sum_height_default;

      this._checkScroll(tbay_width, sum_height, onepage);

      $(window).scroll(() => {
        this._checkScroll(tbay_width, sum_height, onepage);
      });
    }

    if (onepage.hasClass('active') && jQuery('#wpadminbar').length > 0) {
      onepage.css('top', $('#wpadminbar').height());
    }
  }

  _checkScroll(tbay_width, sum_height, onepage) {
    if (tbay_width >= 768) {
      var NextScroll = $(window).scrollTop();

      if (NextScroll > sum_height) {
        onepage.addClass('active');

        if (jQuery('#wpadminbar').length > 0) {
          onepage.css('top', $('#wpadminbar').height());
        }
      } else {
        onepage.removeClass('active');
      }
    } else {
      onepage.removeClass('active');
    }
  }

}

window.$ = window.jQuery;
jQuery(document).ready(() => {
  new MenuDropdownsAJAX(), new MenuClickAJAX(), new StickyHeader(), new AccountMenu(), new BackToTop(), new CanvasMenu(), new FuncCommon(), new NewsLetter(), new Banner(), new Preload(), new Search(), new TreeView(), new Accordion(), new AutoComplete(), new MMenu(), new OnePageNav(), new CountDownTimer(), new Section();

  if (jQuery.browser.mobile || $(window).width() < 1025) {
    new Mobile();
  }

  new SumoSelect();
  jQuery(document).on("woof_ajax_done", woof_ajax_done_handler2);

  function woof_ajax_done_handler2(e) {
    new SumoSelect();
  }
});
setTimeout(function () {
  jQuery(document.body).on('tbay_load_html_click_treeview', () => {
    new TreeView();
  });
}, 2000);
jQuery(window).on('resize', function () {
  if (jQuery.browser.mobile || $(window).width() < 1025) {
    new Mobile();
  }
});

var CanvasMenuHandler = function ($scope, $) {
  var Canvasmenu = new CanvasMenu();

  Canvasmenu._initCanvasMenu();
};

jQuery(window).on('elementor/frontend/init', function () {
  if (elementorFrontend.isEditMode()) {
    elementorFrontend.hooks.addFilter('frontend/element_ready/besa-nav-menu.default', CanvasMenuHandler);
  }
});

var AutoCompleteHandler = function ($scope, $) {
  new AutoComplete();
};

jQuery(window).on('elementor/frontend/init', function () {
  if (elementorFrontend.isEditMode()) {
    elementorFrontend.hooks.addAction('frontend/element_ready/besa-search-form.default', AutoCompleteHandler);
  }
});

var CountDownTimerHandler = function ($scope, $) {
  new CountDownTimer();
};

setTimeout(function () {
  jQuery(document.body).on('tbay_quick_view', () => {
    new CountDownTimer();
  });
}, 2000);
jQuery(window).on('elementor/frontend/init', function () {
  if (typeof besa_settings !== "undefined" && elementorFrontend.isEditMode() && Array.isArray(besa_settings.elements_ready.countdowntimer)) {
    jQuery.each(besa_settings.elements_ready.countdowntimer, function (index, value) {
      elementorFrontend.hooks.addAction('frontend/element_ready/tbay-' + value + '.default', CountDownTimerHandler);
    });
  }
});
