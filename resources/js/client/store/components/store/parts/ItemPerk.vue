<template>
    <div :class="{'store-product store-product-perk store-products__item': true, 'active': active, 'disabled': disabled}" @click="open">
        <div class="store-product-icon" :style="'background-image: url(\'../img/store/products/' + (product.img ? product.img : 'default.png') + '\')'"></div>
        <h1 class="store-product-perk__name">{{getColumnLangValue('name', product)}}</h1>
        <div class="store-product-perk__desc" v-if="getColumnLangValue('descr', product)">{{getColumnLangValue('descr', product)}}</div>
        <div class="store-product-perk__opportunities">
			<h6 class="store-product-perk__opportunities-title">{{Lang.store.opportunities}}</h6>
			<span :class="{'store-product-perk__opportunities-server': true, 'disabled': disabledServers.indexOf(server.name) !== -1}" v-for="server in servers">{{getServerAbbr(server)}}</span>
		</div>
        <div class="store-product-perk__buffs" v-if="product.additionals && product.additionals.length > 0">
			<h6 class="store-product-perk__buffs-title">{{Lang.store.buffs}}</h6>
			<div :class="'cur cur_right cur_coins'" v-if="additional.additional.category.id === 4" v-for="additional in product.additionals">
				+{{additional.additional.amount * additional.amount}}
			</div>
			<div v-else>
				{{getColumnLangValue('name', additional.additional)}} (x{{additional.additional.amount * additional.amount}})
			</div>
		</div>
        <div class="store-product-perk__price">
            <span :class="'cur cur_right ' + (product.price_rub ? 'cur_rub' : 'cur_coins')">{{getPrice()}}</span> /{{Lang.words.time.months[0]}}
            <p class="store-product-perk__price-desc">{{Lang.store.payment_is_possible}}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ItemPerk",
        props: ['product', 'servers', 'disabledServers', 'active', 'disabled'],
        methods: {
            getPrice(product) {
                let price = this.product.price_coins ? this.product.price_coins : this.product.price_rub
                if (this.product.discount && new Date(this.product.discount.date_end).getTime() > new Date().getTime()) {
                    price -= Math.floor(price * (this.product.discount.discount / 100))
                }

                return price
            },
            open() {
                if (!this.disabled) {
                    return this.$emit('open', this.product)
                }
            }
        }
    }
</script>
