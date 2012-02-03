<div class="row">
    <div class="span6">
        <form action="<?=option('base_uri')?>schedule/<?=$schedule[id]?>/edit" method="post" class="form-vertical">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="memberid">Team Member</label>
                    <div class="controls">
                        <select class="input-xlarge" id="memberid" name="memberid">
                            <?=$members?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="startdate">Start Date/Time</label>
                    <div class="controls">
                        <input class="input-medium" id="startdate" name="startdate" type="text" value=<?=date("n/d/Y", strtotime($schedule[startdate]))?> /> <input class="input-medium" id="starttime" name="starttime" type="text" value=<?=date("g:ia", strtotime($schedule[startdate]))?> />
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Shift</button>&nbsp;<a onclick="return confirm('Are you sure you want to delete this shift?');" href="<?=option('base_uri')?>schedule/<?=$schedule['id']?>/delete" class="btn">Delete</a>
            </div>
        </form>
    </div>
    <div class="span2">
        <h5>Page Description</h5>
        <p>This page allows you to add a new shift to the on-call schedule. Make sure that the start date and time you provide is correct, as this is when we will contact this team member, if a page is issued.</p>
        <br />
    </div>
</div>
<script type="text/javascript">

    $(function() {
        $("#startdate").calendricalDate();
        $("#starttime").calendricalTime();
    });

</script>
