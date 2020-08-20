@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{asset('css/store.css')}}?v={{time()}}">
@endsection

@section('content')
    <div class="section-store space-header">
        <div class="container section-store__container">
            <div class="section-store__head">
                <h2 class="section-store__title">Marketplace</h2>
            </div>
            <div class="section-store__content">
                <div class="section-store__left">
                    <div class="section-store__search">
                        <button class="section-store__search-btn"><span class="material-icons">search</span></button>
                        <input type="text" class="input section-store__search-input" placeholder="Search products...">
                    </div>
                    <div class="store section-store__store">
                        <div class="store__types">
                            <div class="store-type active">
                                <img src="{{asset('img/store/items_orange.svg')}}" alt="mine Store" class="store-type__icon">
                                <h4 class="store-type__name">Items</h4>
                                <span class="store-type__count">145 products</span>
                            </div>
                            <div class="store-type">
                                <img src="{{asset('img/store/perks_orange.svg')}}" alt="mine Store" class="store-type__icon">
                                <h4 class="store-type__name">Perks</h4>
                                <span class="store-type__count">5 products</span>
                            </div>
                            <div class="store-type">
                                <img src="{{asset('img/store/cases_orange.svg')}}" alt="mine Store" class="store-type__icon">
                                <h4 class="store-type__name">Cases</h4>
                                <span class="store-type__count">5 products</span>
                            </div>
                            <div class="store-type">
                                <img src="{{asset('img/store/coins_orange.svg')}}" alt="mine Store" class="store-type__icon">
                                <h4 class="store-type__name">Coins</h4>
                                <span class="store-type__count">5 products</span>
                            </div>
                        </div>
                        <section class="store-products">
                            <h2 class="store-products__title">Popular products</h2>
                            <div class="store-products__list">
                                <div class="store-product store-products__item">
                                    <div class="store-product__info">
                                        <div class="store-product__name">Iron Ingot</div>
                                        <div class="store-product__desc">
                                            A lot of different ingots from Slimefun and something description of product.
                                        </div>
                                        <div class="store-product__price cur cur_right cur_coins">
                                            1245
                                        </div>
                                    </div>
                                    <div class="store-product__picture">

                                    </div>
                                </div>

                                <div class="store-product store-products__item">
                                    <div class="store-product__info">
                                        <div class="store-product__name">Iron Ingot</div>
                                        <div class="store-product__desc">
                                            A lot of different ingots from Slimefun and something description of product.
                                        </div>
                                        <div class="store-product__price cur cur_right cur_coins">
                                            1245
                                        </div>
                                    </div>
                                    <div class="store-product__picture">

                                    </div>
                                </div>

                                <div class="store-product store-products__item">
                                    <div class="store-product__info">
                                        <div class="store-product__name">Cob Web</div>
                                        <div class="store-product__desc">
                                            A lot of different ingots from Slimefun and something description of product.
                                        </div>
                                        <div class="store-product__price cur cur_right cur_coins">
                                            1245
                                        </div>
                                    </div>
                                    <div class="store-product__picture">
                                        <img src="{{asset('img/store/cobweb.png')}}" alt="mine" class="store-product__picture-img">
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="section-store__right">
                    <div class="store-menu">
                        <div class="store-menu__item">
                            <div class="store-menu__left">
                                Shopping cart
                            </div>
                            <div class="store-menu__right">
                                <a href="#" class="">Open</a>
                            </div>
                        </div>

                        <div class="store-menu__item">
                            <div class="store-menu__left">
                                Your balance
                            </div>
                            <div class="store-menu__right">
                                1245
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
