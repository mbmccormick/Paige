<div class="row">
    <div class="span14">
        <div class="calendar">
            <table class="bordered-table">
                <thead>
                    <tr>
                        <th><?=$prev?></th>
                        <th colspan="5" style="border-left: none;"><?=$calendar_title?></th>
                        <th style="border-left: none;"><?=$next?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="calendar-header">
                        <td>Sun</th>
                        <td>Mon</td>
                        <td>Tue</td>
                        <td>Wed</td>
                        <td>Thu</td>
                        <td>Fri</td>
                        <td>Sat</td>
                    </tr>
                    <?=$calendar?>
                </tbody>
            </table>
        </div>
        <div class="well">
            <a href="<?=option('base_uri')?>schedule/add" class="btn primary">New Shift</a>
        </div>
    </div>
</div>
