<div class="row">
    <div class="span10">
        <form action="<?=option('base_uri')?>accounts/add" method="post" class="form-stacked">
            <fieldset>
                <div class="clearfix">
                    <label for="name">Name</label>
                    <div class="input">
                        <input class="xlarge" id="name" name="name" size="30" type="text">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="email">Email</label>
                    <div class="input">
                        <input class="xlarge" id="email" name="email" size="30" type="text">
                    </div>
                </div>
				<div class="clearfix">
                    <label for="areacode">Area Code</label>
                    <div class="input">
                        <input class="xlarge" id="areacode" name="areacode" size="30" type="text">
                    </div>
                </div>
				<br />
                <div class="clearfix">
                    <label for="plan">Plan</label>
                    <div class="input">
                        <select class="xlarge" id="plan" name="plan">
							<option value="1">Level 1</option>
							<option value="2">Level 2</option>
							<option value="3">Level 3</option>
						</select>
                    </div>
                </div>
				<br />
                <div class="clearfix">
                    <label for="cardnumber">Credit Card Number</label>
                    <div class="input">
                        <input class="xlarge" id="cardnumber" name="cardnumber" autocomplete="off" size="20" type="text">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="cardexpmonth">Expiration</label>
                    <div class="input">
                        <select class="small" id="cardexpmonth" name="cardexpmonth" autocomplete="off">
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
						<select class="small" id="cardexpyear" name="cardexpyear" autocomplete="off">
							<option value="2012">2012</option>
							<option value="2013">2013</option>
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
                <div class="clearfix">
                    <label for="cardcvc">Security Code</label>
                    <div class="input">
                        <input class="small" id="cardcvc" name="cardcvc" autocomplete="off" size="4" type="text">
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="actions">
                <button type="submit" class="btn primary">Create Account</button>&nbsp;<button type="reset" class="btn">Cancel</button>
            </div>
        </form>
    </div>
    <div class="span4">
        <h5>Page Description</h5>
        <p>This page allows you to create a new account for this application. Make sure that the email address you provide is valid, as it will be used to send important account-related news.</p>
        <br />
    </div>
</div>
