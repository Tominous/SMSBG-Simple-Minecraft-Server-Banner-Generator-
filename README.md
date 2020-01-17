# SMBG (Simple Minecraft Banner Generator)

*As the name of this project is it's just a simple php script which allows you to create custom banners for your minecarft server. These are live banners so they display live stats of your server. At the moment they display the address of your server, the amount of online players and the version of the server.
But you may configure these options in the `banner.php` file.*

### Some additional information

* For prettier urls you can make a request without include the file's extension _(Instead of __banner.php?address=localhost:25565__ you can simply just __banner?address=localhost:25565__)_.
* This project also includes a server query using [mcsrvstat.us](https://mcsrvstat.us/) api.
* If you enable the `enable_key` option in the config file you can restrict the usage of the banner generator but then you must need to pass the following value for the future request's: `&key=your_app_key`. You can find the `app_key` in the `config.php` file. If you want to use it it's recommanded to change it frequently but the actaul usage of it is to disable the momentary usage of the generator.
* This project includes 6 default banners (1-6) and 3 default fonts (Roboto, Ubuntu, Minecraftia)

### Installation

**Just simply drag all the files included form the downloaded zip in to a folder and after the go to the `config.php` and change the `app_url` from `http://localhost/banner/` to the domain for the banner generator.**

### How can you make a request?!

* You can get a custom banner by accessing: `banner?address=your_minecraft_server_ip&background=1&font=roboto`
* You can access the server query by: `query?address=your_minecraft_server_ip`

### How can you add a new banner background?!

**The banner's resolution must be 468x60 and it must a `jpg` file and included in the `img/background` folder with the following name: `banner_(here a custom number).jpg`, example: `banner_11.jpg`. _You can set the background of the banner by changing the `background` variable for example: `banner?address=your_minecraft_server_ip&background=11&font=roboto`_.**

### How can you add a new font for the banner?!

**First you need to add a add your font file to the `fonts` folder and rename it like this: `examplefont-regular.ttf` after this go to the `config.php` and add it to the `fonts` and the name you specify there should be just the font's name without the _-regular.ttf_ and you must make sure it's all lowercase _(The name specified in the config and the actual name of the font file must be all lowercase)_. And now you can change the font by simply changing the `font` variable in the request url for example: `banner?address=your_minecraft_server_ip&background=1&font=your_custom_font`.**

#### Default config

```php
<?php

$config = [
  'app_key' => 'K6TdeFXHuqZC7Fam',
  'enable_key' => false,
  'query_api' => 'https://api.mcsrvstat.us/2/',
  'app_url' => 'http://localhost/banner/',
  'fonts' => ['ubuntu', 'roboto', 'minecraftia']
];
```

### Request Examples

* __Banner:__ `http://localhost/banner/banner?address=mc.hypixel.net&background=1&font=ubuntu`
* __Query__ `http://localhost/banner/query?address=mc.hypixel.net`