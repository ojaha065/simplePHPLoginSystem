<?php
    array_map("unlink",glob("install/*.*"));
    rmdir("install");
    if(!is_dir("install")){
        unlink(__FILE__);
        header("location: index.php");
    }
    else{
        echo "Can't automatically remove the folder. Please remove /install manually.";
    }
?>