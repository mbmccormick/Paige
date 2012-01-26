<div class="row">
    <div class="span10">
        <form action="<?=option('base_uri')?>schedule/add" method="post" class="form-stacked">
            <fieldset>
                <div class="clearfix">
                    <label for="memberid">Team Member</label>
                    <div class="input">
                        <select class="xlarge" id="memberid" name="memberid">
                            <?=$members?>
                        </select>
                    </div>
                </div>
                <div class="clearfix">
                    <label for="startdate">Start Date/Time</label>
                    <div class="input">
                        <input class="medium" id="startdate" name="startdate" size="30" type="text"> <input class="medium" id="starttime" name="starttime" size="30" type="text">
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="actions">
                <button type="submit" class="btn primary">Add Shift</button>&nbsp;<button type="reset" class="btn">Cancel</button>
            </div>
        </form>
    </div>
    <div class="span4">
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
