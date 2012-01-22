<div class="row">
    <div class="span10">
        <form action="<?=option('base_uri')?>accounts/<?=$account['id']?>/edit" method="post" class="form-stacked">
            <fieldset>
                <div class="clearfix">
                    <label for="name">Name</label>
                    <div class="input">
                        <input class="xlarge" id="name" name="name" size="30" type="text" value="<?=$account['name']?>">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="email">Email</label>
                    <div class="input">
                        <input class="xlarge" id="email" name="email" size="30" type="text" value="<?=$account['email']?>">
                    </div>
                </div>
				<div class="clearfix">
                    <label for="phonenumber">Phone Number</label>
                    <div class="input">
                        <input class="xlarge" id="phonenumber" name="phonenumber" size="30" type="text" value="<?=substr($account['phonenumber'], 0, 3) . '-' . substr($account['phonenumber'], 3, 3) . '-' . substr($account['phonenumber'], 6, 4)?>" readonly="true">
                    </div>
					<span class="help-block">If you need to change the phone number assigned to your account, please contact support.</span>
                </div>
                <div class="clearfix">
                    <label for="hash">Page Hook URL</label>
                    <div class="input">
                        <input class="xlarge" id="hash" name="hash" size="30" type="text" value="http://paigeapp.com/page/<?=$account['hash']?>" readonly="true">
                    </div>
                    <span class="help-block">If you need to change the page hook URL assigned to your account, please contact support.</span>
                </div>
				<br />
                <div class="clearfix">
                    <label for="plan">Plan</label>
                    <div class="input">
                        <select class="xlarge" id="stripeplan" name="stripeplan">
							<option value="1" <?php if ($account[stripeplan] == 1) echo "selected='true'"; ?>>Small - $10/month</option>
							<option value="2" <?php if ($account[stripeplan] == 2) echo "selected='true'"; ?>>Medium - $15/month</option>
							<option value="3" <?php if ($account[stripeplan] == 3) echo "selected='true'"; ?>>Large - $20/month</option>
						</select>
                    </div>
					<span class="help-block">Changes to your plan will take effect immediately and you will be pro-rated for this change at the next billing cycle.</span>
                </div>
				<br />
                <div class="clearfix">
                    <label for="cardnumber">Credit Card Number</label>
                    <div class="input">
                        <input class="xlarge" id="cardnumber" name="cardnumber" autocomplete="off" size="20" type="text" value="<?=$creditcard?>" readonly="true">
                    </div>
					<span class="help-block">If you need to change the credit card used for billing your account, please contact support. Your next charge will occur on <?=$nextcharge?>.</span>
                </div>
                <br />
				<div class="clearfix">
                    <label for="newpassword">New Password</label>
                    <div class="input">
                        <input class="xlarge" id="newpassword" name="newpassword" size="30" type="password">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="newpasswordconfirm">Confirm New Password</label>
                    <div class="input">
                        <input class="xlarge" id="newpasswordconfirm" name="newpasswordconfirm" size="30" type="password">
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="actions">
                <button type="submit" class="btn primary">Save Account</button>&nbsp;<a onclick="return confirm('Are you sure you want to delete your account?');" href="<?=option('base_uri')?>accounts/<?=$account['id']?>/delete" class="btn">Delete</a>
            </div>
        </form>
    </div>
    <div class="span4">
        <h5>Page Description</h5>
        <p>This page allows you to create a new account for this application. Make sure that the email address you provide is valid, as it will be used to send important account-related news.</p>
        <br />
    </div>
</div>
<script type="text/javascript">

    $("form.form-stacked").submit(function validate() {
        var formData = $("form.form-stacked").serializeArray();
        for (var i=0; i < formData.length; i++) { 
            if (formData[i].contains("newpassword")) continue;
            if (formData[i].contains("newpasswordconfirm")) continue;
            
            if (!formData[i].value) { 
                alert("Please complete all fields, check your input, and try again.")                
                return false;
            }
        }
    });

</script>
