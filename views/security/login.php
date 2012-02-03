<div class="row">
    <form action="<?=option('base_uri')?>login" method="post" class="form-vertical">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="email">Email Address</label>
                <div class="controls">
                    <input class="input-large email" id="email" name="email" type="text" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password">Password</label>
                <div class="controls">
                    <input class="input-large" id="password" name="password" type="password" />
                    <label class="checkbox">
                        <input type="checkbox" name="rememberme" value="true" /> Remember me
                    </label>
                </div>
            </div>
        </fieldset>
        <br />
        <div class="actions">
            <button type="submit" class="btn btn-primary">Login</button><a class="pull-right" style="padding-top: 5px;" href="<?=option('base_uri')?>login/reset">Forgot your password?</a>
        </div>
    </form>
</div>
