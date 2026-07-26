DATABASE SETUP

There are 3 Main SQL files. ArcherFinddb.sql (CREATE TABLE statments), CCDEVAP-S16-6-ArcherFind-DLSU-Lost-and-Found-System.sql (INSERT statements sample data), ArcherFindTriggers.sql (Database Triggers)
1. Firstly run them in this order, ArcherFindDB.sql, CCDEVAP-S16-6-ArcherFind-DLSU-Lost-and-Found-System.sql, then ArcherFindTriggers.sql on MySQLWorkbench
2. Secondly, check db.php and ensure that the details match the details of your database connection
3. Thirdly, ensure that your file contains the sample data images. These can be found in the assets folder

*It is best to clone this repository to your machine, and run through XAMPP. Make sure that your project folder is located in the htdocs folder*

BROWSER SETUP
4. Once XAMPP is enabled, type 'localhost' and add the file path to your project
5. The browser should open index.php (Login Page)

WEB APPLICATION OPERATIONS
Here are user credentials you may use to log in,
STUDENT VIEW - marc_lesley_quizon@dlsu.edu.ph (student123) or angelo_almeda@dlsu.edu.ph (student123)
STAFF VIEW - carl_crespo@dlsu.edu.ph (staff123)
ADMIN VIEW - daniel_pamintuan@dlsu.edu.ph (admin123)

6. Login using your chosen credentials and explore the web application!

OVERVIEW OF FEATURES
1. Student
- Submit a Surrender Form
- Submit a Claim Request (For an item)
- Submit a Loss Report
- View dashboard
- Manage Account

2. Staff
- Resolve/Close a Surrender Form
- Resolve/Close a Claim Request
- Resolve/Close a Loss Report
- View dashboard
- Manage Account

3. Admin
- Resolve/Close a Surrender Form
- Resolve/Close a Claim Request
- Resolve/Close a Loss Report
- View dashboard
- Manage Account
- CRUD to Manage Users

TIP!!
- After testing with these features, it is best to drop the database and run it again, and to avoid commiting any MOVED image files. This is to ensure your project folder contains the image files matching the BASE SAMPLE DATA database
