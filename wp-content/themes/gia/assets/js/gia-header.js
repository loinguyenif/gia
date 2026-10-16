(function () {
  "use strict";

  var icons = {
    motor:
      '<svg viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M8 27l3-10c.5-1.6 2-2.7 3.7-2.7h18.6c1.7 0 3.2 1.1 3.7 2.7l3 10v9H8z" fill="#fff" stroke="#252525" stroke-width="2.5" stroke-linejoin="round"/><path d="M12 18h24l2.4 8H9.6z" fill="#d7e6f4" stroke="#252525" stroke-width="2"/><path d="M8 27h32M14 33h5m10 0h5" stroke="#252525" stroke-width="2.5" stroke-linecap="round"/><path d="M10 28h4m20 0h4" stroke="#ed7a21" stroke-width="3" stroke-linecap="round"/></svg>',
    property:
      '<svg viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M7 42V22h16v20M24 42V11h16v31" fill="#f4f6fa" stroke="#252525" stroke-width="2.4"/><path d="M24 11h16v7H24z" fill="#ed7a21"/><path d="M29 22h3m4 0h1m-8 6h3m4 0h1m-8 6h3m4 0h1M12 27h3m-3 5h3m-3 5h3" stroke="#426aa8" stroke-width="2"/><path d="M5 42h39" stroke="#252525" stroke-width="2.5" stroke-linecap="round"/></svg>',
    travel:
      '<svg viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M7 29l12-5L13 9l4-2 13 14 9-4c2-.8 4 .1 4.2 1.5.2 1.5-1 2.7-2.5 3.3l-10 4-7 16-4 1 2-14-10 4-4 6-3-1 2-8-2-3z" fill="#ef7a20" stroke="#252525" stroke-width="2" stroke-linejoin="round"/><path d="M17 7l3-1 7 7-4 2z" fill="#4b78ba"/></svg>',
    work:
      '<svg viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M24 5a8 8 0 0 0-8 8v3l-4 6v16h24V22l-4-6v-3a8 8 0 0 0-8-8z" fill="#f0a04c" stroke="#252525" stroke-width="2.3"/><path d="M18 18l6 5 6-5 4 5-4 14H18l-4-14z" fill="#fff" stroke="#252525" stroke-width="2"/><path d="M22 25h4l2 12h-8z" fill="#517ab5"/></svg>',
    other:
      '<svg viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M4 23a20 20 0 0 1 40 0H4z" fill="#eef3fb" stroke="#252525" stroke-width="2.4"/><path d="M24 4v19M14 7l5 16m15-16-5 16M7 13l10 10m24-10L31 23" stroke="#4c78b8" stroke-width="2"/><path d="M24 23v18c0 3 5 3 5 0" fill="none" stroke="#ed7a21" stroke-width="2.5" stroke-linecap="round"/></svg>',
    agent:
      '<svg viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M24 5a8 8 0 0 0-8 8v3l-5 6v17h26V22l-5-6v-3a8 8 0 0 0-8-8z" fill="#f0a04c" stroke="#252525" stroke-width="2.3"/><path d="M18 18l6 5 6-5 4 5-4 14H18l-4-14z" fill="#fff" stroke="#252525" stroke-width="2"/><path d="M22 25h4l2 12h-8z" fill="#517ab5"/></svg>',
  };

  function iconFor(label, item) {
    var name = label.toLowerCase().replace(/[^a-z]+/g, " ").trim();
    var iconNames = ["motor", "property", "travel", "work", "other", "agent"];

    for (var i = 0; i < iconNames.length; i += 1) {
      if (item.classList.contains("gia-nav-icon-" + iconNames[i])) {
        return icons[iconNames[i]];
      }
    }

    if (name.indexOf("motor") !== -1) return icons.motor;
    if (name.indexOf("property") !== -1) return icons.property;
    if (name.indexOf("travel") !== -1) return icons.travel;
    if (name.indexOf("work injury") !== -1) return icons.work;
    if (name.indexOf("other types") !== -1 || name.indexOf("other insurance") !== -1) return icons.other;
    if (name.indexOf("agent search") !== -1) return icons.agent;

    return "";
  }

  function prepareMenu(menu, mobile) {
    if (!menu) return;

    var parentItems = menu.querySelectorAll("li.menu-item-has-children");

    function closeMenuItem(item) {
      item.classList.remove("is-open");
      item.querySelectorAll("li.is-open").forEach(function (child) {
        child.classList.remove("is-open");
        delete child.dataset.giaClickOpen;
        delete child.dataset.giaHoverClosed;
      });
      delete item.dataset.giaClickOpen;
      delete item.dataset.giaHoverClosed;
      item.querySelectorAll(".gia-nav__submenu-toggle").forEach(function (childButton) {
        childButton.setAttribute("aria-expanded", "false");
      });
      var button = item.querySelector(":scope > .gia-nav__submenu-toggle");
      if (button) button.setAttribute("aria-expanded", "false");
    }

    function closeOpenSiblings(item) {
      Array.prototype.forEach.call(item.parentElement.children, function (sibling) {
        if (sibling !== item && sibling.matches("li.is-open")) {
          closeMenuItem(sibling);
        }
      });
    }

    parentItems.forEach(function (item, index) {
      var link = item.querySelector(":scope > a");
      var submenu = item.querySelector(":scope > .sub-menu, :scope > .gia-nav-mega");

      if (!link || !submenu) return;

      var button = document.createElement("button");
      button.className = mobile
        ? "gia-nav__submenu-toggle gia-nav__submenu-toggle--mobile"
        : "gia-nav__submenu-toggle";
      button.type = "button";
      button.setAttribute("aria-expanded", "false");
      button.setAttribute("aria-label", "Toggle submenu for " + link.textContent.trim());
      button.innerHTML = mobile
        ? '<span aria-hidden="true">&#8964;</span>'
        : '<i class="fal fa-angle-down" aria-hidden="true"></i>';
      submenu.id = (mobile ? "gia-nav-mobile-submenu-" : "gia-nav-desktop-submenu-") + index;
      button.setAttribute("aria-controls", submenu.id);
      link.after(button);

      button.addEventListener("click", function () {
        if (
          !mobile &&
          button.getAttribute("aria-expanded") === "true" &&
          item.dataset.giaClickOpen !== "true"
        ) {
          item.dataset.giaClickOpen = "true";
          return;
        }

        var opening = button.getAttribute("aria-expanded") !== "true";

        if (!mobile) menu.classList.remove("gia-nav__list--escape-closed");

        if (opening && !mobile) {
          closeOpenSiblings(item);
        }

        item.classList.toggle("is-open", opening);
        button.setAttribute("aria-expanded", String(opening));
        if (!mobile) {
          if (opening) {
            item.dataset.giaClickOpen = "true";
            delete item.dataset.giaHoverClosed;
          } else {
            item.dataset.giaHoverClosed = "true";
          }
        }
      });

      if (!mobile) {
        item.addEventListener("mouseenter", function () {
          if (item.dataset.giaHoverClosed === "true") return;

          menu.classList.remove("gia-nav__list--escape-closed");
          closeOpenSiblings(item);
          item.classList.add("is-open");
          button.setAttribute("aria-expanded", "true");
        });
        item.addEventListener("mouseleave", function () {
          closeMenuItem(item);
        });
        item.addEventListener("focusin", function () {
          closeOpenSiblings(item);
          item.classList.add("is-open");
          button.setAttribute("aria-expanded", "true");
        });
        item.addEventListener("focusout", function (event) {
          if (!item.contains(event.relatedTarget)) {
            closeMenuItem(item);
          }
        });
      }
    });

    if (!mobile) {
      menu.querySelectorAll(".gia-nav-mega__links > li > a").forEach(function (link) {
        var imageUrl = link.getAttribute("data-gia-menu-icon");
        var svg = imageUrl ? "" : iconFor(link.textContent, link.parentElement);

        if (imageUrl || svg) {
          var icon = document.createElement("span");
          icon.className = "gia-nav-icon";

          if (imageUrl) {
            var image = document.createElement("img");
            image.src = imageUrl;
            image.alt = "";
            image.setAttribute("aria-hidden", "true");
            image.decoding = "async";
            icon.appendChild(image);
          } else {
            icon.innerHTML = svg;
          }

          link.prepend(icon);
        }
      });
    } else {
      menu.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
          window.setTimeout(closeDrawer, 0);
        });
      });
    }
  }

  var desktopMenu = document.querySelector(".gia-nav__list--desktop");
  var mobileMenu = document.querySelector(".gia-nav__list--mobile");
  prepareMenu(desktopMenu, false);
  prepareMenu(mobileMenu, true);

  var header = document.querySelector(".gia-header");
  var mainHeader = header && header.querySelector(".gia-header__main");
  var audienceBar = header && header.querySelector(".gia-header__audience-bar");

  if (header && mainHeader && audienceBar) {
    var scrollFrame = 0;

    function updateStickyMainHeader() {
      var threshold = audienceBar.offsetHeight;
      var mainHeight = mainHeader.offsetHeight;

      header.style.setProperty("--gia-header-main-height", mainHeight + "px");
      header.classList.toggle("gia-header--main-fixed", window.scrollY >= threshold);
      scrollFrame = 0;
    }

    function requestStickyHeaderUpdate() {
      if (!scrollFrame) {
        scrollFrame = window.requestAnimationFrame(updateStickyMainHeader);
      }
    }

    updateStickyMainHeader();
    window.addEventListener("scroll", requestStickyHeaderUpdate, { passive: true });
    window.addEventListener("resize", requestStickyHeaderUpdate);
  }

  if (desktopMenu) {
    desktopMenu.addEventListener("pointerleave", function () {
      desktopMenu.classList.remove("gia-nav__list--escape-closed");
    });
    desktopMenu.addEventListener("focusout", function (event) {
      if (!desktopMenu.contains(event.relatedTarget)) {
        desktopMenu.classList.remove("gia-nav__list--escape-closed");
      }
    });
  }

  var toggle = document.querySelector(".gia-header__toggle");
  var drawer = document.querySelector(".gia-nav--mobile");
  var backdrop = document.querySelector(".gia-header__backdrop");
  var closeButton = document.querySelector(".gia-nav__close");

  function closeDrawer() {
    if (!toggle || !drawer || !backdrop || drawer.hidden) return;

    drawer.classList.remove("is-open");
    backdrop.classList.remove("is-visible");
    toggle.setAttribute("aria-expanded", "false");
    toggle.setAttribute("aria-label", "Open menu");
    document.documentElement.classList.remove("gia-header-menu-open");

    window.setTimeout(function () {
      if (!drawer.classList.contains("is-open")) {
        drawer.hidden = true;
        backdrop.hidden = true;
        toggle.focus();
      }
    }, 260);
  }

  function openDrawer() {
    if (!toggle || !drawer || !backdrop) return;

    drawer.hidden = false;
    backdrop.hidden = false;
    document.documentElement.classList.add("gia-header-menu-open");
    toggle.setAttribute("aria-expanded", "true");
    toggle.setAttribute("aria-label", "Close menu");

    window.requestAnimationFrame(function () {
      drawer.classList.add("is-open");
      backdrop.classList.add("is-visible");
      var firstLink = drawer.querySelector("a");
      if (firstLink) firstLink.focus();
      else if (closeButton) closeButton.focus();
    });
  }

  if (toggle && drawer && backdrop) {
    toggle.addEventListener("click", function () {
      if (drawer.hidden) openDrawer();
      else closeDrawer();
    });
    backdrop.addEventListener("click", closeDrawer);
    if (closeButton) closeButton.addEventListener("click", closeDrawer);

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        closeDrawer();
        if (desktopMenu) {
          desktopMenu.classList.add("gia-nav__list--escape-closed");
          desktopMenu.querySelectorAll("li.is-open").forEach(function (item) {
            item.classList.remove("is-open");
          });
          desktopMenu.querySelectorAll(".gia-nav__submenu-toggle").forEach(function (button) {
            button.setAttribute("aria-expanded", "false");
          });
        }
      }

      if (event.key === "Tab" && drawer && !drawer.hidden) {
        var focusable = Array.prototype.slice.call(
          drawer.querySelectorAll('a[href], button:not([disabled])'),
        );
        var first = focusable[0];
        var last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      }
    });

    document.addEventListener("click", function (event) {
      if (desktopMenu && !desktopMenu.contains(event.target)) {
        desktopMenu.querySelectorAll("li.is-open").forEach(function (item) {
          item.classList.remove("is-open");
        });
        desktopMenu.querySelectorAll(".gia-nav__submenu-toggle").forEach(function (button) {
          button.setAttribute("aria-expanded", "false");
        });
      }
    });

    window.addEventListener("resize", function () {
      if (window.innerWidth > 991) closeDrawer();
    });
  }
})();
