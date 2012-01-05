<div class="row">
    <div class="span10">
        <form action="<?=option('base_uri')?>members/add" method="post" class="form-stacked">
            <fieldset>
                <div class="clearfix">
                    <label for="name">Name</label>
                    <div class="input">
                        <input class="xlarge" id="name" name="name" size="30" type="text">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="email">Email Address</label>
                    <div class="input">
                        <input class="xlarge" id="email" name="email" size="30" type="text">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="phonenumber">Phone Number</label>
                    <div class="input">
                        <input class="xlarge" id="phonenumber" name="phonenumber" size="30" type="text">
                    </div>
                </div>
                <br />
                <div class="clearfix">
                    <div class="input">
                        <input type="checkbox" name="isadministrator" value="1">
                        <span>Receive text message notifications</span>
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="actions">
                <button type="submit" class="btn primary">Add Member</button>&nbsp;<button type="reset" class="btn">Cancel</button>
            </div>
        </form>
    </div>
    <div class="span4">
        <h5>Page Description</h5>
        <p>This page allows you to add a new team member to the application. Make sure that the email address you provide is valid, as it will be used to contact this team member.</p>
        <br />
    </div>
</div>