![license](https://img.shields.io/github/license/mashape/apistatus.svg)
# PCCU4B-PHP-Statistical-Pollng-System-and-AndroidClient
A simple example for creating a web Statistical system and an android client. With some modifications, it can become a polling system.

Screenshots
-------------
**Website(PHP):**


![image](https://github.com/evilhawk00/PCCU4B-PHP-Statistical-Polling-System-and-AndroidClient/blob/master/Screenshot/PCCU4B_Screenshot.gif)
**Android Client:**


<img src="https://github.com/evilhawk00/PCCU4B-PHP-Statistical-Polling-System-and-AndroidClient/blob/master/Screenshot/PCCU4B_Mobile_Screenshot.gif" width="50%">

-------------

Features
------------
- The mobile client retrieve data in json format from /MobileAppAPI
- Google Charts implemented to provide better visual experience.



Host Side Configuration
-------------
To setup your SQL database connection settings, edit two files :
- /dbconfig.php
- /MobileAppAPI/dbconfig.php

There's an database backup file "statistical_pollng_system.sql" in this repository contains testing data used for testing this system. To import this backup file to your database, you can :
- use phpMyAdmin to import this backup file
- use the following SQL command :
`mysql -u username –-password=your_password database_name < statistical_pollng_system.sql`

Once you imported the testing data to your database, use the following account and password to login : 

| E-mail  | Password |
| ------------- | ------------- |
| admin@admin.com  | admin  |
| test1@test.com  | test  |
| test2@test.com  | test  |
| test3@test.com  | test  |
| test4@test.com  | test  |
| test5@test.com  | test  |
| test6@test.com  | test  |
| test7@test.com  | test  |
| test8@test.com  | test  |
| test9@test.com  | test  |
| test10@test.com  | test  |

Passwords are stored in MD5, you can simiply use phpMyAdmin with a MD5 hash caculator to change password / add new account.

Android Client Configuration
-------------
First you need to specify your hostname or IP in Android App source code, so the App can connect to your server. To do this, you have to edit the file : 
- SharedDataBetweenActivities.java (modify value of the variable "HostName" with your domain or IP)

Third Party Software used in Android Client
-------------
- [FlycoTabLayout](https://github.com/H07000223/FlycoTabLayout) for the elegant fragment UI.

- [Glide](https://github.com/bumptech/glide) for loading images in RecycleView.

- [ObscuredSharedPreferences.java](https://github.com/RightHandedMonkey/WorxForUs_Library/blob/master/src/com/worxforus/android/ObscuredSharedPreferences.java) from [WorxForUs_Library](https://github.com/RightHandedMonkey/WorxForUs_Library) for ObscuredSharedPreferences.

- Base64Support.java from [Android Open Source Project](https://source.android.com) used as a dependency for ObscuredSharedPreferences.

# License
###### The MIT License

Copyright © 2018 CHEN-YAN-LIN

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
