<li>
    <a href="<?=$link?>"><i class="fa fa-<?=$icon?>"><span class="icon-bg bg-<?=$background?>"></span></i><span data-i18n="<?=$title?>"></span>
    <?php if($n!=0){
        echo'<span class="badge badge-danger main-badge">'.$n.'</span>';
    }?>
    </a>
    <?php 
    if(isset($submenu)){
        echo"<ul>";
        echo$submenu;
        echo"</ul>";
    }
    ?>
</li>