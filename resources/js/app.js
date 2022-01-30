import Alpine from 'alpinejs'

window.Alpine = Alpine

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.nav = {
    width: 990,
    make() {
        return {
            collapsed: function () {
                if (window.innerWidth < this.width) {
                    return true;
                }

                return false;
            },
            resize() {
                if (window.innerWidth < this.width) {
                    this.collapsed = true;
                }
            },
            click() {
                this.collapsed = !this.collapsed;
                if (!this.collapsed) {
                    this.$refs.body.classList.add("sidebar-open");
                } else {
                    this.$refs.body.classList.remove("sidebar-open");
                }
            },
            clickAway() {
                if (window.innerWidth < this.width) {
                    this.$refs.body.classList.remove("sidebar-open");
                    this.collapsed = true;
                }
            }
        };
    },
};

window.permissions = () => {
    return {
        checkAll(id, css) {
            document.getElementById(id).checked = Array.from(document.querySelectorAll('.' + css)).every((element) => {
                return element.checked === true
            });
        },
        reCheckedSelectAll(el, css) {
            let event = new Event('change');
            Array.from(document.querySelectorAll('.' + css)).forEach((element) => {
                element.checked = el.checked
                element.dispatchEvent(event);
            });
        }
    }
};

Alpine.start()