<div class="row">
    <div class="span7">
        <h3>Page Team</h3>
        <span class="help-block">Send a message to your on-call team from the web.</span>
        <br />
        <form action="<?=option('base_uri')?>" method="post" class="form-stacked" style="padding-left: 0px;">
            <fieldset>
                <div class="clearfix">
                    <label for="name">Message</label>
                    <div class="input">
                        <textarea class="xlarge" id="message" name="message" rows="2"></textarea>
                        <span class="help-block">
                            Your message must be 140 characters or less.
                        </span>
                    </div>
                </div>
            </fieldset>
            <br />
            <div>                
                <button type="submit" class="btn success">Page Team</button>
            </div>
        </form>
    </div>
    <div class="span7">
        <h3>Recent History</h3>
        <span class="help-block">View the recent pages that have been sent.</span>
        <br />
    </div>
</div>