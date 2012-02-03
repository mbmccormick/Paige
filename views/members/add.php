<div class="row">
    <div class="span6">
        <form action="<?=option('base_uri')?>members/add" method="post" class="form-vertical">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="name">Name</label>
                    <div class="controls">
                        <input class="input-xlarge" id="name" name="name" type="text" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">Email Address</label>
                    <div class="controls">
                        <input class="input-xlarge email" id="email" name="email" type="text" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="phonenumber">Phone Number</label>
                    <div class="controls">
                        <input class="input-xlarge phone" id="phonenumber" name="phonenumber" type="text" />
                    </div>
                </div>
                <br />
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            <input type="checkbox" name="isoptedin" value="1" /> This phone number can receive text messages
                        </label>
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Member</button>&nbsp;<button type="reset" class="btn">Cancel</button>
            </div>
        </form>
    </div>
    <div class="span2">
        <h5>Page Description</h5>
        <p>This page allows you to add a new team member to the application. Make sure that the email address you provide is valid, as it will be used to contact this team member.</p>
        <br />
    </div>
</div>
