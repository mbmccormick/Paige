<div class="row">
    <form action="<?=option('base_uri')?>login/reset" method="post" class="form-vertical">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="email">Email Address</label>
                <div class="controls">
                    <input class="input-large email" id="email" name="email" type="text" />
                </div>
            </div>
        </fieldset>
        <div class="actions">
            <button type="submit" class="btn btn-primary">Reset</button>
        </div>
    </form>
</div>
