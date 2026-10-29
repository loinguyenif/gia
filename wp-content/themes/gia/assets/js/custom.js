jQuery(document).ready(function () {
  var desktopMenu = document.querySelector(".gia-desktop-menu");
  if (desktopMenu) {
    desktopMenu
      .querySelectorAll("li.menu-item-has-children")
      .forEach(function (menuItem) {
        var submenu = menuItem.querySelector(":scope > .sub-menu");
        var link = menuItem.querySelector(":scope > a");

        if (!submenu || !link) {
          return;
        }

        var childItems = submenu.children.length;
        submenu.classList.add(
          childItems >= 5 ? "gia-mega-menu" : "gia-dropdown-menu",
        );

        var submenuToggle = document.createElement("button");
        submenuToggle.className = "gia-desktop-submenu-toggle";
        submenuToggle.type = "button";
        submenuToggle.setAttribute("aria-expanded", "false");
        submenuToggle.setAttribute(
          "aria-label",
          "Toggle submenu for " + link.textContent.trim(),
        );
        submenuToggle.innerHTML = '<span aria-hidden="true">&#8964;</span>';
        link.after(submenuToggle);

        submenuToggle.addEventListener("click", function () {
          var isExpanded =
            submenuToggle.getAttribute("aria-expanded") === "true";

          desktopMenu
            .querySelectorAll(".is-submenu-open")
            .forEach(function (openMenuItem) {
              if (openMenuItem !== menuItem) {
                openMenuItem.classList.remove("is-submenu-open");
                var openToggle = openMenuItem.querySelector(
                  ":scope > .gia-desktop-submenu-toggle",
                );
                if (openToggle) {
                  openToggle.setAttribute("aria-expanded", "false");
                }
              }
            });

          menuItem.classList.toggle("is-submenu-open", !isExpanded);
          submenuToggle.setAttribute("aria-expanded", String(!isExpanded));
        });
      });

    document.addEventListener("click", function (event) {
      if (!desktopMenu.contains(event.target)) {
        desktopMenu
          .querySelectorAll(".is-submenu-open")
          .forEach(function (menuItem) {
            menuItem.classList.remove("is-submenu-open");
            var submenuToggle = menuItem.querySelector(
              ":scope > .gia-desktop-submenu-toggle",
            );
            if (submenuToggle) {
              submenuToggle.setAttribute("aria-expanded", "false");
            }
          });
      }
    });
  }

  var menuToggle = document.querySelector(".gia-menu-toggle");
  var mainMenu = document.querySelector(".gia-main-menu");
  var menuBackdrop = document.querySelector(".gia-menu-backdrop");

  if (menuToggle && mainMenu && menuBackdrop) {
    var closeMenuButton = mainMenu.querySelector(".gia-main-menu__close");

    function closeMainMenu() {
      if (mainMenu.hidden) {
        return;
      }

      mainMenu.classList.remove("is-open");
      menuBackdrop.classList.remove("is-visible");
      menuToggle.setAttribute("aria-expanded", "false");
      menuToggle.setAttribute("aria-label", "Open main menu");
      document.documentElement.classList.remove("gia-menu-open");

      mainMenu.addEventListener("transitionend", function onMenuClosed(event) {
        if (
          event.target !== mainMenu ||
          mainMenu.classList.contains("is-open")
        ) {
          return;
        }

        mainMenu.hidden = true;
        menuBackdrop.hidden = true;
        menuToggle.focus();
        mainMenu.removeEventListener("transitionend", onMenuClosed);
      });
    }

    function openMainMenu() {
      mainMenu.hidden = false;
      menuBackdrop.hidden = false;
      document.documentElement.classList.add("gia-menu-open");
      menuToggle.setAttribute("aria-expanded", "true");
      menuToggle.setAttribute("aria-label", "Close main menu");

      window.requestAnimationFrame(function () {
        mainMenu.classList.add("is-open");
        menuBackdrop.classList.add("is-visible");

        var firstMenuLink = mainMenu.querySelector("a");
        if (firstMenuLink) {
          firstMenuLink.focus();
        } else if (closeMenuButton) {
          closeMenuButton.focus();
        }
      });
    }

    menuToggle.addEventListener("click", function () {
      if (mainMenu.hidden) {
        openMainMenu();
      } else {
        closeMainMenu();
      }
    });
    menuBackdrop.addEventListener("click", closeMainMenu);

    if (closeMenuButton) {
      closeMenuButton.addEventListener("click", closeMainMenu);
    }

    mainMenu
      .querySelectorAll("li.menu-item-has-children")
      .forEach(function (menuItem) {
        var submenu = menuItem.querySelector(":scope > .sub-menu");
        var link = menuItem.querySelector(":scope > a");

        if (!submenu || !link) {
          return;
        }

        var submenuToggle = document.createElement("button");
        submenuToggle.className = "gia-submenu-toggle";
        submenuToggle.type = "button";
        submenuToggle.setAttribute("aria-expanded", "false");
        submenuToggle.setAttribute(
          "aria-label",
          "Show submenu for " + link.textContent.trim(),
        );
        submenuToggle.innerHTML = '<span aria-hidden="true">&#8964;</span>';
        link.after(submenuToggle);

        submenuToggle.addEventListener("click", function () {
          var isExpanded =
            submenuToggle.getAttribute("aria-expanded") === "true";
          submenuToggle.setAttribute("aria-expanded", String(!isExpanded));
          submenuToggle.setAttribute(
            "aria-label",
            (isExpanded ? "Show submenu for " : "Hide submenu for ") +
              link.textContent.trim(),
          );
          menuItem.classList.toggle("is-submenu-open", !isExpanded);
        });
      });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && !mainMenu.hidden) {
        closeMainMenu();
      }
    });
  }
});

