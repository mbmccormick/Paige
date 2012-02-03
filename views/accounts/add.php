<div class="row">
    <div class="span6">
        <form action="<?=option('base_uri')?>accounts/add" method="post" class="form-vertical">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="name">Name</label>
                    <div class="controls">
                        <input class="input-xlarge" id="name" name="name" type="text" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                        <input class="input-xlarge email" id="email" name="email" type="text" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="timezone">Timezone</label>
                    <div class="controls">
                        <select class="input-xlarge" id="timezone" name="timezone">
                            <option value="5">Eastern</option>
                            <option value="6">Central</option>
                            <option value="7">Mountain</option>
                            <option value="8">Pacific</option>
                        </select>
                    </div>
                </div>
                <br />
                <div class="control-group">
                    <label class="control-label" for="plan">Plan</label>
                    <div class="controls">
                        <select class="input-xlarge" id="plan" name="plan">
                            <option value="1">Small - $10/month</option>
                            <option value="2">Medium - $15/month</option>
                            <option value="3">Large - $20/month</option>
                        </select>
                    </div>
                </div>
                <br />
                <div class="control-group">
                    <label class="control-label" for="cardnumber">Credit Card Number</label>
                    <div class="controls">
                        <input class="input-xlarge" id="cardnumber" name="cardnumber" autocomplete="off" type="text" value="4242424242424242" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="cardexpmonth">Expiration</label>
                    <div class="controls">
                        <select class="input-small" id="cardexpmonth" name="cardexpmonth" autocomplete="off">
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select class="input-small" id="cardexpyear" name="cardexpyear" autocomplete="off">
                            <option value="2012">2012</option>
                            <option value="2013" selected="true">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="cardcvc">Security Code</label>
                    <div class="controls">
                        <input class="input-small" id="cardcvc" name="cardcvc" autocomplete="off" size="4" type="text" value="123" />
                    </div>
                </div>
                <br />
                <div class="control-group">
                    <label class="control-label" for="password">Password</label>
                    <div class="controls">
                        <input class="input-xlarge" id="password" name="password" type="password" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="passwordconfirm">Confirm Password</label>
                    <div class="controls">
                        <input class="input-xlarge" id="passwordconfirm" name="passwordconfirm" type="password" />
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Account</button>&nbsp;<button type="reset" class="btn">Cancel</button>
            </div>
        </form>
    </div>
    <div class="span2">
        <h5>Page Description</h5>
        <p>This page allows you to create a new account for this application. Make sure that the email address you provide is valid, as it will be used to send important account-related news.</p>
        <br />
    </div>
</div>
