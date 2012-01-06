<div class="row">
    <div class="span10">
        <form action="<?=option('base_uri')?>members/<?=$member['id']?>/edit" method="post" class="form-stacked">
            <fieldset>
                <div class="clearfix">
                    <label for="name">Name</label>
                    <div class="input">
                        <input class="xlarge" id="name" name="name" size="30" type="text" value="<?=$member['name']?>">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="email">Email Address</label>
                    <div class="input">
                        <input class="xlarge" id="email" name="email" size="30" type="text" value="<?=$member['email']?>">
                    </div>
                </div>
                <div class="clearfix">
                    <label for="phonenumber">Phone Number</label>
                    <div class="input">
                        <input class="xlarge" id="phonenumber" name="phonenumber" size="30" type="text" value="<?=$member['phonenumber']?>">
                    </div>
                </div>
                <br />
                <div class="clearfix">
                    <div class="input">
                        <input type="checkbox" name="isoptedin" value="1" <?php if ($member['isoptedin'] == 1) { ?>checked="true"<?php } ?>>
                        <span>Receive text message notifications</span>
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="actions">
                <button type="submit" class="btn primary">Save Member</button>&nbsp;<a onclick="return confirm('Are you sure you want to delete this member?');" href="<?=option('base_uri')?>members/<?=$member['id']?>/delete" class="btn">Delete</a>
            </div>
        </form>
    </div>
    <div class="span4">
        <h5>Page Description</h5>
        <p>This page allows you to edit a team member's information. You also have the ability to delete a team member from this page.</p>
        <br />
    </div>
</div>