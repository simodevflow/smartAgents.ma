<?php
    echo "<pre>";
        // Execute the deploy script and capture its output
        echo "Deploying latest changes... <br>";

        
        system('/home/huce0783/smartagents.ma/hooks/deploy.sh 2>&1'); 
        //2>&1 captures both stdout and stderr and displays it in the browser
        //- The '2' represents the standard error (stderr) stream.
        //- The '>' operator redirects the output of the command.
        //- The '1' represents the standard output (stdout) stream.

    echo "</pre>";
?>    