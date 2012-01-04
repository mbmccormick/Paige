<div class="content">
    <div class="navigation">
        <?php 
        
            if ($type == "user")
                echo "<a href='/user'>Users</a> / <a href='#'>Not Found</a>";
            
        ?>
    </div>
    <div class="list">
        <div class="list-item">
            <h3><?php echo ucfirst($type); ?> Not Found</h3>
            <br />
            <p>We couldn't find that <?=$type?> for some reason. This could be because that <?=$type?> has been deleted or we may have sent you to a bad link.</p>
            <br />
            <p>If you think you are seeing this page in error, please <a href="mailto:<?=EmailAddress?>">contact</a> your administrator.</p>
        </div>
    </div>
</div>