DramaTracking (by Bill Haofei Gong)

A PHP-based Drama Tracking System with CSV-based Database

Prerequisit: PHP, write permission to 2 database csvs. 

Features:
- List of watching drama. Title can be hyperlinked. 
- Update season and episode with buttons. 
- Multiple status of drama, and color coded. 
- Geolocation of drama can be recorded. 
- Auto disable any control that should not be changed.
> When a drama is not in airing, change and delete is not allowed. 
> Non-numerical season cannot be changed.
- Deletion alert to prevent accidentally click the delete button. 
- Auto scroll to bottom of list. 

Known Issues:
- I just can't get geolocation selector and textarea aligned in Chrome. 

There has to be a lot more out there but I didn't found any yet. 

Details of CSVs:
Status DB indicates status of drama can be selected in following format:
ONE-letter abbreviation,Full name,Color code of the row in table

Drama DB contains all records of drama. Will be iterated by order in file, in following format:
Geolocation of drama,Season(can be a word),Episode(must be a number),Status(in abbreviation),Drama title,Drama hyperlink

Postscriptum:
- It's my first actually PHP project so the code is lame. Give me a break thank you :)
- Sample data provided is just for showcase to give an idea of how this code work. If there's an issue with copyright holders of drama please let me know and I will delete it. 

Changelog:
12/Nov/2013
- Initial public release