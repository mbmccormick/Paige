<div class="row">
    <div class="span6">
        <form action="<?=option('base_uri')?>accounts/<?=$account['id']?>/edit" method="post" class="form-vertical">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="name">Name</label>
                    <div class="controls">
                        <input class="input-xlarge" id="name" name="name" type="text" value="<?=$account['name']?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                        <input class="input-xlarge email" id="email" name="email" type="text" value="<?=$account['email']?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="timezone">Timezone</label>
                    <div class="controls">
                        <select class="input-xlarge" id="timezone" name="timezone">
                            <option value="5" <?php if ($account[timezone] == 5) echo "selected='true'"; ?>>Eastern</option>
                            <option value="6" <?php if ($account[timezone] == 6) echo "selected='true'"; ?>>Central</option>
                            <option value="7" <?php if ($account[timezone] == 7) echo "selected='true'"; ?>>Mountain</option>
                            <option value="8" <?php if ($account[timezone] == 8) echo "selected='true'"; ?>>Pacific</option>
                        </select>
                    </div>
                </div>
                <br />
                <div class="control-group">
                    <label class="control-label" for="phonenumber">Phone Number</label>
                    <div class="controls">
                        <input class="input-xlarge" id="phonenumber" name="phonenumber" type="text" value="<?=substr($account['phonenumber'], 0, 3) . '-' . substr($account['phonenumber'], 3, 3) . '-' . substr($account['phonenumber'], 6, 4)?>" readonly="true" />
                    </div>
                    <span class="help-block">If you need to change the phone number assigned to your account, please contact support.</span>
                </div>
                <div class="control-group">
                    <label class="control-label" for="hash">Page Hook URL</label>
                    <div class="controls">
                        <input class="input-xlarge" id="hash" name="hash" type="text" value="https://<?=$_SERVER['HTTP_HOST']?>/page/<?=$account['hash']?>" readonly="true" />
                    </div>
                    <span class="help-block">If you need to change the page hook URL assigned to your account, please contact support.</span>
                </div>
                <br />
                <div class="control-group">
                    <label class="control-label" for="plan">Plan</label>
                    <div class="controls">
                        <select class="input-xlarge" id="stripeplan" name="stripeplan">
                            <option value="1" <?php if ($account[stripeplan] == 1) echo "selected='true'"; ?>>Small - $10/month</option>
                            <option value="2" <?php if ($account[stripeplan] == 2) echo "selected='true'"; ?>>Medium - $15/month</option>
                            <option value="3" <?php if ($account[stripeplan] == 3) echo "selected='true'"; ?>>Large - $20/month</option>
                        </select>
                    </div>
                    <span class="help-block">Changes to your plan will take effect immediately and you will be pro-rated for this change at the next billing cycle.</span>
                </div>
                <br />
                <div class="control-group">
                    <label class="control-label" for="cardnumber">Credit Card Number</label>
                    <div class="controls">
                        <input class="input-xlarge" id="cardnumber" name="cardnumber" autocomplete="off" type="text" value="<?=$creditcard?>" readonly="true" />
                    </div>
                    <span class="help-block">If you need to change the credit card used for billing your account, please contact support. Your next charge will occur on <?=$nextcharge?>.</span>
                </div>
                <br />
                <div class="control-group">
                    <label class="control-label" for="newpassword">New Password</label>
                    <div class="controls">
                        <input class="input-xlarge exclude" id="newpassword" name="newpassword" type="password" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="newpasswordconfirm">Confirm New Password</label>
                    <div class="controls">
                        <input class="input-xlarge exclude" id="newpasswordconfirm" name="newpasswordconfirm" type="password" />
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Account</button>&nbsp;<a onclick="return confirm('Are you sure you want to delete your account?');" href="<?=option('base_uri')?>accounts/<?=$account['id']?>/delete" class="btn">Delete</a>
            </div>
        </form>
    </div>
    <div class="span2">
        <h5>Page Description</h5>
        <p>This page allows you to create a new account for this application. Make sure that the email address you provide is valid, as it will be used to send important account-related news.</p>
        <br />
    </div>
</div>
