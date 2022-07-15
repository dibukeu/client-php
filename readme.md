# Dibuk Client Lib (PHP)

[![Build Status](https://travis-ci.com/dibukeu/client-php.svg?branch=master)](https://travis-ci.com/dibukeu/client-php)

## Installation

`composer require dibukeu/client-php`

## Usage

### Terms

* **Seller** - klient (predajca koncovému zákazníkovi, eshop)
* **User** - koncový zákazník (kupujúci z eshopu)
* **Order** - objednávka

### Public Methods

* `$client = new DibukClient([ 'sellerId' => 'string', 'signature' => 'string', 'url' => 'string', 'version' => 'string']);`
* `$client->setUser(['id' => 'int', 'email' => 'string', 'name' => 'string', 'surname' => 'string']);` - nastavenie údajov koncového zákazníka
* `$client->setItem([ 'id' => 'id polozky z exportu / ISBN','order_id' => 'id objednavky zakaznika','payment_id' => 'int','price' => 'float','currency' => 'EUR|CZK','unique_id' => 'int - Unikátny identifikátor, napr. kombinácia order_id/item, alebo orders_items.id']);` - nastavenie údajov kupovanej položky
* `$client->createLicense()` - kúpa knihy (buy v Dibuk)
* `$client->sendByEmail($email)` - odoslanie eknihy emailom
* `$client->getAllDownloadLinks()` - zoznam linkov na stiahnutie
* `$client->getAttachmentsLinks()` - zoznam linkov na stiahnutie priloh k e-kniham
* _showAllDownloadLinks - netestované_ - zobrazí HTML page s linkami na stiahnutie (možnosť nastaviť vlastný template)
* _exportItems - netestované_ - vrati zoznam položiek katalógu (eknihy a audioknihy)
* _exportCatalog - netestované_ - vrati zoznam kategórii katalógu
* getReport - ??

### Dibuk API Urls

**Production**

`https://agregator.dibuk.eu/2_3/call.php`

**Sandbox**

`https://sandbox.dibuk.eu/agregator/2_3/call.php`

## Examples

//TODO
