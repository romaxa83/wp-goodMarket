<?php 

use yii\helpers\Url;

?>
<header class="site-header">
    <div class="top-banner">
    <a href="#" class="top-banner__link"></a>
    <picture>
        <source
        media="(min-width: 1200px)"
        type="image/webp"
        srcset="/img/top_banner-xl.webp"
        />
        <source
        media="(min-width: 1200px)"
        srcset="/img/top_banner-xl.jpg"
        />
        <source
        media="(min-width: 768px)"
        type="image/webp"
        srcset="/img/top_banner-md.webp"
        />
        <source
        media="(min-width: 768px)"
        srcset="/img/top_banner-md.jpg"
        />
        <source
        type="image/webp"
        srcset="/img/top_banner-sm.webp"
        />
        <img
        src="/img/top_banner-sm.jpg"
        alt="top banner"
        title="top banner"
        class="top-banner__img"
        />
    </picture>
    </div>
    <div class="mob-menu d-xl-none" id="mobMenu">
    <nav class="navbar d-block">
        <div class="mob-menu__logo">
        <button class="mob-menu__close" id="btnMobMenuClose">
            <svg width="25" height="25">
            <use xlink:href="/img/spritemap.svg#sprite-close"></use>
            </svg>
        </button>
        <span class="navbar-brand">
            <img
            width="185"
            height="42"
            src="/img/logo.svg"
            alt="site logo"
            title="site logo"
            class="mob-menu__logo-img"
            />
        </span>
        </div>
        <div
        class="mob-menu__authorization authorization d-flex justify-content-between"
        >
        <button
            class="authorization__login open-popup"
            data-popup="#loginFormPopup"
        >
            <svg width="20" height="20">
            <use xlink:href="/img/spritemap.svg#sprite-profile"></use>
            </svg>
            Войдите в кабинет
        </button>
        <div class="authorization__lang">
            <?= \frontend\widgets\langwidget\LangWidget::widget(['mobile' => true]); ?>
        </div>
        </div>
        <ul class="navbar-nav mob-menu__navigation">
        <li class="nav-item active">
            <div class="nav-icon">
            <svg width="17" height="16">
                <use
                xlink:href="/img/spritemap.svg#sprite-web-page-home"
                ></use>
            </svg>
            </div>
            <span class="nav-link">Главная</span>
        </li>
        <li class="nav-item dropdown">
            <div class="nav-icon">
            <svg width="13" height="13">
                <use xlink:href="/img/spritemap.svg#sprite-menu"></use>
            </svg>
            </div>
            <a class="nav-link dropdown-toggle" href="#">
            Каталог товаров
            </a>
            <div class="dropdown-menu dropdown-menu--category">
            <div>
                <a class="dropdown-item dropdown-toggle" href="#"
                >Категория 1</a
                >
                <div class="dropdown-menu dropdown-menu--subcategory">
                <a class="dropdown-item" href="#">Подкатегория 1</a>
                <a class="dropdown-item" href="#">Подкатегория 2</a>
                <a class="dropdown-item" href="#">Подкатегория 3</a>
                <a class="dropdown-item" href="#">Подкатегория 4</a>
                </div>
            </div>
            <div>
                <div>
                <a class="dropdown-item dropdown-toggle" href="#"
                    >Категория 2</a
                >
                <div class="dropdown-menu dropdown-menu--subcategory">
                    <a class="dropdown-item" href="#">Подкатегория 1</a>
                    <a class="dropdown-item" href="#">Подкатегория 2</a>
                    <a class="dropdown-item" href="#">Подкатегория 3</a>
                    <a class="dropdown-item" href="#">Подкатегория 4</a>
                </div>
                </div>
            </div>
            <div>
                <a class="dropdown-item dropdown-toggle" href="#"
                >Категория 3</a
                >
                <div class="dropdown-menu dropdown-menu--subcategory">
                <a class="dropdown-item" href="#">Подкатегория 1</a>
                <a class="dropdown-item" href="#">Подкатегория 2</a>
                <a class="dropdown-item" href="#">Подкатегория 3</a>
                <a class="dropdown-item" href="#">Подкатегория 4</a>
                </div>
            </div>
            <div>
                <div>
                <a class="dropdown-item dropdown-toggle" href="#"
                    >Категория 4</a
                >
                <div class="dropdown-menu dropdown-menu--subcategory">
                    <a class="dropdown-item" href="#">Подкатегория 1</a>
                    <a class="dropdown-item" href="#">Подкатегория 2</a>
                    <a class="dropdown-item" href="#">Подкатегория 3</a>
                    <a class="dropdown-item" href="#">Подкатегория 4</a>
                </div>
                </div>
            </div>
            <div>
                <a class="dropdown-item dropdown-toggle" href="#"
                >Категория 5</a
                >
                <div class="dropdown-menu dropdown-menu--subcategory">
                <a class="dropdown-item" href="#">Подкатегория 1</a>
                <a class="dropdown-item" href="#">Подкатегория 2</a>
                <a class="dropdown-item" href="#">Подкатегория 3</a>
                <a class="dropdown-item" href="#">Подкатегория 4</a>
                </div>
            </div>
            <div>
                <div>
                <a class="dropdown-item dropdown-toggle" href="#"
                    >Категория 6</a
                >
                <div class="dropdown-menu dropdown-menu--subcategory">
                    <a class="dropdown-item" href="#">Подкатегория 1</a>
                    <a class="dropdown-item" href="#">Подкатегория 2</a>
                    <a class="dropdown-item" href="#">Подкатегория 3</a>
                    <a class="dropdown-item" href="#">Подкатегория 4</a>
                </div>
                </div>
            </div>
            <div>
                <a class="dropdown-item dropdown-toggle" href="#"
                >Категория 7</a
                >
                <div class="dropdown-menu dropdown-menu--subcategory">
                <a class="dropdown-item" href="#">Подкатегория 1</a>
                <a class="dropdown-item" href="#">Подкатегория 2</a>
                <a class="dropdown-item" href="#">Подкатегория 3</a>
                <a class="dropdown-item" href="#">Подкатегория 4</a>
                </div>
            </div>
            </div>
        </li>
        <li class="nav-item">
            <div class="nav-icon">
            <svg width="13" height="13">
                <use xlink:href="/img/spritemap.svg#sprite-percentage"></use>
            </svg>
            </div>
            <a href="#" class="nav-link">Акции</a>
        </li>
        <li class="nav-item">
            <div class="nav-icon">
            <svg width="16" height="16">
                <use
                xlink:href="/img/spritemap.svg#sprite-shopping-cart_menu"
                ></use>
            </svg>
            </div>
            <a href="#" class="nav-link">Корзина</a>
        </li>
        <li class="nav-item">
            <div class="nav-icon">
            <svg width="16" height="16">
                <use
                xlink:href="/img/spritemap.svg#sprite-heart-outline-menu"
                ></use>
            </svg>
            </div>
            <a href="#" class="nav-link">Список желаний</a>
        </li>
        </ul>
        <ul class="navbar-nav mob-menu__info">
        <li class="nav-item">
            <div class="nav-icon">
            <svg width="15" height="16">
                <use xlink:href="/img/spritemap.svg#sprite-phone"></use>
            </svg>
            </div>
            <a href="tel:0001234567" class="nav-link">(000) 123-45-67</a>
        </li>
        <li class="nav-item">
            <div class="nav-icon">
            <svg width="15" height="15">
                <use xlink:href="/img/spritemap.svg#sprite-about_us"></use>
            </svg>
            </div>
            <a href="#" class="nav-link">Почему мы?</a>
        </li>
        <li class="nav-item dropdown">
            <div class="nav-icon">
            <svg width="15" height="15">
                <use xlink:href="/img/spritemap.svg#sprite-information"></use>
            </svg>
            </div>
            <a class="nav-link dropdown-toggle" href="#">
            Клиентам
            </a>
            <div class="dropdown-menu dropdown-menu--category">
            <a class="dropdown-item" href="#">Текст текст текст</a>
            <a class="dropdown-item" href="#">Текст текст текст</a>
            </div>
        </li>
        </ul>
    </nav>
    </div>
    <div class="desctop-menu d-none d-xl-flex align-items-center">
    <div class="container">
        <nav class="navbar justify-content-xxl-start">
        <div class="desctop-menu__logo col-xl-3 col-xxl-2">
            <span class="navbar-brand">
            <img
                width="222"
                height="50"
                src="/img/logo.svg"
                alt="site logo"
                title="site logo"
                class="desctop-menu__logo-img"
            />
            </span>
        </div>
        <ul
            class="navbar-nav d-flex flex-row align-items-center justify-content-between col-xl-7 col-xxl-8 desctop-menu__navigation"
        >
            <li class="nav-item desctop-menu__lang dropdown-click lang-dropdown">
                <?= \frontend\widgets\langwidget\LangWidget::widget(); ?>
            </li>
            <li
            class="nav-item desctop-menu__phone mr-auto dropdown-click phone-dropdown"
            >
            <div class="nav-icon">
                <svg width="17" height="18">
                <use xlink:href="/img/spritemap.svg#sprite-phone"></use>
                </svg>
            </div>
            <span class="nav-link dropdown-toggle" 
                >(000) 123-45-67</span
            >
            <div class="dropdown-menu text-center">
                <p>
                Служба поддержки
                </p>
                <a class="dropdown-item" href="tel:0001234567"
                >(000) 123-45-67</a
                >
                <p>
                Оформление заказа
                </p>
            </div>
            </li>
            <li class="nav-item">
            <a href="#" class="nav-link">Акции</a>
            </li>
            <li class="nav-item">
            <a href="#" class="nav-link">Почему мы?</a>
            </li>
            <li class="nav-item dropdown client-dropdown">
            <a class="nav-link dropdown-toggle" href="#">
                Клиентам
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">О нас</a>
                <a class="dropdown-item" href="#">Доставка</a>
                <a class="dropdown-item" href="#">Оплата</a>
                <a class="dropdown-item" href="#">Новости</a>
                <a class="dropdown-item" href="#">Контакты</a>
            </div>
            </li>
        </ul>
        <div class="col-xl-2 col-xxl-1 desctop-menu__authorization">
            <button
            class="authorization__login desctop-menu__login open-popup"
            data-popup="#loginFormPopup"
            >
            <svg width="20" height="20">
                <use xlink:href="/img/spritemap.svg#sprite-profile"></use>
            </svg>
            Войдите в кабинет
            </button>
        </div>
        </nav>
    </div>
    </div>
    <div class="site-header__wrapper" id="stickyHeader" data-sticky-wrap="true"> 
    <div class="container position-relative">
        <nav
        class="navbar site-navbar d-flex flex-nowrap align-items-center justify-content-start"
        >
        <button
            class="site-navbar__toggle-btn d-xl-none"
            id="btnMobMenuOpen"
        >
            <svg width="23" height="23">
            <use xlink:href="/img/spritemap.svg#sprite-burger"></use>
            </svg>
        </button>
        <a
            href="./catalog.html"
            class="site-navbar__catalog d-none d-xl-block btn btn-secondary text-left"
            >Каталог</a
        >
        <div class="site-navbar__search site-search flex-shrink-1">
            <form class="form-inline">
            <input
                class="form-control"
                type="text"
                placeholder="Поиск товаров"
                aria-label="Search"
                id="site-search"
            />
            <button class="site-search__btn" type="submit">
                <svg width="18" height="18">
                <use xlink:href="/img/spritemap.svg#sprite-search"></use>
                </svg>
            </button>
            </form>
        </div>
        <div
            class="site-navbar__cart-wrap d-flex ml-auto justify-content-between"
        >
            <div class="site-navbar__cart cart">
            <a href="#" class="cart__link open-popup" data-popup="#cartAllOrdersPopup"></a>
            <div class="cart__amount">
                <span>12</span>
            </div>
            <svg width="18" height="18">
                <use
                xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                ></use>
            </svg>
            </div>

            <div class="site-navbar__wish-list wish-list">
            <a href="#" class="wish-list__link"></a>
            <div class="cart__amount">
                <span>12</span>
            </div>
            <svg width="18" height="18">
                <use
                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                ></use>
            </svg>
            </div>
        </div>
        </nav>
        <div class="suggestions" id="suggestions">
        <div class="suggestions__wrapper">
            <div class="suggestions__hints col-md-3 flex-md-shrink-1">
            <span>
                <a href="#">
                блокнот
                </a>
            </span>
            <span>
                <a href="#">
                блокнот А4
                </a>
            </span>
            <span>
                <a href="#">
                блокнот А5
                </a>
            </span>
            <span>
                <a href="#">
                блокноты
                </a>
            </span>
            <span>
                <a href="#">
                блокнот в точку
                </a>
            </span>
            <span>
                <a href="#">
                блокнот планер
                </a>
            </span>
            <span>
                <a href="#">
                кожаный блокнот
                </a>
            </span>
            </div>
            <div class="suggestions__category col-md-3 flex-md-shrink-1">
            <h3>Категория</h3>
            <span>
                <a href="#">
                Название категории
                </a>
            </span>
            <span>
                <a href="#">
                Название категории
                </a>
            </span>
            </div>
            <div
            class="suggestions__popular col-md-6 d-md-flex flex-md-shrink-1 flex-md-wrap"
            >
            <div class="col-12 px-0">
                <h3>
                Популярные товары
                </h3>
            </div>
            <div class="suggestions__card col-12 col-md-6">
                <a href="#"></a>
                <div class="d-flex">
                <div class="suggestions__card-img">
                    <picture>
                    <source
                        type="image/webp"
                        srcset="/img/popular-img.webp"
                    />
                    <img
                        src="/img/popular-img.jpg"
                        alt="product card img"
                        title="product card img"
                    />
                    </picture>
                </div>
                <div class="suggestions__card-body">
                    <div class="suggestions__card-title">
                    <p>
                        Название товара, очень длинное
                    </p>
                    </div>
                    <div class="suggestions__card-price d-flex flex-column">
                    <span>11 100 грн </span>
                    <del>120 грн</del>
                    </div>
                </div>
                </div>
            </div>
            <div class="suggestions__card col-12 col-md-6">
                <a href="#"></a>
                <div class="d-flex">
                <div class="suggestions__card-img">
                    <picture>
                    <source
                        type="image/webp"
                        srcset="/img/popular-img.webp"
                    />
                    <img
                        src="/img/popular-img.jpg"
                        alt="product card img"
                        title="product card img"
                    />
                    </picture>
                </div>
                <div class="suggestions__card-body">
                    <div class="suggestions__card-title">
                    <p>
                        Название товара, очень длинное
                    </p>
                    </div>
                    <div class="suggestions__card-price d-flex flex-column">
                    <span>11 100 грн </span>
                    <del>120 грн</del>
                    </div>
                </div>
                </div>
            </div>
            <div class="suggestions__card col-12 col-md-6">
                <a href="#"></a>
                <div class="d-flex">
                <div class="suggestions__card-img">
                    <picture>
                    <source
                        type="image/webp"
                        srcset="/img/popular-img.webp"
                    />
                    <img
                        src="/img/popular-img.jpg"
                        alt="product card img"
                        title="product card img"
                    />
                    </picture>
                </div>
                <div class="suggestions__card-body">
                    <div class="suggestions__card-title">
                    <p>
                        Название товара, очень длинное
                    </p>
                    </div>
                    <div class="suggestions__card-price d-flex flex-column">
                    <span>11 100 грн </span>
                    <del>120 грн</del>
                    </div>
                </div>
                </div>
            </div>
            <div class="suggestions__card col-12 col-md-6">
                <a href="#"></a>
                <div class="d-flex">
                <div class="suggestions__card-img">
                    <picture>
                    <source
                        type="image/webp"
                        srcset="/img/popular-img.webp"
                    />
                    <img
                        src="/img/popular-img.jpg"
                        alt="product card img"
                        title="product card img"
                    />
                    </picture>
                </div>
                <div class="suggestions__card-body">
                    <div class="suggestions__card-title">
                    <p>
                        Название товара, очень длинное
                    </p>
                    </div>
                    <div class="suggestions__card-price d-flex flex-column">
                    <span>11 100 грн </span>
                    <del>120 грн</del>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="suggestions__more">
            <a href="#">
            Все результаты поиска
            </a>
        </div>
        </div>
    </div>
    </div>
</header>