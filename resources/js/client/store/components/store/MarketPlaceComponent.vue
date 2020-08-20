<template>
    <div class="section-store space-header" v-infinite-scroll="loadMore" infinite-scroll-throttle-delay="500" infinite-scroll-disabled="busy">
        <div class="container section-store__container">
            <div class="section-store__head">
                <h2 class="section-store__title">{{Lang.store.title}}</h2>
            </div>
            <div class="section-store__content">
                <div class="section-store__left">
                    <div @submit.prevent="searchByName" class="section-store__search">
                        <div class="section-store__search-icon" v-if="!searchNameM"><span class="material-icons">search</span></div>
						<div class="section-store__search-cancel" v-else>
							<span>{{Lang.store.search.enter}}</span>
							<button class="section-store__search-btn" @click.prevent="resetName"><span class="material-icons">cancel</span></button>
						</div>
                        <input type="text" class="input section-store__search-input" :placeholder="Lang.store.search.placeholder" v-model="searchNameM" @keyup.enter="searchByName" @keyup.esc="resetName" ref="searchName">
                    </div>
                    <div class="store section-store__store">
                        <div class="store__types">
                            <category
                                :category="category"
                                :active="selectedCategory === category"
                                :disabled="!checkCategory(category)"
                                :key="category.id"
                                @click.native="selectCategory(category)"
                                v-for="category in categories"
                            ></category>
                        </div>
                        <section class="store-products" v-if="!needSearch()">
                            <h2 class="store-products__title">{{Lang.store.subtitle.popular}}</h2>
                            <div class="store-products__list">
                                <template v-for="product in popular">
                                    <item :product="product" :active="selectedItem === product" :disabled="!userData.logged && product.category.need_auth" @open="selectItem" v-if="product.category.id !== 2"></item>
                                    <item-perk
										:product="product"
										:servers="servers"
										:disabled-servers="settings.disabled_servers_perks"
										:active="selectedItem === product"
										:disabled="!userData.logged && product.category.need_auth"
										@open="selectItem"
										v-else
									></item-perk>
                                </template>
                            </div>
                        </section>
                        <section class="store-products" v-else>
                            <div class="store-not-found" v-if="searchedProducts.length < 1">
                                <span class="icon material-icons">search_off</span>
                                <h1>{{Lang.store.not_found.title.replace(':name', searchName)}}</h1>
                                <h6>{{Lang.store.not_found.subtitle}}</h6>
                            </div>
                            <template v-else>
                                <h2 class="store-products__title">{{Lang.store.subtitle.search}}</h2>
                                <div :class="'store-products__list store-products__list_' + (selectedCategory && selectedCategory.id)">
                                    <template v-for="product in searchedProducts">
                                        <item :product="product" :active="selectedItem === product" :disabled="!userData.logged && product.category.need_auth" @open="selectItem" v-if="product.category.id !== 2"></item>
                                        <item-perk
                                            :product="product"
                                            :servers="servers"
                                            :disabled-servers="settings.disabled_servers_perks"
                                            :active="selectedItem === product"
                                            :disabled="!userData.logged && product.category.need_auth"
                                            @open="selectItem"
                                            v-else
                                        ></item-perk>
                                    </template>
                                </div>
                            </template>
                        </section>
                    </div>
                </div>
                <div class="section-store__right">
                    <div class="store-menu">
                        <div class="store-menu__item" v-if="!user.logged">
                            <div class="store-user">
                                <h6 class="store-user__title">{{Lang.store.logged.title}}</h6>
                                <div class="bordered-status bordered-status_circle">{{user.name}}</div>
                            </div>
                        </div>

                        <div class="store-limitations store-menu__item" v-if="!user.logged">
                            <div class="store-limitations__title"><span class="material-icons">warning</span> {{Lang.store.logged.limitations.title}}</div>
                            <h6 class="store-limitations__subtitle">{{Lang.store.logged.limitations.subtitle}}</h6>
                            <div class="store-limitations__able">
                                {{Lang.store.logged.limitations.able.title}}
                                <ul class="store-limitations__able-list">
                                    <li v-for="lim in Lang.store.logged.limitations.able.list">{{lim}}</li>
                                </ul>
                            </div>
                            <p class="store-limitations__use">{{Lang.store.logged.limitations.use}}</p>
                            <a :href="settings.login_url" class="store-limitations__btn btn btn_warning">{{Lang.store.logged.limitations.signin}}</a>
                        </div>

                        <div class="store-menu__item" v-if="user.logged">
                            <div class="store-balance">
                                <h6 class="store-balance__title">{{Lang.store.balance}}</h6>
								<div class="store-balance__balance cur cur_right cur_coins">
                                    {{user.balance}}
								</div>
								<div class="store-menu__line"></div>
                                <a :href="settings.cart_url" class="store-balance__btn btn btn_warning">{{Lang.store.cart.open}}</a>
                            </div>
                        </div>

                        <div class="store-menu__item">
                            <div class="store-menu__head">
                                <h5 class="store-menu__head-title">{{Lang.store.sorting.title}}</h5>
                                <a href="#" class="store-menu__sort-reset" @click.prevent="resetSearch">{{Lang.store.sorting.reset}}</a>
                            </div>
							<div class="store-menu__line"></div>
                            <h5 class="store-menu__title-servers">{{Lang.store.sorting.servers}}</h5>
                            <div class="store-menu__servers">
                                <server
                                    :server="server"
                                    :active="!selectedServer || selectedServer === server"
                                    :key="server.name"
                                    @click.native="selectServer(server)"
                                    v-for="server in servers"
                                ></server>
                            </div>
                            <template v-if="discounts.length > 0">
                                <h5 class="store-menu__title-discounts">{{Lang.store.sorting.offers}}</h5>
                                <div class="store-menu__discounts">
                                    <discount
                                        :discount="discount"
                                        :active="selectedDiscount === discount"
                                        :key="discount.id"
                                        @click.native="selectDiscount(discount)"
                                        v-for="discount in discounts"
                                    ></discount>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <transition name="item-modal">
            <item-modal :item="selectedItem" @close="selectItem(null)" @add="putToCart" v-if="selectedItem && selectedItem.category.id !== 2"></item-modal>
            <item-perk-modal :item="selectedItem" :servers="servers" :disabled-servers="settings.disabled_servers_perks" @close="selectItem(null)" @add="putToCart" v-else-if="selectedItem"></item-perk-modal>
        </transition>
    </div>
