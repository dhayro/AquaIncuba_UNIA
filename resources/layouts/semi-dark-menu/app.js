var App = function() {
    var MediaSize = {
        xl: 1200,
        lg: 992,
        md: 991,
        sm: 576
    };
    var Dom = {
        main: document.querySelector('html') || document.querySelector('body') || document.documentElement,
        id: {
            container: document.querySelector("#container"),
        },
        class: {
            navbar: document.querySelector(".navbar"),
            overlay: document.querySelector('.overlay'),
            search: document.querySelector('.toggle-search'),
            searchOverlay: document.querySelector('.search-overlay'),
            searchForm: document.querySelector('.search-form-control'),
            mainContainer: document.querySelector('.main-container'),
            mainHeader: document.querySelector('.header.navbar')
        }
    }

    var categoryScroll = {
        scrollCat: function() {
            var sidebarWrapper = document.querySelectorAll('.sidebar-wrapper li.active')[0];
            if (sidebarWrapper) {
                var sidebarWrapperTop = sidebarWrapper.offsetTop - 12;
                setTimeout(() => {
                    const scroll = document.querySelector('.menu-categories');
                    if (scroll) {
                        scroll.scrollTop = sidebarWrapperTop;
                    }
                }, 50);
            }
        }
    }

    var toggleFunction = {
        sidebar: function($recentSubmenu) {

            var sidebarCollapseEle = document.querySelectorAll('.sidebarCollapse');

            sidebarCollapseEle.forEach(el => {
                el.addEventListener('click', function (sidebar) {
                    sidebar.preventDefault();
                    let getSidebar = document.querySelector('.sidebar-wrapper');
                    
                    // Safe check for main container
                    if (!Dom.class.mainContainer || !Dom.class.mainHeader || !Dom.class.overlay || !Dom.main) {
                        return;
                    }

                    if ($recentSubmenu === true) {
                        let collapseSubmenu = document.querySelector('.collapse.submenu');
                        
                        if (collapseSubmenu && collapseSubmenu.classList.contains('show')) {
                            let submenuShow = document.querySelector('.submenu.show');
                            if (submenuShow) {
                                submenuShow.classList.add('mini-recent-submenu');
                            }
                            
                            if (getSidebar) {
                                let collapseEle = getSidebar.querySelector('.collapse.submenu');
                                if (collapseEle) {
                                    collapseEle.classList.remove('show');
                                }
                            }
                            
                            if (collapseSubmenu && collapseSubmenu.parentNode) {
                                let dropdownToggle = collapseSubmenu.parentNode.querySelector('.dropdown-toggle');
                                if (dropdownToggle) {
                                    dropdownToggle.setAttribute('aria-expanded', 'false');
                                }
                            }
                        } else {
                            if (Dom.class.mainContainer.classList.contains('sidebar-closed')) {
                                if (collapseSubmenu && collapseSubmenu.classList.contains('recent-submenu')) {
                                    if (getSidebar) {
                                        let recentSubmenuEle = getSidebar.querySelector('.collapse.submenu.recent-submenu');
                                        if (recentSubmenuEle) {
                                            recentSubmenuEle.classList.add('show');
                                            let dropdownToggle = recentSubmenuEle.parentNode.querySelector('.dropdown-toggle');
                                            if (dropdownToggle) {
                                                dropdownToggle.setAttribute('aria-expanded', 'true');
                                            }
                                        }
                                    }
                                    let submenuEl = document.querySelector('.submenu');
                                    if (submenuEl) {
                                        submenuEl.classList.remove('mini-recent-submenu');
                                    }
                                } else {
                                    let activeSubmenu = document.querySelector('li.active .submenu');
                                    if (activeSubmenu) {
                                        activeSubmenu.classList.add('recent-submenu');
                                    }
                                    
                                    if (getSidebar) {
                                        let recentSubmenuEle = getSidebar.querySelector('.collapse.submenu.recent-submenu');
                                        if (recentSubmenuEle) {
                                            recentSubmenuEle.classList.add('show');
                                            let dropdownToggle = recentSubmenuEle.parentNode.querySelector('.dropdown-toggle');
                                            if (dropdownToggle) {
                                                dropdownToggle.setAttribute('aria-expanded', 'true');
                                            }
                                        }
                                    }
                                    
                                    let submenuEl = document.querySelector('.submenu');
                                    if (submenuEl) {
                                        submenuEl.classList.remove('mini-recent-submenu');
                                    }
                                }
                            }
                        }
                    }
                    
                    Dom.class.mainContainer.classList.toggle("sidebar-closed");
                    Dom.class.mainHeader.classList.toggle('expand-header');
                    Dom.class.mainContainer.classList.toggle("sbar-open");
                    Dom.class.overlay.classList.toggle('show');
                    Dom.main.classList.toggle('sidebar-noneoverflow');
                });
            });
        },
        onToggleSidebarSubmenu: function() {
            var sidebarWrapper = document.querySelector('.sidebar-wrapper');
            if (!sidebarWrapper) return;
            
            ['mouseenter', 'mouseleave'].forEach(function(e){
                sidebarWrapper.addEventListener(e, function() {
                    let bodyEl = document.querySelector('body');
                    let mainContainerEl = document.querySelector('.main-container');
                    
                    if (!bodyEl || !mainContainerEl) return;
                    
                    if (bodyEl.classList.contains('alt-menu')) {
                        if (mainContainerEl.classList.contains('sidebar-closed')) {
                            if (e === 'mouseenter') {
                                let liMenuSubmenu = document.querySelector('li.menu .submenu');
                                if (liMenuSubmenu) {
                                    liMenuSubmenu.classList.remove('show');
                                }
                                
                                let activeSubmenu = document.querySelector('li.menu.active .submenu');
                                if (activeSubmenu) {
                                    activeSubmenu.classList.add('recent-submenu');
                                }
                                
                                let liMenuActive = document.querySelector('li.menu.active');
                                if (liMenuActive) {
                                    let recentSubmenu = liMenuActive.querySelector('.collapse.submenu.recent-submenu');
                                    if (recentSubmenu) {
                                        recentSubmenu.classList.add('show');
                                        let dropdownToggle = recentSubmenu.parentNode.querySelector('.dropdown-toggle');
                                        if (dropdownToggle) {
                                            dropdownToggle.setAttribute('aria-expanded', 'true');
                                        }
                                    }
                                }
                            } else if (e === 'mouseleave') {
                                let getMenuList = document.querySelectorAll('li.menu');
                                getMenuList.forEach(element => {
                                    var submenuShowEle = element.querySelector('.collapse.submenu.show');
                                    if (submenuShowEle) {
                                        submenuShowEle.classList.remove('show');
                                    }
                                    var submenuExpandedToggleEle = element.querySelector('.dropdown-toggle[aria-expanded="true"]');
                                    if (submenuExpandedToggleEle) {
                                        submenuExpandedToggleEle.setAttribute('aria-expanded', 'false');
                                    }
                                });
                            }
                        }
                    } else {
                        if (mainContainerEl.classList.contains('sidebar-closed')) {
                            if (e === 'mouseenter') {
                                let liMenuSubmenu = document.querySelector('li.menu .submenu');
                                if (liMenuSubmenu) {
                                    liMenuSubmenu.classList.remove('show');
                                }
                                let activeSubmenu = document.querySelector('li.menu.active .submenu');
                                if (activeSubmenu) {
                                    activeSubmenu.classList.add('recent-submenu');
                                    let liMenuActive = document.querySelector('li.menu.active');
                                    if (liMenuActive) {
                                        let recentSubmenu = liMenuActive.querySelector('.collapse.submenu.recent-submenu');
                                        if (recentSubmenu) {
                                            recentSubmenu.classList.add('show');
                                            let dropdownToggle = recentSubmenu.parentNode.querySelector('.dropdown-toggle');
                                            if (dropdownToggle) {
                                                dropdownToggle.setAttribute('aria-expanded', 'true');
                                            }
                                        }
                                    }
                                }
                            } else if (e === 'mouseleave') {
                                let getMenuList = document.querySelectorAll('li.menu');
                                getMenuList.forEach(element => {
                                    var submenuShowEle = element.querySelector('.collapse.submenu.show');
                                    if (submenuShowEle) {
                                        submenuShowEle.classList.remove('show');
                                    }
                                    var submenuExpandedToggleEle = element.querySelector('.dropdown-toggle[aria-expanded="true"]');
                                    if (submenuExpandedToggleEle) {
                                        submenuExpandedToggleEle.setAttribute('aria-expanded', 'false');
                                    }
                                });
                            }
                        }
                    }
                });
            });
        },
        offToggleSidebarSubmenu: function () {
            // $('.sidebar-wrapper').off('mouseenter mouseleave');
        },
        overlay: function() {
            var overlayElement = document.querySelector('#dismiss, .overlay');
            if (!overlayElement) return;
            
            overlayElement.addEventListener('click', function () {
                // hide sidebar
                if (Dom.class.mainContainer) {
                    Dom.class.mainContainer.classList.add('sidebar-closed');
                    Dom.class.mainContainer.classList.remove('sbar-open');
                }
                // hide overlay
                if (Dom.class.overlay) {
                    Dom.class.overlay.classList.remove('show');
                }
                if (Dom.main) {
                    Dom.main.classList.remove('sidebar-noneoverflow');
                }
            });
        },
        search: function() {

            if (!Dom.class.search || !Dom.class.searchOverlay) {
                return;
            }
            
            let bodyEl = document.querySelector('body');
            
            Dom.class.search.addEventListener('click', function(event) {
                this.classList.add('show-search');
                if (Dom.class.searchOverlay) {
                    Dom.class.searchOverlay.classList.add('show');
                }
                if (bodyEl) {
                    bodyEl.classList.add('search-active');
                }
            });
            
            Dom.class.searchOverlay.addEventListener('click', function(event) {
                this.classList.remove('show');
                if (Dom.class.search) {
                    Dom.class.search.classList.remove('show-search');
                }
                if (bodyEl) {
                    bodyEl.classList.remove('search-active');
                }
            });
            
            var searchClose = document.querySelector('.search-close');
            if (searchClose) {
                searchClose.addEventListener('click', function(event) {
                    event.stopPropagation();
                    if (Dom.class.searchOverlay) {
                        Dom.class.searchOverlay.classList.remove('show');
                    }
                    if (Dom.class.search) {
                        Dom.class.search.classList.remove('show-search');
                    }
                    if (bodyEl) {
                        bodyEl.classList.remove('search-active');
                    }
                    let searchFormControl = document.querySelector('.search-form-control');
                    if (searchFormControl) {
                        searchFormControl.value = '';
                    }
                });
            }

        },
        themeToggle: function (layoutName) {

            var togglethemeEl = document.querySelector('.theme-toggle');
            var getBodyEl = document.body;
            
            if (togglethemeEl) {
                togglethemeEl.addEventListener('click', function() {
                
                var getLocalStorage = sessionStorage.getItem("theme");
                var parseObj = JSON.parse(getLocalStorage);

                if (parseObj.settings.layout.darkMode) {

                    var getObjectSettings = parseObj.settings.layout;

                    var newParseObject = {...getObjectSettings, darkMode: false};

                    var newObject = { ...parseObj, settings: { layout: newParseObject }}

                    sessionStorage.setItem("theme", JSON.stringify(newObject))
                    
                    var getUpdatedLocalObject = sessionStorage.getItem("theme");
                    var getUpdatedParseObject = JSON.parse(getUpdatedLocalObject);

                    if (!getUpdatedParseObject.settings.layout.darkMode) {
                        document.body.classList.remove('dark')
                        let ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;
                        if (ifStarterKit) {
                            // document.querySelector('.navbar-logo').setAttribute('src', '../../src/assets/img/logo2.svg')
                        } else {
                            // document.querySelector('.navbar-logo').setAttribute('src', getUpdatedParseObject.settings.layout.logo.lightLogo)
                        }
                    }
                    
                } else {

                    var getObjectSettings = parseObj.settings.layout;

                    var newParseObject = {...getObjectSettings, darkMode: true};

                    var newObject = { ...parseObj, settings: { layout: newParseObject }}

                    sessionStorage.setItem("theme", JSON.stringify(newObject))
                    
                    var getUpdatedLocalObject = sessionStorage.getItem("theme");
                    var getUpdatedParseObject = JSON.parse(getUpdatedLocalObject);

                    if (getUpdatedParseObject.settings.layout.darkMode) {
                        document.body.classList.add('dark')

                        let ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;

                        if (ifStarterKit) {
                            // document.querySelector('.navbar-logo').setAttribute('src', '../../src/assets/img/logo.svg')
                        } else {
                            // document.querySelector('.navbar-logo').setAttribute('src', getUpdatedParseObject.settings.layout.logo.darkLogo)
                        }
                        
                    }
                    
                }
                
                // sessionStorage.clear()
            })
            }
            
        }
    }

    var inBuiltfunctionality = {
        mainCatActivateScroll: function() {

            if (document.querySelector('.menu-categories')) {
            
                const ps = new PerfectScrollbar('.menu-categories', {
                    wheelSpeed:.5,
                    swipeEasing:!0,
                    minScrollbarLength:40,
                    maxScrollbarLength:300
                });

            }
        },
        notificationScroll: function() {

            if (document.querySelector('.notification-scroll')) {
                const notificationS = new PerfectScrollbar('.notification-scroll', {
                    wheelSpeed:.5,
                    swipeEasing:!0,
                    minScrollbarLength:40,
                    maxScrollbarLength:300
                });
            }
            
        },
        preventScrollBody: function() {
            var nonScrollableElement = document.querySelectorAll('#sidebar, .user-profile-dropdown .dropdown-menu, .notification-dropdown .dropdown-menu,  .language-dropdown .dropdown-menu')

            var preventScrolling = function(e) {
                e = e || window.event;
                if (e.preventDefault)
                    e.preventDefault();
                e.returnValue = false;  

                nonScrollableElement.scrollTop -= e. wheelDeltaY; 
            }

            nonScrollableElement.forEach(preventScroll => {

                preventScroll.addEventListener('mousewheel', preventScrolling);
                preventScroll.addEventListener('DOMMouseScroll', preventScrolling);
                
            });
        },
        searchKeyBind: function() {

            if (Dom.class.search && Dom.class.searchOverlay && Dom.class.searchForm) {
                let bodyEl = document.querySelector('body');
                Mousetrap.bind('ctrl+/', function() {
                    if (bodyEl) {
                        bodyEl.classList.add('search-active');
                    }
                    Dom.class.search.classList.add('show-search');
                    Dom.class.searchOverlay.classList.add('show');
                    Dom.class.searchForm.focus();
                    return false;
                });
            }

        },
        bsTooltip: function() {
            var bsTooltip = document.querySelectorAll('.bs-tooltip')
            for (let index = 0; index < bsTooltip.length; index++) {
                var tooltip = new bootstrap.Tooltip(bsTooltip[index])
            }
        },
        bsPopover: function() {
            var bsPopover = document.querySelectorAll('.bs-popover')
            for (let index = 0; index < bsPopover.length; index++) {
                var popover = new bootstrap.Popover(bsPopover[index])
            }
        },
        onCheckandChangeSidebarActiveClass: function() {
            let bodyEl = document.querySelector('body');
            if (bodyEl && bodyEl.classList.contains('alt-menu')) {
                let expandedEl = document.querySelector('.sidebar-wrapper [aria-expanded="true"]');
                if (expandedEl) {
                    let activeMenuEl = document.querySelector('.sidebar-wrapper li.menu.active [aria-expanded="true"]');
                    if (activeMenuEl) {
                        activeMenuEl.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        },
        MaterialRippleEffect: function() {
            let getAllBtn = document.querySelectorAll('button.btn, a.btn');
            
            getAllBtn.forEach(btn => {
    
                if (!btn.classList.contains('_no--effects')) {
                    btn.classList.add('_effect--ripple');
                }
                
            });
    
            if (document.querySelector('._effect--ripple')) {
                Waves.attach('._effect--ripple', 'waves-light');
                Waves.init();
            }
        }
    }

    var _mobileResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if ( windowWidth <= MediaSize.md ) {
                categoryScroll.scrollCat();
                toggleFunction.sidebar();
            }
        },
        
        onResize: function() {
            window.addEventListener('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if ( windowWidth <= MediaSize.md ) {
                    toggleFunction.offToggleSidebarSubmenu();
                }
            });
        }
        
    }

    var _desktopResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if ( windowWidth > MediaSize.md ) {
                categoryScroll.scrollCat();
                toggleFunction.sidebar();
                toggleFunction.onToggleSidebarSubmenu();
            }
        },
        
        onResize: function() {
            window.addEventListener('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if ( windowWidth > MediaSize.md ) {
                    toggleFunction.onToggleSidebarSubmenu();
                }
            });
        }
        
    }

    function sidebarFunctionality() {
        function sidebarCloser() {
            let bodyEl = document.querySelector('body');
            if (!bodyEl) return;

            if (window.innerWidth <= 991 ) {

                if (!bodyEl.classList.contains('alt-menu')) {

                    if (Dom.id.container) Dom.id.container.classList.add("sidebar-closed");
                    if (Dom.class.overlay) Dom.class.overlay.classList.remove('show');
                } else {
                    if (Dom.class.navbar) Dom.class.navbar.classList.remove("expand-header");
                    if (Dom.class.overlay) Dom.class.overlay.classList.remove('show');
                    if (Dom.id.container) Dom.id.container.classList.remove('sbar-open');
                    if (Dom.main) Dom.main.classList.remove('sidebar-noneoverflow');
                }

            } else if (window.innerWidth > 991 ) {

                if (!bodyEl.classList.contains('alt-menu')) {

                    if (Dom.id.container) Dom.id.container.classList.remove("sidebar-closed");
                    if (Dom.class.navbar) Dom.class.navbar.classList.remove("expand-header");
                    if (Dom.class.overlay) Dom.class.overlay.classList.remove('show');
                    if (Dom.id.container) Dom.id.container.classList.remove('sbar-open');
                    if (Dom.main) Dom.main.classList.remove('sidebar-noneoverflow');
                } else {
                    if (Dom.main) Dom.main.classList.add('sidebar-noneoverflow');
                    if (Dom.id.container) Dom.id.container.classList.add("sidebar-closed");
                    if (Dom.class.navbar) Dom.class.navbar.classList.add("expand-header");
                    if (Dom.class.overlay) Dom.class.overlay.classList.add('show');
                    if (Dom.id.container) Dom.id.container.classList.add('sbar-open');

                    let ariaExpandedEl = document.querySelector('.sidebar-wrapper [aria-expanded="true"]');
                    if (ariaExpandedEl) {
                        let collapseEle = ariaExpandedEl.parentNode.querySelector('.collapse');
                        if (collapseEle) {
                            collapseEle.classList.remove('show');
                        }
                    }

                }
            }
        }

        function sidebarMobCheck() {
            if (window.innerWidth <= 991 ) {

                if ( document.querySelector('.main-container').classList.contains('sbar-open') ) {
                    return;
                } else {
                    sidebarCloser()
                }
            } else if (window.innerWidth > 991 ) {
                sidebarCloser();
            }
        }

        sidebarCloser();

        window.addEventListener('resize', function(event) {
            sidebarMobCheck();
        });

    }

    return {
        init: function(Layout) {
            toggleFunction.overlay();
            toggleFunction.search();
            toggleFunction.themeToggle(Layout);
            
            /*
                Desktop Resoltion fn
            */
            _desktopResolution.onRefresh();
            _desktopResolution.onResize();

            /*
                Mobile Resoltion fn
            */
            _mobileResolution.onRefresh();
            _mobileResolution.onResize();

            sidebarFunctionality();

            /*
                In Built Functionality fn
            */
            inBuiltfunctionality.mainCatActivateScroll();
            inBuiltfunctionality.notificationScroll();
            inBuiltfunctionality.preventScrollBody();
            inBuiltfunctionality.searchKeyBind();
            inBuiltfunctionality.bsTooltip();
            inBuiltfunctionality.bsPopover();
            inBuiltfunctionality.onCheckandChangeSidebarActiveClass();
            inBuiltfunctionality.MaterialRippleEffect();
        }
    }

}();

window.addEventListener('load', function() {
    App.init('layout');
})