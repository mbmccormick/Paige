<div class="row">
    <div class="span10">
        <form action="<?=option('base_uri')?>users/<?=$user['id']?>/edit" method="post" class="form-stacked">
            <fieldset>
                <div class="clearfix">
                    <label for="username">Username</label>
                    <div class="input">
                        <input class="xlarge" id="username" name="username" size="30" type="text" value="<?=$user['username']?>">
                    </div>
                </div>
                <br />
                <div class="clearfix">
                    <label for="name">Name</label>
                    <div class="input">
                        <input class="xlarge" id="name" name="name" size="30" type="text" value="<?=$user['name']?>">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="email">Email Address</label>
                    <div class="input">
                        <input class="xlarge" id="email" name="email" size="30" type="text" value="<?=$user['email']?>">
                    </div>
                </div>
                <br />
                <div class="clearfix">
                    <label for="currentpassword">Current Password</label>
                    <div class="input">
                        <input class="xlarge" id="currentpassword" name="currentpassword" size="30" type="password">
                    </div>
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
                <br />                
                <div class="clearfix">
                    <div class="input">
                        <input type="checkbox" name="isadministrator" value="1" <?php if ($user['isadministrator'] == 1) { ?>checked="true"<?php } ?>>
                        <span>This user is an administrator</span>
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="actions">
                <button type="submit" class="btn primary">Save User</button>&nbsp;<a onclick="return confirm('Are you sure you want to delete this user?');" href="<?=option('base_uri')?>users/<?=$user['id']?>/delete" class="btn">Delete</a>
            </div>
        </form>
    </div>
    <div class="span4">
        <h5>Page Description</h5>
        <p>This page allows you to edit a user's information. You also have the ability to delete a user from this page.</p>
        <br />
        <h5>Change Password</h5>
        <p>You can change the password for this user by entering their existing password and then providing a new password for the user.</p>
        <br />
    </div>
</div>