document.addEventListener("click", function (event) {
  var button = event.target.closest(".gia-annual-reports__load-more");
  if (!button || !window.giaOtherInsurance) {
    return;
  }

  var wrapper = button.closest(".gia-annual-reports");
  var items = wrapper.querySelector(".gia-annual-reports__items");
  var nextPage = Number(wrapper.dataset.page) + 1;
  var buttonText = button.textContent.trim();

  button.disabled = true;
  button.textContent = "Loading...";
  button.setAttribute("aria-busy", "true");

  var body = new URLSearchParams({
    action: "load_more_annual_reports_ajax",
    category: wrapper.dataset.category,
    page: String(nextPage),
    nonce: window.giaOtherInsurance.annualReportsNonce,
  });

  fetch(window.giaOtherInsurance.ajaxUrl, {
    method: "POST",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
    body: body.toString(),
  })
    .then(function (response) {
      return response.json().then(function (data) {
        if (!response.ok || !data.success) {
          throw new Error(
            data.data && data.data.message
              ? data.data.message
              : "Unable to load more annual reports. Please try again.",
          );
        }
        return data.data;
      });
    })
    .then(function (data) {
      items.insertAdjacentHTML("beforeend", data.html);
      wrapper.dataset.page = String(nextPage);

      var errorMessage = wrapper.querySelector(".gia-annual-reports__error");
      if (errorMessage) {
        errorMessage.remove();
      }

      if (!data.has_more) {
        button.remove();
      }
    })
    .catch(function (error) {
      var message = wrapper.querySelector(".gia-annual-reports__error");
      if (!message) {
        message = document.createElement("p");
        message.className = "gia-annual-reports__error";
        message.setAttribute("role", "alert");
        button.before(message);
      }
      message.textContent = error.message;
    })
    .finally(function () {
      if (button.isConnected) {
        button.disabled = false;
        button.textContent = buttonText;
        button.removeAttribute("aria-busy");
      }
    });
});

document.addEventListener("click", function (event) {
  var target = event.target;
  if (!target || typeof target.closest !== "function") {
    return;
  }

  var toggle = target.closest(".gia-dropdown-link__toggle");
  var dropdown = toggle && toggle.closest("[data-gia-dropdown-link]");

  if (toggle && dropdown) {
    var shouldOpen = toggle.getAttribute("aria-expanded") !== "true";

    document
      .querySelectorAll("[data-gia-dropdown-link].is-open")
      .forEach(function (openDropdown) {
        var openToggle = openDropdown.querySelector(
          ".gia-dropdown-link__toggle",
        );
        var openMenu = openDropdown.querySelector(".gia-dropdown-link__menu");

        openDropdown.classList.remove("is-open");
        if (openToggle) {
          openToggle.setAttribute("aria-expanded", "false");
        }
        if (openMenu) {
          openMenu.hidden = true;
        }
      });

    if (shouldOpen) {
      var menu = dropdown.querySelector(".gia-dropdown-link__menu");
      dropdown.classList.add("is-open");
      toggle.setAttribute("aria-expanded", "true");
      if (menu) {
        menu.hidden = false;
      }
    }

    return;
  }

  document
    .querySelectorAll("[data-gia-dropdown-link].is-open")
    .forEach(function (openDropdown) {
      var menu = openDropdown.querySelector(".gia-dropdown-link__menu");
      var openToggle = openDropdown.querySelector(".gia-dropdown-link__toggle");

      openDropdown.classList.remove("is-open");
      if (openToggle) {
        openToggle.setAttribute("aria-expanded", "false");
      }
      if (menu) {
        menu.hidden = true;
      }
    });
});

