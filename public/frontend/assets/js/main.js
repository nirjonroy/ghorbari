document.addEventListener("DOMContentLoaded", function () {
  var root = document.documentElement;
  var themeToggles = document.querySelectorAll(".theme-toggle");
  var savedTheme = localStorage.getItem("landsite-theme");
  var defaultTheme = root.getAttribute("data-default-theme") || "light";
  var currentTheme = savedTheme || defaultTheme;

  var setTheme = function (theme) {
    root.setAttribute("data-theme", theme);
    if (!themeToggles.length) {
      return;
    }

    var isDark = theme === "dark";
    themeToggles.forEach(function (themeToggle) {
      themeToggle.setAttribute("aria-pressed", isDark ? "true" : "false");
      themeToggle.innerHTML = isDark
        ? '<i class="bi bi-sun"></i><span>Light</span>'
        : '<i class="bi bi-moon"></i><span>Dark</span>';
    });
  };

  setTheme(currentTheme);

  if (themeToggles.length) {
    themeToggles.forEach(function (themeToggle) {
      themeToggle.addEventListener("click", function () {
        var nextTheme = root.getAttribute("data-theme") === "dark" ? "light" : "dark";
        localStorage.setItem("landsite-theme", nextTheme);
        setTheme(nextTheme);
      });
    });
  }

  var dashboardMenuLinks = document.querySelectorAll(".dashboard-menu a[href^='#']");

  if (dashboardMenuLinks.length) {
    var setDashboardActive = function () {
      var currentHash = window.location.hash || "#dashboard";

      dashboardMenuLinks.forEach(function (link) {
        link.classList.toggle("active", link.getAttribute("href") === currentHash);
      });
    };

    dashboardMenuLinks.forEach(function (link) {
      link.addEventListener("click", function () {
        dashboardMenuLinks.forEach(function (item) {
          item.classList.remove("active");
        });
        link.classList.add("active");
      });
    });

    window.addEventListener("hashchange", setDashboardActive);
    setDashboardActive();
  }

  if (window.jQuery && jQuery.fn.owlCarousel) {
    var bindDraggableCardLinks = function ($carousel, cardSelector) {
      var didDrag = false;

      $carousel.on("drag.owl.carousel", function () {
        didDrag = true;
        $carousel.addClass("is-dragging");
      });

      $carousel.on("dragged.owl.carousel translated.owl.carousel", function () {
        window.setTimeout(function () {
          didDrag = false;
          $carousel.removeClass("is-dragging");
        }, 120);
      });

      $carousel.on("click", cardSelector, function (event) {
        if (didDrag || $carousel.hasClass("is-dragging")) {
          event.preventDefault();
          return;
        }

        if (event.target.closest("button, a")) {
          return;
        }

        var href = this.querySelector(".card-link-fill") && this.querySelector(".card-link-fill").getAttribute("href");

        if (href) {
          window.location.href = href;
        }
      });

      $carousel.on("keydown", cardSelector, function (event) {
        if (event.key !== "Enter" && event.key !== " ") {
          return;
        }

        var href = this.querySelector(".card-link-fill") && this.querySelector(".card-link-fill").getAttribute("href");

        if (href) {
          event.preventDefault();
          window.location.href = href;
        }
      });
    };

    var $earlyCarousel = jQuery(".early-carousel");

    if ($earlyCarousel.length) {
      $earlyCarousel.owlCarousel({
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 3500,
        dots: true,
        loop: false,
        margin: 22,
        mouseDrag: true,
        nav: false,
        pullDrag: true,
        rewind: true,
        smartSpeed: 420,
        touchDrag: true,
        responsive: {
          0: { items: 1 },
          768: { items: 2 },
          1200: { items: 3 }
        }
      });

      bindDraggableCardLinks($earlyCarousel, ".property-card");

      jQuery(".early-prev").on("click", function () {
        $earlyCarousel.trigger("prev.owl.carousel");
      });

      jQuery(".early-next").on("click", function () {
        $earlyCarousel.trigger("next.owl.carousel");
      });
    }

    var $featuredCarousel = jQuery(".featured-carousel");

    if ($featuredCarousel.length) {
      $featuredCarousel.owlCarousel({
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 3200,
        dots: true,
        loop: false,
        margin: 22,
        mouseDrag: true,
        nav: false,
        pullDrag: true,
        rewind: true,
        smartSpeed: 420,
        touchDrag: true,
        responsive: {
          0: { items: 1 },
          768: { items: 2 },
          1200: { items: 3 }
        }
      });

      bindDraggableCardLinks($featuredCarousel, ".featured-card");

      jQuery(".featured-prev").on("click", function () {
        $featuredCarousel.trigger("prev.owl.carousel");
      });

      jQuery(".featured-next").on("click", function () {
        $featuredCarousel.trigger("next.owl.carousel");
      });
    }

    var $clientsCarousel = jQuery(".clients-carousel");

    if ($clientsCarousel.length) {
      $clientsCarousel.owlCarousel({
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 3000,
        dots: true,
        loop: false,
        margin: 24,
        nav: false,
        rewind: true,
        responsive: {
          0: { items: 1 },
          992: { items: 2 },
          1600: { items: 3 }
        }
      });
    }
  }

  var calculator = document.getElementById("paymentCalculator");

  if (!calculator) {
    return;
  }

  var defaults = {
    homePrice: 73500000,
    downPercent: 20,
    loanYears: 20,
    interestRate: 9.5,
    taxRate: 0.6,
    serviceCharge: 15000
  };

  if (calculator.dataset.defaults) {
    try {
      defaults = Object.assign(defaults, JSON.parse(calculator.dataset.defaults));
    } catch (error) {
      defaults = defaults;
    }
  }

  var fields = {
    homePrice: document.getElementById("homePrice"),
    homePriceRange: document.getElementById("homePriceRange"),
    downPayment: document.getElementById("downPayment"),
    downPaymentRange: document.getElementById("downPaymentRange"),
    downPercent: document.getElementById("downPercent"),
    loanYears: document.getElementById("loanYears"),
    interestRate: document.getElementById("interestRate"),
    taxRate: document.getElementById("taxRate"),
    serviceCharge: document.getElementById("serviceCharge"),
    monthlyPayment: document.getElementById("monthlyPayment"),
    principalValue: document.getElementById("principalValue"),
    taxValue: document.getElementById("taxValue"),
    serviceValue: document.getElementById("serviceValue"),
    loanAmount: document.getElementById("loanAmount"),
    principalBar: document.getElementById("principalBar"),
    taxBar: document.getElementById("taxBar"),
    serviceBar: document.getElementById("serviceBar"),
    reset: document.getElementById("resetCalculator")
  };

  var formatBDT = function (value) {
    return "BDT " + Math.round(value).toLocaleString("en-BD");
  };

  var calculatePayment = function () {
    var homePrice = Number(fields.homePrice.value) || 0;
    var downPayment = Number(fields.downPayment.value) || 0;
    var years = Number(fields.loanYears.value) || 20;
    var annualRate = Number(fields.interestRate.value) || 0;
    var taxRate = Number(fields.taxRate.value) || 0;
    var serviceCharge = Number(fields.serviceCharge.value) || 0;
    var paymentAmount = Math.max(homePrice - downPayment, 0);
    var months = years * 12;
    var monthlyRate = annualRate / 100 / 12;
    var principal = monthlyRate
      ? paymentAmount * (monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1)
      : paymentAmount / months;
    var taxes = homePrice * (taxRate / 100) / 12;
    var total = principal + taxes + serviceCharge;
    var downPercent = homePrice ? Math.round((downPayment / homePrice) * 100) : 0;

    fields.downPercent.textContent = downPercent + "%";
    fields.monthlyPayment.textContent = formatBDT(total);
    fields.principalValue.textContent = formatBDT(principal);
    fields.taxValue.textContent = formatBDT(taxes);
    fields.serviceValue.textContent = formatBDT(serviceCharge);
    fields.loanAmount.textContent = formatBDT(paymentAmount);
    fields.principalBar.style.width = total ? (principal / total * 100) + "%" : "0";
    fields.taxBar.style.width = total ? (taxes / total * 100) + "%" : "0";
    fields.serviceBar.style.width = total ? (serviceCharge / total * 100) + "%" : "0";
  };

  fields.homePrice.addEventListener("input", function () {
    fields.homePriceRange.value = fields.homePrice.value;
    fields.downPayment.value = Math.round((Number(fields.homePrice.value) || 0) * (Number(fields.downPaymentRange.value) || 0) / 100);
    calculatePayment();
  });

  fields.homePriceRange.addEventListener("input", function () {
    fields.homePrice.value = fields.homePriceRange.value;
    fields.downPayment.value = Math.round((Number(fields.homePrice.value) || 0) * (Number(fields.downPaymentRange.value) || 0) / 100);
    calculatePayment();
  });

  fields.downPayment.addEventListener("input", calculatePayment);

  fields.downPaymentRange.addEventListener("input", function () {
    fields.downPayment.value = Math.round((Number(fields.homePrice.value) || 0) * Number(fields.downPaymentRange.value) / 100);
    calculatePayment();
  });

  [fields.loanYears, fields.interestRate, fields.taxRate, fields.serviceCharge].forEach(function (field) {
    field.addEventListener("input", calculatePayment);
  });

  fields.reset.addEventListener("click", function () {
    fields.homePrice.value = defaults.homePrice;
    fields.homePriceRange.value = defaults.homePrice;
    fields.downPaymentRange.value = defaults.downPercent;
    fields.downPayment.value = Math.round(defaults.homePrice * defaults.downPercent / 100);
    fields.loanYears.value = defaults.loanYears;
    fields.interestRate.value = defaults.interestRate;
    fields.taxRate.value = defaults.taxRate;
    fields.serviceCharge.value = defaults.serviceCharge;
    calculatePayment();
  });

  calculatePayment();
});

