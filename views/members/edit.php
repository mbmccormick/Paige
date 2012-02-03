<div class="row">
    <div class="span6">
        <form action="<?=option('base_uri')?>members/<?=$member['id']?>/edit" method="post" class="form-vertical">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="name">Name</label>
                    <div class="controls">
                        <input class="input-xlarge" id="name" name="name" type="text" value="<?=$member['name']?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">Email Address</label>
                    <div class="controls">
                        <input class="input-xlarge email" id="email" name="email" type="text" value="<?=$member['email']?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="phonenumber">Phone Number</label>
                    <div class="controls">
                        <input class="input-xlarge phone" id="phonenumber" name="phonenumber" type="text" value="<?=$member['phonenumber']?>" />
                    </div>
                </div>
                <br />
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            <input type="checkbox" name="isoptedin" value="1" <?php if ($member['isoptedin'] == 1) { ?>checked="true"<?php } ?> /> This phone number can receive text messages
                        </label>
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Member</button>&nbsp;<a onclick="return confirm('Are you sure you want to delete this member?');" href="<?=option('base_uri')?>members/<?=$member['id']?>/delete" class="btn">Delete</a>
            </div>
        </form>
    </div>
    <div class="span2">
        <h5>Page Description</h5>
        <p>This page allows you to edit a team member's information. You also have the ability to delete a team member from this page.</p>
        <br />
    </div>
</div>
