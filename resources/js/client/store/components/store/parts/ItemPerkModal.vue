<template>
    <div class="modal store-modal store-modal_perk" id="buy-modal" style="display: block" @click.self="$emit('close')">
        <div class="modal__dialog" @click.self="$emit('close')">
            <div class="store-modal__container">
                <div class="modal__body">
                    <div class="store-modal__left">
                        <div class="store-modal__head">
                            <h6 class="store-modal__title">{{cart ? Lang.store.modal.product.title_cart : Lang.store.modal.product.title}}</h6>
                            <h2 class="store-modal__name">{{getColumnLangValue('name', item)}}</h2>
                        </div>
                        <div class="store-modal__info">
                            <div class="store-modal__desc">
								<div class="store-modal__desc-title">{{Lang.store.modal.product.desc}}</div>
								<div class="store-modal__desc-content" v-html="getColumnLangValue('descr', item)"></div>
							</div>
                        </div>
                        <div class="store-modal__options">
                            <div class="store-modal__amount">
                                {{Lang.cart.item['amount_' + item.category.id] ? Lang.cart.item['amount_' + item.category.id] : Lang.cart.item.amount}}
                                <div class="store-modal__amount-btns">
                                    <span class="store-modal__amount-btn material-icons" @click="amountAdd">add</span>
                                    {{am}} {{pcs()}}
                                    <span class="store-modal__amount-btn material-icons" @click="amountRemove">remove</span>
                                </div>
                            </div>
                            <div class="store-modal__price">
                                {{Lang.store.modal.product.price}}
                                <div :class="'cur cur_right ' + (item.price_rub ? 'cur_rub' : 'cur_coins')">
                                    {{basePrice}}
                                </div>
                            </div>
                            <div class="store-modal__cost">
                                {{Lang.store.modal.product.total}}
                                <div :class="'cur cur_right ' + (item.price_rub ? 'cur_rub' : 'cur_coins')">
                                    {{price}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="store-modal__right">
						<div class="store-product__picture">
							<div class="store-product-icon" :style="{'background-image': 'url(\'../img/store/products/' + (item.img ? item.img : 'default.png') + '\')'}"></div>
						</div>
                        <div class="store-product-perk__opportunities">
							<h6 class="store-product-perk__opportunities-title">{{Lang.store.opportunities}}</h6>
							<span :class="{'store-product-perk__opportunities-server': true, 'disabled': disabledServers.indexOf(server.name) !== -1}" v-for="server in servers">{{getServerAbbr(server)}}</span>
						</div>
						<div class="store-product-perk__buffs" v-if="item.additionals && item.additionals.length > 0">
							<h6 class="store-product-perk__buffs-title">{{Lang.store.buffs}}</h6>
							<div :class="'cur cur_right cur_coins'" v-if="additional.additional.category.id === 4" v-for="additional in item.additionals">
								+{{additional.additional.amount * additional.amount}}
							</div>
							<div v-else>
								{{getColumnLangValue('name', additional.additional)}} (x{{additional.additional.amount * additional.amount}})
							</div>
						</div>
                    </div>
                </div>
                <div class="store-modal__footer">
                    <div class="store-modal__cancel" @click="$emit('close')">{{Lang.store.modal.product.cancel}}</div>
                    <button class="btn btn_sm btn_warning store-modal__add" @click.prevent="add">{{cart ? Lang.store.modal.product.save : (item.price_rub ? Lang.store.modal.product.buy : Lang.store.modal.product.add)}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ItemPerkModal",
        props: ['item', 'cart', 'servers', 'disabledServers'],
        data() {
            return {
                am: 1,
                price: -1,

                basePrice: -1,
            }
        },
        mounted() {
            if (this.cart) {
                this.am = this.cart.amount
            }
            let price = this.item.price_coins ? this.item.price_coins : this.item.price_rub
            if (this.item.discount && new Date(this.item.discount.date_end).getTime() > new Date().getTime()) {
                price -= Math.floor(price * (this.item.discount.discount / 100))
            }

            this.price = price
            this.basePrice = this.price
        },
        methods: {
            amountAdd() {
                this.am++
                this.price = this.basePrice * this.am
            },
            amountRemove() {
                if (this.am > 1) {
                    this.am--
                    this.price = this.basePrice * this.am
                }
            },
            pcs() {
                let word = Lang.words.store.amount['category_' + this.item.category.id]
                return this.declOfNum(this.am, word ? word : Lang.words.store.amount.pcs)
            },
            add() {
                if (this.item.price_rub) {
                    this.buy()
                    return
                }

                return this.$emit('add', this.cart ? this.cart : this.item, this.am)
            },
            buy() {
                axios.need_close_loading = false
                axios.post('/buy/' + this.item.id, {amount: this.am})
                    .then(res => {
                        axios.need_close_loading = true
                        window.location.href = res.data.url
                    })
            }
        }
    }
</script>
