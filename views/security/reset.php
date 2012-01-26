<div class="row">
    <form action="<?=option('base_uri')?>login/reset" method="post" class="form-stacked">
        <p>To reset your account password, please enter your email address below. A new password will be sent to the email address on file.</p>
        <fieldset>
            <div class="clearfix">
                <label for="email">Email Address</label>
                <div class="input">
                    <input class="xlarge email" id="email" name="email" size="30" type="text">
                </div>
            </div>
        </fieldset>
        <div class="actions">
            <button type="submit" class="btn primary">Reset</button>
        </div>
    </form>
</div>