document.addEventListener("keydown", function (event) {
  if (event.key !== "Escape") {
    return;
  }

  document
    .querySelectorAll("[data-gia-dropdown-link].is-open")
    .forEach(function (dropdown) {
      var menu = dropdown.querySelector(".gia-dropdown-link__menu");
      var toggle = dropdown.querySelector(".gia-dropdown-link__toggle");

      dropdown.classList.remove("is-open");
      if (menu) {
        menu.hidden = true;
      }
      if (toggle) {
        toggle.setAttribute("aria-expanded", "false");
        toggle.focus();
      }
    });
});

document.addEventListener("click", function (event) {
  var button = event.target.closest(".gia-company-news__load-more");
  if (!button || !window.giaOtherInsurance) {
    return;
  }

  var wrapper = button.closest(".gia-company-news");
  var items = wrapper.querySelector(".gia-company-news__items");
  var nextPage = Number(wrapper.dataset.page) + 1;
  var buttonText = button.textContent.trim();

  button.disabled = true;
  button.textContent = "Loading...";
  button.setAttribute("aria-busy", "true");

  var body = new URLSearchParams({
    action: "load_more_company_news_ajax",
    category: wrapper.dataset.category,
    page: String(nextPage),
    nonce: window.giaOtherInsurance.companyNewsNonce,
  });

  fetch(window.giaOtherInsurance.ajaxUrl, {
    method: "POST",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
    body: body.toString(),
  })
    .then(function (response) {
      return response.json().then(function (data) {
        if (!response.ok || !data.success) {
          throw new Error(
            data.data && data.data.message
              ? data.data.message
              : "Unable to load more company news. Please try again.",
          );
        }
        return data.data;
      });
    })
    .then(function (data) {
      items.insertAdjacentHTML("beforeend", data.html);
      wrapper.dataset.page = String(nextPage);

      var errorMessage = wrapper.querySelector(".gia-company-news__error");
      if (errorMessage) {
        errorMessage.remove();
      }

      if (!data.has_more) {
        button.remove();
      }
    })
    .catch(function (error) {
      var message = wrapper.querySelector(".gia-company-news__error");
      if (!message) {
        message = document.createElement("p");
        message.className = "gia-company-news__error";
        message.setAttribute("role", "alert");
        button.before(message);
      }
      message.textContent = error.message;
    })
    .finally(function () {
      if (button.isConnected) {
        button.disabled = false;
        button.textContent = buttonText;
        button.removeAttribute("aria-busy");
      }
    });
});

document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelectorAll(".gia-company-news-slider")
    .forEach(function (slider) {
      var slides = Array.prototype.slice.call(
        slider.querySelectorAll(".gia-company-news-slider__slide"),
      );
      var counter = slider.querySelector(".gia-company-news-slider__counter");
      var previous = slider.querySelector(
        ".gia-company-news-slider__previous",
      );
      var next = slider.querySelector(".gia-company-news-slider__next");
      var activeIndex = 0;
      var timer = null;
      var interval = Number(slider.dataset.autoplay) || 5000;
      var reduceMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
      ).matches;
      var isHovered = false;
      var hasFocus = false;

      if (slides.length < 2) {
        return;
      }

      function showSlide(index) {
        activeIndex = (index + slides.length) % slides.length;
        slides.forEach(function (slide, slideIndex) {
          var isActive = slideIndex === activeIndex;
          slide.classList.toggle("is-active", isActive);
          slide.hidden = !isActive;
          slide.setAttribute("aria-hidden", String(!isActive));
        });

        if (counter) {
          counter.textContent = String(activeIndex + 1) + "/" + slides.length;
        }
      }

      function stopAutoplay() {
        if (timer) {
          window.clearInterval(timer);
          timer = null;
        }
      }

      function startAutoplay() {
        stopAutoplay();

        if (!reduceMotion && !document.hidden && !isHovered && !hasFocus) {
          timer = window.setInterval(function () {
            showSlide(activeIndex + 1);
          }, interval);
        }
      }

      if (previous) {
        previous.addEventListener("click", function () {
          showSlide(activeIndex - 1);
          startAutoplay();
        });
      }

      if (next) {
        next.addEventListener("click", function () {
          showSlide(activeIndex + 1);
          startAutoplay();
        });
      }

      slider.addEventListener("mouseenter", function () {
        isHovered = true;
        stopAutoplay();
      });
      slider.addEventListener("mouseleave", function () {
        isHovered = false;
        startAutoplay();
      });
      slider.addEventListener("focusin", function () {
        hasFocus = true;
        stopAutoplay();
      });
      slider.addEventListener("focusout", function (event) {
        if (!slider.contains(event.relatedTarget)) {
          hasFocus = false;
          startAutoplay();
        }
      });

      document.addEventListener("visibilitychange", function () {
        if (document.hidden) {
          stopAutoplay();
        } else {
          startAutoplay();
        }
      });

      startAutoplay();
    });
});

