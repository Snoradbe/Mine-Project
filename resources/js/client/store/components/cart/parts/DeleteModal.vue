<template>
    <div class="modal cart-modal-del" style="display: block" @click.self="$emit('close')">
        <div class="modal__dialog" @click.self="$emit('close')">
            <div class="modal__container">
                <div class="modal__header">
                    <div>
                        <h4 class="modal__title">Delete Item</h4>
                        <h5 class="modal__attention">Do you really want to delete this item from shopping cart?</h5>
                    </div>
                </div>
                <div class="modal__body">
                    <div class="cart__item">
                        <div class="cart__picture">
                            <div class="store-product-icon" :style="{'background-image': 'url(\'../img/store/products/' + (item.product.img ? item.product.img : 'default.png') + '\')'}"></div>
                        </div>
                        <div class="cart__item-info">
                            <h6 class="cart__item-name">{{getColumnLangValue('name', item.product)}}</h6>
                            <div class="cart__item-desc" v-html="getColumnLangValue('descr', item.product)"></div>
                        </div>
                    </div>
                </div>
                <div class="store-modal__footer">
                    <div class="store-modal__cancel" @click="$emit('close')">{{Lang.store.modal.product.cancel}}</div>
                    <button class="btn btn_sm btn_warning cart-modal-del__btn-del" @click.prevent="$emit('delete', item)">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DeleteModal",
        props: ['item']
    }
</script>
