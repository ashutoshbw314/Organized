# Organized (hastily)
An open-source web app to organize data(currently text only) that are important to you. Hastily is the version name of this app, because it was developed very hastily. (I'm an amateur programmer who don't know the software versioning conventions properly, so I have used a name instead)


## Getting Started
### Prerequisites
Organized(hastily) is a little tough to setup. However if you somehow able to set up your environment, only basic MySQL and JSON Knowledge are enough to get started with app.



### Set up your enviorment
This only works on PC. It will work all the major operating systems such as Linux, Mac or Windows. To set up your enviorment, install the following softwares on your computer:


- [Apache web server](https://httpd.apache.org/)
- [MySQL](https://dev.mysql.com/downloads/mysql/)
- [PHP](http://php.net/)

Start Apache and MySQL server and set them to start automatically during the system startup.
If you need help how to do these things, google it. There is a lot of tutorials can be found suitable for your platform. Then if you problems about this, you can send me an email(ashutoshbw314@gmail.com) about your problems. I will try to help you.

### Setup and start Organized

- Download the Organized from github.
- Extract Organized to `www/html/` folder of your server.
- Open the terminal or command prompt and login to your mysql user account.
- Enter the following the command the to setup the database for organized(replace `path_to_html` to your server's html folder's path):

```
source path_to_html/Organized/setup_db.sql
```
- Open the `path_to_html/Organized/php/con.php` file in a text editor.
- In the `con.php` file replace `username` with your MySQL username and `password` with our MySQL password for that username.
- Now open your favorite web browser and go to `localhost/Organized`
- If eveything worked fine, the home page of the app should appear. If the homepage doesn't appear, that means you have problems in setting up your enviornment or the Apache, MySQL server is not started and if you can't figure out this, you can email(ashutoshbw314@gmail.com) me about this. I will try to help you. 
- Now you have to check your PHP MySQL connection. See the [A Guide to Organized](http://www.youtube.com/watch?v=Ulcx0F87PBk&t=1m55s) to know how to do this.
- Done :)

## A Guide to Organized
A video on how to use Organized: [A Guide to Organized](https://youtu.be/Ulcx0F87PBk).

## Softwares used to Built Organized
- Fedora
- Gedit
- [Code Mirror](https://codemirror.net/)

## Author 
[Ashutosh Biswas](https://github.com/ashutoshbw314)

## License
This project is under an MIT License. see the [License](./LICENSE) for detail.

## Acknowledgements
- The activity calendar of Organized is developed inspired by the GitHub contribution calendar.
- Thanks to Socratica youtube channel for the excellent idea on how to use flash card effectively in [this](https://www.youtube.com/watch?v=p3-o0pxDrL0) video.
