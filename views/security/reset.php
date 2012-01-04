<div class="content">
    <div class="login-form">
        <table cellpadding="0" cellspacing="0" style="width: 100%;">
            <tr>
                <td align="left" valign="middle">
                    <h2><a href="<?=option('base_uri')?>"><?=ApplicationName?></a></h2>
                </td>
                <td align="right" valign="middle">
                    &nbsp;
                </td>
            </tr>
        </table>
        <br />
        <form action="<?=option('base_uri')?>login/reset" method="post">
            <span class="help">Enter the email address for your account below and we will send you a new password.</span><br />
            <br />
            <label for="Username">
                Email Address<br />
                <input class="text" name="email" style="width: 290px;" type="text" />
            </label>
            <br />
            <button type="submit" class="button">
                <span>Reset</span>
            </button>
        </form>  
    </div>
</div>