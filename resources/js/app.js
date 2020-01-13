import "alpinejs";

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

function windowWidthCollapse() {
    if (window.innerWidth < 768) {
        document.body.classList.add("sidebar-open");
        document.body.classList.remove("sidebar-collapse");
    }
}

window.windowWidthCollapse = windowWidthCollapse;
