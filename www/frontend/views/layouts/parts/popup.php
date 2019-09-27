<div id="loginFormPopup" class="white-popup mfp-hide site-popup">
      <nav>
        <div class="nav nav-tabs site-popup__tabs" role="tablist">
          <a
            class="site-popup__tabs-item active"
            data-toggle="tab"
            href="#login-form"
            role="tab"
            aria-controls="login-form"
            aria-selected="true"
          >
            Вход
          </a>
          <a
            class="site-popup__tabs-item"
            data-toggle="tab"
            href="#registration-form"
            role="tab"
            aria-controls="registration-form"
            aria-selected="false"
          >
            Регистрация
          </a>
        </div>
      </nav>
      <div class="tab-content">
        <div
          class="tab-pane fade show active site-popup__tab-pane"
          id="login-form"
          role="tabpanel"
          aria-labelledby="login-form"
        >
          <form class="needs-validation" novalidate>
            <div class="log-in-with site-popup__login-with">
              <button
                class="log-in-with__item log-in-with__item--google"
                type="button"
              >
                <svg width="21" height="21">
                  <use xlink:href="img/spritemap.svg#sprite-google"></use>
                </svg>
                Google
              </button>
              <button
                class="log-in-with__item log-in-with__item--facebook"
                type="button"
              >
                <svg width="9" height="18">
                  <use xlink:href="img/spritemap.svg#sprite-facebook"></use>
                </svg>
                Facebook
              </button>
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control login-validation"
                placeholder="Ваш телефон / e-mail"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="password"
                class="form-control"
                placeholder="Пароль"
                required
              />
            </div>
            <button class="btn btn-primary mx-auto d-block mt-4" type="submit">
              Войти
            </button>
          </form>
          <div class="site-popup__restore-pas">
            <button type="button" class="open-popup" data-popup="#restoreFormPopup">
              Восстановить пароль
            </button>
          </div>
        </div>
        <div
          class="tab-pane fade site-popup__tab-pane site-popup__tab-pane--reg"
          id="registration-form"
          role="tabpanel"
          aria-labelledby="registration-form"
        >
          <form class="needs-validation" novalidate>
            <div class="log-in-with site-popup__login-with">
              <button
                class="log-in-with__item log-in-with__item--google"
                type="button"
              >
                <svg width="21" height="21">
                  <use xlink:href="img/spritemap.svg#sprite-google"></use>
                </svg>
                Google
              </button>
              <button
                class="log-in-with__item log-in-with__item--facebook"
                type="button"
              >
                <svg width="9" height="18">
                  <use xlink:href="img/spritemap.svg#sprite-facebook"></use>
                </svg>
                Facebook
              </button>
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                placeholder="Ваше имя"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control login-validation"
                placeholder="Ваш телефон / e-mail"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="password"
                class="form-control"
                placeholder="Пароль"
                required
              />
            </div>
            <button class="btn btn-primary mx-auto d-block mt-4" type="submit">
              Войти
            </button>
          </form>
        </div>
      </div>
    </div>

    <div
      id="feedbackPopup"
      class="white-popup mfp-hide site-popup site-popup--feedback"
    >
      <nav>
        <div class="nav nav-tabs site-popup__tabs">
          <span class="site-popup__tabs-item">
            Обратная связь
          </span>
        </div>
      </nav>
      <div class="tab-content">
        <div class="site-popup__tab-pane">
          <form class="needs-validation" novalidate data-successfully="#messageSent">
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                placeholder="Ваше имя"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="tel"
                class="form-control phone-mask"
                placeholder="Ваш телефон"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="email"
                class="form-control email-validation"
                placeholder="Ваш e-mail"
                required
              />
            </div>
            <div class="form-group form-group--textarea">
              <textarea
                class="form-control form-control--textarea"
                placeholder="Ваше сообщение"
                required
              ></textarea>
            </div>
            <button class="btn btn-primary mx-auto d-block mt-4" type="submit">
              Отправить
            </button>
          </form>
        </div>
      </div>
    </div>

    <div
      id="cartPopup"
      class="white-popup mfp-hide cart-popup site-popup site-popup--feedback"
    >
      <nav>
        <div class="nav nav-tabs site-popup__tabs">
          <span class="site-popup__tabs-item">
            Товар добавлен в корзину
          </span>
        </div>
      </nav>
      <div class="site-popup__tab-pane">
        <div class="cart-popup__card d-flex">
          <div class="cart-popup__card-img">
            <picture>
              <source
                type="image/webp"
                srcset="img/product-card-img.webp"
              />
              <img
                src="img/product-card-img.jpg"
                alt="product card img"
                title="product card img"
              />
            </picture>
          </div>

          <div class="cart-popup__card-body d-flex flex-wrap">
            <div class="cart-popup__card-title">
              <a href="#" class="cart-popup__card-link"></a>
              <h3>
                Название товара, очень длинное
              </h3>
            </div>
            <div class="cart-popup__card-price">
              <p>
                11 100 грн
              </p>
            </div>

            <div class="cart-popup__card-amount input-amount">
              <form>
                <div class="input-amount__form-group number-spinner">
                  <span
                    class="input-amount__controler input-amount__minus"
                    data-dir="dwn"
                  ></span>
                  <input
                    type="text"
                    class="input-amount__control form-control"
                    value="1"
                  />
                  <span
                    class="input-amount__controler input-amount__plus"
                    data-dir="up"
                  ></span>
                </div>
              </form>
            </div>

            <div class="cart-popup__card-total">
              <p>
                111 100 грн
              </p>
            </div>

            <div class="cart-popup__card-del">
              <button type="button"></button>
            </div>
          </div>
        </div>
      </div>
      <div class="site-popup__tab-pane">
        <div class="cart-popup__card d-flex">
          <div class="cart-popup__card-img">
            <picture>
              <source
                type="image/webp"
                srcset="img/product-card-img.webp"
              />
              <img
                src="img/product-card-img.jpg"
                alt="product card img"
                title="product card img"
              />
            </picture>
          </div>

          <div class="cart-popup__card-body d-flex flex-wrap">
            <div class="cart-popup__card-title">
              <a href="#" class="cart-popup__card-link"></a>
              <h3>
                Название товара, очень длинное
              </h3>
            </div>
            <div class="cart-popup__card-price">
              <p>
                11 100 грн
              </p>
            </div>

            <div class="cart-popup__card-amount input-amount">
              <form>
                <div class="input-amount__form-group number-spinner">
                  <span
                    class="input-amount__controler input-amount__minus"
                    data-dir="dwn"
                  ></span>
                  <input
                    type="text"
                    class="input-amount__control form-control"
                    value="1"
                  />
                  <span
                    class="input-amount__controler input-amount__plus"
                    data-dir="up"
                  ></span>
                </div>
              </form>
            </div>

            <div class="cart-popup__card-total">
              <p>
                111 100 грн
              </p>
            </div>

            <div class="cart-popup__card-del">
              <button type="button"></button>
            </div>
          </div>
        </div>
      </div>

      <div
        class="cart-popup__total d-flex justify-content-between align-items-center"
      >
        <p>
          Итого:
        </p>
        <span>
          111 100 грн
        </span>
      </div>

      <div
        class="cart-popup__checkout-wrapper d-flex justify-content-between align-items-center"
      >
        <button class="cart-popup__continue close-popup">
          Продолжить покупки
        </button>
        <button class="btn btn-primary cart-popup__checkout">
          Оформить заказ
        </button>
      </div>
    </div>

    <div
    id="cartAllOrdersPopup"
    class="white-popup mfp-hide cart-popup site-popup site-popup--feedback"
    >
      <nav>
        <div class="nav nav-tabs site-popup__tabs">
          <span class="site-popup__tabs-item">
            Корзина
          </span>
        </div>
      </nav>
      <div class="site-popup__tab-pane">
        <div class="cart-popup__card d-flex">
          <div class="cart-popup__card-img">
            <picture>
              <source
                type="image/webp"
                srcset="img/product-card-img.webp"
              />
              <img
                src="img/product-card-img.jpg"
                alt="product card img"
                title="product card img"
              />
            </picture>
          </div>

          <div class="cart-popup__card-body d-flex flex-wrap">
            <div class="cart-popup__card-title">
              <a href="#" class="cart-popup__card-link"></a>
              <h3>
                Название товара, очень длинное
              </h3>
            </div>
            <div class="cart-popup__card-price">
              <p>
                11 100 грн
              </p>
            </div>

            <div class="cart-popup__card-amount input-amount">
              <form>
                <div class="input-amount__form-group number-spinner">
                  <span
                    class="input-amount__controler input-amount__minus"
                    data-dir="dwn"
                  ></span>
                  <input
                    type="text"
                    class="input-amount__control form-control"
                    value="1"
                  />
                  <span
                    class="input-amount__controler input-amount__plus"
                    data-dir="up"
                  ></span>
                </div>
              </form>
            </div>

            <div class="cart-popup__card-total">
              <p>
                111 100 грн
              </p>
            </div>

            <div class="cart-popup__card-del">
              <button type="button"></button>
            </div>
          </div>
        </div>
      </div>
      <div class="site-popup__tab-pane">
        <div class="cart-popup__card d-flex">
          <div class="cart-popup__card-img">
            <picture>
              <source
                type="image/webp"
                srcset="img/product-card-img.webp"
              />
              <img
                src="img/product-card-img.jpg"
                alt="product card img"
                title="product card img"
              />
            </picture>
          </div>

          <div class="cart-popup__card-body d-flex flex-wrap">
            <div class="cart-popup__card-title">
              <a href="#" class="cart-popup__card-link"></a>
              <h3>
                Название товара, очень длинное
              </h3>
            </div>
            <div class="cart-popup__card-price">
              <p>
                11 100 грн
              </p>
            </div>

            <div class="cart-popup__card-amount input-amount">
              <form>
                <div class="input-amount__form-group number-spinner">
                  <span
                    class="input-amount__controler input-amount__minus"
                    data-dir="dwn"
                  ></span>
                  <input
                    type="text"
                    class="input-amount__control form-control"
                    value="1"
                  />
                  <span
                    class="input-amount__controler input-amount__plus"
                    data-dir="up"
                  ></span>
                </div>
              </form>
            </div>

            <div class="cart-popup__card-total">
              <p>
                111 100 грн
              </p>
            </div>

            <div class="cart-popup__card-del">
              <button type="button"></button>
            </div>
          </div>
        </div>
      </div>

      <div
        class="cart-popup__total d-flex justify-content-between align-items-center"
      >
        <p>
          Итого:
        </p>
        <span>
          111 100 грн
        </span>
      </div>

      <div
        class="cart-popup__checkout-wrapper d-flex justify-content-between align-items-center"
      >
        <button class="cart-popup__continue close-popup">
          Продолжить покупки
        </button>
        <button class="btn btn-primary cart-popup__checkout">
          Оформить заказ
        </button>
      </div>
    </div>

    <div id="restoreFormPopup" class="white-popup mfp-hide site-popup site-popup--restore">
      <nav>
        <div class="nav nav-tabs site-popup__tabs" role="tablist">
          <a
            class="site-popup__tabs-item active"
            data-toggle="tab"
            href="#restore-form"
            role="tab"
            aria-controls="restore-form"
            aria-selected="true"
          >
            Вход
          </a>
          <a
            class="site-popup__tabs-item"
            data-toggle="tab"
            href="#restore-registration-form"
            role="tab"
            aria-controls="restore-registration-form"
            aria-selected="false"
          >
            Регистрация
          </a>
        </div>
      </nav>
      <div class="tab-content">
        <div
          class="tab-pane fade show active site-popup__tab-pane site-popup__tab-pane--restore"
          id="restore-form"
          role="tabpanel"
          aria-labelledby="login-form"
        >
          <form class="needs-validation" novalidate data-successfully="#passwordСhanged">
            <div class="form-group">
              <input
                type="text"
                class="form-control login-validation"
                placeholder="Ваш телефон / e-mail"
                required
              />
            </div>
            <button class="btn btn-primary mx-auto d-block mt-4" type="submit">
              Получить пароль
            </button>
          </form>
          <div class="site-popup__restore-pas">
            <button class="open-popup" data-popup="#loginFormPopup">
              Вспомнили пароль?
            </button>
          </div>
        </div>
        <div
          class="tab-pane fade site-popup__tab-pane site-popup__tab-pane--reg"
          id="restore-registration-form"
          role="tabpanel"
          aria-labelledby="registration-form"
        >
          <form class="needs-validation" novalidate>
            <div class="log-in-with site-popup__login-with">
              <button
                class="log-in-with__item log-in-with__item--google"
                type="button"
              >
                <svg width="21" height="21">
                  <use xlink:href="img/spritemap.svg#sprite-google"></use>
                </svg>
                Google
              </button>
              <button
                class="log-in-with__item log-in-with__item--facebook"
                type="button"
              >
                <svg width="9" height="18">
                  <use xlink:href="img/spritemap.svg#sprite-facebook"></use>
                </svg>
                Facebook
              </button>
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                placeholder="Ваше имя"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control login-validation"
                placeholder="Ваш телефон / e-mail"
                required
              />
            </div>
            <div class="form-group">
              <input
                type="password"
                class="form-control"
                placeholder="Пароль"
                required
              />
            </div>
            <button class="btn btn-primary mx-auto d-block mt-4" type="submit">
              Войти
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- successfully popup -->
    <div id="messageSent" class="white-popup mfp-hide successfully-popup">
      <div class="successfully-popup__title">
        <h3>
          Ваше сообщение <br>
          отправлено!
        </h3>
      </div>

      <div class="successfully-popup__message">
        <p>
          Наш менеджер свяжется с Вами в
          ближайшее время
        </p>
      </div>
    </div>

    <div id="thankForSubscribing" class="white-popup mfp-hide successfully-popup">
      <div class="successfully-popup__title">
          <h3>
            Благодарим за <br>
            подписку!
          </h3>
        </div>
  
        <div class="successfully-popup__message">
          <p>
            На Ваш e-mail отправлено письмо с
            ссылкой для подтверждение подписки
          </p>
        </div>
    </div>

    <div id="passwordСhanged" class="white-popup mfp-hide successfully-popup">
      <div class="successfully-popup__title">
          <h3>
            Пароль
            изменен!
          </h3>
        </div>
  
        <div class="successfully-popup__message">
          <p>
            На Ваш e-mail отправлено письмо с
            ссылкой для подтверждение подписки
          </p>
        </div>
    </div>