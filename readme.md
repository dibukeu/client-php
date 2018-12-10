* názvoslovie - subjekty
     * **Seller** - klient (predajca koncovému zákazníkovi, eshop)
     * **User** - koncový zákazník (kupujúci z eshopu)
     * **Order** - objednávka
* Akcie / postup
    * `$client = new DibukClient([ 'sellerId' => 'string', 'signature' => 'string', 'sandbox' => true]);`
    * `$client->setUser(['id' => 'int', 'email' => 'string', 'name' => 'string', 'surname' => 'string']);` - nastavenie údajov koncového zákazníka
    * `$client->setItem([ 'id' => 'id polozky z exportu / ISBN','order_id' => 'id objednavky zakaznika','payment_id' => 'int','price' => 'float','currency' => 'EUR|CZK','unique_id' => 'int - Unikátny identifikátor, napr. kombinácia order_id/item, alebo orders_items.id']);` - nastavenie údajov kupovanej položky
    * `$client->createLicense()` - kúpa knihy (buy v Dibuk)
    * `$client->sendByEmail($email)` - odoslanie eknihy emailom
    * `$client->getAllDownloadLinks()` - zoznam linkov na stiahnutie
    * _showAllDownloadLinks - netestované_ - zobrazí HTML page s linkami na stiahnutie (možnosť nastaviť vlastný template)
    * _exportItems - netestované_ - vrati zoznam položiek katalógu (eknihy a audioknihy)
    * _exportCatalog - netestované_ - vrati zoznam kategórii katalógu
    * getReport - ??
    
    