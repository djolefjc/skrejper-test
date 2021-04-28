# skrejper-test
This is a simple tool that I've built for scrapping websites with simple pagination and exporting the data into excel csv, using Simple HTML DOM Parser.
Technologies used: PHP, HTML

Docs:
Because we use Simple HTML DOM parser it's necessary to map all the elements from which we want to extract data form. We do this by simply inspecting the page we want to scrape.

To learn how to find/grab elements, you can view the docs for Simple HTML DOM parser here: https://simplehtmldom.sourceforge.io/ .

FIRST STEP - UNO
a) without pagination
Copy link of shop archive to uno.php and change index.php first link to point to uno.php.

b) with pagination
Copy link of first page in shop to uno2.php and link of page of a shop archive. Set index.php first link to point to uno2.php.

SECOND STEP - DUO
Map columns and set all arrays for data in this script. Change the name of exported .csv file.
