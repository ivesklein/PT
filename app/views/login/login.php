<!--<![endif]--><head><style></style><style type="text/css">@charset "UTF-8";[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide{display:none !important;}ng\:form{display:block;}.ng-animate-block-transitions{transition:0s all!important;-webkit-transition:0s all!important;}.ng-hide-add-active,.ng-hide-remove{display:block!important;}</style>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Web Application</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <style>.file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }.file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }.file-input-name { margin-left: 8px; }</style><link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic" rel="stylesheet" type="text/css">
        <!-- needs images, font... therefore can not be part of ui.css -->
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css">
        <!-- end needs images -->

            <link rel="stylesheet" href="styles/main.css">

    <style></style><style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style><style id="holderjs-style" type="text/css"></style></head>
    <body data-ng-app="app" id="app" data-custom-background="" data-off-canvas-nav="" class="ng-scope body-special">
        <!--[if lt IE 9]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div data-ng-controller="AppCtrl" class="ng-scope">
            

            <div class="view-container">
                <!-- ngView:  --><section data-ng-view="" id="content" class="animate-fade-up ng-scope"><div class="page-signin ng-scope">

    <div class="signin-header" style="background:#121212;">
        <div class="container text-center">
            <section class="logo">
                <a href="#/" class="ng-binding" style="color:white;"><img src="images/logo.png"><h3>PROCESO DE TITULACIÓN</h3></a>
            </section>
        </div>
    </div>

    <div class="signin-body">
        <div class="container">
            <div class="form-container">

                <form class="form-horizontal ng-pristine ng-valid" action="login" method="post">
                    <fieldset>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                <input type="email" class="form-control" placeholder="Email" name="username">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </span>
                                <input type="password" class="form-control" placeholder="password" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <?php if(Session::has('login_errors')) { ?>
                            <div class="alert ng-isolate-scope alert-danger alert-dismissable">    
                                <div ng-transclude=""><span class="ng-binding ng-scope">Usuario o contraseña inválido.</span></div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Log in">
                        </div>
                    </fieldset>
                </form>

                <section>
                    <p class="text-center"><a href="javascript:;">Forgot your password?</a></p>
                    <p class="text-center text-muted text-small">Don't have an account yet? <a href="#/pages/signup">Sign up</a></p>
                </section>
                
            </div>
        </div>
    </div>

</div></section>
            </div>
        </div>


        <!--script src="scripts/vendor.js"></script-->

        <script src="scripts/ui.js"></script>

        <!--script src="scripts/app.js"></script-->
    
</body>