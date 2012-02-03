<div class="row">
    <div class="span6">
        <form action="<?=option('base_uri')?>schedule/add" method="post" class="form-vertical">
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
                        <input class="input-medium" id="startdate" name="startdate" type="text" /> <input class="input-medium" id="starttime" name="starttime" type="text" />
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Shift</button>&nbsp;<button type="reset" class="btn">Cancel</button>
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
