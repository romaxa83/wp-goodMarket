    <main class="main-content subcategory-page">
      <div class="page-breadcrumb">
        <div class="container">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="#">Главная</a>
              </li>
              <li class="breadcrumb-item">
                <a href="#">Канцтовары</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                <span>
                  Блокноты
                </span>
              </li>
            </ol>
          </nav>
        </div>
      </div>

      <div class="subcategory-section">
        <div class="container">
          <div class="row flex-nowrap">
            <div
              class="page-filter d-xl-block col-12 col-xl-3 col-xxl-2"
              id="pageFilter"
            >
              <header
                class="page-filter__header d-flex align-items-center d-xl-none"
              >
                <button
                  class="page-filter__close-btn"
                  type="button"
                  id="closeFilter"
                ></button>
                <h2>
                  Фильтр
                </h2>
              </header>
              <form>
                <div class="page-filter__tegs-wrapper d-flex flex-wrap">
                  <div class="page-filter__teg">
                    <span>
                      A6
                    </span>
                    <span class="page-filter__close-teg"> </span>
                  </div>
                  <div class="page-filter__teg">
                    <span>
                      Производитель
                    </span>
                    <span class="page-filter__close-teg"> </span>
                  </div>
                </div>

                <div class="page-filter__reset-btn-wrapper">
                  <button class="page-filter__reset-btn" type="button">
                    Сбросить все
                  </button>
                </div>

                <div class="form-group page-filter__price">
                  <div
                    class="form-collapse"
                    data-toggle="collapse"
                    data-target="#filterPrice"
                    role="button"
                    aria-expanded="false"
                    aria-controls="filterPrice"
                  >
                    <h3>
                      Цена, грн
                    </h3>
                  </div>
                  <div id="filterPrice" class="collapse show">
                    <div
                      class="d-flex align-items-center flex-wrap justify-content-between"
                    >
                      <span>
                        от
                      </span>
                      <input
                        class="form-control input-number"
                        type="number"
                        name="#"
                        id="fromPrice"
                        min="0"
                        max="5000"
                        step="10"
                      />
                      <span>-</span>
                      <input
                        class="form-control input-number"
                        type="number"
                        name="#"
                        id="toPrice"
                        min="0"
                        max="5000"
                        step="10"
                      />

                      <div class="custom-range__wrapper w-100">
                        <input
                          class="custom-range"
                          type="text"
                          id="customRange"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div
                    class="form-collapse"
                    data-toggle="collapse"
                    data-target="#filterProducer"
                    role="button"
                    aria-expanded="false"
                    aria-controls="filterProducer"
                  >
                    <h3>
                      Производитель
                    </h3>
                  </div>

                  <div id="filterProducer" class="collapse show">
                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-1"
                        class="custom-control-input page-filter__checkbox "
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-1" class="custom-control-label"
                        >Название производителя</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-2"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-2" class="custom-control-label"
                        >Производитель</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-3"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-3" class="custom-control-label"
                        >Название производителя</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-4"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-4" class="custom-control-label"
                        >Производителя</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-5"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-5" class="custom-control-label"
                        >Название производителя</label
                      >
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div
                    class="form-collapse"
                    data-toggle="collapse"
                    data-target="#filterFormat"
                    role="button"
                    aria-expanded="false"
                    aria-controls="filterFormat"
                  >
                    <h3>
                      Формат
                    </h3>
                  </div>

                  <div id="filterFormat" class="collapse show">
                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-6"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-6" class="custom-control-label"
                        >A4</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-7"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-7" class="custom-control-label"
                        >A5</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-8"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-8" class="custom-control-label"
                        >A6</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-9"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-9" class="custom-control-label"
                        >A7</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-10"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-10" class="custom-control-label"
                        >B5</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-11"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-11" class="custom-control-label"
                        >B6</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-12"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-12" class="custom-control-label"
                        >B4</label
                      >
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div
                    class="form-collapse"
                    data-toggle="collapse"
                    data-target="#filterRuler"
                    role="button"
                    aria-expanded="false"
                    aria-controls="filterRuler"
                  >
                    <h3>
                      Линовка
                    </h3>
                  </div>

                  <div id="filterRuler" class="collapse show">
                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-13"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-13" class="custom-control-label"
                        >Без линовки</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-15"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-15" class="custom-control-label"
                        >Клетка</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-16"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-16" class="custom-control-label"
                        >Линия</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-17"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-17" class="custom-control-label"
                        >Точка</label
                      >
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div
                    class="form-collapse"
                    data-toggle="collapse"
                    data-target="#filterQuantity"
                    role="button"
                    aria-expanded="false"
                    aria-controls="filterQuantity"
                  >
                    <h3>
                      Количество листов
                    </h3>
                  </div>

                  <div id="filterQuantity" class="collapse show">
                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-18"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-18" class="custom-control-label"
                        >48</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-19"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-19" class="custom-control-label"
                        >80</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-20"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-20" class="custom-control-label"
                        >90</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-21"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-21" class="custom-control-label"
                        >96</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-22"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-22" class="custom-control-label"
                        >100</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-23"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-23" class="custom-control-label"
                        >120</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-24"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-24" class="custom-control-label"
                        >128</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-25"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-25" class="custom-control-label"
                        >150</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-26"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-26" class="custom-control-label"
                        >160</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-27"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-27" class="custom-control-label"
                        >200</label
                      >
                    </div>

                    <div class="custom-control custom-checkbox">
                      <input
                        id="check-28"
                        class="custom-control-input page-filter__checkbox"
                        type="checkbox"
                        name="#"
                      />
                      <label for="check-28" class="custom-control-label"
                        >256</label
                      >
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="subcategory-section__grid" id="subcategory-grid">
              <div
                class="subcategory-section__header-wrapper d-flex justify-content-between flex-wrap"
              >
                <div
                  class="subcategory-section__headline d-flex w-100 align-items-baseline"
                >
                  <h2>
                    Блокноты
                  </h2>
                  <span>
                    500 товаров
                  </span>
                </div>

                <div
                  class="filter-btn d-xl-none subcategory-section__filter-btn"
                >
                  <button class="btn btn-sm" type="button" id="openFilter">
                    <svg width="15" height="15">
                      <use xlink:href="img/spritemap.svg#sprite-filter"></use>
                    </svg>
                    Фильтр
                  </button>
                </div>

                <div class="d-flex subcategory-section__sorting d-flex">
                  <p class="d-none d-md-block">
                    Сортировка:
                  </p>
                  <select class="custom-select">
                    <option value>По умолчанию</option>
                    <option value="from-cheap">От дешевых к дорогим</option>
                    <option value="from-dear">От дорогих к дешевым</option>
                    <option value="popular">Популярные</option>
                    <option value="novelty">Новинки</option>
                    <option value="promotionaluz">Акционные</option>
                    <option value="by-rating">По рейтингу</option>
                  </select>
                </div>

                <div class="grid-toggler subcategory-section__grid-toggler">
                  <button
                    class="grid-toggler__item"
                    type="button"
                    id="listGrid"
                  >
                    <svg width="27" height="27">
                      <use
                        xlink:href="img/spritemap.svg#sprite-list-grid"
                      ></use>
                    </svg>
                  </button>
                  <button
                    class="grid-toggler__item active"
                    type="button"
                    id="rowGrid"
                  >
                    <svg width="23" height="23">
                      <use xlink:href="img/spritemap.svg#sprite-row-grid"></use>
                    </svg>
                  </button>
                </div>
              </div>

              <div class="subcategory-section__wrapper">
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites active">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img.webp"
                        />
                        <img
                          src="/img/catalog-img.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img-2.webp"
                        />
                        <img
                          src="/img/catalog-img-2.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart product-card__cart--added open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites active">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img.webp"
                        />
                        <img
                          src="/img/catalog-img.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img-2.webp"
                        />
                        <img
                          src="/img/catalog-img-2.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--not-available">
                          <p>
                            Нет в наличии
                          </p>
                        </div>
                        <div
                          class="product-card__cart product-card__cart--not-available"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Предзаказ
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img.webp"
                        />
                        <img
                          src="/img/catalog-img.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img-2.webp"
                        />
                        <img
                          src="/img/catalog-img-2.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--not-available">
                          <p>
                            Нет в наличии
                          </p>
                        </div>
                        <div
                          class="product-card__cart product-card__cart--not-available"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Предзаказ
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="subcategory-section__item subcategory-section__item--stock"
                >
                  <div class="card-stock">
                    <a class="card-stock__link" href="#"></a>
                    <div class="card-stock__img">
                      <picture>
                        <source
                          media="(min-width: 1200px)"
                          type="image/webp"
                          srcset="/img/stock-img_xl.webp"
                        />
                        <source
                          media="(min-width: 1200px)"
                          srcset="/img/stock-img_xl.jpg"
                        />
                        <!--	<source
													media="(min-width: 768px)"
													type="image/webp"
													srcset="/img/top_banner-md.webp"
												/>
												<source
													media="(min-width: 768px)"
													srcset="/img/top_banner-md.jpg"
												/> -->
                        <source
                          type="image/webp"
                          srcset="/img/stock-img_sm.webp"
                        />
                        <img
                          src="/img/stock-img_sm.jpg"
                          alt="top banner"
                          title="top banner"
                          class="top-banner__img"
                        />
                      </picture>
                    </div>
                    <div
                      class="card-stock__desc card-stock__desc--bg-secondary text-center"
                    >
                      <h3>
                        Скидки на товары для дома
                      </h3>
                      <p>
                        50% на домашний текстиль
                      </p>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites active">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img.webp"
                        />
                        <img
                          src="/img/catalog-img.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img-2.webp"
                        />
                        <img
                          src="/img/catalog-img-2.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img-2.webp"
                        />
                        <img
                          src="/img/catalog-img-2.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img-2.webp"
                        />
                        <img
                          src="/img/catalog-img-2.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="subcategory-section__item">
                  <div class="card product-card">
                    <div class="product-card__status-wrap">
                      <div
                        class="product-card__status product-card__status--sale"
                      >
                        <p>-20%</p>
                      </div>
                      <div
                        class="product-card__status product-card__status--new"
                      >
                        <p>new</p>
                      </div>
                    </div>
                    <button class="product-card__favorites">
                      <svg width="27" height="24">
                        <use
                          xlink:href="img/spritemap.svg#sprite-heart-outline"
                        ></use>
                      </svg>
                    </button>
                    <div class="product-card__img">
                      <picture>
                        <source
                          type="image/webp"
                          srcset="/img/catalog-img-2.webp"
                        />
                        <img
                          src="/img/catalog-img-2.jpg"
                          alt="product card img"
                          title="product card img"
                        />
                      </picture>
                    </div>
                    <div class="product-card__body">
                      <div class="product-card__title">
                        <a href="#" class="product-card__goods-link"></a>
                        <p>
                          Название товара, очень длинное название товара
                        </p>
                      </div>
                      <div
                        class="product-card__reviews d-flex justify-content-between"
                      >
                        <div class="product-card__reviews-stars reviews-stars">
                          <input
                            type="hidden"
                            class="rating"
                            disabled
                            value="2"
                          />
                        </div>
                        <div class="product-card__reviews-link">
                          <a href="#">127 отзывов</a>
                        </div>
                      </div>
                      <div
                        class="product-card__price d-flex justify-content-between"
                      >
                        <div class="product-card__price--actual">
                          <p>
                            11 100 грн
                            <del>120 грн</del>
                          </p>
                        </div>
                        <div
                          class="product-card__cart open-popup"
                          data-popup="#cartPopup"
                        >
                          <button>
                            <svg width="24" height="22">
                              <use
                                xlink:href="img/spritemap.svg#sprite-shopping-cart"
                              ></use>
                            </svg>
                            Купить
                          </button>
                        </div>
                      </div>
                      <div class="product-card__hidden-desc">
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipiscing
                          elit. Ut feugiat blandit quam eget scelerisque.
                          Curabitur id interdum ipsum. Nullam ultrices nec lorem
                          at sagittis. Aliquam ut odio molestie, iaculis velit
                          eu, porta lacus. Nulla facilisi. Fusce a sapien
                          tortor.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="position-relative">
                <div class="load-more-card">
                  <a href="#" class="load-more-card__link"></a>
                  <div class="load-more-card__img">
                    <svg width="26" height="27">
                      <use xlink:href="img/spritemap.svg#sprite-refresh"></use>
                    </svg>
                  </div>
                  <div class="load-more-card__desc">
                    <p>
                      Показать ещё
                    </p>
                  </div>
                </div>
                <div
                  class="pagination-wrapper mx-auto col-12 col-xl-3 mx-xl-0 d-flex justify-content-center"
                >
                  <nav aria-label="Page navigation">
                    <ul class="pagination">
                      <li class="page-item active" aria-current="page">
                        <span class="page-link">1</span>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">2</a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">3</a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">...</a>
                      </li>
                      <li class="page-item">
                        <a class="page-link" href="#">15</a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <section class="section-seo section-seo--subcategory">
        <div class="container">
          <div class="section-seo__desc section-seo__desc--dark">
            <h1>
              Заголовок Сео Текста
            </h1>
            <p>
              Lorem Ipsum - это текст-"рыба", часто используемый в печати и
              вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов
              на латинице с начала XVI века. В то время некий безымянный
              печатник создал большую коллекцию размеров и форм шрифтов,
              используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не
              только успешно пережил без заметных изменений пять веков, но и
              перешагнул в электронный дизайн. Его популяризации в новое время
              послужили публикация листов Letraset с образцами Lorem Ipsum в
              60-х годах и, в более недавнее время, программы электронной
              вёрстки типа Aldus PageMaker, в шаблонах которых используется
              Lorem Ipsum.
            </p>
            <p>
              Многие думают, что Lorem Ipsum - взятый с потолка псевдо-латинский
              набор слов, но это не совсем так. Его корни уходят в один фрагмент
              классической латыни 45 года н.э., то есть более двух тысячелетий
              назад. Ричард МакКлинток, профессор латыни из колледжа
              Hampden-Sydney, штат Вирджиния, взял одно из самых странных слов в
              Lorem Ipsum, "consectetur", и занялся его поисками в классической
              латинской литературе. В результате он нашёл неоспоримый
              первоисточник Lorem Ipsum в разделах 1.10.32 и 1.10.33 книги "de
              Finibus Bonorum et Malorum" ("О пределах добра и зла"), написанной
              Цицероном в 45 году н.э. Этот трактат по теории этики был очень
              популярен в эпоху Возрождения. Первая строка Lorem Ipsum, "Lorem
              ipsum dolor sit amet..", происходит от одной из строк в разделе
              1.10.32
            </p>
            <p>
              Многие думают, что Lorem Ipsum - взятый с потолка псевдо-латинский
              набор слов, но это не совсем так. Его корни уходят в один фрагмент
              классической латыни 45 года н.э., то есть более двух тысячелетий
              назад. Ричард МакКлинток, профессор латыни из колледжа
              Hampden-Sydney, штат Вирджиния, взял одно из самых странных слов в
              Lorem Ipsum, "consectetur", и занялся его поисками в классической
              латинской литературе. В результате он нашёл неоспоримый
              первоисточник Lorem Ipsum в разделах 1.10.32 и 1.10.33 книги "de
              Finibus Bonorum et Malorum" ("О пределах добра и зла"), написанной
              Цицероном в 45 году н.э. Этот трактат по теории этики был очень
              популярен в эпоху Возрождения. Первая строка Lorem Ipsum, "Lorem
              ipsum dolor sit amet..", происходит от одной из строк в разделе
              1.10.32
            </p>
            <p>
              Классический текст Lorem Ipsum, используемый с XVI века, приведён
              ниже. Также даны разделы 1.10.32 и 1.10.33 "de Finibus Bonorum et
              Malorum" Цицерона и их английский перевод, сделанный H. Rackham,
              1914 год.
            </p>
          </div>
          <div class="section-seo__read-more">
            <span id="readMore">Читать далее</span>
          </div>
        </div>
      </section>
    </main>