document.addEventListener("DOMContentLoaded", function () {
  var photoCarousel = document.getElementById("detailPhotoCarousel");
  if (!photoCarousel) {
    return;
  }

  var thumbs = Array.prototype.slice.call(document.querySelectorAll(".photo-thumb-row button"));
  photoCarousel.addEventListener("slid.bs.carousel", function (event) {
    thumbs.forEach(function (thumb, index) {
      thumb.classList.toggle("active", index === event.to);
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var mapElement = document.getElementById("bdResultsMap");
  if (!mapElement || typeof L === "undefined") {
    return;
  }

  var properties = window.landsiteResultsMapProperties || [];

  var map = L.map(mapElement, {
    center: [23.7937, 90.4066],
    zoom: 12,
    zoomControl: true,
    scrollWheelZoom: true
  });

  window.addEventListener("resize", function () {
    map.invalidateSize();
  });

  var streetLayer = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap contributors"
  });

  var satelliteLayer = L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
    maxZoom: 19,
    attribution: "Tiles &copy; Esri"
  });

  streetLayer.addTo(map);

  var markerBounds = [];

  properties.forEach(function (property) {
    var label = property.is_search_location ? property.price : "BDT " + property.price;
    var icon = L.divIcon({
      className: "leaflet-price-marker" + (property.active ? " is-active" : ""),
      html: label,
      iconSize: null,
      iconAnchor: [34, 14]
    });

    markerBounds.push([property.lat, property.lng]);

    L.marker([property.lat, property.lng], { icon: icon })
      .addTo(map)
      .bindPopup(property.title + "<br>" + label);
  });

  if (markerBounds.length) {
    map.fitBounds(markerBounds, { padding: [32, 32], maxZoom: 13 });
  }

  var drawing = false;
  var drawPoints = [];
  var drawLayer = null;
  var drawMarkers = [];
  var drawButton = document.querySelector(".map-draw-toggle");
  var layerButton = document.querySelector(".map-layer-toggle");
  var resetButton = document.querySelector(".map-reset");
  var removeButton = document.querySelector(".remove-outline");
  var usingSatellite = false;

  var clearDrawing = function () {
    drawPoints = [];
    if (drawLayer) {
      map.removeLayer(drawLayer);
      drawLayer = null;
    }
    drawMarkers.forEach(function (marker) {
      map.removeLayer(marker);
    });
    drawMarkers = [];
  };

  var stopDrawing = function () {
    drawing = false;
    mapElement.classList.remove("is-drawing");
    if (drawButton) {
      drawButton.classList.remove("is-active");
    }
    map.doubleClickZoom.enable();
  };

  if (drawButton) {
    drawButton.addEventListener("click", function () {
      drawing = !drawing;
      drawButton.classList.toggle("is-active", drawing);
      mapElement.classList.toggle("is-drawing", drawing);
      if (drawing) {
        clearDrawing();
        map.doubleClickZoom.disable();
      } else {
        map.doubleClickZoom.enable();
      }
    });
  }

  map.on("click", function (event) {
    if (!drawing) {
      return;
    }

    drawPoints.push(event.latlng);
    drawMarkers.push(L.circleMarker(event.latlng, {
      radius: 5,
      color: "#e03445",
      fillColor: "#e03445",
      fillOpacity: 1
    }).addTo(map));

    if (drawLayer) {
      map.removeLayer(drawLayer);
    }

    drawLayer = drawPoints.length > 2
      ? L.polygon(drawPoints, { color: "#e03445", weight: 3, fillOpacity: .12 }).addTo(map)
      : L.polyline(drawPoints, { color: "#e03445", weight: 3 }).addTo(map);
  });

  map.on("dblclick", function () {
    if (drawing) {
      stopDrawing();
    }
  });

  if (removeButton) {
    removeButton.addEventListener("click", function () {
      clearDrawing();
      stopDrawing();
    });
  }

  if (layerButton) {
    layerButton.addEventListener("click", function () {
      usingSatellite = !usingSatellite;
      if (usingSatellite) {
        map.removeLayer(streetLayer);
        satelliteLayer.addTo(map);
      } else {
        map.removeLayer(satelliteLayer);
        streetLayer.addTo(map);
      }
      layerButton.classList.toggle("is-active", usingSatellite);
    });
  }

  if (resetButton) {
    resetButton.addEventListener("click", function () {
      if (markerBounds.length) {
        map.fitBounds(markerBounds, { padding: [32, 32], maxZoom: 13 });
      } else {
        map.setView([23.7937, 90.4066], 12);
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  var saveSearchButton = document.querySelector(".save-search[data-save-search-url]");
  if (saveSearchButton) {
    saveSearchButton.addEventListener("click", function () {
      localStorage.setItem("landsite-saved-search", saveSearchButton.getAttribute("data-save-search-url"));
      saveSearchButton.textContent = "Saved";
    });
  }

  var layoutToggle = document.querySelector("[data-layout-toggle]");
  if (layoutToggle) {
    layoutToggle.addEventListener("click", function () {
      document.body.classList.toggle("results-map-collapsed");
      setTimeout(function () {
        window.dispatchEvent(new Event("resize"));
      }, 100);
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  var searchTabs = document.getElementById("searchTabs");
  if (!searchTabs) {
    return;
  }

  var panel = searchTabs.closest(".search-panel");
  if (!panel) {
    return;
  }

  searchTabs.addEventListener("hide.bs.tab", function () {
    var activePane = panel.querySelector(".tab-pane.active");
    if (activePane) {
      panel.style.setProperty("--active-search-height", activePane.offsetHeight + "px");
    }
    panel.classList.add("is-switching-tabs");
  });

  searchTabs.addEventListener("shown.bs.tab", function () {
    panel.classList.remove("is-switching-tabs");
    panel.style.removeProperty("--active-search-height");
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var searchTabs = document.getElementById("searchTabs");
  var advancedForm = document.querySelector(".js-advanced-search-form");
  var advancedLocationInput = document.querySelector(".js-advanced-location-input");
  var postcodeInput = document.getElementById("zipCode");
  var currentLocationButton = document.querySelector(".js-current-location");

  if (!searchTabs || !advancedForm) {
    return;
  }

  var activePurpose = function () {
    var activePane = document.querySelector(".tab-pane.active .js-home-search-form");
    return activePane ? activePane.getAttribute("data-search-purpose") : "buy";
  };

  var syncAdvancedForm = function () {
    var purpose = activePurpose();
    var action = advancedForm.getAttribute("data-" + purpose + "-action");
    var activeInput = document.querySelector(".tab-pane.active .js-home-search-input");

    if (action) {
      advancedForm.setAttribute("action", action);
    }

    if (advancedLocationInput && activeInput && !advancedLocationInput.value) {
      advancedLocationInput.value = activeInput.value;
    }
  };

  searchTabs.addEventListener("shown.bs.tab", syncAdvancedForm);

  document.querySelectorAll(".js-home-search-input").forEach(function (input) {
    input.addEventListener("input", function () {
      if (input.closest(".tab-pane.active") && advancedLocationInput) {
        advancedLocationInput.value = input.value;
      }
    });
  });

  var submitAdvancedSearch = function () {
    syncAdvancedForm();

    if (advancedForm.requestSubmit) {
      advancedForm.requestSubmit();
      return;
    }

    advancedForm.submit();
  };

  var compactParts = function (parts) {
    return parts.filter(Boolean).filter(function (part, index, source) {
      return source.indexOf(part) === index;
    }).join(", ");
  };

  var locationLabelFromAddress = function (address) {
    if (!address) {
      return "";
    }

    var localArea = address.suburb
      || address.quarter
      || address.residential
      || address.city_district
      || address.neighbourhood;
    var city = address.city || address.town || address.municipality || address.county;
    var district = address.state_district || address.state;
    var isDhaka = [city, district, address.state].filter(Boolean).join(" ").toLowerCase().indexOf("dhaka") !== -1;
    var road = (address.road || "").toLowerCase();
    var smallArea = [localArea, address.neighbourhood, address.suburb, address.quarter, address.residential].filter(Boolean).join(" ").toLowerCase();

    if (isDhaka && (road.indexOf("satmasjid") !== -1 || address.postcode === "1205" || smallArea.indexOf("jigatola") !== -1 || smallArea.indexOf("staff quarter") !== -1)) {
      return "Dhanmondi";
    }

    if (address.road) {
      return localArea || city || address.road;
    }

    return compactParts([
      localArea,
      address.suburb,
      address.quarter,
      address.residential,
      address.village,
      address.town,
      address.city,
      district,
    ]);
  };

  if (currentLocationButton && navigator.geolocation) {
    currentLocationButton.addEventListener("click", function () {
      currentLocationButton.disabled = true;
      navigator.geolocation.getCurrentPosition(function (position) {
        var latitude = position.coords.latitude.toFixed(6);
        var longitude = position.coords.longitude.toFixed(6);
        var coordinates = latitude + "," + longitude;
        var reverseUrl = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + latitude + "&lon=" + longitude;

        fetch(reverseUrl, { headers: { "Accept": "application/json" } })
          .then(function (response) {
            return response.ok ? response.json() : null;
          })
          .then(function (data) {
            var address = data ? data.address : null;
            var locationName = locationLabelFromAddress(address);

            if (advancedLocationInput) {
              advancedLocationInput.value = locationName || coordinates;
            }

            if (postcodeInput && address && address.postcode) {
              postcodeInput.value = address.postcode;
            }

            submitAdvancedSearch();
          })
          .catch(function () {
            if (advancedLocationInput) {
              advancedLocationInput.value = coordinates;
            }

            submitAdvancedSearch();
          })
          .finally(function () {
            currentLocationButton.disabled = false;
          });
      }, function () {
        currentLocationButton.disabled = false;
      });
    });
  }

  syncAdvancedForm();
});

document.addEventListener("DOMContentLoaded", function () {
  var csrfToken = document.querySelector('meta[name="csrf-token"]');
  var token = csrfToken ? csrfToken.getAttribute("content") : "";

  document.querySelectorAll("[data-tour-options]").forEach(function (group) {
    var form = group.closest(".contact-card") ? group.closest(".contact-card").querySelector("[data-tour-request-form]") : null;
    var input = form ? form.querySelector("[data-tour-type-input]") : null;
    var message = form ? form.querySelector('textarea[name="message"]') : null;

    group.querySelectorAll("[data-tour-option]").forEach(function (button) {
      button.addEventListener("click", function () {
        var tourType = button.getAttribute("data-tour-option") || "in_person";
        group.querySelectorAll("[data-tour-option]").forEach(function (option) {
          option.classList.toggle("active", option === button);
        });

        if (input) {
          input.value = tourType;
        }

        if (message && !message.dataset.userEdited) {
          message.placeholder = tourType === "video_chat"
            ? "I'm interested in a video tour of this property."
            : "I'm interested in touring this property.";
        }
      });
    });

    if (message) {
      message.addEventListener("input", function () {
        message.dataset.userEdited = "true";
      });
    }
  });

  document.querySelectorAll(".js-share-property").forEach(function (button) {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      event.stopPropagation();

      var shareUrl = button.getAttribute("data-share-url") || window.location.href;
      var shareTitle = button.getAttribute("data-share-title") || document.title;

      if (navigator.share) {
        navigator.share({ title: shareTitle, url: shareUrl }).catch(function () {});
        return;
      }

      if (navigator.clipboard) {
        navigator.clipboard.writeText(shareUrl).then(function () {
          var originalLabel = button.getAttribute("aria-label") || "Share";
          button.setAttribute("aria-label", "Link copied");
          button.classList.add("is-shared");
          setTimeout(function () {
            button.setAttribute("aria-label", originalLabel);
            button.classList.remove("is-shared");
          }, 1600);
        });
      }
    });
  });

  document.querySelectorAll(".js-favorite-property").forEach(function (button) {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      event.stopPropagation();

      var favoriteUrl = button.getAttribute("data-favorite-url");
      var loginUrl = button.getAttribute("data-login-url");

      if (!favoriteUrl || button.disabled) {
        return;
      }

      button.disabled = true;

      fetch(favoriteUrl, {
        method: "POST",
        headers: {
          "Accept": "application/json",
          "X-CSRF-TOKEN": token
        }
      })
        .then(function (response) {
          if (response.status === 401 || response.status === 419) {
            window.location.href = loginUrl || "/login";
            return null;
          }

          return response.json();
        })
        .then(function (data) {
          if (!data) {
            return;
          }

          var active = !!data.favorited;
          var icon = button.querySelector("i");
          var label = button.querySelector("span");

          button.classList.toggle("is-favorite", active);
          button.setAttribute("aria-pressed", active ? "true" : "false");
          button.setAttribute("aria-label", active ? "Remove from favorites" : "Save listing");

          if (icon) {
            icon.classList.toggle("bi-heart", !active);
            icon.classList.toggle("bi-heart-fill", active);
          }

          if (label) {
            label.textContent = active ? "Saved" : "Save";
          }
        })
        .finally(function () {
          button.disabled = false;
        });
    });
  });
});
