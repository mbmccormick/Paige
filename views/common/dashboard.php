<div class="row">
    <div class="span7">
        <h3>Send Page</h3>
        <span class="help-block">Send a message to your on-call team from the web.</span>
        <br />
        <form action="<?=option('base_uri')?>" method="post" class="form-stacked" style="padding-left: 0px;">
            <fieldset>
                <div class="clearfix">
                    <label for="name">Recipient</label>
                    <div class="input">
                        <select class="xlarge" id="recipient" name="recipient">
                            <option value="1"><?=$oncall?> (on-call team member)</option>
                            <option value="2">All team members</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix">
                    <label for="name">Message</label>
                    <div class="input">
						<!-- return (this.value.length <= 120); -->
                        <textarea class="xlarge" id="message" name="message" rows="2" onkeypress="return (this.value.length < 120);"></textarea>
                        <span class="help-block">
                            Your message must be 120 characters or less.
                        </span>
                    </div>
                </div>
            </fieldset>
            <br />
            <div>                
                <button type="submit" class="btn success">Send Page</button>
            </div>
        </form>
    </div>
    <div class="span7">
        <h3>Recent History</h3>
        <span class="help-block">View the recent pages that have been sent.</span>
        <br />
        <?=$history?>
    </div>
</div>
<script type="text/javascript">

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
