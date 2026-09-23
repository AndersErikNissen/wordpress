"use strict";

(function() {
  /* ==========================================================================
     UTILITIES
     ========================================================================== */

  const UTILITY = {
    throttle: (callback, wait) => {
      let throttling = false;

      return (...args) => {
        if (!throttling) {
          callback.apply(null, args);
          throttling = window.setTimeout(() => throttling = false, wait);
        }
      };
    },
    debounce: (callback, wait) => {
      let timeoutId = null;

      return (...args) => {
        window.clearTimeout(timeoutId);

        timeoutId = window.setTimeout(() => {
          callback.apply(null, args);
        }, wait);
      };
    },
    observe: (callback, target, options = {}) => {
      if (!target) {
        return;
      }

      const OBSERVER_CALLBACK = (entries) => {
        entries.forEach((entry) => {
          callback(entry);
        });
      };

      const OBSERVER = new IntersectionObserver(OBSERVER_CALLBACK, options);

      OBSERVER.observe(target);
    },
    clamp: (value, min = 0, max = 1) => {
      return Math.min(max, Math.max(min, value));
    },
    lerp: (from, to, ease) => {
      return from * (1 - ease) + to * ease;
    },
  };

  /* ==========================================================================
     ALL THE CLASSES
     ========================================================================== */

  class ScrollProgressSection {
    state = {
      ticking: false,
      looping: false,
    };
    
    progress = {
      cache: 0,
      current: 0,
      scroll: 0,
    }

    constructor({section, observe}) {
      this.section = section;
      this.observe = observe || section;

      if (this.section) {
        this.bindEvents();
      }
    }

    loop() {
      const RECT = this.observe.getBoundingClientRect();
      const START_PROGRESS_FROM = window.innerHeight;
      const EASE = 0.2;

      this.progress.scroll = 1 - ((RECT.bottom - START_PROGRESS_FROM) / RECT.height * 1);
      this.progress.current = UTILITY.clamp(UTILITY.lerp(this.progress.cache, this.progress.scroll, EASE));
      
      if (this.progress.cache === this.progress.current) {
        this.state.looping = false;
      } 

      if (this.progress.cache !== this.progress.current) {
        this.section.style.setProperty("--progress", this.progress.current);

        this.progress.cache = this.progress.current;

        window.requestAnimationFrame(() => this.loop());
      } 
    }

    bindEvents() {
      window.addEventListener("scroll", () => {
        if (!this.state.ticking) {
          window.requestAnimationFrame(() => {
            if (!this.state.looping) {
              this.state.looping = true;
              this.loop();
            }

            this.state.ticking = false;
          });

          this.state.ticking = true;
        }
      });
    }
  }
  
  class Menu {
    isOpen = false;
    openClassName = "the-menu-is-open";
    openAnimationClass = "opening-animation";
    closeAnimationClass = "closing-animation";
    animationTiming = 500;
    animationTimeout;
    
    constructor({menu, btns, aside}) {
      this.menu = menu;
      this.btns = btns;
      this.aside = aside;

      this.bindEvents();
    }

    toggle () {
      if (!document.body.classList.contains('allow-animations')) {
        document.body.classList.add('allow-animations');
      }

      if (this.animationTimeout) {
        return;
      }

      window.requestAnimationFrame(() => {
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
          document.body.classList.add(this.openClassName, this.openAnimationClass);
          
          this.animationTimeout = setTimeout(() => {
            window.requestAnimationFrame(() => {
              document.body.classList.remove(this.openAnimationClass);
              this.animationTimeout = false;
            });
          }, this.animationTiming);
        } else {
          document.body.classList.add(this.closeAnimationClass);

          this.closingTimeout = setTimeout(() => {
            window.requestAnimationFrame(() => {
              document.body.classList.remove(this.openClassName, this.closeAnimationClass);
              this.animationTimeout = false;
            });
          }, this.animationTiming);
        }
      })
    }
    
    bindEvents () {
      this.btns.forEach((btn) => {
        btn.addEventListener("click", () => this.toggle());
      });

      this.aside.addEventListener("click", () => this.toggle());

      document.body.addEventListener("keyup", (e) => {
        if (e.key === "Escape") {
          if (this.isOpen) {
            this.toggle();
          }
        }
      });
    }
  }

  class SubMenuHandler {
    activeSubMenu = null;

    constructor({subMenus, btns, menu}) {
      this.subMenus = this.mapSubMenus(subMenus);
      this.btns = btns;
      this.menu = menu;

      this.bindEvents();
    }

    mapSubMenus(subMenus) {
      return Array.from(subMenus).map((subMenu) => {
        let mappedSubMenu = {
          element: subMenu,
          parentId: false, 
        };

        let parsedParentId = parseInt(subMenu.dataset.subMenuParentId);

        if (parsedParentId) {
          mappedSubMenu.parentId = parsedParentId;
        }

        return mappedSubMenu;
      });
    }

    open(btn) {    
      const ID = parseInt(btn.dataset.subMenuId);
      const SUB_MENU = this.subMenus.find((subMenu) => subMenu.parentId === ID);

      if (!SUB_MENU) return;

      this.activeSubMenu = SUB_MENU;
      this.activeSubMenu.element.classList.add('display-sub-menu');
      this.menu.classList.add('the-sub-menu-is-open');
    }

    close() {
      if (!this.activeSubMenu) return;
      
      this.activeSubMenu.element.classList.remove('display-sub-menu');
      this.menu.classList.remove('the-sub-menu-is-open');

      this.activeSubMenu = null;
    }

    bindEvents() {
      this.btns.forEach((btn) => btn.addEventListener("click", () => {
        this[btn.dataset.subMenuAction.toLowerCase()](btn);
      }));

    }
  }

  /* ==========================================================================
     INIT
     ========================================================================== */

  // --- Header ---------------------------------------------------------------
  UTILITY.observe((entry) => {
    document.querySelector(".the-header")?.classList.toggle('animate', !entry.isIntersecting);
  }, document.querySelector('.observer-zone--the-header'));
  
  // --- Section(s) -----------------------------------------------------------
  new ScrollProgressSection({
    section: document.querySelector(".icon-w-text"),
  })

  new ScrollProgressSection({
    section: document.querySelector(".expertises"),
    observe: document.querySelector(".expertises .observer-zone"),
  });

  // --- Menu -----------------------------------------------------------------
  new Menu({
    menu: document.querySelector(".the-menu"),
    btns: document.querySelectorAll('[data-btn="menu"]'),
    aside: document.querySelector(".the-menu__aside"),
  });

  new SubMenuHandler({
    subMenus: document.querySelectorAll('.the-menu__sub-menu'),
    btns: document.querySelectorAll('[data-btn="sub-menu"]'),
    menu: document.body.querySelector(".the-menu"),
  });
}());