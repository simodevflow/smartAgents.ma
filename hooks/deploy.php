<?php
echo "<pre>";
// Execute the deploy script and capture its output
echo "Deploying latest changes... <br>";
system('/home/huce0783/smartagents.ma/hooks/deploy.sh 2>&1');
echo "</pre>";