<!doctype html>
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <link rel="shortcut icon" href="favicon.png" />
        <title>PT-UAI</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic" rel="stylesheet" type="text/css">
        <!-- needs images, font... therefore can not be part of ui.css -->
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css">
        <!-- end needs images -->

            <link rel="stylesheet" href="styles/main.css">

    </head>
    <body data-ng-app="app" id="app" data-custom-background="" data-off-canvas-nav="">
        <!--[if lt IE 9]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div data-ng-controller="AppCtrl">
            <div data-ng-hide="isSpecificPage()" data-ng-cloak="">
                <section data-ng-include=" 'views/header' " id="header" class="top-header"></section>

                <aside data-ng-include=" 'views/nav' " id="nav-container"></aside>
            </div>

            <div class="view-container">
                <section data-ng-view="" id="content" class="animate-fade-up"></section>
            </div>
        </div>


        <script src="scripts/vendor.js"></script>

        <script src="scripts/ui.js"></script>

        <script src="scripts/app.js"></script>
    </body>
</html>