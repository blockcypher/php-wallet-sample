BlockCypher REST API PHP Sample
===============================

[![Build Status](https://travis-ci.org/blockcypher/php-wallet-sample.svg)](https://travis-ci.org/blockcypher/php-wallet-sample)

> **WARNING: WORK IN PROGRESS**

Overview
--------

This is a sample that showcases the features of BlockCypher's REST APIs. The application uses the SDKs provided by BlockCypher.
It is a Bitcoin wallet.

Pre-requisites
--------------

   * PHP 5.4+
   * curl, openssl PHP extensions
   * [Composer](http://getcomposer.org/download/) for installing the Rest API SDK.

Running the app
---------------

   * It´s a symfony app so you can use official [Symfony documentation](http://symfony.com/doc/current/book/installation.html)    
   * Copy the php-wallet-sample folder to your htdocs folder.
   * Run 'composer install' from the root directory.
   * You are ready. Bring up http://localhost/php-wallet-sample on your favorite browser.

References
----------

   * GitHub repository for PHP REST API SDK - [https://github.com/blockcypher/php-client](https://github.com/blockcypher/php-client)
   
TODO
----

   * Login using BlockCypher API token.
   * Validate address in "Send Funds".
   * Add edit pages for wallets, address, txs.
   * Client-side signing.
   * Support for multisign addresses.
   * Console commands for basic app commands: create wallet, address and transaction.
   * Add behat, phpspec, tests, ...
   * ...

Upcoming features
-----------------

   * Extract common code to independent package (make some parts reusable to another Symfony or XXX framework projects)
   * Extract common code to independent Symfony bundle (make some parts reusable to another Symfony projects)