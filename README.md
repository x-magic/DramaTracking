## DramaTracking (by Bill Haofei Gong)

A PHP-based Drama Tracking System with CSV-based Database

####Prerequisite: 
PHP, write permission to 2 database CSVs. 

####Features:
* List of watching drama. Title can be hyperlinked. 
* Update season and episode with buttons. 
* Multiple status of drama, and color coded. 
* Geolocation of drama can be recorded. 
* Auto disable any control that should not be changed.
  * When a drama is not in airing, change and delete is not allowed. 
  * Non-numerical season cannot be changed.
* Deletion alert to prevent accidentally click the delete button. 
* Auto scroll to bottom of list. 

####Known Issues:
* I just can't get geolocation selector and textarea aligned in Chrome. 

There has to be a lot more out there but I didn't found any yet. 

####Details of CSVs:
##### db_status.csv
Status DB indicates status of drama can be selected in following format:
```
ONE-letter abbreviation,Full name,Color code of the row in table,Field disable option
```

##### db_drama.csv
Drama DB contains all records of drama. Will be iterated by order in file, in following format:
```
Geolocation of drama,Season(can be a word),Episode(must be a number),Status(in abbreviation),Drama title,Drama hyperlink
```
###### Field disable option explained:
There are 3 fields are designed to be disabled when status of drama is not "Airing": Season, Episode and Delete button. In versions after 26/Nov/2013, you need to add a new column at end of db_status.csv that enables you to set what field to disable by status of drama as following rule:

Value of Season Field, Episode Field and Delete Button are: 2, 3, 4. Add value to the column to set which field to disable. 
Example: to disable Season Field only when drama is in "x episodes to go" status, add 2 to the last column of correspondence line; To disable both Season field and delete button, add (2+4=)6 to the last column of correspondence line, etc. Otherwise, use 0 to represent nothing should be disabled. 

####Postscriptum:
* It's actually my first PHP project so the code sucks. Give me a break thank you :)
* Sample data provided is just for showcase to give an idea of how this code work. If there's an issue with copyright holders of drama please let me know and I will delete it. 

####Changelog:
#####12/Nov/2013
* Add: Initial public release

#####14/Nov/2013
* Add: Ignore invalid lines in database file to prevent error
* Improve: Improve auto scroll to bottom
* Add: Index.php is now commented

#####26/Nov/2013
* Fix: Adding button not working. Now fixed. 
* Improve: Now what field is disabled can be set in db_status.csv(last column). 