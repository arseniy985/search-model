import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import { get, post } from "./http.js";
import { initSearchSuggestions } from './components/search.js';

Alpine.plugin(collapse)

window.Alpine = Alpine;

// Инициализируем подсказки поиска после загрузки документа
document.addEventListener('DOMContentLoaded', function() {
    initSearchSuggestions();
});

document.addEventListener("alpine:init", async () => {

    Alpine.data("toast", () => ({
        visible: false,
        timeout: null,
        percent: 0,
        interval: null,
        message: null,
        show(message) {
            this.visible = true;
            this.message = message;

            if (this.timeout) {
                clearTimeout(this.timeout);
                this.timeout = null;
            }
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }

            this.timeout = setTimeout(() => {
                this.hide();
            }, 5000);

            this.percent = 0;
            const startDate = Date.now();
            const endDate = startDate + 5000;
            this.interval = setInterval(() => {
                const date = Date.now();
                this.percent = ((date - startDate) * 100) / (endDate - startDate);
                if (this.percent >= 100) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            }, 30);
        },
        hide() {
            this.visible = false;
            if (this.timeout) {
                clearTimeout(this.timeout);
                this.timeout = null;
            }
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
        },
        close() {
            this.hide();
        }
    }));

    Alpine.data("productItem", (product) => {
        return {
            product,
            addToCart(quantity = 1) {
                post(this.product.addToCartUrl, { quantity })
                    .then(result => {
                        this.$dispatch('cart-change', { count: result.count })
                        this.$dispatch("notify", {
                            message: "The item was added into the cart",
                        });
                    })
                    .catch(response => {
                        console.log(response);
                    })
            },
            removeItemFromCart() {
                post(this.product.removeUrl)
                    .then(result => {
                        this.$dispatch("notify", {
                            message: "The item was removed from cart",
                        });
                        this.$dispatch('cart-change', { count: result.count })
                        this.cartItems = this.cartItems.filter(p => p.id !== product.id)
                    })
            },
            changeQuantity() {
                post(this.product.updateQuantityUrl, { quantity: product.quantity })
                    .then(result => {
                        this.$dispatch('cart-change', { count: result.count })
                        this.$dispatch("notify", {
                            message: "The item quantity was updated",
                        });
                    })
            },
        };
    });
});


Alpine.start();