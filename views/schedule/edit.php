<div class="row">
    <div class="span10">
        <form action="<?=option('base_uri')?>schedule/<?=$schedule[id]?>/edit" method="post" class="form-stacked">
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
                        <input class="medium" id="startdate" name="startdate" size="30" type="text" value=<?=date("n/d/Y", strtotime($schedule[startdate]))?>> <input class="medium" id="starttime" name="starttime" size="30" type="text" value=<?=date("g:ia", strtotime($schedule[startdate]))?>>
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="actions">
                <button type="submit" class="btn primary">Edit Shift</button>&nbsp;<a onclick="return confirm('Are you sure you want to delete this shift?');" href="<?=option('base_uri')?>schedule/<?=$schedule['id']?>/delete" class="btn">Delete</a>
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

    $("form.form-stacked").submit(function validate() {
        var formData = $("form.form-stacked").serializeArray();
        for (var i=0; i < formData.length; i++) { 
            if (!formData[i].value) { 
                alert("Please complete all fields, check your input, and try again.")                
                return false;
            }
        }
    });

</script>
