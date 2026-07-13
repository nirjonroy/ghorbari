document.addEventListener("DOMContentLoaded", function () {
  var root = document.documentElement;
  var themeToggle = document.querySelector(".theme-toggle");
  var savedTheme = localStorage.getItem("landsite-theme");
  var preferredTheme = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
  var currentTheme = savedTheme || preferredTheme;

  var setTheme = function (theme) {
    root.setAttribute("data-theme", theme);
    if (!themeToggle) {
      return;
    }

    var isDark = theme === "dark";
    themeToggle.setAttribute("aria-pressed", isDark ? "true" : "false");
    themeToggle.innerHTML = isDark
      ? '<i class="bi bi-sun"></i><span>Light</span>'
      : '<i class="bi bi-moon"></i><span>Dark</span>';
  };

  setTheme(currentTheme);

  if (themeToggle) {
    themeToggle.addEventListener("click", function () {
      var nextTheme = root.getAttribute("data-theme") === "dark" ? "light" : "dark";
      localStorage.setItem("landsite-theme", nextTheme);
      setTheme(nextTheme);
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

  var properties = [
    { title: "Banani Modern Residence", price: "7.35 Cr", lat: 23.7948, lng: 90.4043, active: true },
    { title: "Gulshan Luxury Apartment", price: "8.2 Cr", lat: 23.7925, lng: 90.4143 },
    { title: "Bashundhara Ready Flat", price: "4.89 Cr", lat: 23.8196, lng: 90.4520 },
    { title: "Dhanmondi Family Home", price: "2.4 Cr", lat: 23.7465, lng: 90.3760 },
    { title: "Uttara Ready Apartment", price: "1.65 Cr", lat: 23.8759, lng: 90.3795 },
    { title: "Mirpur Lake View", price: "3.25 Cr", lat: 23.8041, lng: 90.3667 },
    { title: "Bashundhara Plot", price: "5.9 Cr", lat: 23.8132, lng: 90.4312 }
  ];

  var map = L.map(mapElement, {
    center: [23.7937, 90.4066],
    zoom: 12,
    zoomControl: true,
    scrollWheelZoom: true
  });

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap contributors"
  }).addTo(map);

  properties.forEach(function (property) {
    var icon = L.divIcon({
      className: "leaflet-price-marker" + (property.active ? " is-active" : ""),
      html: "BDT " + property.price,
      iconSize: null,
      iconAnchor: [34, 14]
    });

    L.marker([property.lat, property.lng], { icon: icon })
      .addTo(map)
      .bindPopup(property.title + "<br>BDT " + property.price);
  });

  [
    [23.7819, 90.4005],
    [23.8067, 90.4156],
    [23.7722, 90.3654],
    [23.8376, 90.3847]
  ].forEach(function (coords) {
    L.marker(coords, {
      icon: L.divIcon({
        className: "leaflet-dot-marker",
        iconSize: [16, 16],
        iconAnchor: [8, 8]
      })
    }).addTo(map);
  });
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
