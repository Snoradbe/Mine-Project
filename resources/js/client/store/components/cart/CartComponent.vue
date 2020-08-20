<template>
    <div class="section-store section-cart space-header">
        <div class="container section-store__container">
            <transition name="item-modal">
                <div class="section-cart-head" @click="error = null" v-if="error">
                    <alert :error="error"></alert>
                </div>
            </transition>
            <div class="section-store__head section-cart-head" :data-z="rnd">
                <h2 class="section-store__title section-store__title_cart">{{Lang.cart.title}}</h2>
                <a :href="settings.store_url" class="section-store__back" v-if="items.length > 0"><span class="material-icons">arrow_back</span> {{Lang.cart.back}}</a>
            </div>
            <div class="section-store__content" v-if="items.length > 0">
                <div class="section-store__left">
                    <div class="cart section-store__cart">
                        <div class="cart__items" v-if="items.length > 0">
                            <item :item="item" :key="item.id" @update="selectCartItem" @delete="deleteItem" v-for="item in items"></item>
                        </div>
                    </div>
					<div class="cart-recommended" v-if="recommended.length > 0">
						<h1 class="cart-recommended__title">{{Lang.cart.recommended}}</h1>
						<div class="cart-recommended__products">
							<store-item :product="product" :active="selectedItem === product" :disabled="false" :key="product.id" @open="selectItem" v-for="product in recommended"></store-item>
						</div>
					</div>
                </div>
                <div class="section-store__right">
                    <div class="store-menu">
                        <div class="cart-total store-menu__item">
                            <div class="cart-total__coin"></div>
                            <div class="cart-total__total">
                                <h6 class="cart-total__total-title">{{Lang.cart.total.title}}</h6>
                                <span class="cart-total__total-amount">{{totalCost}} {{declOfNum(totalCost, ['coin', 'coins'])}}</span>
                            </div>
                            <button class="cart-total__pay btn btn_warning btn_warning-outline" @click.prevent="pay">{{Lang.cart.total.pay}}</button>
                        </div>

                        <div class="cart-info store-menu__item">
                            <a :href="settings.coins_url" class="cart-info__get-coins btn btn_warning">{{Lang.cart.more_coins}}</a>
                            <h1 class="cart-info__promo-title">{{Lang.cart.promo.title}}</h1>
                            <a href="#" class="cart-info__promo-link" @click.prevent="promoModal = true">{{Lang.cart.promo.link}}</a>

                            <h6 class="cart-info__how-title">{{Lang.cart.how.title}}</h6>
                            <p class="cart-info__how-text">{{Lang.cart.how.text}}</p>
                        </div>
                    </div>
                </div>
            </div>
			<div v-else>
				<div class="cart-empty">
					<h2 class="cart-empty__title">{{Lang.cart.empty.title}}</h2>
					<h4 class="cart-empty__subtitle">{{Lang.cart.empty.subtitle}}</h4>
					<a :href="settings.store_url" class="cart-empty__back section-store__back"><span class="material-icons">arrow_back</span> {{Lang.cart.empty.back}}</a>
				</div>
				<div class="cart-recommended" v-if="recommended.length > 0">
					<h1 class="cart-recommended__title">{{Lang.cart.recommended}}</h1>
					<div class="cart-recommended__products">
						<store-item :product="product" :active="selectedItem === product" :disabled="false" :key="product.id" @open="selectItem" v-for="product in recommended"></store-item>
					</div>
				</div>
			</div>
        </div>
        <transition name="item-modal">
            <item-modal :item="selectedItem" @close="selectItem(null)" @add="putToCart" v-if="selectedItem"></item-modal>
        </transition>
        <transition name="item-modal">
            <item-modal :item="selectedCartItem.product" :cart="selectedCartItem" @close="selectCartItem(null)" @add="updateCart" v-if="selectedCartItem"></item-modal>
        </transition>
        <transition name="item-modal">
            <delete-modal :item="deletingItem" @close="deleteItem(null)" @delete="deleteFromCart" v-if="deletingItem"></delete-modal>
        </transition>
        <transition name="item-modal">
            <promo-modal @close="promoModal = false" @error="promoError" @activated="promoActivated" v-show="promoModal && !promo"></promo-modal>
        </transition>
    </div>
