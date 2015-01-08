<div class="page-signin">

    <div class="signin-header">
        <div class="container text-center">
            <section class="logo">
                <a href="#/">{{main.brand}}</a>
            </section>
        </div>
    </div>

    <div class="signin-body">
        <div class="container">
            <div class="form-container">

                <form class="form-horizontal" action="login" method="post">
                    <fieldset>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                <input type="email"
                                       class="form-control"
                                       placeholder="Email"
                                       name="username"
                                       >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </span>
                                <input type="password"
                                       class="form-control"
                                       placeholder="password"
                                       name="password"
                                       >
                            </div>
                        </div>
                        <div class="form-group">
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

</div>