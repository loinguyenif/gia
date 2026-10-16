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