document.addEventListener("click", function (event) {
  var button = event.target.closest(".gia-other-insurance__load-more");
  if (!button || !window.giaOtherInsurance) {
    return;
  }

  var wrapper = button.closest(".gia-other-insurance");
  var items = wrapper.querySelector(".gia-other-insurance__items");
  var nextPage = Number(wrapper.dataset.page) + 1;
  var errorMessage = wrapper.querySelector(".gia-other-insurance__error");
  var buttonText = button.textContent;

  button.disabled = true;
  button.textContent = "Loading...";
  button.setAttribute("aria-busy", "true");

  var body = new URLSearchParams({
    action: "load_more_types_of_insurance_ajax",
    category: wrapper.dataset.category,
    page: String(nextPage),
    nonce: window.giaOtherInsurance.nonce,
  });

  fetch(window.giaOtherInsurance.ajaxUrl, {
    method: "POST",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
    body: body.toString(),
  })
    .then(function (response) {
      return response.json().then(function (data) {
        if (!response.ok || !data.success) {
          throw new Error(
            data.data && data.data.message
              ? data.data.message
              : "Unable to load more posts. Please try again.",
          );
        }
        return data.data;
      });
    })
    .then(function (data) {
      items.insertAdjacentHTML("beforeend", data.html);
      wrapper.dataset.page = String(nextPage);

      if (errorMessage) {
        errorMessage.remove();
      }

      if (!data.has_more) {
        button.remove();
      }
    })
    .catch(function (error) {
      var message = wrapper.querySelector(".gia-other-insurance__error");
      if (!message) {
        message = document.createElement("p");
        message.className = "gia-other-insurance__error";
        message.setAttribute("role", "alert");
        button.before(message);
      }
      message.textContent = error.message;
    })
    .finally(function () {
      if (button.isConnected) {
        button.disabled = false;
        button.textContent = buttonText;
        button.removeAttribute("aria-busy");
      }
    });
});

document.addEventListener("click", function (event) {
  var closeButton = event.target.closest(".gia-committee__close");
  if (closeButton) {
    var panel = closeButton.closest(".gia-committee__detail-panel");
    var row = panel.closest(".gia-committee__row");
    var committee = closeButton.closest(".gia-committee");

    panel.hidden = true;
    panel.style.removeProperty("--gia-committee-pointer-x");
    panel.querySelector(".gia-committee__detail-panel-content").replaceChildren();
    row.querySelectorAll(".gia-committee__card").forEach(function (card) {
      card.classList.remove("is-active");
      card.setAttribute("aria-expanded", "false");
    });
    committee.querySelectorAll(".gia-committee__item").forEach(function (item) {
      item.classList.remove("is-muted");
    });
    return;
  }

  var card = event.target.closest(".gia-committee__card");
  if (!card) {
    return;
  }

  var committee = card.closest(".gia-committee");
  var row = card.closest(".gia-committee__row");
  var panel = row.querySelector(".gia-committee__detail-panel");
  var wasActive = card.classList.contains("is-active");

  committee.querySelectorAll(".gia-committee__card").forEach(function (item) {
    item.classList.remove("is-active");
    item.setAttribute("aria-expanded", "false");
  });
  committee.querySelectorAll(".gia-committee__item").forEach(function (item) {
    item.classList.remove("is-muted");
  });
  committee.querySelectorAll(".gia-committee__detail-panel").forEach(function (item) {
    item.hidden = true;
    item.querySelector(".gia-committee__detail-panel-content").replaceChildren();
  });

  if (wasActive) {
    return;
  }

  card.classList.add("is-active");
  card.setAttribute("aria-expanded", "true");
  committee.querySelectorAll(".gia-committee__item").forEach(function (item) {
    if (item !== card.parentElement) {
      item.classList.add("is-muted");
    }
  });
  var detail = card.parentElement.querySelector(".gia-committee__detail-source");
  panel.querySelector(".gia-committee__detail-panel-content").replaceChildren(
    detail.content.cloneNode(true),
  );
  panel.hidden = false;
  if (
    committee.closest("#member-directory") ||
    committee.classList.contains("gia-committee--management")
  ) {
    var cardRect = card.getBoundingClientRect();
    var panelRect = panel.getBoundingClientRect();
    panel.style.setProperty(
      "--gia-committee-pointer-x",
      cardRect.left + cardRect.width / 2 - panelRect.left + "px",
    );
  }
});

