<div class="row">
    <form action="<?=option('base_uri')?>login" method="post" class="form-stacked">
        <fieldset>
            <div class="clearfix">
                <label for="email">Email Address</label>
                <div class="input">
                    <input class="xlarge email" id="email" name="email" size="30" type="text">
                </div>
            </div>
            <div class="clearfix">
                <label for="password">Password</label>
                <div class="input">
                    <input class="xlarge" id="password" name="password" size="30" type="password">
                </div>
            </div>
            <div class="clearfix">
                <div class="input">
                    <input type="checkbox" id="rememberme" name="rememberme" value="true">
                    <span>Remember me</span>
                </div>
            </div>
        </fieldset>
        <div class="actions">
            <button type="submit" class="btn primary">Login</button>&nbsp;<a style="float: right; padding-top: 6px;" href="<?=option('base_uri')?>login/reset">Forgot your password?</a>
        </div>
    </form>
</div>
