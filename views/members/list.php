<div class="row">
    <div class="span6">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Name</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?=$body?>
            </tbody>
        </table>
        <div class="well">
            <a href="<?=option('base_uri')?>members/add" class="btn btn-primary">New Member</a>
        </div>
    </div>
    <div class="span2">
        <h5>Page Description</h5>
        <p>This page shows the list of team members currently setup for the application. This list allows you to view a team members's information or create a new team member.</p>
        <br />
    </div>
</div>