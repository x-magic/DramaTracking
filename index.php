<?php
setlocale(LC_ALL, 'en_US.UTF-8');

//databases used in the process
$dramaDB = "db_drama.csv";
$statusDB = "db_status.csv";

//status indicators of whether content has been changed
$statAdded = false;
$statUpdate = false;
$statDelete = false;

//set status db with abbreviation as key
$statusValue = array();
$statusKey = array();
$dbStatus = fopen($statusDB,"r");
while(!feof($dbStatus)) {
    array_push($statusValue,fgetcsv($dbStatus));
    array_push($statusKey,end($statusValue)[0]);
}
fclose($dbStatus);
$statusArray = array_combine($statusKey,$statusValue); 

function toDisable($statusCode,$matchCode) {
	
}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Drama Tracking System</title>
        <style type="text/css">
            <!--
            * {font-family: sans-serif; }
            html {display: table; margin: auto; }
            body {display: table-cell; width: 100%;}
            .Season {width: 5em; padding: 0 5px 0 5px; }
            .Episode {width: 2em; padding: 0 5px 0 5px; }
            .btn {min-width: 2em; }
            .title {min-width: 10em; }
            .add {width: 100%; }
            .btnop {min-width: 5em; }
            .outside {padding: 1em 0 1em 0; text-align: center; }
            table {border-collapse: collapse; }
            th, td {padding: 0 5px 0 5px; border :1px solid black; }
            thead {background: black; color: white; }
			input[type=button]:disabled, button:disabled {visibility: hidden; }
            -->
        </style>
        <script type="text/javascript">
            <!--
            //get parent's child of Box to do addition/substruction
            function btnoperation(btn,operation){
                var node=btn.parentNode.getElementsByClassName("box")[0];
                if(operation=="+"){
                    node.value++;
                }else{
                    node.value--;
                }
            }
            
            //POST a form back to delete designated item
            function btndelete(btn){
                var operation=btn.value;
                var name=btn.parentNode.parentNode.getElementsByClassName("title")[0].innerText;
                var abox=confirm("Do you really want to delete \"" + name + "\"");
                if(abox==true&&true){
                    var form=document.createElement("form");
                    form.setAttribute("method", "POST");
                    var field = document.createElement("input");
                    field.setAttribute("type", "hidden");
                    field.setAttribute("name", "delete");
                    field.setAttribute("value", operation);
                    form.appendChild(field);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
            -->
        </script>
    </head>
    <body>
        <div class="outside"><h1>Drama Tracking System</h1></div>
        <table>
            <thead>
                <tr>
                    <th>Geolocation</th>
                    <th>Season</th>
                    <th>Episode</th>
                    <th>Status</th>
                    <th>Title</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody id="dramatable">
        <?php
        //when request add item from POST, return add item page
        if(array_key_exists('add', $_POST)) {
            //read all geolocations from drama db
            $geolocationArray = array();
            $dbDrama = fopen($dramaDB,"r");
            while(!feof($dbDrama)) array_push($geolocationArray,fgetcsv($dbDrama)[0]);
            fclose($dbDrama);
            //extract all unique geolocations to new array
            $geolocationArray = array_values(array_unique($geolocationArray)); ?>
                <form method="POST">
                <tr>
                    <td>
                        <select name="geolocation" class="add">
                            <?php foreach($geolocationArray as $value) if(!empty($value)) echo "<option>$value</option>\n"; ?>
                        </select>
                    </td>
                    <td rowspan="2">
                        <input type="text" value="" name="season" class="box Season">
                    </td>
                    <td rowspan="2">
                        <input type="text" value="" name="episode" class="box Episode">
                    </td>
                    <td rowspan="2">
                        <select name="status" class="add">
                            <?php foreach($statusArray as $value) echo "<option value=\"$value[0]\">$value[1]</option>\n"; ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" value="Title" name="title" class="title" onFocus="this.value=''">
                    </td>
                    <td>
                        <button type="submit" value="1" name="added" class="add">Update</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="text" value="New Location" name="geolocationdiy" onFocus="this.value=''">
                    </td>
                    <td>
                        <input type="text" value="Download Page URL" name="URL" class="title" onFocus="this.value=''">
                    </td>
                    <td>
                        <button type="button" onClick="location.href='.';" class="add">Cancel</button>
                    </td>
                </tr>
                </form>
        <?php } else {
            if(array_key_exists('added', $_POST)) {
                $addedLine = "\n";
                //geolocation from textarea has a higher priority than those in selector
                if ($_POST['geolocationdiy'] === "New Location" || empty($_POST['geolocationdiy'])) $addedLine .= $_POST['geolocation'];
                else $addedLine .= $_POST['geolocationdiy'];
                //construct csv line
                $addedLine .= ",".$_POST['season'].",".$_POST['episode'].",".$_POST['status'].",".$_POST['title'].",";
                if ($_POST['URL'] !== "Download Page URL") $addedLine .= $_POST['URL'];
                file_put_contents($dramaDB, $addedLine, FILE_APPEND);
                $statAdded = !$statAdded;
            } elseif(array_key_exists('update', $_POST)) {
                //read original line from file
                $updateFile = file($dramaDB);
                $updateLine = $updateFile[$_POST['update']-1];
                $updateArray = str_getcsv($updateLine);
                //update changed term only
                if(isset($_POST['season'])) $updateArray[1] = $_POST['season'];
                if(isset($_POST['episode'])) $updateArray[2] = $_POST['episode'];
                if(isset($_POST['status'])) $updateArray[3] = $_POST['status'];
                foreach($updateArray as $key => $value) {
                    if($key == 0) $updateLine = $value;
                    else $updateLine .= ",".$value;
                }
                $updateFile[$_POST['update']-1] = $updateLine."\n";
                file_put_contents($dramaDB,implode($updateFile));
                $statUpdate = !$statUpdate;
            } elseif(array_key_exists('delete', $_POST)) {
                $deleteFile = file($dramaDB);
                unset($deleteFile[$_POST['delete']-1]);
                $deleteFile = array_values($deleteFile);
                file_put_contents($dramaDB,implode($deleteFile));
                $statDelete = !$statDelete;
            }
            $currentDrama = array();
            $currentCounter = 1;
            $dramaFile = fopen($dramaDB,"r");
            while(!feof($dramaFile)) {
                $currentDrama = fgetcsv($dramaFile);
                //empty should be skipped
                if ($currentDrama !== false) 
                if (count($currentDrama) >= 5) { ?>
                <form method="POST">
                <tr style="background: <?php echo $statusArray[$currentDrama[3]][2]?>;">
                    <td><?php echo $currentDrama[0];?></td>
                    <td>
                        <input type="text" value="<?php echo $currentDrama[1];?>" name="season" class="box Season"<?php if (!ctype_digit($currentDrama[1]) || in_array($statusArray[$currentDrama[3]][3], array("2", "5", "6", "9"))) echo " disabled"; ?>>
                        <input type="button" value="+" name="seasonplus" class="btn" onClick="btnoperation(this,'+');"<?php if (!ctype_digit($currentDrama[1]) || in_array($statusArray[$currentDrama[3]][3], array("2", "5", "6", "9"))) echo "  disabled"; ?>>
                        <input type="button" value="-" name="seasonminus" class="btn" onClick="btnoperation(this,'-');"<?php if (!ctype_digit($currentDrama[1]) || in_array($statusArray[$currentDrama[3]][3], array("2", "5", "6", "9"))) echo "disabled"; ?>>
                    </td>
                    <td>
                        <input type="text" value="<?php echo $currentDrama[2];?>" name="episode" class="box Episode"<?php if (in_array($statusArray[$currentDrama[3]][3], array("3", "5", "7", "9"))) echo " disabled"; ?>>
                        <input type="button" value="+" name="episodeplus" class="btn" onClick="btnoperation(this,'+');"<?php if (in_array($statusArray[$currentDrama[3]][3], array("3", "5", "7", "9"))) echo " disabled"; ?>>
                        <input type="button" value="-" name="episodeminus" class="btn" onClick="btnoperation(this,'-');"<?php if (in_array($statusArray[$currentDrama[3]][3], array("3", "5", "7", "9"))) echo " disabled"; ?>>
                    </td>
                    <td>
                        <select name="status">
                            <?php foreach($statusArray as $value) {
                                echo "<option value=\"$value[0]\"";
                                if ($value[0] == $currentDrama[3]) echo " selected";
                                echo">$value[1]</option>\n";
                            } ?>
                        </select>
                    </td>
                    <td class="title">
                        <?php if(!empty($currentDrama[5])) echo "<a href=\"$currentDrama[5]\" target=\"_blank\">$currentDrama[4]</a>";
                        else echo $currentDrama[4];?>
                    </td>
                    <td>
                        <button type="button" value="<?php echo $currentCounter;?>" name="delete" class="btnop" onClick="btndelete(this);"<?php if (in_array($statusArray[$currentDrama[3]][3], array("4", "6", "7", "9"))) echo " disabled"; ?>>Delete</button>
                        <button type="submit" value="<?php echo $currentCounter;?>" name="update" class="btnop">Update</button>
                    </td>
                </tr>
                </form>
            <?php }
                //counter should be increased even that line is empty so buttons can return correct line number by POST
                $currentCounter ++;
            }
            fclose($dramaFile); ?>
            </tbody>
        </table>
        <div class="outside">
            <form method="POST">
                <button type="button" onclick="window.open('https://www.pogdesign.co.uk/cat/', '_blank')">PoGDesign TV Calendar</button>
                <button type="submit" value="1" name="add">Add new drama</button>
            </form>
            <?php //Show an alert if content has been changed
			if($statAdded) { ?>
            <div id="message">Add drama successful!</div>
            <?php } elseif($statUpdate) { ?>
            <div id="message">Update drama successful!</div>
            <?php } elseif($statDelete) { ?>
            <div id="message">Delete drama successful!</div>
            <?php } ?>
        </div>
        <?php }?>
		<!-- Scroll to bottom of page -->
        <script type="text/javascript">
			function hideMessage() {
				document.getElementById('message').style.display="none";
			}
			window.scrollTo(0,document.body.scrollHeight);
			setTimeout('hideMessage()', 1000);
		</script>
    </body>
</html>