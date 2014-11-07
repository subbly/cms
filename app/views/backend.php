<!DOCTYPE html>
<html lang="fr">
<head>

  <meta charset="UTF-8">

  <title>Subbly - Backend</title>

  <link rel="stylesheet" href="<?= URL::to('/themes/backend/assets/css/subbly.' . $environment . '.css'); ?>">

  <script src="<?= URL::to('/themes/backend/assets/lib/modernizr.js'); ?>"></script>
  <script src="<?= URL::to('/themes/backend/assets/lib/pace.min.js'); ?>"></script>

</head>
<body>
  <div class="login -active -logged" id="login">
    <div class="login-contenair">
      <img class src="<?= URL::to('/themes/backend/assets/img/logo.svg'); ?>" width="100" alt="logo Subbly">
      <form class="login-box" method="post" accept-charset="utf-8" target="postFrame" action="<?= url('/void'); ?>" id="login-form">
        <p class="login-msg" id="login-msg">
          Say “Hello shop”
        </p>
        <div class="form-row">
          <div class="form-field">
            <input type="text" class="form-input" name="email" placeholder="Email" id="login-email">
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <div class="form-row">
          <div class="form-field">
            <input type="password" class="form-input" name="password" placeholder="Password">
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <p class="btn-login">
          <button type="submit" class="btn" id="login-submit" data-loading-text="loading">
            Login in
          </button>
        </p>
        <p>
          <a href="javascript:;">
            Forget password
          </a>
        </p>
      </form><!-- /.login-box -->
    </div><!-- /.login-contenair -->
  </div><!-- /.login -->
  <div class="container">
    <section class="main-nav">
      <a href="javascript:;" class="main-logo js-trigger-go-home">
         <img class src="<?= URL::to('/themes/backend/assets/img/subbly-logo.svg'); ?>" width="100" alt="logo Subbly">
      </a>

      <div class="user-nav">
        <div class="user-nav-container">
          <a href="javascript:;" class="cur-d">
            My Shop
          </a>
          <span class="caret"></span>
          <ul class="user-nav-sub">
            <li>
              <a href="<?= URL::to('/' ); ?>" target="_blank">
                Visit my store
              </a>
            </li>
            <li>
              <a href="javascript:;" class="js-trigger-logout" onclick="Subbly.logout()">
                logout
              </a>
            </li>
          </ul><!-- /.user-nav-sub -->
        </div><!-- /.user-nav-container -->
      </div><!-- /.user-nav -->
      <hr>
      <ul class="bo-nav" id="bo-nav">
<!--         <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-home">
            Dashboard
          </a>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-orders active">
            Orders
          </a>
          <span class="badge product">9</span>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-customers">
            Customers
          </a>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-products">
            Products
          </a>
        </li>
        <li>
          <a href="javascript:;" class="fst-nav js-trigger-go-settings">
            Settings
          </a>
        </li>
        <li class="fst-nav js-trigger-go-hangard">
          <a href="javascript:;">
            Hangar
          </a>
        </li> -->
      </ul><!-- /.subbly-nav -->
    </section><!-- /.main-nav -->
    <section class="main-view" id="main-view"></section><!-- /.main-view -->
  </div><!-- /. container -->

  <!-- Force 3d acceleration always and forever :) -->
  <div style="-webkit-transform: translateZ(0)"></div>
  <!-- allow natural autocomplete on ajax form -->
  <iframe class="dp-n" name="postFrame"></iframe>
  <script>
    // Subbly constants
    var subblyConfig = {
        baseUrl: '/<?= Config::get( 'subbly.backendUri', 'backend' ); ?>/'
      , apiUrl:  '<?= URL::to('/api/v1'); ?>/'
      , env:     '<?= $environment ?>'
      , debug:   <?= (bool) Config::get('app.debug'); ?>

    }
  </script>
  <script src="<?= URL::to('/themes/backend/assets/js/subbly.' . $environment . '.js') ?>"></script>
  <script src="<?= URL::to('/themes/plugins/customers.js') ?>"></script>
  <script src="<?= URL::to('/themes/plugins/dashboard.js') ?>"></script>
</body>
</html>