document.addEventListener("click", function (event) {
  var button = event.target.closest(".gia-committee__load-more");
  if (!button || !window.giaOtherInsurance) {
    return;
  }

  var committee = button.closest(".gia-committee");
  var nextPage = Number(committee.dataset.page) + 1;
  var buttonText = button.textContent;
  var errorMessage = committee.querySelector(".gia-committee__error");

  button.disabled = true;
  button.textContent = "Loading...";
  button.setAttribute("aria-busy", "true");

  var body = new URLSearchParams({
    action: "load_more_management_committee_ajax",
    category: committee.dataset.category,
    limit: committee.dataset.limit,
    page: String(nextPage),
    nonce: window.giaOtherInsurance.managementCommitteeNonce,
  });

  fetch(window.giaOtherInsurance.ajaxUrl, {
    method: "POST",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    },
    body: body.toString(),
  })
    .then(function (response) {
      return response.json().then(function (data) {
        if (!response.ok || !data.success) {
          throw new Error(
            data.data && data.data.message
              ? data.data.message
              : "Unable to load more committee members. Please try again.",
          );
        }
        return data.data;
      });
    })
    .then(function (data) {
      committee.querySelector(".gia-committee__rows").insertAdjacentHTML("beforeend", data.html);
      committee.dataset.page = String(nextPage);

      if (errorMessage) {
        errorMessage.remove();
      }

      if (!data.has_more) {
        button.remove();
      }
    })
    .catch(function (error) {
      var message = committee.querySelector(".gia-committee__error");
      if (!message) {
        message = document.createElement("p");
        message.className = "gia-committee__error";
        message.setAttribute("role", "alert");
        button.before(message);
      }
      message.textContent = error.message;
    })
    .finally(function () {
      if (button.isConnected) {
        button.disabled = false;
        button.textContent = buttonText;
        button.removeAttribute("aria-busy");
      }
    });
});

/* GIA header: desktop/mobile navigation, submenu icons and sticky header. */
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

      var button = item.querySelector(":scope > .gia-nav__submenu-toggle");
      if (!button) {
        button = document.createElement("button");
        button.innerHTML = mobile
          ? '<span aria-hidden="true">&#8964;</span>'
          : '<i class="fal fa-angle-down" aria-hidden="true"></i>';
        link.after(button);
      }

      button.className = mobile
        ? "gia-nav__submenu-toggle gia-nav__submenu-toggle--mobile"
        : "gia-nav__submenu-toggle";
      button.type = "button";
      button.setAttribute("aria-expanded", "false");
      button.setAttribute("aria-label", "Toggle submenu for " + link.textContent.trim());
      if (!submenu.id) {
        submenu.id = (mobile ? "gia-nav-mobile-submenu-" : "gia-nav-desktop-submenu-") + index;
      }
      button.setAttribute("aria-controls", submenu.id);

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

/* Admin menu icon picker: upload and remove custom menu item icons. */
(function ($) {
  "use strict";

  $(document).on("click", ".gia-menu-icon-upload", function (event) {
    event.preventDefault();

    var field = $(this).closest(".gia-menu-icon-field");
    var input = field.find(".edit-menu-item-gia-icon");
    var preview = field.find(".gia-menu-icon-preview");
    var removeButton = field.find(".gia-menu-icon-remove");
    var frame = wp.media({
      title: "Choose a menu icon",
      button: {
        text: "Use this image",
      },
      library: {
        type: "image",
      },
      multiple: false,
    });

    frame.on("select", function () {
      var attachment = frame.state().get("selection").first().toJSON();
      var imageUrl = attachment.sizes && attachment.sizes.thumbnail
        ? attachment.sizes.thumbnail.url
        : attachment.url;
      var image = $("<img>", {
        src: imageUrl,
        alt: "",
        width: 36,
        height: 36,
      });

      input.val(attachment.id);
      preview.empty().append(image).prop("hidden", false);
      removeButton.prop("hidden", false);
    });

    frame.open();
  });

  $(document).on("click", ".gia-menu-icon-remove", function (event) {
    event.preventDefault();

    var field = $(this).closest(".gia-menu-icon-field");
    field.find(".edit-menu-item-gia-icon").val("");
    field.find(".gia-menu-icon-preview").empty().prop("hidden", true);
    $(this).prop("hidden", true);
  });
})(jQuery);
