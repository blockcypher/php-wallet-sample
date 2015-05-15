BlockCypher REST API PHP Sample
===============================

> **WARNING: WORK IN PROGRESS. This project is at the very beginning**

Overview
--------

This is a sample that showcases the features of BlockCypher's REST APIs. The application uses the SDKs provided by BlockCypher.
It is a Bitcoin wallet but also includes some Blockchain explorer features.

Pre-requisites
--------------

   * PHP 5.3.3+
   * curl, openssl PHP extensions
   * [Composer](http://getcomposer.org/download/) for installing the Rest API SDK.
	
Running the app
---------------

   * It´s a symfony app so you can use official [Symfony documentation](http://symfony.com/doc/current/book/installation.html)    
   * Copy the php-wallet-sample folder to your htdocs folder.
   * Run 'composer update' from the root directory.
   * Optionally, update *app/config/parameters.yml* with your own API token.
   * You are ready. Bring up http://localhost/php-wallet-sample on your favorite browser.
   
   > **Notice: Only this urls work for the time being:**
   
   * http://localhost/php-wallet-sample/app_dev.php
   * http://localhost/php-wallet-sample/app_dev.php/explorer/btc/address/1DEP8i3QJCsomS4BSMY2RpU1upv62aGvhD/
   * http://localhost/php-wallet-sample/app_dev.php/explorer/btc/302013/
	
References
----------

   * Github repository for PHP REST API SDK - [https://github.com/blockcypher/php-client](https://github.com/blockcypher/php-client)
   
TODO
----

   * Migrate a lot of django templates for the AppExplorer (not a priority)
   * Implement AppWallet. It is the main purpose of this sample.
   * User registration/login
   * Get API token from parameters.yml
   * Add behat, phpspec, tests, ...
   * ...

Upcoming features
-----------------

   * Extract common code to independent package (make some parts reusable to another Symfony or XXX framework projects)
   * Extract common code to independent Symfony bundle (make some parts reusable to another Symfony projects)