</template>

<script>
    import Category from "./parts/Category";
    import Item from "./parts/Item";
    import Server from "./parts/Server";
    import Discount from "./parts/Discount";
    import ItemModal from "./parts/ItemModal";
    import ItemPerk from "./parts/ItemPerk";
    import ItemPerkModal from "./parts/ItemPerkModal";
    export default {
        name: "StoreComponent",
        props: ['categories', 'servers', 'discounts', 'popular', 'settings', 'userData', 'selectedCat'],
        data() {
            return {
                user: this.userData,

                selectedCategory: null,
                selectedServer: null,
                selectedDiscount: null,

				searchName: null,
                searchNameM: null,

                selectedItem: null,


                searchedProducts: [],

                current_page: 1,
                last_page: 1,
                busy: false,
            }
        },
        mounted() {
            if (this.selectedCat) {
                for (let category of this.categories) {
                    if (category.id == this.selectedCat) {
                        this.selectCategory(category)
                    }
                }
            }
        },
        methods: {
            selectCategory(category) {
                if (!this.checkCategory(category)) {
                    return
                }

                if (category === this.selectedCategory) {
                    category = null
                }

                this.selectedCategory = category
                this.current_page = 1
                this.searchProducts()
            },
            selectServer(server) {
                if (server === this.selectedServer) {
                    server = null
                }

                this.selectedServer = server
                this.current_page = 1
                this.searchProducts()
            },
            selectDiscount(discount) {
                if (discount === this.selectedDiscount) {
                    discount = null
                }

                this.selectedDiscount = discount
                this.current_page = 1
                this.searchProducts()
            },
			searchByName() {
				this.searchName = this.searchNameM ? this.searchNameM.trim() : null
                this.current_page = 1
                this.searchProducts()
			},
            resetName() {
                this.searchNameM = null

                if (!this.searchName) {
                    return
                }

                this.current_page = 1
                this.searchByName()
            },
            resetSearch() {
                this.selectedCategory = null
                this.selectedServer = null
                this.selectedDiscount = null
                this.searchName = null
                this.searchNameM = null
                this.current_page = 1
            },
            needSearch() {
                return this.selectedCategory || this.selectedServer || this.selectedDiscount || this.searchName
            },
            searchProducts() {
				if (!this.needSearch()) {
					return
				}

                let data = {}
                if (this.selectedCategory) {
                    data.category = this.selectedCategory.id
                }
                if (this.selectedServer) {
                    data.server = this.selectedServer.name
                }
                if (this.selectedDiscount) {
                    data.discount = this.selectedDiscount.id
                }
                if (this.searchName) {
                    data.name = this.searchName
                }
                this.busy = true
                axios.post('/search?page=' + this.current_page, data)
                    .then(res => {
                        this.current_page = res.data.current_page
                        if (this.current_page > 1) {
                            this.searchedProducts = Array.prototype.concat(this.searchedProducts, res.data.data)
                        } else {
                            this.searchedProducts = res.data.data
                        }
                        this.last_page = res.data.last_page
                        this.busy = false
                    })
            },
            loadMore() {
                if (this.needSearch() && this.current_page < this.last_page) {
                    this.current_page++
                    this.searchProducts()
                }
            },
            putToCart(item, amount) {
                this.selectItem(null)
                axios.post('/cart/put/' + item.id, {amount: amount})
                    .then(res => {
                        alert(res.data.message)
                    })
            },
            checkCategory(category) {
                return this.user.logged || !category.need_auth
            },
            selectItem(item) {
                if (this.selectedItem === item) {
                    item = null
                }

                this.selectedItem = item
            },
        },
        components: {ItemPerkModal, ItemPerk, ItemModal, Discount, Server, Item, Category}
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
