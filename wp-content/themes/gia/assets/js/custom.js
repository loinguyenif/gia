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