</template>

<script>
    import Item from "./parts/Item";
    import StoreItem from "../store/parts/Item";
    import ItemModal from "../store/parts/ItemModal";
    import DeleteModal from "./parts/DeleteModal";
    import PromoModal from "./parts/PromoModal";
    import Alert from "../store/parts/Alert";
    export default {
        name: "CartComponent",
        props: ['items', 'recommended', 'settings', 'userData'],
        data() {
            return {
                error: null,
                totalCost: 0,
                selectedItem: null,
                deletingItem: null,

                promo: null,
                promoDiscount: 0,
                promoModal: false,

                selectedCartItem: null,
				rnd: 0,
            }
        },
        mounted() {
            this.totalCost = this.getTotalCost()
        },
        methods: {
            getTotalCost() {
                let cost = 0
                for (let item of this.items)
                {
                    let price = item.product.price_rub ? item.product.price_rub : item.product.price_coins
                    if (item.product.discount && new Date(this.item.product.discount.date_end).getTime() > new Date().getTime()) {
                        price -= Math.floor(price * (item.product.discount.discount / 100))
                    }
                    cost += price * item.amount
                }

				if (cost > 0 && this.promoDiscount > 0) {
					cost -= Math.floor(cost * (this.promoDiscount / 100))
				}

                return cost
            },
            pay() {
                this.error = null
                if (this.totalCost > this.userData.balance) {
                    loading(true)
                    setTimeout(() => {
                        this.error = this.Lang.cart.responses.not_enough_coins
                        this.error.link_url = this.settings.store_url
                        loading(false)
                    }, 200)
                    return
                }
                axios.post('/cart/pay', {promo: this.promo})
                    .then(res => {
                        if (res.data.error) {
                            this.error = res.data.error
                        } else {
                            alert(res.data.message)
                            this.userData.balance = res.data.balance
                            this.items = [];
                            this.totalCost = this.getTotalCost()
                        }
                    })
            },
            selectCartItem(item) {
                this.selectedCartItem = item
            },
            updateCart(cart, amount) {
                this.selectCartItem(null)
                axios.post('/cart/update', {id: cart.id, amount: amount})
                    .then(res => {
                        alert(res.data.message)
                        for (let i in this.items)
                        {
                            if (this.items[i].id === cart.id) {
                                this.items[i] = res.data.cart
                            }
                        }
                        this.totalCost = this.getTotalCost()
                    })
            },
            selectItem(item) {
                if (this.selectedItem === item) {
                    item = null
                }

                this.selectedItem = item
            },
            putToCart(item, amount) {
                this.selectItem(null)
                axios.post('/cart/put/' + item.id, {amount: amount})
                    .then(res => {
                        alert(res.data.message)
                        this.items.push(res.data.cart)
                        this.totalCost = this.getTotalCost()
						this.rnd = Math.random()
						console.log(this.items)
                    })
            },
            deleteItem(item) {
                if (this.deletingItem === item) {
                    item = null
                }

                this.deletingItem = item
            },
            deleteFromCart(item) {
                this.deleteItem(null)
                axios.post('/cart/delete/' + item.id)
                    .then(res => {
                        alert(res.data.message)
                        let deletingI = -1;
                        for (let i in this.items)
                        {
                            if (this.items[i] === item) {
                                deletingI = i;
                                break
                            }
                        }
                        if (deletingI !== -1) {
                            this.items.splice(deletingI, 1)
                        }
                        this.totalCost = this.getTotalCost()
                    })
            },
            promoActivated(promo, discount) {
                this.promoDiscount = discount
                this.promo = promo
                this.totalCost = this.getTotalCost()
            },
            promoError(error) {
                this.error = error
            }
        },
        components: {Alert, PromoModal, DeleteModal, ItemModal, Item, StoreItem}
    }
</script>

<style scoped>
    /* Появление */
    .item-modal-enter-active {
        animation: item-modal .3s;
    }

    /* Скрывание */
    .item-modal-leave-active {
        animation: item-modal .2s reverse;
    }

    @keyframes item-modal {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
</style>
